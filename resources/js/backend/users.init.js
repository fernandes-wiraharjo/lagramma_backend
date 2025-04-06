document.addEventListener('DOMContentLoaded', function () {
    let table = new DataTable('#tb_data', {
        processing: true,
        serverSide: true,
        ajax: '/user/list',
        columns: [
            { data: 'id', name: 'users.id' },
            { data: 'name', name: 'users.name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'role_name', name: 'roles.name' },
            {
                data: 'is_active',
                name: 'is_active',
                render: function (data) {
                    return data == 1 ? 'True' : 'False';
                }
            },
            { data: 'id', name: 'users.id', orderable: false, searchable: false, render: function (data) {
                return `<button class="btn btn-sm btn-soft-info edit-user" data-bs-toggle="modal" href="#showModal" data-id="${data}">Edit</button>
                    <button class="btn btn-sm btn-soft-danger delete-user" data-id="${data}">Delete</button>`;
            }}
        ]
    });

    $(document).on("click", ".delete-user", function () {
        let id = $(this).data("id");
        if (confirm("Are you sure?")) {
            fetch(`/user/${id}`, {
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

                let url = id ? `/user/${id}` : "/user";

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

    $(document).on("click", ".edit-user", function () {
        let id = $(this).data("id");

        fetch(`/user/${id}`)
            .then(response => response.json())
            .then(data => {
                $("#exampleModalLabel").text("Edit User");
                $("#add-btn").text("Update");
                $("#id").val(data.id);
                $("#name").val(data.name);
                $("#email").val(data.email);
                $("#phone").val(data.phone);
                $("#role").val(data.role_id).trigger('change');
                $("#is_active").val(data.is_active);

                // make password not required in edit
                $("#password").prop("required", false).val('');
                $("#password_confirmation").prop("required", false).val('');
            })
            .catch(error => console.error("Error fetching data:", error));
    });

    function clearVal() {
        $("#exampleModalLabel").text("Create User");
        $("#add-btn").text("Add User");
        $("#id").val("");
        $("#name, #email, #phone").val("");
        $("#role").val($("#role option:first").val()).trigger('change');
        $("#is_active").val("0");
        $("#password").val('').prop("required", true);
        $("#password_confirmation").val('').prop("required", true);
        $('.tablelist-form').removeClass('was-validated');
    }

    // clearVal on modal show (when adding user)
    $('#showModal').on('show.bs.modal', function (e) {
        if (!$(e.relatedTarget).hasClass("edit-user")) {
            clearVal();
        }
    });
});
