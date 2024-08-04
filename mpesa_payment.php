<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "laundry";

$consumerKey = 'YihRQ9UNoEyqAa4CS055i62ewqFEKdN2P8hCmSknTLEoqdH6'; // Replace with your consumer key
$consumerSecret = 'Q6WKFcDx45YDbaLFivqOHDTc8NBj8GxeAIPd9LiCFDdPqwh9xDW5XNdLX2rP12Ga'; // Replace with your consumer secret
$shortcode = '174379'; // Replace with your shortcode
$passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // Replace with your passkey
$callbackUrl = 'https://mydomain.com/path'; // Replace with your callback URL

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getAccessToken($consumerKey, $consumerSecret) {
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $credentials = base64_encode($consumerKey . ':' . $consumerSecret);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $curl_response = curl_exec($curl);
    $result = json_decode($curl_response);
    
    return $result->access_token;
}

function initiateSTKPush($accessToken, $shortcode, $passkey, $amount, $phoneNumber, $callbackUrl) {
    $timestamp = date('YmdHis');
    $password = base64_encode($shortcode . $passkey . $timestamp);

    $curl_post_data = [
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $phoneNumber,
        'PartyB' => $shortcode,
        'PhoneNumber' => $phoneNumber,
        'CallBackURL' => $callbackUrl,
        'AccountReference' => 'LaundryOrder',
        'TransactionDesc' => 'Laundry payment'
    ];

    $data_string = json_encode($curl_post_data);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $accessToken));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    
    $curl_response = curl_exec($curl);
    return json_decode($curl_response);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['email'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit();
    }

    $userEmail = $_SESSION['email'];

    $orderQuery = "SELECT * FROM orders WHERE user_id = (SELECT id FROM users WHERE email = ?) ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $amount = $order['total_amount'];
        $phoneNumber = $order['mobile_number'];

        $accessToken = getAccessToken($consumerKey, $consumerSecret);
        $stkPushResponse = initiateSTKPush($accessToken, $shortcode, $passkey, $amount, $phoneNumber, $callbackUrl);

        echo json_encode($stkPushResponse);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
