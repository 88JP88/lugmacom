
<?php

  


class tknValidation{

      function validationTkn($tkn,$pid){
require '../../bck/db_users.php';
$conectar=conn();
  


if($pid==2){
  $query= mysqli_query($conectar,"select * from start_token where tokenn='$tkn' and tokenTypeId ='admin' and status=1");
$nr=mysqli_num_rows($query);

if($nr==1){
$retornos = 1;
return $retornos;

}
if($nr==0){
    
    $retornos = 0;
return $retornos;
   }



}
  if($pid==3){
    $query= mysqli_query($conectar,"select * from start_token where tokenn='$tkn' and tokenTypeId ='teacher' and status=1");
$nr=mysqli_num_rows($query);


if($nr==1){
$retornos = 1;
return $retornos;
}
if($nr==0){
    $retornos = 0;
return $retornos;
   }




  }
if($pid>3){
    
    $retornos = 0;
return $retornos;
   }




  }

  
  
  


      }  

       




?>
