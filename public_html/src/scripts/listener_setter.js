function set_listeners(listeners_list)
{
    for(const i in listeners_list)
    {
        const elem = listeners_list[i]
        const node_list = document.getElementsByClassName(elem["class_name"])
        for(const node of node_list)
            node.addEventListener(elem["event"], elem["function"])
    }
}