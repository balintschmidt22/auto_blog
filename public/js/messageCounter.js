document.getElementById("message").onkeyup = function () {
    document.getElementById("counter").innerHTML =
        this.value.length + " / 2000";
};
