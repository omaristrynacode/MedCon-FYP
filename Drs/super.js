function displayBubbles() {
    $.ajax({
        type: 'GET',
        url: 'getbubbles.php',
        dataType: 'json',
        success: function(bubbles) {
            console.log("Specialties fetched:", bubbles); // Debugging log
            if (Array.isArray(bubbles)) {
                renderSpecialtyBubbles(bubbles);
            } else {
                console.error("Unexpected data format:", bubbles);
            }
        },
        error: function(xhr, status, error) {
            console.error("An error occurred: " + error);
            
        }
    });
}

// Render specialty bubbles
let selectedSpecialties = []; // Array to keep track of selected specialties

function renderSpecialtyBubbles(bubbles) {
    const bubblesContainer = document.getElementById('specialtyBubbles');
    bubblesContainer.innerHTML = ''; 

    bubbles.forEach(specialty => {
        const bubble = document.createElement('div');
        bubble.classList.add('bubble', 'button');
        bubble.textContent = specialty;

        // Maintain selected state
        if (selectedSpecialties.includes(specialty)) {
            bubble.classList.add('selected');
        }

        // Attach event listener
        bubble.addEventListener('click', () =>
            toggleSpecialtySelection(specialty, bubble)
        );

        bubblesContainer.appendChild(bubble);
    });

    // Clear button logic
    const clearButton = document.createElement('div');
    clearButton.classList.add('bubble', 'button');
    clearButton.innerHTML = `<i class='bx bx-x' id='selecx'></i>`;
    clearButton.addEventListener('click', clearSelections);
    bubblesContainer.appendChild(clearButton);
}
function updateDoctorsDisplay() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const doctorsToShow = filteredDoctors.slice(start, end); 

    displayDoctors(doctorsToShow); 
    generatePagination(); 
    displayBubbles();  // Ensure bubbles are rendered after page update
}
function clearSelections() {
    selectedSpecialties = []; // Reset the selected specialties array

    // Remove 'selected' class from all bubbles
    const bubbles = document.querySelectorAll('.bubble');
    bubbles.forEach(bubble => {
        bubble.classList.remove('selected'); // Remove visual feedback
    });

    // Call the function to display all doctors again
fetchAllDoctors('active')
}
function toggleSpecialtySelection(specialty, bubble) {
    const index = selectedSpecialties.indexOf(specialty);
    if (index > -1) {
        // Remove specialty if already selected
        selectedSpecialties.splice(index, 1);
        bubble.classList.remove('selected');
    } else {
        // Add specialty if not already selected
        selectedSpecialties.push(specialty);
        bubble.classList.add('selected');
    }

    // Apply the filter and reset to the first page
    filterDoctorsBySpecialties();
}

function filterDoctorsBySpecialties() {
    if (selectedSpecialties.length > 0) {
        filteredDoctors = allDoctors.filter(doctor =>
            selectedSpecialties.includes(doctor.specialty)
        );
    } else {
        filteredDoctors = [...allDoctors]; // Show all if no specialties are selected
    }

    // Reset page to 1 and update the display
    currentPage = 1; 
    updateDoctorsDisplay();
}



// Dark mode toggle

// Check local storage and apply theme on load


let allDoctors = []; // Global array to hold all doctors
let filteredDoctors = []; // Global array to hold filtered results
let currentPage = 1;
const rowsPerPage = 7; // Number of doctors per page

// Fetch all doctors data from the server via AJAX
function fetchAllDoctors(status = null) {
    const xhr = new XMLHttpRequest();
    
    // Show the loading spinner before sending the request
    showLoadingSpinner(); // Call your function to display loading spinner
    $(".pagination").css("display","none")
    const url = status ? `fetch_drs.php?status=${status}` : 'fetch_drs.php';
    xhr.open('GET', url, true); // Fetch all doctors

    xhr.onload = function () {
        // Hide the loading spinner when the request completes
        hideLoadingSpinner(); // Call your function to hide loading spinner
        $(".drtable .pagination").css("display","block")

        if (xhr.status === 200) {
            allDoctors = JSON.parse(xhr.responseText); // Store all fetched doctors
            filteredDoctors = allDoctors; // Initialize filteredDoctors
            displayDoctors(filteredDoctors); // Display the complete list initially
            generatePagination(); // Initialize pagination
        } else {
            console.error('Error fetching doctors:', xhr.statusText);
        }
    };

    xhr.onerror = function () {
        // Hide the loading spinner if there's an error
        hideLoadingSpinner();
        console.error('Request error:', xhr.statusText);
    };

    xhr.send();
}
function fetchDoctors(status, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `fetch_drs.php?status=${status}`, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            allDoctors = JSON.parse(xhr.responseText);
            filteredDoctors = allDoctors;
            displayDoctors(filteredDoctors); // Display doctors
            generatePagination(); // Initialize pagination
            if (callback) callback(); // Call the callback function if provided
        } else {
            console.error('Error fetching doctors:', xhr.statusText);
        }
    };
    xhr.send();
}



