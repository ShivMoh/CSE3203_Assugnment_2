window.expand = function(index) {
    let expand = document.getElementsByClassName("group-expanded");
    let container = document.getElementsByClassName("group-preview-body");

    
    let current_state = expand[index].style.display;
    
    if (current_state == "flex") {
        expand[index].style.display = "none";
        container[index].style.borderRadius = "100px";

    } else {
        expand[index].style.display = "flex";
        container[index].style.borderRadius = "50px";
    }
}