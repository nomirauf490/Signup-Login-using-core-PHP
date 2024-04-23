<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // die('post req');

    $mysqli = require __DIR__ . "/database.php";

    // die($_POST["email"]);

    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                    $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if ($user) {
        // die($_POST["password"]);
        // die($user["password"]);
        if (password_verify($_POST["password"], $user["password"])) {
            
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: index.php");
            exit;
        }
    }

    $is_invalid = true;
} 

?>
<!DOCTYPE html>
<html>
<head>
        <tital>Login</tital>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Login</h1>

    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>

        <form method="post">
        <label for="email">email</label>
        <input type="email" name="email" id="email"
                value="<?php htmlspecialchars($_POST["email"] ?? "") ?>">
        <label for="password">password</label>
        <input type="password" name="password" id="password">

        <button>Login</button>
    </form>

</body>
</html>
