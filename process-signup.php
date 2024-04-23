<?php
if (empty($_POST["name"])){
        die("Name must is requried");
}

if (!filter_var($_POST["email"],FILTER_VALIDATE_EMAIL)){
        die("Vaild email is requried");
}

if (strlen($_POST["password"]) < 8) {
        die("password must be at least 8 characters");
    }

if (!preg_match("/[a-z]/i", $_POST["password"])){
    die("password must contain at least on letter");
}

if (!preg_match("/[0-9]/i", $_POST["password"])){
    die("password must contain at least on naumber");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("password must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, password)
        VALUES (?, ?, ?)";

$stmt = $mysqli->stmt_init();

if(! $stmt->prepare($sql)){
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                    $_POST["name"],
                    $_POST["email"],
                    $password_hash);

if ($stmt->execute()) {

    header("Location: signup-success.html");
    exit;
                    
} else {
                    
if ($mysqli->errno === 1062) {
    die("email already taken");

    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}