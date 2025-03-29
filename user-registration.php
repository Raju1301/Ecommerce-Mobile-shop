<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $pincode = $_POST['pincode'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];

    // Save to database
    $query = "INSERT INTO users (username, email, password, phone, country, state, district, pincode, address, dob, age) 
              VALUES ('$username', '$email', '$password', '$phone', '$country', '$state', '$district', '$pincode', '$address', '$dob', '$age')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registration successful!'); window.location='user-login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
  width: 100%;
  height: 100vh;
  margin: 0;
  background-color: #1b1b32;
  color: #f5f6f7;
  font-family: Tahoma;
  font-size: 16px;
}

h1, p {
  margin: 1em auto;
  text-align: center;
}

form {
  width: 60vw;
  max-width: 500px;
  min-width: 300px;
  margin: 0 auto;
  padding-bottom: 2em;
}

fieldset {
  border: none;
  padding: 2rem 0;
  border-bottom: 3px solid #3b3b4f;
}

fieldset:last-of-type {
  border-bottom: none;
}

label {
  display: block;
  margin: 0.5rem 0;
}

input,
textarea,
select {
  margin: 10px 0 0 0;
  width: 100%;
  min-height: 2em;
}

input, textarea {
  background-color: #0a0a23;
  border: 1px solid #0a0a23;
  color: #ffffff;
}

.inline {
  width: unset;
  margin: 0 0.5em 0 0;
  vertical-align: middle;
}

input[type="submit"] {
  display: block;
  width: 60%;
  margin: 1em auto;
  height: 2em;
  font-size: 1.1rem;
  background-color: #3b3b4f;
  border-color: white;
  min-width: 300px;
}

input[type="file"] {
  padding: 1px 2px;
}

.inline{
  display: inline; 
}

a{
  color:#dfdfe2;
}
footer 
{
            margin-top: 50px;
            padding: 10px 0;
            background-color: steelblue;
            color: black;
            font-size: 0.9rem;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            
 }

    </style>
    <script>
        function calculateAge() {
            const dob = document.getElementById('dob').value;
            const birthDate = new Date(dob);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h1>Registration Form</h1>
        <p>Please fill out this form with the required information</p><br>
        <form method="POST">
            <fieldset>
            <label for="name">Enter your Name: <input type="text"id="name" name="username" required></label>
            <label for="mail">Enter your Email:<input type="email" name="email" id="mail" required></label>
            <label for="password">Create a New Password:<input type="password" name="password" id="password" required></label>
            </fieldset>
            <fieldset>
                <legend>Personal details (required)</legend>
                 <input type="date" name="dob" id="dob" onchange="calculateAge()" required>
            
            <!-- Age -->
            <input type="text" name="age" id="age" placeholder="Age" readonly required>
            <label for="phone">Enter your Mobile Number:<input type="text" name="phone" id="phone"  required>
            <select name="country" required>
                <option value="" disabled selected>Select Country</option>
                <option value="India">India</option>
                <!-- Add more countries -->
            </select>
            
            <!-- State Selection -->
            <select name="state" required>
                <option value="" disabled selected>Select State</option>
                <option value="Tamilnadu">Tamilnadu</option>
                <!-- Add more states -->
            </select>
            
            <!-- District Selection -->
            <select name="district" required>
                <option value="" disabled selected>Select District</option>
                <option value="Ariyalur">Ariyalur</option>
                <option value="Chengalpatu">Chengalpatu</option>
                <option value="Chennai">Chennai</option>
                <option value="Coimbatore">Coimbatore</option>
                <option value="Cuddaloore">Cuddaloore</option>
                <option value="Dharmapuri">Dharmapuri</option>
                <option value="Dindigul">Dindigul</option>
                <option value="Erode">Erode</option>
                <option value="Kallakurici">Kallakurici</option>
                <option value="Kanjipuram">Kanjipuram</option>
                <option value="Kanniyakumari">Kanniyakumari</option>
                <option value="Karur">Karur</option>
                <option value="Krishnagiri">Krishnagiri</option>
                <option value="Madurai">Madurai</option>
                <option value="Mayiladuthurai">Mayiladuthurai</option>
                <option value="Namakkal">Namakkal</option>
                <option value="Perambalur">Perambalur</option>
                <option value="Pudukottai">Pudukottai</option>
                <option value="Ramanathapuram">Ramanathapuram</option>
                <option value="Ranipet">Ranipet</option>
                <option value="Salem">Salem</option>
                <option value="Sivaganga">Sivaganga</option>
                <option value="Tenkasi">Tenkasi</option>
                <option value="Thanjavur">Thanjavur</option>
                <option value="Tirucirappalli">Tiruchirappalli</option>
                <option value="Theni">Theni</option>
                <option value="Tirupattur">Tirupattur</option>
                <option value="Tiruvallur">Tiruvallur</option>
                <option value="Nilgiris">Nilgiris</option>
                <option value="Tiruvannamalai">Tiruvannamalai</option>
                <option value="Tiruppur">Tiruppur</option>
                <option value="Vellore">Vellore</option>
                <option value="Viluppuram">Viluppuram</option>
                <!-- Add more districts -->
            </select>
            
            <!-- Pincode -->
            <input type="text" name="pincode" id="pincode" placeholder="Pincode" required>
            
            <!-- Address -->
            <input type="textarea" name="address" placeholder="Address" required>
            
            <!-- Date of Birth -->
           
            
            <button type="submit">Register</button>
        </form>
        <br>
        <a href="user-login.php">Already have an account?<br>Login</a>
    </div>
    <footer>
        © 2024 Mobile Trolley. All Rights Reserved.
    </footer>
</body>
</html>