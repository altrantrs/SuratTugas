document.addEventListener("DOMContentLoaded", function() {
    generateCalendar(); // Panggil fungsi saat halaman selesai dimuat
});

function generateCalendar() {
    const daysContainer = document.getElementById('days-container');
    const employeeSelect = document.getElementById('employee-select');
    const monthSelect = document.getElementById('month-select');
    const selectedEmployee = employeeSelect.value;
    const selectedMonth = parseInt(monthSelect.value);
    const year = new Date().getFullYear();

    daysContainer.innerHTML = ''; // Bersihkan hari sebelumnya

    if (selectedEmployee === 'all') {
        // Jika "Semua Pegawai" dipilih, loop semua pegawai
        fetch(`get_all_employees.php`)
        .then(response => response.json())
        .then(employees => {
            // Loop untuk setiap pegawai dan buat tabel serta kalendernya masing-masing
            employees.forEach(employee => {
                createEmployeeCalendar(employee, selectedMonth, year);
            });
        })
        .catch(error => console.error("Error fetching employees:", error));
    } else {
        // Tampilkan kalender untuk satu pegawai yang dipilih
        createEmployeeCalendar({ nip: selectedEmployee }, selectedMonth, year);
    }
}

function createEmployeeCalendar(employee, selectedMonth, year) {
    const daysInMonth = getDaysInMonth(selectedMonth, year);
    const employeeCalendarContainer = document.createElement('div');
    employeeCalendarContainer.className = 'employee-calendar';

    // Judul kalender pegawai
    const employeeName = document.createElement('h3');
    employeeName.textContent = `Kegiatan: ${employee.nama || 'Pegawai'} (${employee.nip})`;
    employeeCalendarContainer.appendChild(employeeName);

    // Kontainer untuk hari-hari kalender
    const calendarDaysContainer = document.createElement('div');
    calendarDaysContainer.className = 'days';

    employeeCalendarContainer.appendChild(calendarDaysContainer);

    // Fetch kegiatan pegawai untuk bulan yang dipilih
    fetch(`get_activities.php?month=${selectedMonth + 1}&year=${year}&nip=${employee.nip}`)
    .then(response => response.json())
    .then(data => {
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

            // Jika ada kegiatan di tanggal tersebut, tampilkan icon
            if (activitiesForDate.length > 0) {
                const link = document.createElement('a');
                link.href = `tampil_kegiatan.php?date=${currentDate}&nip=${employee.nip}`;
                
                const icon = document.createElement('i');
                icon.className = 'fa-solid fa-check';
                icon.style.color = 'green';
                icon.style.fontSize = '16px';

                link.appendChild(icon);
                dayElement.appendChild(link);
                dayElement.classList.add('icon-day');
            }

            calendarDaysContainer.appendChild(dayElement);
        }
    })
    .catch(error => console.error("Error fetching activities:", error));

    daysContainer.appendChild(employeeCalendarContainer); // Tambahkan kalender ke kontainer utama
}

function getDaysInMonth(month, year) {
    return new Date(year, month + 1, 0).getDate();
}
