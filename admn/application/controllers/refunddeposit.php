<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refunddeposit extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Refunddeposit_model');
		
		//접속체크
		$this->load->model('Access_model');
		$this->load->model('Manager_model');
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
		$condition  = [];
		$page       = $this->input->get('page', TRUE) ?: 1;
		$ssdate     = $this->input->get('ssdate', TRUE) ?: '';
		$sedate     = $this->input->get('sedate', TRUE) ?: '';
		$stype      = $this->input->get('stype', TRUE) ?: '';
		$skeyword   = $this->input->get('skeyword', TRUE) ?: '';
		$scale      = 20;
		
		if($ssdate) $condition['ssdate'] = $ssdate;
		if($sedate) $condition['sedate'] = $sedate;
		if($stype) $condition['stype'] = $stype;
		if($skeyword) $condition['skeyword'] = $skeyword;
		
		$board_cnt = $this->Refunddeposit_model->getListCnt($condition);
		
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
		
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
		
		$board_list = $this->Refunddeposit_model->getList($condition,$scale,$first);

		//페이징 html 생성
		$paging_html = '';
		if($scale < $board_cnt)
		{
			$paging_html = '<ul class="pagination pagination-sm">';
			if($block > 1) $paging_html .= '<li><a href="/admn/refunddeposit?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		
			for($go_page; $go_page <= $last_page; $go_page++){
				if($page == $go_page){
					$paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
				}else{
					$paging_html .= '<li><a href="/admn/refunddeposit?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
				}
			}
		
			if($block < $total_block) $paging_html .= '<li><a href="/admn/refunddeposit?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			$paging_html .= '</ul>';
		}
		
		$data = array(
			"ssdate" => $ssdate,
			"sedate" => $sedate,
			"stype" => $stype,
			"skeyword" => $skeyword,
			"scale" => $scale,
			"page" => $page,
			"board_list" => $board_list,
			"paging_html" => $paging_html,
			"total_cnt" => $board_cnt,
			"param" => $param
		);
		
		$this->load->view('refunddeposit/list', $data);
	}

	function delproc()
	{	
		$seq        = $this->input->get('seq', TRUE) ?: '';
		$page       = $this->input->get('page', TRUE) ?: 1;
		$ssdate     = $this->input->get('ssdate', TRUE) ?: '';
		$sedate     = $this->input->get('sedate', TRUE) ?: '';
		$stype      = $this->input->get('stype', TRUE) ?: '';
		$skeyword   = $this->input->get('skeyword', TRUE) ?: '';

		if(empty($seq)){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/refunddeposit");
		}
		
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
		
		$this->Refunddeposit_model->deleteList($seq);
		doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/refunddeposit?".$param);
	}
	
	function write()
	{
		$page       = $this->input->get('page', TRUE) ?: 1;
		$ssdate     = $this->input->get('ssdate', TRUE) ?: '';
		$sedate     = $this->input->get('sedate', TRUE) ?: '';
		$stype      = $this->input->get('stype', TRUE) ?: '';
		$skeyword   = $this->input->get('skeyword', TRUE) ?: '';
		
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;

		$manager_list = $this->Manager_model->getList(); //담당자
		
		$data = array(
			"page" => $page,
			"ssdate" => $ssdate,
			"sedate" => $sedate,
			"stype" => $stype,
			"skeyword" => $skeyword,
			"param" => $param,
			'manager_list' => $manager_list
		);
		
		$this->load->view('refunddeposit/write', $data);
	}

	function writeproc()
	{
		$page       = $this->input->get('page', TRUE) ?: 1;
		$ssdate     = $this->input->get('ssdate', TRUE) ?: '';
		$sedate     = $this->input->get('sedate', TRUE) ?: '';
		$stype      = $this->input->get('stype', TRUE) ?: '';
		$skeyword   = $this->input->get('skeyword', TRUE) ?: '';
		
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
		
		$deposit_date = trim($this->input->post('deposit_date', TRUE) ?: '');
		$deposit_amount = trim($this->input->post('deposit_amount', TRUE) ?: '');
		$deposit_amount = str_replace(',', '', $deposit_amount);
		$depositor_name = trim($this->input->post('depositor_name', TRUE) ?: '');
		$depositor_contact = '';
		$depositor_contact1 = trim($this->input->post('depositor_contact1', TRUE) ?: '');
		$depositor_contact2 = trim($this->input->post('depositor_contact2', TRUE) ?: '');
		$depositor_contact3 = trim($this->input->post('depositor_contact3', TRUE) ?: '');
		if ($depositor_contact1 && $depositor_contact2 && $depositor_contact3) {
			$depositor_contact = $depositor_contact1 . '-' . $depositor_contact2 . '-' . $depositor_contact3;
		}
		$remarks = trim($this->input->post('remarks', TRUE) ?: '');
		$manager = trim($this->input->post('manager', TRUE) ?: '');
		
		$data = array(
		    'deposit_date' => $deposit_date,
		    'deposit_amount' => $deposit_amount,
		    'depositor_name' => $depositor_name,
		    'depositor_contact' => $depositor_contact,
		    'remarks' => $remarks,
		    'manager' => $manager,
		);

		$this->Refunddeposit_model->insertList($data);

		doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/refunddeposit?".$param);
	}

	function modify()
	{
		$seq        = $this->input->get('seq', TRUE) ?: '';
		$page       = $this->input->get('page', TRUE) ?: 1;
		$ssdate     = $this->input->get('ssdate', TRUE) ?: '';
		$sedate     = $this->input->get('sedate', TRUE) ?: '';
		$stype      = $this->input->get('stype', TRUE) ?: '';
		$skeyword   = $this->input->get('skeyword', TRUE) ?: '';
		
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
		
		$view = $this->Refunddeposit_model->getView($seq);
		$manager_list = $this->Manager_model->getList(); //담당자
		
		if(count($view) == 0){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/refunddeposit?".$param);
		}
		
		$data = array(
			"seq" => $seq,
			"view" => $view,
			"param" => $param,
			'manager_list' => $manager_list
		);
		
		$this->load->view('refunddeposit/modify', $data);
	}

	function modifyproc()
	{
		$seq        = $this->input->post('seq', TRUE) ?: '';
		$page       = $this->input->get('page', TRUE) ?: 1;
		$ssdate     = $this->input->get('ssdate', TRUE) ?: '';
		$sedate     = $this->input->get('sedate', TRUE) ?: '';
		$stype      = $this->input->get('stype', TRUE) ?: '';
		$skeyword   = $this->input->get('skeyword', TRUE) ?: '';
		
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
		
		$deposit_date = trim($this->input->post('deposit_date', TRUE) ?: '');
		$deposit_amount = trim($this->input->post('deposit_amount', TRUE) ?: '');
		$deposit_amount = str_replace(',', '', $deposit_amount);
		$depositor_name = trim($this->input->post('depositor_name', TRUE) ?: '');
		$depositor_contact = '';
		$depositor_contact1 = trim($this->input->post('depositor_contact1', TRUE) ?: '');
		$depositor_contact2 = trim($this->input->post('depositor_contact2', TRUE) ?: '');
		$depositor_contact3 = trim($this->input->post('depositor_contact3', TRUE) ?: '');
		if ($depositor_contact1 && $depositor_contact2 && $depositor_contact3) {
			$depositor_contact = $depositor_contact1 . '-' . $depositor_contact2 . '-' . $depositor_contact3;
		}
		$remarks = trim($this->input->post('remarks', TRUE) ?: '');
		$manager = trim($this->input->post('manager', TRUE) ?: '');
		
		//유효성 체크
		if($seq == ''){
		    doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/refunddeposit");
		}
		
		$data = array(
		    'deposit_date' => $deposit_date,
		    'deposit_amount' => $deposit_amount,
		    'depositor_name' => $depositor_name,
		    'depositor_contact' => $depositor_contact,
		    'remarks' => $remarks,
		    'manager' => $manager,
		);
		
		$this->Refunddeposit_model->updateList($data, $seq);

		doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/refunddeposit?".$param);

	}
	
	function excel()
	{
        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
        }
        
		$condition  = [];
		$ssdate     = $this->input->get('ssdate', TRUE) ?: '';
		$sedate     = $this->input->get('sedate', TRUE) ?: '';
		$stype      = $this->input->get('stype', TRUE) ?: '';
		$skeyword   = $this->input->get('skeyword', TRUE) ?: '';
		
		if($ssdate) $condition['ssdate'] = $ssdate;
		if($sedate) $condition['sedate'] = $sedate;
		if($stype) $condition['stype'] = $stype;
		if($skeyword) $condition['skeyword'] = $skeyword;
		
		$board_list = $this->Refunddeposit_model->getList($condition);
		
		if(count($board_list) == 0){
			doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
			return true;
		}
		
		$filename = date('Y-m-d').'_반품비입금내역_'.count($board_list).'건';
	
		$this->load->library("PHPExcel");
	
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(60);
	
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1", '번호')
		->setCellValue("B1", '입금날짜')
		->setCellValue("C1", '입금액')
		->setCellValue("D1", '입금자명')
		->setCellValue("E1", '입금자연락처')
		->setCellValue("F1", '비고');
	
		$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFill()->applyFromArray(array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array( 'rgb' => 'f5ebed' )
		));
		$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->applyFromArray(
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
			$deposit_date = $v->deposit_date;
			$deposit_date = date('Y-m-d', strtotime($deposit_date));
			$deposit_amount = $v->deposit_amount;
			$deposit_amount = number_format($deposit_amount);
			$depositor_name = $v->depositor_name;
			$depositor_contact = $v->depositor_contact;
			$remarks = $v->remarks;
              
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".($k+2), $seq)
			->setCellValue("B".($k+2), $deposit_date)
			->setCellValue("C".($k+2), $deposit_amount)
			->setCellValue("D".($k+2), $depositor_name)
			->setCellValue("E".($k+2), $depositor_contact)
			->setCellValue("F".($k+2), $remarks);
	
			$objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":F".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.($k+2))->getFont()->setSize(10);
			
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
