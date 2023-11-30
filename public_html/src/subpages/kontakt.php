<?php
    if(!defined("INDEX"))
        exit;
    
  
    function get_mails($mails)
    {
        if(empty($mails))
            return "";
        $html = "";
        $labels = array_flip($mails);
        foreach($mails as $mail)
            $html .= '<span class="line">' . $labels[$mail] . '<a href="mailto: ' . $mail . '" class="link-btn inline colored-text">' . $mail . '</a></span>';
        return $html;
    }
    
    function html_page($data)
    {
        return
        '
            <section class="window small-window">
                <span class="window-title">' . $data["title"] . '</span>
                ' . get_mails($data["mails"]) . '
            </section>
        ';
    };
?>