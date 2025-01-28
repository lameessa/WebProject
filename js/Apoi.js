    // قائمة الأطباء بناءً على التخصص
    const doctorsBySpecialty = {
        cardiology: ["Dr. John Smith", "Dr. Mary Jones"],
        dermatology: ["Dr. Susan Brown", "Dr. David Lee"],
        neurology: ["Dr. Emily Clark", "Dr. Robert White"],
        orthopedics: ["Dr. Chris Black", "Dr. Laura Green"]
    };

    // المراجع لعناصر HTML
    const specialtySelect = document.getElementById("specialty");
    const doctorSelect = document.getElementById("doctor");
    const updateDoctorsButton = document.getElementById("update-doctors");

    // تحديث قائمة الأطباء عند الضغط على زر التحديث
    updateDoctorsButton.addEventListener("click", () => {
        const selectedSpecialty = specialtySelect.value;

        // تفريغ قائمة الأطباء القديمة
        doctorSelect.innerHTML = '<option value="">   Doctors </option>';

        // إذا كان التخصص مختارًا، أضف الأطباء المناسبين
        if (selectedSpecialty && doctorsBySpecialty[selectedSpecialty]) {
            doctorsBySpecialty[selectedSpecialty].forEach(doctor => {
                const option = document.createElement("option");
                option.value = doctor.toLowerCase().replace(/\s+/g, "-");
                option.textContent = doctor;
                doctorSelect.appendChild(option);
            });
        }
    });
	
	function redirectToBooking() {
window.location.href = "Appointment.html";}