document.addEventListener("DOMContentLoaded", () => {
    const password_inputs = document.getElementsByClassName("password-input")
    for(const input of password_inputs)
    {
        const copy = input.cloneNode(true)
        const box = document.createElement("div")
        box.classList.add("show-password-box")
        box.appendChild(copy)
        const button_box = document.createElement("div")
        button_box.classList.add("show-password-btn-box")
        const show_pass_btn = document.createElement("div")
        show_pass_btn.classList.add("show-password-btn", "btn")
        show_pass_btn.addEventListener("click", show_password)
        button_box.appendChild(show_pass_btn)
        box.appendChild(button_box)
        input.replaceWith(box)
    }
})

function show_password(e)
{
    const input = e.target.parentNode.previousSibling
    if(input.type == "password")
    {
        input.type = "text"
        e.target.classList.add("active")
    }
    else
    {
        input.type = "password"
        e.target.classList.remove("active")
    }
}