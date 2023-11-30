<?php
    if(!defined("INDEX"))
        exit;
  
    if(!isset_year() || !isset_month())
    {
        Header("Location: ?page=" . $GLOBALS["config"]["home_page"]);
        exit;
    }
  
    if(!isset_day())
    {
        Header("Location: ?page=" . $GLOBALS["config"]["home_page"]);
        exit;
    }

    function delete_event($fields)
    {
        $prep = $GLOBALS["connect"]->prepare("DELETE FROM participants WHERE event_date = ? AND event_title = ?");
        $prep->bind_param("ss", get_SQL_date(), $fields["event_title"]);
        $prep->execute();
        $prep = $GLOBALS["connect"]->prepare("DELETE FROM day_events WHERE date = ? AND title = ?");
        $prep->bind_param("ss", get_SQL_date(), $fields["event_title"]);
        $prep->execute();
        $prep->close();
    }
  
    if(isset($_POST["delete_event"]))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_events"] != 1)
            return;
        $fields = get_post(array("event_title"));
        delete_event($fields);
    }

    function delete_participation($fields, $user_data)
    {
        $prep = $GLOBALS["connect"]->prepare("DELETE FROM participants WHERE user_id = ? AND event_date = ? AND event_title = ?");
        $prep->bind_param("iss", $user_data["user_id"], get_SQL_date(), $fields["event_title"]);
        $prep->execute();
        $prep->close();
    }
  
    if(isset($_POST["delete_participation"]))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_participate_in_events"] != 1)
            return;
        $fields = get_post(array("event_title"));
        delete_participation($fields, $user_data);
    }

    function get_day_events($data, $user_data)
    {
        $cur_date = get_SQL_date();
        $result = $GLOBALS["connect"]->query("SELECT * FROM day_events WHERE date = '" . $cur_date . "'");
        if(mysqli_num_rows($result) == 0)
            return array("html" => '<span class="line">' . $GLOBALS["general_data"]["alerts"]["empty_day_events"] . '</span>', "small_window" => true);
        $html = "";
        while($arr = $result->fetch_assoc())
        {
            $html .=
            '
                <div class="day-event">
                    <input class="day-event-title text-input colored-text" value="' . $arr["title"] . '" readonly disabled>
                    <pre class="text-input validated-input day-event-content">' . $arr["content"] . '</pre>
            ';
            if($user_data["can_manage_events"])
            {
                $html .=
                '
                    <div class="button-box margin-bottom">
                        <a class="btn loading-btn" href="' . create_href($GLOBALS["config"]["edit_event_page"], $_GET["year"], $_GET["month"], $_GET["day"]) . '&event_title=' . $arr["title"] . '">' . $data["edit_event_btn_value"] . '</a>
                        <button class="btn loading-btn" onclick="delete_event(' . "'" . $arr["title"] . "'" . ')">' . $data["delete_event_btn_value"] . '</button>
                    </div>
                ';
            }
            if($user_data["can_participate_in_events"])
            {
                $html .=
                '
                    <div class="button-box margin-bottom one-column">
                        <a class="btn loading-btn" href="' . create_href($GLOBALS["config"]["participate_in_event_page"], $_GET["year"], $_GET["month"], $_GET["day"]) . '&event_title=' . $arr["title"] . '">' . $data["participate_in_event_btn_value"] . '</a>
                    </div>
                ';
            }
            $result2 = $GLOBALS["connect"]->query("SELECT users.user_id, users.login, participants.message FROM participants
                JOIN users ON participants.user_id = users.user_id
                WHERE event_date = '" . $cur_date . "' AND event_title = '" . $arr["title"] . "'");
            while($arr2 = $result2->fetch_assoc())
            {
                $html .=
                '
                    <div class="participation-box">
                        <div class="colored-text">' . $arr2["login"] . '</div>
                        <div class="participation-message">' . $arr2["message"] . '</div>
                ';
                if($arr2["user_id"] == $user_data["user_id"])
                {
                    $html .= '<button class="btn loading-btn" onclick="delete_participation(' . "'" . $arr["title"] . "'" . ')">' . $data["delete_participation_btn_value"] . '</button>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';   
        }
        return array("html" => $html, "small_window" => false);
    }
    
    function html_page($data)
    {
        $add_event_btn = "";
        $small_window = "";
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_events"] == 1)
            $add_event_btn = '<a class="btn loading-btn" href="' . create_href($GLOBALS["config"]["add_event_page"], $_GET["year"], $_GET["month"], $_GET["day"]) . '">' . $data["add_event_btn_value"] . '</a>';
        $day_events_data = get_day_events($data, $user_data);
        $day_events = $day_events_data["html"];
        if($day_events_data["small_window"])
            $small_window = "small-window";
        return
        '
            <section class="window ' . $small_window . '">
                <span class="window-title">' . $data["title"] . '
                    <div class="colored-text">' . create_full_day_name($_GET["year"], $_GET["month"], $_GET["day"]) . '</div>
                </span>
                <div class="button-box one-column margin-bottom-end">
                    ' . $add_event_btn . '
                    <a class="btn loading-btn" href="?page=' . $GLOBALS["config"]["home_page"] . '">' . $data["back_btn_value"] . '</a>
                </div>
                ' . $day_events . '
                <form method="post">
                    <input type="hidden" name="event_title">
                    <input type="submit" tabindex="-1" name="delete_event" class="hidden-submit">
                    <input type="submit" tabindex="-1" name="delete_participation" class="hidden-submit">
                </form>
            </section>
        ';
    };
?>