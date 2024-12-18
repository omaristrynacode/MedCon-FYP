$(document).ready(function() {
    // Load notifications on page load
    $.ajax({
        url: "patient_noti.php",
        type: "GET",
        success: function (data) {
            const dropdown = $("#notificationDropdown");
            dropdown.empty(); // Clear old notifications

            if (data.length > 0) {
                data.forEach(notification => {
                    if(notification.days_left > 0){
                    dropdown.append(`
                     <div class="dropdown-item" data-id="${notification.noti_id}">
                       
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <p><strong>In ${notification.days_left} days</strong></p>
                            <button class="delete-btnNoti" data-id="${notification.noti_id}" style="background: none; border: none; color: red; font-weight: lighter; cursor: pointer;">Clear</button>
                        </div>
                        <p style="font-weight: lighter;">${notification.reason} - (${notification.date})</p>
                        <hr>
                    </div>
                    `);
                    }
                });
            } else {
                dropdown.append("<div class='dropdown-item'>No notifications found.</div>");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching notifications:", error);
        }
    });

    // Toggle dropdown on hover
    $(".notif").on("click", function(){
        $("#notificationDropdown").toggle();
        $(".notif").toggleClass("active");
    });

    // Use event delegation to bind the click event to the parent container
    $(document).on("click", ".delete-btnNoti", function() {
        const notiId = $(this).data("id");
        console.log(notiId);
        // Send an AJAX request to delete the notification
        $.ajax({
            url: "../Dr_side/delete_notification.php",
            type: "POST",
            data: { noti_id: notiId },
            success: function () {
                    // Remove the notification from the DOM
                    $(`.dropdown-item[data-id="${notiId}"]`).remove();
                    if($("#notificationDropdown").empty()){
                        $("#notificationDropdown").append("<div class='dropdown-item'>No notifications found.</div>");
                        console.log("empty")
                    }
                  
            },
            error: function (xhr, status, error) {
                console.error("Error deleting notification:", error);
            }
        });
    });
});
