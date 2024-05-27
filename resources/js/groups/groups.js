window.expand = function(index) {
    let expand = document.getElementsByClassName("group-expanded");
    let container = document.getElementsByClassName("group-preview-body");
    let button = document.getElementsByClassName("toggle-button");



    let current_state = expand[index].style.display;
    
    if (current_state == "flex") {
        expand[index].style.display = "none";
        container[index].style.borderRadius = "100px";
        button[index].style.transform = "rotate(-180deg)"
    } else {
        expand[index].style.display = "flex";
        container[index].style.borderRadius = "50px";
        button[index].style.transform = "rotate(180deg)"
    }
}