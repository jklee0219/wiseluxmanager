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
		$page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$sid       = $this->input->get('sid', TRUE);
		$scale     = 20;
		
		if($sid) $condition['sid'] = $sid;
		
		$board_cnt = $this->Workers_model->getListCnt($condition);
		
		$total_page  = 0;
		if($board_cnt > 0) $total_page = ceil($board_cnt/$scale);
		$first       = $scale * ($page - 1);
		$no          = $board_cnt - $first + 1;
		$total_block = ceil($total_page / 5);
		$block       = ceil($page / 5);
		$first_page  = ($block - 1) * 5;
		$last_page   = $total_block <= $block ? $total_page : $block * 5;
		$prev        = $first_page;
		$next        = $last_page + 1;
		$go_page     = $first_page + 1;
		
		$param2 = "&sid=".$sid;
		$param = "page=".$page."&sid=".$sid;

		$board_list = $this->Workers_model->getList($condition,$scale,$first);
		
		//페이징 html 생성
		$paging_html = '';
		if($scale < $board_cnt){
			$paging_html = '<ul class="pagination pagination-sm">';
			if($block > 1) $paging_html .= '<li><a href="?page='.($go_page-5).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		
			for($go_page; $go_page <= $last_page; $go_page++){
				if($page == $go_page){
					$paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
				}else{
					$paging_html .= '<li><a href="?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
				}
			}
		
			if($block < $total_block) $paging_html .= '<li><a href="?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			$paging_html .= '</ul>';
		}
		
		$data = array(
			"sid" => $sid,
			"slimit" => $scale,
			"page" => $page,
			"board_list" => $board_list,
			"paging_html" => $paging_html,
			"total_cnt" => $board_cnt,
			"param" => $param,
            "auth" => $this->config->item('auth'),
		);
		
		$this->load->view('workers/list', $data);
	}
	
}
