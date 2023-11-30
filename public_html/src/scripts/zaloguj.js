document.addEventListener("DOMContentLoaded", () => {
    document.getElementsByClassName("loading-screen")[0].classList.add("whole-screen")
})

function empty_validation_success(e)
{
    send_form(e)
}