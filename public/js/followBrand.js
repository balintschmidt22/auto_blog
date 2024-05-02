document.addEventListener("DOMContentLoaded", function () {
    var followButtons = document.getElementsByClassName("followButton");

    for (var i = 0; i < followButtons.length; i++) {
        followButtons[i].addEventListener("click", function () {
            const query = this.id;

            axios
                .post("/follows/followBrand", {
                    params: {
                        id: query,
                    },
                })
                .then((response) => {
                    var followCount = response.data;
                    var i = this.querySelector("i").id;

                    var fc = document.getElementById("followcount");

                    if (fc) {
                        fc.innerHTML = followCount;
                    }

                    if (i == "unfollowed") {
                        this.innerHTML =
                            "<i class='fa-solid fa-user-plus' style='color: #ffffff;' id='followed'></i>";
                    } else {
                        this.innerHTML =
                            "<i class='fa-solid fa-user-minus' style='color: #ffffff;' id='unfollowed'></i>";
                    }
                })
                .catch((error) => {
                    console.error("Error fetching data:", error);
                });
        });
    }
});
