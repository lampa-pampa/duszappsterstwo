<?php
    if(!defined("INDEX"))
        exit;
  
    $user_data = unserialize($_SESSION["user_data"]);
    if($user_data["can_manage_events"] != 1)
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
  
    function update_event($fields)
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_events"] != 1)
            return;
        foreach($fields as $field_value)
        {
            if(empty($field_value))
                return;
        }
      
        $field_names = array_flip($fields);
        foreach($field_names as $field_name)
        {
            if(strlen($fields[$field_name]) < $GLOBALS["config"]["value_lengths"]["event_$field_name"]["min"] ||
            strlen($fields[$field_name]) > $GLOBALS["config"]["value_lengths"]["event_$field_name"]["max"])
                return;
        }
        $cur_date = get_SQL_date();
        $prep = $GLOBALS["connect"]->prepare("UPDATE day_events SET title = ?, content = ? WHERE date = ? AND title = ?");
        $prep->bind_param("ssss", $fields["title"], $fields["content"], $cur_date, $_GET["event_title"]);
        $prep->execute();
        $prep->close();
        $_SESSION["cache"] = $GLOBALS["general_data"]["alerts"]["updated_event"];
        Header("Location: " . create_href($GLOBALS["config"]["edit_event_page"], $_GET["year"], $_GET["month"], $_GET["day"]) . "&event_title=" . $fields["title"]);
        exit;
    }
  
    function read_event()
    {
        $prep = $GLOBALS["connect"]->prepare("SELECT title, content FROM day_events WHERE DATE = ? AND title = ?");
        $prep->bind_param("ss", get_SQL_date(), $_GET["event_title"]);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        $arr = $result->fetch_assoc();
        return array("title" => $arr["title"], "content" => $arr["content"]);
    }
  
    if(isset($_POST['send']))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_events"])
        {
            $fields = get_post(array("title", "content"));
            update_event($fields);
        }
    }

    function html_page($data)
    {
        $alert = "";
        $event = read_event();
        $title_value = $event["title"];
        $content_value = $event["content"];
        if(!empty($_SESSION["cache"]))
            $alert = $_SESSION["cache"];
      
        return
        '
            <section class="window">
                ' . create_js_object("title_length", $GLOBALS["config"]["value_lengths"]["event_title"], array("min", "max")) . '
                ' . create_js_object("content_length", $GLOBALS["config"]["value_lengths"]["event_content"], array("min", "max")) . '
                ' . create_js_object("alerts", $GLOBALS["general_data"]["alerts"],
                array("empty_field", "too_short_title", "too_long_title", "too_short_content", "too_long_content")) . '
                <span class="window-title">
                    ' . $data["title"] . '
                    <div class="colored-text">' . create_full_day_name($_GET["year"], $_GET["month"], $_GET["day"]) . '</div>
                    <div class="colored-text">' . $_GET["event_title"] . '</div>
                </span>
                <div>
                    <form method="post">
                        <input name="title" value="' . $title_value . '" type="text" class="colored-text day-event-title text-input validated-input" placeholder="' . $data["event_title_placeholder"] . '">
                        <textarea name="content" rows="10" class="day-event-content text-input validated-input" placeholder="'
                        . $data["event_content_placeholder"] . '" autocapitalize="none" autocorrect="off" spellcheck="false">' . $content_value . '</textarea>
                        <input type="submit" tabindex="-1" class="hidden-submit send" name="send">
                    </form>
                    <div class="button-box">
                        <a class="btn loading-btn" href="' . create_href($GLOBALS["config"]["events_page"], $_GET["year"], $_GET["month"], $_GET["day"]) . '">' . $data["back_btn_value"] . '</a>
                        <button class="btn validate-btn">' . $data["update_event_btn_value"] . '</button>
                    </div>
                </div>
                <div class="alert">' . $alert . '</div>
            </section>
        ';
    };
?>