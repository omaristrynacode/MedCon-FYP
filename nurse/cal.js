$('.calendar').on('click', '.day_num:not(.ignore)', function() {
    // Check if the clicked day has an event inside it
    if ($(this).find('.event').length === 0) {
        console.log("No events for this day");
        createModal();
    } else {
        console.log("Event exists for this day");
    }
});


$('.calendar').on('click', '.event', function() {
    const date = $(this).find('.id').text();
console.log(date)
    // Send an AJAX request to the server to retrieve the event information
    $.ajax({
        type: 'POST',
        url: 'get_event_info.php',
        data: { date: date },
        success: function(data) {
            try {
                const eventInfo = JSON.parse(data);
                createModal(eventInfo, date);
            } catch (error) {
                console.error("Failed to parse response:", error);
                showErrorModal("Failed to retrieve event information.");
            }
        },
        error: function(xhr) {
            console.error("AJAX request failed:", xhr.responseText);
            showErrorModal("An error occurred while fetching event details.");
        }
    });
});

function createModal(eventInfo, date) {
    const htmlmodal = document.getElementById("exampleModalCenter");
    let modalContent = eventInfo ? getExistingAppointmentHtml(eventInfo) : getNoAppointmentHtml();
    htmlmodal.innerHTML = `
       <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Appointment Details</h5>
                </div>
                <div class="modal-body">
                    ${modalContent}
                </div>
                <div class="modal-footer">
                    <div class="container">
                        <div class="row" style="padding: 5px;">
                            <button type="button" class="btn btn-primary modal-btn-edit" style="width: 49%; margin: 2px;">
                                <i class='bx bxs-edit' style="margin-right: 5px;"></i>Edit Appointment
                            </button>
                            <button type="button" class="btn btn-primary modal-btn-notify" style="width: 49%; margin: 2px;">
                                <i class='bx bxs-bell-ring' style="margin-right: 5px;"></i>Notify Patient
                            </button>
                            <button type="button" class="btn btn-danger  btn-block mt-3" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    // Check if appointment exists and hide buttons if necessary
    if ($(`.day_num:has(.id:contains(${date})) .event.green`).length > 0 || !eventInfo ) {
        $("#exampleModalCenter .modal-btn-edit").hide();
        $(".modal-btn-notify").hide();
    $("#exampleModalCenter .btn-danger").replaceWith(`  
       <button type="button" class="btn btn-danger btn-block mt-3" data-bs-dismiss="modal">Close</button>
      `);

    } else {
        $(".modal-btn-edit").show();
        $(".modal-btn-notify").show();
    }
 
    // Populate the modal fields if event info exists
    if (eventInfo) {
        $('#patient-id').val(`${eventInfo.fname} ${eventInfo.lname}`);
        $('#datecal').val(eventInfo.date);
        $('#timecal').val(eventInfo.time);
        $('#reasoncal').val(eventInfo.reason);
        $('#hospitalcal').val(eventInfo.hosp_name);
    }
    $(".modal-btn-edit").on("click", function () {
        makeFieldsEditable(eventInfo, date);
    });
    $(".modal-btn-notify").on("click",function(){
        sendNoti(eventInfo, date);
    })
    // Initialize and show the modal
    const modal = new bootstrap.Modal(htmlmodal, {
        backdrop: 'static',
        keyboard: false
    });
    modal.show();
}

function showErrorModal(message) {
    const htmlmodal = document.getElementById("exampleModalCenter");
    htmlmodal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                </div>
                <div class="modal-body">
                    <h3>${message}</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>`;
    const modal = new bootstrap.Modal(htmlmodal);
    modal.show();
    modal._element.addEventListener('hidden.bs.modal', function () {
        modal.hide();  // Hide the modal when "Close" is clicked
        $('body').removeClass('modal-open');  // Remove Bootstrap's "modal-open" class from body
        $('.modal-backdrop').remove();  
    });
}

// Helper function to get HTML for existing appointment details
function getExistingAppointmentHtml(eventInfo) {
    return `
        <div class="form-group" id="exist">
            <label>Patient Name:</label>
            <input type="text" class="form-control" disabled id="patient-id" value="${eventInfo.fname} ${eventInfo.lname}">
            <label>Reason:</label>
            <textarea class="form-control" disabled id="reasoncal">${eventInfo.reason}</textarea>
            <label>Date:</label>
            <input type="date" class="form-control" disabled id="datecal" value="${eventInfo.date}">
            <label>Time:</label>
            <input type="time" class="form-control" disabled id="timecal" value="${eventInfo.time}">
            <label>Hospital:</label>
            <input type="text" class="form-control" disabled id="hospitalcal" value="${eventInfo.hosp_name}">
            
        </div>`;

}

