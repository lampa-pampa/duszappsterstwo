function delete_event(title)
{
    save_scroll_position()
    document.getElementsByName("event_title")[0].value = title
    document.getElementsByName("delete_event")[0].click()
}

function delete_participation(title)
{
    save_scroll_position()
    document.getElementsByName("event_title")[0].value = title
    document.getElementsByName("delete_participation")[0].click()
}
