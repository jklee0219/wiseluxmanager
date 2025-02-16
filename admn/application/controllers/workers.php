<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workers extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Workers_model');
	}

	public function index()
	{	
		$condition = array();
		$sid       = $this->input->get('sid', TRUE);
			
		if($sid) $condition['sid'] = $sid;
			
		$board_list = $this->Workers_model->getList($condition);

		$data = array(
			"sid" => $sid,
			"board_list" => $board_list,
			"total_cnt" => count($board_list),
			"auth" => $this->config->item('auth'),
		);
		
		$this->load->view('workers/list', $data);
	}
	
}
