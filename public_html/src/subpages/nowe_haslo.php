<?php
    
    if(!defined("INDEX"))
        exit;
  
    function generate_password()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = "";
        for ($i = 0; $i < $GLOBALS["config"]["default_password_length"]; ++$i)
            $password .= $chars[rand(0, strlen($chars) - 1)];
        return $password;
    }
  
    if(empty($_SESSION["cache"]) || $_SESSION["page"] != $GLOBALS["config"]["users_page"])
    {
        Header("Location: ?page=" . $GLOBALS["config"]["users_page"]);
        exit;
    }
  
    $new_password = generate_password();
    $hashed_pass = hash("sha512", $new_password);
    $prep = $GLOBALS["connect"]->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $prep->bind_param("ss", $hashed_pass, $_SESSION["cache"]);
    $prep->execute();
    $prep->close();
  
    $prep = $GLOBALS["connect"]->prepare("SELECT login FROM users WHERE user_id = ?");
    $prep->bind_param("s", $_SESSION["cache"]);
    $prep->execute();
    $result = $prep->get_result();
    $prep->close();
    $login = $result->fetch_assoc()["login"];

    function html_page($data)
    {
        $user_data = unserialize($_SESSION["user_data"]);
        return
        '
            <section class="window small-window">
                <span class="window-title">' . $data["title"] . '<div><span class="colored-text">' . $GLOBALS["login"] . '</span></div></span>
                <input type="text" readonly disabled class="colored-text text-input new-password" value="' . $GLOBALS["new_password"] . '">
                <div class="button-box">
                    <a class="btn loading-btn" href="?page=' . $GLOBALS["config"]["users_page"] . '">' . $data["back_btn_value"] . '</a>
                    <button class="btn copy-btn" field="new-password">' . $data["copy_btn_value"] . '</button>
                </div>
            </section>  
        ';
    };
?>