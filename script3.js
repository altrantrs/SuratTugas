// Update the generateCalendar function in script.js
function generateCalendar() {
    const daysContainer = document.getElementById('days-container');
    daysContainer.innerHTML = ''; // Clear previous days

    const monthSelect = document.getElementById('month-select');
    const selectedMonth = parseInt(monthSelect.value);
    const year = new Date().getFullYear();
    const daysInMonth = getDaysInMonth(selectedMonth, year);

    const nipSelect = document.getElementById('nip');
    const selectedNip = nipSelect.value; // Get selected NIP

    fetch('get_activities.php?nip=' + encodeURIComponent(selectedNip))
    .then(response => response.json())
    .then(datesWithActivities => {
        console.log("Dates with activities:", datesWithActivities);
        if (datesWithActivities.length === 0) {
            console.error("No dates received");
        }

        // Create a Set to handle unique dates
        const uniqueDates = new Set(datesWithActivities);

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'day';
            dayElement.textContent = day.toString().padStart(2, '0');

            const dayOfWeek = new Date(year, selectedMonth, day).getDay();

            if (dayOfWeek === 0 || dayOfWeek === 6) {
                dayElement.classList.add('weekend');
            }

            const currentDate = `${year}-${(selectedMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            console.log("Checking date:", currentDate);

            if (uniqueDates.has(currentDate)) {
                const checkIcon = document.createElement('i');
                checkIcon.className = 'fa-solid fa-check';
                
                // Create the link
                const link = document.createElement('a');
                link.href = `tampil_kegiatan.php?date=${currentDate}`;
                link.appendChild(checkIcon);

                dayElement.appendChild(link); // Add the link to the day element

                // Add the cream background class
                dayElement.classList.add('icon-day');
            }

            dayElement.addEventListener('click', () => {
                openForm(day, selectedMonth + 1, year);
            });

            daysContainer.appendChild(dayElement);
        }
    })
    .catch(error => console.error("Error fetching dates:", error));
}
