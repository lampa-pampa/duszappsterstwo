document.addEventListener("DOMContentLoaded", () => {
    set_error_remove_listeners()
    set_listeners([
        {
            "class_name": "validate-btn",
            "event": "click",
            "function": validate_empty
        }
    ])
})
  
function set_error_remove_listeners()
{
    const node_list = document.getElementsByClassName("validated-input")
    for(const node of node_list)
        node.removeEventListener("input", remove_error)
    set_listeners([
        {
            "class_name": "validated-input",
            "event": "input",
            "function": remove_error
        }
    ])
}

function get_value(node)
{
    return node.value.trim()
}

function remove_error(e)
{
    e.target.classList.remove("error")
    e.target.removeEventListener("input", remove_error)
}

function validate_empty(e)
{
    set_error_remove_listeners()
    state = true
    const text_fields = document.getElementsByClassName("text-input")
    for(field of text_fields)
    {
        if(get_value(field) == "")
        {
            state = false
            highlight_error(field)
        }
    }
    if(state == false)
        display_alert("empty_field")
    else
        empty_validation_success(e)
}
  
function highlight_error(node)
{
    node.classList.add("error")
}
  
function display_alert(alert)
{
    document.getElementsByClassName("alert")[0].textContent = alerts[alert]
}
  
function send_form(e)
{
    e.target.classList.add("loading-btn")
    add_loading_button_animation(e)
    document.getElementsByClassName("send")[0].click()
}
