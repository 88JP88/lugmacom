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
        $primeros_ocho = substr($myuuid, 0, 8);
        $primeros_ocho2 = substr($myuuid, 0, 8);
   

$correosArray = explode(" ", $copy);


// Convertir el array en un JSON
//$correosJSON = json_encode(["cc_mails"=>$correosArray]);

// Mostrar el resultado
//echo $correosJSON;
   
    $query= mysqli_query($conectar,"INSERT INTO mail_general (mail_id,sender_id,receiver_id,copy,name,content,parent_id) VALUES ('$primeros_ocho','$sender_id','$receiver_id','$copy','$name','$content','$primeros_ocho')");
       
    foreach ($correosArray as $elemento) {
        // Realizar la acción para cada elemento

        
    $query= mysqli_query($conectar,"INSERT INTO mail_general_info (info_id,mail_id,profile_id,category_id,type) VALUES ('$primeros_ocho2','$primeros_ocho','$elemento','inbox','copy')");
       
       // echo "Realizando acción para: " . $elemento . "<br>";
    }
    
    $query= mysqli_query($conectar,"INSERT INTO mail_general_info (info_id,mail_id,profile_id,category_id,type) VALUES ('$primeros_ocho2','$primeros_ocho','$receiver_id','inbox','destine')");
    $query= mysqli_query($conectar,"INSERT INTO mail_general_info (info_id,mail_id,profile_id,category_id,type) VALUES ('$primeros_ocho2','$primeros_ocho','$sender_id','send','main')");
    
    //echo $uri; // muestra "/mi-pagina.php?id=123"
        echo "true";
    
});



Flight::route('POST /postLoged', function () {
    
    header('Access-Control-Allow-Origin: *');

    $conectar=conn();
   // $uri = $_SERVER['REQUEST_URI'];

    $username=(Flight::request()->data->username);
    $tittle=(Flight::request()->data->tittle);
    $keywords=(Flight::request()->data->keywords);
    $type=(Flight::request()->data->type);
    $public=(Flight::request()->data->public);
    $value=(Flight::request()->data->value);

    require('../../apiRepos/v1/model/modelSecurity/uuid/uuidd.php');
    $con=new generateUuid();
        $myuuid = $con->guidv4();
        $primeros_ocho = substr($myuuid, 0, 8);
    $query= mysqli_query($conectar,"SELECT repo_id FROM repo_one where repo_id='$primeros_ocho'");
    $nr=mysqli_num_rows($query);

    if($nr>=1){
        $info=[

            'data' => "ups! el id del repo está repetido , intenta nuevamente, gracias."
            
        ];
     echo json_encode(['info'=>$info]);
     //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
    }else{

      

   $keyword=$keywords." ".$username." ".$tittle." ".$type." ".$value;
    $query= mysqli_query($conectar,"INSERT INTO repo_one (repo_id,tittle,value,keywords,type,user_id,public) VALUES ('$primeros_ocho','$tittle','$value','$keyword','$type','$username','$public')");
       
    
   // echo "nn";
   echo "true"; // muestra "/mi-pagina.php?id=123"

    }
});


Flight::route('POST /putLoged', function () {
    
    header('Access-Control-Allow-Origin: *');

    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];

    $username=(Flight::request()->data->username);
    $tittle=(Flight::request()->data->tittle);
    $keywords=(Flight::request()->data->keywords);
    $type=(Flight::request()->data->type);
    $public=(Flight::request()->data->public);
    $value=(Flight::request()->data->value);
    $repo=(Flight::request()->data->repo);

    
    
     //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
   
     $keyword=$keywords." ".$username." ".$tittle." ".$type." ".$value;
   
   
    $query= mysqli_query($conectar,"UPDATE repo_one SET tittle='$tittle',value='$value',keywords='$keyword',type='$type',public='$public' WHERE user_id='$username' and repo_id='$repo'");
       
    
    
    echo "true"; // muestra "/mi-pagina.php?id=123"

    
});

Flight::route('POST /delLoged', function () {
    
    header('Access-Control-Allow-Origin: *');

    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];

    $username=(Flight::request()->data->username);
    $repo=(Flight::request()->data->repo);

    
    
     //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
   

   
    $query= mysqli_query($conectar,"DELETE FROM repo_one WHERE user_id='$username' and repo_id='$repo'");
       
    
    
    echo "true"; // muestra "/mi-pagina.php?id=123"

    
});
Flight::route('GET /getInboxMail/@id', function ($id) {
    
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,rr.type,r.parent_id,r.copy FROM mail_general r JOIN mail_general_info rr ON rr.mail_id=r.mail_id where rr.profile_id = '$id'  or r.copy LIKE'%$id%' and rr.category_id='inbox' ORDER BY r.created_at DESC LIMIT 100");
       

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
                    'copy' => $row['copy']
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


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.category_id='send' ORDER BY r.created_at DESC  LIMIT 100");
       

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
                    'copy' => $row['copy']
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


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.category_id='important' ORDER BY r.created_at DESC  LIMIT 100");
       

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
                    'copy' => $row['copy']
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


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.category_id='spam' ORDER BY r.created_at DESC  LIMIT 100");
       

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
                    'copy' => $row['copy']
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


    $query= mysqli_query($conectar,"SELECT r.mail_id,r.sender_id,r.receiver_id,r.name,r.content,r.created_at,rr.category_id,r.type,r.parent_id,r.copy FROM mail_general r  JOIN mail_general_info rr ON r.mail_id=rr.mail_id where r.sender_id = '$id' and rr.category_id='viewed' ORDER BY r.created_at DESC  LIMIT 100");
       

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
                    'copy' => $row['copy']
                ];
                
                array_push($mails,$mail);
                
        }
        $row=$query->fetch_assoc();
        //echo $repos;
        echo json_encode(['mail_constructor'=>$mails]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});

Flight::start();
