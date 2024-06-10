<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockcheck extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Trade_model');
		$this->load->model('Purchase_model');
		$this->load->model('Login_model');
		$this->load->model('Goods_model');
		$this->load->model('Stockcheck_model');
		$this->load->model('Brand_model');
		$this->load->model('Refund_model');
		
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
		if($this->session->userdata('ADM_AUTH') == '3'){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn");
		}

	    $condition = array();
	    $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
	    $ssdate        = $this->input->get('ssdate', TRUE);
	    $sedate        = $this->input->get('sedate', TRUE);
	    $sselltype     = $this->input->get('sselltype', TRUE);
	    $stype         = $this->input->get('stype', TRUE);
	    $skeyword      = $this->input->get('skeyword', TRUE);
	    $sbrand        = $this->input->get('sbrand', TRUE);
	    $skind         = $this->input->get('skind', TRUE);
	    $sstock        = $this->input->get('sstock', TRUE);
	    $spaymethod    = $this->input->get('spaymethod', TRUE);
	    $scale         = 20;
	    
	    if($ssdate) $condition['ssdate'] = $ssdate;
	    if($sedate) $condition['sedate'] = $sedate;
	    if($sselltype) $condition['sselltype'] = $sselltype;
	    if($stype) $condition['stype'] = $stype;
	    if($skeyword) $condition['skeyword'] = $skeyword;
	    if($sbrand) $condition['sbrand'] = $sbrand;
	    if($skind) $condition['skind'] = $skind;
	    if($sstock) $condition['sstock'] = $sstock;
	    if($spaymethod) $condition['spaymethod'] = $spaymethod;
		
	    $board_cnt = $this->Stockcheck_model->getListCnt($condition);
		$brand_list = $this->Brand_model->getList();
		
		$total_page  = 0;
		if($board_cnt > 0) $total_page = ceil($board_cnt/$scale);
		$first       = $scale * ($page - 1);
		$total_block = ceil($total_page / 15);
		$block       = ceil($page / 15);
		$first_page  = ($block - 1) * 15;
		$last_page   = $total_block <= $block ? $total_page : $block * 15;
		$go_page     = $first_page + 1;
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod;
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod;
		
		$board_list = $this->Stockcheck_model->getList($condition,$scale,$first);
		$totsum = $this->Stockcheck_model->totalsum($condition);
		
		//페이징 html 생성
		$paging_html = '';
		if($scale < $board_cnt){
			$paging_html = '<ul class="pagination pagination-sm">';
			if($block > 1) $paging_html .= '<li><a href="/admn/stockcheck?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
			for($go_page; $go_page <= $last_page; $go_page++){
                if($page == $go_page){
                    $paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
				}else{
                    $paging_html .= '<li><a href="/admn/stockcheck?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
                }
            }
            if($block < $total_block) $paging_html .= '<li><a href="/admn/stockcheck?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			$paging_html .= '</ul>';
		}
		
		$data = array(
		    "ssdate" => $ssdate,
		    "sedate" => $sedate,
		    "sselltype" => $sselltype,
		    "stype" => $stype,
		    "skeyword" => $skeyword,
		    "skind" => $skind,
		    "sbrand" => $sbrand,
		    "scale" => $scale,
		    "page" => $page,
		    "board_list" => $board_list,
		    "paging_html" => $paging_html,
		    "trade_selltype" => $this->config->item('trade_selltype'),
		    "total_cnt" => $board_cnt,
		    "param" => $param,
		    "brand_list" => $brand_list,
		    "sstock" => $sstock,
		    "purchase_kind" => $this->config->item('purchase_kind'),
		    "trade_paymethod" => $this->config->item('trade_paymethod'),
		    "spaymethod" => $spaymethod,
		    "totsum" => $totsum
		);
		
		$this->load->view('stockcheck/list', $data);
	}

	function modify()
	{
	    $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
	    $ssdate        = $this->input->get('ssdate', TRUE);
	    $sedate        = $this->input->get('sedate', TRUE);
	    $sselltype     = $this->input->get('sselltype', TRUE);
	    $stype         = $this->input->get('stype', TRUE);
	    $skeyword      = $this->input->get('skeyword', TRUE);
	    $sbrand        = $this->input->get('sbrand', TRUE);
	    $skind         = $this->input->get('skind', TRUE);
	    $sstock        = $this->input->get('sstock', TRUE);
	    $spaymethod    = $this->input->get('spaymethod', TRUE);
		
	    $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod;
		
		$seq  = $this->input->get('seq', TRUE);
		$view = $this->Stockcheck_model->getView($seq);
		
		if(count($view) == 0){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/stockcheck?".$param);
		}
		
		$data = array(
			"seq" => $seq,
			"page" => $page,
			"view" => $view,
		    "param" => $param,
		    "purchase_type" => $this->config->item('purchase_type')
		);
		
		$this->load->view('stockcheck/modify', $data);
	}

	function modifyproc()
	{
	    $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
	    $ssdate        = $this->input->get('ssdate', TRUE);
	    $sedate        = $this->input->get('sedate', TRUE);
	    $sselltype     = $this->input->get('sselltype', TRUE);
	    $stype         = $this->input->get('stype', TRUE);
	    $skeyword      = $this->input->get('skeyword', TRUE);
	    $sbrand        = $this->input->get('sbrand', TRUE);
	    $skind         = $this->input->get('skind', TRUE);
	    $sstock        = $this->input->get('sstock', TRUE);
	    $spaymethod    = $this->input->get('spaymethod', TRUE);
	    
	    $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod;
		
		$seq = $this->input->post('seq', TRUE);
		$purchase_seq = trim($this->input->post('purchase_seq', TRUE));
		$purchase_seller = trim($this->input->post('purchase_seller', TRUE));
		$purchase_modelname = trim($this->input->post('purchase_modelname', TRUE));
		$purchase_pprice = trim($this->input->post('purchase_pprice', TRUE));
		$purchase_pprice = str_replace(',', '', $purchase_pprice);
		$purchase_pdate = trim($this->input->post('purchase_pdate', TRUE));
		$goods_price = trim($this->input->post('goods_price', TRUE));
		$goods_price = str_replace(',', '', $goods_price);
		$trade_buyer = trim($this->input->post('trade_buyer', TRUE));
		$buyerphone = '';
		$buyerphone1 = trim($this->input->post('trade_buyerphone1', TRUE));
		$buyerphone2 = trim($this->input->post('trade_buyerphone2', TRUE));
		$buyerphone3 = trim($this->input->post('trade_buyerphone3', TRUE));
		if($buyerphone1 && $buyerphone2 && $buyerphone3){
		    $buyerphone = $buyerphone1.'-'.$buyerphone2.'-'.$buyerphone3;
		}
		$trade_sellprice = trim($this->input->post('trade_sellprice', TRUE));
		$trade_sellprice = str_replace(',', '', $trade_sellprice);
		$goods_stock = trim($this->input->post('goods_stock', TRUE));
		$purchase_type = trim($this->input->post('purchase_type', TRUE));
		$note = trim($this->input->post('note', TRUE));
		
		if($purchase_seq){
		    $data = array(
		        'seller' => $purchase_seller,
		        'modelname' => $purchase_modelname,
		        'pdate' => $purchase_pdate,
		        'type' => $purchase_type
		    );
		    if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){
		        $data['pprice'] = $purchase_pprice;
		    }
		    $this->Purchase_model->updateList($data, $purchase_seq);
		    
		    $data = array(
		        'price' => $goods_price,
		        'stock' => $goods_stock
		    );
		    $this->Goods_model->updateList2($data, $purchase_seq);
		    
		    $data = array(
		        'buyer' => $trade_buyer,
		        'buyerphone' => $buyerphone,
		        'sellprice' => $trade_sellprice
		    );
		    $this->Trade_model->updateList2($data, $purchase_seq);
		   
		    $this->Stockcheck_model->noteupdate($note, $purchase_seq);
		}

		doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/stockcheck/?$param");

	}
	
	function excel()
	{
        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
        }
        
	    ini_set("memory_limit","512M");
		$condition = array();
		$ssdate        = $this->input->get('ssdate', TRUE);
		$sedate        = $this->input->get('sedate', TRUE);
		$sselltype     = $this->input->get('sselltype', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$sstock        = $this->input->get('sstock', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
		
		if($ssdate) $condition['ssdate'] = $ssdate;
		if($sedate) $condition['sedate'] = $sedate;
		if($sselltype) $condition['sselltype'] = $sselltype;
		if($stype) $condition['stype'] = $stype;
		if($skeyword) $condition['skeyword'] = $skeyword;
		if($sbrand) $condition['sbrand'] = $sbrand;
		if($skind) $condition['skind'] = $skind;
		if($sstock) $condition['sstock'] = $sstock;
		if($spaymethod) $condition['spaymethod'] = $spaymethod;
		
		$board_list = $this->Stockcheck_model->getList($condition);
		
		if(count($board_list) == 0){
			doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
			return true;
		}
		
		$filename = date('Y-m-d').'_재고검수목록리스트_'.count($board_list).'건';
	
		$this->load->library("PHPExcel");
	
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(70);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(18);
	
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1", '거래번호')
		->setCellValue("B1", '상품코드')
		->setCellValue("C1", '판매자')
        ->setCellValue("D1", '구분')
		->setCellValue("E1", '모델명')
		->setCellValue("F1", '매입거래가격')
		->setCellValue("G1", '매입일자')
		->setCellValue("H1", '등록일자')
		->setCellValue("I1", '판매예정금액')
		->setCellValue("J1", '구매자성함')
		->setCellValue("K1", '구매자연락처')
		->setCellValue("L1", '정산금액')
		->setCellValue("M1", '재고');
	
		$objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFill()->applyFromArray(array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array( 'rgb' => 'f5ebed' )
		));
		$objPHPExcel->getActiveSheet()->getStyle("A1:L1")->applyFromArray(
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
		    $purchase_seq = $v->purchase_seq;
		    $trade_seq = $v->trade_seq;
		    $pcode = $v->pcode;
		    $seller = $v->seller;
		    $modelname = $v->modelname;
		    $pprice = $v->pprice;
		    $pdate = $v->pdate;
		    if(strpos($pdate, '0000-00') === false){
		        $pdate_arr = explode(' ', $pdate);
		        $pdate = $pdate_arr[0];
		    }
		    $rdate = $v->rdate;
		    if(strpos($rdate, '0000-00') !== false){
		        $rdate = '';
		    }
		    $price = $v->price;
		    $buyer = $v->buyer;
		    $buyerphone = $v->buyerphone;
		    $sellprice = $v->sellprice;
		    $stock = $v->stock;
            $type = $v->type;
			
			//admin의 경우에만 매입거래가격 보임
		    if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
				$pprice = '';
			}else{
			    $pprice = number_format($pprice);
			}
			$stock = $stock=='Y' ? '있음' : '없음';
              
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".($k+2), $purchase_seq)
			->setCellValue("B".($k+2), $pcode)
			->setCellValue("C".($k+2), $seller)
            ->setCellValue("D".($k+2), $type)
			->setCellValue("E".($k+2), $modelname)
			->setCellValue("F".($k+2), $pprice)
			->setCellValue("G".($k+2), $pdate)
			->setCellValue("H".($k+2), $rdate)
			->setCellValue("I".($k+2), number_format($price))
			->setCellValue("J".($k+2), $buyer)
			->setCellValue("K".($k+2), $buyerphone)
			->setCellValue("L".($k+2), $sellprice)
			->setCellValue("M".($k+2), $stock);
	
			$objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":D".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle("F".($k+2).":L".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
		$seq = $this->input->post('seq', TRUE);
		
		if($pcode){
		    $info = $this->Stockcheck_model->getInfoFromCode($pcode);
			
			$result = array();
			//거래테이블에 해당 코드로 이미 등록된것이 있는지 확인
			$purchase_seq = isset($info->purchase_seq) ? $info->purchase_seq : '';
			if($purchase_seq){
			    $trade_info = $this->Stockcheck_model->getSeqStockcheck($purchase_seq);
				$trade_seq = isset($trade_info->seq) ? $trade_info->seq : '';
				if($trade_seq == '' || $seq == $trade_seq){
					$result = array('result' => 'ok', 'data' => $info);
				}else{
					//이미 등록된 데이터가 있는 경우
					$result = array('result' => 'already', 'data' => $trade_seq);
				}
			}else{
				//해당코드로 인해 조회가 안되는 경우
				$result = array('result' => 'notfound');
			}
			
			echo json_encode($result);
		}
	}
	
}
