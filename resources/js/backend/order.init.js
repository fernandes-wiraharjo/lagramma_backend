// make 'table' accessible globally
let table;

function filterData() {
    table.ajax.reload();
}

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
        columns: [
            {   data: 'order_date',
                name: 'orders.created_at' ,
                render: function (data) {
                    return formatIndonesianDate(data);
                }
            },
            { data: 'invoice_number', name: 'invoice_number', orderable: false, searchable: false },
            {
                data: 'status',
                name: 'orders.status',
                render: function (data) {
                    return isStatus(data);
                }
            },
            {   data: 'order_price',
                name: 'order_price',
                render: function (data) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(data);
                }
            },
            { data: 'payment_method', name: 'payment_method' },
            // {
            //     data: null,
            //     orderable: false,
            //     searchable: false,
            //     render: function (data, type, row) {
            //         return `
            //             <div class="dropdown d-inline-block">
            //                 <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
            //                     data-bs-toggle="dropdown" aria-expanded="false">
            //                     <i class="ri-more-fill align-middle"></i>
            //                 </button>
            //                 <ul class="dropdown-menu dropdown-menu-end">
            //                     <li>
            //                         <a href="#!" class="dropdown-item edit-product-btn"
            //                             data-id="${row.id}"
            //                             data-weight="${row.weight ?? 0}"
            //                             data-width="${row.width ?? 0}"
            //                             data-height="${row.height ?? 0}"
            //                             data-length="${row.length ?? 0}"
            //                         >
            //                             Edit
            //                         </a>
            //                     </li>
            //                     <li>
            //                         <a href="#!" class="dropdown-item view-variant-btn" data-id="${row.id}">
            //                             Variant
            //                         </a>
            //                     </li>
            //                     <li>
            //                         <a href="#!" class="dropdown-item edit-image-btn" data-id="${row.id}">
            //                             Image
            //                         </a>
            //                     </li>
            //                     <li>
            //                         <a href="#!" class="dropdown-item deactivate-date-btn" data-id="${row.id}">
            //                             Deactivate By Date
            //                         </a>
            //                     </li>
            //                 </ul>
            //             </div>
            //         `;
            //     }
            // }
        ]
    });

    // $('#tb_data').on('change', '.toggle-switch', function () {
    //     const id = $(this).data('id');
    //     const isActive = $(this).is(':checked') ? 1 : 0;

    //     Swal.fire({
    //         title: 'Confirm change?',
    //         icon: 'question',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes',
    //         reverseButtons: true
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: `/product/${id}/toggle-active`,
    //                 method: 'POST',
    //                 data: {
    //                     is_active: isActive,
    //                     _token: $('meta[name="csrf-token"]').attr('content')
    //                 },
    //                 success: function () {
    //                     Swal.fire({
    //                         toast: true,
    //                         icon: 'success',
    //                         title: 'Status updated',
    //                         position: 'top-end',
    //                         showConfirmButton: false,
    //                         timer: 2000,
    //                         timerProgressBar: true,
    //                     });
    //                 },
    //                 error: function () {
    //                     Swal.fire('Oops!', 'Something went wrong.', 'error');
    //                 }
    //             });
    //         } else {
    //             $(this).prop('checked', !isActive); // revert toggle
    //         }
    //     });
    // });

    // $(document).on('click', '.view-variant-btn', function () {
    //     const idProduct = $(this).data('id');
    //     window.open(`/product-variant/${idProduct}`, '_blank');
    // });

    // $(document).on('click', '.edit-image-btn', function () {
    //     const idProduct = $(this).data('id');
    //     window.open(`/product-image/${idProduct}`, '_blank');
    // });

    // $(document).on('click', '.deactivate-date-btn', function () {
    //     const idProduct = $(this).data('id');
    //     window.open(`/product-deactivate-by-date/${idProduct}`, '_blank');
    // });

    // $(document).on('click', '.edit-product-btn', function () {
    //     $('#edit_product_id').val($(this).data('id'));
    //     $('#edit_weight').val($(this).data('weight'));
    //     $('#edit_width').val($(this).data('width'));
    //     $('#edit_height').val($(this).data('height'));
    //     $('#edit_length').val($(this).data('length'));

    //     $('#editProductModal').modal('show');
    // });

    // $('#editProductForm').submit(function (e) {
    //     e.preventDefault();

    //     const productId = $('#edit_product_id').val();
    //     const formData = {
    //         _token: $('input[name="_token"]').val(),
    //         weight: $('#edit_weight').val(),
    //         width: $('#edit_width').val(),
    //         height: $('#edit_height').val(),
    //         length: $('#edit_length').val(),
    //     };

    //     $.ajax({
    //         url: `/product/${productId}/update`,
    //         type: 'POST',
    //         data: formData,
    //         success: function (res) {
    //             $('#editProductModal').modal('hide');
    //             $('#tb_data').DataTable().ajax.reload(null, false);
    //             alert('Product updated successfully!');
    //         },
    //         error: function (xhr) {
    //             console.error(xhr);
    //             alert('Failed to update product.');
    //         }
    //     });
    // });
});