// Helper function to display "No Appointment" message
function getNoAppointmentHtml() {
    return `
        <div class="form-group" id="notexist">
            <h3>No Appointment For Today</h3>
        </div>`;
}
function makeFieldsEditable(eventInfo, appt_id) {
    // Make the fields editable
    console.log(appt_id);
    $('#reasoncal, #datecal, #timecal').prop('disabled', false);

    // Replace "Edit" button with "Save" and "Cancel" buttons
    $('#exampleModalCenter .modal-btn-notify').replaceWith(`
        <button type="button" class="btn btn-secondary modal-btn-cancel" style="width: 32%; margin: 2px;">
            <i class='bx bx-x-circle' style="margin-right: 5px;"></i>Cancel
        </button>  
        <button type="button" class="btn btn-warning modal-btn-delete" style="width: 32%; margin: 2px;">
            <i class='bx bxs-trash' style="margin-right: 5px;"></i>Delete
        </button>
    `);
    $('#exampleModalCenter .modal-btn-edit').replaceWith(`
        <button type="button" class="btn btn-success modal-btn-save" style="width: 33%; margin: 2px;">
            <i class='bx bx-save' style="margin-right: 5px;"></i>Save
        </button>
    `);

    // Handle "Save" button click
    $('.modal-btn-save').on('click', function () {
        saveUpdatedAppointment(eventInfo, appt_id);
    });

    // Handle "Delete" button click
    $('.modal-btn-delete').on('click', function () {
        // Replace modal content with delete confirmation dialog
        const htmlmodal = document.getElementById("exampleModalCenter");
        htmlmodal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Appointment?</h5>
                    </div>
                    <div class="modal-body">
                        <h4>Are you sure you want to delete this appointment?</h4>
                    </div>
                    <div class="modal-footer">
                        <div class="container">
                            <div style="padding: 5px;" class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" id="deleteappt" class="btn btn-danger" style="margin-left: 5px;">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        // Add a click event listener to the "Delete" button after it has been added to the DOM
        $('#deleteappt').on('click', function () {
            deleteAppointment(appt_id); // Call the delete function with the appropriate appointment ID
            $('#exampleModalCenter').modal('hide'); // Close the modal
        });
    });

    // Handle "Cancel" button click
    $('.modal-btn-cancel').on('click', function () {
        cancelEdit(eventInfo); // Revert changes
    });
}

function deleteAppointmentadapt(){
    deleteAppointment(date);
}

function saveUpdatedAppointment(eventInfo, appt_id) {
    // Collect updated values from the input fields
    const updatedData = {
        appt_id: appt_id,
        patient_id: $('#patient-id').val(),
        reason: $('#reasoncal').val(),
        date: $('#datecal').val(),
        time: $('#timecal').val(),
        hosp_name: $('#hospitalcal').val()
    };

    // Send the updated data to the server
    $.ajax({
        type: 'POST',
        url: 'update_appointment.php', // PHP file to handle the update
        data: updatedData,
        success: function(response) {
            console.log("Update successful:", response);
            showSuccessModal("Appointment updated successfully!");
            
        },
        error: function(xhr) {
            console.error("Update failed:", xhr.responseText);
            showErrorModal("Failed to update appointment.");
        }
    });

    // After saving, disable the fields again and replace buttons
    $('#patient-id, #reasoncal, #datecal, #timecal, #hospitalcal').prop('disabled', true);
    $('#exampleModalCenter .modal-btn-save').replaceWith(`
        <button type="button" class="btn btn-primary modal-btn-edit" style="width: 49%; margin: 2px;">
            <i class='bx bxs-edit' style="margin-right: 5px;"></i>Edit Appointment
        </button>
    `);
    $("#exampleModalCenter .modal-btn-delete").replaceWith(``);
    $('#exampleModalCenter .modal-btn-cancel').replaceWith(`   
        <button type="button" class="btn btn-primary modal-btn-notify" style="width: 49%; margin: 2px;">
            <i class='bx bxs-bell-ring' style="margin-right: 5px;"></i>Notify Patient
        </button>`)
    $('#exampleModalCenter .modal-btn-edit').on("click", function () {
        makeFieldsEditable(eventInfo);
    });
}

