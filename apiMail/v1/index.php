<?php

require 'flight/Flight.php';

require 'database/db_users.php';

Flight::route('POST /postMail', function () {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    //$uri = $_SERVER['REQUEST_URI'];


    $sender_id=(Flight::request()->data->sender_id);
    $receiver_id=(Flight::request()->data->receiver_id);
    $name=(Flight::request()->data->name);
    $content=(Flight::request()->data->content);
    $copy=(Flight::request()->data->copy);

    require('../../apiMail/v1/model/modelSecurity/uuid/uuidd.php');
    $con=new generateUuid();
        $myuuid = $con->guidv4();
        $myuuid2 = $con->guidv4();
        $primeros_ocho = substr($myuuid, 0, 8);
        $primeros_ocho2 = substr($myuuid, 0, 8);
        $primeros_ocho3 = substr($myuuid, 0, 8);
        $primeros_ocho5 = substr($myuuid, 0, 8);
        $primeros_ocho6 = substr($myuuid2, 0, 8);
   

$correosArray = explode(" ", $copy);


// Convertir el array en un JSON
//$correosJSON = json_encode(["cc_mails"=>$correosArray]);

// Mostrar el resultado
//echo $correosJSON;
   
    $query= mysqli_query($conectar,"INSERT INTO mail_general (mail_id,sender_id,receiver_id,copy,name,content,parent_id) VALUES ('$primeros_ocho','$sender_id','$receiver_id','$copy','$name','$content','$primeros_ocho')");
    $query= mysqli_query($conectar,"INSERT INTO mail_general_info (general_id,info_id,mail_id,profile_id,category_id,type) VALUES ('$primeros_ocho6','$primeros_ocho2','$primeros_ocho','$sender_id','send','main')");
    
    foreach ($correosArray as $elemento) {
        // Realizar la acción para cada elemento
        $myuuid = $con->guidv4();
        $primeros_ocho4 = substr($myuuid, 0, 8);
        
    $query= mysqli_query($conectar,"INSERT INTO mail_general_info (general_id,info_id,mail_id,profile_id,category_id,type) VALUES ('$primeros_ocho4','$primeros_ocho2','$primeros_ocho','$elemento','inbox','copy')");
       
       // echo "Realizando acción para: " . $elemento . "<br>";
    }
    
    $query= mysqli_query($conectar,"INSERT INTO mail_general_info (general_id,info_id,mail_id,profile_id,category_id,type) VALUES ('$primeros_ocho5','$primeros_ocho2','$primeros_ocho','$receiver_id','inbox','destine')");
    
    //echo $uri; // muestra "/mi-pagina.php?id=123"
        echo "true";
    
});

Flight::route('POST /putCategoryMail', function () {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    //$uri = $_SERVER['REQUEST_URI'];


    $general_id=(Flight::request()->data->general_id);
    $value=(Flight::request()->data->value);

  if($value=="inbox"){
    $query= mysqli_query($conectar,"UPDATE mail_general_info SET category_id='inbox' WHERE general_id='$general_id'");
   
  }
  if($value=="important"){
    $query= mysqli_query($conectar,"UPDATE mail_general_info SET category_id='important' WHERE general_id='$general_id'");
   
  }
  if($value=="spam"){
    $query= mysqli_query($conectar,"UPDATE mail_general_info SET category_id='spam' WHERE general_id='$general_id'");
   
  }
  if($value=="viewed"){
    $query= mysqli_query($conectar,"UPDATE mail_general_info SET category_id='viewed' WHERE general_id='$general_id'");
   
  }
  if($value=="rec"){
    $query= mysqli_query($conectar,"UPDATE mail_general_info SET category_id='recicler' WHERE general_id='$general_id'");
   
  }
  if($value=="del"){
    $query= mysqli_query($conectar,"DELETE FROM mail_general_info WHERE general_id='$general_id'");
   
  }
    //echo $uri; // muestra "/mi-pagina.php?id=123"
        echo "true";
    
});



