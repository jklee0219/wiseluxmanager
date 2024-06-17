<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productmvdate extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
        $this->load->model('Productmovedate_model');
        $this->load->model('Brand_model');
        $this->load->model('Manager_model');
        
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
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $scale         = 20;
        
        if($stype) $condition['stype'] = $stype;
        if($ssdate) $condition['ssdate'] = $ssdate;
        if($sedate) $condition['sedate'] = $sedate;
        if($skeyword) $condition['skeyword'] = $skeyword;
        
        $board_cnt = $this->Productmovedate_model->getListCnt($condition);
        
        $total_page  = 0;
        if($board_cnt > 0) $total_page = ceil($board_cnt/$scale);
        $first       = $scale * ($page - 1);
        $total_block = ceil($total_page / 15);
        $block       = ceil($page / 15);
        $first_page  = ($block - 1) * 15;
        $last_page   = $total_block <= $block ? $total_page : $block * 15;
        $go_page     = $first_page + 1;
        
        $param2 = "&stype=".$stype."&ssdate=".$ssdate."&sedate=".$sedate."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;

        $board_list = $this->Productmovedate_model->getList($condition,$scale,$first);
        
        //페이징 html 생성
        $paging_html = '';
        if($scale < $board_cnt)
        {
            $paging_html = '<ul class="pagination pagination-sm">';
            if($block > 1)
            {
                $paging_html .= '<li><a href="/admn/productmove?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }
            
            for($go_page; $go_page <= $last_page; $go_page++)
            {
                if($page == $go_page)
                {
                    $paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
                } else
                {
                    $paging_html .= '<li><a href="/admn/productmove?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
                }
            }
            
            if($block < $total_block) {
                $paging_html .= '<li><a href="/admn/productmove?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            }
            $paging_html .= '</ul>';
        }

        //달력 전체 리스트
        $alllist = $this->Productmovedate_model->getAllList();
        
        $data = array(
            "stype" => $stype,
            "ssdate" => $ssdate,
            "sedate" => $sedate,
            "skeyword" => $skeyword,
            "scale" => $scale,
            "page" => $page,
            "board_list" => $board_list,
            "paging_html" => $paging_html,
            'param' => $param,
            'alllist' => $alllist
        );
        
        $this->load->view('productmvdate/list', $data);
    }
    
    function delproc()
    {
        
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype         = $this->input->get('stype', TRUE);
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        
        $param2 = "&stype=".$stype."&ssdate=".$ssdate."&sedate=".$sedate."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        $seq = $this->input->get('seq', TRUE);
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $this->Productmovedate_model->deleteList($seq);
        doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/productmvdate?".$param);
    }
    
    function write()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype         = $this->input->get('stype', TRUE);
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        
        $param2 = "&stype=".$stype."&ssdate=".$ssdate."&sedate=".$sedate."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;

        $data = array(
            "param" => $param,
            "goods_place" => $this->config->item('goods_place'),
        );
        
        $this->load->view('productmvdate/write', $data);
    }
    
    function writeproc()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype         = $this->input->get('stype', TRUE);
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        
        $param2 = "&stype=".$stype."&ssdate=".$ssdate."&sedate=".$sedate."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        $purchase_seq = trim($this->input->post('purchase_seq', TRUE));
        $movedate = trim($this->input->post('movedate', TRUE));
        $moveyn = trim($this->input->post('moveyn', TRUE));
        $shipplace = trim($this->input->post('shipplace', TRUE));
        $reciveplace = trim($this->input->post('reciveplace', TRUE));
        $memo = trim($this->input->post('memo', TRUE));

        if(empty($movedate)) $movedate = null;
        
        if(empty($moveyn)){
            $this->Productmovedate_model->moveyn($purchase_seq, $moveyn);
        }

        $data = array(
            'purchase_seq' => $purchase_seq,
            'movedate' => $movedate,
            'shipplace' => $shipplace,
            'reciveplace' => $reciveplace,
            'note' => $memo,
        );
        
        $this->Productmovedate_model->insertList($data);
        
        doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/productmvdate?".$param);
    }
    
    function modify()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype         = $this->input->get('stype', TRUE);
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        
        $param2 = "&stype=".$stype."&ssdate=".$ssdate."&sedate=".$sedate."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;

        $manager_list = $this->Manager_model->getList(); //담당자
        
        $seq  = $this->input->get('seq', TRUE);
        $view = $this->Productmovedate_model->getView($seq);
        
        if(count($view) == 0){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/productmvdate?".$param);
        }
        
        $data = array(
            "param" => $param,
            "manager_list" => $manager_list,
            "goods_place" => $this->config->item('goods_place'),
            "view" => $view,
        );
        
        $this->load->view('productmvdate/modify', $data);
    }
    
    function modifyproc()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sstype        = $this->input->get('sstype', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $smoveyn       = $this->input->get('smoveyn', TRUE); 
        $sshipdate     = $this->input->get('sshipdate', TRUE);
        $eshipdate     = $this->input->get('eshipdate', TRUE);
        $srecivedate   = $this->input->get('srecivedate', TRUE);
        $erecivedate   = $this->input->get('erecivedate', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        
        $param2 = "&sstype=".$sstype."&stype=".$stype."&sbrand=".$sbrand."&skind=".$skind."&smoveyn=".$smoveyn."&sshipdate=".$sshipdate."&eshipdate=".$eshipdate."&srecivedate=".$srecivedate."&erecivedate=".$erecivedate."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        $seq  = $this->input->post('seq', TRUE);
        $purchase_seq = trim($this->input->post('purchase_seq', TRUE));
        $shipdate = trim($this->input->post('shipdate', TRUE));
        $recivedate = trim($this->input->post('recivedate', TRUE));
        $moveyn = trim($this->input->post('moveyn', TRUE));
        $shipplace = trim($this->input->post('shipplace', TRUE));
        $reciveplace = trim($this->input->post('reciveplace', TRUE));
        $reciveman = trim($this->input->post('reciveman', TRUE));
        $sendman = trim($this->input->post('sendman', TRUE));
        $memo = trim($this->input->post('memo', TRUE));

        if(empty($shipdate)) $shipdate = null;
        if(empty($recivedate)) $recivedate = null;
        
        $data = array(
            'purchase_seq' => $purchase_seq,
            'shipdate' => $shipdate,
            'recivedate' => $recivedate,
            'moveyn' => $moveyn,
            'shipplace' => $shipplace,
            'reciveplace' => $reciveplace,
            'reciveman' => $reciveman,
            'udate' => date('Y-m-d H:i:s'),
            'sendman' => $sendman,
            'memo' => $memo,
        );
        
        $this->Goodsmove_model->updateList($data, $seq);
        
        doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/productmvdate?".$param);
        
    }
    
    function getPurchaseInfoFromCode(){
        $pcode = $this->input->post('pcode', TRUE);
        
        if($pcode){
            $info = $this->Productmovedate_model->getPurchaseInfoFromCode($pcode);
            $result = array();
            if(count($info) > 0){
                if($info->tb_productmovedate_seq == 0){
                    $result = array('result' => 'ok', 'data' => $info);
                }else{
                    $result = array('result' => 'already', 'data' => $info);
                }
            }else{
                //해당코드로 인해 조회가 안되는 경우
                $result = array('result' => 'notfound');
            }
            
            echo json_encode($result);
        }
    }
    
}
