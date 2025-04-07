document.addEventListener('DOMContentLoaded', function () {
    let table = new DataTable('#tb_data', {
        processing: true,
        serverSide: true,
        ajax: '/modifier/list',
        columns: [
            { data: 'moka_id_modifier', name: 'moka_id_modifier' },
            { data: 'name', name: 'name' },
            {
                data: 'is_active',
                name: 'is_active',
                render: function (data) {
                    return data == 1 ? 'True' : 'False';
                }
            }
        ]
    });

    const syncBtn = document.getElementById('syncBtn');
    const btnText = document.getElementById('syncBtnText');
    const spinner = document.getElementById('syncBtnSpinner');

    syncBtn.addEventListener('click', function () {
        Swal.fire({
            title: 'Sync Modifiers?',
            text: "This will pull the latest modifiers from MOKA.",
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

            fetch('/modifier/sync', {
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
                    title: 'Modifiers synced successfully',
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
});
