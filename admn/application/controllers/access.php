<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		$this->load->model('Access_model');
		$this->Access_model->gc_ip(); //1분이상 지난 아이피 제거
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		if(stripos($referer,'wiseluxserver.cafe24.com') === false){
			include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		}
		$id = !empty($this->session->userdata('ADM_ID')) ? $this->session->userdata('ADM_ID') : '';
		$this->Access_model->set_ip($id);
	}

	public function index()
	{
		$isblockip = $this->Access_model->chkblockip();
		if($isblockip > 0){
			$this->session->unset_userdata('ADM_LOGIN');
			$this->session->unset_userdata('ADM_ID');
			$this->session->unset_userdata('ADM_NAME');
			exit(header('Location: /admn/'));
		}
		$this->load->view('access/list');
	}

	function ipchk()
	{	
		$this->Access_model->gc_ip(); //1분이상 지난 아이피 제거
		$isblockip = $this->Access_model->chkblockip();
		if($isblockip > 0){
			$this->session->unset_userdata('ADM_LOGIN');
			$this->session->unset_userdata('ADM_ID');
			$this->session->unset_userdata('ADM_NAME');
			$ip = $_SERVER['REMOTE_ADDR'];
			$this->Access_model->removeaccessip($ip);
			echo 'block';
			exit();
		}
		$id = $this->session->userdata('ADM_ID');
		$this->Access_model->set_ip($id);
	}	
	
	function accesslist(){
		$list = $this->Access_model->get_access_list();
		echo json_encode($list);
	}
	
	function blocklist(){
		$list = $this->Access_model->get_block_list();
		echo json_encode($list);
	}
	
	function setblockip(){
		//접속체크
		if($this->session->userdata('ADM_AUTH') != '9'){
			echo '<script> alert("권한이 없습니다."); </script>';
		}else{
			$ip = $this->input->post('ip', TRUE);
			$this->Access_model->setblockip($ip);
		}
		
	}
	
	function removeblockip(){
		//접속체크
		if($this->session->userdata('ADM_AUTH') != '9'){
			echo 'notaccess';
		}else{
			$ip = $this->input->post('ip', TRUE);
			$this->Access_model->removeblockip($ip);
		}
	}
	
	function setpassword(){
		//접속체크
		if($this->session->userdata('ADM_AUTH') != '9'){
			echo 'notaccess';
		}else{
			$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			if(stripos($referer,'wiseluxserver.cafe24.com') === false) exit(); 
				
			$id = $this->input->post('id', TRUE);
			$pw = $this->input->post('pw', TRUE);
			
			$this->Access_model->chpw($id, $pw);
		}
	}
}
