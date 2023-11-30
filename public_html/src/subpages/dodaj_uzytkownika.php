<?php
    if(!defined("INDEX"))
        exit;
  
    $user_data = unserialize($_SESSION["user_data"]);
    if($user_data["can_manage_users"] != 1)
    {
        Header("Location: ?page=" . $GLOBALS["config"]["users_page"]);
        exit;
    }
  
    function add_user($fields, $grant_fields)
    {
        if(empty($fields["login"] || empty($fields["password"])))
            return;
        
        $field_names = array_flip($fields);
        foreach($field_names as $field_name)
        {
            if(strlen($fields[$field_name]) < $GLOBALS["config"]["value_lengths"][$field_name]["min"] ||
            strlen($fields[$field_name]) > $GLOBALS["config"]["value_lengths"][$field_name]["max"])
                return;
        }
        
        $prep = $GLOBALS["connect"]->prepare("SELECT * FROM users WHERE login = ?");
        $prep->bind_param("s", $fields["login"]);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        if(mysqli_num_rows($result) > 0)
        {
            $_SESSION["cache"] = $GLOBALS["general_data"]["alerts"]["login_taken"];
            return;
        }
      
        $field_names = array_flip($grant_fields);
        $grant_values = array();
        foreach($grant_fields as $field)
        {
            if($field == "on")
                array_push($grant_values, 1);
            else
                array_push($grant_values, 0);
        }
        
        $hashed_pass = hash("sha512", $fields["password"]);
        $chars = "";
        for($i = 0; $i < sizeof($grant_values); ++$i)
            $chars .= ", ?";
        $arg_types = "";
        for($i = 0; $i < sizeof($grant_values); ++$i)
            $arg_types .= "i";
        $prep = $GLOBALS["connect"]->prepare("INSERT INTO users (login, password, " . implode(", ", $GLOBALS["config"]["grants"]) . ") VALUES(?, ?" . $chars . ")");
        $prep->bind_param("ss" . $arg_types, $fields["login"], $hashed_pass, ...$grant_values);
        $prep->execute();
        $prep->close();
        $_SESSION["cache"] = $GLOBALS["general_data"]["alerts"]["added_user"];
    }
  
    if(isset($_POST['send']))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_users"])
        {
            $input_fields = get_post(array("login", "password"));
            $grant_fields = get_post($GLOBALS["config"]["grants"]);
            add_user($input_fields, $grant_fields);
        }
    }
  
    function get_grant_inputs()
    {
        $value_to_checked = array(true => "checked", false => "");
        $html = '<div class="grants-list">';
        foreach($GLOBALS["config"]["grants"] as $grant)
            $html .= '<span>' . $GLOBALS["general_data"]["grant_labels"][$grant] . '</span><label class="toggle-box"><input type="checkbox" name="' . $grant . '" class="toggle-input" ' . $value_to_checked[$GLOBALS["config"]["default_grant_values"][$grant]] . '><div class="toggle"></div></label>';
        $html .= '</div>';
        return $html;
    }

    function html_page($data)
    {
        $alert = "";
        if(!empty($_SESSION["cache"]))
            $alert = $_SESSION["cache"];
      
        return
        '
            <section class="window">
                ' . create_js_object("generator_data", $GLOBALS["config"], array("default_password_length")) . '
                ' . create_js_object("login_length", $GLOBALS["config"]["value_lengths"]["login"], array("min", "max")) . '
                ' . create_js_object("password_length", $GLOBALS["config"]["value_lengths"]["password"], array("min", "max")) . '
                ' . create_js_object("alerts", $GLOBALS["general_data"]["alerts"],
                array("empty_field", "too_short_login", "too_long_login", "too_short_password", "too_long_password")) . '
                <span class="window-title">' . $data["title"] . '</span>
                    <form method="post">
                        <div>                             
                            <input type="text" name="login" required class="text-input validated-input new-login" placeholder="' . $data["login_placeholder"] . '">
                            <input type="password" name="password" required class="text-input validated-input new-password password-input" placeholder="' . $data["password_placeholder"] . '">
                            <input type="submit" tabindex="-1" class="hidden-submit send" name="send">
                            <div class="button-box">
                                <button class="btn copy-btn" onclick="return false" field="new-password">' . $data["copy_btn_value"] . '</button>
                                <button class="btn generate-password-btn" onclick="return false">' . $data["generate_password_btn_value"] . '</button>
                            </div>
                            <div class="line"></div>                                                                               
                        </div>
                        ' . get_grant_inputs() . '
                    </form>    
                    <div class="button-box">
                        <a class="btn loading-btn" href="?page=' . $GLOBALS["config"]["users_page"] . '">' . $data["back_btn_value"] . '</a>
                        <button class="btn validate-btn">' . $data["add_user_btn_value"] . '</button>
                    </div>
                <div class="alert">' . $alert . '</div>
            </section>
        ';
    };
?>