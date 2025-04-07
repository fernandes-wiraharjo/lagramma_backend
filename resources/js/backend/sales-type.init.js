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
