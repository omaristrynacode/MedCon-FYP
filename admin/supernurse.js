let allNurses = []; // Global array to hold all nurses
let filteredNurses = []; // Global array to hold filtered results
let doctors = []; // Store all doctors
let hospitals = []; // Store all hospitals
let currentPageNurse = 1;
const rowsPerPageNurse = 7; // Number of nurses per page

// Fetch all nurses, doctors, and hospitals data
function fetchAllNurses(status = null) {
    const xhr = new XMLHttpRequest();
    showLoadingSpinner();
    $(".nursestable .pagination").css("display", "none");

    const url = status ? `fetch_nurses.php?status=${status}` : 'fetch_nurses.php';
    xhr.open('GET', url, true);

    xhr.onload = async function () {
        hideLoadingSpinner();
        $(".nursestable .pagination").css("display", "block");

        if (xhr.status === 200) {
            try {
                allNurses = JSON.parse(xhr.responseText);
                filteredNurses = allNurses;

                // Fetch doctors and hospitals in parallel
                await Promise.all([fetchDoctors(), fetchHospitals()]);

                displayNurses(filteredNurses);
                generateNursePagination();
            } catch (error) {
                console.error('Error parsing JSON response:', error);
            }
        } else {
            console.error('Error fetching nurses:', xhr.statusText);
        }
    };

    xhr.onerror = function () {
        hideLoadingSpinner();
        console.error('Request error:', xhr.statusText);
    };

    xhr.send();
}
function filterNurses() {
    const searchTerm = document.getElementById('nurseSearchBar').value.toLowerCase();

    // If the search term is empty, display all nurses
    if (searchTerm === '') {
        filteredNurses = allNurses; // Reset filtered nurses
        currentPageNurse = 1; // Reset to the first page
        displayNurses(filteredNurses); // Show all nurses
        generateNursePagination(); // Regenerate pagination
        return;
    }

    // Filter nurses based on the search term
    filteredNurses = allNurses.filter(nurse => {
        const doctorName = doctors.find(dr => dr.dr_id === nurse.assigned_dr);
        const fullDoctorName = doctorName ? `${doctorName.fname} ${doctorName.lname}` : '';

        const hospital = hospitals.find(hosp => hosp.hosp_id === nurse.hosp_id);
        const hospitalName = hospital ? hospital.name : 'Unknown Hospital';

        return (
            nurse.fname.toLowerCase().includes(searchTerm) ||
            nurse.lname.toLowerCase().includes(searchTerm) ||
            nurse.phone.toLowerCase().includes(searchTerm) ||
            nurse.email.toLowerCase().includes(searchTerm) ||
            fullDoctorName.toLowerCase().includes(searchTerm) ||
            hospitalName.toLowerCase().includes(searchTerm)
        );
    });

    currentPageNurse = 1; // Reset to the first page after filtering
    displayNurses(filteredNurses); // Display the filtered list
    generateNursePagination(); // Regenerate pagination based on filtered results
}


// Fetch all doctors via AJAX
function fetchDoctors() {
    return $.ajax({
        url: 'fetch_drs.php',
        method: 'GET',
        success: function (data) {
            doctors = data;
        },
        error: function (xhr) {
            console.error('Error fetching doctors:', xhr.statusText);
        }
    });
}

// Fetch all hospitals via AJAX
function fetchHospitals() {
    return $.ajax({
        url: 'fetch_hospitals.php',
        method: 'GET',
        success: function (data) {
            hospitals = data;
            console.log(data)
        },
        error: function (xhr) {
            console.error('Error fetching hospitals:', xhr.statusText);
        }
    });
}

// Display nurses for the current page
document.getElementById('prevNursePage').addEventListener('click', () => {
    if (currentPageNurse > 1) {
        currentPageNurse--;
        displayNurses(filteredNurses); // Display the current page of filtered nurses
        updateActiveNursePageButton(currentPageNurse); // Update active page button
    }
});

// Event listener for the next nurse page button
document.getElementById('nextNursePage').addEventListener('click', () => {
    const totalPages = Math.ceil(filteredNurses.length / rowsPerPageNurse);
    if (currentPageNurse < totalPages) {
        currentPageNurse++;
        displayNurses(filteredNurses); // Display the current page of filtered nurses
        updateActiveNursePageButton(currentPageNurse); // Update active page button
    }
});

