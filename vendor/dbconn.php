<?php
$dbconn = mysqli_connect('127.0.0.1', 'wiseluxmanager', 'wiseluxmanager1*', 'wiseluxmanager');
mysqli_set_charset($dbconn, 'utf8'); 

function getQueryCount($query)
{
   global $dbconn;
   $returnResultValue = 0;
   if ($dbconn) {
      $result = mysqli_query($dbconn,$query);
      $returnResultValue = mysqli_fetch_row($result);
      $returnResultValue = $returnResultValue[0];
      mysqli_free_result($result);
   }
   return $returnResultValue;
}

function getQueryResult($query)
{
   global $dbconn;
   $returnResultArray = array ();
   if ($dbconn && $query) {
      $result = mysqli_query($dbconn,$query);
      if ($result) {
         if(mysqli_num_rows($result) > 0){
            while ($qry_result_row = $result->fetch_assoc()) {
               array_push($returnResultArray,$qry_result_row);
            }
         }
         mysqli_free_result($result);
      }
   }
   return $returnResultArray;
}
?>