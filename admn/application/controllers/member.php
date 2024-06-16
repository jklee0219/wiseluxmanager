<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Member_model');

        if($this->session->userdata('ADM_AUTH') != 9){
            doMsgLocation('접근이 불가능합니다.',"http://".$_SERVER['HTTP_HOST']."/admn/");
        }
	}

	public function index()
	{	
		$condition = array();
		$page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$sid       = $this->input->get('sid', TRUE);
		$scale     = 20;
		
		if($sid) $condition['sid'] = $sid;
		
		$board_cnt = $this->Member_model->getListCnt($condition);
		
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

		$board_list = $this->Member_model->getList($condition,$scale,$first);
		
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
		
		$this->load->view('member/list', $data);
	}

	function delproc()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sid           = $this->input->get('sid', TRUE);
        
        $param = "page=".$page."&sid=".$sid;
        
        $seq = $this->input->post('seq', TRUE);

        if($seq && $seq != 1){   
            $this->Member_model->deleteList($seq);
            doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/member?".$param);
        }else{
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/member/modify?seq=".$seq."&".$param);
        }
    }
    
    function write()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sid       = $this->input->get('sid', TRUE);
        
        $param = "page=".$page."&sid=".$sid;
        
        $data = array(
            "page" => $page,
            "param" => $param,
            "auth" => $this->config->item('auth'),
        );
        
        $this->load->view('member/write', $data);
    }
    
    function writeproc()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sid       = $this->input->get('sid', TRUE);
        
        $param = "page=".$page."&sid=".$sid;

        $id = $this->input->post('id', TRUE) ? trim($this->input->post('id', TRUE)) : '';
        $auth = $this->input->post('auth', TRUE) ? trim($this->input->post('auth', TRUE)) : '';
        $password = $this->input->post('password', TRUE) ? trim($this->input->post('password', TRUE)) : '';
        $name = $this->input->post('name', TRUE) ? trim($this->input->post('name', TRUE)) : '';
        $class = $this->input->post('class', TRUE) ? trim($this->input->post('class', TRUE)) : '';
        $phone = $this->input->post('phone', TRUE) ? trim($this->input->post('phone', TRUE)) : '';
        $ordernum = $this->input->post('ordernum', TRUE) ? trim($this->input->post('ordernum', TRUE)) : 0;
        $work_status = $this->input->post('work_status', TRUE) ? trim($this->input->post('work_status', TRUE)) : '근무중';

        if($id == '' || $password == ''){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/member/write?".$param);
        }
        
        $data = array(
            'id' => $id,
            'name' => $name,
            'password' => $password,
            'name' => $name,
            'class' => $class,
            'phone' => $phone,
            'auth' => $auth,
            'ordernum' => $ordernum,
            'work_status' => $work_status
        );
        
        $this->Member_model->insertList($data);
        
        doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/member?".$param);
    }
    
    function modify()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sid       = $this->input->get('sid', TRUE);
        
        $param = "page=".$page."&sid=".$sid;
        
        $seq = $this->input->get('seq', TRUE) ? trim($this->input->get('seq', TRUE)) : '';
        
        $view = $this->Member_model->getView($seq);
        
        if(!isset($view->seq)){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/member?".$param);
        }
        
        $data = array(
            "seq" => $seq,
            "page" => $page,
            "view" => $view,
            "param" => $param,
            "auth" => $this->config->item('auth'),
        );
        
        $this->load->view('member/modify', $data);
    }
    
    function modifyproc()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sid       = $this->input->get('sid', TRUE);
        
        $param = "page=".$page."&sid=".$sid;
        
        $seq = $this->input->post('seq', TRUE) ? trim($this->input->post('seq', TRUE)) : '';

        $auth = $this->input->post('auth', TRUE) ? trim($this->input->post('auth', TRUE)) : '';
        $password = $this->input->post('password', TRUE) ? trim($this->input->post('password', TRUE)) : '';
        $name = $this->input->post('name', TRUE) ? trim($this->input->post('name', TRUE)) : '';
        $class = $this->input->post('class', TRUE) ? trim($this->input->post('class', TRUE)) : '';
        $phone = $this->input->post('phone', TRUE) ? trim($this->input->post('phone', TRUE)) : '';
        $ordernum = $this->input->post('ordernum', TRUE) ? trim($this->input->post('ordernum', TRUE)) : 0;
        $work_status = $this->input->post('work_status', TRUE) ? trim($this->input->post('work_status', TRUE)) : '근무중';
        
        //유효성 체크
        if($seq == ''){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/member/modify?seq=".$seq."&".$param);
        }
        
        $data = array(
            'name' => $name,
            'class' => $class,
            'phone' => $phone,
            'auth' => $auth,
            'ordernum' => $ordernum,
            'work_status' => $work_status
        );

        if(!empty($password)){
            $data['password'] = $password;
        }
        
        $this->Member_model->updateList($data, $seq);
        
        doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/member?".$param);
        
    }

    function idchk(){

        $chkstr = $this->input->post('chkstr', TRUE) ? trim($this->input->post('chkstr', TRUE)) : '';

        echo $this->Member_model->idchk($chkstr);

    }
	
}