Flight::route('GET /getInboxMail/@id', function ($id) {
    
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,rr.type,r.parent_id,r.copy,rr.general_id FROM mail_general r JOIN mail_general_info rr ON rr.mail_id=r.mail_id where rr.profile_id = '$id' and rr.category_id='inbox' and rr.type in ('destine','copy') ORDER BY r.created_at DESC LIMIT 100");
       

        $mails=[];
 
        while($row = $query->fetch_assoc())
        {
                $mail=[
                    'mail_id' => $row['mail_id'],
                    'sender_id' => $row['sender_id'],
                    'receiver_id' => $row['receiver_id'],
                    'name' => $row['name'],
                    'content' => $row['content'],
                    'send' => $row['created_at'],
                    'category_id' => $row['category_id'],
                    'type' => $row['type'],
                    'parent_id' => $row['parent_id'],
                    'copy' => $row['copy'],
                    'general_id' => $row['general_id']
                ];
                
                array_push($mails,$mail);
                
        }
        $row=$query->fetch_assoc();
        //echo $repos;
        echo json_encode(['mail_constructor'=>$mails]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});


Flight::route('GET /getCopyMail/@id', function ($id) {
    
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,rr.type,r.parent_id,r.copy,rr.general_id FROM mail_general r JOIN mail_general_info rr ON rr.mail_id=r.mail_id where rr.profile_id ='$id' and rr.type='copy' and rr.category_id='inbox' ORDER BY r.created_at DESC LIMIT 100");
       

        $mails=[];
 
        while($row = $query->fetch_assoc())
        {
                $mail=[
                    'mail_id' => $row['mail_id'],
                    'sender_id' => $row['sender_id'],
                    'receiver_id' => $row['receiver_id'],
                    'name' => $row['name'],
                    'content' => $row['content'],
                    'send' => $row['created_at'],
                    'category_id' => $row['category_id'],
                    'type' => $row['type'],
                    'parent_id' => $row['parent_id'],
                    'copy' => $row['copy'],
                    
                    'general_id' => $row['general_id']
                ];
                
                array_push($mails,$mail);
                
        }
        $row=$query->fetch_assoc();
        //echo $repos;
        echo json_encode(['mail_constructor'=>$mails]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});


Flight::route('GET /getSendMail/@id', function ($id) {
    
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy,rr.general_id FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.type='main' and rr.category_id in ('inbox','send') ORDER BY r.created_at DESC  LIMIT 100");
       

        $mails=[];
 
        while($row = $query->fetch_assoc())
        {
                $mail=[
                    'mail_id' => $row['mail_id'],
                    'sender_id' => $row['sender_id'],
                    'receiver_id' => $row['receiver_id'],
                    'name' => $row['name'],
                    'content' => $row['content'],
                    'send' => $row['created_at'],
                    'category_id' => $row['category_id'],
                    'type' => $row['type'],
                    'parent_id' => $row['parent_id'],
                    'copy' => $row['copy'],
                    'general_id' => $row['general_id']
                ];
                
                array_push($mails,$mail);
                
        }
        $row=$query->fetch_assoc();
        //echo $repos;
        echo json_encode(['mail_constructor'=>$mails]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});



Flight::route('GET /getImportantMail/@id', function ($id) {
    
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy,rr.general_id FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.category_id='important' ORDER BY r.created_at DESC  LIMIT 100");
       

        $mails=[];
 
        while($row = $query->fetch_assoc())
        {
                $mail=[
                    'mail_id' => $row['mail_id'],
                    'sender_id' => $row['sender_id'],
                    'receiver_id' => $row['receiver_id'],
                    'name' => $row['name'],
                    'content' => $row['content'],
                    'send' => $row['created_at'],
                    'category_id' => $row['category_id'],
                    'type' => $row['type'],
                    'parent_id' => $row['parent_id'],
                    'copy' => $row['copy'],
                    'general_id' => $row['general_id']
                ];
                
                array_push($mails,$mail);
                
        }
        $row=$query->fetch_assoc();
        //echo $repos;
        echo json_encode(['mail_constructor'=>$mails]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});

Flight::route('GET /getSpamMail/@id', function ($id) {
    
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy,rr.general_id FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.category_id='spam' ORDER BY r.created_at DESC  LIMIT 100");
       

        $mails=[];
 
        while($row = $query->fetch_assoc())
        {
                $mail=[
                    'mail_id' => $row['mail_id'],
                    'sender_id' => $row['sender_id'],
                    'receiver_id' => $row['receiver_id'],
                    'name' => $row['name'],
                    'content' => $row['content'],
                    'send' => $row['created_at'],
                    'category_id' => $row['category_id'],
                    'type' => $row['type'],
                    'parent_id' => $row['parent_id'],
                    'copy' => $row['copy'],
                    'general_id' => $row['general_id']
                ];
                
                array_push($mails,$mail);
                
        }
        $row=$query->fetch_assoc();
        //echo $repos;
        echo json_encode(['mail_constructor'=>$mails]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});



Flight::route('GET /getReadMail/@id', function ($id) {
    
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy,rr.general_id FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.category_id='viewed' ORDER BY r.created_at DESC  LIMIT 100");
       

        $mails=[];
 
        while($row = $query->fetch_assoc())
        {
                $mail=[
                    'mail_id' => $row['mail_id'],
                    'sender_id' => $row['sender_id'],
                    'receiver_id' => $row['receiver_id'],
                    'name' => $row['name'],
                    'content' => $row['content'],
                    'send' => $row['created_at'],
                    'category_id' => $row['category_id'],
                    'type' => $row['type'],
                    'parent_id' => $row['parent_id'],
                    'copy' => $row['copy'],
                    'general_id' => $row['general_id']
                ];
                
                array_push($mails,$mail);
                
        }
        $row=$query->fetch_assoc();
        //echo $repos;
        echo json_encode(['mail_constructor'=>$mails]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});


Flight::route('GET /getReciclerMail/@id', function ($id) {
    
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy,rr.general_id FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.category_id='recicler' or r.copy like '%$id%' and rr.category_id='recicler' ORDER BY r.created_at DESC  LIMIT 100");
       

        $mails=[];
 
        while($row = $query->fetch_assoc())
        {
                $mail=[
                    'mail_id' => $row['mail_id'],
                    'sender_id' => $row['sender_id'],
                    'receiver_id' => $row['receiver_id'],
                    'name' => $row['name'],
                    'content' => $row['content'],
                    'send' => $row['created_at'],
                    'category_id' => $row['category_id'],
                    'type' => $row['type'],
                    'parent_id' => $row['parent_id'],
                    'copy' => $row['copy'],
                    'general_id' => $row['general_id']
                ];
                
                array_push($mails,$mail);
                
        }
        $row=$query->fetch_assoc();
        //echo $repos;
        echo json_encode(['mail_constructor'=>$mails]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});

Flight::start();
