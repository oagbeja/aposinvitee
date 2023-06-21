<?php include('acn.php');
include ('sendMsg.php');
$table= "invitees";

$rsql = mysqli_query($cnx,"select * from $table  ") or die('Unable to query'.mysqli_error($cnx));
while($tab=mysqli_fetch_assoc($rsql)){
    $name=$tab['name']??'';
    $phnum=$tab['phnum']??'';
    $message="Hi, $name, you are invited to the Apostolic Faith Church, Arab road or any branch close to you. 
    Your spiritual welfare is our concern, seats are free.";
    sendMessage("&message=$message&mobiles=0$phnum");
}
@trail("Invitation was sent ");