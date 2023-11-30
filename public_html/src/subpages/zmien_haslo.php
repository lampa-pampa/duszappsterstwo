<?php
    if(!defined("INDEX"))
        exit;

    function check_password_change($fields)
    {      
        foreach($fields as $field_value)
        {
            if(empty($field_value))
                return;
        }
      
        if($fields["new_password"] != $fields["repeat_new_password"])
            return;
      
        if($fields["old_password"] == $fields["new_password"])
            return;

        if(strlen($fields["new_password"]) < $GLOBALS["config"]["value_lengths"]["password"]["min"] ||
        strlen($fields["new_password"]) > $GLOBALS["config"]["value_lengths"]["password"]["max"])
                return;
        
        $login = unserialize($_SESSION['user_data'])['login'];
        $hashed_old_pass = hash("sha512", $fields["old_password"]);
        $prep = $GLOBALS["connect"]->prepare("SELECT * FROM users WHERE login = ? AND password = ?");
        $prep->bind_param("ss", $login, $hashed_old_pass);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        if(mysqli_num_rows($result) == 0)
        {
            $_SESSION["cache"] = $GLOBALS["general_data"]["alerts"]["wrong_password"];
            return;
        }

        $hashed_pass = hash("sha512", $fields["new_password"]);
        $prep = $GLOBALS["connect"]->prepare("UPDATE users SET password = ? WHERE login = ?");
        $prep->bind_param("ss", $hashed_pass, $login);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        $_SESSION["cache"] = $GLOBALS["general_data"]["alerts"]["password_changed"];
    }

    if(isset($_POST['send']))
    {
        $fields = get_post(array("old_password", "new_password", "repeat_new_password"));
        check_password_change($fields);
    }
  
    function html_page($data)
    {
        $alert = "";
        $field_class = "";
        if(!empty($_SESSION["cache"]))
        {
            $alert = $_SESSION["cache"];
            if($alert == $GLOBALS["general_data"]["alerts"]["wrong_password"])
                $field_class = "error";
        }
        return
        '
            <section class="window">
                ' . create_js_object("password_length", $GLOBALS["config"]["value_lengths"]["password"], array("min", "max")) . '
                ' . create_js_object("alerts", $GLOBALS["general_data"]["alerts"],
                array("empty_field", "different_passwords", "same_passwords", "too_short_password", "too_long_password")) . '
                <span class="window-title">' . $data["title"] . '</span>
                <div>
                    <form method="post">
                        <input type="password" name="old_password" required class="text-input old_password validated-input password-input ' . $field_class . '" placeholder="' . $data["old_password_placeholder"] . '">
                        <input type="password" name="new_password" required class="text-input new_password validated-input password-input" placeholder="' . $data["new_password_placeholder"] . '">
                        <input type="password" name="repeat_new_password" required class="text-input repeat_new_password validated-input password-input" placeholder="' . $data["repeat_new_password_placeholder"] . '">
                        <input type="submit" tabindex="-1" name="send" class="send hidden-submit">
                    </form>
                    <button class="btn validate-btn">' . $data["change_password_btn_value"] . '</button>
                </div>
                <div class="alert">' . $alert . '</div>
            </section>  
        ';
    };
?>