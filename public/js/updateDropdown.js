document.addEventListener("DOMContentLoaded", function () {
    updateTypeDropdown();

    document.getElementById("brand").addEventListener("change", function () {
        updateTypeDropdown();
    });

    // Function to update "Types" dropdown options based on the selected value of "Brand" dropdown
    function updateTypeDropdown() {
        var selectedBrand = document.getElementById("brand").value;

        if (selectedBrand != "") {
            axios.defaults.headers.common = {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            };

            axios
                .get("/gallery/gettypes", {
                    params: {
                        brand: selectedBrand,
                    },
                })
                .then((response) => {
                    const d = response;
                    const types = d.data;

                    var typeDropdown = document.getElementById("type");
                    typeDropdown.innerHTML = "";

                    for (var i in types) {
                        var option = document.createElement("option");
                        option.value = types[i]["id"];
                        option.text = types[i]["type"];
                        typeDropdown.appendChild(option);
                    }
                })
                .catch((error) => {
                    console.error("Error fetching data:", error);
                });
        }
    }
});
