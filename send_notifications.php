<?php

function establishDatabaseConnection($dbHost, $dbUser, $dbPass, $dbName) {
    $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    if (!$conn) {
        die("Connection Error: " . mysqli_connect_error());
    }
    return $conn;
}

function check_email($email): int
{
    // TODO: Implement email validation
    sleep(rand(1, 6));

    return rand(0, 1);
}

function send_email($from, $to, $text): bool
{
    // TODO: Implement email sending (e.g., Symfony Mailer)
    sleep(rand(1, 10));

    return true;
}

function calculateDaysDifference($expirationTimestamp, $currentTimestamp): bool|int
{
    $expirationDate = date('Y-m-d', $expirationTimestamp);
    $currentDate = date('Y-m-d', $currentTimestamp);

    $expirationTime = new DateTime($expirationDate);
    $currentTime = new DateTime($currentDate);

    return $expirationTime->diff($currentTime)->days;
}

function processUserSubscription($row, $currentTimestamp, $conn): void
{
    $username = $row->username;
    $email = $row->email;
    $expirationTimestamp = $row->validts;

    $text = "$username, your subscription is expiring soon";

    $daysDifference = calculateDaysDifference($expirationTimestamp, $currentTimestamp);

    if ($daysDifference == 3 || $daysDifference == 1) {
        $lastNotificationStage = ($daysDifference == 3) ? 3 : 1;

        // Check email for validity
        $isValidEmail = check_email($email);

        if ($isValidEmail) {
            send_email("noreply@example.com", $email, $text);
            echo "Уведомление отправлено пользователю: $username, email: $email\n";

            $updateQuery = "UPDATE users SET last_notification_stage = ? WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, 'si', $lastNotificationStage, $row->id);
            mysqli_stmt_execute($updateStmt);
        } else {
            echo "Невалидный email, уведомление не отправлено пользователю: $username, email: $email\n";
        }
    }
}

$dbHost = 'your_host';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_name';

$conn = establishDatabaseConnection($dbHost, $dbUser, $dbPass, $dbName);

// Get current date in Unix timestamp
$currentTimestamp = time();

// Get date until 1 and 3 days before subscription expires
$oneDayBefore = $currentTimestamp + 24 * 3600;
$threeDaysBefore = $currentTimestamp + 3 * 24 * 3600;

// Receive all users which have 1-3 days until subscription expiration
$query = "SELECT * FROM users WHERE DATE(FROM_UNIXTIME(validts)) IN (DATE(FROM_UNIXTIME(?)), DATE(FROM_UNIXTIME(?))) AND confirmed = 1 AND checked = 1 AND valid = 1 AND (last_notification_stage = 0 OR last_notification_stage = 3)";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ii', $oneDayBefore, $threeDaysBefore);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            while ($row = mysqli_fetch_object($result)) {
                processUserSubscription($row, $currentTimestamp, $conn);
            }
        } else {
            echo 'Could not fetch result: ' . mysqli_error($conn);
        }
    } else {
        echo 'Statement execution failed: ' . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    echo 'Prepare statement failed: ' . mysqli_error($conn);
}

mysqli_close($conn);