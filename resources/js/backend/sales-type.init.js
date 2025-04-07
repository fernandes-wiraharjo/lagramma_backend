document.addEventListener('DOMContentLoaded', function () {
    let table = new DataTable('#tb_data', {
        processing: true,
        serverSide: true,
        ajax: '/sales-type/list',
        columns: [
            { data: 'moka_id_sales_type', name: 'moka_id_sales_type' },
            { data: 'name', name: 'name' },
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
