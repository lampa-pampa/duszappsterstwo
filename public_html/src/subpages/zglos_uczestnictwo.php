<?php
    if(!defined("INDEX"))
        exit;
  
    $user_data = unserialize($_SESSION["user_data"]);
    if($user_data["can_participate_in_events"] != 1)
    {
        Header("Location: ?page=" . $GLOBALS["config"]["events_page"]);
        exit;
    }
  
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
  
    if(!isset_event_title())
    {
        Header("Location: " . create_href($GLOBALS["config"]["events_page"], $_GET["year"], $_GET["month"], $_GET["day"]));
        exit;
    }
  
    function participate($fields, $user_id)
    {
        if(empty($fields["participation_message"]))
            return;

        if(strlen($fields["participation_message"]) < $GLOBALS["config"]["value_lengths"]["participation_message"]["min"] ||
            strlen($fields["participation_message"]) > $GLOBALS["config"]["value_lengths"]["participation_message"]["max"])
                return;  

        $cur_date = get_SQL_date();
        $prep = $GLOBALS["connect"]->prepare("SELECT * FROM participants WHERE user_id = ? AND event_date = ? AND event_title = ?");
        $prep->bind_param("iss", $user_id, $cur_date, $_GET["event_title"]);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        if(mysqli_num_rows($result) > 0)
        {
            $_SESSION["cache"] = $GLOBALS["general_data"]["alerts"]["already_participate"];
            return;
        }
        $prep = $GLOBALS["connect"]->prepare("INSERT INTO participants VALUES(?, ?, ?, ?)");
        $prep->bind_param("isss", $user_id, $cur_date, $_GET["event_title"], $fields["participation_message"]);
        $prep->execute();
        $prep->close();
        $_SESSION["cache"] = $_SESSION["cache"] = $GLOBALS["general_data"]["alerts"]["participation_submited"];
    }
  
    if(isset($_POST['send']))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_participate_in_events"])
        {
            $fields = get_post(array("participation_message"));
            participate($fields, $user_data["user_id"]);
        }
    }

    function html_page($data)
    {
        $alert = "";
        if(!empty($_SESSION["cache"]))
            $alert = $_SESSION["cache"];
        return
        '
            <section class="window">
                ' . create_js_object("participation_message_length", $GLOBALS["config"]["value_lengths"]["participation_message"], array("min", "max")) . '
                ' . create_js_object("alerts", $GLOBALS["general_data"]["alerts"],
                array("empty_field", "too_short_participation_message", "too_long_participation_message")) . '
                <span class="window-title">' . $data["title"] . '
                    <div class="colored-text">' . create_full_day_name($_GET["year"], $_GET["month"], $_GET["day"]) . '</div>
                    <div class="colored-text">' . $_GET["event_title"] . '</div>
                </span>
                <div>
                    <form method="post">
                        <input name="participation_message" type="text" class="day-event-title text-input validated-input participation-message" placeholder="' . $data["participation_message_placeholder"] . '">
                        <input type="submit" tabindex="-1" class="hidden-submit send" name="send">
                    </form>
                    <div class="button-box">
                        <a class="btn loading-btn" href="' . create_href($GLOBALS["config"]["events_page"], $_GET["year"], $_GET["month"], $_GET["day"]) . '">' . $data["back_btn_value"] . '</a>
                        <button class="btn validate-btn">' . $data["participate_btn_value"] . '</button>
                    </div>
                </div>
                <div class="alert">' . $alert . '</div>
            </section>
        ';
    };
?>