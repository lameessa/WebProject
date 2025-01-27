


			// Redirect when "Login/Signup" button is clicked
			document.getElementById("login-signup-button").addEventListener("click", () => {
				window.location.href = "LoginPage.html";
			});

			// Redirect when "Book Appointment" button is clicked
			document.getElementById("appointment-button").addEventListener("click", () => {
				window.location.href = "Appointment.html"; 
			});

			// Redirect when "Login" button is clicked in the split-section
			const loginButton = document.querySelector(".login-button");
			loginButton.addEventListener("click", () => {
				window.location.href = "LoginPage.html"; 
			});