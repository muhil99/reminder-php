<?php
include_once "dbConfig.php"; // Include your database configuration file

// Check if date and reminder text are set
if(isset($_POST['date']) && isset($_POST['reminderText'])) {
    // Get date and reminder text from POST data
    $date = $_POST['date'];
    $reminderText = $_POST['reminderText'];

    // Prepare and execute the SQL query to insert reminder into database
    $stmt = $db->prepare("INSERT INTO reminders (date, reminder_text) VALUES (?, ?)");
    $stmt->bind_param("ss", $date, $reminderText);
    $stmt->execute();

    // Check if the query was successful
    if($stmt->affected_rows > 0) {
        echo "Reminder saved successfully.";
    } else {
        echo "Failed to save reminder.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Date and reminder text are required.";
}
?>
