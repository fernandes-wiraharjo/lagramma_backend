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
        alert(`Uploaded: ${file.name}`);
    },
    error: function(file, response) {
         // Check if response is a string or an object
         let message = typeof response === 'string'
         ? response
         : response.message || 'Something went wrong';

         alert(`Error uploading ${file.name}: ${message}`);

        // Remove file preview if upload failed
        this.removeFile(file);
    },
    queuecomplete: function() {
        // Show success message
        alert('Upload success! Please wait, the page will reload shortly.');
        location.reload();
    }
});

$(document).on('click', '.set-main-image', function () {
    let imageId = $(this).data('id');
    $.ajax({
        url: `/product-image/set-main/${imageId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            alert('Main image set successfully.');
            location.reload();
        },
        error: function () {
            alert('Failed to set as main image.');
        }
    });
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
