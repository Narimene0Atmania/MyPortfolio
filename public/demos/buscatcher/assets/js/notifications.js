/**
 * Notification Handler
 * Handles marking notifications as read and updating the badge count
 */

document.addEventListener("DOMContentLoaded", function() {
    // Handle notification item clicks
    document.querySelectorAll(".notification-item").forEach(item => {
        item.addEventListener("click", function(e) {
            // Don''t prevent default if clicking on dropdown menu
            if (e.target.closest(".dropdown-menu")) {
                return;
            }

            const notificationId = this.getAttribute("data-notification-id");
            const status = this.getAttribute("data-status");

            // If unread, automatically mark as read when clicked
            if (status === "unread") {
                markNotificationAsRead(notificationId);
            }
        });
    });
});

/**
 * Mark a specific notification as read
 */
function markNotificationAsRead(notificationId) {
    const baseUrl = window.location.pathname.includes("/admin") ? "/admin/admin-notifications" : "/university/notifications";
    
    fetch(`${baseUrl}/${notificationId}/mark-as-read`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content,
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the badge count
            updateNotificationBadge();
            
            // Update the item''s visual state
            const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (item) {
                item.classList.remove("bg-light");
                const badge = item.querySelector(".badge.bg-primary");
                if (badge) {
                    badge.remove();
                }
            }
        }
    })
    .catch(error => console.error("Error:", error));
}

/**
 * Mark all notifications as read
 */
function markAllNotificationsAsRead(e) {
    e.preventDefault();
    
    const baseUrl = window.location.pathname.includes("/admin") ? "/admin/admin-notifications" : "/university/notifications";
    
    fetch(`${baseUrl}/mark-all-as-read`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content,
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the badge
            updateNotificationBadge();
            
            // Update all items'' visual state
            document.querySelectorAll(".notification-item.bg-light").forEach(item => {
                item.classList.remove("bg-light");
                const badge = item.querySelector(".badge.bg-primary");
                if (badge) {
                    badge.remove();
                }
            });
        }
    })
    .catch(error => console.error("Error:", error));
}

/**
 * Update the notification badge count
 */
function updateNotificationBadge() {
    const baseUrl = window.location.pathname.includes("/admin") ? "/admin/admin-notifications" : "/university/notifications";
    
    fetch(`${baseUrl}/unread-count`)
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector(".navbar-dropdown.dropdown-notifications .position-absolute.badge");
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? "99+" : data.count;
                    badge.style.display = "inline-block";
                } else {
                    badge.style.display = "none";
                }
            }
        })
        .catch(error => console.error("Error:", error));
}

// Optional: Auto-refresh notification count every 30 seconds
setInterval(updateNotificationBadge, 30000);
