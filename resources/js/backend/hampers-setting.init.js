document.addEventListener('DOMContentLoaded', function () {
    $('#allowed_items').select2({
        placeholder: "Select allowed items",
        width: '100%'
    });

    let table = new DataTable('#tb_data', {
        processing: true,
        serverSide: true,
        ajax: '/hampers-setting/list',
        columns: [
            { data: 'hampers_name', name: 'hampers.name' },
            { data: 'max_items', name: 'max_items', orderable: false },
            {
                data: 'item_names',
                name: 'item_names',
                orderable: false,
                render: function (data) {
                    return data.split(',').map(name =>
                        `<span class="btn btn-sm btn-soft-info" style="pointer-events: none; cursor: default; margin: 2px;">${name.trim()}</span>`
                    ).join('');
                }
            },
            { data: 'id', name: 'id', orderable: false, searchable: false, render: function (data) {
                return `<button class="btn btn-sm btn-soft-info edit-data" data-bs-toggle="modal" href="#showModal" data-id="${data}">Edit</button>
                    <button class="btn btn-sm btn-soft-danger delete-data" data-id="${data}">Delete</button>`;
            }}
        ]
    });

    $(document).on("click", ".delete-data", function () {
        let id = $(this).data("id");
        if (confirm("Are you sure?")) {
            fetch(`/hampers-setting/${id}`, {
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

    var createForm = document.querySelectorAll('.tablelist-form')
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

                let url = id ? `/hampers-setting/${id}` : "/hampers-setting";

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
                    bootstrap.Modal.getOrCreateInstance(document.getElementById("showModal")).hide();
                })
                .catch(error => {
                    // This only catches network errors or those re-thrown above
                    console.error("Error:", error);
                });
            }
        }, false)
    });

    $(document).on("click", ".edit-data", function () {
        let id = $(this).data("id");

        fetch(`/hampers-setting/${id}`)
            .then(response => response.json())
            .then(data => {
                $("#exampleModalLabel").text("Edit");
                $("#add-btn").text("Update");
                $("#id").val(data.id);
                $("#hampers").val(data.product_id).prop("disabled", true);
                $("#allowed_items").val(data.allowed_items).trigger('change');
                $("#max_items").val(data.max_items);
            })
            .catch(error => console.error("Error fetching data:", error));
    });

    function clearVal() {
        $("#exampleModalLabel").text("Create");
        $("#add-btn").text("Add");
        $("#id").val("");
        $("#hampers").val("").prop("disabled", false);
        $("#allowed_items").val("").trigger('change');
        $("#max_items").val(1);
        $('.tablelist-form').removeClass('was-validated');
    }

    // clearVal on modal show (when adding user)
    $('#showModal').on('show.bs.modal', function (e) {
        if (!$(e.relatedTarget).hasClass("edit-data")) {
            clearVal();
        }
    });
});
