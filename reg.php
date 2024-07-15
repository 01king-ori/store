<?php
$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';
$DATABASE = 'online_store';

$conn = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["submit"])) {
    $first_name = htmlspecialchars($_POST['first_name']);
    $surname = htmlspecialchars($_POST['surname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $course = htmlspecialchars($_POST['course']);
    $school_id = htmlspecialchars($_POST['school_id']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    if (!preg_match('/@students\.tukenya\.ac\.ke$/', $email)) {
        echo "Registration Failed: This is only accessible to tukstudents only";
    } elseif ($password === $confirmPassword) {
        

        $query = "INSERT INTO buyer (first_name, surname, username, email, phone_number, password,course,school_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            die("Prepared statement failed: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssssssss", $first_name, $surname, $username, $email, $phone_number, $password,$course,$school_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful! You can now log in.";
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Registration Failed: Passwords do not match.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="boxicons/css/boxicons.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
   
    <style>
        /* POPPINS FONT */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

*{  
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body{
    background: url("imagesk/backgrorlogo.jpg");
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    overflow: hidden;
}
.wrapper{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 110vh;
    background: rgba(39, 39, 39, 0.4);
}
.nav{
    position: fixed;
    top: 0;
    display: flex;
    justify-content: space-around;
    width: 100%;
    height: 100px;
    line-height: 100px;
    background: (radial-gradient(#fff,#ffd6d6,#d3d3d3), transparent);
    z-index: 100;
}
.nav-logo p{
    color: white;
    font-size: 25px;
    font-weight: 600;
}
.nav-menu ul{
    display: flex;
}
.nav-menu ul li{
    list-style-type: none;
}
.nav-menu ul li .link{
    text-decoration: none;
    font-weight: 500;
    color: #fff;
    padding-bottom: 15px;
    margin: 0 25px;
}
.link:hover, .active{
    border-bottom: 2px solid #fff;
}
.nav-button .btn{
    width: 130px;
    height: 40px;
    font-weight: 500;
    background: rgba(255, 255, 255, 0.4);
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: .3s ease;
}
.btn:hover{
    background: rgba(255, 255, 255, 0.3);
}
#registerBtn{
    margin-left: 15px;
}
.btn.white-btn{
    background: rgba(255, 255, 255, 0.7);
}
.btn.btn.white-btn:hover{
    background: rgba(255, 255, 255, 0.5);
}
.nav-menu-btn{
    display: none;
}
.form-box{
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 512px;
    height: 500px;
    overflow-y: auto;
    z-index: 2;
}
.login-container{
    position: absolute;
    left: 4px;
    width: 500px;
    display: flex;
    flex-direction: column;
    transition: .5s ease-in-out;
}
.register-container{
    position: absolute;
    overflow-y: auto;
    left: 4px;
    width: 500px;
    display: flex;
    flex-direction: column;
    transition: .5s ease-in-out;
    scrollbar-width: none;
}
.register-container::-webkit-scrollbar {
    display: none;
}
.top span{
    color: #fff;
    font-size: small;
    padding: 10px 0;
    display: flex;
    justify-content: center;
}
.top span a{
    font-weight: 500;
    color: #fff;
    margin-left: 5px;
}
header{
    color: #fff;
    font-size: 30px;
    text-align: center;
    padding: 10px 0 30px 0;
}
.two-forms{
    display: flex;
    gap: 10px;
}
.input-field{
    font-size: 15px;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    height: 50px;
    width: 100%;
    padding: 0 10px 0 45px;
    border: none;
    border-radius: 30px;
    outline: none;
    transition: .2s ease;
}
.input-field:hover, .input-field:focus{
    background: rgba(255, 255, 255, 0.25);
}
::-webkit-input-placeholder{
    color: #fff;
}
.input-box i{
    position: relative;
    top: -35px;
    left: 17px;
    color: #fff;
}
.submit{
    font-size: 15px;
    font-weight: 500;
    color: black;
    height: 45px;
    width: 100%;
    border: none;
    border-radius: 30px;
    outline: none;
    background: rgba(255, 255, 255, 0.7);
    cursor: pointer;
    transition: .3s ease-in-out;
}
.submit:hover{
    background: rgba(255, 255, 255, 0.5);
    box-shadow: 1px 5px 7px 1px rgba(0, 0, 0, 0.2);
}
.two-col{
    display: flex;
    justify-content: space-between;
    color: #fff;
    font-size: small;
    margin-top: 10px;
}
.two-col .one{
    display: flex;
    gap: 5px;
}
.two label a{
    text-decoration: none;
    color: #fff;
}
.two label a:hover{
    text-decoration: underline;
}
@media only screen and (max-width: 786px){
    .nav-button{
        display: none;
    }
    .nav-menu.responsive{
        top: 100px;
    }
    .nav-menu{
        position: absolute;
        top: -800px;
        display: flex;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        width: 100%;
        height: 90vh;
        backdrop-filter: blur(20px);
        transition: .3s;
    }
    .nav-menu ul{
        flex-direction: column;
        text-align: center;
    }
    .nav-menu-btn{
        display: block;
    }
    .nav-menu-btn i{
        font-size: 25px;
        color: #fff;
        padding: 10px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        cursor: pointer;
        transition: .3s;
    }
    .nav-menu-btn i:hover{
        background: rgba(255, 255, 255, 0.15);
    }
}
@media only screen and (max-width: 540px) {
    .wrapper{
        min-height: 100vh;
    }
    .form-box{
        width: 100%;
        height: 500px;
    }
    .register-container, .login-container{
        width: 100%;
        padding: 0 20px;
    }
    .register-container .two-forms{
        flex-direction: column;
        gap: 0;
    }
}
    </style>
</head>
<body>
 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <p>TUK Trade Circle</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="#" class="link active">Membership</a></li>               
                <li><a href="index.html">Home</a></li>  
            </ul>
        </div>
     
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
    </nav>

<!----------------------------- Form box ----------------------------------->    
    <div class="form-box">
        

        <!------------------- registration form -------------------------->
        <div class="register-container" id="register">
            <div class="top">
                <span><Adr></Adr>Already an member? <a href="#" onclick="login()"> Login</a></span>
                <header>Sign Up</header>
            </div>
            <form method="POST" action="" onsubmit="return validateRegisterForm()">
            <div class="two-forms">
                <div class="input-box">
                    <input type="text" name="first_name" class="input-field" placeholder="Firstname">
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" name="surname" class="input-field" placeholder="Lastname">
                    <i class="bx bx-user"></i>
                </div>
            </div>
            <div class="input-box">
                    <input type="tel" name="phone_number" class="input-field" placeholder="Phone Number" required>
                    <i class="bx bx-phone"></i>
                </div>
                <div class="input-box">
                    <input type="text" name="username" class="input-field" placeholder="enter username" required>
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" name="course" class="input-field" placeholder="Course" required>
                    <i class="bx bx-book"></i>
                </div>
                <div class="input-box">
                    <input type="text" name="school_id" class="input-field" placeholder="School ID" required>
                    <i class="bx bx-id-card"></i>s
            <div class="input-box">
                <input type="text" class="input-field" name="email" placeholder="Email">
                <i class="bx bx-envelope"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field"  name="password"placeholder="Password">
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field"  name="confirmPassword"placeholder="confirmPassword">
                <i class="bx bx-lock-alt"></i>
            </div>

            <div class="input-box">
                <input type="submit" name="submit" class="submit" value="Register">
            </div>
            <div class="two-col">
            <span><Adr></Adr>Already an member? <a href="login.php" onclick="login()"> Login</a></span>
                
            </div>
</form>
        </div>

   
    </div>
</div>   




<script>
    document.getElementsByClassName("input-box").addEventListener("submit", function (event) {
        event.preventDefault();

        // Perform AJAX request to submit the login data to the server
        // Assuming the server responds with a success flag (e.g., { success: true })

        // Example response from the server
        const response = { success: true };

        if (response.success) {
            // Redirect to another page upon successful login
            window.location.href = "dashboard.php"; 
        } else {
            // Handle login failure, display an error message, etc.
            alert("Login failed. Please check your credentials.");
        }
    });
</script>




<script>
   
   function myMenuFunction() {
    var i = document.getElementById("navMenu");

    if(i.className === "nav-menu") {
        i.className += " responsive";
    } else {
        i.className = "nav-menu";
    }
   }
 
</script>

<script>

    var a = document.getElementById("loginBtn");
    var b = document.getElementById("registerBtn");
    var x = document.getElementById("login");
    var y = document.getElementById("register");



    function register() {
        x.style.left = "-510px";
        y.style.right = "5px";
        a.className = "btn";
        b.className += " white-btn";
        x.style.opacity = 0;
        y.style.opacity = 1;

    }
  

        function validateRegisterForm() {
            var first_name = document.querySelector('input[name="first_name"]').value;
            var surname = document.querySelector('input[name="surname"]').value;
            var email = document.querySelector('input[name="email"]').value;
            var password = document.querySelector('input[name="password"]').value;

            if (first_name === "" || surname === "" || email === "" || password === "") {
                alert("All fields are required.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
