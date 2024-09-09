document.addEventListener("DOMContentLoaded", () => {
  updateCalendar(); // Call the calendar update function on page load
});

function updateCalendar() {
  const selectedEmployee = document.getElementById("employee-select").value;
  const selectedMonth = document.getElementById("month-select").value;
  const isAdmin =
    document
      .getElementById("employee-select")
      .querySelector("option[value='all']") !== null;

  // For admins: filter employees and then update their calendar
  if (isAdmin) {
    filterEmployees(); // Filter employees and generate their calendars
  } else {
    // For regular users: directly generate the calendar
    generateCalendar(selectedEmployee, selectedMonth);
  }
}

function filterEmployees() {
  const selectedEmployeeName = document.getElementById("employee-select").value;
  const rows = document.querySelectorAll(".employee-row");
  const selectedMonth = document.getElementById("month-select").value;

  rows.forEach((row) => {
    const nama = row.getAttribute("data-nama");
    const employeeId = nama.replace(/\s+/g, "_");
    const daysContainer = document.getElementById(
      `days-container-${employeeId}`
    );

    // Clear the calendar before generating a new one
    if (daysContainer) {
      daysContainer.innerHTML = "";
    }

    if (selectedEmployeeName === "all" || nama === selectedEmployeeName) {
      row.style.display = ""; // Show row
      generateCalendar(nama, selectedMonth); // Generate calendar for each visible employee
    } else {
      row.style.display = "none"; // Hide row
    }
  });
}

function generateCalendar(employeeName, selectedMonth = 0) {
  const year = new Date().getFullYear();
  const daysInMonth = getDaysInMonth(parseInt(selectedMonth), year);

  const employeeId = employeeName.replace(/\s+/g, "_");
  const daysContainer = document.getElementById(`days-container-${employeeId}`);

  if (!daysContainer) {
      console.error("Container for calendar not found for:", employeeName);
      return;
  }
  daysContainer.innerHTML = ""; // Clear previous calendar content

  // Fetch activity data for selected employee
  const month = (parseInt(selectedMonth) + 1).toString().padStart(2, "0");
  fetch(`get_activities.php?month=${month}&year=${year}&pelaksana=${encodeURIComponent(employeeName)}`)
      .then(response => response.json())
      .then(data => {
          console.log("Activities data fetched:", data);

          // Generate calendar days
          for (let day = 1; day <= daysInMonth; day++) {
              const dayElement = document.createElement("div");
              dayElement.className = "day";
              dayElement.textContent = day.toString().padStart(2, "0");

              const dayOfWeek = new Date(year, selectedMonth, day).getDay();
              if (dayOfWeek === 0 || dayOfWeek === 6) {
                  dayElement.classList.add("weekend");
              }

            const currentDate = `${year}-${(selectedMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const activitiesForDate = data.filter(activity => activity.date === currentDate);

              if (activitiesForDate.length > 0) {
                  const activityIcon = document.createElement("i");
                  activityIcon.className = "fa-solid fa-check";

                  const link = document.createElement("a");
                  link.href = `tampil_kegiatan.php?date=${currentDate}&pelaksana=${encodeURIComponent(employeeName)}`;
                  link.appendChild(activityIcon);

                  dayElement.appendChild(link);
                  dayElement.classList.add("icon-day");
              }

              dayElement.addEventListener("click", () => {
                  openForm(day, selectedMonth + 1, year);
              });

              daysContainer.appendChild(dayElement);
          }
      })
      .catch(error => {
          console.error("Error fetching activities:", error);
      });
}

// Helper function to get the number of days in the selected month and year
function getDaysInMonth(month, year) {
  return new Date(year, month + 1, 0).getDate();
}

// Helper function to get the number of days in the selected month and year
function getDaysInMonth(month, year) {
  return new Date(year, month + 1, 0).getDate();
}
console.log(`Open Form dengan tanggal: ${year}-${(selectedMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
function openForm(day, month, year) {
  const selectedDate = `${year}-${month.toString().padStart(2, "0")}-${day
    .toString()
    .padStart(2, "0")}`;
  localStorage.setItem("selectedDate", selectedDate);
  console.log("Selected Date in openForm:", selectedDate);
  window.location.href = "perjalanan_tambah.php";
}
