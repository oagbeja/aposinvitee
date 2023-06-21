<?php include('acn.php');
include ('sendMsg.php');
$table= "invitees";
$tablevs="memoryverses";
$rsqlvs = mysqli_query($cnx,"select * from $tablevs where done='N' order by id limit 0,1  ") or die('Unable to query'.mysqli_error($cnx));
$tabvs=mysqli_fetch_assoc($rsqlvs);

$mv=$tabvs['mv'] ?? '';
if($mv == '') exit();
$mvid =$tabvs['id'] ?? '';

$rsql = mysqli_query($cnx,"select * from $table  ") or die('Unable to query'.mysqli_error($cnx));
while($tab=mysqli_fetch_assoc($rsql)){
    $name=$tab['name']??'';
    $phnum=$tab['phnum']??'';
    $message="Hi, $name, the memory verse for this week is: $mv. Apostolic Faith Church";
    sendMessage("&message=$message&mobiles=0$phnum");
}

mysqli_query($cnx,"update $tablevs set done='Y' where id="$mvid"  ") or die('Unable to query'.mysqli_error($cnx));
@trail("$mvid memory verse was sent ");
