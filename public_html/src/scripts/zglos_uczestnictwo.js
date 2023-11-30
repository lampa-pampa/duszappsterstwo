function empty_validation_success(e)
{
    const message = document.getElementsByClassName("participation-message")[0]
      
    if(get_value(message).length < participation_message_length["min"])
        display_alert("too_short_participation_message")

    else if(get_value(message).length > participation_message_length["max"])
        display_alert("too_long_participation_message")

    else
        send_form(e)
}