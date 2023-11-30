<?php
    if (!defined('INDEX'))
        exit;
  
    $config = array
    (
        "value_lengths" => array
        (
            "password" => array("min" => 5, "max" => 64),
            "login" => array("min" => 1, "max" => 24),
            "event_title" => array("min" => 1, "max" => 24),
            "event_content" => array("min" => 1, "max" => 16384),
            "participation_message" => array("min" => 1, "max" => 24),
        ),
        
        "default_password_length" => 16,
        "year_range" => 10,
      
        "subpages_dir" => "src/subpages/",
        "styles_dir" => "src/css/",
        "scripts_dir" => "src/scripts/",
        
        "icon_file" => "src/img/icon.png",
        "logo_file" => "src/img/logo.png",
        "error_404_file" => "src/error_404.php",
        "html_template_file" => "src/html_template.php",
        "html_data_file" => "src/html_data.php",
        "connect_file" => "src/connect.php",
        "fade_in_animation_file" => "fade_in_animation.css",
        
        "grants" => array
        (
            "can_participate_in_events",
            "can_manage_events",
            "can_manage_users",
        ),
        "default_grant_values" =>  array
        (
            "can_participate_in_events" => true,
            "can_manage_events" => false,
            "can_manage_users" => false,        
        ),
      
        "theme_properties" => array
        (
            "--main-theme-color",
            "--gradient-bg-color",
        ),
      
        "home_page" => "kalendarz",
        "login_page" => "zaloguj",
        "users_page" => "uzytkownicy",
        "new_password_page" => "nowe_haslo",
        "add_user_page" => "dodaj_uzytkownika",
        "events_page" => "wydarzenia",
        "add_event_page" => "dodaj_wydarzenie",
        "edit_event_page" => "edytuj_wydarzenie",
        "participate_in_event_page" => "zglos_uczestnictwo",
      
        "scripts" => array
        (
            "default" => array
            (
                "listener_setter.js",
                "theme_changer.js",
                "loading_animation.js",
                "easter_egg.js",
            ),
            "zaloguj" => array("validator.js", "zaloguj.js", "show_password.js"),
            "uzytkownicy" => array("scroll_restorer.js", "uzytkownicy.js", "search.js"),
            "dodaj_uzytkownika" => array("dodaj_uzytkownika.js", "validator.js", "show_password.js", "copy_to_clipboard.js", "password_generator.js"),
            "zmien_haslo" => array("validator.js", "zmien_haslo.js", "show_password.js"),
            "nowe_haslo" => array("copy_to_clipboard.js"),
            "wydarzenia" => array("scroll_restorer.js", "validator.js", "wydarzenia.js"),
            "dodaj_wydarzenie" => array("validator.js", "dodaj_wydarzenie.js"),
            "edytuj_wydarzenie" => array("validator.js", "dodaj_wydarzenie.js"),
            "zglos_uczestnictwo" => array("validator.js", "zglos_uczestnictwo.js"),
        ),
        
        "styles" => array
        (
            "default" => array("main.css"),
            "kalendarz" => array("kalendarz.css"),
            "zaloguj" => array("zaloguj.css"),
            "uzytkownicy" => array("uzytkownicy.css"),
            "nowe_haslo" => array("nowe_haslo.css"),
            "wydarzenia" => array("wydarzenia1.css"),
            "dodaj_wydarzenie" => array("wydarzenia.css"),
            "edytuj_wydarzenie" => array("wydarzenia.css"),
        ),
        
        "protected_pages" => array
        (
            "kontakt",
            "uprawnienia",
            "zmien_haslo",
            "uzytkownicy",
            "kalendarz",   
            "wydarzenia",
            "dodaj_wydarzenie",
            "edytuj_wydarzenie",
            "nowe_haslo",
            "dodaj_uzytkownika",
            "zglos_uczestnictwo"
        ),
        
        "unprotected_pages" => array
        (
            "zaloguj",    
        ),
    );
?>