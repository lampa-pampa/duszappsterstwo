function empty_validation_success(e)
{
    const old_password = document.getElementsByClassName("old_password")[0]
    const new_password = document.getElementsByClassName("new_password")[0]
    const repeat_new_password = document.getElementsByClassName("repeat_new_password")[0]
    highlight_new_password = false
      
    if(get_value(new_password) != get_value(repeat_new_password))
    {
        highlight_new_password = true
        display_alert("different_passwords")
    }
    else if(get_value(old_password) == get_value(new_password))
    {
        highlight_error(old_password)
        highlight_new_password = true
        display_alert("same_passwords")
    }
    else if(get_value(new_password).length < password_length["min"])
    {
        highlight_new_password = true
        display_alert("too_short_password")
    }
    else if(get_value(new_password).length > password_length["max"])
    {
        highlight_new_password = true
        display_alert("too_long_password")
    }
    else
    {
        send_form(e)
    }
 
    if(highlight_new_password)
    {
        highlight_error(new_password)
        highlight_error(repeat_new_password)
    }
}