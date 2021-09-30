<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>XKCD Subscriber</title> 
</head>
<body>

    <div class="box">

        <h1>Subscribe Now</h1>
        <span>Subscribe to get xkcd web comics to your mailbox every 5 minutes!! Trust me, It's Fun :D</span>        
        <hr>

        <form method="POST" action="#">
            <input type="email" name="email" placeholder="Email Address" required/>
            <button type="submit" name="submit" value="subscribe">Subscribe</button>

        </form>

        <?php

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


            if(isset($_POST["submit"]))   //if submit button pressed
            {
                $userEmail = $_POST["email"]; //taking user entered email to variable

                $userEmail = filter_var($userEmail, FILTER_SANITIZE_EMAIL); // sanitizing user entered email

                if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) // revalidating email server side if user has bypassed 
                                                                   //html validation 
                {
                    echo "<br><h3 style='color:RED'>Invalid Email"; //this statement will executed only if user entered invalid email 
                }
                else //sending verification mail to user
                {
                    //retrieving data from database to check if  user already exists there
                    $sql = "SELECT * FROM `subscribers` WHERE `subscribers`.`Email_Id` = '$userEmail'"; 
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $check = $row['Email_Id'];


                    if ($check == $userEmail) //checking if email already exists in database
                    {
                       echo "<h3 style='color: RED;'}>Your Email already exists in our database, verify your email from mail we had sent you,
                               if you haven't verified your email</h3>";
                    }
                    else //sending verification mail to user
                    {
                        $id = md5($userEmail);

                        $sql = "INSERT INTO `subscribers` (`ID`, `Email_Id`, `Verified`) VALUES ('$id', '$userEmail', '0')";//query

                        mysqli_query($conn, $sql); // adding email to the database

                        $sql = "SELECT ID FROM `subscribers` WHERE `subscribers`.`Email_Id` = '$userEmail'"; //query for fetching id

                        $result = mysqli_query($conn, $sql); // getting column id from table where current user's emailid is stored

                        $row = mysqli_fetch_assoc($result); // fetching row of the selected email

                        $id = $row['ID']; // fetching id from 
                    
                        /*
                        if($result) 
                        {
                            echo "added to db";
                        }
                        else
                        {
                            echo "error".mysqli_error($conn);
                        }
                        */

                        $body = "This is a verification Email sent to you by <b>xkcd subscriber</b>
                                click the below button to verify your email id so, we can start 
                                sending you xkcd comic images every five minuites!!<br>
                                <a href='http://localhost/php-dhruvil111/Subscription.php?ID=$id'><button>Verify</button></a>";
                                //this is the body part of email

                        $subject = "verification Email"; // subject of email

                        $headers = array(
                            //add ur api key for sendgrid
                        ); //headers 

                        $data = array(
                            "personalizations" => array(
                                array(
                                    "to" => array(
                                        array(
                                            "email" => $userEmail
                                        )
                                    )
                                )
                            ),
                            "from" => array(
                                "email" => "" //Email for sending mail to users
                            ),
                            "subject" => $subject,
                            "content" => array(
                                array(
                                    "type" => "text/html",
                                    "value" => $body
                                )
                            )
                        ); // data converted into arrays so, we can easily encode it into json format for curl
                        
                        
                    

                        $ch = curl_init(); //initializing curl

                        curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send"); //sendgrid mail api is called
                        curl_setopt($ch, CURLOPT_POST, 1); // Post method
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //data encoded into json format
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // headers added
                        //curl_setopt($ch, CURLOPT_FOLLOWLOCARION, 1);
                        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                        $response = curl_exec($ch); // response is stored 

                        if(!$response)
                        {
                            echo "Email does not exist";
                        }
                        curl_close($ch);

                        
                        header('Location: Verification.html'); // after sucessfully sending an email page will redirect to 
                                                               //this location

                    }
                }
               
            }


        ?>
    </div>
    
</body>
</html>

