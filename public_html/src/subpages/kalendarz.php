<?php
    if(!defined("INDEX"))
    exit;

    if(!isset_year() && !isset_month())
    {
        if(isset($_SESSION["calendar_data"]))
        {
            $calendar_data = unserialize($_SESSION["calendar_data"]);
            Header("Location: " . create_href($GLOBALS["config"]["home_page"], $calendar_data["cur_year"], $calendar_data["cur_month"]));
            exit;   
        }
        Header("Location: " . create_href($GLOBALS["config"]["home_page"], date("Y"), date("n")));
        exit;
    }
  
    if(!isset_year())
    {
        Header("Location: " . create_href($GLOBALS["config"]["home_page"], date("Y"), $_GET["month"]));
        exit;
    }

    if(!isset_month())
    {
        Header("Location: " . create_href($GLOBALS["config"]["home_page"], $_GET["year"], date("n")));
        exit;
    }

    update_calendar_data($_GET["year"], $_GET["month"]);

    function update_calendar_data($cur_year, $cur_month)
    {   
        $previous_month = ($cur_month + 10) % 12 + 1;
        $previous_year = $cur_year;
        if($previous_month != ($cur_month - 1))
            --$previous_year;

        $next_month = $cur_month % 12 + 1;
        $next_year = $cur_year;
        if($next_month != ($cur_month + 1))
            ++$next_year;

        $calendar_data = array();
        $calendar_data["cur_year"] = $cur_year;
        $calendar_data["cur_month"] = $cur_month;
        $calendar_data["previous_year"] = $previous_year;
        $calendar_data["previous_month"] = $previous_month;
        $calendar_data["next_year"] = $next_year;
        $calendar_data["next_month"] = $next_month;

        $_SESSION["calendar_data"] = serialize($calendar_data);
    }

    function create_selector_button($link, $points)
    {
        return '
            <a class="selector-btn" href="' . $link . '">
                <svg viewBox="0 0 8 16">
                    <polygon points="'.$points.'" class="selector-arrow">
                </svg>
            </a>
          ';
    }

    function create_selectors($calendar_data, $data)
    {
        $html = "";
        $button_points = array("left" => "0,8 8,16 8,0", "right" => "8,8 0,16 0,0");
        $html .=
        '
            <div class="selector month-selector">
                ' . create_selector_button(create_href($GLOBALS["config"]["home_page"], $calendar_data["previous_year"], $calendar_data["previous_month"]), $button_points["left"]) . '
                <div class="selector-value">' . $data["month_names"][$calendar_data["cur_month"]] . '</div>
                ' . create_selector_button(create_href($GLOBALS["config"]["home_page"], $calendar_data["next_year"], $calendar_data["next_month"]), $button_points["right"]) . '
            </div>
            <div class="selector year-selector">
                ' . create_selector_button(create_href($GLOBALS["config"]["home_page"], $calendar_data["cur_year"] - 1, $calendar_data["cur_month"]), $button_points["left"]) . '
                <div class="selector-value">' . $calendar_data["cur_year"] . '</div>
                ' . create_selector_button(create_href($GLOBALS["config"]["home_page"], $calendar_data["cur_year"] + 1, $calendar_data["cur_month"]), $button_points["right"]) . '
            </div>
        ';
        return $html;
    }

    function create_header($data)
    {
        $html = "";
        for($i = 1; $i <= 7; ++$i)
            $html .= '<div class="header"><span class="full-name">'.$data["day_names"][$i]["full"].
            '</span><span class="short-name">' . $data["day_names"][$i]["short"] . '</span></div>';
        return $html;
    }

    function create_fill_cells($start_day, $number_of_cells, $days_with_content)
    {
        $html = "";
        $day = $start_day;
        for($i = 0; $i < $number_of_cells; ++$i)
        {
            $class = "";
            if(in_array($day, $days_with_content))
                $class = "has-content";
            $html .= '<div class="cell btn filler '.$class.'">'.$day++.'</div>';  
        }
        return $html;
    }

    function create_main_cells($month, $year, $main_cells_number, $days_with_content, $week_day_number, $data)
    {
        $html = "";
        for($day = 1; $day <= $main_cells_number; ++$day)
        {
            $day_name = $data["day_names"][$week_day_number]["full"];
            $week_day_number = $week_day_number % 7 + 1;
            $class = "";
            if(in_array($day, $days_with_content))
                $class .= "has-content";
            if(date("Y-m-d") == date("Y-m-d", strtotime($year . "-" . $month . "-" . $day)))
                $class .= " cur-day";
            $html .= '<a title="' . create_full_day_name($year, $month, $day) . ' (' . $day_name . ')"
            href="' . create_href($GLOBALS["config"]["events_page"], $year, $month, $day) . '"
            class="cell btn loading-btn '.$class.'">'.$day.'</a>';
        }
        return $html;
    }
  
    function get_cells_with_content($month, $year)
    {
        $cells_with_content = array();
        $result = $GLOBALS["connect"]->query("SELECT DISTINCT DAY(date) AS day FROM day_events WHERE MONTH(date) = $month AND YEAR(date) = $year");
        while($arr = $result->fetch_assoc())
            array_push($cells_with_content, $arr['day']);
        return $cells_with_content;
    }

    function create_cells($calendar_data, $data)
    {
        $cur_month = $calendar_data["cur_month"];
        $cur_year = $calendar_data["cur_year"];
        $prefix_month = $calendar_data["previous_month"];
        $prefix_year = $calendar_data["previous_year"];
        $suffix_month = $calendar_data["next_month"];
        $suffix_year = $calendar_data["next_year"];
        $html = "";
      
        $first_day_string_date = strtotime('01-'.$cur_month.'-'.$cur_year);
        $first_day_name_number = date('w', $first_day_string_date);
        if($first_day_name_number == 0)
            $first_day_name_number = 7;
        
        $prefix_cells_number = $first_day_name_number - 1;
        if($prefix_cells_number == 0)
            $prefix_cells_number = 7;
        
        $prefix_cells_first_day = cal_days_in_month(CAL_GREGORIAN, $prefix_month, $prefix_year) - $prefix_cells_number + 1;
        $prefix_days_with_content = get_cells_with_content($prefix_month, $prefix_year);
        $html .= create_fill_cells($prefix_cells_first_day, $prefix_cells_number, $prefix_days_with_content);
        
        $main_cells_number = cal_days_in_month(CAL_GREGORIAN, $cur_month, $cur_year);
        $main_days_with_content = get_cells_with_content($cur_month, $cur_year);
        $html .= create_main_cells($cur_month, $cur_year, $main_cells_number, $main_days_with_content, $first_day_name_number, $data);
        
        $rows = 1;
        $main_cells_without_first_row_number = $main_cells_number - (7 - $prefix_cells_number);
        $rows += floor($main_cells_without_first_row_number / 7);
        if($main_cells_without_first_row_number % 7 != 0)
            ++$rows;

        $suffix_cells_number = 7 - (($prefix_cells_number + $main_cells_number) % 7);
        if($rows == 5 && $suffix_cells_number < 7)
            $suffix_cells_number += 7;

        $suffix_days_with_content = get_cells_with_content($suffix_month, $suffix_year);
        $html .= create_fill_cells(1, $suffix_cells_number, $suffix_days_with_content);
        
        return $html;
    }

    function create_calendar($data)
    {
        $calendar_data = unserialize($_SESSION["calendar_data"]);
        return create_selectors($calendar_data, $data) . create_header($data) . create_cells($calendar_data, $data);
    }
    
    function html_page($data)
    {
        return
        '
            <section class="window calendar">
                ' . create_calendar($data) . '
            </section>      
        ';
    };
?>