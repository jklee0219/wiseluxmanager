<?php
ini_set('memory_limit','-1');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$dbconn = mysqli_connect('127.0.0.1', 'wiseluxmanager', 'wiseluxmanager1*', 'wiseluxmanager');
mysqli_set_charset($dbconn, 'utf8');

$qry = "  select seq, pcode from tb_refund where paymentprice is null ";
$list = getQueryResult($qry);

foreach($list as $v){
    $seq = $v['seq'];
    $pcode = $v['pcode'];
    if(!empty($pcode)){
        $qry = " select paymentprice from tb_trade where purchase_seq = (select seq from tb_purchase where pcode = '".$pcode."') ";
        echo $qry; exit;
        $list2 = getQueryResult($qry);
        $paymentprice = $list2[0]['paymentprice'];
        $qry = " update tb_refund set paymentprice = '".$paymentprice."' where seq = '".$seq."' ";
        //mysqli_query($dbconn, $qry);    
    }
}

function getQueryCount($query){
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

function getQueryResult($query){
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