document.addEventListener('DOMContentLoaded', function () {
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = date.toLocaleString('en-US', { month: 'short' }); // Apr
        const year = date.getFullYear();
        return `${day} ${month}, ${year}`;
    }

    const fp = flatpickr("#date_range", {
        mode: "range",
        dateFormat: "d M, Y"
    });

    let table = new DataTable('#tb_data', {
        processing: true,
        serverSide: true,
        searching: false,
        ajax: `/product-deactivate-by-date/${idProduct}/list`,
        columns: [
            {
                data: 'start_date',
                name: 'start_date',
                render: function (data) {
                    return formatDate(data); // call your custom function
                }
            },
            {
                data: 'end_date',
                name: 'end_date',
                render: function (data) {
                    return formatDate(data);
                }
            },
            { data: null, name: 'id', orderable: false, searchable: false, render: function (data) {
                return `
                    <button class="btn btn-sm btn-soft-info edit-data" data-id="${data.id}">Edit</button>
                    <button class="btn btn-sm btn-soft-danger delete-data" data-id="${data.id}">Delete</button>
                `;
            }}
        ]
    });

    // Delete
    $(document).on("click", ".delete-data", function () {
        let id = $(this).data("id");
        if (confirm("Are you sure?")) {
            fetch(`/product-deactivate-by-date/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                }
            }).then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    alert("Error:\n" + (data.message || "Failed to delete data."));
                    throw new Error(data.message || "Request failed");
                }

                alert("Data deleted successfully!");
                table.ajax.reload();
            })
            .catch(error => {
                console.error("Error deleting data:", error);
            });
        }
    });

    var createForm = document.querySelectorAll('.createForm')
    Array.prototype.slice.call(createForm).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                event.preventDefault();

                let id = document.getElementById("id").value;
                let formData = new FormData(this);

                if (id) {
                    formData.append("_method", "PUT");
                }

                let url = id ? `/product-deactivate-by-date/${id}` : `/product-deactivate-by-date/${idProduct}`;

                fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        "Accept": "application/json"
                    },
                    body: formData
                }).then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        // Check if it's a validation error (422)
                        if (response.status === 422 && data?.message) {
                            const validationMsg = Object.values(data.message).flat().join('\n');
                            alert("Validation Error:\n" + validationMsg);
                        } else {
                            // General server error (500, 400, etc.)
                            alert("Server Error:\n" + (data.message || "Something went wrong."));
                        }

                        throw new Error(data.message || "Request failed");
                    }

                    // Success block
                    alert("Data saved successfully!");
                    table.ajax.reload();
                    clearVal();
                    form.classList.remove('was-validated');
                })
                .catch(error => {
                    // This only catches network errors or those re-thrown above
                    console.error("Error:", error);
                });
            }
        }, false)
    });

    // Edit
    $(document).on("click", ".edit-data", function () {
        let id = $(this).data("id");

        fetch(`/product-deactivate-by-date/by-id/${id}`)
            .then(response => response.json())
            .then(data => {
                $("#addLabel").text("Edit");
                $("#addBtn").text("Update");
                $("#id").val(data.id);

                //edit date range
                const start = formatDate(data.start_date);
                const end = formatDate(data.end_date);
                $("#date_range").val(`${start} to ${end}`);
                fp.setDate([start, end], true);

                // Scroll to top smoothly
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(error => console.error("Error fetching data:", error));
    });

    function clearVal() {
        $("#addLabel").text("Create");
        $("#addBtn").text("Add");
        $("#id").val("");
        $("#date_range").val("");
        fp.clear();
        $('#createForm').removeClass('was-validated');
    }
});
