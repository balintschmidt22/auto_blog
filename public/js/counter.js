document.getElementById("comment").onkeyup = function () {
    document.getElementById("counter").innerHTML =
        "Comment - " + this.value.length + " / 2000";
};
