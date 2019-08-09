<?php

   $to = "vcdizon.idigitaltigers@gmail.com"; // <– replace with your address here
   $subject = "Test mail";
   $message = "Hello! This is a simple test email message.";
   $from = "mail@admin.iphixx.com";
   $headers = "From:" . $from;
   mail($to,$subject,$message,$headers);
   echo "Mail Sent.";


// $to = "test-znw6a@mail-tester.com";
// $subject = "My subject";
// $txt = "Hello world!";
// $headers = "From: webmaster@example.com" . "\r\n" .
// "CC: somebodyelse@example.com";

// mail($to,$subject,$txt,$headers);
?>