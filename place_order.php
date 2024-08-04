<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "laundry";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getCustomerOrderDetails($customerId, $conn) {
    $orderCountQuery = "SELECT COUNT(order_id) AS order_count FROM Orders WHERE user_id = ?";
    $stmt = $conn->prepare($orderCountQuery);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderCount = $result->fetch_assoc()['order_count'];
    $stmt->close();

    $totalSpentQuery = "SELECT SUM(total_amount) AS total_spent FROM Orders WHERE user_id = ?";
    $stmt = $conn->prepare($totalSpentQuery);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalSpent = $result->fetch_assoc()['total_spent'];
    $stmt->close();

    return [$orderCount, $totalSpent];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['email'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit();
    }

    $userEmail = $_SESSION['email'];

    $pickUpDate = $conn->real_escape_string($_POST['pickUpDate']);
    $pickUpTime = $conn->real_escape_string($_POST['pickUpTime']);
    $pickUpAddress = $conn->real_escape_string($_POST['pickUpAddress']);
    $mobileNumber = $conn->real_escape_string($_POST['mobileNumber']);
    $modeOfPayment = $conn->real_escape_string($_POST['modeOfPayment']);
    $initialAmount = floatval($_POST['totalAmount']);
    $createdAt = date('Y-m-d H:i:s');

    $services = $_POST['typeOfService'];
    $serviceDetails = '';

    foreach ($services as $index => $service) {
        $serviceDetails .= "Service " . ($index + 1) . ": " . $service . "\n";

        if ($service !== "exclusive") {
            $amountOfLaundry = $_POST['amountOfLaundry'][$index] ?? 0;
            $serviceDetails .= "  Amount of Laundry: " . $amountOfLaundry . " kgs\n";
        } else {
            $exclusiveItems = $_POST['exclusiveItemType'];
            foreach ($exclusiveItems as $itemIndex => $item) {
                $itemType = $item;
                $itemSize = $_POST['exclusiveItemSize'][$itemIndex];
                $numberOfItems = $_POST['numberOfItems'][$itemIndex];
                $serviceDetails .= "  Exclusive Item " . ($itemIndex + 1) . ": Type - " . $itemType . ", Size - " . $itemSize . ", Number of Items - " . $numberOfItems . "\n";
            }
        }
    }

    $serviceDetails = $conn->real_escape_string($serviceDetails);

    $userIdQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($userIdQuery);
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customerId = $row['id'];
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID not found.']);
        exit();
    }

    $sql = "INSERT INTO orders (user_id, pick_up_date, pick_up_time, pick_up_address, mobile_number, mode_of_payment, total_amount, services, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssdss", $customerId, $pickUpDate, $pickUpTime, $pickUpAddress, $mobileNumber, $modeOfPayment, $initialAmount, $serviceDetails, $createdAt);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to place order: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
