<?php
session_start(); // Start the session at the beginning of the script

// Database connection credentials
$servername = "127.0.0.1:3306";
$username = "samwatts12396"; // Replace with your actual database username
$password = "Iamoldgreg123!"; // Replace with your actual database password
$dbname = "SWDB1"; // Replace with your actual database name

// Set up DSN (Data Source Name) for PDO connection
$dsn = "mysql:host=$servername;dbname=$dbname";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Create a PDO instance (connect to the database)
    $pdo = new PDO($dsn, $username, $password, $options);

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collect and sanitize input
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        // Validate the email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Invalid email format";
            header('Location: contact_form.html');
            exit();
        }

        // Prepare the SQL statement with all fields included
        $sql = "INSERT INTO contacts (firstname, lastname, email, country, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters and execute
        $stmt->execute([$firstname, $lastname, $email, $country, $message]);

        // Set a success message and redirect
        $_SESSION['success_message'] = "Thank You! Your message has been sent.";
        header('Location: thank_you_page.php'); // Redirect to a thank you or confirmation page
        exit;
    }
} catch (PDOException $e) {
    // Handle SQL connection errors or execution errors
    $_SESSION['error_message'] = "Connection failed: " . $e->getMessage();
    header('Location: contact_form.html');
    exit();
}

?>
