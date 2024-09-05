document.addEventListener("DOMContentLoaded", function() {
    generateCalendar(); // Call the function only once when the page loads
});

function generateCalendar() {
    const daysContainer = document.getElementById('days-container');
    const employeeSelect = document.getElementById('employee-select');
    const monthSelect = document.getElementById('month-select');
    
    daysContainer.innerHTML = ''; // Clear previous days
    
    const selectedMonth = parseInt(monthSelect.value);
    const selectedEmployee = employeeSelect.value;
    const year = new Date().getFullYear();
    const daysInMonth = getDaysInMonth(selectedMonth, year);

    fetch(`get_activities.php?month=${selectedMonth + 1}&year=${year}&nip=${selectedEmployee}`)
    .then(response => response.json())
    .then(data => {
        console.log("Data fetched:", data);
        if (data.length === 0) {
            console.error("No activities received");
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'day';
            dayElement.textContent = day.toString().padStart(2, '0');

            const dayOfWeek = new Date(year, selectedMonth, day).getDay();

            if (dayOfWeek === 0 || dayOfWeek === 6) {
                dayElement.classList.add('weekend');
            }

            const currentDate = `${year}-${(selectedMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const activitiesForDate = data.filter(activity => activity.date === currentDate);

            if (activitiesForDate.length > 0) {
                activitiesForDate.forEach(activity => {
                    const activityInfo = document.createElement('div');
                    activityInfo.className = 'activity-info';
                    activityInfo.textContent = activity.nama || "No name"; // Show employee's name

                    const link = document.createElement('a');
                    link.href = `tampil_kegiatan.php?date=${currentDate}`;
                    link.appendChild(activityInfo);

                    dayElement.appendChild(link); // Add the link to the day element

                    dayElement.classList.add('icon-day');
                });
            }

            dayElement.addEventListener('click', () => {
                openForm(day, selectedMonth + 1, year);
            });

            daysContainer.appendChild(dayElement);
        }
    })
    .catch(error => console.error("Error fetching activities:", error));
}

function getDaysInMonth(month, year) {
    return new Date(year, month + 1, 0).getDate(); // Get the number of days in the month
}

function openForm(day, month, year) {
    const selectedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
    localStorage.setItem('selectedDate', selectedDate);
    window.location.href = 'perjalanan_tambah.php';
}
