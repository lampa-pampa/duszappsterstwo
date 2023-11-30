document.addEventListener("DOMContentLoaded", () => {
    set_listeners([
        {
            "class_name": "search-input",
            "event": "input",
            "function": refresh_result
        }
    ])
    const alert = document.createElement("div")
    alert.textContent = alerts["empty_search_results"]
    alert.classList.add("empty-search-results", "hidden-item", "line")
    document.getElementsByClassName("window")[0].appendChild(alert)
})
  
function refresh_result(e)
{
    const search_value = e.target.value
    const search_items = document.getElementsByClassName("search-item")
    let empty_results = true
    for(const item of search_items)
    {
        if(item.id.toLowerCase().includes(search_value.toLowerCase()))
        {
            item.classList.remove("hidden-item")
            empty_results = false
        }
        else
        {
            item.classList.add("hidden-item")
        }
    }
    
    const alert = document.getElementsByClassName("empty-search-results")[0]
    if(search_items.length > 0 && empty_results)
    {
        alert.classList.remove("hidden-item")
        document.getElementsByClassName("window")[0].classList.add("small-window")
    }
    else
    {
        alert.classList.add("hidden-item")
        document.getElementsByClassName("window")[0].classList.remove("small-window")
    }
}
