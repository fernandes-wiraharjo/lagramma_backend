document.addEventListener('DOMContentLoaded', function () {
    let table = new DataTable('#tb_role_menus', {
        processing: true,
        serverSide: true,
        ajax: '/role-menu/list',
        columns: [
            { data: 'role_name', name: 'roles.name' },
            { data: 'menu_names', name: 'menu_names' },
            { data: 'role_id', name: 'roles.id', orderable: false, searchable: false, render: function (data) {
                return `<button class="btn btn-sm btn-soft-info edit-role-menu" data-id="${data}">Edit</button>
                    <button class="btn btn-sm btn-soft-danger delete-role-menu" data-id="${data}">Delete</button>`;
            }}
        ]
    });

    $(document).on("click", ".delete-role-menu", function () {
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

    var createForm = document.querySelectorAll('.createRoleMenuForm')
    Array.prototype.slice.call(createForm).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                event.preventDefault();

                let roleId = document.getElementById("role_id").value;
                let formData = new FormData(this);

                if (roleId) {
                    formData.append("_method", "PUT");
                }

                let url = roleId ? `/role-menu/${roleId}` : "/role-menu";

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

    $(document).on("click", ".edit-role-menu", function () {
        let roleId = $(this).data("id");

        fetch(`/role-menu/${roleId}`)
            .then(response => response.json())
            .then(data => {
                $("#addLabel").text("Edit Data");
                $("#addNew").text("Save");
                $("#role_id").val(data.role_id);
                $("#role").val(data.role_id);
                $("#menu").val(role.menus);

                // Scroll to top smoothly
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(error => console.error("Error fetching data:", error));
    });

    function clearVal() {
        $("#addLabel").text("Create Role Menu");
        $("#addNew").text("Add");
        $("#role_id").val("");
        $("#role").val("");
        $("#menu").val("");
        $('#createRoleMenuForm').removeClass('was-validated');
    }
});
