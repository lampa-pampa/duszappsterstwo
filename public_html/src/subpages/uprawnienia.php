<?php
    if(!defined("INDEX"))
        exit;
    
    function get_grants($user_data)
    {
        $value_to_checked = array(true => "checked", false => "");
        $html = '<div class="grants-list">';
        foreach($GLOBALS["config"]["grants"] as $grant)
            $html .= '<span>' . $GLOBALS["general_data"]["grant_labels"][$grant] . '</span><label class="toggle-box"><input type="checkbox"  class="toggle-input disabled" disabled ' . $value_to_checked[$user_data[$grant]] . '><div class="toggle"></div></label>';
        $html .= '</div>';
        return $html;
    }
  
    function html_page($data)
    {
        $user_data = unserialize($_SESSION["user_data"]);
        return
        '
            <section class="window  small-window">
                <span class="window-title">' . $data["title"] . '<div class="colored-text">' . $user_data["login"] . '</div></span>
                ' . get_grants($user_data) . '
            </section>  
        ';
    };
?>