function cancelEdit(eventInfo) {
    // Revert changes by resetting fields to their original values
    $('#patient-id').val(`${eventInfo.fname} ${eventInfo.lname}`);
    $('#reasoncal').val(eventInfo.reason);
    $('#datecal').val(eventInfo.date);
    $('#timecal').val(eventInfo.time);
    $('#hospitalcal').val(eventInfo.hosp_name);

    // Disable the fields again
    $('#patient-id, #reasoncal, #datecal, #timecal, #hospitalcal').prop('disabled', true);

    // Replace "Save" and "Cancel" buttons with "Edit" button
    $('#exampleModalCenter .modal-btn-save').replaceWith(`
        <button type="button" class="btn btn-primary modal-btn-edit" style="width: 49%; margin: 2px;">
            <i class='bx bxs-edit' style="margin-right: 5px;"></i>Edit Appointment
        </button>
    `);
    $("#exampleModalCenter .modal-btn-delete").replaceWith(``);

    $('#exampleModalCenter .modal-btn-cancel').replaceWith(`   
        <button type="button" class="btn btn-primary modal-btn-notify" style="width: 49%; margin: 2px;">
            <i class='bx bxs-bell-ring' style="margin-right: 5px;"></i>Notify Patient
        </button>`)
    $('.modal-btn-edit').on("click", function () {
        makeFieldsEditable(eventInfo);
    });
}

