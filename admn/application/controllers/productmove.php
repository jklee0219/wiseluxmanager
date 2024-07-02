<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productmove extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
        $this->load->model('Goodsmove_model');
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
        $sshipplace    = $this->input->get('sshipplace', TRUE);
        $sreciveplace   = $this->input->get('sreciveplace', TRUE);
        $scale         = 20;
        
        if($sstype) $condition['sstype'] = $sstype;
        if($sbrand) $condition['sbrand'] = $sbrand;
        if($skind) $condition['skind'] = $skind;
        if($smoveyn) $condition['smoveyn'] = $smoveyn;
        if($sshipdate) $condition['sshipdate'] = $sshipdate;
        if($eshipdate) $condition['eshipdate'] = $eshipdate;
        if($srecivedate) $condition['srecivedate'] = $srecivedate;
        if($erecivedate) $condition['erecivedate'] = $erecivedate;
        if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        if($sshipplace) $condition['sshipplace'] = $sshipplace;
        if($sreciveplace) $condition['sreciveplace'] = $sreciveplace;
        
        $board_cnt = $this->Goodsmove_model->getListCnt($condition);
        
        $movenCnt = $this->Goodsmove_model->movenCnt();
        $moveyCnt = $this->Goodsmove_model->moveyCnt();
        
        $total_page  = 0;
        if($board_cnt > 0) $total_page = ceil($board_cnt/$scale);
        $first       = $scale * ($page - 1);
        $total_block = ceil($total_page / 15);
        $block       = ceil($page / 15);
        $first_page  = ($block - 1) * 15;
        $last_page   = $total_block <= $block ? $total_page : $block * 15;
        $go_page     = $first_page + 1;
        
        $param2 = "&sstype=".$sstype."&stype=".$stype."&sbrand=".$sbrand."&skind=".$skind."&smoveyn=".$smoveyn."&sshipdate=".$sshipdate."&eshipdate=".$eshipdate."&srecivedate=".$srecivedate."&erecivedate=".$erecivedate."&skeyword=".$skeyword."&sshipplace=".$sshipplace."&sreciveplace=".$sreciveplace;
        $param = "page=".$page.$param2;
        
        $brand_list = $this->Brand_model->getList();

        $board_list = $this->Goodsmove_model->getList($condition,$scale,$first);
        
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
        $alllist = $this->Goodsmove_model->getAllList();
        
        $data = array(
            "sstype" => $sstype,
            "stype" => $stype,
            "sbrand" => $sbrand,
            "skind" => $skind,
            "smoveyn" => $smoveyn,
            "sshipdate" => $sshipdate,
            "eshipdate" => $eshipdate,
            "srecivedate" => $srecivedate,
            "erecivedate" => $erecivedate,
            "skeyword" => $skeyword,
            "scale" => $scale,
            "page" => $page,
            "board_list" => $board_list,
            "paging_html" => $paging_html,
            "goodsmove_yn" => $this->config->item('goodsmove_yn'),
            "purchase_type" => $this->config->item('purchase_type'),
            "purchase_kind" => $this->config->item('purchase_kind'),
            "brand_list" => $brand_list,
            "param" => $param,
            "movenCnt" => $movenCnt,
            "moveyCnt" => $moveyCnt,
            'alllist' => $alllist,
            'sshipplace' => $sshipplace,
            'sreciveplace' => $sreciveplace,
            "goods_place" => $this->config->item('goods_place'),
        );
        
        $this->load->view('goodsmove/list', $data);
    }
    
    function delproc()
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
        $sshipplace    = $this->input->get('sshipplace', TRUE);
        $sreciveplace   = $this->input->get('sreciveplace', TRUE);
        
        $param2 = "&sstype=".$sstype."&stype=".$stype."&sbrand=".$sbrand."&skind=".$skind."&smoveyn=".$smoveyn."&sshipdate=".$sshipdate."&eshipdate=".$eshipdate."&srecivedate=".$srecivedate."&erecivedate=".$erecivedate."&skeyword=".$skeyword."&sshipplace=".$sshipplace."&sreciveplace=".$sreciveplace;
        $param = "page=".$page.$param2;
        
        $seq = $this->input->get('seq', TRUE);
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $this->Goodsmove_model->deleteList($seq);
        doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/productmove?".$param);
    }
    
    function write()
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
        $sshipplace    = $this->input->get('sshipplace', TRUE);
        $sreciveplace   = $this->input->get('sreciveplace', TRUE);
        
        $param2 = "&sstype=".$sstype."&stype=".$stype."&sbrand=".$sbrand."&skind=".$skind."&smoveyn=".$smoveyn."&sshipdate=".$sshipdate."&eshipdate=".$eshipdate."&srecivedate=".$srecivedate."&erecivedate=".$erecivedate."&skeyword=".$skeyword."&sshipplace=".$sshipplace."&sreciveplace=".$sreciveplace;
        $param = "page=".$page.$param2;
        
        $manager_list = $this->Manager_model->getList(); //담당자

        $data = array(
            "param" => $param,
            "manager_list" => $manager_list,
            "goods_place" => $this->config->item('goods_place'),
        );
        
        $this->load->view('goodsmove/write', $data);
    }
    
    function writeproc()
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
        $sshipplace    = $this->input->get('sshipplace', TRUE);
        $sreciveplace   = $this->input->get('sreciveplace', TRUE);
        
        $param2 = "&sstype=".$sstype."&stype=".$stype."&sbrand=".$sbrand."&skind=".$skind."&smoveyn=".$smoveyn."&sshipdate=".$sshipdate."&eshipdate=".$eshipdate."&srecivedate=".$srecivedate."&erecivedate=".$erecivedate."&skeyword=".$skeyword."&sshipplace=".$sshipplace."&sreciveplace=".$sreciveplace;
        $param = "page=".$page.$param2;
        
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
            'sendman' => $sendman,
            'memo' => $memo,
        );
        
        $this->Goodsmove_model->insertList($data);
        
        doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/productmove?".$param);
    }
    
    function modify()
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
        $sshipplace    = $this->input->get('sshipplace', TRUE);
        $sreciveplace   = $this->input->get('sreciveplace', TRUE);
        
        $param2 = "&sstype=".$sstype."&stype=".$stype."&sbrand=".$sbrand."&skind=".$skind."&smoveyn=".$smoveyn."&sshipdate=".$sshipdate."&eshipdate=".$eshipdate."&srecivedate=".$srecivedate."&erecivedate=".$erecivedate."&skeyword=".$skeyword."&sshipplace=".$sshipplace."&sreciveplace=".$sreciveplace;
        $param = "page=".$page.$param2;

        $manager_list = $this->Manager_model->getList(); //담당자
        
        $seq  = $this->input->get('seq', TRUE);
        $view = $this->Goodsmove_model->getView($seq);
        
        if(count($view) == 0){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/productmove?".$param);
        }
        
        $data = array(
            "param" => $param,
            "manager_list" => $manager_list,
            "goods_place" => $this->config->item('goods_place'),
            "view" => $view,
        );
        
        $this->load->view('goodsmove/modify', $data);
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
        $sshipplace    = $this->input->get('sshipplace', TRUE);
        $sreciveplace   = $this->input->get('sreciveplace', TRUE);
        
        $param2 = "&sstype=".$sstype."&stype=".$stype."&sbrand=".$sbrand."&skind=".$skind."&smoveyn=".$smoveyn."&sshipdate=".$sshipdate."&eshipdate=".$eshipdate."&srecivedate=".$srecivedate."&erecivedate=".$erecivedate."&skeyword=".$skeyword."&sshipplace=".$sshipplace."&sreciveplace=".$sreciveplace;
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
        
        doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/productmove?".$param);
        
    }
    
    function excel()
    {
        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
        }
        
        $condition = array();
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
        $sshipplace    = $this->input->get('sshipplace', TRUE);
        $sreciveplace   = $this->input->get('sreciveplace', TRUE);
        
        if($sstype) $condition['sstype'] = $sstype;
        if($sbrand) $condition['sbrand'] = $sbrand;
        if($skind) $condition['skind'] = $skind;
        if($smoveyn) $condition['smoveyn'] = $smoveyn;
        if($sshipdate) $condition['sshipdate'] = $sshipdate;
        if($eshipdate) $condition['eshipdate'] = $eshipdate;
        if($srecivedate) $condition['srecivedate'] = $srecivedate;
        if($erecivedate) $condition['erecivedate'] = $erecivedate;
        if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        if($sshipplace) $condition['sshipplace'] = $sshipplace;
        if($sreciveplace) $condition['sreciveplace'] = $sreciveplace;
        
        $board_list = $this->Goodsmove_model->getList($condition);
        
        if(count($board_list) == 0){
            doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
            return true;
        }
        
        $filename = date('Y-m-d').'_상품이동현황리스트_'.count($board_list).'건';
        
        $this->load->library("PHPExcel");
        
        $objPHPExcel = new PHPExcel();
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(18);
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A1", '번호')
        ->setCellValue("B1", '상품코드')
        ->setCellValue("C1", '매입일자')
        ->setCellValue("D1", '구분')
        ->setCellValue("E1", '종류')
        ->setCellValue("F1", '매입지점')
        ->setCellValue("G1", '모델명')
        ->setCellValue("H1", '발송일자')
        ->setCellValue("I1", '수령일자')
        ->setCellValue("J1", '이동결과')
        ->setCellValue("K1", '발송지점')
        ->setCellValue("L1", '수령지점')
        ->setCellValue("M1", '수령인');
        
        $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array( 'rgb' => 'f5ebed' )
        ));
        $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '000000')
                    )
                )
            )
            );

        $goodsmove_yn = $this->config->item('goodsmove_yn');
        
        foreach($board_list as $k => $v){
            $seq = $v->seq;
             $pcode = $v->pcode;
             $pdate = $v->pdate;
             if($pdate){
                 $pdate = strtotime($pdate);
                 $pdate = date('Y-m-d', $pdate);
             }else{
                $pdate = '';
             }
             $modelname = $v->modelname;
             $shipdate = $v->shipdate;
             if($shipdate){
                 $shipdate = strtotime($shipdate);
                 $shipdate = date('Y-m-d', $shipdate);
             }else{
                $shipdate = '';
             }
             $recivedate = $v->recivedate;
             if($recivedate){
                 $recivedate = strtotime($recivedate);
                 $recivedate = date('Y-m-d', $recivedate);
             }else{
                $recivedate = '';
             }
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".($k+2), $seq)
            ->setCellValue("B".($k+2), $pcode)
            ->setCellValue("C".($k+2), $pdate)
            ->setCellValue("D".($k+2), $v->type)
            ->setCellValue("E".($k+2), $v->kind)
            ->setCellValue("F".($k+2), $v->place)
            ->setCellValue("G".($k+2), $modelname)
            ->setCellValue("H".($k+2), $shipdate)
            ->setCellValue("I".($k+2), $recivedate)
            ->setCellValue("J".($k+2), $goodsmove_yn[$v->moveyn])
            ->setCellValue("K".($k+2), $v->shipplace)
            ->setCellValue("L".($k+2), $v->reciveplace)
            ->setCellValue("M".($k+2), $v->reciveman);
            
            $objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":M".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        
        $objPHPExcel->getActiveSheet()->getStyle('A1:M'.($k+2))->getFont()->setSize(10);
        
        $objPHPExcel->getActiveSheet()->setTitle($filename);
        $objPHPExcel->setActiveSheetIndex(0);
        $filename = iconv("UTF-8", "EUC-KR", $filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=".$filename.".xlsx");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
    
    function getPurchaseInfoFromCode(){
        $pcode = $this->input->post('pcode', TRUE);
        
        if($pcode){
            $info = $this->Goodsmove_model->getPurchaseInfoFromCode($pcode);
            $result = array();
            if(count($info) > 0){
                if($info->tb_goodsmove_seq == 0){
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
