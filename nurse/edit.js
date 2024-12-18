
// Assume this script runs when the form is submitted
document.querySelector('#editModalNurse').addEventListener('submit', function(event) {
    event.preventDefault();

    // Collect form data
    const formData = new FormData(event.target);

    // Send AJAX request
    fetch('./editModalNurse.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Edit successful!');
            window.location.href = './nursepage.php'; // Redirect to nurse page on success
        } else {
            alert('Edit failed: ' + (data.error || 'An unknown error occurred.'));
        }
    })
    .catch(error => {
        alert('An error occurred: ' + error.message);
    });
});

