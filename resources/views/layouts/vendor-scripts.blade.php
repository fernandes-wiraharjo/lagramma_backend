<!-- JAVASCRIPT -->
<script src="{{ URL::asset('build/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>
<script>
    function formatIndonesianDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = date.toLocaleString('en-US', { month: 'short' }); // Apr
        const year = date.getFullYear();
        return `${day} ${month}, ${year}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        window.isLoggedIn = @json(auth()->check());
        if (window.isLoggedIn) {
            fetchNotifications();
        }

        function fetchNotifications() {
            fetch('/notifications')
                .then(response => response.json())
                .then(data => {
                    const badgeElems = document.querySelectorAll('.notification-badge');
                    badgeElems.forEach(badge => {
                        // Hide badge if count = 0
                        if ((data.unread_count || 0) === 0) {
                            badge.parentElement.style.display = 'none'; // Hide the whole badge container
                        } else {
                            badge.parentElement.style.display = 'inline-block';
                            badge.textContent = data.unread_count;
                        }
                    });

                    let itemsHtml = '';
                    if(data.notifications && data.notifications.length > 0) {
                        data.notifications.forEach(item => {
                            itemsHtml += `
                            <div class="text-reset notification-item d-block dropdown-item position-relative ${item.read ? '' : 'unread-message'}" data-id="${item.id}">
                                <div class="d-flex">
                                    <div class="avatar-xs me-3 flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                            <i class="bx bx-badge-check"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <a href="${item.url || '#'}" class="stretched-link notification-link">
                                            <h6 class="mt-0 fs-14 mb-2 lh-base">${item.title}</h6>
                                        </a>
                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                            <span><i class="mdi mdi-clock-outline"></i> ${item.time_ago}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                    } else {
                        itemsHtml = `<div class="dropdown-item text-center text-muted">No notifications</div>`;
                    }

                    const container = document.getElementById('notificationItemsTabContent');
                    if (container) {
                        container.innerHTML = `<div data-simplebar style="max-height: 300px;" class="pe-2">${itemsHtml}</div>`;
                    }

                    // Add click listeners to notification links to mark as read
                    document.querySelectorAll('.notification-link').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault(); // prevent immediate navigation
                            const notificationDiv = this.closest('.notification-item');
                            const id = notificationDiv.getAttribute('data-id');
                            const url = this.getAttribute('href');

                            // Call API to mark read
                            fetch(`/notifications/${id}/mark-read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(resp => resp.json())
                            .then(res => {
                                if(res.success) {
                                    // Optionally update UI instantly:
                                    notificationDiv.classList.remove('unread-message');

                                    // Decrease badge count and hide if 0
                                    badgeElems.forEach(badge => {
                                        let currentCount = parseInt(badge.textContent) || 0;
                                        currentCount = Math.max(0, currentCount - 1);
                                        if(currentCount === 0){
                                            badge.parentElement.style.display = 'none';
                                        }
                                        badge.textContent = currentCount;
                                    });

                                    // Redirect after marking read
                                    if(url && url !== '#') {
                                        window.location.href = url;
                                    }
                                }
                            })
                            .catch(err => {
                                console.error('Failed to mark notification as read:', err);
                                // Still navigate even if error
                                if(url && url !== '#') {
                                    window.location.href = url;
                                }
                            });
                        });
                    });
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
        }
    });
</script>

@yield('scripts')
