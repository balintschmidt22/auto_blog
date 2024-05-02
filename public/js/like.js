document.addEventListener("DOMContentLoaded", function () {
    var likeButtons = document.getElementsByClassName("likeButton");

    for (var i = 0; i < likeButtons.length; i++) {
        likeButtons[i].addEventListener("click", function () {
            const query = this.id;

            axios
                .post("/favourites/add", {
                    params: {
                        id: query,
                    },
                })
                .then((response) => {
                    var likeCount = response.data.likeCount;

                    var i = this.querySelector("i").id;

                    var lc = document.getElementById("likecount");
                    if (lc) {
                        lc.innerHTML = response.data.allLikes;
                    }

                    if (i == "disliked") {
                        this.innerHTML =
                            "<i class='fa-regular fa-heart fa-xl' style='color: #ff0000;' id='liked'></i><b> " +
                            likeCount +
                            "</b>";
                    } else {
                        this.innerHTML =
                            "<i class='fa-solid fa-heart fa-xl' style='color: #ff0000;' id='disliked'></i><b> " +
                            likeCount +
                            "</b>";
                    }
                })
                .catch((error) => {
                    console.error("Error fetching data:", error);
                });
        });
    }
});
