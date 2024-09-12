document.addEventListener("DOMContentLoaded", () => {
  updateCalendar(); 
});

function updateCalendar() {
  const selectedEmployee = document.getElementById("employee-select").value;
  const selectedMonth = parseInt(document.getElementById("month-select").value, 10); 
  const isAdmin = document.getElementById("employee-select").querySelector("option[value='all']") !== null;

  if (isAdmin) {
    filterEmployees(); 
  } else {
    generateCalendar(selectedEmployee, selectedMonth);
  }
}

function filterEmployees() {
  const selectedEmployeeName = document.getElementById("employee-select").value;
  const rows = document.querySelectorAll(".employee-row");
  const selectedMonth = parseInt(document.getElementById("month-select").value, 10); 
  
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
      row.style.display = ""; 
      generateCalendar(nama, selectedMonth); // Generate calendar for each visible employee
    } else {
      row.style.display = "none"; 
    }
  });
}

function generateCalendar(employeeName, selectedMonth = 0) {
  const year = new Date().getFullYear();
  const daysInMonth = getDaysInMonth(selectedMonth, year); // Use zero-based index

  const employeeId = employeeName.replace(/\s+/g, "_");
  const daysContainer = document.getElementById(`days-container-${employeeId}`);

  if (!daysContainer) {
    console.error("Container for calendar not found for:", employeeName);
    return;
  }

  daysContainer.innerHTML = ""; // Clear previous calendar content

  // Fetch activity data for selected employee
  const month = (selectedMonth + 1).toString().padStart(2, "0"); // Adjust month for display

  console.log('Selected Month:', selectedMonth);
  console.log('Bulan:', month);
  console.log('Tahun:', year);

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

        const currentDate = `${year}-${month}-${day.toString().padStart(2, '0')}`;
        console.log(`currentDate: ${currentDate}`);

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
          openForm(day, selectedMonth + 1, year); // Display month as 1-based
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

function openForm(day, selectedMonth, year) {
  const selectedDate = `${year}-${selectedMonth.toString().padStart(2, "0")}-${day
    .toString()
    .padStart(2, "0")}`;
  localStorage.setItem("selectedDate", selectedDate);
  console.log("Selected Date in openForm:", selectedDate);
  window.location.href = "perjalanan_tambah.php";
}
