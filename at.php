<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>attendance</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./attendance/attend.css">
    
</head>
<body>
    <?php
    session_start();
    include 'config.php';

    // Check if user is logged in
    if (!isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: login.php");
        exit(); // Terminate script execution
    }

    // Logout logic
    if(isset($_POST['logout'])) {
        session_destroy(); // Destroy all sessions
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

    // Retrieve reminders associated with the logged-in user
    $reminderQuery = "SELECT * FROM reminders WHERE user_id='$userId'";
    $reminderResult = mysqli_query($conn, $reminderQuery);

    // Retrieve attendance tracker data associated with the logged-in user
    $attendanceQuery = "SELECT * FROM attendance_tracker WHERE user_id='$userId'";
    $attendanceResult = mysqli_query($conn, $attendanceQuery);

    $msg = '';

    // Handle form submission for attendance tracker
    if(isset($_POST['submitAttendance'])) {
        $startTime = mysqli_real_escape_string($conn, $_POST['start_time']);
        $stopTime = mysqli_real_escape_string($conn, $_POST['stop_time']);
        $totalTime = mysqli_real_escape_string($conn, $_POST['total_time']);
        $activity = mysqli_real_escape_string($conn, $_POST['activity']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $notes = mysqli_real_escape_string($conn, $_POST['notes']);
         // Retrieve total time from form

        // Insert attendance data into database
        $insertQuery = "INSERT INTO attendance_tracker (user_id, start_time, stop_time, notes, activity, location, total_time) VALUES ('$userId', '$startTime', '$stopTime','$activity', '$notes', '$totalTime', '$location')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            $msg = "<div class='alert alert-success'>Attendance data added successfully.</div>";
            header("Location: attendance_tracker_data.php");
exit(); // Terminate script execution
        } else {
            $msg = "<div class='alert alert-danger'>Failed to add attendance data.</div>";
        }
    }
    ?>

    <div class="container mt-2">
        
        <!-- Logout button -->
        <form action="" method="post" style="display: flex; justify-content: space-between;">
        <h5>Welcome, <?php echo $userEmail; ?></h5>
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
        </form>
        <!-- Attendance tracker form -->
        <div class="head-title p-2">
        <h4 class="text-center" style="font-size: 23px; padding: 1px;" >Attendance Form</h4>
        <h5 class="text-center"  style="font-size: 18px; padding: 1px;" id="current_date"></h5>
            <h6 class="text-center" style="font-size: 18px; padding: 1px;" id="current_time"></h6>
            </div>
        <table class="table">
                <tbody>
                    <tr>
                        <td>Start Time:</td>
                        <td><span id="start_display"></span></td>
                    </tr>
                    <tr>
                        <td>End Time:</td>
                        <td><span id="end_display">--</span></td>
                    </tr>
                    <tr>
                        <td>Total Time:</td>
                        <td><span id="total_time">--</span></td>
                    </tr>
                </tbody>
            </table>
       
        <?php echo $msg; ?>
        <form action="" method="post" onsubmit="return validateForm()">
            <div class="button-container">
                <button type="button" class="start-button" onclick="startTimer()">Start</button>
                <button type="button" class="stop-button" name="stop_time" onclick="stopTimer()">Stop</button>
            </div>

            <input type="hidden" id="start_time_input" name="start_time" />
            <input type="hidden" id="end_time_input" name="stop_time" />

            <div class="form-row" style="display: flex; align-items: self-end;">
            <div class="form-group col-md-6">
              <label for="activity">Activity</label>
                <select id="activity" name="activity" required class="form-control">
                    <option value="">Select Activity</option>
                    <option value="Cold Call">Cold Call</option>
                    <option value="Initial Discussion">Initial Discussion</option>
                    <option value="Follow Up">Follow Up</option>
                    <option value="Negotiations">Negotiations</option>
                    <option value="Service Call">Service Call</option>
                </select>
            </div>
            <div class="form-group col-md-6 ">
            <div class="row">
                <!-- <label for="location">Location:</label>
                <input type="text" class="form-control" name="location" id="current_location"> -->
                <div class="col-md-3 lg-6">
                <button type="button" onclick="getAddress()"  class="btn btn-secondary btn-block">Location</button>
            </div>
                <div class="col-md-8 lg-6">
                <input type="text" name="location" id="current_location" required  readonly class="form-control">
            </div>
            </div>
            </div>
            </div>
            <div class="form-row">
            <div class="form-group col-12">
                <label for="notes">Notes:</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>
            </div>           
            <!-- Add hidden input field to store total time -->
            <div class="col-lg-12 mt-2" style="display: flex; justify-content: space-between; ">
            <a href="./welcome.php" class="goback-btn">Go Back</a>
            <a href="./view_attendance.php" class="view-btn">View Attendance</a>
            <button type="submit" name="submitAttendance" class="submit-btn">Submit</button>
                </div>
            <input type="hidden" id="total_time_input" name="total_time" />
        </form>
    </div>

    <script>
    function validateForm() {
        var activity = document.getElementById("activity");
        var location = document.getElementById("current_location");
        var startDisplay = document.getElementById("start_display").innerText;
        var stopDisplay = document.getElementById("end_display").innerText;

        // Check if start time has been recorded
        if (!startDisplay || startDisplay === "--") {
            alert("Please start the timer before submitting the form.");
            return false;
        }

        if (!stopDisplay || stopDisplay === "--") {
            alert("Please stop the timer before submitting the form.");
            return false;
        }

        // Check if activity is selected
        if (activity.value === "") {
            alert("Please select an activity.");
            return false;
        }
        if (notes.value === "") {
            alert("please enter notes");
            return false;
        }

        // if (location.value === "") {
        //     alert("Please select a location.");
        //     return false;
        // }

        // You can add more validation logic here if needed

        return true; // Form will submit if all validations pass
    }
</script>

    <script>
    var startTime;

    // Function to get current date in the format "Month Day, Year"
    function getCurrentDate() {
        var currentDate = new Date();
        var options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return currentDate.toLocaleDateString('en-US', options);
    }

    // Function to get current time in the format "Hours:Minutes:Seconds AM/PM"
    function getCurrentTime() {
    var currentTime = new Date();
    var year = currentTime.getFullYear();
    var month = (currentTime.getMonth() + 1).toString().padStart(2, '0');
    var day = currentTime.getDate().toString().padStart(2, '0');
    var hours = currentTime.getHours().toString().padStart(2, '0');
    var minutes = currentTime.getMinutes().toString().padStart(2, '0');
    var seconds = currentTime.getSeconds().toString().padStart(2, '0');
    return year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
}


    function startTimer() {
        if (!startTime) {
            startTime = getCurrentTime();
            // Update UI to show start time
            document.getElementById("start_display").innerText = startTime;
            // Set start time value in hidden input field
            document.getElementById("start_time_input").value = startTime;
        }
    }

    function stopTimer() {
        if (startTime) {
            var endTime = getCurrentTime();
            var totalTime = calculateTotalTime(startTime, endTime);
            document.getElementById("end_display").innerText = endTime;
            document.getElementById("total_time").innerText = "Total Time: " + totalTime;

            // Set end time value in hidden input field
            document.getElementById("end_time_input").value = endTime;

            // Add total time to hidden input field
            document.getElementById("total_time_input").value = totalTime;

            // Reset startTime
            startTime = null;
        }
    }

    function calculateTotalTime(startTime, stopTime) {
    var start = new Date(startTime);
    var stop = new Date(stopTime);
    var totalTime = stop - start; // Difference in milliseconds
    var hours = Math.floor(totalTime / (1000 * 60 * 60));
    var minutes = Math.floor((totalTime % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((totalTime % (1000 * 60)) / 1000);

    return hours + " hours, " + minutes + " minutes, " + seconds + " seconds";
}


    function getAddress() {
        if(navigator.geolocation) {
            // api supported
            navigator.geolocation.getCurrentPosition((position) => {
                let lat =  position.coords.latitude;
                let lon = position.coords.longitude;
                fetch(`https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${lon}&apiKey=1582496b204e408a83a5c0add49f1942`)
                    .then(data => data.json()).then(data => {
                        let x = data.features[0].properties.formatted;
                        document.getElementById("current_location").value = x;
                    })
                    .catch(err => console.log(err));
            })
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

     // Call getAddress() when the page loads
    </script>

        <script>
                // Update current date and time
                document.getElementById("current_date").innerText = getCurrentDate();
                setInterval(function() {
                    document.getElementById("current_time").innerText = "Current Time: " + getCurrentTime();
                }, 1000); // Update every second
            </script>



    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