// Display nurses for the current page
function displayNurses(nurses) {
    const nursesBody = document.getElementById('nurses-list');
    nursesBody.innerHTML = ''; // Clear the table body

    const start = (currentPageNurse - 1) * rowsPerPageNurse;
    const end = start + rowsPerPageNurse;
    const paginatedNurses = nurses.slice(start, end);

    if (paginatedNurses.length === 0) {
        nursesBody.innerHTML = '<tr><td colspan="8">No nurses found.</td></tr>';
        return;
    }

    paginatedNurses.forEach(nurse => {
        const row = document.createElement('tr');

        const doctor = doctors.find(dr => dr.dr_id === nurse.assigned_dr);
        const doctorName = doctor ? `${doctor.fname} ${doctor.lname}` : 'Unknown Doctor';

        const hospitalName = hospitals.find(hosp => hosp.Hospital_ID === nurse.hosp_id)?.hosp_name || 'Unknown Hospital';

        row.innerHTML = `
            <td>${nurse.fname}</td>
            <td>${nurse.lname}</td>
            <td>${nurse.phone}</td>
            <td>${nurse.email}</td>
            <td>${nurse.password}</td>
            <td>${hospitalName}</td>
            <td>${doctorName}</td>
            <td>
                <button class="btn-primary" onclick='openEditNurseModal(${JSON.stringify(nurse)})'><i class='bx bxs-edit'></i></button>
                <a class="btn-danger status" href="javascript:void(0);" onclick="toggleNurseArchive(${nurse.nurse_id}, '${nurse.status}')">
                    <i class='bx bx-archive'></i>
                </a>
            </td>
        `;
        nursesBody.appendChild(row);
    });

    // Disable pagination buttons if on the first or last page
    document.getElementById('prevNursePage').disabled = currentPageNurse === 1;
    document.getElementById('nextNursePage').disabled = currentPageNurse === Math.ceil(nurses.length / rowsPerPageNurse);
}

document.getElementById('flexSwitchCheckNurse').addEventListener('change', function() {
    const isChecked = this.checked; // Get the state of the toggle
    const status = isChecked ? 'archived' : 'active'; // Set status based on toggle state
    fetchAllNurses(status); // Fetch doctors based on the toggle status
    const h4 = $("#toggletitle");
    const title = isChecked ? "Archived Nurse: " : "Active Nurses:";
    h4.text(title);
});
function toggleNurseArchive(nurse_id, currentStatus) {
    const xhr = new XMLHttpRequest();
    const newStatus = currentStatus === 'archived' ? 'active' : 'archived'; // Determine new status
    xhr.open('POST', 'archive_nurse.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                console.log(`Nurse ${nurse_id} status updated to ${newStatus}.`);
                fetchAllNurses(newStatus); // Refresh the list of nurses to reflect changes
            } else {
                console.error('Error updating nurse status:', response.message);
            }
        } else {
            console.error('Request failed:', xhr.statusText);
        }
    };

    xhr.send(`nurse_id=${nurse_id}&status=${newStatus}`);
}

// Open the nurse edit modal and populate it with data
function openEditNurseModal(nurse) {
    document.getElementById('nurseId').value = nurse.nurse_id;
    document.getElementById('nurseFirstName').value = nurse.fname;
    document.getElementById('nurseLastName').value = nurse.lname;
    document.getElementById('nursePhone').value = nurse.phone;
    document.getElementById('nurseEmail').value = nurse.email;

// Populate doctors dropdown
const doctorSelect = document.getElementById('assignedDoctor');
doctorSelect.innerHTML = doctors.map(dr => `
    <option value="${dr.dr_id}" ${dr.dr_id === nurse.assigned_dr ? 'selected' : ''}>${dr.fname} ${dr.lname}</option>
`).join('');

// Populate hospitals dropdown
const hospitalSelect = document.getElementById('nurseHospital');
hospitalSelect.innerHTML = hospitals.map(hosp => `
    <option value="${hosp.Hospital_ID}" ${hosp.Hospital_ID === nurse.hosp_id ? 'selected' : ''}>${hosp.hosp_name}</option>
`).join('');


    const modal = new bootstrap.Modal(document.getElementById('nurseModalCenter'));
    modal.show();
}

// Handle nurse form submission via AJAX
document.getElementById('editNurseForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const nurseId = document.getElementById('nurseId').value;
    const updatedData = {
        nurse_id: nurseId,
        fname: document.getElementById('nurseFirstName').value,
        lname: document.getElementById('nurseLastName').value,
        phone: document.getElementById('nursePhone').value,
        email: document.getElementById('nurseEmail').value,
        assigned_dr: document.getElementById('assignedDoctor').value,
        hosp_id: document.getElementById('nurseHospital').value
    };

    $.ajax({
        type: 'POST',
        url: 'edit_nurse.php',
        data: JSON.stringify(updatedData),
        contentType: 'application/json',
        success: function (response) {
            console.log('Nurse updated successfully:', response);
            const modalElement = document.getElementById('nurseModalCenter');
            const modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();

            fetchAllNurses(); // Refresh nurse list
        },
        error: function (xhr, status, error) {
            console.error('Error updating nurse:', error);
        }
    });
});
// Generate pagination for nurses
function generateNursePagination() {
    const pageNumbersContainer = document.getElementById('nursePageNumbers');
    pageNumbersContainer.innerHTML = ''; // Clear previous pagination

    const totalPages = Math.ceil(filteredNurses.length / rowsPerPageNurse);

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('a');
        button.textContent = i;
        button.classList.add('nursepage-btn');

        button.addEventListener('click', () => {
            currentPageNurse = i;
            displayNurses(filteredNurses);
            updateActiveNursePageButton(i);
        });

        pageNumbersContainer.appendChild(button);
    }
}
// Update active class on pagination buttons
function updateActiveNursePageButton(activePage) {
    const pageButtons = document.querySelectorAll('.nursepage-btn');
    pageButtons.forEach((button, index) => {
        button.classList.remove('active');
        if (index + 1 === activePage) {
            button.classList.add('active');
        }
    });
}

