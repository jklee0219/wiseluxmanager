<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';

		$this->load->model('Login_model');
		
		//접속체크
		$this->load->model('Access_model');
		$this->Access_model->gc_ip(); //1분이상 지난 아이피 제거
		$id = !empty($this->session->userdata('ADM_ID')) ? $this->session->userdata('ADM_ID') : '';
		$this->Access_model->set_ip($id);
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function loginconfirm()
	{
		$id = $this->input->post('id', TRUE);
		$pw = $this->input->post('pw', TRUE);
		$id = trim($id);
		$pw = trim($pw);

		$loginchk = $this->Login_model->getLoginChk($id,$pw);
		$chkCnt = $loginchk ? 1 : 0;
		$chkStr = '';

		if($chkCnt == 1)
		{
			$chkStr = 'OK';
			$this->Login_model->setLoginData($id,$pw);
			
			//세션값 셋팅
			$this->session->set_userdata('ADM_LOGIN', LOGIN_SESSION_VALUE);
			$this->session->set_userdata('ADM_ID', $id);
			$this->session->set_userdata('ADM_NAME', $loginchk->name);
			$this->session->set_userdata('ADM_AUTH', $loginchk->auth);
		}

		@header('Content-Type: text/xml; charset=UTF-8');
		@header('Pragma: no-cache');
		@header('Cache-Control: no-cache,must-revalidate');

		$xml  = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= "<root>";
		$xml .= "<result>".$chkStr."</result>";
		$xml .= "</root>";

		echo $xml;
	}

	public function logout()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$this->Access_model->removeaccessip($ip);
		$this->session->set_userdata('ADM_LOGIN', '');
		exit(header('Location: /admn'));
	}
}
