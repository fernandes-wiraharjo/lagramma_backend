document.addEventListener('DOMContentLoaded', function () {
    let table = new DataTable('#tb_data', {
        processing: true,
        serverSide: true,
        ajax: '/product/list',
        columns: [
            { data: 'moka_id_product', name: 'moka_id_product' },
            { data: 'product_name', name: 'products.name' },
            { data: 'category_name', name: 'categories.name' },
            { data: 'modifier_name', name: 'modifiers.name' },
            {
                data: 'is_active',
                name: 'is_active',
                render: function (data, type, row) {
                    const checked = data == 1 ? 'checked' : '';
                    return `
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                data-id="${row.id}" ${checked}>
                        </div>
                    `;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="#!" class="dropdown-item view-variant-btn" data-id="${row.id}">
                                        <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View
                                    </a>
                                </li>
                                <li>
                                    <a href="#!" class="dropdown-item edit-item-btn" data-id="${row.id}">
                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a href="#!" class="dropdown-item remove-item-btn" data-id="${row.id}">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    `;
                }
            }
        ]
    });

    const syncBtn = document.getElementById('syncBtn');
    const btnText = document.getElementById('syncBtnText');
    const spinner = document.getElementById('syncBtnSpinner');

    syncBtn.addEventListener('click', function () {
        Swal.fire({
            title: 'Sync Sales Types?',
            text: "This will pull the latest sales types from MOKA.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, sync it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) return;

            // Disable button + show spinner
            syncBtn.disabled = true;
            btnText.textContent = 'Syncing...';
            spinner.classList.remove('d-none');

            fetch('/sales-type/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Sales types synced successfully',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                if (data.success) table.ajax.reload();
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Unexpected error occurred.', 'error');
            })
            .finally(() => {
                syncBtn.disabled = false;
                btnText.textContent = 'Sync';
                spinner.classList.add('d-none');
            });
        });
    });

    $('#tb_data').on('change', '.toggle-switch', function () {
        const id = $(this).data('id');
        const isActive = $(this).is(':checked') ? 1 : 0;

        Swal.fire({
            title: 'Confirm change?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/sales-type/${id}/toggle-active`,
                    method: 'POST',
                    data: {
                        is_active: isActive,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: 'Status updated',
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        });
                    },
                    error: function () {
                        Swal.fire('Oops!', 'Something went wrong.', 'error');
                    }
                });
            } else {
                $(this).prop('checked', !isActive); // revert toggle
            }
        });
    });
});
