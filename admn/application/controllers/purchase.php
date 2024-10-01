<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
		$this->load->model('Purchase_model');
		$this->load->model('Login_model');
		$this->load->model('Manager_model');
		$this->load->model('Brand_model');
        $this->load->model('Goods_model');
		
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
		$sstype        = $this->input->get('sstype', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sonlineyn     = $this->input->get('sonlineyn', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
        $smanager      = $this->input->get('smanager', TRUE);
		$spurchase_method = $this->input->get('spurchase_method', TRUE);
		$splace        = $this->input->get('splace', TRUE);
		$sstock        = $this->input->get('sstock', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		$scale         = 20;
		
		if($ssdate) $condition['ssdate'] = $ssdate;
		if($sedate) $condition['sedate'] = $sedate;
		if($stype) $condition['stype'] = $stype;
		if($sstype) $condition['sstype'] = $sstype;
		if($skeyword) $condition['skeyword'] = $skeyword;
		if($sonlineyn) $condition['sonlineyn'] = $sonlineyn;
		if($sbrand) $condition['sbrand'] = $sbrand;
		if($skind) $condition['skind'] = $skind;
		if($spaymethod) $condition['spaymethod'] = $spaymethod;
        if($smanager) $condition['smanager'] = $smanager;
		if($spurchase_method) $condition['spurchase_method'] = $spurchase_method;
		if($sstock) $condition['sstock'] = $sstock;
		if($splace) $condition['splace'] = $splace;
		if($saccount_conf) $condition['saccount_conf'] = $saccount_conf;
		if($sminprice || $smaxprice){
			$condition['smmpricecol'] = $smmpricecol;
			$sminprice = str_replace(',', '', $sminprice);
			$smaxprice = str_replace(',', '', $smaxprice);
			$condition['sminprice'] = $sminprice;
			$condition['smaxprice'] = $smaxprice;
		}
		
		$board_cnt = $this->Purchase_model->getListCnt($condition);
		$brand_list = $this->Brand_model->getList();
		
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
		
		$param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sstype=".urlencode($sstype)."&sonlineyn=".$sonlineyn."&sbrand=".$sbrand."&skind=".$skind."&spaymethod=".$spaymethod."&spurchase_method=".$spurchase_method."&sstock=".$sstock."&splace=".$splace."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice."&smanager=".$smanager;
		$param = "page=".$page.$param2;
		
		$board_list = $this->Purchase_model->getList($condition,$scale,$first);
		
		$getOnlineyData1 = $this->Purchase_model->getOnlineyData1($condition)->cnt; //등록된수량
		$getOnlineyData2 = $this->Purchase_model->getOnlineyData2($condition)->pprice; //등록된매입금액
		$getOnlineyData3 = $this->Purchase_model->getOnlineyData3($condition)->pprice; //등록된위탁금액
		$getOnlinenData1 = $this->Purchase_model->getOnlinenData1($condition)->cnt; //미등록된수량
		$getOnlinenData2 = $this->Purchase_model->getOnlinenData2($condition)->pprice; //미등록된매입금액
		$getOnlinenData3 = $this->Purchase_model->getOnlinenData3($condition)->pprice; //미등록된위탁금액
		$total_purchaseprice1 = $this->Purchase_model->getPurchaseprice1($condition)->pprice;
		$total_purchaseprice2 = $this->Purchase_model->getPurchaseprice2($condition)->pprice;
		
		$getType1Cnt = $this->Purchase_model->getTypeCnt($condition, '매입');
		$getType2Cnt = $this->Purchase_model->getTypeCnt($condition, '위탁');
		$getType3Cnt = $this->Purchase_model->getTypeCnt($condition, '기타');
		$getType4Cnt = $this->Purchase_model->getTypeCnt($condition, '교환');
        $getType5Cnt = $this->Purchase_model->getTypeCnt($condition, '반환');

        $getType3Data = $this->Purchase_model->getType3Data($condition)->exprice; //총교환금액
        $getType4Data = $this->Purchase_model->getType4Data($condition)->exprice; //등록교환금액
        $getType5Data = $this->Purchase_model->getType5Data($condition)->exprice; //미등록교환금액

		$total_purchaseprice = $this->Purchase_model->getPurchasetotsellprise($condition)->price;
		$stocktotpriceY = $this->Purchase_model->getstocktotpriceY($condition);
		$stocktotpriceN = $this->Purchase_model->getstocktotpriceN($condition);

        $stocktotprice = $this->Goods_model->getstocktotprice($condition);

        $manager_list = $this->Manager_model->getList(); //담당자
		
		//페이징 html 생성
		$paging_html = '';
		if($scale < $board_cnt)
		{
			$paging_html = '<ul class="pagination pagination-sm">';
			if($block > 1)
			{
				$paging_html .= '<li><a href="/admn/purchase?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
			}
		
			for($go_page; $go_page <= $last_page; $go_page++)
			{
			if($page == $go_page)
			{
			$paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
				} else
			{
			$paging_html .= '<li><a href="/admn/purchase?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
			}
			}
		
					if($block < $total_block) {
					$paging_html .= '<li><a href="/admn/purchase?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			}
			$paging_html .= '</ul>';
		}
		
		$data = array(
		    "spurchase_method" => $spurchase_method,
			"ssdate" => $ssdate,
			"sedate" => $sedate,
			"stype" => $stype,
			"skeyword" => $skeyword,
			"skind" => $skind,
			"sbrand" => $sbrand,
		    "sstock" => $sstock,
            "smanager" => $smanager,
			"scale" => $scale,
			"page" => $page,
			"board_list" => $board_list,
			"paging_html" => $paging_html,
			"total_purchaseprice1" => $total_purchaseprice1,
			"total_purchaseprice2" => $total_purchaseprice2,
			"total_cnt" => $board_cnt,
			"sstype" => $sstype,
			'sonlineyn' => $sonlineyn,
			"param" => $param,
			"brand_list" => $brand_list,
			"spaymethod" => $spaymethod,
			"purchase_kind" => $this->config->item('purchase_kind'),
		    "purchase_method" => $this->config->item('purchase_method'),
		    "purchase_paymethod" => $this->config->item('purchase_paymethod'),
		    "purchase_type" => $this->config->item('purchase_type'),
		    "getOnlineyData1" => $getOnlineyData1,
		    "getOnlineyData2" => $getOnlineyData2,
		    "getOnlineyData3" => $getOnlineyData3,
		    "getOnlinenData1" => $getOnlinenData1,
		    "getOnlinenData2" => $getOnlinenData2,
		    "getOnlinenData3" => $getOnlinenData3,
		    "total_purchaseprice" => $total_purchaseprice,
		    "getType1Cnt" => $getType1Cnt,
		    "getType2Cnt" => $getType2Cnt,
		    "getType3Cnt" => $getType3Cnt,
		    "getType4Cnt" => $getType4Cnt,
            "getType5Cnt" => $getType5Cnt,
		    "goods_place" => $this->config->item('goods_place'),
		    "splace" => $splace,
		    'stocktotpriceY' => $stocktotpriceY->price,
		    'stocktotpriceN' => $stocktotpriceN->price,
		    'saccount_conf' => $saccount_conf,
		    'smmpricecol' => $smmpricecol,
		    'sminprice' => $sminprice,
		    'smaxprice' => $smaxprice,
            'getType3Data' => $getType3Data,
            'getType4Data' => $getType4Data,
            'getType5Data' => $getType5Data,
            'manager_list' => $manager_list,
            'stocktotprice' => $stocktotprice
		);
		
		$this->load->view('purchase/list', $data);
	}

	function delproc()
	{	
		
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate        = $this->input->get('ssdate', TRUE);
		$sedate        = $this->input->get('sedate', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sstype        = $this->input->get('sstype', TRUE);
		$sonlineyn     = $this->input->get('sonlineyn', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
		$spurchase_method = $this->input->get('spurchase_method', TRUE);
		$sstock = $this->input->get('sstock', TRUE);
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sstype=".urlencode($sstype)."&sonlineyn=".$sonlineyn."&sbrand=".$sbrand."&skind=".$skind."&spaymethod=".$spaymethod."&spurchase_method=".$spurchase_method."&sstock=".$sstock."&splace=".$splace."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$delchk = $this->input->post('delchk', TRUE);
		if($delchk)
		{
			$delchk_str = implode(',', $delchk);
			$this->Purchase_model->deleteList($delchk_str);
			doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/purchase?".$param);
		}
		$seq = $this->input->get('seq', TRUE);
		if($seq)
		{
			$page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
			$this->Purchase_model->deleteList($seq);
			doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/purchase?".$param);
		}
	}
	
	function write()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate        = $this->input->get('ssdate', TRUE);
		$sedate        = $this->input->get('sedate', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sstype        = $this->input->get('sstype', TRUE);
		$sonlineyn     = $this->input->get('sonlineyn', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
		$spurchase_method = $this->input->get('spurchase_method', TRUE);
		$sstock        = $this->input->get('sstock', TRUE);
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sstype=".urlencode($sstype)."&sonlineyn=".$sonlineyn."&sbrand=".$sbrand."&skind=".$skind."&spaymethod=".$spaymethod."&spurchase_method=".$spurchase_method."&sstock=".$sstock."&splace=".$splace."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$manager_list = $this->Manager_model->getList(); //담당자
		$brand_list = $this->Brand_model->getList();
		
		$data = array(
			"page" => $page,
			"purchase_type" => $this->config->item('purchase_type'),
			"purchase_kind" => $this->config->item('purchase_kind'),
			"purchase_method" => $this->config->item('purchase_method'),
			"manager_list" => $manager_list,
			"purchase_class" => $this->config->item('purchase_class'),
			"purchase_paymethod" => $this->config->item('purchase_paymethod'),
			"required_mark" => $this->config->item('required_mark'),
			"goods_astype" => $this->config->item('goods_astype'),
			"goods_note" => $this->config->item('goods_note'),
			"goods_guarantee" => $this->config->item('goods_guarantee'),
			"param" => $param,
			'brand_list' => $brand_list,
			"goods_place" => $this->config->item('goods_place')
		);
		
		$this->load->view('purchase/write', $data);
	}

	function writeproc()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate        = $this->input->get('ssdate', TRUE);
		$sedate        = $this->input->get('sedate', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sstype        = $this->input->get('sstype', TRUE);
		$sonlineyn     = $this->input->get('sonlineyn', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
		$spurchase_method = $this->input->get('spurchase_method', TRUE);
		$sstock        = $this->input->get('sstock', TRUE);
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sstype=".urlencode($sstype)."&sonlineyn=".$sonlineyn."&sbrand=".$sbrand."&skind=".$skind."&spaymethod=".$spaymethod."&spurchase_method=".$spurchase_method."&sstock=".$sstock."&splace=".$splace."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$pcode = $this->get_purchase_code();
		
		$seller = trim($this->input->post('seller', TRUE));
		$onlineyn = trim($this->input->post('onlineyn', TRUE));
		$sellerphone1 = trim($this->input->post('sellerphone1', TRUE));
		$sellerphone2 = trim($this->input->post('sellerphone2', TRUE));
		$sellerphone3 = trim($this->input->post('sellerphone3', TRUE));
		$sellerphone = '';
		if($sellerphone1 && $sellerphone2 && $sellerphone3){
			$sellerphone = $sellerphone1.'-'.$sellerphone2.'-'.$sellerphone3;
		} 
		$type = $this->input->post('type', TRUE);
		$method = $this->input->post('method', TRUE);
		$kind = $this->input->post('kind', TRUE);
		$modelname = trim($this->input->post('modelname', TRUE));
		$pprice = $this->input->post('pprice', TRUE);
		$pprice = str_replace(',', '', $pprice);
		if(!$pprice) $pprice = 0;
		$class = $this->input->post('class', TRUE);
		$paymethod = $this->input->post('paymethod', TRUE);
		$account = trim($this->input->post('account', TRUE));
		$note = $this->input->post('note', TRUE);
		$manager = $this->input->post('manager', TRUE);
		$astype = $this->input->post('astype', TRUE);
		if($astype) $astype = implode($astype, '|');
		$birth = $this->input->post('birth', TRUE);
		$reference = $this->input->post('reference', TRUE);
		if($reference) $reference = implode($reference, '|');
		$asprice = $this->input->post('asprice', TRUE);
		$asprice = str_replace(',', '', $asprice);
		$asprice = trim($asprice);
		$asprice = $asprice == '' ? 0 : $asprice;
		$goods_price = $this->input->post('goods_price', TRUE);
		$goods_price = str_replace(',', '', $goods_price);
		$goods_price = $goods_price == '' ? 0 : $goods_price;
      	$goods_stock = $this->input->post('goods_stock', TRUE);
      	$goods_stock = !empty($goods_stock) ? $goods_stock : 'Y';
        $exprice = $this->input->post('exprice', TRUE);
        $exprice = str_replace(',', '', $exprice);
        if(empty($exprice)) $exprice = 0;
		$reason = $this->input->post('reason', TRUE);

        //astype 기타 
        $astype_etc_chk = $this->input->post('astype_etc_chk', TRUE);
        $astype_etc_txt = $this->input->post('astype_etc_txt', TRUE);
        if($astype_etc_chk == '기타' && $astype_etc_txt){
            if($astype) $astype = $astype.'|';
            $astype .= '기타!@#^'.$astype_etc_txt;
        }
 
		//참고사항 기타 
		$reference_etc_chk = $this->input->post('reference_etc_chk', TRUE);
		$reference_etc_txt = $this->input->post('reference_etc_txt', TRUE);
		if($reference_etc_chk == '기타' && $reference_etc_txt){
			if($reference) $reference = $reference.'|';
			$reference .= '기타!@#^'.$reference_etc_txt;
		}

		$guarantee = $this->input->post('guarantee', TRUE);
		if($guarantee) $guarantee = implode($guarantee, '|');
		$pbrand_seq = $this->input->post('pbrand_seq', TRUE);
		$place = $this->input->post('place', TRUE);
		$account_conf = !empty($this->input->post('account_conf', TRUE)) ? $this->input->post('account_conf', TRUE) : 'N';
		
		//유효성 체크
		if($type == ''){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/purchase");
		}

		//200921 온라인등록이 매입실패 인경우 as요청은 빈값으로 저장
		if($onlineyn == 'F'){
			$astype = '';
		}

		$data = array(
			'pcode' => $pcode,
			'seller' => $seller,
			'onlineyn' => $onlineyn,
			'sellerphone' => $sellerphone,
			'type' => $type,
			'method' => $method,
			'kind' => $kind,
			'modelname' => $modelname,
			'pprice' => $pprice,
            'exprice' => $exprice,
			'class' => $class,
			'paymethod' => $paymethod,
			'account' => $account,
			'note' => $note,
			'manager' => $manager,
			'astype' => $astype,
			'reference' => $reference,
			'guarantee' => $guarantee,
			'pbrand_seq' => $pbrand_seq,
			'place' => $place,
			'account_conf' => $account_conf,
			'birth' => $birth,
			'asprice' => $asprice,
			'goods_price' => $goods_price,
            'goods_stock' => $goods_stock,
			'reason' => $reason
		);

		$this->Purchase_model->insertList($data);
		
		//as요청을 한가지라도 선택한경우 as자동등록
		$purchase_seq = $this->db->insert_id();
		if($astype){
			echo "
				<script src='/lib/jquery/jquery-1.12.0.min.js'></script>
				<script>
					$.ajax({
						type: 'POST',
				        url:'/admn/asinfo/writeproc',
				        data: 'purchase_seq=".$purchase_seq."'
				    })
				</script>";
		}
		
		//온라인등록시 상품 자동등록
		if($onlineyn == 'Y'){
			echo "
				<script src='/lib/jquery/jquery-1.12.0.min.js'></script>
				<script>
					$.ajax({
						type: 'POST',
				      url:'/admn/goods/writeproc',
				      data: 'purchase_seq=".$purchase_seq."'
				   })
				</script>";
		}

		doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/purchase?".$param);
	}

	function modify()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate        = $this->input->get('ssdate', TRUE);
		$sedate        = $this->input->get('sedate', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sstype        = $this->input->get('sstype', TRUE);
		$sonlineyn     = $this->input->get('sonlineyn', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
		$spurchase_method = $this->input->get('spurchase_method', TRUE);
		$sstock        = $this->input->get('sstock', TRUE);
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sstype=".urlencode($sstype)."&sonlineyn=".$sonlineyn."&sbrand=".$sbrand."&skind=".$skind."&spaymethod=".$spaymethod."&spurchase_method=".$spurchase_method."&sstock=".$sstock."&splace=".$splace."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$seq      = $this->input->get('seq', TRUE);
		$manager_list = $this->Manager_model->getList(); //담당자 //담당자
		$view = $this->Purchase_model->getView($seq);
		$brand_list = $this->Brand_model->getList();
		
		if(count($view) == 0){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/purchase?".$param);
		}
		
		$data = array(
			"seq" => $seq,
			"page" => $page,
			"purchase_type" => $this->config->item('purchase_type'),
			"purchase_kind" => $this->config->item('purchase_kind'),
			"purchase_method" => $this->config->item('purchase_method'),
			"manager_list" => $manager_list,
			"purchase_class" => $this->config->item('purchase_class'),
			"purchase_paymethod" => $this->config->item('purchase_paymethod'),
			"required_mark" => $this->config->item('required_mark'),
			"goods_astype" => $this->config->item('goods_astype'),
			"goods_note" => $this->config->item('goods_note'),
			"goods_guarantee" => $this->config->item('goods_guarantee'),
			"view" => $view,
			"param" => $param,
			"goods_note" => $this->config->item('goods_note'),
			"goods_guarantee" => $this->config->item('goods_guarantee'),
			'brand_list' => $brand_list,
			"goods_place" => $this->config->item('goods_place')
		);
		
		$this->load->view('purchase/modify', $data);
	}

	function modifyproc()
	{
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate        = $this->input->get('ssdate', TRUE);
		$sedate        = $this->input->get('sedate', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sstype        = $this->input->get('sstype', TRUE);
		$sonlineyn     = $this->input->get('sonlineyn', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
		$spurchase_method = $this->input->get('spurchase_method', TRUE);
		$sstock        = $this->input->get('sstock', TRUE);
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sstype=".urlencode($sstype)."&sonlineyn=".$sonlineyn."&sbrand=".$sbrand."&skind=".$skind."&spaymethod=".$spaymethod."&spurchase_method=".$spurchase_method."&sstock=".$sstock."&splace=".$splace."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		$seq = $this->input->post('seq', TRUE);
		$onlineyn = trim($this->input->post('onlineyn', TRUE));
		$seller = trim($this->input->post('seller', TRUE));
		$sellerphone1 = trim($this->input->post('sellerphone1', TRUE));
		$sellerphone2 = trim($this->input->post('sellerphone2', TRUE));
		$sellerphone3 = trim($this->input->post('sellerphone3', TRUE));
		$sellerphone = '';
		if($sellerphone1 && $sellerphone2 && $sellerphone3){
			$sellerphone = $sellerphone1.'-'.$sellerphone2.'-'.$sellerphone3;
		}  
		$type = $this->input->post('type', TRUE);
		$method = $this->input->post('method', TRUE);
		$kind = $this->input->post('kind', TRUE);
		$modelname = trim($this->input->post('modelname', TRUE));
		$pprice = $this->input->post('pprice', TRUE);
		$pprice = str_replace(',', '', $pprice);
		$class = $this->input->post('class', TRUE);
		$paymethod = $this->input->post('paymethod', TRUE);
		$account = trim($this->input->post('account', TRUE));
		$note = $this->input->post('note', TRUE);
		$manager = $this->input->post('manager', TRUE);
		$astype = $this->input->post('astype', TRUE);
		if($astype) $astype = implode($astype, '|');
		$reference = $this->input->post('reference', TRUE);
		if($reference) $reference = implode($reference, '|');
		$birth = $this->input->post('birth', TRUE);
		$asprice = $this->input->post('asprice', TRUE);
		$asprice = str_replace(',', '', $asprice);
		$asprice = $asprice == '' ? 0 : $asprice;
		$goods_price = $this->input->post('goods_price', TRUE);
		$goods_price = str_replace(',', '', $goods_price);
		$goods_price = $goods_price == '' ? 0 : $goods_price;
      $goods_stock = $this->input->post('goods_stock', TRUE);
        $exprice = $this->input->post('exprice', TRUE);
        $exprice = str_replace(',', '', $exprice);
        if(empty($exprice)) $exprice = 0;
		$reason = $this->input->post('reason', TRUE);

        //astype 기타 
        $astype_etc_chk = $this->input->post('astype_etc_chk', TRUE);
        $astype_etc_txt = $this->input->post('astype_etc_txt', TRUE);
        if($astype_etc_chk == '기타'){
            if($astype) $astype = $astype.'|';
            $astype .= '기타!@#^'.$astype_etc_txt;
        }

		//참고사항 기타 
		$reference_etc_chk = $this->input->post('reference_etc_chk', TRUE);
		$reference_etc_txt = $this->input->post('reference_etc_txt', TRUE);
		if($reference_etc_chk == '기타' && $reference_etc_txt){
			if($reference) $reference = $reference.'|';
			$reference .= '기타!@#^'.$reference_etc_txt;
		}

		$guarantee = $this->input->post('guarantee', TRUE);
		if($guarantee) $guarantee = implode($guarantee, '|');
		$pbrand_seq = $this->input->post('pbrand_seq', TRUE);
		$place = $this->input->post('place', TRUE);
		$account_conf = !empty($this->input->post('account_conf', TRUE)) ? $this->input->post('account_conf', TRUE) : '';
		
		//유효성 체크
		if($seq == '' || $type == ''){
			doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/purchase");
		}

		//200921 온라인등록이 매입실패 인경우 as요청은 빈값으로 저장
		if($onlineyn == 'F'){
			$astype = '';
		}

		$data = array(
			'seller' => $seller,
			'onlineyn' => $onlineyn,
			'sellerphone' => $sellerphone,
			'type' => $type,
			'method' => $method,
			'kind' => $kind,
			'modelname' => $modelname,
			'class' => $class,
			'paymethod' => $paymethod,
			'account' => $account,
			'note' => $note,
			'manager' => $manager,
			'astype' => $astype,
			'reference' => $reference,
			'guarantee' => $guarantee,
			'pbrand_seq' => $pbrand_seq,
			'place' => $place,
			'birth' => $birth,
			'asprice' => $asprice,
			'goods_price' => $goods_price,
			'reason' => $reason
		);

      if(!empty($goods_stock)){
        $this->Purchase_model->updateGoodsstock($goods_stock, $seq);
         $data['goods_stock'] = $goods_stock;
      }

		if($account_conf) $data['account_conf'] = $account_conf;
		
		//admin의 경우에만 매입거래가격 수정 가능
		if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){
			$data['pprice'] = $pprice;
            $data['exprice'] = $exprice;
		}
		
		$this->Purchase_model->updateList($data, $seq);
		
		//as요청을 한가지라도 선택한경우 as자동등록
		if($astype){
			echo "
				<script src='/lib/jquery/jquery-1.12.0.min.js'></script>
				<script>
					$.ajax({
						type: 'POST',
				        url:'/admn/asinfo/writeproc',
				        data: 'purchase_seq=".$seq."'
				    })
				</script>";
		}
		
		//온라인등록시 상품 자동등록
		if($onlineyn == 'Y'){
			echo "
				<script src='/lib/jquery/jquery-1.12.0.min.js'></script>
				<script>
					$.ajax({
						type: 'POST',
				        url:'/admn/goods/writeproc',
				        data: 'purchase_seq=".$seq."'
				    })
				</script>";
		}

		//수정시 pbrand_seq값이 있는 경우 상품쪽도 같이 연동이 되어야 함
		if($pbrand_seq){
			echo "
				<script src='/lib/jquery/jquery-1.12.0.min.js'></script>
				<script>
					$.ajax({
						type: 'POST',
				        url:'/admn/goods/brandupdate',
				        data: 'purchase_seq=".$seq."&pbrand_seq=".$pbrand_seq."'
				    })
				</script>";
		}

		//tb_goods > price 업데이트
		$this->Purchase_model->updateGoodsprice($goods_price, $seq);
     
		doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/purchase?".$param);

	}
	
	function copyproc()
	{
		$seq = $this->input->get('seq', TRUE);
		$page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
		$ssdate        = $this->input->get('ssdate', TRUE);
		$sedate        = $this->input->get('sedate', TRUE);
		$stype         = $this->input->get('stype', TRUE);
		$skeyword      = $this->input->get('skeyword', TRUE);
		$sstype        = $this->input->get('sstype', TRUE);
		$sonlineyn     = $this->input->get('sonlineyn', TRUE);
		$sbrand        = $this->input->get('sbrand', TRUE);
		$skind         = $this->input->get('skind', TRUE);
		$spaymethod    = $this->input->get('spaymethod', TRUE);
		$spurchase_method = $this->input->get('spurchase_method', TRUE);
		$sstock        = $this->input->get('sstock', TRUE);
		$splace        = $this->input->get('splace', TRUE);
		$saccount_conf = $this->input->get('saccount_conf', TRUE);
		$smmpricecol   = $this->input->get('smmpricecol', TRUE);
		$sminprice     = $this->input->get('sminprice', TRUE);
		$smaxprice     = $this->input->get('smaxprice', TRUE);
		
		$param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&stype=".$stype."&skeyword=".$skeyword."&sstype=".urlencode($sstype)."&sonlineyn=".$sonlineyn."&sbrand=".$sbrand."&skind=".$skind."&spaymethod=".$spaymethod."&spurchase_method=".$spurchase_method."&sstock=".$sstock."&splace=".$splace."&saccount_conf=".$saccount_conf."&smmpricecol=".$smmpricecol."&sminprice=".$sminprice."&smaxprice=".$smaxprice;
		
		if($seq){
			$pcode = $this->get_purchase_code();
			$this->Purchase_model->copy($seq, $pcode);
			doMsgLocation('복사 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/purchase/?$param");
		}
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
        $sstype        = $this->input->get('sstype', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sonlineyn     = $this->input->get('sonlineyn', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $spaymethod    = $this->input->get('spaymethod', TRUE);
        $smanager      = $this->input->get('smanager', TRUE);
        $spurchase_method = $this->input->get('spurchase_method', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $saccount_conf = $this->input->get('saccount_conf', TRUE);
        $smmpricecol   = $this->input->get('smmpricecol', TRUE);
        $sminprice     = $this->input->get('sminprice', TRUE);
        $smaxprice     = $this->input->get('smaxprice', TRUE);
        
        if($ssdate) $condition['ssdate'] = $ssdate;
        if($sedate) $condition['sedate'] = $sedate;
        if($stype) $condition['stype'] = $stype;
        if($sstype) $condition['sstype'] = $sstype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        if($sonlineyn) $condition['sonlineyn'] = $sonlineyn;
        if($sbrand) $condition['sbrand'] = $sbrand;
        if($skind) $condition['skind'] = $skind;
        if($spaymethod) $condition['spaymethod'] = $spaymethod;
        if($smanager) $condition['smanager'] = $smanager;
        if($spurchase_method) $condition['spurchase_method'] = $spurchase_method;
        if($sstock) $condition['sstock'] = $sstock;
        if($splace) $condition['splace'] = $splace;
        if($saccount_conf) $condition['saccount_conf'] = $saccount_conf;
        if($sminprice || $smaxprice){
            $condition['smmpricecol'] = $smmpricecol;
            $sminprice = str_replace(',', '', $sminprice);
            $smaxprice = str_replace(',', '', $smaxprice);
            $condition['sminprice'] = $sminprice;
            $condition['smaxprice'] = $smaxprice;
        }
		
		$member_list = $this->Login_model->getMemberList();
		
		$board_list = $this->Purchase_model->getList($condition);
        
		if(count($board_list) == 0){
			doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
			return true;
		}
		
		$filename = date('Y-m-d').'_매입리스트_'.count($board_list).'건';
	
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
	
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1", '거래번호')
		->setCellValue("B1", '상품코드')
		->setCellValue("C1", '판매자')
		->setCellValue("D1", '판매자연락처')
		->setCellValue("E1", '매입한일자')
		->setCellValue("F1", '구분')
		->setCellValue("G1", '모델명')
		->setCellValue("H1", '매입거래가격')
        ->setCellValue("I1", '교환금액')
		->setCellValue("J1", '지급방법')
		->setCellValue("K1", '비고')
		->setCellValue("L1", '입금계좌번호');
	
		$objPHPExcel->getActiveSheet()->getStyle("A1:L1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1:L1")->getFill()->applyFromArray(array(
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
			$seq = $v->seq;
			$pcode = $v->pcode;
			$onlineyn = $v->onlineyn;
			$seller = $v->seller;
			$sellerphone = $v->sellerphone;
			$pdate = $v->pdate;
			 if($pdate){
				 $pdate = strtotime($pdate);
				 $pdate = date('Y-m-d', $pdate);
			 }else{
			 	$pdate = '';
			 }
			$type = $v->type;
			$method = $v->method;
			$kind = $v->kind;
			$modelname = $v->modelname;
			$pprice = $v->pprice;
			$pprice = number_format($pprice);
            $exprice = $v->exprice;
            $exprice = number_format($exprice);
			$class = $v->class;
			$paymethod = $v->paymethod;
			$note = $v->note;
			if(mb_strlen($note) > 20){
				$note = mb_substr($note, 0, 20, 'utf-8').'..';
			}
			$manager = $v->manager;
			
			//admin의 경우에만 매입거래가격 보임
			if(!in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){
				$pprice = '';
                $exprice = '';
			}
			$account = $v->account;
              
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".($k+2), $seq)
			->setCellValue("B".($k+2), $pcode)
			->setCellValue("C".($k+2), $seller)
			->setCellValue("D".($k+2), $sellerphone)
			->setCellValue("E".($k+2), $pdate)
			->setCellValue("F".($k+2), $type)
			->setCellValue("G".($k+2), $modelname)
			->setCellValue("H".($k+2), $pprice)
            ->setCellValue("I".($k+2), $exprice)
			->setCellValue("J".($k+2), $paymethod)
			->setCellValue("K".($k+2), $note)
			->setCellValue("L".($k+2), $account);
	
			$objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":L".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	
		$objPHPExcel->getActiveSheet()->getStyle('A1:L'.($k+2))->getFont()->setSize(10);
			
		$objPHPExcel->getActiveSheet()->setTitle($filename);
		$objPHPExcel->setActiveSheetIndex(0);
		$filename = iconv("UTF-8", "EUC-KR", $filename);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=".$filename.".xlsx");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	
	function get_purchase_code(){
		//DB에서 마지막 값을 조회
		$code = $this->Purchase_model->getLastCode();
		
		if($code == ''){
			return "aa00001"; //최초
		}
		
		if(is_numeric($code)){
			$code = (int)$code;
			$code++;
		}else{
		
			$al1 = substr($code, 0, 1);
			$al2 = substr($code, 1, 1);
			$num = (int)substr($code, 2, 5);
			
			if($num == 99999){
				$alphas = range('a','z');
				if($al2 == 'z'){
					if($al1 == 'z'){
						$code = 1;
					}else{
						foreach($alphas as $idx => $v){
							if($al1 == $v){
								$al1 = $alphas[$idx+1];
								break;
							}
						}
						$code = $al1.'a00001';
					}
				}else{
					foreach($alphas as $idx => $v){
						if($al2 == $v){
							$al2 = $alphas[$idx+1];
							break;
						}
					}	
					$code = $al1.$al2.'00001';
				}
			}else{
				$num++;
				$num = '00000'.$num;
				$num = substr($num, -5);
				$code = $al1.$al2.$num;
			}
		}
		
		return $code;
	} 
	
	function brandupdate(){
		$purchase_seq = $this->input->post('purchase_seq', TRUE);
		$pbrand_seq = $this->input->post('brand_seq', TRUE);
		$this->Purchase_model->brandupdate($purchase_seq, $pbrand_seq);
	}
	
}
