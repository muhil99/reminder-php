<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-3 " style="background-color: #fafafa;">
        <div class="close" style="display: flex; justify-content: flex-end;">
        <a href="./at.php" class="btn btn-secondary mt-3">Go Back</a>
        </div>
    
        
        <form action="" method="post">
        <h3 class="mb-3" style="display: flex; justify-content: center;">View Attendance</h3>
            <div class="form-group">
                <label for="email">Select Email:</label>
                <select name="email" id="email" class="form-control" required>
                    <option value="" disabled selected>Please select email id</option>
                    <!-- Populate the dropdown menu with emails from the database -->
                    <?php
                    session_start();
                    include 'config.php';
                    $emailQuery = "SELECT DISTINCT email FROM users";
                    $emailResult = mysqli_query($conn, $emailQuery);
                    while ($row = mysqli_fetch_assoc($emailResult)) {
                        echo "<option value='{$row['email']}'>{$row['email']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="viewAttendance" class="btn btn-primary">View Attendance</button>
        </form>

        <!-- Display attendance records -->
        <?php
        if(isset($_POST['viewAttendance'])) {
            $selectedEmail = $_POST['email'];
            $attendanceQuery = "SELECT * FROM attendance_tracker WHERE user_id IN (SELECT id FROM users WHERE email='$selectedEmail')";
            $attendanceResult = mysqli_query($conn, $attendanceQuery);

            if(mysqli_num_rows($attendanceResult) > 0) {
                echo "<h3 class='mt-4'>Attendance Records for $selectedEmail:</h3>";
                echo "<table class='table mt-2'>";
                echo "<thead><tr><th>Start Time</th><th>Stop Time</th><th>Total Time</th><th>Activity</th><th>Location</th><th>Notes</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($attendanceResult)) {
                    echo "<tr>";
                    echo "<td>{$row['start_time']}</td>";
                    echo "<td>{$row['stop_time']}</td>";
                    echo "<td>{$row['location']}</td>";
                    echo "<td>{$row['notes']}</td>";
                    echo "<td>{$row['total_time']}</td>";
                    echo "<td>{$row['activity']}</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p class='mt-4'>No attendance records found for $selectedEmail.</p>";
            }
        }
        ?>

       
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
