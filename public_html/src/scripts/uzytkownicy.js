function set_grant(id, grant, btn)
{
    save_scroll_position()
    document.getElementsByName("user_id")[0].value = id
    document.getElementsByName("grant_name")[0].value = grant
    document.getElementsByName("grant_value")[0].value = btn.checked
    document.getElementsByName("set_grant")[0].click()
}

function delete_user(user_id)
{
    save_scroll_position()
    document.getElementsByName("user_id")[0].value = user_id
    document.getElementsByName("delete_user")[0].click()
}

function reset_password(user_id)
{
    document.getElementsByName("user_id")[0].value = user_id
    document.getElementsByName("reset_password")[0].click()
}
