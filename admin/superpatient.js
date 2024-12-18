function loadscript(src){
    const script = document.createElement("script");
    script.src = src;
    document.head.prepend(script);
}
loadscript("super.js");
loadscript("supernurse.js");


let allPatients = []; // Global array to hold all patients
let filteredPatients = []; // Global array to hold filtered results
let currentPagePatient = 1;
const rowsPerPagePatient = 7; // Number of patients per page

console.log("YOOOOOOOOOOOOO")
// Fetch all patients, doctors, and hospitals data
function fetchAllPatients(status = null) {
    const xhr = new XMLHttpRequest();
    showLoadingSpinner();
    $(".patientstable .pagination").css("display", "none");

    const url = status ? `fetch_patients.php?status=${status}` : 'fetch_patients.php';
    xhr.open('GET', url, true);

    xhr.onload = async function () {
        hideLoadingSpinner();
        $(".patientstable .pagination").css("display", "block");

        if (xhr.status === 200) {
            try {
                allPatients = JSON.parse(xhr.responseText);
                filteredPatients = allPatients;

                // Fetch doctors and hospitals in parallel
                await Promise.all([fetchDoctors(), fetchHospitals()]);

                displayPatients(filteredPatients);
                generatePatientPagination();
            } catch (error) {
                console.error('Error parsing JSON response:', error);
            }
        } else {
            console.error('Error fetching patients:', xhr.statusText);
        }
    };

    xhr.onerror = function () {
        hideLoadingSpinner();
        console.error('Request error:', xhr.statusText);
    };

    xhr.send();
}
function showLoadingSpinner() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    loadingSpinner.style.display = 'block'; // Display the spinner
}


// Filter patients by search term
function filterPatients() {
    const searchTerm = document.getElementById('patientSearchBar').value.toLowerCase();

    // If the search term is empty, display all patients
    if (searchTerm === '') {
        filteredPatients = allPatients;
        currentPagePatient = 1;
        displayPatients(filteredPatients);
        generatePatientPagination();
        return;
    }

    // Filter patients based on the search term
    filteredPatients = allPatients.filter(patient => {
        const doctor = doctors.find(dr => dr.dr_id === patient.assigned_dr);
        const doctorName = doctor ? `${doctor.fname} ${doctor.lname}` : '';

        const hospital = hospitals.find(hosp => hosp.hosp_id === patient.hosp_id);
        const hospitalName = hospital ? hospital.hosp_name : 'Unknown Hospital';

        return (
            patient.fname.toLowerCase().includes(searchTerm) ||
            patient.lname.toLowerCase().includes(searchTerm) ||
            patient.phone.toLowerCase().includes(searchTerm) ||
            patient.email.toLowerCase().includes(searchTerm) ||
            doctorName.toLowerCase().includes(searchTerm) ||
            hospitalName.toLowerCase().includes(searchTerm)
        );
    });

    currentPagePatient = 1;
    displayPatients(filteredPatients);
    generatePatientPagination();
}

// Display patients for the current page
function displayPatients(patients) {
    const patientsBody = document.getElementById('patients-list');
    patientsBody.innerHTML = '';

    const start = (currentPagePatient - 1) * rowsPerPagePatient;
    const end = start + rowsPerPagePatient;
    const paginatedPatients = patients.slice(start, end);

    if (paginatedPatients.length === 0) {
        patientsBody.innerHTML = '<tr><td colspan="8">No patients found.</td></tr>';
        return;
    }

    paginatedPatients.forEach(patient => {
        const row = document.createElement('tr');


        const subcaps = patient.paid.toUpperCase();
 

        row.innerHTML = `
            <td>${patient.fname}</td>
            <td>${patient.lname}</td>
            <td>${patient.phone}</td>
            <td>${patient.email}</td>
            <td>${patient.address}</td>
            <td>${patient.dob}</td>
            <td>${subcaps}</td>
            <td>
                <button class="btn-primary" onclick='openEditPatientModal(${JSON.stringify(patient)})'><i class='bx bxs-edit'></i></button>
            
            </td>
        `;
        patientsBody.appendChild(row);
    });
}
document.getElementById('patientStatusSelect').addEventListener('change', function () {
    const status = this.value; // Get selected value (free, paid, or all)
    currentPagePatient = 1; // Reset to page 1 when filter changes
    fetchFilteredPatients(status); // Fetch and display patients based on filter
});

