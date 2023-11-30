<?php
    if(!defined("INDEX"))
        exit;
  
    function check_login($fields)
    {      
        foreach($fields as $field_value)
        {
            if(empty($field_value))
                return;
        }
        
        $hashed_pass = hash("sha512", $fields["password"]);
        $prep = $GLOBALS["connect"]->prepare("SELECT login FROM users WHERE login = ? AND password = ?");
        $prep->bind_param("ss", $fields["login"], $hashed_pass);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        if(mysqli_num_rows($result) == 0)
        {
            $_SESSION["cache"] = $GLOBALS["general_data"]["alerts"]["wrong_login_or_password"];
            return;
        }
      
        $user_data = $result->fetch_assoc();
        $_SESSION["user_data"] = serialize($user_data);
        Header("Location: ?page=" . $GLOBALS["config"]["home_page"]);
    }

    if(isset($_POST['send']))
    {
        $fields = get_post(array("login", "password"));
        check_login($fields);
    }
    
    function html_page($data)
    {
        $alert = "";
        $fields_class = "";
        if(!empty($_SESSION["cache"]))
        {
            $alert = $_SESSION["cache"];
            if($alert == $GLOBALS["general_data"]["alerts"]["wrong_login_or_password"])
                $fields_class = "error";
        }
        return
        '
            <section class="window small-window">
                ' . create_js_object("alerts", $GLOBALS["general_data"]["alerts"], array("empty_field")) . '
                <span class="title">' . $data["title"] . '</span>
                <div>
                    <form method="post">
                        <div>
                            <input type="text" name="login" required class="text-input validated-input ' . $fields_class . '" placeholder="' . $data["login_placeholder"] . '">
                            <input type="password" name="password" required  class="text-input validated-input password-input ' . $fields_class . '" placeholder="' . $data["password_placeholder"] . '">
                            <input type="submit" tabindex="-1" name="send" class="hidden-submit send">
                        </div>        
                    </form>
                    <button class="btn validate-btn">' . $data["login_btn_value"] . '</button>
                </div>
                <div class="alert">' . $alert . '</div>
            </section>
        ';
    };
?>