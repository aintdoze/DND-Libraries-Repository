<?php
session_start(); // Start the session
require_once 'config.php'; // Include your database connection configuration

// Retrieve the user's ID from the session
$userID = $_SESSION['userID'];

// Check if a delete action is requested
if (isset($_POST['delete']) && isset($_POST['notification_id'])) {
    $notificationID = $_POST['notification_id'];

    // Delete the selected notification
    $deleteSQL = "DELETE FROM notifications WHERE notification_id = ?";
    $stmt = mysqli_prepare($mysqli, $deleteSQL);
    mysqli_stmt_bind_param($stmt, "i", $notificationID);

    if (mysqli_stmt_execute($stmt)) {
        // Notification deleted successfully
    } else {
        // Handle the error
        echo "Error deleting notification: " . mysqli_error($mysqli);
    }
}

// Query the database for notifications
$notificationSQL = "SELECT * FROM notifications WHERE user_id = $userID ORDER BY `timestamp` DESC";
$notificationResult = mysqli_query($mysqli, $notificationSQL);

if (!$notificationResult) {
    die("Error: " . mysqli_error($mysqli));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <link rel="stylesheet" href="js\bootstrap.bundle.min.js">
</head>
<body>
<?php include 'navbar.php'; ?>
    <div id="header">
        <div id="title">

            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div>
        <h1>Notifications</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Title</th>
                    <th>Timestamp</th>
                    <th>Message</th>
                    <th>Delete</th> <!-- Added Delete column -->
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($notificationResult)) {
                    echo "<tr>";
                    echo "<td>{$row['action']}</td>";
                    echo "<td>{$row['document_title']}</td>";
                    echo "<td>{$row['timestamp']}</td>";
                    echo "<td>{$row['message']}</td>";
                    echo "<td>
                            <form method='post'>
                            <input type='hidden' name='notification_id' value='{$row['notification_id']}'>
                            <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
