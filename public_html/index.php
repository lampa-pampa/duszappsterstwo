<?php
    function get_post($names)
    {
        $values_map = array();
        foreach($names as $element)
        {
            $values_map[$element] = "";
            if(!empty($_POST[$element]))
                $values_map[$element] = trim($_POST[$element]);    
        }
        return $values_map;
    }

    function create_js_object($object_name, $from, $elements)
    {
        $html = "<script> const " . $object_name . " = {";
        foreach($elements as $alert)
            $html .= "'" . $alert . "': '" . $from[$alert] . "', ";
        $html .= "}</script>";
        return $html;
    }
  
    function create_js_array($object_name, $elements)
    {
        $html = "<script> const " . $object_name . " = [";
        foreach($elements as $element)
            $html .= "'" . $element . "', ";
        $html .= "]</script>";
        return $html;
    }
  
    function create_full_day_name($year, $month, $day)
    {
        return sprintf("%02d", $day) . "-" . sprintf("%02d", $month) . "-" . $year;
    }
  
    function create_href($page, $year, $month, $day = 0)
    {
        if($day == 0)
            return "?page=" . $page . "&year=" . $year . "&month=" . $month;
        return "?page=" . $page . "&year=" . $year . "&month=" . $month . "&day=" . $day;
    }
  
    function get_SQL_date()
    {
        return $_GET["year"] . "-" . $_GET["month"] . "-" . $_GET["day"];
    }
  
    function isset_year()
    {
        $cur_year = date("Y");
        $year_range = $GLOBALS["config"]["year_range"];
        return (isset($_GET["year"]) && $_GET["year"] >= $cur_year - $year_range && $_GET["year"] <= $cur_year + $year_range &&
        strval($_GET["year"]) == strval(intval($_GET["year"])));
    }

    function isset_month()
    {
        return (isset($_GET["month"]) && $_GET["month"] >= 1 && $_GET["month"] <= 12
        && strval($_GET["month"]) == strval(intval($_GET["month"])));
    }
  
    function isset_day()
    {
        return (isset($_GET["day"]) && $_GET["day"] >= 1 && $_GET["day"] <= cal_days_in_month(CAL_GREGORIAN, $_GET["month"], $_GET["year"]));
    }
  
    function isset_event_title()
    {
        $prep = $GLOBALS["connect"]->prepare("SELECT * FROM day_events WHERE DATE  = ? AND title = ?");
        $prep->bind_param("ss", get_SQL_date(), $_GET["event_title"]);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        return mysqli_num_rows($result) != 0;
    }
  
    define("INDEX", "true");
    include("config.php");
  
    session_start();

    if(isset($_POST["logout"]))
    {
        session_destroy();
        Header("Location: ?page=" . $config["login_page"]);
        exit;
    }
  
    if(empty($_GET["page"]) && empty($_SESSION["page"]))
    {
        Header("Location: ?page=" . $config["home_page"]);
        exit;
    }
    
    if(!empty($_GET["page"]))
        $page = $_GET["page"];
    else
        $page = $_SESSION["page"];
  
    if(!in_array($page, $config["protected_pages"]) && !in_array($page, $config["unprotected_pages"]))
    {
        include($config["error_404_file"]);
        exit;
    }
  
    include($config["connect_file"]);
    if(empty($_SESSION["user_data"]))
    {
        if(!in_array($page, $config["unprotected_pages"]))
        {
            Header("Location: ?page=" . $config["login_page"]);
            exit;
        }
    }
    else
    {
        $login = unserialize($_SESSION["user_data"])["login"];
        $prep = $connect->prepare("SELECT user_id, login, " . implode(", ", $GLOBALS["config"]["grants"]) .", super_user FROM users WHERE login = ?");
        $prep->bind_param("s", $login);
        $prep->execute();
        $result = $prep->get_result();
        $prep->close();
        if(mysqli_num_rows($result) == 0  && !in_array($page, $config["unprotected_pages"]))
        {
            Header("Location: ?page=" . $config["login_page"]);
            session_destroy();
            exit;
        }
        if($page == $config["login_page"])
        {
            Header("Location: ?page=" . $config["home_page"]);
            exit;
        }
      
        $user_data = $result->fetch_assoc();
        if($user_data["super_user"])
        {
            foreach($GLOBALS["config"]["grants"] as $grant)
                $user_data[$grant] = 1;
        }
        $_SESSION["user_data"] = serialize($user_data);
    }
  
    include($config["html_data_file"]);
  
    include($config["subpages_dir"] . $page . ".php");

    if(empty($_GET["page"]))
    {
        Header("Location: ?page=" . $page);
        exit;
    }
  
    include($config["html_template_file"]);

    $new_page = empty($_SESSION["page"]) || $page != $_SESSION["page"];

    $_SESSION["page"] = $page;

    echo html_head($html_head_data, $new_page);
    if(in_array($page, $config["protected_pages"]))
        echo protected_html_nav($html_nav_data);
    else
        echo unprotected_html_nav($html_nav_data);
    echo html_page($html_page_data[$page]);
    echo html_footer($html_footer_data);


    $_SESSION['cache'] = "";
    $connect->close();  
?>