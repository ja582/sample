<?php
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require("config.php");

if(isset($_SESSION['user']['name'])){
    header("Location: dashboard.php");
}

if(isset($_POST['submitButton'])){
    try{
        $username = $_POST['username'];
        $password = $_POST['password'];
        $stmt = $db->prepare('SELECT id, username, password FROM Users WHERE username = :username LIMIT 1');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if($results){
            $userpass = $results['password'];
            if(password_verify($password, $userpass)){ //comparing plaintext and hash
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                if($results && count($results) > 0){
                    $userSes = array("name"=> $results['username'], "id"=> $results['id']);
                    $_SESSION['user'] = $userSes;
                    header("Location: dashboard.php");
                }
                return true;
                echo "Logged in (Console)";
            }
            else{
                return false;
                echo "invalid password";
            }
        }
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
</head>
<body class="text-center">
<form class="form-signin" method="POST" action="#">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <input name="username" type="text" class="form-control" placeholder="Username" required autofocus/>
    <input name="password" type="password" class="form-control" placeholder="Password" required/>
    <input type="submit" value="Submit" name="submitButton" id="submitButton"/>
    <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
</form>
</body>
</html>