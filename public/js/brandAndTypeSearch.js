document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const typeInput = document.getElementById("typeInput");
    const brandsTab = document.getElementById("foundBrands");
    const typesTab = document.getElementById("foundTypes");

    searchInput.addEventListener("input", function () {
        const query = searchInput.value;

        brandsTab.innerHTML = "";
        typesTab.innerHTML = "";

        axios
            .post("/brands/search", {
                params: {
                    search: query,
                },
            })
            .then((response) => {
                var brands = Object.values(response.data);

                brandsTab.innerHTML = "";
                typesTab.innerHTML = "";

                if (brands.length > 0) {
                    var row = document.createElement("tr");
                    var col = document.createElement("th");
                    col.innerHTML = "<b>Brands found</b> - " + brands.length;
                    row.appendChild(col);
                    brandsTab.appendChild(row);
                }

                brands.forEach((b) => {
                    var row = document.createElement("tr");
                    var col = document.createElement("td");
                    var a = document.createElement("a");

                    col.innerHTML =
                        "<a href='brands/" +
                        b["id"] +
                        "' class='text-decoration-none'>" +
                        b["name"] +
                        "</a>";
                    row.appendChild(col);
                    brandsTab.appendChild(row);
                });
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });
    });

    typeInput.addEventListener("input", function () {
        const query = typeInput.value;

        typesTab.innerHTML = "";
        brandsTab.innerHTML = "";

        axios
            .post("/types/search", {
                params: {
                    search: query,
                },
            })
            .then((response) => {
                var types = Object.values(response.data);

                brandsTab.innerHTML = "";
                typesTab.innerHTML = "";

                if (types.length > 0) {
                    var row = document.createElement("tr");
                    var col = document.createElement("th");
                    col.innerHTML = "<b>Types found</b> - " + types.length;
                    row.appendChild(col);
                    typesTab.appendChild(row);
                }

                types.forEach((t) => {
                    var row = document.createElement("tr");
                    var col = document.createElement("td");
                    var a = document.createElement("a");

                    col.innerHTML =
                        "<a href='types/" +
                        t["id"] +
                        "' class='text-decoration-none'>" +
                        t["type"] +
                        "</a>";
                    row.appendChild(col);
                    typesTab.appendChild(row);
                });
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });
    });
});
