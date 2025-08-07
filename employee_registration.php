<?php
// Database connection settings
$servername = "localhost";
$username = "root";       // update as needed
$password = "";           // update as needed
$dbname = "ems";

$message = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $position = trim(htmlspecialchars($_POST['position'] ?? ''));
    $department = trim(htmlspecialchars($_POST['department'] ?? ''));

    if ($name && $email && $position && $department) {
        $sql = "INSERT INTO employees (name, email, position, department) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $message = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("ssss", $name, $email, $position, $department);
            if ($stmt->execute()) {
                $message = "Employee registered successfully!";
            } else {
                $message = "Execute failed: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $message = "Please fill all the fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Registration</title>
    <style>
        /* Same styles as before */
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            padding: 40px;
        }
        form {
            background: #fff;
            max-width: 450px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        input[type=text], input[type=email] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 700;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Employee Registration</h2>

<?php if ($message): ?>
    <p class="message <?php echo strpos($message, 'failed') !== false ? 'error' : ''; ?>">
        <?php echo $message; ?>
    </p>
<?php endif; ?>

<form method="POST" action="">
    <label for="name">Full Name</label>
    <input type="text" name="name" id="name" placeholder="Enter full name" required>

    <label for="email">Email Address</label>
    <input type="email" name="email" id="email" placeholder="Enter email address" required>

    <label for="position">Position</label>
    <input type="text" name="position" id="position" placeholder="Enter position" required>

    <label for="department">Department</label>
    <input type="text" name="department" id="department" placeholder="Enter department" required>

    <button type="submit">Register Employee</button>
</form>

</body>
</html>
