
let map;
let marker;

function initMap() {
    const pontianakBounds = {
        north: 0.15,
        south: -0.15,
        east: 109.45,
        west: 109.2
    };

    const pontianak = { lat: -0.0263, lng: 109.3414 };

    map = new google.maps.Map(document.getElementById("map"), {
        center: pontianak,
        zoom: 14,
        restriction: {
            latLngBounds: pontianakBounds,
            strictBounds: false,
        },
    });

    marker = new google.maps.Marker({
        position: pontianak,
        map,
        draggable: true
    });

    // Update lat/lng on drag
    marker.addListener("dragend", (e) => {
        updateLatLng(e.latLng.lat(), e.latLng.lng());
    });

    // Update marker on map click
    map.addListener("click", (e) => {
        marker.setPosition(e.latLng);
        updateLatLng(e.latLng.lat(), e.latLng.lng());
    });

    // Address Autocomplete
    const input = document.getElementById("search-address");
    const searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;

        const place = places[0];
        if (!place.geometry || !place.geometry.location) {
            console.log("Returned place contains no geometry");
            return;
        }

        marker.setPosition(place.geometry.location);
        map.panTo(place.geometry.location);
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setZoom(15);
        }

        updateLatLng(place.geometry.location.lat(), place.geometry.location.lng());
    });

    // Set default lat/lng
    updateLatLng(pontianak.lat, pontianak.lng);
}

function updateLatLng(lat, lng) {
    document.getElementById("latitude").value = lat.toFixed(6);
    document.getElementById("longitude").value = lng.toFixed(6);
}

window.initMap = initMap;

document.addEventListener('DOMContentLoaded', function () {
    $('#addAddressModal').on('shown.bs.modal', function () {
        //for region search
        $('#region-select').select2({
            dropdownParent: $('#addAddressModal .modal-body'),
            // width: '100%',
            placeholder: 'City/District/Subdistrict/Postal Code...',
            minimumInputLength: 3,
            ajax: {
                url: '/account/komerce/search-region',
                delay: 250,
                dataType: 'json',
                headers: {
                    'x-api-key': komerceApiKey
                },
                data: function (params) {
                    return {
                        keyword: params.term // search term
                    };
                },
                processResults: function (data) {
                    // Assuming API returns { data: [{ id, label }] }
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.label
                            };
                        })
                    };
                }
            }
        });
    });

    $('#region-select').on('select2:select', function (e) {
        let data = e.params.data;
        $('#region-id').val(data.id);
        $('#region-label').val(data.text);
    });

    // ADD/UPDATE Address
    $('#createAddress-form').on('submit', function (e) {
        e.preventDefault();

        const id = $('#addressid-input').val(); // check if editing

        const formData = {
            label: $('#addaddress-Name').val(),
            address: $('#addaddress-textarea').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            region_id: $('#region-id').val(),
            region_label: $('#region-label').val(),
        };

        const url = id ? `/account/addresses/${id}` : '/account/addresses';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function (response) {
                $('#addAddressModal').modal('hide');
                location.reload(); // refresh to show new address
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // DELETE Address
    let deleteAddressId = null;

    $(document).on('click', '[data-bs-target="#removeAddressModal"]', function () {
        const card = $(this).closest('.col-lg-6');
        deleteAddressId = card.find('input[type="radio"]').val();
    });

    $('#remove-address').on('click', function () {
        if (!deleteAddressId) return;

        $.ajax({
            url: `/account/addresses/${deleteAddressId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                $('#removeAddressModal').modal('hide');
                location.reload();
            }
        });
    });

    // EDIT: populate modal with address
    $(document).on('click', '[data-bs-target="#addAddressModal"]', function () {
        const isEdit = $(this).data('edit') === true;

        if (isEdit) {
            $('#addAddressModalLabel').text('Edit Address');
            $('#addNewAddress').text('Update');
            const card = $(this).closest('.col-lg-6');
            const id = card.find('input[type="radio"]').val();

            $.get(`/account/addresses/${id}`, function (data) {
                $('#addressid-input').val(data.id);
                $('#addaddress-Name').val(data.label);
                $('#addaddress-textarea').val(data.address);
                $('#latitude').val(data.latitude);
                $('#longitude').val(data.longitude);
                $('#region-id').val(data.region_id);
                $('#region-label').val(data.region_label);

                const newOption = new Option(data.region_label, data.region_id, true, true);
                $('#region-select').append(newOption).trigger('change');
            });
        }
    });

    // Reset form when modal is closed
    $('#addAddressModal').on('hidden.bs.modal', function () {
        $('#addAddressModalLabel').text('Add New Address');
        $('#addNewAddress').text('Add');
        $('#createAddress-form')[0].reset();
        $('#region-select').val(null).trigger('change');
        $('#addressid-input').val('');
    });
});
