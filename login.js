// Check if the user is logged in and redirect if not
document.addEventListener("DOMContentLoaded", function() {
    // Assume the login status is stored in localStorage
    const isLoggedIn = localStorage.getItem('isLoggedIn');

    // If not logged in, redirect to login page
    if (!isLoggedIn) {
        window.location.href = 'login.html';
    }
});
