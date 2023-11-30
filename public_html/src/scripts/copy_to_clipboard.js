document.addEventListener("DOMContentLoaded", () => {
    document.getElementsByClassName("copy-btn")[0].addEventListener("click", copy_to_clipboard)
})
  
function copy_to_clipboard(e)
{
    const new_password = document.getElementsByClassName(e.target.getAttribute("field"))[0].value
    navigator.clipboard.writeText(new_password)
}
