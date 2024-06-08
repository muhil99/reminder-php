<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    exit(); // Terminate script execution
}

// Get logged-in user's email
$userEmail = $_SESSION['SESSION_EMAIL'];

// Retrieve user's ID
$userQuery = "SELECT id FROM users WHERE email='$userEmail'";
$userResult = mysqli_query($conn, $userQuery);
$userRow = mysqli_fetch_assoc($userResult);
$userId = $userRow['id'];

// Get today's date
$todayDate = date("Y-m-d");

// Retrieve attendance tracker data associated with the logged-in user for today's date
$attendanceQuery = "SELECT * FROM attendance_tracker WHERE user_id='$userId' AND DATE(start_time) = '$todayDate'";
$attendanceResult = mysqli_query($conn, $attendanceQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracker Data</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./attendance/attend.css">
</head>
<body>
<!-- <form action="" method="post" style="display: flex; justify-content: end;padding: 15px;">
            <button type="submit" name="logout"  class="btn btn-danger">Logout</button>
        </form> -->
        <div class="go-back-btn" style="display: flex; justify-content: end;padding: 15px;">
        <a href="at.php" class="btn btn-primary">Go Back</a>
    </div>
    <div class="container-fluid">
        <h4 class="p-2">Your Attendance Tracker Data for <?php echo date("F j, Y", strtotime($todayDate)); ?>:</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>Stop Time</th>
                        <th>Total Time</th>
                        <th>Activity</th>
                        <th>Location</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display attendance tracker data
                    while ($attendance = mysqli_fetch_assoc($attendanceResult)) {
                        echo "<tr>";
                        echo "<td>{$attendance['start_time']}</td>";
                        echo "<td>{$attendance['stop_time']}</td>";
                        echo "<td>{$attendance['location']}</td>";
                        echo "<td>{$attendance['notes']}</td>";
                        echo "<td>{$attendance['total_time']}</td>";
                        echo "<td>{$attendance['activity']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
