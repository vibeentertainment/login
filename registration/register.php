<?php
if (isset($_POST['submit'])) {
    if (isset($_POST['firstname']) && isset($_POST['lastname']) &&
        isset($_POST['phone']) && isset($_POST['dob']) &&
        isset($_POST['email']) && isset($_POST['password'])) {
        
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
		$phone = $_POST['phone'];   
		$dob = $_POST['dob'];
		$email = $_POST['email'];
        $password = $_POST['password'];
        
		$host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "test";

        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            die('Could not connect to the database.');
        }
        else {
            $Select = "SELECT email FROM registration WHERE email = ? LIMIT 1";
            $Insert = "INSERT INTO registration (firstname, lastname, phone, dob, email, password) values(?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($Select);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($resultEmail);
            $stmt->store_result();
            $stmt->fetch();
            $rnum = $stmt->num_rows;

            if ($rnum == 0) {
                $stmt->close();

                $stmt = $conn->prepare($Insert);
                $stmt->bind_param("ssisss",$firstname, $lastname, $phone, $dob, $email, $password);
                if ($stmt->execute()) {
                    echo "PHP Redirect";
				header("Location: ../msg/confirm.html");
                }
                else {
                    echo $stmt->error;
                }
            }
            else {
                header("Location: ../msg/error1.html");
            }
            $stmt->close();
            $conn->close();
        }
    }
    else {
        echo "All field are required.";
        die();
    }
}
else {
    echo "Submit button is not set";
}
?>