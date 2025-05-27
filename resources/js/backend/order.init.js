// make 'table' accessible globally
let table;

function filterData() {
    table.ajax.reload();
}

const userRole = document.getElementById('tb_data').dataset.userRole;
const isCustomer = userRole === 'customer';

document.addEventListener('DOMContentLoaded', function () {
    function isStatus(val) {
        switch (val) {
            case "waiting for payment":
                return (
                    '<span class="badge bg-primary-subtle text-primary  text-uppercase">' +
                    val +
                    "</span>"
                );
            case "pending":
                return (
                    '<span class="badge bg-warning-subtle text-warning  text-uppercase">' +
                    val +
                    "</span>"
                );
            case "payment failed":
                return (
                    '<span class="badge bg-danger-subtle text-danger  text-uppercase">' +
                    val +
                    "</span>"
                );
            case "packed":
                return (
                    '<span class="badge bg-secondary-subtle text-secondary  text-uppercase">' +
                    val +
                    "</span>"
                );
            case "request picked up":
                return (
                    '<span class="badge bg-info-subtle text-info  text-uppercase">' + val + "</span>"
                );
            case "picked up":
                return (
                    '<span class="badge bg-info-subtle text-info  text-uppercase">' + val + "</span>"
                );
            case "delivered":
                return (
                    '<span class="badge bg-success-subtle text-success  text-uppercase">' +
                    val +
                    "</span>"
                );
        }
    }

    flatpickr("#demo-datepicker", {
        mode: "range",
        dateFormat: "d M, Y"
    });

    const columns = [
        {
            data: 'order_date',
            name: 'orders.created_at',
            render: function (data) {
                return formatIndonesianDate(data);
            }
        }
    ];

    if (!isCustomer) {
        columns.push({
            data: 'user_name',
            name: 'users.name',
            render: function (data) {
                return data || '-';
            }
        });
    }

    columns.push(
        {
            data: 'invoice_number',
            name: 'invoice_number',
            orderable: false,
            searchable: false,
            render: function (data) {
                return '<a href="orders/' + data + '/detail" class="fw-medium link-primary">#' + data + '</a>';
            }
        },
        {
            data: 'status',
            name: 'orders.status',
            render: function (data) {
                return isStatus(data);
            }
        },
        {
            data: 'order_price',
            name: 'order_price',
            render: function (data) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(data);
            }
        },
        {
            data: 'payment_method',
            name: 'payment_method'
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                const isPending = row.status === 'pending';

                let dropdownHTML = `
                    <li>
                        <a href="orders/${row.invoice_number}/detail" class="dropdown-item view-btn" data-id="${row.invoice_number}">
                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View
                        </a>
                    </li>
                `;

                if (!isCustomer && isPending) {
                    dropdownHTML += `
                        <li>
                            <a href="#!" class="dropdown-item edit-order-btn"
                                data-id="${row.id}"
                            >
                                <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                            </a>
                        </li>
                    `;
                }

                return `
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-more-fill align-middle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            ${dropdownHTML}
                        </ul>
                    </div>
                `;
            }
        }
    );

    table = new DataTable('#tb_data', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '/orders/list',
            data: function (d) {
                const dateRange = $('#demo-datepicker').val();
                if (dateRange) {
                    const dates = dateRange.split(' to ');
                    d.start_date = dates[0]?.trim();
                    d.end_date = dates[1]?.trim();
                }
            }
        },
        order: [[0, 'desc']],
        columns: columns
    });

    $(document).on('click', '.edit-order-btn', function () {
        $('#edit_order_id').val($(this).data('id'));
        $('#editOrderModal').modal('show');
    });

    $('#editOrderForm').submit(function (e) {
        e.preventDefault();

        const orderId = $('#edit_order_id').val();
        const formData = {
            _token: $('input[name="_token"]').val(),
            status: $('#status').val(),
        };

        $.ajax({
            url: `/orders/${orderId}/update`,
            type: 'POST',
            data: formData,
            success: function (res) {
                $('#editOrderModal').modal('hide');
                $('#tb_data').DataTable().ajax.reload(null, false);
                alert('Order updated successfully!');
            },
            error: function (xhr) {
                console.error(xhr);
                alert('Failed to update order.');
            }
        });
    });
});
