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
            { data: 'weight' },
            {
                data: null,
                orderable: false,
                render: function (data, type, row) {
                    const l = row.length || 0;
                    const w = row.width || 0;
                    const h = row.height || 0;
                    return `${l} x ${w} x ${h}`;
                }
            },
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
                                    <a href="#!" class="dropdown-item edit-product-btn"
                                        data-id="${row.id}"
                                        data-weight="${row.weight ?? 0}"
                                        data-width="${row.width ?? 0}"
                                        data-height="${row.height ?? 0}"
                                        data-length="${row.length ?? 0}"
                                    >
                                        Edit
                                    </a>
                                </li>
                                <li>
                                    <a href="#!" class="dropdown-item view-variant-btn" data-id="${row.id}">
                                        Variant
                                    </a>
                                </li>
                                <li>
                                    <a href="#!" class="dropdown-item edit-image-btn" data-id="${row.id}">
                                        Image
                                    </a>
                                </li>
                                <li>
                                    <a href="#!" class="dropdown-item deactivate-date-btn" data-id="${row.id}">
                                        Deactivate By Date
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
            title: 'Sync Products?',
            text: "This will pull the latest products from MOKA.",
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

            fetch('/product/sync', {
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
                    title: 'Products synced successfully',
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
                    url: `/product/${id}/toggle-active`,
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

    $(document).on('click', '.view-variant-btn', function () {
        const idProduct = $(this).data('id');
        window.open(`/product-variant/${idProduct}`, '_blank');
    });

    $(document).on('click', '.edit-image-btn', function () {
        const idProduct = $(this).data('id');
        window.open(`/product-image/${idProduct}`, '_blank');
    });

    $(document).on('click', '.deactivate-date-btn', function () {
        const idProduct = $(this).data('id');
        window.open(`/product-deactivate-by-date/${idProduct}`, '_blank');
    });

    $(document).on('click', '.edit-product-btn', function () {
        $('#edit_product_id').val($(this).data('id'));
        $('#edit_weight').val($(this).data('weight'));
        $('#edit_width').val($(this).data('width'));
        $('#edit_height').val($(this).data('height'));
        $('#edit_length').val($(this).data('length'));

        $('#editProductModal').modal('show');
    });

    $('#editProductForm').submit(function (e) {
        e.preventDefault();

        const productId = $('#edit_product_id').val();
        const formData = {
            _token: $('input[name="_token"]').val(),
            weight: $('#edit_weight').val(),
            width: $('#edit_width').val(),
            height: $('#edit_height').val(),
            length: $('#edit_length').val(),
        };

        $.ajax({
            url: `/product/${productId}/update`,
            type: 'POST',
            data: formData,
            success: function (res) {
                $('#editProductModal').modal('hide');
                $('#tb_data').DataTable().ajax.reload(null, false);
                alert('Product updated successfully!');
            },
            error: function (xhr) {
                console.error(xhr);
                alert('Failed to update product.');
            }
        });
    });
});
