<?php
    if (!defined('INDEX'))
        exit;
  
    $general_data = array
    (
        "alerts" => array
         (
             "empty_field" => "Uzupełnij wszystkie pola",
             "too_short_password" => "Nowe hasło jest za krótkie",
             "too_long_password" => "Nowe hasło jest za długie",
             "same_passwords" => "Nowe hasło jest takie samo jak poprzednie",
             "different_passwords" => "Podane hasła nie są takie same",
             "too_short_login" => "login jest za krótki",
             "too_long_login" => "login jest za długi",
             "too_short_title" => "Tytuł wydarzenia jest za krótki",
             "too_long_title" => "Tytuł wydarzenia jest za długi",
             "too_short_content" => "Treść wydarzenia jest za krótka",
             "too_long_content" => "Treść wydarzenia jest za długa",
             "too_short_participation_message" => "Wiadomość za krótka",
             "too_long_participation_message" => "Wiadomość za długa",
             "already_participate" => "Już zgłosiłeś uczestnictwo",
             "participation_submited" => "<span class='correct'>Zgłoszono uczestnictwo</span>",
             "wrong_login_or_password" => "Błędny login lub hasło",
             "login_taken" => "Login zajęty",
             "added_user" => "<span class='correct'>Dodano użytkownika</span>",
             "event_title_taken" => "Tytuł zajęty",
             "added_event" => "<span class='correct'>Dodano wydarzenie</span>",
             "updated_event" => "<span class='correct'>Zapisano wydarzenie</span>",
             "wrong_password" => "Błędne hasło",
             "password_changed" => "<span class='correct'>Hasło zostało zmienione</span>",
             "access_denied" => "Odmowa dostępu - nie masz uprawnień",
             "empty_users" => "Pusta lista",
             "empty_day_events" => "Brak wydarzeń",
             "empty_search_results" => "Brak wyników",
         ),
         "grant_labels" => array
         (
             "can_participate_in_events" => "Uczestnictwo w wydarzeniach",
             "can_manage_events" => "Zarządzanie wydarzeniami",
             "can_manage_users" => "Zarządzanie użytkownikami",
         ),
    );
    $html_page_data = array
    (
        "zaloguj" => array
        (
            "title" => "Dusz<span class='colored-text'>app</span>sterstwo",
            "login_placeholder" => "Login",
            "password_placeholder" => "Hasło",
            "login_btn_value" => "Zaloguj",
        ),
        "kontakt" => array
        (
            "title" => "Kontakt",
            "mails" => array
            (
                "X Oskar (uprawnienia): " => "o.siwak@gmail.com",
                "Marek (problemy ze stroną): " => "marek.kandulski@proton.me",
            ),
        ),
        "uprawnienia" => array
        (
            "title" => "Uprawnienia konta",
        ),
        "zmien_haslo" => array
        (
            "title" => "Zmiana hasła",
            "old_password_placeholder" => "Stare hasło",
            "new_password_placeholder" => "Nowe hasło",
            "repeat_new_password_placeholder" => "Powtórz nowe hasło",
            "change_password_btn_value" => "Zmień hasło",
        ),
        "uzytkownicy" => array
        (
            "title" => "Użytkownicy",
            "search_placeholder" => "Szukaj",
            "delete_account_btn_value" => "Usuń",
            "reset_password_btn_value" => "Resetuj hasło",
            "add_new_user_btn_value" => "Dodaj nowego użytkownika",
        ),
        "dodaj_uzytkownika" => array
        (
            "title" => "Dodaj użytkownika",
            "login_placeholder" => "Login",
            "password_placeholder" => "Hasło",
            "copy_btn_value" => "Kopiuj",
            "generate_password_btn_value" => "Generuj hasło",
            "add_user_btn_value" => "Dodaj",
            "back_btn_value" => "Powrót",
        ),
        "nowe_haslo" => array
        (
            "title" => "Nowe hasło użytkownika",
            "back_btn_value" => "Powrót",
            "copy_btn_value" => "Kopiuj",
        ),
        "kalendarz" => array
        (
            "title" => "Kalendarz",
            "month_names" => array
            (
                1 => "Styczeń",
                2 => "Luty",
                3 => "Marzec",
                4 => "Kwiecień",
                5 => "Maj",
                6 => "Czerwiec",
                7 => "Lipiec",
                8 => "Sierpień",
                9 => "Wrzesień",
                10 => "Październik",
                11 => "Listopad",
                12 => "Grudzień",
            ),
            "day_names" => array
            (
                1 => array("short" => "Pon", "full" => "Poniedziałek"),
                2 => array("short" => "Wt", "full" => "Wtorek"),
                3 => array("short" => "Śr", "full" => "Środa"),
                4 => array("short" => "Czw", "full" => "Czwartek"),
                5 => array("short" => "Pt", "full" => "Piątek"),
                6 => array("short" => "Sob", "full" => "Sobota"),
                7 => array("short" => "Nie", "full" => "Niedziela"),
            ),
        ),
        "wydarzenia" => array
        (
            "title" => "Wydarzenia w dniu",
            "participate_in_event_btn_value" => "Zgłoś uczestnictwo",
            "delete_event_btn_value" => "Usuń",
            "edit_event_btn_value" => "Edytuj",
            "back_btn_value" => "Powrót do kalendarza",
            "add_event_btn_value" => "Dodaj wydarzenie",
            "delete_participation_btn_value" => "Anuluj uczestnictwo",
        ),
        "dodaj_wydarzenie" => array
        (
            "title" => "Dodaj wydarzenie w dniu",
            "event_title_placeholder" => "Tytuł",
            "event_content_placeholder" => "Treść",
            "back_btn_value" => "Powrót",
            "add_event_btn_value" => "Dodaj"
        ),
        "edytuj_wydarzenie" => array
        (
            "title" => "Edytuj wydarzenie",
            "event_title_placeholder" => "Tytuł",
            "event_content_placeholder" => "Treść",
            "back_btn_value" => "Powrót",
            "update_event_btn_value" => "Zapisz"
        ),
        "zglos_uczestnictwo" => array
        (
            "title" => "Zapisz się na wydarzenie",
            "participation_message_placeholder" => "Wiadomość",
            "back_btn_value" => "Powrót",
            "participate_btn_value" => "Zgłoś",
        ),
    );

    $html_head_data = array
    (
        "title" => "Duszappsterstwo",
    );

    $html_nav_data = array
    (
        "title" => "Dusz<span class='colored-text easter-egg-text' second-text='pa'>app</span>sterstwo",
        "nav_links" => array
        (
            array
            (
                "value" => "Kalendarz",
                "href" => "kalendarz",
                "active_subpages" => array ("kalendarz", "wydarzenia", "dodaj_wydarzenie", "zglos_uczestnictwo"),
            ),
            array
            (
                "value" => "Użytkownicy",
                "href" => "uzytkownicy",
                "active_subpages" => array ("uzytkownicy", "nowe_haslo", "dodaj_uzytkownika"),
            ),
            array
            (
                "value" => "Kontakt",
                "href" => "kontakt",
                "active_subpages" => array ("kontakt"),
            ),
        ),
        "account_btn_value" => "Konto",
        "account_links" => array
        (
            array
            (
                "value" => "Uprawnienia",
                "href" => "uprawnienia",
                "active_subpages" => array ("uprawnienia"),
            ),
            array
            (
                "value" => "Zmień hasło",
                "href" => "zmien_haslo",
                "active_subpages" => array ("zmien_haslo"),
            ),
        ),
        "logout_btn_value" => "Wyloguj",
        "theme_btn_value" => "Motyw",
        "themes" => array
        (
            array
            (
               "--main-theme-color" => "#1aca4f",
               "--gradient-bg-color" => "#107a39",
            ),
            array
            (
               "--main-theme-color" => "#2288ff",
               "--gradient-bg-color" => "#134e92",
            ),
            array
            (
               "--main-theme-color" => "#791de2",
               "--gradient-bg-color" => "#4e1391",
            ),
            array
            (
               "--main-theme-color" => "#c01919",
               "--gradient-bg-color" => "#7a1111",
            ),
        ),
    );

    $html_footer_data = array
    (
        "footer" => "Copyright ©  by Marek Kandulski All rights reserved.",
        "creation_year" => 2023,
        "creation_year_index" => 13,
    );
?>