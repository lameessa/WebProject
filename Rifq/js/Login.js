document.querySelector(".login-form form").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevent the default form submission

    let selectedRole = document.getElementById("role").value;

    if (selectedRole === "patient") {
        window.location.href = "PatientHomePage.html";
    } else if (selectedRole === "doctor") {
        window.location.href = "Doctor.html";
    } 
});