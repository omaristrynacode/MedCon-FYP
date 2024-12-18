$(document).ready(function() {
    // Load notifications on page load
    $.ajax({
        url: "nurse_noti.php",
        type: "GET",
        success: function (data) {
            const dropdown = $("#notificationDropdown");
            dropdown.empty(); // Clear old notifications
    
            if (data.length > 0) {
                data.forEach(notification => {
                    if (notification.reason !== "You have a new appointment request.") {
                        dropdown.append(`
                            <div class="dropdown-item" data-id="${notification.noti_id}">
                               <div style="display: flex; justify-content: space-between; align-items: center;">
                                   <p><strong>${notification.patient_name}</strong></p>
                                   <button class="delete-btnNoti" data-id="${notification.noti_id}" style="background: none; border: none; color: red; font-weight: lighter; cursor: pointer;">Clear</button>
                               </div>
                               <p style="font-weight: lighter;">${notification.reason}</p>
                               <hr>
                           </div>
                        `);
                    } else {
                        dropdown.append(`
                            <div class="dropdown-item" data-id="${notification.noti_id}">
                             <span class="id" hidden>${notification.noti_id}</span>
                               <div style="display: flex; justify-content: space-between; align-items: center;">
                                   <p><strong>${notification.patient_name}</strong></p>
                                    <div>
                                        <button class="view-btnNoti" data-id="${notification.noti_id}" style="background: none; border: none; color: blue; font-weight: lighter; cursor: pointer;">View</button>
                                        <button class="delete-btnNoti" data-id="${notification.noti_id}" style="background: none; border: none; color: red; font-weight: lighter; cursor: pointer;">Clear</button>
                                    </div>    
                               </div>
                               <p style="font-weight: lighter;">${notification.reason}</p>
                               <hr>
                           </div>
                        `);
                    }
                });
                $(".delete-btnNoti").on("click", function () {
                    const notificationId = $(this).data("id");
                    deleteNoti(notificationId);
                });
    
                $(".view-btnNoti").on("click", function () {
                    const notificationId = $(this).data("id");
                
                    // Send an AJAX request to fetch appointment details for the given notification ID
                    $.ajax({
                        type: "POST",
                        url: "get_requested_appt.php",
                        data: { notification_id: notificationId },
                        success: function (data) {
                            try {
                                const eventInfo = JSON.parse(data);
                                createModalReq(eventInfo, notificationId);
                            } catch (error) {
                                console.error("Failed to parse event info:", error);
                                alert("Error fetching event details.");
                            }
                        },
                        error: function (xhr) {
                            console.error("Error fetching event info:", xhr.responseText);
                        },
                    });
                });
                
                
            } else {
                dropdown.append("<div class='dropdown-item'>No notifications found.</div>");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching notifications:", error);
        }
    });
    
    function createModalReq(eventInfo, notiID) {
        const htmlmodal = document.getElementById("exampleModalCenter");
    
        let modalContent = `
            <div class="form-group">
                <input type="text" class="form-control" id="noti-id" value="${notiID}" hidden>

                <label for="patient-id">Patient Name</label>
                <input type="text" class="form-control" id="patient-id" value="${eventInfo.fname} ${eventInfo.lname}" readonly>
            </div>
            <div class="form-group">
                <label for="datecal">Requested Date</label>
                <input type="date" class="form-control" id="datecal" value="${eventInfo.date}" readonly>
            </div>
            <div class="form-group">
                <label for="timecal">Requested Time</label>
                <input type="time" class="form-control" id="timecal" value="${eventInfo.time}" readonly>
            </div>
            <div class="form-group">
                <label for="reasoncal">Reason</label>
                <input type="text" class="form-control" id="reasoncal" value="${eventInfo.reason}" readonly>
            </div>
            <div class="form-group">
                <label for="hospitalcal">Hospital</label>
                <input type="text" class="form-control" id="hospitalcal" value="${eventInfo.hosp_name}" readonly>
            </div>`;
    
        htmlmodal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Requested Appointment</h5>
                    </div>
                    <div class="modal-body">
                        ${modalContent}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-accept-appointment" id='acceptAppt' data-id="${eventInfo.appt_id}">Accept Appointment</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>`;
            console.log(eventInfo.hosp_name)
    
        // Show the modal
        const modal = new bootstrap.Modal(htmlmodal, {
            backdrop: "static",
            keyboard: false,
        });
        modal.show();
    
        // Attach handler for the "Accept Appointment" button
        $(".btn-accept-appointment").on("click", function () {
            acceptAppointment(eventInfo);
            deleteNoti(notiID);
        });
     
    }
    
    function acceptAppointment(eventInfo) {
        console.log(eventInfo)
        $.ajax({
            type: "POST",
            url: "add_appt.php",
            data: {
                patient: eventInfo.patient_id,
                date: eventInfo.date,
                time: eventInfo.time,
                reason: eventInfo.reason,
                hospital: eventInfo.hosp_id,
            },
            success: function (response) {
                try {
                  
                    alert("Appointment successfully added!");
                    $("#exampleModalCenter").modal("hide"); // Close the modal
                    location.reload(); // Reload calendar or notification list
                } catch (error) {
                    console.error("Error:", error);
                    alert("Failed to accept appointment.");
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
                alert("An error occurred while accepting the appointment.");
            },
        });
    }
    

    // Toggle dropdown on hover
    $(".notif").on("click", function(){
        $("#notificationDropdown").toggle();
        $(".notif").toggleClass("active");
    });

    function deleteNoti(notificationID){
        $.ajax({
            url: "../Dr_side/delete_notification.php",
            type: "POST",
            data: { noti_id: notificationID },
            success: function () {
                    // Remove the notification from the DOM
                    $(`.dropdown-item[data-id="${notificationID}"]`).remove();

                  
            },
            error: function (xhr, status, error) {
                console.error("Error deleting notification:", error);
            }
        });
    }
});
