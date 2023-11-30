document.addEventListener("DOMContentLoaded", () => {
    set_listeners([
        {
            "class_name": "easter-egg-text",
            "event": "click",
            "function": easter_egg
        }
    ])
})
  
function easter_egg(e)
{
    const previous_text = e.target.textContent
    e.target.textContent = e.target.getAttribute("second-text")
    e.target.setAttribute("second-text", previous_text)
    if(e.target.classList.contains("colored-text"))
        e.target.classList.remove("colored-text")
    else
        e.target.classList.add("colored-text")
}