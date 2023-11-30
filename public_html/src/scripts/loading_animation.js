const logo = new Image()
logo.src = "src/img/logo.png"
  
document.addEventListener("DOMContentLoaded", () => {
    const loading_screen = document.createElement("div")
    loading_screen.classList.add("loading-screen")
    loading_screen.appendChild(logo)
    const spinner = document.createElement("div")
    spinner.classList.add("loading-spinner")
    loading_screen.appendChild(spinner)  
    document.body.appendChild(loading_screen)

    set_listeners([
        {
            "class_name": "loading-btn",
            "event": "click",
            "function": add_loading_button_animation,
        },
        {
            "class_name": "loading-toggle",
            "event": "change",
            "function": add_loading_toggle_animation,
        },
        {
            "class_name": "loading-screen-btn",
            "event": "click",
            "function": add_loading_screen_animation,
        },
    ])
    const loading_screen_state = sessionStorage.getItem("loading_screen_state")
    if(loading_screen_state == "active")
    {
        loading_screen.style.animationName = "fade-out-screen"
        sessionStorage.removeItem("loading_screen_state")
    }
})

function disable_input(input)
{
    input.classList.add("disabled")
}

function disable_all_inputs()
{
    const selected_classes = ["btn", "toggle-input"]
    for(i in selected_classes)
    {
        const cls = selected_classes[i]
        const node_list = document.getElementsByClassName(cls)
        for(const node of node_list)
            disable_input(node)
    }
}

function add_loading_button_animation(e)
{
    const btn = e.target
    e.target.classList.add("clicked")
    const button_style = getComputedStyle(btn)
    btn.style.height = button_style.height
    btn.style.width = button_style.width
    const spinner = document.createElement("div")
    spinner.classList.add("loading-spinner")
    btn.replaceChildren(spinner)
    disable_all_inputs()
}

function add_loading_toggle_animation(e)
{
    const spinner = document.createElement("div")
    spinner.classList.add("loading-spinner")
    e.target.nextSibling.innerHTML = ""
    e.target.nextSibling.appendChild(spinner)
    disable_all_inputs()
}

function add_loading_screen_animation(e)
{
    const btn = e.target
    btn.classList.add("selected")
    document.getElementsByClassName("menu")[0].classList.add("hidden-list")
    const loading_screen = document.getElementsByClassName("loading-screen")[0]
    if(btn.classList.contains("animate-whole-screen"))
        loading_screen.classList.add("whole-screen")
    loading_screen.classList.add("loading")
    disable_all_inputs()
    close_list()
    sessionStorage.setItem("loading_screen_state", "active")
}
          
function close_list()
{
    const all_lists = document.getElementsByClassName("list")
    for(const list of all_lists)
    list.classList.add("closed")
}