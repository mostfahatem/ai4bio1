<?php
// Start the session
session_start();

// Unset all the server side variables
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Africa/Cairo');
$date = date('Y-m-d');

$_SESSION["date"] = $date;

// Import database connection
include("connection.php");

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'login') {
            // Login logic
            $email = $_POST['useremail'];
            $password = $_POST['userpassword'];
            
            $result = $database->query("SELECT * FROM webuser WHERE email='$email'");
            if ($result->num_rows == 1) {
                $utype = $result->fetch_assoc()['usertype'];
                if ($utype == 'p') {
                    $checker = $database->query("SELECT * FROM patient WHERE pemail='$email' AND ppassword='$password'");
                    if ($checker->num_rows == 1) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'p';
                        header('location: patient/index.php');
                    } else {
                        $error = 'Wrong credentials: Invalid email or password';
                    }
                } elseif ($utype == 'a') {
                    $checker = $database->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$password'");
                    if ($checker->num_rows == 1) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'a';
                        header('location: admin/index.php');
                    } else {
                        $error = 'Wrong credentials: Invalid email or password';
                    }
                } elseif ($utype == 'd') {
                    $checker = $database->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$password'");
                    if ($checker->num_rows == 1) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'd';
                        header('location: doctor/index.php');
                    } else {
                        $error = 'Wrong credentials: Invalid email or password';
                    }
                }
            } else {
                $error = 'We can\'t find any account for this email.';
            }
        } elseif ($_POST['action'] == 'signup') {
            // Signup logic
            $_SESSION["personal"] = array(
                'fname' => $_POST['fname'],
                'lname' => $_POST['lname'],
                'address' => $_POST['address'],
                'nic' => $_POST['nic'],
                'dob' => $_POST['dob'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            );

            // Here you would typically insert the new user into the database
            // For this example, we'll just set a success message
            
            print_r($_SESSION["personal"]);
            header("location: create-account.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <title>Opticare</title>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="#" method="POST">
                <input type="hidden" name="action" value="signup">
                <h1 id="vit">Opticare</h1>
                <input type="text" placeholder="First Name" name="fname" required>
                <input type="text" placeholder="Last Name" name="lname" required>
                <input type="text" placeholder="Address" name="address" required>
                <input type="text" placeholder="NID Number" name="nic" required>
                <input type="date" placeholder="Date of Birth" name="dob" required>
                <button type="submit">Sign Up</button>
                <br><br>
                <div class="social-container">
                    <a href="https://www.facebook.com" class="social" id="fb"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com" class="social" id="ins"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.gmail.com" class="social" id="gm"><i class="fas fa-envelope"></i></a>
                    <a href="https://www.twitter.com" class="social" id="tw"><i class="fab fa-twitter"></i></a>
                </div>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="#" method="POST">
                <input type="hidden" name="action" value="login">
                <h1>Sign In</h1>
                <input type="email" placeholder="Email" name="useremail" required>
                <input type="password" placeholder="Password" name="userpassword" required>
                <button type="submit">Log In</button>
                <br><br><br><br><br><br>
                <div class="social-container">
                    <a href="https://www.facebook.com" class="social" id="fb"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com" class="social" id="ins"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.gmail.com" class="social" id="gm"><i class="fas fa-envelope"></i></a>
                    <a href="https://www.twitter.com" class="social" id="tw"><i class="fab fa-twitter"></i></a>
                </div>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome</h1>
                    <p>Wherever the art of Medicine is loved, there is also a love of Humanity.</p>
                    <br>
                    <p style="color: rgb(0, 68, 255);">Already have an account?</p>
                    <button class="press" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Opticare</h1>
                    <p>Passion for excellence. Compassion for people.</p>
                    <br>
                    <p style="color: rgb(0, 68, 255);">Don't have an account?</p>
                    <button class="press" id="signUp" style="color: rgb(57, 49, 5);">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (!empty($error)) {
        echo "<script>alert('$error');</script>";
    }
    if (!empty($success)) {
        echo "<script>alert('$success');</script>";
    }
    ?>
    <script type="text/javascript">
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    </script>
</body>
</html>