function showLoadingSpinner() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    loadingSpinner.style.display = 'block'; // Display the spinner
}

// Function to hide loading spinner
function hideLoadingSpinner() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    loadingSpinner.style.display = 'none'; // Hide the spinner
}


// Display doctors for the current page
function displayDoctors(doctors) {
    const doctorsBody = document.getElementById('doctors-list');
    doctorsBody.innerHTML = ''; // Clear the existing content

    // Calculate start and end index based on current page
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedDoctors = doctors.slice(start, end); // Get the current page's doctors

    paginatedDoctors.forEach(doctor => {
        const row = document.createElement('tr');

        // Convert rating to stars
        const stars = generateStars(doctor.rating);

        row.innerHTML = `
            <td>${doctor.fname}</td>
            <td>${doctor.lname}</td>
            <td>${doctor.specialty}</td>
            <td>${doctor.DoS}</td>
            <td>${stars}</td>
        `;
        doctorsBody.appendChild(row);
    });
}

// Function to generate stars based on rating
function generateStars(rating) {
    const fullStar = '★';
    const emptyStar = '☆';
    const maxStars = 5;

    const fullStarsCount = Math.floor(rating); // Whole number of full stars
    const emptyStarsCount = maxStars - fullStarsCount; // Remaining stars

    return fullStar.repeat(fullStarsCount) + emptyStar.repeat(emptyStarsCount);
}







// Function to filter doctors based on search input
function filterDoctors() {
    const searchTerm = document.getElementById('searchBar').value.toLowerCase();

    // If the search term is empty, display all doctors
    if (searchTerm === '') {
        filteredDoctors = allDoctors; // Reset filtered doctors
        currentPage = 1; // Reset to the first page
        displayDoctors(filteredDoctors); // Show all doctors
        generatePagination(); // Regenerate pagination
        return;
    }

    // Filter doctors based on search term
    filteredDoctors = allDoctors.filter(doctor => {
        return (
            doctor.fname.toLowerCase().includes(searchTerm) ||
            doctor.lname.toLowerCase().includes(searchTerm) ||
            doctor.phone.toLowerCase().includes(searchTerm) ||
            doctor.email.toLowerCase().includes(searchTerm) ||
            doctor.specialty.toLowerCase().includes(searchTerm)
        );
    });

    currentPage = 1; // Reset to the first page after filtering
    displayDoctors(filteredDoctors); // Display the filtered list
    generatePagination(); // Regenerate pagination based on filtered results
}

// Generate pagination buttons dynamically
function generatePagination() {
    const pageNumbersContainer = document.getElementById('pageNumbers');
    pageNumbersContainer.innerHTML = ''; // Clear previous pagination

    const totalPages = Math.ceil(filteredDoctors.length / rowsPerPage); // Total number of pages

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('a');
        button.textContent = i;
        button.classList.add('page-btn');

        // Add an event listener to change the current page
        button.addEventListener('click', () => {
            currentPage = i;
            displayDoctors(filteredDoctors); // Fetch data for the clicked page
            updateActivePageButton(i); // Update the active class
        });

        pageNumbersContainer.appendChild(button);
    }

    // Enable or disable Prev/Next buttons based on the current page
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
}

// Function to update the active class on the pagination buttons
function updateActivePageButton(activePage) {
    const pageButtons = document.querySelectorAll('.page-btn');
    pageButtons.forEach((button, index) => {
        button.classList.remove('active');
        if (index + 1 === activePage) {
            button.classList.add('active');
        }
    });
}

document.getElementById('searchBar').addEventListener('keyup', filterDoctors);

// // Handle Next and Previous button clicks
document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        displayDoctors(filteredDoctors); // Display current page of filtered doctors
        updateActivePageButton(currentPage); // Update active page
    }
});

document.getElementById('nextPage').addEventListener('click', () => {
    const totalPages = Math.ceil(filteredDoctors.length / rowsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayDoctors(filteredDoctors); // Display current page of filtered doctors
        updateActivePageButton(currentPage); // Update active page
    }
});

// Initialize the page
fetchAllDoctors('active');
displayBubbles();