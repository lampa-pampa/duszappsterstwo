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
  
    function add_event($fields)
    {
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
        $prep = $GLOBALS["connect"]->prepare("SELECT * FROM day_events WHERE date = ? AND title = ?");
        $prep->bind_param("ss", $cur_date, $fields["title"]);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        if(mysqli_num_rows($result) > 0)
        {
            $_SESSION["cache"] = serialize(array("alert" => $GLOBALS["general_data"]["alerts"]["event_title_taken"], "fields" => $fields));
        }
        else
        {
            $prep = $GLOBALS["connect"]->prepare("INSERT INTO day_events VALUES(?, ?, ?)");
            $prep->bind_param("sss", $cur_date, $fields["title"], $fields["content"]);
            $prep->execute();
            $result = $prep->get_result();
            $prep->close();
            $_SESSION["cache"] = serialize(array("alert" => $GLOBALS["general_data"]["alerts"]["added_event"], "fields" => array("title" => "", "content" => "")));
        }
    }
  
    if(isset($_POST['send']))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_events"])
        {
            $fields = get_post(array("title", "content"));
            add_event($fields);
        }
    }

    function html_page($data)
    {
        $alert = "";
        $title_value = "";
        $content_value = "";
        if(!empty($_SESSION["cache"]))
        {
            $cache_data = unserialize($_SESSION["cache"]);
            $alert = $cache_data["alert"];
            $title_value = $cache_data["fields"]["title"];
            $content_value = $cache_data["fields"]["content"];
        }
      
        return
        '
            <section class="window">
                ' . create_js_object("title_length", $GLOBALS["config"]["value_lengths"]["event_title"], array("min", "max")) . '
                ' . create_js_object("content_length", $GLOBALS["config"]["value_lengths"]["event_content"], array("min", "max")) . '
                ' . create_js_object("alerts", $GLOBALS["general_data"]["alerts"],
                array("empty_field", "too_short_title", "too_long_title", "too_short_content", "too_long_content")) . '
                <span class="window-title">' . $data["title"] . '
                    <div class="colored-text">' . create_full_day_name($_GET["year"], $_GET["month"], $_GET["day"]) . '</div>
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
                        <button class="btn validate-btn">' . $data["add_event_btn_value"] . '</button>
                    </div>
                </div>
                <div class="alert">' . $alert . '</div>
            </section>
        ';
    };
?>