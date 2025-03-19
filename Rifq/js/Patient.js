document.addEventListener("DOMContentLoaded", function () {
    function sortAppointments() {
        const tableBody = document.querySelector(".appointments-table tbody");
        const rows = Array.from(tableBody.querySelectorAll("tr"));

        rows.sort((rowA, rowB) => {
            const dateA = getDateTime(rowA);
            const dateB = getDateTime(rowB);

            return dateA - dateB;
        });

        rows.forEach(row => tableBody.appendChild(row));
    }

    function getDateTime(row) {
        const dateText = row.cells[1].innerText.trim(); // Get the date (e.g., "20/5/2025")
        const timeText = row.cells[0].innerText.trim(); // Get the time (e.g., "3 PM")

        const [day, month, year] = dateText.split('/').map(Number);
        let [time, period] = timeText.split(' ');
        let [hours] = time.split(':').map(Number);

        if (period === "PM" && hours < 12) hours += 12;
        if (period === "AM" && hours === 12) hours = 0;

        return new Date(year, month - 1, day, hours);
    }

    sortAppointments();
});

function redirectToBooking() {
window.location.href = "Appointment.html";}