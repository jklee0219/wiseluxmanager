<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reference extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Reference_model');
		
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
		$condition = array();
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sreference_category = $this->input->get('sreference_category', TRUE);
		$scale         = 20;
		
		if($stype) $condition['stype'] = $stype;
		if($skeyword) $condition['skeyword'] = $skeyword;
		if($sreference_category) $condition['sreference_category'] = $sreference_category;
		
		$board_cnt = $this->Reference_model->getListCnt($condition);
		
		$total_page  = 0;
		if($board_cnt > 0) $total_page = ceil($board_cnt/$scale);
		$first       = $scale * ($page - 1);
		$no          = $board_cnt - $first + 1;
		$total_block = ceil($total_page / 15);
		$block       = ceil($page / 15);
		$first_page  = ($block - 1) * 15;
		$last_page   = $total_block <= $block ? $total_page : $block * 15;
		$prev        = $first_page;
		$next        = $last_page + 1;
		$go_page     = $first_page + 1;
		
		$param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&sreference_category=".$sreference_category;
		$param2 = "&stype=".$stype."&skeyword=".$skeyword."&sreference_category=".$sreference_category;
		
		$board_list = $this->Reference_model->getList($condition,$scale,$first);
		
		//페이징 html 생성
		$paging_html = '';
		if($scale < $board_cnt)
		{
			$paging_html = '<ul class="pagination pagination-sm">';
			if($block > 1)
			{
				$paging_html .= '<li><a href="/admn/reference?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
			}
		
			for($go_page; $go_page <= $last_page; $go_page++)
			{
			if($page == $go_page)
			{
			$paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
				} else
			{
			$paging_html .= '<li><a href="/admn/reference?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
			}
			}
		
					if($block < $total_block) {
					$paging_html .= '<li><a href="/admn/reference?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			}
			$paging_html .= '</ul>';
		}
		
		$data = array(
		    "sreference_category" => $sreference_category,
			"stype" => $stype,
			"skeyword" => $skeyword,
			"scale" => $scale,
			"page" => $page,
			"board_list" => $board_list,
			"paging_html" => $paging_html,
			"total_cnt" => $board_cnt,
			"param" => $param,
		    "reference_category" => $this->config->item('reference_category'),
            "getTopCnt1" => $this->Reference_model->getTopCnt($condition,'Q&A'),
            "getTopCnt2" => $this->Reference_model->getTopCnt($condition,'정가품이미지'),
            "getTopCnt3" => $this->Reference_model->getTopCnt($condition,'기타'),
            "getTopCnt4" => $this->Reference_model->getTopCnt($condition,'상품관련'),
            "getTopCnt5" => $this->Reference_model->getTopCnt($condition,'회사내규'),
		);
		
		$this->load->view('reference/list', $data);
	}

	function delproc()
	{	
		
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sreference_category = $this->input->get('sreference_category', TRUE);
		
		$param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&sreference_category=".$sreference_category;
		
		$delchk = $this->input->post('delchk', TRUE);
		if($delchk)
		{
			$delchk_str = implode(',', $delchk);
			$this->Reference_model->deleteList($delchk_str);
			doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/reference?".$param);
		}
		$seq = $this->input->get('seq', TRUE);
		if($seq)
		{
			$page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
			$this->Reference_model->deleteList($seq);
			doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/reference?".$param);
		}
	}
	
	function write()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sreference_category = $this->input->get('sreference_category', TRUE);
		
		$param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&sreference_category=".$sreference_category;
		
		$data = array(
			"page" => $page,
			"param" => $param,
			"required_mark" => $this->config->item('required_mark'),
		    "reference_category" => $this->config->item('reference_category'),
		);
		
		$this->load->view('reference/write', $data);
	}

	function writeproc()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sreference_category = $this->input->get('sreference_category', TRUE);
		
		$param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&sreference_category=".$sreference_category;
		
		$title = trim($this->input->post('title', TRUE));
		$writer = trim($this->input->post('writer', TRUE));
		$content = trim($this->input->post('content', TRUE));
		$note = trim($this->input->post('note', TRUE));
		$category = trim($this->input->post('category', TRUE));
		
		//유효성 체크
		if($title == '' || $writer == ''){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/reference");
		}

		$data = array(
			'title' => $title,
			'writer' => $writer,
			'content' => $content,
			'note' => $note,
		    'category' => $category
		);

		$this->Reference_model->insertList($data);
		
		doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/reference?".$param);
	}

	function modify()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sreference_category = $this->input->get('sreference_category', TRUE);
		
		$param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&sreference_category=".$sreference_category;
		
		$seq      = $this->input->get('seq', TRUE);
		$view = $this->Reference_model->getView($seq);
		
		if(count($view) == 0){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/reference?".$param);
		}
		
		$data = array(
			"seq" => $seq,
			"page" => $page,
			"view" => $view,
			"param" => $param,
			"required_mark" => $this->config->item('required_mark'),
		    "reference_category" => $this->config->item('reference_category')
		);
		
		$this->load->view('reference/modify', $data);
	}
	
	function view()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sreference_category = $this->input->get('sreference_category', TRUE);
		
		$param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&sreference_category=".$sreference_category;
	
		$seq      = $this->input->get('seq', TRUE);
		$view = $this->Reference_model->getView($seq);
	
		if(count($view) == 0){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/reference?".$param);
		}
	
		$data = array(
				"seq" => $seq,
				"page" => $page,
				"view" => $view,
				"param" => $param
		);
	
		$this->load->view('reference/view', $data);
	}

	function modifyproc()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sreference_category = $this->input->get('sreference_category', TRUE);
		
		$param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&sreference_category=".$sreference_category;
		
		$seq = $this->input->post('seq', TRUE);
		$title = trim($this->input->post('title', TRUE));
		$writer = trim($this->input->post('writer', TRUE));
		$content = trim($this->input->post('content', TRUE));
		$note = trim($this->input->post('note', TRUE));
		$category = trim($this->input->post('category', TRUE));
		
		//유효성 체크
		if($seq == '' || $title == '' || $writer == ''){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/reference");
		}

		$data = array(
			'title' => $title,
			'writer' => $writer,
			'content' => $content,
			'note' => $note,
		    'category' => $category
		);
		
		$this->Reference_model->updateList($data, $seq);

		doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/reference?".$param);
	}
}
