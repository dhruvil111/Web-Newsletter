<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sucessfull Subscription</title>
</head>
<body>
<div class=box>
        <h1>Congratulations, You Have Sucessfully Subscribed To Newsletter</h1>
        <span>You can Unsubscribe anytime, just click the unsubscribe button in the mail we have sent to you</span>
        <h3><br>Have Fun reading!!!<br>:)</h3>
    </div>
    <?php

        $id = $_GET['ID']; // geting id of user from get request

        //connecting to database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "xkcd";
       //creating connection
        $conn = mysqli_connect($servername, $username, $password, $database);

        if(!$conn)
        {
            die("Sorry, we failed to connect".mysqli_connect_error());
        }

        $sql = "UPDATE `subscribers` SET `Verified` = '1' WHERE `subscribers`.`ID` = '$id'"; //query for updating column verified's value

        $result = mysqli_query($conn, $sql); // getting column Verified from table where current user's id is stored
      
    /*    $sql = "SELECT * FROM `subscribers`"; // functionality to showsubscriber counts
        $result = mysqli_query($conn, $sql);

        $num = mysqli_num_rows($result);
        echo "<H1>loved by<br> $num <br>People<br> Including<br> you";*/
        
        $ch = curl_init();

        $url = "https://c.xkcd.com/random/comic/";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        
        $response = curl_exec($ch);
        $url = curl_getinfo($ch, CURLINFO_REDIRECT_URL) . "info.0.json";

        $content = file_get_contents($url);

        $decode = json_decode($content, true);

        $img = $decode['img'];
        $alt = $decode['alt'];
        $title = $decode['title'];

        echo $img, $alt, $title;

        curl_close($ch);










        
    ?>
</body>
</html>