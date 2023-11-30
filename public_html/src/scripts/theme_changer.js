read_theme_preload()

document.addEventListener("DOMContentLoaded", () => {
    set_listeners([
        {
            "class_name": "theme-btn",
            "event": "click",
            "function": refresh_theme
        }
    ])
})
  
function refresh_theme(e)
{
    set_theme(e.target)
    read_theme()
}

function read_theme_preload()
{
    const style = document.createElement("style")
    style.textContent = ":root {"
    const theme = JSON.parse(localStorage.getItem("theme"))
    for(const color_name in theme)
        style.textContent += `${color_name}: ${theme[color_name]};`
    style.textContent += "}"
    document.head.appendChild(style)
}
  
function read_theme()
{
    const theme = JSON.parse(localStorage.getItem("theme"))
    for(const color_name in theme)
        document.body.style.setProperty(color_name, theme[color_name])
}

function set_theme(node_btn)
{
    const theme = new Object
    for(i in css_properties)
    {
        const css_property = css_properties[i]
        theme[css_property] = node_btn.style.getPropertyValue(css_property)
    }
    localStorage.setItem("theme", JSON.stringify(theme))
}

