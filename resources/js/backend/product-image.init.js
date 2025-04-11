Dropzone.autoDiscover = false;

var myDropzone = new Dropzone("#productDropzone", {
    url: `/product-image/${idProduct}`,
    maxFiles: 8,
    maxFilesize: 2, // MB
    acceptedFiles: 'image/*',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    success: function(file, response) {
        location.reload(); // Reload the page to display the new image
    },
    error: function(file, response) {
        alert(response.error || response);
    }
});

// Delete image
document.querySelectorAll('.delete-image').forEach(btn => {
    btn.addEventListener('click', function () {
        const imageId = this.dataset.id;
        fetch(`/product-image/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(res => res.json()).then(data => {
            if (data.success) {
                document.getElementById(`image-${imageId}`).remove();
            } else {
                alert('Failed to delete image.');
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the image.');
        });
    });
});