function fetchFilteredPatients(status) {
    const url = status === 'all' 
        ? 'fetch_patients.php' 
        : `fetch_patients.php?paid_status=${status}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            filteredPatients = data; // Store filtered results globally
            currentPagePatient = 1; // Reset to the first page
            displayPatients(filteredPatients); // Display the first page of results
            generatePatientPagination(); // Regenerate pagination
        })
        .catch(error => console.error('Error fetching patients:', error));
}




// Open the patient edit modal and populate it with data
function openEditPatientModal(patient) {
    document.getElementById('patientId').value = patient.patient_id;
    document.getElementById('patientFirstName').value = patient.fname;
    document.getElementById('patientLastName').value = patient.lname;
    document.getElementById('patientPhone').value = patient.phone;
    document.getElementById('patientAddress').value = patient.address;
    document.getElementById('patientEmail').value = patient.email;
    document.getElementById('patientDOB').value = patient.dob;

console.log(document.getElementById("patientId").value)

    const modal = new bootstrap.Modal(document.getElementById('patientModalCenter'));
    modal.show();
}

document.getElementById('editPatientForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form's default submission behavior

    patientID = document.getElementById('patientId').value;
    const updatedData = {
        patID: patientID,
        patientFname: document.getElementById('patientFirstName').value,
        patientLname: document.getElementById('patientLastName').value,
        patientPhone: document.getElementById('patientPhone').value,
        patientAddy: document.getElementById('patientAddress').value,
        patientEmail: document.getElementById('patientEmail').value,
        patientDOB: document.getElementById('patientDOB').value
    };

    // Send the updated data via AJAX
    $.ajax({
        type: 'POST',
        url: 'edit_patient.php', // Your endpoint for updating doctor data
        data: JSON.stringify(updatedData),
        contentType: 'application/json',
        success: function(response) {
            console.log('Patient updated successfully:', response);
            
            // Close the modal on success using Bootstrap's API
            const modalElement = document.getElementById('patientModalCenter');
            const modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();
            $("#patientSearchBar").val(" ");
            fetchAllPatients("active"); // Refresh the doctor list
        
        },
        error: function(xhr, status, error) {
            console.error('Error updating patient:', error);
        }
    });
});


// Generate pagination for patients
function generatePatientPagination() {
    const pageNumbersContainer = document.getElementById('patientPageNumbers');
    pageNumbersContainer.innerHTML = '';

    const totalPages = Math.ceil(filteredPatients.length / rowsPerPagePatient);

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('a');
        button.textContent = i;
        button.classList.add('patientpage-btn');
        button.addEventListener('click', () => {
            currentPagePatient = i;
            displayPatients(filteredPatients); // Display patients for selected page
            updateActivePatientPageButton(i);
        });

        pageNumbersContainer.appendChild(button);
    }

    // Disable prev/next buttons based on the current page
    document.getElementById('prevPatientPage').disabled = currentPagePatient === 1;
    document.getElementById('nextPatientPage').disabled = currentPagePatient === totalPages;
}


// Update active class on pagination buttons
function updateActivePatientPageButton(activePage) {
    const pageButtons = document.querySelectorAll('.patientpage-btn');
    pageButtons.forEach((button, index) => {
        button.classList.toggle('active', index + 1 === activePage);
    });
}
document.getElementById('prevPatientPage').addEventListener('click', () => {
    if (currentPagePatient > 1) {
        currentPagePatient--;
        displayPatients(filteredPatients);
        updateActivePatientPageButton(currentPagePatient);
    }
});

document.getElementById('nextPatientPage').addEventListener('click', () => {
    const totalPages = Math.ceil(filteredPatients.length / rowsPerPagePatient);
    if (currentPagePatient < totalPages) {
        currentPagePatient++;
        displayPatients(filteredPatients);
        updateActivePatientPageButton(currentPagePatient);
    }
});



fetchAllPatients();
