function empty_validation_success(e)
{
    const title = document.getElementsByClassName("day-event-title")[0]
    const content = document.getElementsByClassName("day-event-content")[0]
      
    if(get_value(title).length < title_length["min"])
        display_alert("too_short_title")

    else if(get_value(title).length > title_length["max"])
        display_alert("too_long_title")

    else if(get_value(content).length < content_length["min"])
        display_alert("too_short_content")

    else if(get_value(content).length > content_length["max"])
        display_alert("too_long_content")

    else
        send_form(e)
}