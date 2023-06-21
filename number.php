<?php include('acn.php');
$errormsg=$succmsg='';$arrMessage=[];
$method = $_SERVER['REQUEST_METHOD'];
$data = file_get_contents('php://input');


$table= "invitees";
$data = json_decode($data);


switch($method){
    case 'POST' :
    case "PUT": 
        $phnum =  $data->phnum ??'';
        $name =   $data->name ??'';
        $edt =  $data->id ??'';

        $phnum =mysqli_real_escape_string($cnx,trim($phnum));
        $name =mysqli_real_escape_string($cnx,trim($name));
        $edt =mysqli_real_escape_string($cnx,trim($edt));
        $phnum=strval((int)$phnum);
        $errorArr=[];
        if($name== ''){
            $errorArr[] ="Name is not found";
        }
        if(strlen($phnum) > 11 || strlen($phnum) < 10 ){
            $errorArr[] ="Phone number is invalid";
        }
            
        if(count($errorArr) > 0){
            $arrMessage['message']=$errorArr;
            http_response_code(400);
            break;
        }

        if($edt == '' && $method== 'PUT') {
            $arrMessage['message']="Error: Id is missing";
            http_response_code(400);
            break;
        }
        if($method == 'POST'){
            $rsql = mysqli_query($cnx,"select * from $table where phnum = '$phnum' ") or die('Unable to query'.mysqli_error($cnx));
        
        }else{
            $rsql = mysqli_query($cnx,"select * from $table where phnum = '$phnum' and id <> '$edt' ") or die('Unable to query'.mysqli_error($cnx));
        
        }
        
        if(mysqli_num_rows($rsql) > 0   ){
            $errormsg .= " Record Already Exist";
        }                               
        if($errormsg<>''){ 
            $arrMessage['message']="Error:".$errormsg;
            http_response_code(400);
            break;
        }else{
            mysqli_autocommit($cnx,FALSE);
            if($method == 'POST'){
                mysqli_query($cnx,"insert into $table set phnum = '$phnum',name='$name',dateCreated=now(),dateupdated=now() ")or die('Unable to query'.mysqli_error($cnx));
            }else{
                mysqli_query($cnx,"update $table set phnum = '$phnum',name='$name',dateupdated=now() where id='$edt' ")or die('Unable to query'.mysqli_error($cnx));
                
            }
            mysqli_commit($cnx);
            http_response_code(200);
            $arrMessage['message']= $method == 'POST'? "Added successfully!!!":"Updated successfully!!!";
            @trail("$phnum $name was".$method == 'POST'? "created":"Updated "." created in table $table");
        }
    break;
    case "GET": 
        $id =  $_GET['id'] ??'';        
        $id =mysqli_real_escape_string($cnx,trim($id));
        
        $arrMessage['payload']=[];
        if($id == ''){
            $rsql = mysqli_query($cnx,"select * from $table  ") or die('Unable to query'.mysqli_error($cnx));
            while($tab=mysqli_fetch_assoc($rsql)){
                $arrMessage['payload'][] = $tab;
            }
        
        }else{
            $rsql = mysqli_query($cnx,"select * from $table where id = '$id'  ") or die('Unable to query'.mysqli_error($cnx));
            $tab=mysqli_fetch_assoc($rsql);
            $arrMessage['payload'] = $tab;
        }
        
        $arrMessage['message']="Retrieved Successfully!!!";
        http_response_code(200);
        
    break;
    case "DELETE": 
        $id =   $_GET['id'] ??'';         
        $id =mysqli_real_escape_string($cnx,trim($id));
        
       
        if($id == ''){
            $arrMessage['message']="Error: No Item selected";
            http_response_code(400);
            break;
        
        }else{
            $rsql = mysqli_query($cnx,"delete from $table where id = '$id'  ") or die('Unable to query'.mysqli_error($cnx));
            $arrMessage['message']=mysqli_affected_rows($cnx)." deleted Successfully!!!"; 
            http_response_code(200);
            @trail("$id was deleted in table $table");

        }
        
       
       
        
    break;
}


echo json_encode($arrMessage);
 