function showSuccessModal(message) {
    const htmlmodal = document.getElementById("exampleModalCenter");
    htmlmodal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                </div>
                <div class="modal-body">
                    <h3>${message}</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-close-modal">Close</button>
                </div>
            </div>
        </div>`;
    
    // Initialize the modal
    const modal = new bootstrap.Modal(htmlmodal, {
        backdrop: 'static',
        keyboard: false
    });
    modal.show();

    // Handle the close button
    document.querySelector('.btn-close-modal').addEventListener('click', function () {
        modal.hide();  // Hide the modal when "Close" is clicked
        $('body').removeClass('modal-open');  // Remove Bootstrap's "modal-open" class from body
        $('.modal-backdrop').remove();  // Remove the modal backdrop
        location.reload(); 
    });
}
function sendNoti(eventInfo, appt_id) {
    var reason = eventInfo.reason;
    if (!reason || !appt_id) {
        console.error("Reason or Appointment ID is missing.");
        return; // Exit if either value is missing
    }
    var nurse_id = $("#nurseid").val();

    $.ajax({
        type: 'POST',
        url: 'sendNoti.php',
        data: { appt_id, reason, nurse_id },
        success: function(data) {
       
                showSuccessModal("Notification has been succefully pushed!");
        },
        error: function(xhr, status, error) {
         if(error = "Conflict"){
            showErrorModal("A Notification for this appointment had already been pushed.");

         }else{
            showErrorModal("An error occurred while sending the notification.");
         }
        }
    });
}
    
function deleteAppointment(appt_id){
    $.ajax({
        type: 'POST',
        url: 'deleteAppt.php',
        data: { appt_id },
        success: function(data) {

            location.reload();
            },
            error: function(xhr, status, error) {
                showErrorModal("An error occurred while deleting the appointment.");
                }
            });
}
    
//////////////////////////////////////////////////////////////////////////////////////////////////////NEXT MONTH////////////////////////////////////////////////////

$(document).ready(function() {
    // Event listeners for navigation buttons
    $('#prevMonth').on('click', function() {
        updateCalendar(-1);
        console.log("prev")
    });

    $('#left').on('click', function() {
        updateCalendar(1);
        console.log("nxt")

    });

    // Function to update the calendar
    function updateCalendar(monthOffset) {
        const calendar = $('#calendarnext');
        const year = parseInt(calendar.attr('data-year'));
        const month = parseInt(calendar.attr('data-month'));

        let newMonth = month + monthOffset;
        let newYear = year;

        if (newMonth < 1) {
            newMonth = 12;
            newYear = year - 1;
        } else if (newMonth > 12) {
            newMonth = 1;
            newYear = year + 1;
        }

        // Update the calendar HTML
        $.ajax({
            type: "POST",
            url: "update_calendar.php",
            data: { year: newYear, month: newMonth },
            dataType: "html"
        })
        .done(function(html) {
            // Update calendar content and data attributes
            $('.calendar').html(html);
            calendar.attr('data-year', newYear);
            calendar.attr('data-month', newMonth);
            $('#prevMonth').on('click', function() {
                updateCalendar(-1);
                console.log("prev")
            });
        
            $('#left').on('click', function() {
                updateCalendar(1);
                console.log("nxt")
        
            });
            // Re-bind event listener for day clicks
            bindDayClick();
        })
        .fail(function(xhr, status, error) {
            console.error("Error updating calendar:", error);
        });
    }

    // Function to bind click event for day numbers
    function bindDayClick() {
        $('.calendar .event').on('click', function() {
            const date = $(this).find('.id').text();
            fetchEventInfo(date);
        });
    }

    // Function to fetch event information
    function fetchEventInfo(date) {
        $.ajax({
            type: 'POST',
            url: 'get_event_info.php',
            data: { date: date },
            success: function(data) {
                try {
                    const eventInfo = JSON.parse(data);
                    createModal(eventInfo);
                } catch (error) {
                    console.error("Failed to parse response:", error);
                    showErrorModal("Failed to retrieve event information.");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", xhr.responseText);
                showErrorModal("An error occurred while fetching event details.");
            }
        });
    }

    // Function to create and display the modal
    function createModal(eventInfo) {
        const htmlmodal = document.getElementById("exampleModalCenter");
        let modalContent;

        if (eventInfo.patient_id) {
            modalContent = getExistingAppointmentHtml(eventInfo);
        } else {
            modalContent = getNoAppointmentHtml();
        }

        htmlmodal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Appointment Details</h5>
                    </div>
                    <div class="modal-body">
                        ${modalContent}
                    </div>
                    <div class="modal-footer">
                        <div class="container">
                            <div class="row" style="padding: 5px;">
                                <button type="button" class="btn btn-primary" style="width: 49%; margin: 2px;" id="modalbtn"><i class='bx bxs-edit' style="margin-right: 5px;"></i>Edit Appointment</button>
                                <button type="button" class="btn btn-primary" style="width: 49%; margin: 2px; margin-left: 10px;" id="modalbtn1"><i class='bx bxs-bell-ring' style="margin-right: 5px;"></i>Notify Patient</button>
                                <button type="button" class="btn btn-danger btn-block mt-3" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            if ($(`.day_num:has(.id:contains(${date})) .event.green`).length > 0) {
                $("#modalbtn").hide(); // Hide the Edit button
                $("#modalbtn1").hide(); // Hide the Notify button
            } else {
                $("#modalbtn").show(); // Show the Edit button
                $("#modalbtn1").show(); // Show the Notify button
            }
        
        // Show the modal
        const modal = new bootstrap.Modal(htmlmodal);
        modal.show();
    modal._element.addEventListener('hidden.bs.modal', function () {
        modal.hide();  // Hide the modal when "Close" is clicked
        $('body').removeClass('modal-open');  // Remove Bootstrap's "modal-open" class from body
        $('.modal-backdrop').remove();
          
    });
       
        // Bind edit button functionality
        bindEditButton(eventInfo);
    }

    // Function to bind edit button functionality
    function bindEditButton(eventInfo) {
        $('#modalbtn').on('click', function() {
            toggleEditableFields(true);
        });
        
        // Assuming you have a save button to send the updated information
        $('#modalbtnSave').on('click', function() {
            const updatedInfo = {
                patient_id: eventInfo.patient_id,
                fname: $('#patient-id').val(),
                reason: $('#reasoncal').val(),
                date: $('#datecal').val(),
                time: $('#timecal').val(),
                hosp_name: $('#hospitalcal').val()
            };
            saveUpdatedInfo(updatedInfo);
        });
    }

    // Function to toggle editable fields
    function toggleEditableFields(isEditable) {
        $('#reasoncal, #datecal, #timecal').prop('disabled', !isEditable);
    }

    // Function to save updated information
    function saveUpdatedInfo(updatedInfo) {
        $.ajax({
            type: 'POST',
            url: 'save_event_info.php',
            data: updatedInfo,
            success: function(response) {
                showSuccessModal("Appointment updated successfully!");
                toggleEditableFields(false);
            },
            error: function(xhr, status, error) {
                console.error("Failed to save event info:", error);
                showErrorModal("Failed to update appointment.");
            }
        });
    }
});

// Add event listener to the modalbtn button
