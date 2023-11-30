document.addEventListener("DOMContentLoaded", () => {
    const scroll_position = sessionStorage.getItem('scroll_position')
    if (scroll_position)
    {
        window.scrollTo(0, scroll_position)
        sessionStorage.removeItem("scroll_position")
    }
})

function save_scroll_position()
{
    sessionStorage.setItem("scroll_position", window.scrollY)
}