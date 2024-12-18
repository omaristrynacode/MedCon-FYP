document.querySelectorAll('.star-rating').forEach(ratingDiv => {
    const stars = ratingDiv.querySelectorAll('.fa-star');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.dataset.value;
            stars.forEach(s => s.classList.remove('checked'));
            for (let i = 0; i < value; i++) {
                stars[i].classList.add('checked');
            }
            ratingDiv.dataset.rating = value;
            console.log("Rating selected:", value);
        });
    });
});

// Submit Feedback Logic
document.querySelectorAll('.submit-feedback').forEach(button => {
    button.addEventListener('click', function() {
        const parent = this.closest('.list-group-item');
        const rating = parent.querySelector('.star-rating').dataset.rating;
        const feedback = parent.querySelector('textarea').value;

        console.log("Feedback Submitted:");
        console.log("Rating:", rating);
        console.log("Feedback:", feedback);

        alert("Thank you for your feedback!\nRating: " + rating + " stars\nMessage: " + feedback);
    });
});