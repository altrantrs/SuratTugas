document.addEventListener("DOMContentLoaded", () => {
  filterEmployees(); // Call the filter function on page load
});

function filterEmployees() {
    const selectedEmployeeName = document.getElementById("employee-select").value;
    const rows = document.querySelectorAll(".employee-row");
  
    rows.forEach((row) => {
      const nama = row.getAttribute("data-nama");
      const containerId = `days-container-${nama.replace(/\s+/g, "_")}`;
  
      // Jika elemen kontainer tidak ada, buat secara dinamis
      if (!document.getElementById(containerId)) {
        const newContainer = document.createElement("div");
        newContainer.id = containerId;
        newContainer.className = "days-container";
        document.body.appendChild(newContainer); // Tambahkan ke DOM, sesuaikan posisi jika diperlukan
      }
  
      if (selectedEmployeeName === "all" || nama === selectedEmployeeName) {
        row.style.display = ""; // Tampilkan baris
        generateCalendar(nama); // Generate calendar untuk setiap pegawai yang visible
      } else {
        row.style.display = "none"; // Sembunyikan baris
      }
    });
  }

document.getElementById("month-select").addEventListener("change", function () {
  const selectedEmployee = document.getElementById("employee-select").value;
  console.log("Selected employee:", selectedEmployee);

  const selectedMonth = this.value; 
  console.log("Selected month:", selectedMonth);
  
  generateCalendar(selectedEmployee, selectedMonth); // Panggil generateCalendar dengan pegawai dan bulan terpilih
});

  
  document.getElementById("employee-select").addEventListener("change", function () {
    const selectedEmployee = this.value;
    console.log("Selected employee:", selectedEmployee);
  
    if (selectedEmployee === "all") {
      // Generate calendar untuk semua pegawai
      const rows = document.querySelectorAll(".employee-row");
      rows.forEach((row) => {
        const nama = row.getAttribute("data-nama");
        generateCalendar(nama); // Panggil generateCalendar untuk setiap pegawai
      });
    } else {
      // Reset kontainer sebelum generate kalender baru
      const daysContainer = document.getElementById(`days-container-${selectedEmployee.replace(/\s+/g, "_")}`);
      if (daysContainer) {
        daysContainer.innerHTML = ""; // Bersihkan kontainer
      }
  
      // Panggil generateCalendar untuk pegawai yang dipilih
      generateCalendar(selectedEmployee);
    }
  });
  
  

// Generate calendar based on selected employee and month
function generateCalendar(employeeName, selectedMonth = null) {
    const month =
      selectedMonth !== null
        ? selectedMonth
        : document.getElementById("month-select").value;
    const year = new Date().getFullYear();
    const daysInMonth = getDaysInMonth(parseInt(month), year);
  
    let daysContainer;
    if (employeeName) {
      daysContainer = document.getElementById(`days-container-${employeeName}`);
    } else {
      daysContainer = document.getElementById("days-container");
    }
  
    if (!daysContainer) {
      console.error("Container for calendar not found for:", employeeName);
      return;
    }
  
    // **Bersihkan kontainer sebelum menambahkan tanggal baru**
    daysContainer.innerHTML = ""; // Reset kontainer agar tidak duplikat
  
    fetch(
      `get_activities.php?month=${
        parseInt(month) + 1
      }&year=${year}&pelaksana=${employeeName}`
    )
      .then((response) => response.json())
      .then((data) => {
        console.log("Activities data fetched:", data);
  
        for (let day = 1; day <= daysInMonth; day++) {
          const dayElement = document.createElement("div");
          dayElement.className = "day";
          dayElement.textContent = day.toString().padStart(2, "0");
  
          const dayOfWeek = new Date(year, month, day).getDay();
          if (dayOfWeek === 0 || dayOfWeek === 6) {
            dayElement.classList.add("weekend");
          }
  
          const currentDate = `${year}-${(parseInt(month) + 1)
            .toString()
            .padStart(2, "0")}-${day.toString().padStart(2, "0")}`;
          const activitiesForDate = data.filter(
            (activity) => activity.date === currentDate
          );
  
          if (activitiesForDate.length > 0) {
            const activityIcon = document.createElement("i");
            activityIcon.className = "fa-solid fa-check";
  
            const link = document.createElement("a");
            link.href = `tampil_kegiatan.php?date=${currentDate}&pelaksana=${employeeName}`;
            link.appendChild(activityIcon);
  
            dayElement.appendChild(link);
            dayElement.classList.add("icon-day");
          }
  
          // Tambahkan elemen hari ke dalam daysContainer
          daysContainer.appendChild(dayElement);
        }
      })
      .catch((error) => {
        console.error("Error fetching activities:", error);
      });
  }

function updateCalendar() {
  const selectedEmployee = document.getElementById("employee-select").value;
  const selectedMonth = document.getElementById("month-select").value;

  // Panggil fungsi filterEmployees untuk memfilter pegawai jika admin
  if (document.getElementById("employee-select")) {
    filterEmployees();
  }
}

// Helper function to get the number of days in the selected month and year
function getDaysInMonth(month, year) {
  return new Date(year, month + 1, 0).getDate();
}

function openForm(day, month, year) {
  const selectedDate = `${year}-${month.toString().padStart(2, "0")}-${day
    .toString()
    .padStart(2, "0")}`;
  localStorage.setItem("selectedDate", selectedDate);
  window.location.href = "perjalanan_tambah.php";
}
