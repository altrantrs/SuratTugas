<!-- script.js:29 Generating calendar for NIP: 198712345678901234 using container ID: days-container-198712345678901234
script.js:29 Generating calendar for NIP: 198812345678901234 using container ID: days-container-198812345678901234
script.js:29 Generating calendar for NIP: 198912345678901234 using container ID: days-container-198912345678901234
2script.js:69  Error fetching activities: SyntaxError: Unexpected token '<', "<br />
<b>"... is not valid JSON
(anonymous) @ script.js:69
script.js:73 Response from server: Responsebody: (...)bodyUsed: trueheaders: Headers {}ok: trueredirected: falsestatus: 200statusText: "OK"type: "basic"url: "http://localhost/Surat/get_activities.php?month=1&year=2024&nip=198712345678901234"[[Prototype]]: Response
script.js:79  Error fetching activities: SyntaxError: Unexpected token '<', "<br />
<b>"... is not valid JSON
(anonymous) @ script.js:79
script.js:69  Error fetching activities: SyntaxError: Unexpected token '<', "<br />
<b>"... is not valid JSON
(anonymous) @ script.js:69
script.js:73 Response from server: Responsebody: (...)bodyUsed: trueheaders: Headers {}ok: trueredirected: falsestatus: 200statusText: "OK"type: "basic"url: "http://localhost/Surat/get_activities.php?month=1&year=2024&nip=198912345678901234"[[Prototype]]: Response
script.js:79  Error fetching activities: SyntaxError: Unexpected token '<', "<br />
<b>"... is not valid JSON
(anonymous) @ script.js:79
script.js:73 Response from server: Responsebody: (...)bodyUsed: trueheaders: Headers {}ok: trueredirected: falsestatus: 200statusText: "OK"type: "basic"url: "http://localhost/Surat/get_activities.php?month=1&year=2024&nip=198812345678901234"[[Prototype]]: Response
script.js:79  Error fetching activities: SyntaxError: Unexpected token '<', "<br />
<b>"... is not valid JSON
(anonymous) @ script.js:79 -->
<?php
session_start();
include_once("db_connection.php");

$month = $_GET['month'];
$year = $_GET['year'];
$pelaksana = $_GET['pelaksana'];
$nip = isset($_GET['nip']) ? $_GET['nip'] : $_SESSION["nip"];

if ($_SESSION['level'] == "Administrator") {
    if ($nip == "all") {
        $query = "SELECT activity_dates.date 
                  FROM activity_dates
                  WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year";
    } else {
        $query = "SELECT activity_dates.date 
                  FROM activity_dates
                  WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year AND activity_dates.pelaksana='$pelaksana'";
    }
} else {
    $query = "SELECT activity_dates.date 
              FROM activity_dates 
              WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year AND pelaksana='$pelaksana'";
}

$result = mysqli_query($conn, $query);
$activities = [];

while ($row = mysqli_fetch_assoc($result)) {
    $activities[] = [
        'date' => $row['date']
    ];
}

header('Content-Type: application/json');
echo json_encode($activities);
?>