// Initialize the page
fetchAllNurses('active');
function openaddmodal(){
    const modal = new bootstrap.Modal(document.getElementById('AddModalCenter'));
    modal.show();
}
$(document).ready(function () {
    console.log("DOM Ready"); // Ensure this is logged to confirm the script runs.

    // Use event delegation on the body or another static parent container.
    $('body').on('click', '#addEntityDropdown .dropdown-item', function (e) {
        e.preventDefault(); // Prevent default anchor behavior
        console.log("Dropdown item clicked"); // Debugging: Confirm click detection

        // Capture the action and entity type
        const action = $(this).data('action');
        const entity = $(this).data('entity');

        console.log(`Action: ${action}, Entity: ${entity}`); // Confirm values

        // Update the form's action attribute
        $('#AddDoctorForm').attr('action', action);

        // Toggle fields based on entity type
        if (entity === 'nurse') {
            $('#hospitalField, #assignedToField').slideDown();
        } else {
            $('#hospitalField, #assignedToField').slideUp();
        }
    });

    // Ensure the modal can be closed
   
});
$('.close-button').on('click', function () {
    $('#AddModalCenter').modal('hide');
});
function addnurse(){
    $("#dropdowntbtn").text("Add Nurse")
    $("#hospitalField").css("display", "block");
    $("#assignedToField").css("display", "block");
    $("#specialty").css("display", "none");
    $("#dos").css("display", "none");
    $('#AddDoctorForm').attr('action', "add_nurse.php");
    $('#newdos').prop('required', false);
    $('#newspecialty').prop('required', false);
    $('#assto').prop('required', true);
    $('#hosp').prop('required', true);

}
function adddoctor(){
    $("#dropdowntbtn").text("Add Doctor")
    $("#hospitalField").css("display", "none");
    $("#assignedToField").css("display", "none");
    $("#specialty").css("display", "block");
    $("#dos").css("display", "block");
    $('#AddDoctorForm').attr('action', "add_doctor.php");
    $('#newdos').prop('required', true);
    $('#newspecialty').prop('required', true);
    $('#assto').prop('required', false);
    $('#hosp').prop('required', false);
}
// Fetch distinct hospitals and display them as bubbles
// Fetch and display hospital bubbles
function fetchAndDisplayHospitalBubbles() {
    $.ajax({
        url: 'fetch_hospitals.php',
        method: 'GET',
        success: function (data) {
            hospitals = data; // Store hospitals globally
            const bubblesContainer = document.getElementById('hospbubbles');

            // Clear previous bubbles
            bubblesContainer.innerHTML = '';

            // Create a "Show All" bubble
            const allBubble = createBubble('All Hospitals', 'all');
            bubblesContainer.appendChild(allBubble);

            // Generate a bubble for each hospital
            hospitals.forEach(hospital => {
                const bubble = createBubble(hospital.hosp_name, hospital.Hospital_ID);
                bubblesContainer.appendChild(bubble);
            });

            // Add event listener to all bubbles for filtering
            $('.bubble').on('click', function () {
                // Remove 'selected' class from all bubbles
                $('.bubble').removeClass('selected');
                // Add 'selected' class to the clicked bubble
                $(this).addClass('selected');

                const hospitalId = $(this).data('id');
                filterNursesByHospital(hospitalId); // Filter nurses by hospital
            });
        },
        error: function (xhr) {
            console.error('Error fetching hospitals:', xhr.statusText);
        }
    });
}

// Helper function to create a bubble element
function createBubble(text, id) {
    const bubble = document.createElement('div');
    bubble.classList.add('bubble', 'button');
    bubble.textContent = text;
    bubble.setAttribute('data-id', id);
    return bubble;
}

// Filter nurses by the selected hospital
function filterNursesByHospital(hospitalId) {
    currentPageNurse = 1; // Reset to the first page

    if (hospitalId === 'all') {
        filteredNurses = allNurses; // Reset to all nurses
    } else {
        filteredNurses = allNurses.filter(nurse => nurse.hosp_id === hospitalId);
    }

    displayNurses(filteredNurses); // Display the filtered nurses
    generateNursePagination(); // Regenerate pagination
}
document.getElementById('prevNursePage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        displayDoctors(filteredDoctors); // Display current page of filtered doctors
        updateActivePageButton(currentPage); // Update active page
    }
});

document.getElementById('nextNursePage').addEventListener('click', () => {
    const totalPages = Math.ceil(filteredDoctors.length / rowsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayDoctors(filteredDoctors); // Display current page of filtered doctors
        updateActivePageButton(currentPage); // Update active page
    }
});
// Initialize hospital bubbles on page load
fetchAndDisplayHospitalBubbles();
