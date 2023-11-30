<?php
    if(!defined("INDEX"))
        exit;
  
    function update_grants($fields)
    {
        $prep = $GLOBALS["connect"]->prepare("SELECT * FROM users WHERE user_id = ? AND super_user != 1");
        $prep->bind_param("i", $fields["user_id"]);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        if(mysqli_num_rows($result) == 0)
            return;
        if(!in_array($fields["grant_name"], $GLOBALS["config"]["grants"]))
            return;
        $grant_value = 0;
        if($fields["grant_value"] == "true")
            $grant_value = 1;
        $GLOBALS["connect"]->query("UPDATE users SET " . $fields["grant_name"] . " = " . $grant_value . " WHERE user_id = " . $fields["user_id"]);
        
    }

    if(isset($_POST['set_grant']))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_users"])
        {
            $fields = get_post(array("user_id", "grant_name", "grant_value"));
            update_grants($fields);
        }
    }
  
    function delete_user($fields)
    {
        $prep = $GLOBALS["connect"]->prepare("DELETE FROM participants WHERE user_id IN (SELECT user_id FROM users WHERE user_id = ? AND super_user != 1)");
        $prep->bind_param("i", $fields["user_id"]);
        $prep->execute();
        $prep = $GLOBALS["connect"]->prepare("DELETE FROM users WHERE user_id = ? AND super_user != 1");
        $prep->bind_param("i", $fields["user_id"]);
        $prep->execute();
        $prep->close();
    }
  
    if(isset($_POST['delete_user']))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_users"])
        {
            $fields = get_post(array("user_id"));
            delete_user($fields);
        }
    }
  
    function reset_password($fields)
    {
        $prep = $GLOBALS["connect"]->prepare("SELECT login FROM users WHERE user_id = ? AND super_user != 1");
        $prep->bind_param("i", $fields["user_id"]);
        $prep->execute();
        $result = $prep->get_result();
        if(mysqli_num_rows($result) == 0)
            return;
        $prep->close();
        $_SESSION['cache'] = $fields["user_id"];
        Header("Location: ?page=" . $GLOBALS["config"]["new_password_page"]);
        exit;
    }
  
    if(isset($_POST['reset_password']))
    {
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_users"])
        {
            $fields = get_post(array("user_id"));
            reset_password($fields);
        }
    }
  
    function get_users_list($data)
    {
        $user_data = unserialize($_SESSION["user_data"]);
        $prep = $GLOBALS["connect"]->prepare("SELECT * FROM users WHERE super_user != 1 AND user_id != ? ORDER BY login");
        $prep->bind_param("i", $user_data["user_id"]);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        $html = "";
        if(mysqli_num_rows($result) == 0)
            return array("html" => '<div class="line">' . $GLOBALS["general_data"]["alerts"]["empty_users"] . '</div>', "small_window" => true);
        while($arr = $result->fetch_assoc())
        {
            $value_to_checked = array(true => "checked", false => "");
            $html .= '<span id="' . $arr["login"] . '" class="user search-item"><span class="text-input login colored-text">' . $arr["login"] . '</span><div class="grants-list"><div class="button-box"><button class="btn users-btn loading-btn" onclick="reset_password(' . $arr["user_id"] . ')">' . $data["reset_password_btn_value"] . '</button><button class="btn users-btn loading-btn" onclick="delete_user(' . $arr["user_id"] . ')">' . $data["delete_account_btn_value"] . '</button></div>';
            foreach($GLOBALS["config"]["grants"] as $grant)
                $html .= '<span>' . $GLOBALS["general_data"]["grant_labels"][$grant] . '</span><label class="toggle-box"><input type="checkbox" class="loading-toggle toggle-input" ' . $value_to_checked[$arr[$grant]] . ' onchange="set_grant(' . $arr["user_id"] . ', ' . "'" . $grant . "'" .', this)"><div class="toggle"></div></label>';
            $html .= '</div></span>';
        }
        return array("html" => $html, "small_window" => false);
    }

    function html_page($data)
    {
        $small_window = "small-window";
        $content = "";
        $alert = '<span class="alert">' . $GLOBALS["general_data"]["alerts"]["access_denied"] . '</span>';
        $user_data = unserialize($_SESSION["user_data"]);
        if($user_data["can_manage_users"] == 1)
        {
            $alert = "";
            $users_list_data = get_users_list($data);
            $users_list = $users_list_data["html"];
            if(!$users_list_data["small_window"])
                $small_window = "";
            $content = 
            '
                <div class="search-box">
                    <input type="text" class="text-input search-input" placeholder="' . $data["search_placeholder"] . '">
                    <a class="btn loading-btn" href="?page=' . $GLOBALS["config"]["add_user_page"] . '">' . $data["add_new_user_btn_value"] . '</a>
                </div>
                ' . $users_list . '
            ';
        }
        return
        '
            <section class="window ' . $small_window . '">
                ' . create_js_object("alerts", $GLOBALS["general_data"]["alerts"], array("empty_search_results")) . '
                <span class="window-title">' . $data["title"] . '</span>
                    ' . $content . '
                    ' . $alert . '
                <form method="post">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="grant_name">
                    <input type="hidden" name="grant_value">
                    <input type="submit" tabindex="-1" name="set_grant" class="hidden-submit">
                    <input type="submit" tabindex="-1" name="delete_user" class="hidden-submit">
                    <input type="submit" tabindex="-1" name="reset_password" class="hidden-submit">
                </form>
            </section>
        ';
    };
?>