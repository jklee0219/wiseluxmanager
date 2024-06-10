<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sendsms extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Sendsms_model');
		
		//접속체크
		$this->load->model('Access_model');
		$this->Access_model->gc_ip(); //1분이상 지난 아이피 제거
		$isblockip = $this->Access_model->chkblockip();
		if($isblockip > 0){
			$this->session->unset_userdata('ADM_LOGIN');
			$this->session->unset_userdata('ADM_ID');
			$this->session->unset_userdata('ADM_NAME');
			exit(header('Location: /admn/'));
		}
		$id = !empty($this->session->userdata('ADM_ID')) ? $this->session->userdata('ADM_ID') : '';
		$this->Access_model->set_ip($id);
	}

	public function index()
	{	
	    $list = $this->Sendsms_model->getList();
	    
	    $data = array('list' => $list);
		
		$this->load->view('sendsms/list', $data);
	}
	
	public function savesms(){
	    $seq = $this->input->post('seq', TRUE);
	    $txt = $this->input->post('txt', TRUE);
	    $txt = addslashes($txt);
	    
	    if($txt && $seq){
	        $this->Sendsms_model->insert($seq,$txt);
	    }
	}
	
	public function send_sms(){
	    $phonenum = $this->input->post('phonenum', TRUE);
	    $smscontent = $this->input->post('smscontent', TRUE);
	    $smstype = $this->input->post('smstype', TRUE);
	    
	    if($phonenum && $smscontent && strlen($phonenum) <= 11 && $smstype){
	        
	        $sID = "ncp:sms:kr:258952456405:sms"; // 서비스 ID
	        $smsURL = "https://sens.apigw.ntruss.com/sms/v2/services/".$sID."/messages";
	        $smsUri = "/sms/v2/services/".$sID."/messages";
	        $sKey = "cecd650e4ce34904a70146f086d57f04";
	        
	        $accKeyId = "rWrETQNU1qzCeIP7CLgs";
	        $accSecKey = "sv5eEoT21rF4qYG8EPjLumbtuhJxbwd04vLwTJcM";
	        
	        $sTime = floor(microtime(true) * 1000);
	        
	        // The data to send to the API
	        $postData = array(
	            'type' => $smstype,
	            'countryCode' => '82',
	            'from' => '16001393', // 발신번호 (등록되어있어야함)
	            'contentType' => 'COMM',
	            'content' => "메세지 내용",
	            'messages' => array(array('content' => $smscontent, 'to' => $phonenum))
	        );
	        
	        $postFields = json_encode($postData) ;
	        
	        $hashString = "POST {$smsUri}\n{$sTime}\n{$accKeyId}";
	        $dHash = base64_encode( hash_hmac('sha256', $hashString, $accSecKey, true) );
	        
	        $header = array(
	            // "accept: application/json",
	            'Content-Type: application/json; charset=utf-8',
	            'x-ncp-apigw-timestamp: '.$sTime,
	            "x-ncp-iam-access-key: ".$accKeyId,
	            "x-ncp-apigw-signature-v2: ".$dHash
	        );
	        
	        // Setup cURL
	        $ch = curl_init($smsURL);
	        curl_setopt_array($ch, array(
	            CURLOPT_POST => TRUE,
	            CURLOPT_RETURNTRANSFER => TRUE,
	            CURLOPT_HTTPHEADER => $header,
	            CURLOPT_POSTFIELDS => $postFields
	        ));
	        
	        $response = curl_exec($ch);
	        var_dump($response);
	    }
	}
	
}
