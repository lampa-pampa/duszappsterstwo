<?php
        if (!defined('INDEX'))
            exit;
        
        function get_styles($data, $dir)
        {
            if(empty($data))
                return "";
            $html = "";
            foreach($data as $style_name)
                $html .= '<link rel="stylesheet" type="text/css" href="' . $dir . $style_name . '">';
            return $html;
        }

        function get_scripts($data, $dir)
        {
            if(empty($data))
                return "";
            $html = "";
            foreach($data as $script_name)
                $html .= '<script src="' . $dir . $script_name . '" type="text/javascript"></script>';
            return $html;
        }

        function html_head($data, $new_page)
        {
            $default_styles = get_styles($GLOBALS["config"]["styles"]["default"], $GLOBALS["config"]["styles_dir"]);
            if($new_page)
                $default_styles .= get_styles(array($GLOBALS["config"]["fade_in_animation_file"]), $GLOBALS["config"]["styles_dir"]);
            $page_styles = "";
            if(isset($GLOBALS["config"]["styles"][$_SESSION["page"]]))
                $page_styles = get_styles($GLOBALS["config"]["styles"][$_SESSION["page"]], $GLOBALS["config"]["styles_dir"]);
            $default_scripts = get_scripts($GLOBALS["config"]["scripts"]["default"], $GLOBALS["config"]["scripts_dir"]);
            $page_scripts = "";
            if(isset($GLOBALS["config"]["scripts"][$_SESSION["page"]]))
                $page_scripts = get_scripts($GLOBALS["config"]["scripts"][$_SESSION["page"]], $GLOBALS["config"]["scripts_dir"]);
            return
                '
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name= "viewport" content="width=device-width, user-scalable=no">
                        <link rel="icon" href="' . $GLOBALS["config"]["icon_file"] . '">
                        ' . create_js_array("css_properties", $GLOBALS["config"]["theme_properties"]) . '
                        ' . $default_styles . '
                        ' . $page_styles . '
                        ' . $default_scripts . '
                        ' . $page_scripts . '
                        <link rel= "manifest" href="manifest.json">
                        <script src="service-worker.js"></script>
                        <title>' . $data["title"] . '</title>
                    </head>
                ';
        }

        function get_nav_links($data)
        {
            $html = "";
            foreach($data as $link)
            {
                $colored_class = "";
                if(in_array($_SESSION["page"], $link["active_subpages"]))
                    $colored_class = "selected-nav-btn";
                $html .= '<li><a href="?page=' . $link["href"] . '" class="link-btn loading-screen-btn ' . $colored_class . '" tabindex="-1">' . $link["value"] . '</a></li>';
            }
            return $html;
        }

        function get_account_links($data)
        {
            $html = "";
            foreach($data as $link)
            {
                $colored_class = "";
                if(in_array($_SESSION["page"], $link["active_subpages"]))
                    $colored_class = "selected-nav-btn";
                    $html .= '<a href="?page=' . $link["href"] . '" class="list-item link-btn loading-screen-btn ' . $colored_class . '" tabindex="-1">' . $link["value"] . '</a>';
            }
            return $html;
        }

        function get_theme_buttons($data)
        {
            $html = "";
            foreach($data as $theme)
            {
                $style = "";
                $theme_color_names = array_flip($theme);
                foreach($theme as $theme_color)
                    $style .= $theme_color_names[$theme_color] . ": " . $theme_color . "; ";
                $html .= '<div class="list-item"><button class="theme-btn btn" style="' . $style . '" tabindex="-1"></button></div>';
            }
            return $html;
        }

        function protected_html_nav($data)
        {
            $account_btn_class = "";
            foreach($data["account_links"] as $link)
            {
                if(in_array($_SESSION["page"], $link["active_subpages"]))
                {
                    $account_btn_class = "selected-nav-btn";
                    break;
                }
            }
            return
            '
                    <body>
                        <span class="bg-gradient"></span>
                        <header>
                            <span class="nav-aligner"></span>
                            <span class="title">' . $data["title"] . '</span>
                            <input type="checkbox" class="nav-toggle" id="nav-toggle">
                            <label for="nav-toggle" class="nav-toggle-label">
                                <div class="nav-btn"></div>
                            </label>
                            <section class="menu">
                                <nav>
                                    <ul>
                                        ' . get_nav_links($data["nav_links"], $_SESSION["page"]) . '
                                        <li class="list-box">
                                            <button class="list-opener link-btn ' . $account_btn_class . '" tabindex="-1">' . $data["account_btn_value"] . '</button>
                                            <div class="list account-list" style="--list-elements: ' . (1 + sizeof($data["account_links"])) . '">
                                                ' . get_account_links($data["account_links"], $_SESSION["page"]) . '
                                                <form method="post"><input type="submit" class="list-item link-btn loading-screen-btn animate-whole-screen" tabindex="-1" name="logout" value="' . $data["logout_btn_value"] . '"></form>
                                            </div>
                                        </li>
                                    </ul>
                                </nav>
                                <div class="list-box">
                                    <button class="list-opener link-btn change-theme-btn" tabindex="-1">' . $data["theme_btn_value"] . '</button>
                                    <div class="list theme-list" style="--list-elements: ' . sizeof($data["themes"]) . '">
                                        ' . get_theme_buttons($data["themes"]) . '
                                    </div>
                                </div>
                            </section>
                        </header>
                        <main>
            ';
        }

        function unprotected_html_nav($data)
        {
            return
            '
                    <body>
                        <span class="bg-gradient"></span>
                        <main>
            ';
        }

        function get_footer_year($year)
        {
            $cur_year = date('Y');
            if($cur_year > $year)
                return "$year-$cur_year";
            else
                return $year;
        }

        function html_footer($data)
        {
            return
            '
                        </main>
                            <footer>
                                <i>' . substr($data["footer"], 0, $data["creation_year_index"]) .
                                get_footer_year($data['creation_year']) .
                                substr($data["footer"], ($data["creation_year_index"] - 1)). '</i>
                            </footer>
                    </body>
                </html>
            ';
        }
    ?>