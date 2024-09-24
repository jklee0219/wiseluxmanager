<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asinfo extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
        $this->load->model('Asinfo_model');
        $this->load->model('Purchase_model');
        $this->load->model('Login_model');
        
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
        $sasyn         = $this->input->get('sasyn', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $scale         = 20;
        
        if($ssdate) $condition['ssdate'] = $ssdate;
        if($sedate) $condition['sedate'] = $sedate;
        if($stype) $condition['stype'] = $stype;
        if($sasyn) $condition['sasyn'] = $sasyn;
        if($skeyword) $condition['skeyword'] = $skeyword;
        
        $board_cnt = $this->Asinfo_model->getListCnt($condition);
        
        $asnCnt = $this->Asinfo_model->asnCnt();
        $asyCnt = $this->Asinfo_model->asyCnt();
        
        $totPprise1 = $this->Asinfo_model->getTotPprise($condition, 'Y');
        $totPprise2 = $this->Asinfo_model->getTotPprise($condition, 'N');
        
        $total_page  = 0;
        if($board_cnt > 0) $total_page = ceil($board_cnt/$scale);
        $first       = $scale * ($page - 1);
        $total_block = ceil($total_page / 15);
        $block       = ceil($page / 15);
        $first_page  = ($block - 1) * 15;
        $last_page   = $total_block <= $block ? $total_page : $block * 15;
        $go_page     = $first_page + 1;
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sasyn=".$sasyn;
        $param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sasyn=".$sasyn;
        
        $board_list = $this->Asinfo_model->getList($condition,$scale,$first);
        
        //페이징 html 생성
        $paging_html = '';
        if($scale < $board_cnt)
        {
            $paging_html = '<ul class="pagination pagination-sm">';
            if($block > 1)
            {
                $paging_html .= '<li><a href="/admn/asinfo?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }
            
            for($go_page; $go_page <= $last_page; $go_page++)
            {
                if($page == $go_page)
                {
                    $paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
                } else
                {
                    $paging_html .= '<li><a href="/admn/asinfo?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
                }
            }
            
            if($block < $total_block) {
                $paging_html .= '<li><a href="/admn/asinfo?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            }
            $paging_html .= '</ul>';
        }
        
        $data = array(
            "ssdate" => $ssdate,
            "sedate" => $sedate,
            "stype" => $stype,
            "sasyn" => $sasyn,
            "skeyword" => $skeyword,
            "scale" => $scale,
            "page" => $page,
            "board_list" => $board_list,
            "paging_html" => $paging_html,
            "trade_selltype" => $this->config->item('trade_selltype'),
            "param" => $param,
            "asnCnt" => $asnCnt,
            "asyCnt" => $asyCnt,
            "totPprise1" => $totPprise1,
            "totPprise2" => $totPprise2
        );
        
        $this->load->view('asinfo/list', $data);
    }
    
    function delproc()
    {
        
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sasyn         = $this->input->get('sasyn', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sasyn=".$sasyn;
        
        $seq = $this->input->get('seq', TRUE);
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $this->Asinfo_model->deleteList($seq);
        doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/asinfo?".$param);
    }
    
    function write()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sasyn         = $this->input->get('sasyn', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sasyn=".$sasyn;
        
        $data = array(
            "page" => $page,
            "trade_selltype" => $this->config->item('trade_selltype'),
            "trade_paymethod" => $this->config->item('trade_paymethod'),
            "required_mark" => $this->config->item('required_mark'),
            "goods_note" => $this->config->item('goods_note'),
            "goods_guarantee" => $this->config->item('goods_guarantee'),
            "goods_astype" => $this->config->item('goods_astype'),
            "param" => $param
        );
        
        $this->load->view('asinfo/write', $data);
    }
    
    function writeproc()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sasyn         = $this->input->get('sasyn', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sasyn=".$sasyn;
        
        $purchase_seq = trim($this->input->post('purchase_seq', TRUE));
        $start_date = trim($this->input->post('start_date', TRUE));
        $reason = trim($this->input->post('reason', TRUE));
        $end_date = trim($this->input->post('end_date', TRUE));
        $result = trim($this->input->post('result', TRUE));
        $note = trim($this->input->post('note', TRUE));
        $as_yn = trim($this->input->post('as_yn', TRUE));
        
        if($purchase_seq != '' || $purchase_seq != '0'){
            //이미 등록된것은 등록 되면 안됨 (강제 종료)
            $chkcnt = $this->Asinfo_model->confirmPurchase($purchase_seq);
            if($chkcnt > 0){
                exit();
            }
        }
        
        if($purchase_seq){
            //매입정보
            $purchase_pdate = trim($this->input->post('purchase_pdate', TRUE));
            $purchase_seller = trim($this->input->post('purchase_seller', TRUE));
            $purchase_sellerphone1 = trim($this->input->post('purchase_sellerphone1', TRUE));
            $purchase_sellerphone2 = trim($this->input->post('purchase_sellerphone2', TRUE));
            $purchase_sellerphone3 = trim($this->input->post('purchase_sellerphone3', TRUE));
            $purchase_sellerphone = "";
            if($purchase_sellerphone1 && $purchase_sellerphone2 && $purchase_sellerphone3){
                $purchase_sellerphone = $purchase_sellerphone1."-".$purchase_sellerphone2."-".$purchase_sellerphone3;
            }
            $reference = $this->input->post('reference', TRUE);
            if($reference) $reference = implode($reference, '|');

            //참고사항 기타 
            $reference_etc_chk = $this->input->post('reference_etc_chk', TRUE);
            $reference_etc_txt = $this->input->post('reference_etc_txt', TRUE);
            if($reference_etc_chk == '기타' && $reference_etc_txt){
                if($reference) $reference = $reference.'|';
                $reference .= '기타!@#^'.$reference_etc_txt;
            }

            $guarantee = $this->input->post('guarantee', TRUE);
            if($guarantee) $guarantee = implode($guarantee, '|');

            $purchase_note = $this->input->post('purchase_note', TRUE);

            $astype = $this->input->post('astype', TRUE);
            if($astype) $astype = implode($astype, '|');

            //astype 기타 
            $astype_etc_chk = $this->input->post('astype_etc_chk', TRUE);
            $astype_etc_txt = $this->input->post('astype_etc_txt', TRUE);
            if($astype_etc_chk == '기타'){
                if($astype) $astype = $astype.'|';
                $astype .= '기타!@#^'.$astype_etc_txt;
            }

            if($purchase_pdate || $reference || $guarantee || $astype){
                $data = array(
                    'pdate' => $purchase_pdate,
                    // 'sellerphone' => $purchase_sellerphone,
                    'reference' => $reference,
                    'astype' => $astype,
                    'guarantee' => $guarantee,
                    'note' => $purchase_note,
                    'reason' => $reason
                );
                $this->Purchase_model->updateList($data, $purchase_seq);
            }
        }
        
        if(!$as_yn) $as_yn = 'N';
        
        $data = array(
            'purchase_seq' => $purchase_seq,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'result' => $result,
            // 'note' => $note,
            'as_yn' => $as_yn,
            'udate' => date('Y-m-d H:i:s')
        );
        
        $this->Asinfo_model->insertList($data);
        
        doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/asinfo?".$param);
    }
    
    function modify()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sasyn         = $this->input->get('sasyn', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sasyn=".$sasyn;
        
        $seq  = $this->input->get('seq', TRUE);
        $view = $this->Asinfo_model->getView($seq);
        
        if(count($view) == 0){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/asinfo?".$param);
        }
        
        $data = array(
            "seq" => $seq,
            "page" => $page,
            "trade_selltype" => $this->config->item('trade_selltype'),
            "trade_paymethod" => $this->config->item('trade_paymethod'),
            "required_mark" => $this->config->item('required_mark'),
            "goods_note" => $this->config->item('goods_note'),
            "goods_guarantee" => $this->config->item('goods_guarantee'),
            "goods_astype" => $this->config->item('goods_astype'),
            "view" => $view,
            "param" => $param
        );
        
        $this->load->view('asinfo/modify', $data);
    }
    
    function modifyproc()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sasyn         = $this->input->get('sasyn', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sasyn=".$sasyn;
        
        $seq = $this->input->post('seq', TRUE);
        $purchase_seq = trim($this->input->post('purchase_seq', TRUE));
        $start_date = trim($this->input->post('start_date', TRUE));
        $reason = trim($this->input->post('reason', TRUE));
        $end_date = trim($this->input->post('end_date', TRUE));
        $result = trim($this->input->post('result', TRUE));
        $note = trim($this->input->post('note', TRUE));
        $as_yn = trim($this->input->post('as_yn', TRUE));
        
        //유효성 체크
        if($seq == ''){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/asinfo");
        }
        
        if($purchase_seq){
            //매입정보
            $purchase_pdate = trim($this->input->post('purchase_pdate', TRUE));
            $purchase_seller = trim($this->input->post('purchase_seller', TRUE));
            $purchase_sellerphone1 = trim($this->input->post('purchase_sellerphone1', TRUE));
            $purchase_sellerphone2 = trim($this->input->post('purchase_sellerphone2', TRUE));
            $purchase_sellerphone3 = trim($this->input->post('purchase_sellerphone3', TRUE));
            $purchase_sellerphone = "";
            if($purchase_sellerphone1 && $purchase_sellerphone2 && $purchase_sellerphone3){
                $purchase_sellerphone = $purchase_sellerphone1."-".$purchase_sellerphone2."-".$purchase_sellerphone3;
            }
            $reference = $this->input->post('reference', TRUE);
            if($reference) $reference = implode($reference, '|');

            //참고사항 기타 
            $reference_etc_chk = $this->input->post('reference_etc_chk', TRUE);
            $reference_etc_txt = $this->input->post('reference_etc_txt', TRUE);
            if($reference_etc_chk == '기타' && $reference_etc_txt){
                if($reference) $reference = $reference.'|';
                $reference .= '기타!@#^'.$reference_etc_txt;
            }
            
            $guarantee = $this->input->post('guarantee', TRUE);
            if($guarantee) $guarantee = implode($guarantee, '|');

            $purchase_note = $this->input->post('purchase_note', TRUE);

            $astype = $this->input->post('astype', TRUE);
            if($astype) $astype = implode($astype, '|');

            //astype 기타 
            $astype_etc_chk = $this->input->post('astype_etc_chk', TRUE);
            $astype_etc_txt = $this->input->post('astype_etc_txt', TRUE);
            if($astype_etc_chk == '기타'){
                if($astype) $astype = $astype.'|';
                $astype .= '기타!@#^'.$astype_etc_txt;
            }

            if($purchase_pdate || $reference || $guarantee){
                $data = array(
                    'pdate' => $purchase_pdate,
                    // 'sellerphone' => $purchase_sellerphone,
                    'reference' => $reference,
                    'astype' => $astype,
                    'guarantee' => $guarantee,
                    'note' => $purchase_note,
                    'reason' => $reason
                );
                $this->Purchase_model->updateList($data, $purchase_seq);
            }
        }
        
        $data = array(
            'purchase_seq' => $purchase_seq,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'result' => $result,
            // 'note' => $note,
            'as_yn' => $as_yn,
            'udate' => date('Y-m-d H:i:s')
        );
        
        $this->Asinfo_model->updateList($data, $seq);
        
        doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/asinfo?".$param);
        
    }
    
    function copyproc()
    {
        $seq = $this->input->get('seq', TRUE);
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sasyn         = $this->input->get('sasyn', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sasyn=".$sasyn;
        
        if($seq){
            $this->Asinfo_model->copy($seq);
            doMsgLocation('복사 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/asinfo/?$param");
        }
    }
    
    function excel()
    {
        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
        }
        
        $condition = array();
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sasyn         = $this->input->get('sasyn', TRUE);
        
        if($ssdate) $condition['ssdate'] = $ssdate;
        if($sedate) $condition['sedate'] = $sedate;
        if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        if($sasyn) $condition['sasyn'] = $sasyn;
        
        $board_list = $this->Asinfo_model->getList($condition);
        
        if(count($board_list) == 0){
            doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
            return true;
        }
        
        $filename = date('Y-m-d').'_AS리스트_'.count($board_list).'건';
        
        $this->load->library("PHPExcel");
        
        $objPHPExcel = new PHPExcel();
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A1", '번호')
        ->setCellValue("B1", '상품코드')
        ->setCellValue("C1", '매입일자')
        ->setCellValue("D1", '판매자')
        ->setCellValue("E1", '판매자연락처')
        ->setCellValue("F1", '모델명')
        ->setCellValue("G1", 'AS신청사유')
        ->setCellValue("H1", '신청날짜')
        ->setCellValue("I1", '마감날짜')
        ->setCellValue("J1", 'AS신청결과')
        ->setCellValue("K1", '비고');
        
        $objPHPExcel->getActiveSheet()->getStyle("A1:K1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:K1")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array( 'rgb' => 'f5ebed' )
        ));
        $objPHPExcel->getActiveSheet()->getStyle("A1:K1")->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '000000')
                    )
                )
            )
            );
        
        foreach($board_list as $k => $v){
            $seq = $v->seq;
            $pcode = $v->pcode;
            $thumb = $v->thumb;
            if(!$thumb) $thumb = '/admn/img/noimg_l.jpg';
            $pdate = $v->pdate;
            if($pdate){
                $pdate = strtotime($pdate);
                $pdate = date('Y-m-d', $pdate);
            }else{
                $pdate = '';
            }
            $seller = $v->seller;
            $sellerphone = $v->sellerphone;
            $modelname = $v->modelname;
            $reason = $v->reason;
            $start_date = $v->start_date;
            if($start_date){
                $start_date = strtotime($start_date);
                $start_date = date('Y-m-d', $start_date);
            }else{
                $start_date = '';
            }
            $end_date = $v->end_date;
            if($end_date){
                $end_date = strtotime($end_date);
                $end_date = date('Y-m-d', $end_date);
            }else{
                $end_date = '';
            }
            $result = $v->as_yn;
            $result = $result == 'Y' ? '처리완료' : '미처리';
            $note = $v->note;
            $buyer = $v->buyer;
            $buyer = $buyer ? '('.$buyer.')' : '';
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".($k+2), $seq)
            ->setCellValue("B".($k+2), $pcode)
            ->setCellValue("C".($k+2), $pdate)
            ->setCellValue("D".($k+2), $seller)
            ->setCellValue("E".($k+2), $sellerphone)
            ->setCellValue("F".($k+2), $modelname)
            ->setCellValue("G".($k+2), $reason)
            ->setCellValue("H".($k+2), $start_date)
            ->setCellValue("I".($k+2), $end_date)
            ->setCellValue("J".($k+2), $result)
            ->setCellValue("K".($k+2), $note);
            
            $objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":K".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        
        $objPHPExcel->getActiveSheet()->getStyle('A1:K'.($k+2))->getFont()->setSize(10);
        
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
        $seq = $this->input->post('seq', TRUE);
        
        if($pcode){
            $info = $this->Purchase_model->getInfoFromCode4($pcode);
            
            $result = array();
            //거래테이블에 해당 코드로 이미 등록된것이 있는지 확인
            $purchase_seq = isset($info->seq) ? $info->seq : '';
            if($purchase_seq){
                $assinfo_info = $this->Asinfo_model->getSeqAsinfo($purchase_seq);
                $assinfo_seq = isset($assinfo_info->seq) ? $assinfo_info->seq : '';
                if($assinfo_seq == '' || $seq == $assinfo_seq){
                    $result = array('result' => 'ok', 'data' => $info);
                }else{
                    //이미 등록된 데이터가 있는 경우
                    $result = array('result' => 'already', 'data' => $assinfo_seq);
                }
            }else{
                //해당코드로 인해 조회가 안되는 경우
                $result = array('result' => 'notfound');
            }
            
            echo json_encode($result);
        }
    }
    
}
