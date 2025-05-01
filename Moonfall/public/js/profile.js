document.addEventListener("DOMContentLoaded", function () {
    const applyform = document.getElementById("apply");

applyform.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(applyform);
    const actionUrl = applyform.getAttribute("action");

    fetch(actionUrl, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
    .then(async (response) => {
        const data = await response.json();

        if (response.ok && data.success) {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: data.message
            }).then(() => {
                window.location.reload();
            });
        } else {
            const message = data.message || "Validation error";
            const errors = data.errors
                ? Object.values(data.errors).flat().join("\n")
                : "";

            Swal.fire({
                icon: "error",
                title: "Error",
                text: message + "\n" + errors
            });
        }
    })
    .catch((error) => {
        console.error("Fetch error:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "An unexpected error occurred."
        });
    });
});


    const form = document.getElementById("profileForm");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const actionUrl = form.getAttribute("action");

        fetch(actionUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
        .then(async (response) => {
            const data = await response.json();

            if (response.ok && data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: data.message
                }).then(() => {
                    window.location.reload();
                });
            } else {
                const message = data.message || "Validation error";
                const errors = data.errors
                    ? Object.values(data.errors).flat().join("\n")
                    : "";

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: message + "\n" + errors
                });
            }
        })
        .catch((error) => {
            console.error("Fetch error:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "An unexpected error occurred."
            });
        });
    });
});
