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

// Handle form submission
$msg = '';
if(isset($_POST['submitReminder'])) {
    $reminderMessage = mysqli_real_escape_string($conn, $_POST['reminder_message']);
    $reminderDate = mysqli_real_escape_string($conn, $_POST['reminder_date']);
    $recurrence = mysqli_real_escape_string($conn, $_POST['recurrence']);
    
    // Insert reminder into database
    $insertQuery = "INSERT INTO reminders (user_id, message, reminder_date, recurrence) VALUES ('$userId', '$reminderMessage', '$reminderDate', '$recurrence')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        $msg = "<div class='alert alert-success'>Reminder added successfully.</div>";
        // Reload the page after 1 second
        echo "<script>setTimeout(function() { window.location.href = '$_SERVER[PHP_SELF]'; }, 1000);</script>";
    } else {
        $msg = "<div class='alert alert-danger'>Failed to add reminder.</div>";
    }
}

// Determine the view (daily, weekly, monthly, yearly)
$view = isset($_POST['view']) ? $_POST['view'] : 'daily';

// Retrieve reminders based on the selected view
$today = date('Y-m-d');
$reminderQuery = "";

switch ($view) {
    case 'weekly':
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        $reminderQuery = "SELECT * FROM reminders WHERE user_id='$userId' AND (reminder_date BETWEEN '$startOfWeek' AND '$endOfWeek' AND (recurrence='none' OR recurrence='weekly') OR (recurrence='daily' AND reminder_date <= '$today'))";
        break;
    case 'monthly':
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        $reminderQuery = "SELECT * FROM reminders WHERE user_id='$userId' AND (reminder_date BETWEEN '$startOfMonth' AND '$endOfMonth' AND (recurrence='none' OR recurrence='monthly') OR (recurrence='daily' AND reminder_date <= '$today'))";
        break;
    case 'yearly':
        $startOfYear = date('Y-01-01');
        $endOfYear = date('Y-12-31');
        $reminderQuery = "SELECT * FROM reminders WHERE user_id='$userId' AND (reminder_date BETWEEN '$startOfYear' AND '$endOfYear' AND (recurrence='none' OR recurrence='yearly') OR (recurrence='daily' AND reminder_date <= '$today'))";
        break;
    case 'daily':
    default:
        $reminderQuery = "SELECT * FROM reminders WHERE user_id='$userId' AND (reminder_date='$today' OR recurrence='today' OR recurrence='daily' OR (recurrence='weekly' AND DAYOFWEEK(reminder_date) = DAYOFWEEK('$today')) OR (recurrence='monthly' AND DAY(reminder_date) = DAY('$today')) OR (recurrence='yearly' AND MONTH(reminder_date) = MONTH('$today') AND DAY(reminder_date) = DAY('$today')))";
        break;
}

// Handle form submission for deleting a reminder
if(isset($_POST['deleteReminder'])) {
    $reminderId = $_POST['reminder_id'];

    // Delete reminder from the database
    $deleteQuery = "DELETE FROM reminders WHERE id='$reminderId' AND user_id='$userId'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        // Optionally, you can provide a message indicating successful deletion
        $msg = "<div class='alert alert-success'>Reminder deleted successfully.</div>";
    } else {
        // Optionally, you can provide a message indicating deletion failure
        $msg = "<div class='alert alert-danger'>Failed to delete reminder.</div>";
    }
    
    // After deletion, redirect back to the same page to refresh the reminders
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}


$reminderResult = mysqli_query($conn, $reminderQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reminder</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./attendance/reminder.css">
</head>
<style>
    
</style>
<body >
    <section class="reminder-head">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                <h6>Welcome, <?php echo $userEmail; ?></h6>
                    <form action="" method="post" style="display: flex; justify-content: space-between;">
                    <a href="./welcome.php" class="btn btn-secondary mt-2  "  role="button" aria-disabled="true">Go Back</a>
                       <!-- <a href="./welcome.php" class="goback"></a> -->
                       <button type="submit" name="logout" class="btn btn-danger mt-2">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <section class="reminder-form-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                <h2 class="reminder-heading">Add Reminder</h2>
                <?php echo $msg; ?>
                <form action="" method="post" class="reminder-form">
                    <div class="form-group">
                        <label for="reminder_date">Reminder Date</label>
                        <input type="date" name="reminder_date" id="reminder_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="reminder_message">Reminder Message</label>
                        <input type="text" name="reminder_message" id="reminder_message" class="form-control" placeholder="Enter your reminder message" required>
                    </div>
                    <div class="form-group">
                        <label for="recurrence">Recurrence</label>
                        <select name="recurrence" id="recurrence" class="form-control">
                            <option value="none">None</option>
                            <option value="today">Today</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <button type="submit" name="submitReminder" class="btn btn-primary ">Add Reminder</button>
                </form>
                </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <h2 class="reminder-heading">View Reminders</h2>
            <form action="" method="post" class="reminder-view-form">
            <div class="row">
                <!-- <label for="location">Location:</label>
                <input type="text" class="form-control" name="location" id="current_location"> -->
                <div class="col-md-6 lg-6">
                <div class="form-group">
                        <!-- <label class="view-head" for="view">View Reminders</label> -->
                        <select name="view" id="view" class="form-control mt-2">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
            </div>
                <div class="col-md-6 lg-6">
                <button type="submit" name="viewReminders" class="btn btn-info mt-2" >View Reminders</button>
            </div>
            </div>
                   
                </form>
                

                <!-- <h2 class="reminder-table-head">Your Reminders</h2> -->
                <table class="table table-striped mt-2">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Message</th>
                        <th>Action</th> <!-- New column for the delete button -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display reminders in a table
                    while ($reminder = mysqli_fetch_assoc($reminderResult)) {
                        echo "<tr>";
                        echo "<td>{$reminder['reminder_date']}</td>";
                        echo "<td>{$reminder['message']}</td>";
                        echo "<td><form action='' method='post'><input type='hidden' name='reminder_id' value='{$reminder['id']}'><button type='submit' name='deleteReminder' class='btn btn-danger btn-sm'>Delete</button></form></td>"; // Delete button
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
       
                </div>
                </div>
                </section>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
