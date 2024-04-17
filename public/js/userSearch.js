document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");

    searchInput.addEventListener("input", function () {
        const query = searchInput.value;

        const tab = document.getElementById("foundUsers");
        tab.innerHTML = "";

        axios
            .post("/users/search", {
                params: {
                    search: query,
                },
            })
            .then((response) => {
                var users = Object.values(response.data);

                tab.innerHTML = "";

                if (users.length > 0) {
                    var row = document.createElement("tr");
                    var col = document.createElement("th");
                    col.innerHTML = "<b>Users found</b> - " + users.length;
                    row.appendChild(col);
                    tab.appendChild(row);
                }

                users.forEach((u) => {
                    var row = document.createElement("tr");
                    var col = document.createElement("td");
                    var a = document.createElement("a");

                    col.innerHTML =
                        "<a href='users/" +
                        u["id"] +
                        "' class='text-decoration-none'>" +
                        u["username"] +
                        "</a>";
                    row.appendChild(col);
                    tab.appendChild(row);
                });
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });
    });
});
