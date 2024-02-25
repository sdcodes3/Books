function showSnackBar() {
    var x = document.getElementById("snackbar");
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
}

function passView() {
    var images = document.querySelector(".pass-view").children;
    var passwordInput = document.getElementById("exampleInputPassword1");
    if(images[0].classList.contains("d-none")) {
        passwordInput.type = "password";
        images[0].classList.remove("d-none");
        images[1].classList.add("d-none");
    }
    else {
        passwordInput.type = "text";
        images[0].classList.add("d-none");
        images[1].classList.remove("d-none");
    }
}