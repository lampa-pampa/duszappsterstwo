function empty_validation_success(e)
{
    const login = document.getElementsByClassName("new-login")[0]
    const password = document.getElementsByClassName("new-password")[0]
      
    if(get_value(login).length < login_length["min"])
    {
        highlight_error(login)
        display_alert("too_short_login")
    }
    else if(get_value(login).length > login_length["max"])
    {
        highlight_error(login)
        display_alert("too_long_login")
    }
    else if(get_value(password).length < password_length["min"])
    {
        highlight_error(password)
        display_alert("too_short_password")
    }
    else if(get_value(password).length > password_length["max"])
    {
        highlight_error(password)
        display_alert("too_long_password")
    }
    else
    {
        send_form(e)
    }
}