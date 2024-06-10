<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Request_model');
		$this->load->model('Login_model');
		$this->load->model('Brand_model');
		
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
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$confirmyn     = $this->input->get('confirmyn', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $scale         = 20;
        
        if($ssdate) $condition['ssdate'] = $ssdate;
        if($sedate) $condition['sedate'] = $sedate;
        if($skind) $condition['skind'] = $skind;
        if($splace) $condition['splace'] = $splace;
        if($sstock) $condition['sstock'] = $sstock;
        if($confirmyn) $condition['confirmyn'] = $confirmyn;
        if($sbrand) $condition['sbrand'] = $sbrand;
        if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        
        $board_cnt = $this->Request_model->getListCnt($condition);
        
        $total_page  = 0;
        if($board_cnt > 0) $total_page = ceil($board_cnt/$scale);
        $first       = $scale * ($page - 1);
        $no          = $board_cnt - $first + 1;
        $total_block = ceil($total_page / 15);
        $block       = ceil($page / 15);
        $first_page  = ($block - 1) * 15;
        $last_page   = $total_block <= $block ? $total_page : $block * 5;
        $prev        = $first_page;
        $next        = $last_page + 1;
        $go_page     = $first_page + 1;
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&skind=".$skind."&splace=".$splace."&sstock=".$sstock."&confirmyn=".$confirmyn."&sbrand=".$sbrand."&stype=".$stype."&skeyword=".$skeyword;
        $param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&skind=".$skind."&splace=".$splace."&sstock=".$sstock."&confirmyn=".$confirmyn."&sbrand=".$sbrand."&stype=".$stype."&skeyword=".$skeyword;
        
        $board_list = $this->Request_model->getList($condition,$scale,$first);
        $brand_list = $this->Brand_model->getList();
        $confirmdata1 = $this->Request_model->getConfirmData($condition,1); //미처리
        $confirmdata2 = $this->Request_model->getConfirmData($condition,2); //처리완료
        
        //페이징 html 생성
        $paging_html = '';
        if($scale < $board_cnt)
        {
            $paging_html = '<ul class="pagination paginationion-sm">';
            if($block > 1)
            {
                $paging_html .= '<li><a href="?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }
            
            for($go_page; $go_page <= $last_page; $go_page++)
            {
                if($page == $go_page)
                {
                    $paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
                } else
                {
                    $paging_html .= '<li><a href="?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
                }
            }
            
            if($block < $total_block) {
                $paging_html .= '<li><a href="?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            }
            $paging_html .= '</ul>';
        }
        
        $data = array(
            "ssdate" => $ssdate,
            "sedate" => $sedate,
            "skind" => $skind,
            "splace" => $splace,
            "sstock" => $sstock,
            "confirmyn" => $confirmyn,
            "sbrand" => $sbrand,
            "stype" => $stype,
            "skeyword" => $skeyword,
            "scale" => $scale,
            "page" => $page,
            "board_list" => $board_list,
            "paging_html" => $paging_html,
            "param" => $param,
            "purchase_kind" => $this->config->item('purchase_kind'),
            "goods_place" => $this->config->item('goods_place'),
            "brand_list" => $brand_list,
            "confirmdata1" => $confirmdata1,
            "confirmdata2" => $confirmdata2,
        );
        
        $this->load->view('request/list', $data);
	}

	function confirmproc()
	{	
		
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$confirmyn     = $this->input->get('confirmyn', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&skind=".$skind."&splace=".$splace."&sstock=".$sstock."&confirmyn=".$confirmyn."&sbrand=".$sbrand."&stype=".$stype."&skeyword=".$skeyword;
		
		$seq = $this->input->get('seq', TRUE);
		$this->Request_model->confirmProc($seq);
		doMsgLocation('수정 확인 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/request?".$param);
	}
	
}
