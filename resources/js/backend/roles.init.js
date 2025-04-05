document.addEventListener('DOMContentLoaded', function () {
    let table = new DataTable('#tb_roles', {
        processing: true,
        serverSide: true,
        ajax: '/role/list',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'is_active', name: 'is_active', render: function(data) {
                return data ? 'True' : 'False';
            }},
            { data: 'id', name: 'id', orderable: false, searchable: false, render: function (data) {
                return `<button class="btn btn-sm btn-soft-info edit-role" data-id="${data}">Edit</button>
                    <button class="btn btn-sm btn-soft-danger delete-role" data-id="${data}">Delete</button>`;
            }}
        ]
    });

    // Delete Role
    $(document).on("click", ".delete-role", function () {
        let roleId = $(this).data("id");
        if (confirm("Are you sure?")) {
            fetch(`/role/${roleId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                }
            }).then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    alert("Error:\n" + (data.message || "Failed to delete role."));
                    throw new Error(data.message || "Request failed");
                }

                alert("Role deleted successfully!");
                table.ajax.reload();
            })
            .catch(error => {
                console.error("Error deleting role:", error);
            });
        }
    });

    var createRoleForm = document.querySelectorAll('.createRoleForm')
    Array.prototype.slice.call(createRoleForm).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                event.preventDefault();

                let roleId = document.getElementById("roleid-input").value;
                let formData = new FormData(this);

                if (roleId) {
                    formData.append("_method", "PUT");
                }

                let url = roleId ? `/role/${roleId}` : "/role";

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

    // Edit Role
    $(document).on("click", ".edit-role", function () {
        let roleId = $(this).data("id");

        fetch(`/role/${roleId}`)
            .then(response => response.json())
            .then(role => {
                $("#addRoleLabel").text("Edit Role");
                $("#addNewRole").text("Save");
                $("#roleid-input").val(role.id);
                $("#name").val(role.name);
                $("#is_active_" + (role.is_active ? "1" : "0")).prop("checked", true);

                // Scroll to top smoothly
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(error => console.error("Error fetching role:", error));
    });

    function clearVal() {
        $("#addRoleLabel").text("Create Role");
        $("#addNewRole").text("Add Role");
        $("#roleid-input").val("");
        $("#name").val("");
        $("#is_active_1").prop("checked", true);
        $('#createRoleForm').removeClass('was-validated');
    }
});
