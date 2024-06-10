<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund extends CI_Controller
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
		$this->load->model('Refund_model');
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
		$condition      = array();
		$page           = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate         = $this->input->get('ssdate', TRUE);
		$sedate         = $this->input->get('sedate', TRUE);
		$sselltype      = $this->input->get('sselltype', TRUE);
		$sprocess       = $this->input->get('sprocess', TRUE);
		$spaymethod     = $this->input->get('spaymethod', TRUE);
		$sbrand         = $this->input->get('sbrand', TRUE);
		$skind          = $this->input->get('skind', TRUE);
		$ssapplydate    = $this->input->get('ssapplydate', TRUE);
		$seapplydate    = $this->input->get('seapplydate', TRUE);
		$sscompletedate = $this->input->get('sscompletedate', TRUE);
		$secompletedate = $this->input->get('secompletedate', TRUE);		
		$stype          = $this->input->get('stype', TRUE);
		$skeyword       = $this->input->get('skeyword', TRUE);
		$saccount_conf  = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
        $splace        = $this->input->get('splace', TRUE);
		$scale          = 20;
		
        if($splace) $condition['splace'] = $splace;
		if($ssdate) $condition['ssdate'] = $ssdate;
		if($sedate) $condition['sedate'] = $sedate;
		if($sselltype) $condition['sselltype'] = $sselltype;
		if($sprocess) $condition['sprocess'] = $sprocess;
		if($spaymethod) $condition['spaymethod'] = $spaymethod;
		if($sbrand) $condition['sbrand'] = $sbrand;
		if($skind) $condition['skind'] = $skind;
		if($ssapplydate) $condition['ssapplydate'] = $ssapplydate;
		if($seapplydate) $condition['seapplydate'] = $seapplydate;
		if($sscompletedate) $condition['sscompletedate'] = $sscompletedate;
		if($secompletedate) $condition['secompletedate'] = $secompletedate;
		if($stype) $condition['stype'] = $stype;
		if($skeyword) $condition['skeyword'] = $skeyword;
		if($saccount_conf) $condition['saccount_conf'] = $saccount_conf;
		if($sminprice || $smaxprice){
			$condition['smmpricecol'] = $smmpricecol;
			$sminprice = str_replace(',', '', $sminprice);
			$smaxprice = str_replace(',', '', $smaxprice);
			$condition['sminprice'] = $sminprice;
			$condition['smaxprice'] = $smaxprice;
		}
		
		$board_cnt = $this->Refund_model->getListCnt($condition);
		
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
		
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sprocess=".$sprocess."&spaymethod=".$spaymethod."&sbrand=".$sbrand."&skind=".$skind."&ssapplydate=".$ssapplydate."&seapplydate=".$seapplydate."&sscompletedate=".$sscompletedate."&secompletedate=".$secompletedate."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice."&splace=".$splace;
        $param = "page=".$page.$param2;
		
		$totalsum_a = $this->Refund_model->totalsum_a($condition); //총반품금액
		$totalsum_b = $this->Refund_model->totalsum_b($condition); //총환불금액
		$totalsum_c = $this->Refund_model->totalsum_c($condition); //총거부금액
		$totalsum_d = $this->Refund_model->totalsum_d($condition); //총결제금액
		$board_list = $this->Refund_model->getList($condition,$scale,$first);
		$brand_list = $this->Brand_model->getList();

		//페이징 html 생성
		$paging_html = '';
		if($scale < $board_cnt)
		{
			$paging_html = '<ul class="pagination pagination-sm">';
			if($block > 1) $paging_html .= '<li><a href="/admn/refund?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		
			for($go_page; $go_page <= $last_page; $go_page++){
				if($page == $go_page){
					$paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
				}else{
					$paging_html .= '<li><a href="/admn/refund?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
				}
			}
		
			if($block < $total_block) $paging_html .= '<li><a href="/admn/refund?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			$paging_html .= '</ul>';
		}
		
		$data = array(
			"ssdate" => $ssdate,
			"sedate" => $sedate,
			"trade_selltype" => $this->config->item('trade_selltype'),
			"purchase_kind" => $this->config->item('purchase_kind'),
			"trade_paymethod" => $this->config->item('trade_paymethod'),
			"brand_list" => $brand_list,
			"sselltype" => $sselltype,
			"stype" => $stype,
			"skeyword" => $skeyword,
			"sbrand" => $sbrand,
			"skind" => $skind,
			"ssapplydate" => $ssapplydate,
			"seapplydate" => $seapplydate,
			"sscompletedate" => $sscompletedate,
			"secompletedate" => $secompletedate,
			"sprocess" => $sprocess,
			"spaymethod" => $spaymethod,
			"scale" => $scale,
			"page" => $page,
			"board_list" => $board_list,
			"paging_html" => $paging_html,
			"total_cnt" => $board_cnt,
			"totalsum_a" => $totalsum_a->totalsum_a ? $totalsum_a->totalsum_a : 0, //총반품금액
			"totalsum_b" => $totalsum_b->totalsum_b ? $totalsum_b->totalsum_b : 0, //총환불금액
			"totalsum_c" => $totalsum_c->totalsum_c ? $totalsum_c->totalsum_c : 0, //총거부금액
			"totalsum_d" => $totalsum_d->totalsum_d ? $totalsum_d->totalsum_d : 0, //총결제금액
			"param" => $param,
		    'saccount_conf' => $saccount_conf,
		    'smmpricecol' => $smmpricecol,
		    'sminprice' => $sminprice,
		    'smaxprice' => $smaxprice,
            'splace' => $splace,
            "goods_place" => $this->config->item('goods_place'),
		);
		
		$this->load->view('refund/list', $data);
	}

	function delproc()
	{	
		$seq            = $this->input->get('seq', TRUE);
		$page           = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate         = $this->input->get('ssdate', TRUE);
		$sedate         = $this->input->get('sedate', TRUE);
		$sselltype      = $this->input->get('sselltype', TRUE);
		$sprocess       = $this->input->get('sprocess', TRUE);
		$spaymethod     = $this->input->get('spaymethod', TRUE);
		$sbrand         = $this->input->get('sbrand', TRUE);
		$skind          = $this->input->get('skind', TRUE);
		$ssapplydate    = $this->input->get('ssapplydate', TRUE);
		$seapplydate    = $this->input->get('seapplydate', TRUE);
		$sscompletedate = $this->input->get('sscompletedate', TRUE);
		$secompletedate = $this->input->get('secompletedate', TRUE);		
		$stype          = $this->input->get('stype', TRUE);
		$skeyword       = $this->input->get('skeyword', TRUE);
		$saccount_conf  = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
        $splace        = $this->input->get('splace', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sprocess=".$sprocess."&spaymethod=".$spaymethod."&sbrand=".$sbrand."&skind=".$skind."&ssapplydate=".$ssapplydate."&seapplydate=".$seapplydate."&sscompletedate=".$sscompletedate."&secompletedate=".$secompletedate."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice."&splace=".$splace;
		
		$this->Refund_model->deleteList($seq);
		doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/refund?".$param);
	}
	
	function write()
	{
		$page           = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate         = $this->input->get('ssdate', TRUE);
		$sedate         = $this->input->get('sedate', TRUE);
		$sselltype      = $this->input->get('sselltype', TRUE);
		$sprocess       = $this->input->get('sprocess', TRUE);
		$spaymethod     = $this->input->get('spaymethod', TRUE);
		$sbrand         = $this->input->get('sbrand', TRUE);
		$skind          = $this->input->get('skind', TRUE);
		$ssapplydate    = $this->input->get('ssapplydate', TRUE);
		$seapplydate    = $this->input->get('seapplydate', TRUE);
		$sscompletedate = $this->input->get('sscompletedate', TRUE);
		$secompletedate = $this->input->get('secompletedate', TRUE);		
		$stype          = $this->input->get('stype', TRUE);
		$skeyword       = $this->input->get('skeyword', TRUE);
		$saccount_conf  = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
        $splace        = $this->input->get('splace', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sprocess=".$sprocess."&spaymethod=".$spaymethod."&sbrand=".$sbrand."&skind=".$skind."&ssapplydate=".$ssapplydate."&seapplydate=".$seapplydate."&sscompletedate=".$sscompletedate."&secompletedate=".$secompletedate."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice."&splace=".$splace;
		
		$data = array(
			"page" => $page,
			"ssdate" => $ssdate,
			"sedate" => $sedate,	
			"sselltype" => $sselltype,
			"sprocess" => $sprocess,
			"spaymethod" => $spaymethod,
			"sbrand" => $sbrand,
			"skind" => $skind,
			"ssapplydate" => $ssapplydate,
			"seapplydate" => $seapplydate,
			"sscompletedate" => $sscompletedate,
			"secompletedate" => $secompletedate,
			"stype" => $stype,
			"skeyword" => $skeyword,
			"param" => $param
		);
		
		$this->load->view('refund/write', $data);
	}

	function writeproc()
	{
		$page           = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate         = $this->input->get('ssdate', TRUE);
		$sedate         = $this->input->get('sedate', TRUE);
		$sselltype      = $this->input->get('sselltype', TRUE);
		$sprocess       = $this->input->get('sprocess', TRUE);
		$spaymethod     = $this->input->get('spaymethod', TRUE);
		$sbrand         = $this->input->get('sbrand', TRUE);
		$skind          = $this->input->get('skind', TRUE);
		$ssapplydate    = $this->input->get('ssapplydate', TRUE);
		$seapplydate    = $this->input->get('seapplydate', TRUE);
		$sscompletedate = $this->input->get('sscompletedate', TRUE);
		$secompletedate = $this->input->get('secompletedate', TRUE);		
		$stype          = $this->input->get('stype', TRUE);
		$skeyword       = $this->input->get('skeyword', TRUE);
		$saccount_conf  = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
        $splace        = $this->input->get('splace', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sprocess=".$sprocess."&spaymethod=".$spaymethod."&sbrand=".$sbrand."&skind=".$skind."&ssapplydate=".$ssapplydate."&seapplydate=".$seapplydate."&sscompletedate=".$sscompletedate."&secompletedate=".$secompletedate."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice."&splace=".$splace;
		
		$purchase_seq = trim($this->input->post('purchase_seq', TRUE));
		$thumb = trim($this->input->post('thumb', TRUE));
		$modelname = trim($this->input->post('modelname', TRUE));
		$modelname = urldecode($modelname);
		$selltype = trim($this->input->post('selltype', TRUE));
		$paymethod = trim($this->input->post('paymethod', TRUE));
		$brand_seq = trim($this->input->post('brand_seq', TRUE));
		$kind = trim($this->input->post('kind', TRUE));
		$amount = trim($this->input->post('amount', TRUE));
		$price = trim($this->input->post('price', TRUE));
        if(empty($price)) $price = 0;
		$pcode = trim($this->input->post('pcode', TRUE));
		$selldate = trim($this->input->post('selldate', TRUE));
		$buyer = trim($this->input->post('buyer', TRUE));
		$buyerphone = '';
		$buyerphone1 = trim($this->input->post('buyerphone1', TRUE));
		$buyerphone2 = trim($this->input->post('buyerphone2', TRUE));
		$buyerphone3 = trim($this->input->post('buyerphone3', TRUE));
		if($buyerphone1 && $buyerphone2 && $buyerphone3){
		    $buyerphone = $buyerphone1.'-'.$buyerphone2.'-'.$buyerphone3;
		}
		$applydate = trim($this->input->post('applydate', TRUE));
		$completedate = trim($this->input->post('completedate', TRUE));
		$process = trim($this->input->post('process', TRUE));
		$reason = trim($this->input->post('reason', TRUE));
		$note = trim($this->input->post('note', TRUE));
        $paymentprice = trim($this->input->post('paymentprice', TRUE));
        if(empty($paymentprice)) $paymentprice = 0;
		$account_conf = !empty($this->input->post('account_conf', TRUE)) ? $this->input->post('account_conf', TRUE) : 'N';
		
		//유효성 체크
		if($purchase_seq == ''){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/refund");
		}
		
		//상품재고처리
		if(in_array($process, array('Y', 'N'))){
		    $this->Refund_model->stockupdate($process, $purchase_seq);
		}

		$data = array(
		    'thumb' => $thumb,
		    'modelname' => $modelname,
		    'selltype' => $selltype,
		    'paymethod' => $paymethod,
		    'brand_seq' => $brand_seq,
		    'kind' => $kind,
		    'amount' => $amount,
		    'price' => $price,
		    'pcode' => $pcode,
		    'selldate' => $selldate,
		    'buyer' => $buyer,
		    'buyerphone' => $buyerphone,
		    'applydate' => $applydate,
		    'completedate' => $completedate,
		    'process' => $process,
		    'reason' => $reason,
		    'note' => $note,
			'account_conf' => $account_conf,
            'paymentprice' => $paymentprice
		);

		$this->Refund_model->insertList($data);

		doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/refund?".$param);
	}

	function modify()
	{
		$page           = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate         = $this->input->get('ssdate', TRUE);
		$sedate         = $this->input->get('sedate', TRUE);
		$sselltype      = $this->input->get('sselltype', TRUE);
		$sprocess       = $this->input->get('sprocess', TRUE);
		$spaymethod     = $this->input->get('spaymethod', TRUE);
		$sbrand         = $this->input->get('sbrand', TRUE);
		$skind          = $this->input->get('skind', TRUE);
		$ssapplydate    = $this->input->get('ssapplydate', TRUE);
		$seapplydate    = $this->input->get('seapplydate', TRUE);
		$sscompletedate = $this->input->get('sscompletedate', TRUE);
		$secompletedate = $this->input->get('secompletedate', TRUE);		
		$stype          = $this->input->get('stype', TRUE);
		$skeyword       = $this->input->get('skeyword', TRUE);
		$saccount_conf  = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
        $splace        = $this->input->get('splace', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sprocess=".$sprocess."&spaymethod=".$spaymethod."&sbrand=".$sbrand."&skind=".$skind."&ssapplydate=".$ssapplydate."&seapplydate=".$seapplydate."&sscompletedate=".$sscompletedate."&secompletedate=".$secompletedate."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice."&splace=".$splace;
		
		$seq  = $this->input->get('seq', TRUE);
		$view = $this->Refund_model->getView($seq);
		
		if(count($view) == 0){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/refund?".$param);
		}
		
		$data = array(
			"seq" => $seq,
			"view" => $view,
			"param" => $param
		);
		
		$this->load->view('refund/modify', $data);
	}

	function modifyproc()
	{
		$page           = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate         = $this->input->get('ssdate', TRUE);
		$sedate         = $this->input->get('sedate', TRUE);
		$sselltype      = $this->input->get('sselltype', TRUE);
		$sprocess       = $this->input->get('sprocess', TRUE);
		$spaymethod     = $this->input->get('spaymethod', TRUE);
		$sbrand         = $this->input->get('sbrand', TRUE);
		$skind          = $this->input->get('skind', TRUE);
		$ssapplydate    = $this->input->get('ssapplydate', TRUE);
		$seapplydate    = $this->input->get('seapplydate', TRUE);
		$sscompletedate = $this->input->get('sscompletedate', TRUE);
		$secompletedate = $this->input->get('secompletedate', TRUE);		
		$stype          = $this->input->get('stype', TRUE);
		$skeyword       = $this->input->get('skeyword', TRUE);
		$saccount_conf  = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
        $splace        = $this->input->get('splace', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sselltype=".$sselltype."&stype=".$stype."&skeyword=".$skeyword."&sprocess=".$sprocess."&spaymethod=".$spaymethod."&sbrand=".$sbrand."&skind=".$skind."&ssapplydate=".$ssapplydate."&seapplydate=".$seapplydate."&sscompletedate=".$sscompletedate."&secompletedate=".$secompletedate."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice."&splace=".$splace;
		
		$seq = $this->input->post('seq', TRUE);
		$selldate = trim($this->input->post('selldate', TRUE));
		$buyer = trim($this->input->post('buyer', TRUE));
		$buyerphone = '';
		$buyerphone1 = trim($this->input->post('buyerphone1', TRUE));
		$buyerphone2 = trim($this->input->post('buyerphone2', TRUE));
		$buyerphone3 = trim($this->input->post('buyerphone3', TRUE));
		if($buyerphone1 && $buyerphone2 && $buyerphone3){
		    $buyerphone = $buyerphone1.'-'.$buyerphone2.'-'.$buyerphone3;
		}
		$applydate = trim($this->input->post('applydate', TRUE));
		$completedate = trim($this->input->post('completedate', TRUE));
		$process = trim($this->input->post('process', TRUE));
		$reason = trim($this->input->post('reason', TRUE));
		$note = trim($this->input->post('note', TRUE));
		$account_conf = !empty($this->input->post('account_conf', TRUE)) ? $this->input->post('account_conf', TRUE) : '';
		
		//유효성 체크
		if($seq == ''){
		    doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/refund");
		}
		
		$data = array(
		    'selldate' => $selldate,
		    'buyer' => $buyer,
		    'buyerphone' => $buyerphone,
		    'applydate' => $applydate,
		    'completedate' => $completedate,
		    'process' => $process,
		    'reason' => $reason,
		    'note' => $note
		);

		if($account_conf) $data['account_conf'] = $account_conf;
		
		$this->Refund_model->updateList($data, $seq);

		doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/refund?".$param);

	}
	
	function excel()
	{
        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
        }
        
		$condition = array();
		$page           = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate         = $this->input->get('ssdate', TRUE);
		$sedate         = $this->input->get('sedate', TRUE);
		$sselltype      = $this->input->get('sselltype', TRUE);
		$sprocess       = $this->input->get('sprocess', TRUE);
		$spaymethod     = $this->input->get('spaymethod', TRUE);
		$sbrand         = $this->input->get('sbrand', TRUE);
		$skind          = $this->input->get('skind', TRUE);
		$ssapplydate    = $this->input->get('ssapplydate', TRUE);
		$seapplydate    = $this->input->get('seapplydate', TRUE);
		$sscompletedate = $this->input->get('sscompletedate', TRUE);
		$secompletedate = $this->input->get('secompletedate', TRUE);		
		$stype          = $this->input->get('stype', TRUE);
		$skeyword       = $this->input->get('skeyword', TRUE);
		$saccount_conf  = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
        $splace        = $this->input->get('splace', TRUE);
		
        if($splace) $condition['splace'] = $splace;
		if($ssdate) $condition['ssdate'] = $ssdate;
		if($sedate) $condition['sedate'] = $sedate;
		if($sselltype) $condition['sselltype'] = $sselltype;
		if($sprocess) $condition['sprocess'] = $sprocess;
		if($spaymethod) $condition['spaymethod'] = $spaymethod;
		if($sbrand) $condition['sbrand'] = $sbrand;
		if($skind) $condition['skind'] = $skind;
		if($ssapplydate) $condition['ssapplydate'] = $ssapplydate;
		if($seapplydate) $condition['seapplydate'] = $seapplydate;
		if($sscompletedate) $condition['sscompletedate'] = $sscompletedate;
		if($secompletedate) $condition['secompletedate'] = $secompletedate;
		if($stype) $condition['stype'] = $stype;
		if($skeyword) $condition['skeyword'] = $skeyword;
		if($saccount_conf) $condition['saccount_conf'] = $saccount_conf;
		if($sminprice || $smaxprice){
			$condition['smmpricecol'] = $smmpricecol;
			$sminprice = str_replace(',', '', $sminprice);
			$smaxprice = str_replace(',', '', $smaxprice);
			$condition['sminprice'] = $sminprice;
			$condition['smaxprice'] = $smaxprice;
		}
		
		$board_list = $this->Refund_model->getList($condition);
		
		if(count($board_list) == 0){
			doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
			return true;
		}
		
		$filename = date('Y-m-d').'_반품목록리스트_'.count($board_list).'건';
	
		$this->load->library("PHPExcel");
	
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(60);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
	
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1", '번호')
		->setCellValue("B1", '상품코드')
		->setCellValue("C1", '판매일자')
		->setCellValue("D1", '신청일자')
		->setCellValue("E1", '반품완료일')
		->setCellValue("F1", '모델명')
		->setCellValue("G1", '구매자')
		->setCellValue("H1", '구매자연락처')
		->setCellValue("I1", '처리결과');
	
		$objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFill()->applyFromArray(array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array( 'rgb' => 'f5ebed' )
		));
		$objPHPExcel->getActiveSheet()->getStyle("A1:I1")->applyFromArray(
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
            $selldate = $v->selldate;
            $applydate = $v->applydate;
            $completedate = $v->completedate;
            $applydate_arr = explode(' ', $applydate);
            $completedate_arr = explode(' ', $completedate);
            $modelname = $v->modelname;
            $buyer = $v->buyer;
            $buyerphone = $v->buyerphone;
            $process = $v->process;
            $process = ($process=='Y') ? '승인' : '거부';
              
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".($k+2), $seq)
			->setCellValue("B".($k+2), $pcode)
			->setCellValue("C".($k+2), $selldate)
			->setCellValue("D".($k+2), $applydate_arr[0])
			->setCellValue("E".($k+2), $completedate_arr[0])
			->setCellValue("F".($k+2), $modelname)
			->setCellValue("G".($k+2), $buyer)
			->setCellValue("H".($k+2), $buyerphone)
			->setCellValue("I".($k+2), $process);
	
			$objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":I".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.($k+2))->getFont()->setSize(10);
			
		$objPHPExcel->getActiveSheet()->setTitle($filename);
		$objPHPExcel->setActiveSheetIndex(0);
		$filename = iconv("UTF-8", "EUC-KR", $filename);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=".$filename.".xlsx");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	
	function getPurchaseCode(){
		$pcode = $this->input->post('pcode', TRUE);
		
		if($pcode){
			$info = $this->Refund_model->getPurchaseCode($pcode);
			$result = array();
			//거래테이블에 해당 코드로 이미 등록된것이 있는지 확인
			$purchase_seq = isset($info->seq) ? $info->seq : '';
			if($purchase_seq){
				$result = array('result' => 'ok', 'data' => $info);
			}else{
			    $result = array('result' => 'notfound'); //해당코드로 인해 조회가 안되는 경우
			}
			
			echo json_encode($result);
		}
	}
	
}
