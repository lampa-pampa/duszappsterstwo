document.addEventListener("DOMContentLoaded", () => {
    set_error_remove_listeners()
    set_listeners([
        {
            "class_name": "generate-password-btn",
            "event": "click",
            "function": generate_password
        }
    ])
})

function generate_password()
{
    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
    let password = ""
    for(let i = 0; i < generator_data["default_password_length"]; ++i)
        password += chars.charAt(Math.floor(Math.random() * chars.length))
    const password_input = document.getElementsByClassName("new-password")[0]
    password_input.value = password
}