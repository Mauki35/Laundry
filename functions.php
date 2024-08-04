<?php
function getCustomerOrderDetails($customerId, $conn) {
    $orderCountQuery = "SELECT COUNT(order_id) AS order_count FROM Orders WHERE customer_id = ?";
    $stmt = $conn->prepare($orderCountQuery);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderCount = $result->fetch_assoc()['order_count'];
    $stmt->close();

    $totalSpentQuery = "SELECT SUM(total_amount) AS total_spent FROM Orders WHERE customer_id = ?";
    $stmt = $conn->prepare($totalSpentQuery);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalSpent = $result->fetch_assoc()['total_spent'];
    $stmt->close();

    return [$orderCount, $totalSpent];
}

function applyDiscounts($orderAmount, $customerId, $conn) {
    list($orderCount, $totalSpent) = getCustomerOrderDetails($customerId, $conn);

    if ($orderCount > 10) {
        $orderAmount *= 0.90; // Apply 10% discount
    }
    if ($orderAmount >= 10000) {
        $orderAmount *= 0.97; // Apply 3% discount
    }

    return $orderAmount;
}
?>
