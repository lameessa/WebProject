  
document.addEventListener("DOMContentLoaded", function () {
    const specialtySelect = document.getElementById("specialty");
    const doctorSelect = document.getElementById("doctor");
    const updateDoctorsButton = document.getElementById("update-doctors");

    updateDoctorsButton.addEventListener("click", () => {
        const selectedSpecialty = specialtySelect.value;

        if (!selectedSpecialty) {
            alert("Please select a specialty first.");
            return;
        }

        // إرسال طلب إلى الخادم
        fetch("appointment.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `ajax=get_doctors&specialty_id=${selectedSpecialty}`

        })
        .then(response => response.json())
        .then(data => {
            doctorSelect.innerHTML = '<option value="">-- Select Doctor --</option>';
            data.forEach(doctor => {
                const option = document.createElement("option");
                option.value = doctor.id;
                option.textContent = doctor.name;
                doctorSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error fetching doctors:", error);
            alert("Error fetching doctors. Check console.");
        });
    });
});


	
	






   

