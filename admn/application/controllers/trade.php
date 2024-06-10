<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trade extends CI_Controller
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
		$sselltype     = $this->input->get('sselltype', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$sstock        = $this->input->get('sstock', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$spayment_price = $this->input->get('spayment_price', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
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
		if($splace) $condition['splace'] = $splace;
		if($saccount_conf) $condition['saccount_conf'] = $saccount_conf;
		if($spayment_price) $condition['spayment_price'] = $spayment_price;
		if($sminprice || $smaxprice){
			$condition['smmpricecol'] = $smmpricecol;
			$sminprice = str_replace(',', '', $sminprice);
			$smaxprice = str_replace(',', '', $smaxprice);
			$condition['sminprice'] = $sminprice;
			$condition['smaxprice'] = $smaxprice;
		}
		
		$board_cnt = $this->Trade_model->getListCnt($condition);
		
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
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod."&splace=".$splace."&saccount_conf=".$saccount_conf."&spayment_price=".$spayment_price."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod."&splace=".$splace."&saccount_conf=".$saccount_conf."&spayment_price=".$spayment_price."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$totsum = $this->Trade_model->totalsum($condition);
		$board_list = $this->Trade_model->getList($condition,$scale,$first);
		$brand_list = $this->Brand_model->getList();
		
		$totSelltype = array();
		$temparr = $this->config->item('trade_selltype');
		foreach($temparr as $v){
		    $totSelltype[] = $this->Trade_model->getTotSelltype($condition, $v);
		}
		
		//페이징 html 생성
		$paging_html = '';
		if($scale < $board_cnt)
		{
			$paging_html = '<ul class="pagination pagination-sm">';
			if($block > 1)
			{
				$paging_html .= '<li><a href="/admn/trade?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
			}
		
			for($go_page; $go_page <= $last_page; $go_page++)
			{
			if($page == $go_page)
			{
			$paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
				} else
			{
			$paging_html .= '<li><a href="/admn/trade?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
			}
			}
		
					if($block < $total_block) {
					$paging_html .= '<li><a href="/admn/trade?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			}
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
		    "total_price" => $totsum->total_price ? $totsum->total_price : 0,
		    "total_pprice" => $totsum->total_pprice ? $totsum->total_pprice : 0,
			"total_sellprice" => $totsum->total_sellprice ? $totsum->total_sellprice : 0,
			"total_paymentprice" => $totsum->total_paymentprice ? $totsum->total_paymentprice : 0,
			"param" => $param,
			"brand_list" => $brand_list,
			"sstock" => $sstock,
			"purchase_kind" => $this->config->item('purchase_kind'),
			"trade_paymethod" => $this->config->item('trade_paymethod'),
			"spaymethod" => $spaymethod,
		    "totSelltype" => $totSelltype,
		    "goods_place" => $this->config->item('goods_place'),
		    "splace" => $splace,
		    'saccount_conf' => $saccount_conf,
		    'spayment_price' => $spayment_price,
		    'smmpricecol' => $smmpricecol,
		    'sminprice' => $sminprice,
		    'smaxprice' => $smaxprice,
            'payment_price_1_sum' => $totsum->payment_price_1_sum,
            'payment_price_2_sum' => $totsum->payment_price_2_sum,
            'payment_price_3_sum' => $totsum->payment_price_3_sum,
            'payment_price_4_sum' => $totsum->payment_price_4_sum,
            'payment_price_5_sum' => $totsum->payment_price_5_sum,
            'npay_sum' => $totsum->npay_sum,
		);
		
		$this->load->view('trade/list', $data);
	}

	function delproc()
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
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$spayment_price = $this->input->get('spayment_price', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod."&splace=".$splace."&saccount_conf=".$saccount_conf."&spayment_price=".$spayment_price."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$seq = $this->input->get('seq', TRUE);
		$page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$this->Trade_model->deleteList($seq);
		doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/trade?".$param);
	}
	
	function write()
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
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$spayment_price = $this->input->get('spayment_price', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod."&splace=".$splace."&saccount_conf=".$saccount_conf."&spayment_price=".$spayment_price."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$data = array(
			"page" => $page,
			"trade_selltype" => $this->config->item('trade_selltype'),
			"trade_paymethod" => $this->config->item('trade_paymethod'),
			"required_mark" => $this->config->item('required_mark'),
			"purchase_kind" => $this->config->item('purchase_kind'),
			"purchase_method" => $this->config->item('purchase_method'),
			"purchase_class" => $this->config->item('purchase_class'),
			"param" => $param
		);
		
		$this->load->view('trade/write', $data);
	}

	function writeproc()
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
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$spayment_price = $this->input->get('spayment_price', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod."&splace=".$splace."&saccount_conf=".$saccount_conf."&spayment_price=".$spayment_price."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$purchase_seq = trim($this->input->post('purchase_seq', TRUE));
		$selltype = trim($this->input->post('selltype', TRUE));
		$sellprice = trim($this->input->post('sellprice', TRUE));
		$sellprice = str_replace(',', '', $sellprice);
		$dc = trim($this->input->post('dc', TRUE));
		$buyer = trim($this->input->post('buyer', TRUE));
		$paymethod = trim($this->input->post('paymethod', TRUE)); 
		$note = trim($this->input->post('note', TRUE));
		$sellerinfo = trim($this->input->post('sellerinfo', TRUE));
		$selldate = trim($this->input->post('selldate', TRUE));
        $senddate = trim($this->input->post('senddate', TRUE));
		$buyerphone = '';
		$buyerphone1 = trim($this->input->post('buyerphone1', TRUE));
		$buyerphone2 = trim($this->input->post('buyerphone2', TRUE));
		$buyerphone3 = trim($this->input->post('buyerphone3', TRUE));
		if($buyerphone1 && $buyerphone2 && $buyerphone3){
			$buyerphone = $buyerphone1.'-'.$buyerphone2.'-'.$buyerphone3;
		}
		$amount = trim($this->input->post('amount', TRUE));
		$amount = str_replace(',', '', $amount);
		$paymentprice = trim($this->input->post('paymentprice', TRUE));
		$paymentprice = str_replace(',', '', $paymentprice);
		$account_conf = !empty($this->input->post('account_conf', TRUE)) ? $this->input->post('account_conf', TRUE) : 'N';

		$payment_price_1 = trim($this->input->post('payment_price_1', TRUE));
		$payment_price_1 = str_replace(',', '', $payment_price_1);
		$payment_price_2 = trim($this->input->post('payment_price_2', TRUE));
		$payment_price_2 = str_replace(',', '', $payment_price_2);
		$payment_price_3 = trim($this->input->post('payment_price_3', TRUE));
		$payment_price_3 = str_replace(',', '', $payment_price_3);
		$payment_price_4 = trim($this->input->post('payment_price_4', TRUE));
		$payment_price_4 = str_replace(',', '', $payment_price_4);
		$payment_price_5 = trim($this->input->post('payment_price_5', TRUE));
		$payment_price_5 = str_replace(',', '', $payment_price_5);
        $npay = trim($this->input->post('npay', TRUE));
        $npay = str_replace(',', '', $npay);

		$payment_price_1 = $payment_price_1 ? $payment_price_1 : 0;
		$payment_price_2 = $payment_price_2 ? $payment_price_2 : 0;
		$payment_price_3 = $payment_price_3 ? $payment_price_3 : 0;
		$payment_price_4 = $payment_price_4 ? $payment_price_4 : 0;
		$payment_price_5 = $payment_price_5 ? $payment_price_5 : 0;
        $npay = $npay ? $npay : 0;
		
		$dc = $dc ? $dc : 0;
		$amount = $amount ? $amount : 0;
		
		if($purchase_seq){
			//매입정보
			$purchase_pdate = trim($this->input->post('purchase_pdate', TRUE));
			$purchase_kind = trim($this->input->post('purchase_kind', TRUE));
			$purchase_modelname = trim($this->input->post('purchase_modelname', TRUE));
			$purchase_pprice = trim($this->input->post('purchase_pprice', TRUE));
			$purchase_method = trim($this->input->post('purchase_method', TRUE));
			$purchase_class = trim($this->input->post('purchase_class', TRUE));
            $goods_price = trim($this->input->post('goods_price', TRUE));
            $goods_price = str_replace(',', '', $goods_price);
			if($purchase_pdate || $purchase_kind || $purchase_modelname || $purchase_pprice || $purchase_method || $purchase_class){
				$data = array(
					'pdate' => $purchase_pdate,
					'kind' => $purchase_kind,
					'modelname' => $purchase_modelname,
					'method' => $purchase_method,
					'class' => $purchase_class,
                    'goods_price' => $goods_price,
				);
				if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
					$purchase_pprice = str_replace(',', '', $purchase_pprice);
					$data['pprice'] = $purchase_pprice;
				}
				$this->Purchase_model->updateList($data, $purchase_seq);
			}
			
			//상품정보
			$goods_selfcode = trim($this->input->post('goods_selfcode', TRUE));
			$goods_stock = trim($this->input->post('goods_stock', TRUE));
			if($goods_selfcode){
				$data = array(
					'selfcode' => $goods_selfcode
				);
				if($goods_stock){
					$data = array(
						'selfcode' => $goods_selfcode,
						'stock' => $goods_stock
					);
                    //매입목록도 수정
                    $this->Purchase_model->updatePurchaseGoodsstock($goods_stock,$purchase_seq);
				}
				$this->Goods_model->updateList2($data, $purchase_seq);
			}
		}
		
		//유효성 체크
		if($selltype == '' || $sellprice == ''){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/trade");
		}

		$data = array(
			'purchase_seq' => $purchase_seq,
			'selltype' => $selltype,
			'sellprice' => $sellprice,
			'dc' => $dc,
			'buyer' => $buyer,
			'paymethod' => $paymethod,
			'note' => $note,
			'selldate' => $selldate,
            'senddate' => $senddate,
			'buyerphone' => $buyerphone,
			'amount' => $amount,
			'sellerinfo' => $sellerinfo,
			'paymentprice' => $paymentprice,
			'account_conf' => $account_conf,
			'payment_price_1' => $payment_price_1,
			'payment_price_2' => $payment_price_2,
			'payment_price_3' => $payment_price_3,
			'payment_price_4' => $payment_price_4,
			'payment_price_5' => $payment_price_5,
            'npay' => $npay,
		);

		$this->Trade_model->insertList($data);

		doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/trade?".$param);
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
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$spayment_price = $this->input->get('spayment_price', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod."&splace=".$splace."&saccount_conf=".$saccount_conf."&spayment_price=".$spayment_price."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$seq  = $this->input->get('seq', TRUE);
		$view = $this->Trade_model->getView($seq);
		
		if(count($view) == 0){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/trade?".$param);
		}
		
		$data = array(
			"seq" => $seq,
			"page" => $page,
			"trade_selltype" => $this->config->item('trade_selltype'),
			"trade_paymethod" => $this->config->item('trade_paymethod'),
			"required_mark" => $this->config->item('required_mark'),
			"view" => $view,
			"purchase_kind" => $this->config->item('purchase_kind'),
			"purchase_method" => $this->config->item('purchase_method'),
			"purchase_class" => $this->config->item('purchase_class'),
			"param" => $param
		);
		
		$this->load->view('trade/modify', $data);
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
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$spayment_price = $this->input->get('spayment_price', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod."&splace=".$splace."&saccount_conf=".$saccount_conf."&spayment_price=".$spayment_price."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$seq = $this->input->post('seq', TRUE);
		$purchase_seq = trim($this->input->post('purchase_seq', TRUE));
		$selltype = trim($this->input->post('selltype', TRUE));
		$sellprice = trim($this->input->post('sellprice', TRUE));
		$sellprice = str_replace(',', '', $sellprice);
		$buyer = trim($this->input->post('buyer', TRUE));
		$dc = trim($this->input->post('dc', TRUE));
		$paymethod = trim($this->input->post('paymethod', TRUE)); 
		$note = trim($this->input->post('note', TRUE));
		$sellerinfo = trim($this->input->post('sellerinfo', TRUE));
		$selldate = trim($this->input->post('selldate', TRUE));
        $senddate = trim($this->input->post('senddate', TRUE));
		$buyerphone = '';
		$buyerphone1 = trim($this->input->post('buyerphone1', TRUE));
		$buyerphone2 = trim($this->input->post('buyerphone2', TRUE));
		$buyerphone3 = trim($this->input->post('buyerphone3', TRUE));
		if($buyerphone1 && $buyerphone2 && $buyerphone3){
			$buyerphone = $buyerphone1.'-'.$buyerphone2.'-'.$buyerphone3;
		}
		$amount = trim($this->input->post('amount', TRUE));
		$amount = str_replace(',', '', $amount);
		$paymentprice = trim($this->input->post('paymentprice', TRUE));
		$paymentprice = str_replace(',', '', $paymentprice);
		$account_conf = !empty($this->input->post('account_conf', TRUE)) ? $this->input->post('account_conf', TRUE) : '';

		$payment_price_1 = trim($this->input->post('payment_price_1', TRUE));
		$payment_price_1 = str_replace(',', '', $payment_price_1);
		$payment_price_2 = trim($this->input->post('payment_price_2', TRUE));
		$payment_price_2 = str_replace(',', '', $payment_price_2);
		$payment_price_3 = trim($this->input->post('payment_price_3', TRUE));
		$payment_price_3 = str_replace(',', '', $payment_price_3);
		$payment_price_4 = trim($this->input->post('payment_price_4', TRUE));
		$payment_price_4 = str_replace(',', '', $payment_price_4);
		$payment_price_5 = trim($this->input->post('payment_price_5', TRUE));
		$payment_price_5 = str_replace(',', '', $payment_price_5);
        $npay = trim($this->input->post('npay', TRUE));
        $npay = str_replace(',', '', $npay);

		$payment_price_1 = $payment_price_1 ? $payment_price_1 : 0;
		$payment_price_2 = $payment_price_2 ? $payment_price_2 : 0;
		$payment_price_3 = $payment_price_3 ? $payment_price_3 : 0;
		$payment_price_4 = $payment_price_4 ? $payment_price_4 : 0;
		$payment_price_5 = $payment_price_5 ? $payment_price_5 : 0;
        $npay = $npay ? $npay : 0;
		
		$dc = $dc ? $dc : 0;
		$amount = $amount ? $amount : 0;
		
		//유효성 체크
		if($seq == '' || $selltype == '' || $sellprice == ''){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/trade");
		}
		
		if($purchase_seq){
			//매입정보
			$purchase_pdate = trim($this->input->post('purchase_pdate', TRUE));
			$purchase_kind = trim($this->input->post('purchase_kind', TRUE));
			$purchase_modelname = trim($this->input->post('purchase_modelname', TRUE));
			$purchase_pprice = trim($this->input->post('purchase_pprice', TRUE));
			$purchase_method = trim($this->input->post('purchase_method', TRUE));
			$purchase_class = trim($this->input->post('purchase_class', TRUE));
            $goods_price = trim($this->input->post('goods_price', TRUE));
            $goods_price = str_replace(',', '', $goods_price);
			$data = array(
				'pdate' => $purchase_pdate,
				'kind' => $purchase_kind,
				'modelname' => $purchase_modelname,
				'method' => $purchase_method,
				'class' => $purchase_class,
                'goods_price' => $goods_price
			);
			if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
				$purchase_pprice = str_replace(',', '', $purchase_pprice);
				$data['pprice'] = $purchase_pprice;
			}
			$this->Purchase_model->updateList($data, $purchase_seq);
			
			//상품정보
			$goods_selfcode = trim($this->input->post('goods_selfcode', TRUE));
			$goods_stock = trim($this->input->post('goods_stock', TRUE));
			if($goods_selfcode){
				$data = array(
					'selfcode' => $goods_selfcode
				);
				if($goods_stock){
					$data = array(
						'selfcode' => $goods_selfcode,
						'stock' => $goods_stock
					);
                    //매입목록도 수정
                    $this->Purchase_model->updatePurchaseGoodsstock($goods_stock,$purchase_seq);
				}
				$this->Goods_model->updateList2($data, $purchase_seq);
			}
		}

		$data = array(
			'purchase_seq' => $purchase_seq,
			'selltype' => $selltype,
			'sellprice' => $sellprice,
			'dc' => $dc,
			'buyer' => $buyer,
			'paymethod' => $paymethod,
			'note' => $note,
			'selldate' => $selldate,
            'senddate' => $senddate,
			'buyerphone' => $buyerphone,
			'amount' => $amount,
			'sellerinfo' => $sellerinfo,
			'paymentprice' => $paymentprice,
			'payment_price_1' => $payment_price_1,
			'payment_price_2' => $payment_price_2,
			'payment_price_3' => $payment_price_3,
			'payment_price_4' => $payment_price_4,
			'payment_price_5' => $payment_price_5,
            'npay' => $npay,
		);

		if($account_conf) $data['account_conf'] = $account_conf;
		
		$this->Trade_model->updateList($data, $seq);

		doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/trade?".$param);

	}
	
	function copyproc()
	{
		$seq = $this->input->get('seq', TRUE);
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
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$spayment_price = $this->input->get('spayment_price', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstock=".$sstock."&spaymethod=".$spaymethod."&splace=".$splace."&saccount_conf=".$saccount_conf."&spayment_price=".$spayment_price."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		if($seq){
			$this->Trade_model->copy($seq);
			doMsgLocation('복사 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/trade/?$param");
		}
	}
	
	function excel()
	{
        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
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
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$spayment_price = $this->input->get('spayment_price', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		if($ssdate) $condition['ssdate'] = $ssdate;
		if($sedate) $condition['sedate'] = $sedate;
		if($sselltype) $condition['sselltype'] = $sselltype;
		if($stype) $condition['stype'] = $stype;
		if($skeyword) $condition['skeyword'] = $skeyword;
		if($sbrand) $condition['sbrand'] = $sbrand;
		if($skind) $condition['skind'] = $skind;
		if($sstock) $condition['sstock'] = $sstock;
		if($spaymethod) $condition['spaymethod'] = $spaymethod;
		if($splace) $condition['splace'] = $splace;
		if($saccount_conf) $condition['saccount_conf'] = $saccount_conf;
		if($spayment_price) $condition['spayment_price'] = $spayment_price;
		if($sminprice || $smaxprice){
			$condition['smmpricecol'] = $smmpricecol;
			$sminprice = str_replace(',', '', $sminprice);
			$smaxprice = str_replace(',', '', $smaxprice);
			$condition['sminprice'] = $sminprice;
			$condition['smaxprice'] = $smaxprice;
		}
		
		$board_list = $this->Trade_model->getList($condition);
		
		if(count($board_list) == 0){
			doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
			return true;
		}
		
		$filename = date('Y-m-d').'_판매목록리스트_'.count($board_list).'건';
	
		$this->load->library("PHPExcel");
	
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(18);
	
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1", '번호')
		->setCellValue("B1", '상품코드')
		->setCellValue("C1", '판매구분')
		->setCellValue("D1", '판매일자')
		->setCellValue("E1", '종류')
		->setCellValue("F1", '모델명')
        ->setCellValue("G1", '결제구분')
		->setCellValue("H1", '가격')
		->setCellValue("I1", '결제금액')
		->setCellValue("J1", '정산금액')
		->setCellValue("K1", '결제방법')
		->setCellValue("L1", '구매자성함')
		->setCellValue("M1", '구매자연락처')
        ->setCellValue("N1", 'N페이');
	
		$objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getFill()->applyFromArray(array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array( 'rgb' => 'f5ebed' )
		));
		$objPHPExcel->getActiveSheet()->getStyle("A1:N1")->applyFromArray(
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
			$selltype = $v->selltype;
			$brandname = $v->brandname;
			$pdate = $v->pdate;
			$selldate = $v->selldate;
			$type = $v->type;
			$kind = $v->kind;
			$modelname = $v->modelname;
			$price = $v->price;
			$price = number_format($price);
			$sellprice = $v->sellprice;
			$sellprice = number_format($sellprice);
			$dc = $v->dc;
			$paymethod = $v->paymethod;
			$pprice = $v->pprice;
			$pprice = number_format($pprice);
			$stock = ($selldate=='') ? '있음' : '없음';
			$buyer = $v->buyer;
			$buyerphone = $v->buyerphone;
			$paymentprice = number_format($v->paymentprice); //결제금액

			
			//admin의 경우에만 매입거래가격 보임
			if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
				$pprice = '';
			}

            $gubun = '';
            if(!empty($v->payment_price_1)){
                if($gubun != '') $gubun = $gubun.',';
                $gubun .= '현금';
            }
            if(!empty($v->payment_price_2)){
                if($gubun != '') $gubun = $gubun.',';
                $gubun .= '무통장입금';
            }
            if(!empty($v->payment_price_3)){
                if($gubun != '') $gubun = $gubun.',';
                $gubun .= '카드단말기';
            }
            if(!empty($v->payment_price_4)){
                if($gubun != '') $gubun = $gubun.',';
                $gubun .= '온라인카드';
            }
            if(!empty($v->payment_price_5)){
                if($gubun != '') $gubun = $gubun.',';
                $gubun .= '기타';
            }

            $npay = $v->npay;
              
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".($k+2), $seq)
			->setCellValue("B".($k+2), $pcode)
			->setCellValue("C".($k+2), $selltype)
			->setCellValue("D".($k+2), $selldate)
			->setCellValue("E".($k+2), $kind)
			->setCellValue("F".($k+2), $modelname)
            ->setCellValue("G".($k+2), $gubun)
			->setCellValue("H".($k+2), $price)
			->setCellValue("I".($k+2), $paymentprice)
			->setCellValue("J".($k+2), $sellprice)
			->setCellValue("K".($k+2), $paymethod)
			->setCellValue("L".($k+2), $buyer)
            ->setCellValue("M".($k+2), $buyerphone)
            ->setCellValue("N".($k+2), $npay);
	
			$objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":N".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	
		$objPHPExcel->getActiveSheet()->getStyle('A1:N'.($k+2))->getFont()->setSize(10);
			
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
			$info = $this->Purchase_model->getInfoFromCode2($pcode);
			
			$result = array();
			//거래테이블에 해당 코드로 이미 등록된것이 있는지 확인
			$purchase_seq = isset($info->seq) ? $info->seq : '';
			if($purchase_seq){
				$trade_info = $this->Trade_model->getSeqTrade($purchase_seq);
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
