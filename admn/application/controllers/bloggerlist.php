<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bloggerlist extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Bloggerlist_model');
	}

	public function index()
	{	
		$condition = array();
		$page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        $splace    = $this->input->get('splace', TRUE);
        $svisdate  = $this->input->get('svisdate', TRUE);
        $evisdate  = $this->input->get('evisdate', TRUE);
        $spaydate  = $this->input->get('spaydate', TRUE);
        $epaydate  = $this->input->get('epaydate', TRUE);
		$scale     = 20;
		
		if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        if($splace) $condition['splace'] = $splace;
        if($svisdate) $condition['svisdate'] = $svisdate;
        if($evisdate) $condition['evisdate'] = $evisdate;
        if($spaydate) $condition['spaydate'] = $spaydate;
        if($epaydate) $condition['epaydate'] = $epaydate;
		
		$board_cnt = $this->Bloggerlist_model->getListCnt($condition);
		
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
		
		$param2 = "&stype=".$stype."&skeyword=".$skeyword."&splace=".$splace."&svisdate=".$svisdate."&evisdate=".$evisdate."&spaydate=".$spaydate."&epaydate=".$epaydate;
		$param = "page=".$page.$param2;

		$board_list = $this->Bloggerlist_model->getList($condition,$scale,$first);
        $paycnt = $this->Bloggerlist_model->getPayCnt($condition); //총정산건수(검색연동)
        $totpay = $this->Bloggerlist_model->getTotpay($condition); //총정산금액(검색연동)
		
		//페이징 html 생성
		$paging_html = '';
		if($scale < $board_cnt){
			$paging_html = '<ul class="pagination pagination-sm">';
			if($block > 1) $paging_html .= '<li><a href="?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		
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
			"slimit" => $scale,
			"page" => $page,
			"board_list" => $board_list,
			"paging_html" => $paging_html,
			"total_cnt" => $board_cnt,
			"param" => $param,
            "goods_place" => $this->config->item('goods_place'),
            "stype" => $stype,
            "skeyword" => $skeyword,
            "splace" => $splace,
            "svisdate" => $svisdate,
            "evisdate" => $evisdate,
            "spaydate" => $spaydate,
            "epaydate" => $epaydate,
            "paycnt" => $paycnt,
            "totpay" => $totpay,
		);
		
		$this->load->view('bloggerlist/list', $data);
	}

	function delproc()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        $splace    = $this->input->get('splace', TRUE);
        $svisdate  = $this->input->get('svisdate', TRUE);
        $evisdate  = $this->input->get('evisdate', TRUE);
        $spaydate  = $this->input->get('spaydate', TRUE);
        $epaydate  = $this->input->get('epaydate', TRUE);
        
        $param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&splace=".$splace."&svisdate=".$svisdate."&evisdate=".$evisdate."&spaydate=".$spaydate."&epaydate=".$epaydate;
        
        $seq = $this->input->post('seq', TRUE);

        if(!empty($seq)){   
            $this->Bloggerlist_model->deleteList($seq);
            doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/bloggerlist?".$param);
        }else{
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/bloggerlist/modify?seq=".$seq."&".$param);
        }
    }
    
    function write()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        $splace    = $this->input->get('splace', TRUE);
        $svisdate  = $this->input->get('svisdate', TRUE);
        $evisdate  = $this->input->get('evisdate', TRUE);
        $spaydate  = $this->input->get('spaydate', TRUE);
        $epaydate  = $this->input->get('epaydate', TRUE);
        
        $param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&splace=".$splace."&svisdate=".$svisdate."&evisdate=".$evisdate."&spaydate=".$spaydate."&epaydate=".$epaydate;
        
        $data = array(
            "page" => $page,
            "param" => $param,
            "goods_place" => $this->config->item('goods_place'),
        );
        
        $this->load->view('bloggerlist/write', $data);
    }
    
    function writeproc()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        $splace    = $this->input->get('splace', TRUE);
        $svisdate  = $this->input->get('svisdate', TRUE);
        $evisdate  = $this->input->get('evisdate', TRUE);
        $spaydate  = $this->input->get('spaydate', TRUE);
        $epaydate  = $this->input->get('epaydate', TRUE);
        
        $param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&splace=".$splace."&svisdate=".$svisdate."&evisdate=".$evisdate."&spaydate=".$spaydate."&epaydate=".$epaydate;

        $name = $this->input->post('name', TRUE) ? trim($this->input->post('name', TRUE)) : '';
        $phone = $this->input->post('phone', TRUE) ? trim($this->input->post('phone', TRUE)) : '';
        $keyword = $this->input->post('keyword', TRUE) ? trim($this->input->post('keyword', TRUE)) : '';
        $accountnumber = $this->input->post('accountnumber', TRUE) ? trim($this->input->post('accountnumber', TRUE)) : '';
        $place = $this->input->post('place', TRUE) ? trim($this->input->post('place', TRUE)) : '';
        $visdate = $this->input->post('visdate', TRUE) ? trim($this->input->post('visdate', TRUE)) : '';
        $paydate = $this->input->post('paydate', TRUE) ? trim($this->input->post('paydate', TRUE)) : '';
        $payprice = $this->input->post('payprice', TRUE) ? trim($this->input->post('payprice', TRUE)) : 0;
        $link = $this->input->post('link', TRUE) ? trim($this->input->post('link', TRUE)) : 0;

        if($name == ''){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/bloggerlist/write?".$param);
        }

        $payprice = str_replace(',','',$payprice);
        if(empty($visdate)) $visdate = '0000-00-00';
        if(empty($paydate)) $paydate = '0000-00-00';
        
        $data = array(
            'name' => $name,
            'phone' => $phone,
            'keyword' => $keyword,
            'accountnumber' => $accountnumber,
            'place' => $place,
            'visdate' => $visdate,
            'paydate' => $paydate,
            'payprice' => $payprice,
            'ssn' => $ssn,
            'link' => $link,
        );

        if(in_array($this->session->userdata('ADM_ID'), array('admin','admin1','dev'))){
            $ssn = $this->input->post('ssn', TRUE) ? trim($this->input->post('ssn', TRUE)) : '';
            $data['ssn'] = $ssn;
        }
        
        $this->Bloggerlist_model->insertList($data);
        
        doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/bloggerlist?".$param);
    }
    
    function modify()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        $splace    = $this->input->get('splace', TRUE);
        $svisdate  = $this->input->get('svisdate', TRUE);
        $evisdate  = $this->input->get('evisdate', TRUE);
        $spaydate  = $this->input->get('spaydate', TRUE);
        $epaydate  = $this->input->get('epaydate', TRUE);
        
        $param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&splace=".$splace."&svisdate=".$svisdate."&evisdate=".$evisdate."&spaydate=".$spaydate."&epaydate=".$epaydate;
        
        $seq = $this->input->get('seq', TRUE) ? trim($this->input->get('seq', TRUE)) : '';
        
        $view = $this->Bloggerlist_model->getView($seq);
        
        if(!isset($view->seq)){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/bloggerlist?".$param);
        }
        
        $data = array(
            "seq" => $seq,
            "page" => $page,
            "view" => $view,
            "param" => $param,
            "goods_place" => $this->config->item('goods_place'),
        );
        
        $this->load->view('bloggerlist/modify', $data);
    }
    
    function modifyproc()
    {
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        $splace    = $this->input->get('splace', TRUE);
        $svisdate  = $this->input->get('svisdate', TRUE);
        $evisdate  = $this->input->get('evisdate', TRUE);
        $spaydate  = $this->input->get('spaydate', TRUE);
        $epaydate  = $this->input->get('epaydate', TRUE);
        
        $param = "page=".$page."&stype=".$stype."&skeyword=".$skeyword."&splace=".$splace."&svisdate=".$svisdate."&evisdate=".$evisdate."&spaydate=".$spaydate."&epaydate=".$epaydate;
        
        $seq = $this->input->post('seq', TRUE) ? trim($this->input->post('seq', TRUE)) : '';

        $name = $this->input->post('name', TRUE) ? trim($this->input->post('name', TRUE)) : '';
        $phone = $this->input->post('phone', TRUE) ? trim($this->input->post('phone', TRUE)) : '';
        $keyword = $this->input->post('keyword', TRUE) ? trim($this->input->post('keyword', TRUE)) : '';
        $accountnumber = $this->input->post('accountnumber', TRUE) ? trim($this->input->post('accountnumber', TRUE)) : '';
        $place = $this->input->post('place', TRUE) ? trim($this->input->post('place', TRUE)) : '';
        $visdate = $this->input->post('visdate', TRUE) ? trim($this->input->post('visdate', TRUE)) : '';
        $paydate = $this->input->post('paydate', TRUE) ? trim($this->input->post('paydate', TRUE)) : '';
        $payprice = $this->input->post('payprice', TRUE) ? trim($this->input->post('payprice', TRUE)) : 0;
        $link = $this->input->post('link', TRUE) ? trim($this->input->post('link', TRUE)) : 0;
        
        //유효성 체크
        if($seq == ''){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/bloggerlist/modify?seq=".$seq."&".$param);
        }

        $payprice = str_replace(',','',$payprice);
        if(empty($visdate)) $visdate = '0000-00-00';
        if(empty($paydate)) $paydate = '0000-00-00';
        
        $data = array(
            'name' => $name,
            'phone' => $phone,
            'keyword' => $keyword,
            'accountnumber' => $accountnumber,
            'place' => $place,
            'visdate' => $visdate,
            'paydate' => $paydate,
            'payprice' => $payprice,
            'link' => $link
        );

        if(in_array($this->session->userdata('ADM_ID'), array('admin','admin1','dev'))){
            $ssn = $this->input->post('ssn', TRUE) ? trim($this->input->post('ssn', TRUE)) : '';
            $data['ssn'] = $ssn;
        }
        
        $this->Bloggerlist_model->updateList($data, $seq);
        
        doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/bloggerlist?".$param);
        
	}

    function excel()
    {
        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
        }

        ini_set("memory_limit","512M");

        $condition = array();
        $stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        $splace    = $this->input->get('splace', TRUE);
        $svisdate  = $this->input->get('svisdate', TRUE);
        $evisdate  = $this->input->get('evisdate', TRUE);
        $spaydate  = $this->input->get('spaydate', TRUE);
        $epaydate  = $this->input->get('epaydate', TRUE);
        
        if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        if($splace) $condition['splace'] = $splace;
        if($svisdate) $condition['svisdate'] = $svisdate;
        if($evisdate) $condition['evisdate'] = $evisdate;
        if($spaydate) $condition['spaydate'] = $spaydate;
        if($epaydate) $condition['epaydate'] = $epaydate;
        
        $board_list = $this->Bloggerlist_model->getList($condition);
        
        if(count($board_list) == 0){
            doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
            return true;
        }
        
        $filename = date('Y-m-d').'_블로거리스트_'.count($board_list).'건';
    
        $this->load->library("PHPExcel");
    
        $objPHPExcel = new PHPExcel();
    
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
    
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A1", '고유번호')
        ->setCellValue("B1", '성함')
        ->setCellValue("C1", '연락처')
        ->setCellValue("D1", '키워드')
        ->setCellValue("E1", '계좌번호')
        ->setCellValue("F1", '주민번호')
        ->setCellValue("G1", '방문지점')
        ->setCellValue("H1", '방문날짜')
        ->setCellValue("I1", '정산날짜')
        ->setCellValue("J1", '정산금액')
        ->setCellValue("K1", '링크');
    
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
            $name = $v->name;
            $phone = $v->phone;
            $keyword = $v->keyword;
            $accountnumber = $v->accountnumber;
            $place = $v->place;
            $visdate = $v->visdate;
            if($visdate && $visdate != '0000-00-00 00:00:00'){
                $visdate = strtotime($visdate);
                $visdate = date('Y-m-d', $visdate);
            }else{
                $visdate = '';
            }
            $paydate = $v->paydate;
            if($paydate && $paydate != '0000-00-00 00:00:00'){
                $paydate = strtotime($paydate);
                $paydate = date('Y-m-d', $paydate);
            }else{
                $paydate = '';
            }
            $payprice = number_format($v->payprice);
            $link = $v->link;
            $ssn = $v->ssn;
              
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".($k+2), $seq)
            ->setCellValue("B".($k+2), $name)
            ->setCellValue("C".($k+2), $phone)
            ->setCellValue("D".($k+2), $keyword)
            ->setCellValue("E".($k+2), $accountnumber)
            ->setCellValue("F".($k+2), $ssn)
            ->setCellValue("G".($k+2), $place)
            ->setCellValue("H".($k+2), $visdate)
            ->setCellValue("I".($k+2), $paydate)
            ->setCellValue("J".($k+2), $payprice)
            ->setCellValue("K".($k+2), $link);
    
            $objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":K".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getCell("K".($k+2))->getHyperlink()->setUrl($link);
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
}
