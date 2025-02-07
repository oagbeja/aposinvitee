<?php include 'acn.php';include 'sendMsg.php';
$errormsg   = $succmsg   = '';
$arrMessage = [];
$method     = $_SERVER['REQUEST_METHOD'];
$data       = file_get_contents('php://input');

$table = "invitees";
$data  = json_decode($data);

switch ($method) {
    case 'POST':

        $message = $data->message ?? '';
        if (! $message) {
            $arrMessage['message'] = "No message";
            http_response_code(400);
            break;
        }

        $rsql = mysqli_query($cnx, "select * from $table  ") or die('Unable to query' . mysqli_error($cnx));
        while ($tab = mysqli_fetch_assoc($rsql)) {
            $name    = $tab['name'] ?? '';
            $phnum   = $tab['phnum'] ?? '';
            $message = "Hi $name, $message";
            sendMessage("&message=$message&mobiles=0$phnum");
        }

        http_response_code(200);
        $arrMessage['message'] = "Mesage sent successfully!!!";
        @trail("message $message sent to all");

        break;

}

echo json_encode($arrMessage);
