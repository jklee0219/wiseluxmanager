<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends CI_Controller
{
    protected $sClientId = '8fsBDx2e8EQnOYI22zfAyH';
    protected $sClientSecret = 'hBiGS93xGtPoaI1fO2e7jN';
    // protected $cafe24_ftp_host = '112.175.31.236';
    protected $cafe24_ftp_host = '14.128.159.170';
    protected $cafe24_ftp_id = 'wiselux';
    protected $cafe24_ftp_pw = 'ASwsux1103!!';
    
    function __construct()
    {
        parent::__construct();
        include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
        $this->load->model('Goods_model');
        $this->load->model('Goods_img_model');
        $this->load->model('Login_model');
        $this->load->model('Brand_model');
        $this->load->model('Purchase_model');
        $this->load->model('Cafe24api_model');
        $this->load->model('Asinfo_model');
        
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
        $ssrdate       = $this->input->get('ssrdate', TRUE);
        $serdate       = $this->input->get('serdate', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $sstype        = $this->input->get('sstype', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sorder        = $this->input->get('sorder', TRUE) ? $this->input->get('sorder', TRUE) : '1';
        $sc24display   = $this->input->get('sc24display', TRUE);
        $scale         = 20;
        
        if($ssdate) $condition['ssdate'] = $ssdate;
        if($sedate) $condition['sedate'] = $sedate;
        if($ssrdate) $condition['ssrdate'] = $ssrdate;
        if($serdate) $condition['serdate'] = $serdate;
        if($sstock) $condition['sstock'] = $sstock;
        if($stype) $condition['stype'] = $stype;
        if($sstype) $condition['sstype'] = $sstype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        if($sbrand) $condition['sbrand'] = $sbrand;
        if($skind) $condition['skind'] = $skind;
        if($sorder) $condition['sorder'] = $sorder;
        if($splace) $condition['splace'] = $splace;
        if($sc24display) $condition['sc24display'] = $sc24display;
        
        $board_cnt = $this->Goods_model->getListCnt($condition);
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
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sstock=".$sstock."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstype=".$sstype."&ssrdate=".$ssrdate."&serdate=".$serdate."&sorder=".$sorder."&splace=".$splace."&sc24display=".$sc24display;
        $param2 = "&ssdate=".$ssdate."&sedate=".$sedate."&sstock=".$sstock."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstype=".$sstype."&ssrdate=".$ssrdate."&serdate=".$serdate."&sorder=".$sorder."&splace=".$splace."&sc24display=".$sc24display;
        
        $board_list = $this->Goods_model->getList($condition,$scale,$first);
        $stocktotprice = $this->Goods_model->getstocktotprice($condition);
        
        //페이징 html 생성
        $paging_html = '';
        if($scale < $board_cnt)
        {
            $paging_html = '<ul class="pagination pagination-sm">';
            if($block > 1)
            {
                $paging_html .= '<li><a href="/admn/goods?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }
            
            for($go_page; $go_page <= $last_page; $go_page++)
            {
                if($page == $go_page)
                {
                    $paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
                } else
                {
                    $paging_html .= '<li><a href="/admn/goods?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
                }
            }
            
            if($block < $total_block) {
                $paging_html .= '<li><a href="/admn/goods?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            }
            $paging_html .= '</ul>';
        }
        
        $data = array(
            "ssdate" => $ssdate,
            "sedate" => $sedate,
            "ssrdate" => $ssrdate,
            "serdate" => $serdate,
            "sstock" => $sstock,
            "skind" => $skind,
            "sbrand" => $sbrand,
            "stype" => $stype,
            "skeyword" => $skeyword,
            "scale" => $scale,
            "page" => $page,
            "board_list" => $board_list,
            "paging_html" => $paging_html,
            "param" => $param,
            "brand_list" => $brand_list,
            "purchase_kind" => $this->config->item('purchase_kind'),
            "stocktotprice" => $stocktotprice,
            "sstype" => $sstype,
            "sorder" => $sorder,
            "goods_place" => $this->config->item('goods_place'),
            "splace" => $splace,
            "purchase_type" => $this->config->item('purchase_type'),
            'sc24display' => $sc24display,
        );
        
        $this->load->view('goods/list', $data);
    }
    
    function delproc()
    {
        
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $ssrdate       = $this->input->get('ssrdate', TRUE);
        $serdate       = $this->input->get('serdate', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $sstype        = $this->input->get('sstype', TRUE);
        $sorder        = $this->input->get('sorder', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sc24display   = $this->input->get('sc24display', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sstock=".$sstock."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstype=".$sstype."&ssrdate=".$ssrdate."&serdate=".$serdate."&sorder=".$sorder."&splace=".$splace."&sc24display=".$sc24display;
        
        $seq = $this->input->get('seq', TRUE);
        if($seq)
        {
            $this->pdApiCall('D', $seq); //카페24도 삭제
            
            //파일삭제
            $images_list = $this->Goods_img_model->getList($seq);
            foreach($images_list as $v){
                @unlink($_SERVER['DOCUMENT_ROOT'].'/'.$v->filepath.$v->realfilename);
            }
            
            $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
            $this->Goods_model->deleteList($seq);
            
            
            doMsgLocation('삭제 되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/goods?".$param);
        }
    }
    
    function write()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $ssrdate       = $this->input->get('ssrdate', TRUE);
        $serdate       = $this->input->get('serdate', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $sstype        = $this->input->get('sstype', TRUE);
        $sorder        = $this->input->get('sorder', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sc24display   = $this->input->get('sc24display', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sstock=".$sstock."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstype=".$sstype."&ssrdate=".$ssrdate."&serdate=".$serdate."&sorder=".$sorder."&splace=".$splace."&sc24display=".$sc24display;
        
        $brand_list = $this->Brand_model->getList();
        
        $data = array(
            "page" => $page,
            "required_mark" => $this->config->item('required_mark'),
            "brand_list" => $brand_list,
            "purchase_kind" => $this->config->item('purchase_kind'),
            "purchase_method" => $this->config->item('purchase_method'),
            "purchase_class" => $this->config->item('purchase_class'),
            "param" => $param,
            "goods_note" => $this->config->item('goods_note'),
            "goods_place" => $this->config->item('goods_place'),
            "goods_astype" => $this->config->item('goods_astype'),
            "purchase_type" => $this->config->item('purchase_type'),
        );
        
        $this->load->view('goods/write', $data);
    }
    
    function writeproc()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $ssrdate       = $this->input->get('ssrdate', TRUE);
        $serdate       = $this->input->get('serdate', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $sstype        = $this->input->get('sstype', TRUE);
        $sorder        = $this->input->get('sorder', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sc24display   = $this->input->get('sc24display', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sstock=".$sstock."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstype=".$sstype."&ssrdate=".$ssrdate."&serdate=".$serdate."&sorder=".$sorder."&splace=".$splace."&sc24display=".$sc24display;
        
        $purchase_seq = trim($this->input->post('purchase_seq', TRUE));
        if($purchase_seq == '') $purchase_seq = 0;
        $price = trim($this->input->post('price', TRUE));
        $price = str_replace(',', '', $price);
        if($price == '') $price = 0;
        $brand_seq = trim($this->input->post('brand_seq', TRUE));
        if(!$brand_seq) $brand_seq = 0;
        $selfcode = trim($this->input->post('selfcode', TRUE));
        $asmemo = $this->input->post('asmemo', TRUE);
        $note = $this->input->post('note', TRUE);
        $floor = $this->input->post('floor', TRUE);
        $reason  = $this->input->post('reason', TRUE);
        
        //카페24정보추가
        $c24_display = $this->input->post('c24_display', TRUE); //진열상태
        $c24_category = $this->input->post('c24_category', TRUE); //상품분류 선택
        $c24_main = $this->input->post('c24_main', TRUE); //진열상태
        if($c24_main) $c24_main= '['.implode($c24_main, ',').']';
        $c24_summary_description = $this->input->post('c24_summary_description', TRUE); //상품 요약설명
        $c24_simple_description = $this->input->post('c24_simple_description'); //상품 간략설명
        $c24_simple_description = nl2br($c24_simple_description);
        $c24_simple_description = str_ireplace('<br />', "\n", $c24_simple_description);
        $c24_description = $this->input->post('c24_description', TRUE); //상품 상세설명
        $c24_supply_price = $this->input->post('c24_supply_price', TRUE); //공급가
        $c24_supply_price = str_replace(',', '', $c24_supply_price);
        $c24_origin_place_value = $this->input->post('c24_origin_place_value', TRUE); //원산지(기타정보)
        $c24_tax_type = $this->input->post('c24_tax_type', TRUE); //과세구분
        $c24_tax_amount = $this->input->post('c24_tax_amount', TRUE); //과세율
        $c24_supply_price = $c24_supply_price ? $c24_supply_price : 0;
        $c24_tax_amount = $c24_tax_amount ? $c24_tax_amount : 0;
        if($c24_tax_type != 'A') $c24_tax_amount = 0;
        
        if($purchase_seq != '' || $purchase_seq != '0'){
            //이미 등록된것은 등록 되면 안됨 (강제 종료)
            $chkcnt = $this->Goods_model->confirmPurchase($purchase_seq);
            if($chkcnt > 0){
                exit();
            }
        }
       
        //첨부파일
        $uploadFullDir = '/file/'.date('Y').'/'.date('m').'/'.date('d').'/';
        if(!is_dir($_SERVER['DOCUMENT_ROOT'].$uploadFullDir))
        {
            umask(0);
            mkdir($_SERVER['DOCUMENT_ROOT'].$uploadFullDir,0770,true);
        }
        
        $this->load->library('upload');
        
        $config = array();
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].$uploadFullDir;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        
        $file_db_arr = array();
        if(!empty($_FILES['image']['name'][0])){
            $files = $_FILES;
            $cpt = count($_FILES['image']['name']);
            $represent = "";
            
            for($i=0; $i<$cpt; $i++)
            {
                $filename = $files['image']['name'][$i];
                $fileext = explode('.', $filename);
                $fileext = end($fileext);
                $_FILES['image']['name']= uniqid().'.'.$fileext;
                $_FILES['image']['type']= $files['image']['type'][$i];
                $_FILES['image']['tmp_name']= $files['image']['tmp_name'][$i];
                $_FILES['image']['error']= $files['image']['error'][$i];
                $_FILES['image']['size']= $files['image']['size'][$i];
                $represent = $represent=="" ? 'Y' : 'N';
                
                $this->upload->initialize($config);
                if(!$this->upload->do_upload('image'))
                {
                    doMsgLocation(strip_tags($this->upload->display_errors()),"http://".$_SERVER['HTTP_HOST']."/admn/goods/write?".$param);
                }else{
                    $file_db_arr[] = array(
                        'represent' => $represent,
                        'filepath' => $uploadFullDir,
                        'filename' => $files['image']['name'][$i],
                        'realfilename' => $_FILES['image']['name']
                    );
                }
            }
        }
        
        if($purchase_seq){
            //매입정보
            $purchase_kind = trim($this->input->post('purchase_kind', TRUE));
            $purchase_modelname = trim($this->input->post('purchase_modelname', TRUE));
            $purchase_pdate = trim($this->input->post('purchase_pdate', TRUE));
            $purchase_pprice = trim($this->input->post('purchase_pprice', TRUE));
            $purchase_method = trim($this->input->post('purchase_method', TRUE));
            $purchase_class = trim($this->input->post('purchase_class', TRUE));
            $purchase_place = trim($this->input->post('purchase_place', TRUE));
            $purchase_reference = $this->input->post('purchase_reference', TRUE);
            if($purchase_reference) $purchase_reference = implode($purchase_reference, '|');

            //참고사항 기타 
            $reference_etc_chk = $this->input->post('purchase_reference_etc_chk', TRUE);
            $reference_etc_txt = $this->input->post('purchase_reference_etc_txt', TRUE);
            if($reference_etc_chk == '기타' && $reference_etc_txt){
                if($purchase_reference) $purchase_reference = $purchase_reference.'|';
                $purchase_reference .= '기타!@#^'.$reference_etc_txt;
            }

            $astype = $this->input->post('astype', TRUE);
            if($astype) $astype = implode($astype, '|');

            //astype 기타 
            $astype_etc_chk = $this->input->post('purchase_astype_etc_chk', TRUE);
            $astype_etc_txt = $this->input->post('purchase_astype_etc_txt', TRUE);
            if($astype_etc_chk == '기타'){
                if($astype) $astype = $astype.'|';
                $astype .= '기타!@#^'.$astype_etc_txt;
            }

            $purchase_asprice = $this->input->post('purchase_asprice', TRUE);
            $purchase_asprice = str_replace(',', '', $purchase_asprice);
            $purchase_type = $this->input->post('purchase_type', TRUE);

            if($purchase_kind || $purchase_modelname || $purchase_pdate || $purchase_pprice || $purchase_method || $purchase_class || $price){
                $data = array(
                    'pdate' => $purchase_pdate,
                    'kind' => $purchase_kind,
                    'modelname' => $purchase_modelname,
                    'method' => $purchase_method,
                    'class' => $purchase_class,
                    'reference' => $purchase_reference,
                    'note' => $note,
                    'astype' => $astype,
                    'type' => $purchase_type,
                    'asprice' => $purchase_asprice,
                    'place' => $purchase_place,
                    'goods_price' => $price
                );
                if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){
                    $purchase_pprice = str_replace(',', '', $purchase_pprice);
                    $data['pprice'] = $purchase_pprice;
                    
                    $purchase_exprice = $this->input->post('purchase_exprice', TRUE);
                    $purchase_exprice = str_replace(',', '', $purchase_exprice);
                    if(empty($purchase_exprice)) $purchase_exprice = 0;
                    $data['exprice'] = $purchase_exprice;
                }
                $this->Purchase_model->updateList($data, $purchase_seq);
            }

            //매입목록에서 온라인체크로 자동등록되는 경우 price값을 가져오도록 처리..
            $purchase_data = $this->Purchase_model->getView($purchase_seq);
            $price = empty($purchase_data->goods_price) ? 0 : $purchase_data->goods_price;

            //AS신청사유 수정
            $this->Purchase_model->updateReason($purchase_seq, $reason);

        }
        
        //brand_seq값이 있는 경우 매입쪽도 같이 연동이 되어야 함
        if($brand_seq && $purchase_seq){
            echo "
				<script src='/lib/jquery/jquery-1.12.0.min.js'></script>
				<script>
					$.ajax({
						type: 'POST',
				        url:'/admn/purchase/brandupdate',
				        data: 'purchase_seq=".$purchase_seq."&brand_seq=".$brand_seq."'
				    })
				</script>";
        }
        
        $stock = !empty($this->input->post('stock', TRUE)) ? $this->input->post('stock', TRUE) : '';
        if(empty($stock) && !empty($purchase_seq)){
            $purchase_data = $this->Purchase_model->getView($purchase_seq);
            $stock = $purchase_data->goods_stock;
        }

        $data = array(
            'purchase_seq' => $purchase_seq,
            'brand_seq' => $brand_seq,
            'selfcode' => $selfcode,
            'asmemo' => $asmemo,
            'note' => $note,
            'floor' => $floor,
            'c24_display' => $c24_display,
            'c24_category' => $c24_category,
            'c24_main' => $c24_main,
            'c24_summary_description' => $c24_summary_description,
            'c24_simple_description' => $c24_simple_description,
            'c24_description' => $c24_description,
            'c24_supply_price' => $c24_supply_price,
            'c24_origin_place_value' => $c24_origin_place_value,
            'c24_tax_type' => $c24_tax_type,
            'c24_tax_amount' => $c24_tax_amount,
            'c24_product_no' => 0,
            'stock' => $stock
        );

        //210812 재고없음으로 등록한 경우 stockcheck_date 날짜등록
        if($stock == 'N'){
            $data['stockcheck_date'] = date('Y-m-d H:i:s');
        }
        
        $this->Goods_model->insertList($data);
        $goods_seq = $this->db->insert_id();
        
        //파일 db insert
        $idx = 0;
        foreach($file_db_arr as $v){
            $idx++;
            $data = array(
                'goods_seq' => $goods_seq,
                'filepath' => $v['filepath'],
                'filename' => $v['filename'],
                'realfilename' => $v['realfilename'],
                'represent' => $v['represent'],
                'order' => $idx
            );
            $this->Goods_img_model->insertList($data);
        }

        //tb_purchase > goods_price 수정
        if($price && $purchase_seq){
            $this->Purchase_model->updatePurchaseGoodsprice($price,$purchase_seq);
        }
        
        $this->pdApiCall('C', $goods_seq); //카페24등록
        
        doMsgLocation('등록 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/goods?".$param);
    }
    
    function modify()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $ssrdate       = $this->input->get('ssrdate', TRUE);
        $serdate       = $this->input->get('serdate', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $sstype        = $this->input->get('sstype', TRUE);
        $sorder        = $this->input->get('sorder', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sc24display   = $this->input->get('sc24display', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sstock=".$sstock."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstype=".$sstype."&ssrdate=".$ssrdate."&serdate=".$serdate."&sorder=".$sorder."&splace=".$splace."&sc24display=".$sc24display;
        
    	
        $seq  = $this->input->get('seq', TRUE);
        $brand_list = $this->Brand_model->getList();
        $view = $this->Goods_model->getView($seq);
        $images_list = $this->Goods_img_model->getList($seq);
        
        if(count($view) == 0){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/goods?".$param);
        }
        
        $data = array(
            "seq" => $seq,
            "page" => $page,
            "required_mark" => $this->config->item('required_mark'),
            "brand_list" => $brand_list,
            "view" => $view,
            "param" => $param,
            "images_list" => $images_list,
            "purchase_kind" => $this->config->item('purchase_kind'),
            "purchase_class" => $this->config->item('purchase_class'),
            "purchase_method" => $this->config->item('purchase_method'),
            "goods_note" => $this->config->item('goods_note'),
            "goods_place" => $this->config->item('goods_place'),
            "goods_astype" => $this->config->item('goods_astype'),
            "purchase_type" => $this->config->item('purchase_type'),
        );
        
        $this->load->view('goods/modify', $data);
    }
    
    function modifyproc()
    {
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $ssrdate       = $this->input->get('ssrdate', TRUE);
        $serdate       = $this->input->get('serdate', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $sstype        = $this->input->get('sstype', TRUE);
        $sorder        = $this->input->get('sorder', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sc24display   = $this->input->get('sc24display', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sstock=".$sstock."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstype=".$sstype."&ssrdate=".$ssrdate."&serdate=".$serdate."&sorder=".$sorder."&splace=".$splace."&sc24display=".$sc24display;
        
        $seq = $this->input->post('seq', TRUE);
        $purchase_seq = trim($this->input->post('purchase_seq', TRUE));
        $price = trim($this->input->post('price', TRUE));
        $price = str_replace(',', '', $price);
        $brand_seq = trim($this->input->post('brand_seq', TRUE));
        $selfcode = trim($this->input->post('selfcode', TRUE));
        $asmemo = $this->input->post('asmemo', TRUE);
        $note = $this->input->post('note', TRUE);
        $guarantee = $this->input->post('guarantee', TRUE);
        if($guarantee) $guarantee = implode($guarantee, '|');
        $stock = $this->input->post('stock', TRUE);
        $represent = $this->input->post('represent', TRUE);
        $floor = $this->input->post('floor', TRUE);       
        $reason  = $this->input->post('reason', TRUE);
        
        //카페24정보추가
        $c24_product_no = $this->input->post('c24_product_no', TRUE); //카페24고유값
        $c24_display = $this->input->post('c24_display', TRUE); //진열상태
        $c24_category = $this->input->post('c24_category', TRUE); //상품분류 선택
        $c24_main = $this->input->post('c24_main', TRUE); //진열상태
        if($c24_main) $c24_main= '['.implode($c24_main, ',').']';
        $c24_summary_description = $this->input->post('c24_summary_description', TRUE); //상품 요약설명
        $c24_simple_description = $this->input->post('c24_simple_description'); //상품 간략설명
        $c24_description = $this->input->post('c24_description'); //상품 상세설명
        $c24_supply_price = $this->input->post('c24_supply_price', TRUE); //공급가
        $c24_supply_price = str_replace(',', '', $c24_supply_price);
        $c24_origin_place_value = $this->input->post('c24_origin_place_value', TRUE); //원산지(기타정보)
        $c24_tax_type = $this->input->post('c24_tax_type', TRUE); //과세구분
        $c24_tax_amount = $this->input->post('c24_tax_amount', TRUE); //과세율
        $c24_supply_price = $c24_supply_price ? $c24_supply_price : 0;
        $c24_tax_amount = $c24_tax_amount ? $c24_tax_amount : 0;
        if($c24_tax_type != 'A') $c24_tax_amount = 0;
        
        //유효성 체크
        if($seq == ''){
            doMsgLocation('잘못된 요청입니다. 시스템 관리자에게 문의 하십시요.', "http://".$_SERVER['HTTP_HOST']."/admn/goods");
        }
        
        //첨부파일
        $uploadFullDir = '/file/'.date('Y').'/'.date('m').'/'.date('d').'/';
        if(!is_dir($_SERVER['DOCUMENT_ROOT'].$uploadFullDir))
        {
            umask(0);
            mkdir($_SERVER['DOCUMENT_ROOT'].$uploadFullDir,0770,true);
        }
        
        $this->load->library('upload');
        
        $config = array();
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].$uploadFullDir;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        
        $file_db_arr = array();
        if(!empty($_FILES['image']['name'][0])){
            if($_FILES['image']['name'][0] != ''){
                $files = $_FILES;
                $cpt = count($_FILES['image']['name']);
                
                for($i=0; $i<$cpt; $i++)
                {
                    $filename = $files['image']['name'][$i];
                    $fileext = explode('.', $filename);
                    $fileext = end($fileext);
                    $_FILES['image']['name']= uniqid().'.'.$fileext;
                    $_FILES['image']['type']= $files['image']['type'][$i];
                    $_FILES['image']['tmp_name']= $files['image']['tmp_name'][$i];
                    $_FILES['image']['error']= $files['image']['error'][$i];
                    $_FILES['image']['size']= $files['image']['size'][$i];
                    
                    $this->upload->initialize($config);
                    if(!$this->upload->do_upload('image'))
                    {
                        echo $this->upload->display_errors(); exit();
                        doMsgLocation(strip_tags($this->upload->display_errors()),"http://".$_SERVER['HTTP_HOST']."/admn/goods/modify?seq=".$seq."&".$param);
                    }else{
                        $file_db_arr[] = array(
                            'filepath' => $uploadFullDir,
                            'filename' => $files['image']['name'][$i],
                            'realfilename' => $_FILES['image']['name']
                        );
                    }
                }
            }
        }
        
        //대표 이미지 업데이트
        if($represent){
            $this->Goods_model->representUpdate($seq, $represent);
        }
        
        if($purchase_seq){
            //매입정보
            $purchase_kind = trim($this->input->post('purchase_kind', TRUE));
            $purchase_modelname = trim($this->input->post('purchase_modelname', TRUE));
            $purchase_pdate = trim($this->input->post('purchase_pdate', TRUE));
            $purchase_pprice = trim($this->input->post('purchase_pprice', TRUE));
            $purchase_method = trim($this->input->post('purchase_method', TRUE));
            $purchase_class = trim($this->input->post('purchase_class', TRUE));
            $purchase_place = trim($this->input->post('purchase_place', TRUE));
            $purchase_reference = $this->input->post('purchase_reference', TRUE);
            if($purchase_reference) $purchase_reference = implode($purchase_reference, '|');

            //참고사항 기타 
            $reference_etc_chk = $this->input->post('purchase_reference_etc_chk', TRUE);
            $reference_etc_txt = $this->input->post('purchase_reference_etc_txt', TRUE);
            if($reference_etc_chk == '기타' && $reference_etc_txt){
                if($purchase_reference) $purchase_reference = $purchase_reference.'|';
                $purchase_reference .= '기타!@#^'.$reference_etc_txt;
            }

            $astype = $this->input->post('astype', TRUE);
            if($astype) $astype = implode($astype, '|');

            //astype 기타 
            $astype_etc_chk = $this->input->post('purchase_astype_etc_chk', TRUE);
            $astype_etc_txt = $this->input->post('purchase_astype_etc_txt', TRUE);
            if($astype_etc_chk == '기타'){
                if($astype) $astype = $astype.'|';
                $astype .= '기타!@#^'.$astype_etc_txt;
            }

            $purchase_asprice = $this->input->post('purchase_asprice', TRUE);
            $purchase_asprice = str_replace(',', '', $purchase_asprice);
            $purchase_type = $this->input->post('purchase_type', TRUE); 

            $data = array(
                'pdate' => $purchase_pdate,
                'kind' => $purchase_kind,
                'modelname' => $purchase_modelname,
                'method' => $purchase_method,
                'class' => $purchase_class,
                'reference' => $purchase_reference,
                'note' => $note,
                'astype' => $astype,
                'type' => $purchase_type,
                'asprice' => $purchase_asprice,
                'place' => $purchase_place,
                'goods_price' => $price
            );
            if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){
                $purchase_pprice = str_replace(',', '', $purchase_pprice);
                $data['pprice'] = $purchase_pprice;

                $purchase_exprice = $this->input->post('purchase_exprice', TRUE);
                $purchase_exprice = str_replace(',', '', $purchase_exprice);
                if(empty($purchase_exprice)) $purchase_exprice = 0;
                $data['exprice'] = $purchase_exprice;
            }
            $this->Purchase_model->updateList($data, $purchase_seq);

            //AS신청사유 수정
            $this->Purchase_model->updateReason($purchase_seq, $reason);
        }
        
        //brand_seq값이 있는 경우 매입쪽도 같이 연동이 되어야 함
        if($brand_seq && $purchase_seq){
            echo "
				<script src='/lib/jquery/jquery-1.12.0.min.js'></script>
				<script>
					$.ajax({
						type: 'POST',
				        url:'/admn/purchase/brandupdate',
				        data: 'purchase_seq=".$purchase_seq."&brand_seq=".$brand_seq."'
				    })
				</script>";
        }
        
        $data = array(
            'purchase_seq' => $purchase_seq,
            'brand_seq' => $brand_seq,
            'selfcode' => $selfcode,
            'asmemo' => $asmemo,
            'note' => $note,
            'floor' => $floor,
            'c24_display' => $c24_display,
            'c24_category' => $c24_category,
            'c24_main' => $c24_main,
            'c24_summary_description' => $c24_summary_description,
            'c24_simple_description' => $c24_simple_description,
            'c24_description' => $c24_description,
            'c24_supply_price' => $c24_supply_price,
            'c24_origin_place_value' => $c24_origin_place_value,
            'c24_tax_type' => $c24_tax_type,
            'c24_tax_amount' => $c24_tax_amount,
            'c24_product_no' => $c24_product_no,
            'c24_represent_thumb_seq' => ($represent ? $represent : 0) 
        );

        if($this->session->userdata('ADM_AUTH') != 3){
            $data['stock'] = $stock;
           $this->Purchase_model->updatePurchaseGoodsstock($stock,$purchase_seq);
        }

        //210812 재고없음으로 등록한 경우 stockcheck_date 날짜등록
        if($stock == 'N'){
            $data['stockcheck_date'] = date('Y-m-d H:i:s');
        }
        
        $this->Goods_model->updateList($data, $seq);
        
        //파일 db insert
        $totimgcnt = $this->Goods_img_model->getimgcnt($seq);
        $order = $totimgcnt;
        $represent = '';
        if($totimgcnt > 0) $represent = 'N';
        foreach($file_db_arr as $v){
            $order++;
            $represent = ($represent == '' ? 'Y' : 'N');
            $data = array(
                'represent' => $represent,
                'goods_seq' => $seq,
                'filepath' => $v['filepath'],
                'filename' => $v['filename'],
                'realfilename' => $v['realfilename'],
                'order' => $order
            );
            $this->Goods_img_model->insertList($data);
        }

        //tb_purchase > goods_price 수정
        if($price && $purchase_seq){
            $this->Purchase_model->updatePurchaseGoodsprice($price,$purchase_seq);
        }
        
        //cafe24
        if($c24_product_no){

            //해당 번호로 카페24에 존재하는지 여부 확인
            $ret = $this->pdApiCall('G', $seq);
            if(isset($ret['error'])){
                $this->pdApiCall('C', $seq); //카페24등록
            }else{
                $this->pdApiCall('U', $seq); //카페24수정
            }
            
        }else{
            //개발시점 이후에 등록된 데이터만 수정시 등록으로 처리
            if((int)$seq > 7731){
                $this->pdApiCall('C', $seq); //카페24등록
            }
        }
        
        doMsgLocation('수정 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/goods?".$param);
        
    }
    
    function delimage(){
        $seq  = $this->input->post('seq', TRUE);
        $images_view = $this->Goods_img_model->getView($seq);
        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.$images_view->filepath.$images_view->realfilename); //파일삭제
        $this->Goods_img_model->deleteimg($seq);
    }
    
    function copyproc()
    {
        $seq = $this->input->get('seq', TRUE);
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $ssrdate       = $this->input->get('ssrdate', TRUE);
        $serdate       = $this->input->get('serdate', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $sstype        = $this->input->get('sstype', TRUE);
        $sorder        = $this->input->get('sorder', TRUE);
        $splace        = $this->input->get('splace', TRUE);
        $sc24display   = $this->input->get('sc24display', TRUE);
        
        $param = "page=".$page."&ssdate=".$ssdate."&sedate=".$sedate."&sstock=".$sstock."&stype=".$stype."&skeyword=".$skeyword."&sbrand=".$sbrand."&skind=".$skind."&sstype=".$sstype."&ssrdate=".$ssrdate."&serdate=".$serdate."&sorder=".$sorder."&splace=".$splace."&sc24display=".$sc24display;
        
        if($seq){
            $this->Goods_model->copy($seq);
            doMsgLocation('복사 되었습니다.', "http://".$_SERVER['HTTP_HOST']."/admn/goods/?$param");
        }
    }
    
    function excel()
    {

        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
        }
        
        ini_set("memory_limit","512M");
        $condition = array();
        $page          = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $ssdate        = $this->input->get('ssdate', TRUE);
        $sedate        = $this->input->get('sedate', TRUE);
        $ssrdate       = $this->input->get('ssrdate', TRUE);
        $serdate       = $this->input->get('serdate', TRUE);
        $sstock        = $this->input->get('sstock', TRUE);
        $stype         = $this->input->get('stype', TRUE);
        $skeyword      = $this->input->get('skeyword', TRUE);
        $sbrand        = $this->input->get('sbrand', TRUE);
        $skind         = $this->input->get('skind', TRUE);
        $sstype        = $this->input->get('sstype', TRUE);
        $sorder        = $this->input->get('sorder', TRUE);
        $sc24display   = $this->input->get('sc24display', TRUE);
        
        if($ssdate) $condition['ssdate'] = $ssdate;
        if($sedate) $condition['sedate'] = $sedate;
        if($sstock) $condition['sstock'] = $sstock;
        if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        if($sbrand) $condition['sbrand'] = $sbrand;
        if($skind) $condition['skind'] = $skind;
        if($sstype) $condition['sstype'] = $sstype;
        if($sorder) $condition['sorder'] = $sorder;
        if($sc24display) $condition['sc24display'] = $sc24display;
        
        $board_list = $this->Goods_model->getList($condition);
        
        if(count($board_list) == 0){
            doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
            return true;
        }
        
        $filename = date('Y-m-d').'_상품리스트_'.count($board_list).'건';
        
        $this->load->library("PHPExcel");
        
        $objPHPExcel = new PHPExcel();
        
        if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", '상품번호')
            ->setCellValue("B1", '상품코드')
            ->setCellValue("C1", '브랜드')
            ->setCellValue("D1", '매입일자')
            ->setCellValue("E1", '종류')
            ->setCellValue("F1", '모델명')
            ->setCellValue("G1", '판매예정금액')
            ->setCellValue("H1", '매입가격')
            ->setCellValue("I1", '재고')
            ->setCellValue("J1", '비고');
            
            $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array( 'rgb' => 'f5ebed' )
            ));
            $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
                );
        }else{
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", '상품번호')
            ->setCellValue("B1", '상품코드')
            ->setCellValue("C1", '브랜드')
            ->setCellValue("D1", '매입일자')
            ->setCellValue("E1", '종류')
            ->setCellValue("F1", '모델명')
            ->setCellValue("G1", '판매예정금액')
            ->setCellValue("H1", '재고')
            ->setCellValue("I1", '비고');
            
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
        }
        
        foreach($board_list as $k => $v){
            $seq = $v->seq;
            $pcode = $v->pcode;
            $brandname = $v->brandname;
            $pdate = $v->pdate;
            if($pdate){
                $pdate = strtotime($pdate);
                $pdate = date('Y-m-d', $pdate);
            }else{
                $pdate = '';
            }
            $kind = $v->kind;
            $modelname = $v->modelname;
            $price = $v->price;
            $price = number_format($price);
            $stock = $v->stock;
            $stock = ($stock=='Y') ? '있음' : '없음';
            $note = $v->note;
            $pprice = $v->pprice;
            
            if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A".($k+2), $seq)
                ->setCellValue("B".($k+2), $pcode)
                ->setCellValue("C".($k+2), $brandname)
                ->setCellValue("D".($k+2), $pdate)
                ->setCellValue("E".($k+2), $kind)
                ->setCellValue("F".($k+2), $modelname)
                ->setCellValue("G".($k+2), $price)
                ->setCellValue("H".($k+2), number_format($pprice))
                ->setCellValue("I".($k+2), $stock)
                ->setCellValue("J".($k+2), $note);
                $objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":J".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }else{
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A".($k+2), $seq)
                ->setCellValue("B".($k+2), $pcode)
                ->setCellValue("C".($k+2), $brandname)
                ->setCellValue("D".($k+2), $pdate)
                ->setCellValue("E".($k+2), $kind)
                ->setCellValue("F".($k+2), $modelname)
                ->setCellValue("G".($k+2), $price)
                ->setCellValue("H".($k+2), $stock)
                ->setCellValue("I".($k+2), $note);
                $objPHPExcel->getActiveSheet()->getStyle("A".($k+2).":I".($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
        }
        
        if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            $objPHPExcel->getActiveSheet()->getStyle('A1:J'.($k+2))->getFont()->setSize(10);
        }else{
            $objPHPExcel->getActiveSheet()->getStyle('A1:I'.($k+2))->getFont()->setSize(10);
        }
        
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
            $info = $this->Purchase_model->getInfoFromCode($pcode);
            
            $result = array();
            //상품테이블에 해당 코드로 이미 등록된것이 있는지 확인
            $purchase_seq = isset($info->seq) ? $info->seq : '';
            if($purchase_seq){
                $goods_info = $this->Goods_model->getSeqGoods($purchase_seq);
                $goods_seq = isset($goods_info->seq) ? $goods_info->seq : '';
                if($goods_seq == '' || $seq == $goods_seq){
                    $result = array('result' => 'ok', 'data' => $info);
                }else{
                    //이미 등록된 데이터가 있는 경우
                    $result = array('result' => 'already', 'data' => $goods_seq);
                }
            }else{
                //해당코드로 인해 조회가 안되는 경우
                $result = array('result' => 'notfound');
            }
            
            echo json_encode($result);
        }
    }
    
    function imageOrderChange(){
        $orderseqs = $this->input->post('orderseqs', TRUE);
        $ordernums = $this->input->post('ordernums', TRUE);
        
        $orderseqs_arr = explode(',', $orderseqs);
        $ordernums_arr = explode(',', $ordernums);
        
        foreach($orderseqs_arr as $k => $v){
            $this->Goods_img_model->orderUpdate($v, $ordernums_arr[$k]);
        }
    }
    
    function brandupdate(){
        $purchase_seq = $this->input->post('purchase_seq', TRUE);
        $brand_seq = $this->input->post('pbrand_seq', TRUE);
        $this->Goods_model->brandupdate($purchase_seq, $brand_seq);
    }
    
    function getAccessToken()
    {
        /*
         1단계
         https://wiselux.cafe24api.com/api/v2/oauth/authorize?response_type=code&client_id=8fsBDx2e8EQnOYI22zfAyH&redirect_uri=https://wiselux.co.kr/&scope=mall.read_application,mall.write_application,mall.read_category
         2단계
         $sThisUrl = 'https://wiselux.co.kr/';
         $sCode = 'gDNlqB8yPBJkUqGwLfFI3D';// 1단계 응답의 'code'
         $aFields = array(
         'grant_type'   => 'authorization_code',
         'code'         => $sCode,
         'redirect_uri' => $sThisUrl
         );
         $oCurl = curl_init();
         curl_setopt_array($oCurl, array(
         CURLOPT_URL            => 'https://wiselux.cafe24api.com/api/v2/oauth/token',
         CURLOPT_POSTFIELDS     => http_build_query($aFields),
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER     => array(
         'Authorization: Basic ' . base64_encode($sClientId . ':' . $sClientSecret)
         )
         ));
         $sResponse = curl_exec($oCurl);
         curl_close($oCurl);
         print_r($sResponse);
         exit;
         */
        
        //DB에 저장된 토큰정보를 가져온다.
        $tokeninfo = $this->Cafe24api_model->getToken();
        $access_token = $tokeninfo->access_token;
        $refresh_token = $tokeninfo->refresh_token;
        $expires_at = $tokeninfo->expires_at;
        
        //만료 되었으면 새롭게 발급받는다.
        if(strtotime($expires_at) <= strtotime('+10 minutes')){
            
            //키가 만료 되었으면 refresh
            $aFields = array(
                'grant_type'   => 'refresh_token',
                'refresh_token' => $refresh_token
            );
            $oCurl = curl_init();
            curl_setopt_array($oCurl, array(
                CURLOPT_URL            => 'https://wiselux.cafe24api.com/api/v2/oauth/token',
                CURLOPT_POSTFIELDS     => http_build_query($aFields),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => array(
                    'Authorization: Basic ' . base64_encode($this->sClientId . ':' . $this->sClientSecret)
                )
            ));
            $sResponse = curl_exec($oCurl);
            curl_close($oCurl);
            $sResponse = json_decode($sResponse, true);
            
            $access_token = $sResponse['access_token'];
            $refresh_token = $sResponse['refresh_token'];
            $expires_at = $sResponse['expires_at'];
            $expires_at = date('Y-m-d H:i:s', strtotime($expires_at));
            $refresh_token_expires_at = $sResponse['refresh_token_expires_at'];
            $refresh_token_expires_at = date('Y-m-d H:i:s', strtotime($refresh_token_expires_at));
            
            $this->Cafe24api_model->setToken($access_token, $refresh_token, $expires_at, $refresh_token_expires_at);
        }
        
        return $access_token;
        
    }
    
    function pdApiCall($type, $goods_seq)
    {
        $access_token = $this->getAccessToken();
        
        $product_info = $this->Cafe24api_model->getProductInfo($goods_seq);
        
        $represent_thumb_seq = $product_info->c24_represent_thumb_seq;
        $product_no = $product_info->c24_product_no;
        $display = $product_info->c24_display; //T:진열함, F:진열안함
        $add_category_no = $product_info->c24_category;
        $main = $product_info->c24_main; //6,5,4,3,2 (메인 진열)
        $summary_description = $product_info->c24_summary_description; //255자
        $summary_description = addslashes($summary_description);
        $simple_description = $product_info->c24_simple_description;
        $simple_description = addslashes($simple_description);
        $simple_description = preg_replace('/\r\n|\r|\n/','\n',$simple_description);
        $description = $product_info->c24_description;
        $description = str_replace('=""', '', $description);
        $description = preg_replace('/\r\n|\r|\n/','<br/>',$description);
        // $description = nl2br($description);
        // $description = html_entity_decode($description);
        // $description = addslashes($description);
        // $description = str_replace('%5C%22', '%22', urlencode($description));
        // $description = urldecode($description);
        // $description = str_replace('\<', '<', $description);
        // $description = preg_replace('/\r\n|\r|\n/','',$description);
        // $description = htmlspecialchars_decode($description);
        // $description = addslashes($description);
        // $description = htmlentities($description);
        // $description = html_entity_decode($description);
        // $description = addslashes($description);
        // $description = json_encode($description);
        // $description = json_encode($description, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        // $description = json_decode($description);
        // $description = html_entity_decode(preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($description)), null, 'UTF-8');
        $description = str_replace("'", '&apos;', $description);
        $description = addslashes($description);
        // $description = json_encode($description, JSON_FORCE_OBJECT);
        $supply_price = $product_info->c24_supply_price; //공급가
        $price = $product_info->price2; //판매가
        $origin_place_value = $product_info->c24_origin_place_value; //원산지기타정보
        $selling = $product_info->stock=='Y' ? 'T' : 'F'; //T:판매함, F:판매안함
        $product_name = $product_info->modelname; //상품명
        $custom_product_code = $product_info->pcode; //상품코드
        $retail_price = $supply_price; //소비자가
        $tax_type = $product_info->c24_tax_type; //과세구분 (A : 과세상품 / B : 면세 상품 / C : 영세상품)
        $tax_amount = $product_info->c24_tax_amount; //과세율
        $additional_image = '';
        $detail_image = '';
        $list_image = '';
        $tiny_image = '';
        $small_image = '';
        $folderdate = date('Ymd');
        //$product_name = addslashes($product_name);

        switch($type){
            case "C":
                
                /*------------------------------------------------------------
                 * 등록
                 *------------------------------------------------------------*/
                
                if($product_name && $supply_price){
                
	                //ftp 이미지 파일전송
	                $source_files = $this->Cafe24api_model->getGoodsImg($goods_seq);
	                
	                if(count($source_files) > 0){
	                    
	                    if(!($fc = ftp_connect($this->cafe24_ftp_host, "21"))){
	                        doMsgLocationHB('이미지전송 ftp 연결에 실패 했습니다.');
	                    }
	                    if(!ftp_login($fc, $this->cafe24_ftp_id, $this->cafe24_ftp_pw)){
	                        doMsgLocationHB('이미지전송 ftp 로그인에 실패 했습니다.');
	                    }
	                    ftp_pasv($fc, true);
	                    @ftp_mkdir($fc, "/web/product/big/".$folderdate);
	                    @ftp_mkdir($fc, "/web/product/medium/".$folderdate);
	                    @ftp_mkdir($fc, "/web/product/tiny/".$folderdate);
	                    @ftp_mkdir($fc, "/web/product/small/".$folderdate);
	                    @ftp_mkdir($fc, "/web/product/extra/big/".$folderdate);
	                    @ftp_mkdir($fc, "/web/product/extra/medium/".$folderdate);
	                    @ftp_mkdir($fc, "/web/product/extra/small/".$folderdate);
	                    
	                    foreach($source_files as $k => $v){
	                        $source_file = $_SERVER['DOCUMENT_ROOT'].$v->filepath.$v->realfilename;
	                        if(file_exists($source_file)){
	                            if(!ftp_put($fc, "/web/product/tiny/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
	                                doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
	                            }
	                            if(!ftp_put($fc, "/web/product/big/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
	                                doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
	                            }
	                            if(!ftp_put($fc, "/web/product/medium/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
	                                doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
	                            }
	                            if(!ftp_put($fc, "/web/product/small/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
	                                doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
	                            }
	                            if(!ftp_put($fc, "/web/product/extra/big/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
	                                doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
	                            }
	                            if(!ftp_put($fc, "/web/product/extra/medium/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
	                                doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
	                            }
	                            if(!ftp_put($fc, "/web/product/extra/small/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
	                                doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
	                            }
	                            
	                            if($k == 0){
	                                $detail_image .= '"/web/product/big/'.$folderdate.'/'.$v->realfilename.'"';
	                                $list_image .= '"/web/product/medium/'.$folderdate.'/'.$v->realfilename.'"';
	                                $tiny_image .= '"/web/product/tiny/'.$folderdate.'/'.$v->realfilename.'"';
	                                $small_image .= '"/web/product/small/'.$folderdate.'/'.$v->realfilename.'"';
	                            }else{
	                            	if($additional_image != '') $additional_image = $additional_image.',';
	                            	$additional_image .= '"'.$folderdate.'/'.$v->realfilename.'"';	
	                            }
	                        }
	                    }
	                    ftp_close($fc);
	                    if($additional_image) $additional_image = '['.$additional_image.']';
	                }
	                
                    if(!$additional_image) $additional_image = '""';
                    if(!$detail_image) $detail_image = '""';
                    if(!$list_image) $list_image = '""';
                    if(!$tiny_image) $tiny_image = '""';
                    if(!$small_image) $small_image = '""';
                    $add_category_no_str = '';
                    if($add_category_no){
                        $add_category_no_str = '"add_category_no": '.$add_category_no.',';   
                    }

	                $data = '{
	                    "shop_no": 1,
	                    "request": {
	                        "display": "'.$display.'",
	                        '.$add_category_no_str.'
	                        "main": '.$main.',
	                        "summary_description": "'.$summary_description.'",
	                        "simple_description": "'.$simple_description.'",
	                        "description": "'.$description.'",
	                        "supply_price": '.$supply_price.',
	                        "price": "'.$price.'",
	                        "retail_price": "'.$retail_price.'",
	                        "origin_classification": "E",
	                        "origin_place_no": 1800,
	                        "origin_place_value": "'.$origin_place_value.'",
	                        "selling": "'.$selling.'",
	                        "product_name": "'.$product_name.'",
	                        "model_name": "'.$product_name.'",
	                        "custom_product_code": "'.$custom_product_code.'",
	                        "additional_image": '.$additional_image.',
	                        "tax_type": "'.$tax_type.'",
	                        "tax_amount": "'.$tax_amount.'",
	                        "detail_image": '.$detail_image.',
	                        "list_image": '.$list_image.',
	                        "tiny_image": '.$tiny_image.',
	                        "small_image": '.$small_image.'
	                    }
	                }';
	                
	                $curl = curl_init();
	                
	                curl_setopt_array($curl, array(
	                    CURLOPT_URL => 'https://wiselux.cafe24api.com/api/v2/admin/products',
	                    CURLOPT_RETURNTRANSFER => true,
	                    CURLOPT_CUSTOMREQUEST => 'POST',
	                    CURLOPT_POSTFIELDS => $data,
	                    CURLOPT_HTTPHEADER => array(
	                        'Authorization: Bearer '.$access_token,
	                        'Content-Type: application/json'
	                    ),
	                ));
	                
	                $response = curl_exec($curl);
	                $response = json_decode($response, true);
	                if(isset($response['error'])){
	                    doMsgLocationHB('[카페24등록실패] '.$response['error']['message']);
                        $fn = '/home/wiselux/www/log/cafe24_i_errlog';
                        $fp = fopen($fn, "a");
                        fwrite($fp, date('Y-m-d H:i:s').PHP_EOL.$response['error']['message'].PHP_EOL.$data.PHP_EOL.PHP_EOL);
                        fclose($fp);
	                }
	                $product_no = isset($response['product']['product_no']) ? $response['product']['product_no'] : '';

	                $fn = '/home/wiselux/www/log/cafe24_test_log';
                    $fp = fopen($fn, "a");
                    fwrite($fp, date('Y-m-d H:i:s').' | '.$product_no.PHP_EOL);
                    fclose($fp);

	                if($product_no){
	                    //재고관리 사용 등록
	                    curl_setopt_array($curl, array(
	                        CURLOPT_URL => 'https://wiselux.cafe24api.com/api/v2/admin/products/'.$product_no.'/variants',
	                        CURLOPT_RETURNTRANSFER => true,
	                        CURLOPT_CUSTOMREQUEST => 'GET',
	                        CURLOPT_HTTPHEADER => array(
	                            'Authorization: Bearer '.$access_token,
	                            'Content-Type: application/json'
	                        ),
	                    ));
	                    $response_i = curl_exec($curl);
	                    $response_i = json_decode($response_i, true);
	                    $variants_code = isset($response_i['variants'][0]['variant_code']) ? $response_i['variants'][0]['variant_code'] : '';
	                    
	                    if($variants_code){
	                        $data = '{
	                            "shop_no": 1,
	                            "request": {
	                                "use_inventory": "T",
	                                "important_inventory": "A",
	                                "inventory_control_type": "A",
	                                "display_soldout": "T",
	                                "quantity": 1,
	                                "safety_inventory": 0
	                            }
	                        }';
	                        
	                        curl_setopt_array($curl, array(
	                            CURLOPT_URL => 'https://wiselux.cafe24api.com/api/v2/admin/products/'.$product_no.'/variants/'.$variants_code.'/inventories',
	                            CURLOPT_RETURNTRANSFER => true,
	                            CURLOPT_CUSTOMREQUEST => 'PUT',
	                            CURLOPT_POSTFIELDS => $data,
	                            CURLOPT_HTTPHEADER => array(
	                                'Authorization: Bearer '.$access_token,
	                                'Content-Type: application/json'
	                            ),
	                        ));
	                        curl_exec($curl);
	                    }
	                    
	                    $this->Cafe24api_model->setProductNo($goods_seq, $product_no);
	                }
	                
	                curl_close($curl);
                }
                
                break;
                
            case "U":
                
                /*------------------------------------------------------------
                 * 수정
                 *------------------------------------------------------------*/
                
                if($product_no && $product_name && $supply_price){
                    
                    //ftp 이미지 파일전송
                    $source_files = $this->Cafe24api_model->getGoodsImg($goods_seq);
                    
                    if(count($source_files) > 0){
                        
                        if(!($fc = ftp_connect($this->cafe24_ftp_host, "21"))){
                            doMsgLocationHB('이미지전송 ftp 연결에 실패 했습니다.');
                        }
                        if(!ftp_login($fc, $this->cafe24_ftp_id, $this->cafe24_ftp_pw)){
                            doMsgLocationHB('이미지전송 ftp 로그인에 실패 했습니다.');
                        }
                        ftp_pasv($fc, true);
                        @ftp_mkdir($fc, "/web/product/big/".$folderdate);
                        @ftp_mkdir($fc, "/web/product/medium/".$folderdate);
                        @ftp_mkdir($fc, "/web/product/tiny/".$folderdate);
                        @ftp_mkdir($fc, "/web/product/small/".$folderdate);
                        @ftp_mkdir($fc, "/web/product/extra/big/".$folderdate);
                        @ftp_mkdir($fc, "/web/product/extra/medium/".$folderdate);
                        @ftp_mkdir($fc, "/web/product/extra/small/".$folderdate);
                        
                        foreach($source_files as $k => $v){
                            $source_file = $_SERVER['DOCUMENT_ROOT'].$v->filepath.$v->realfilename;
                            if(file_exists($source_file)){
                                if(!ftp_put($fc, "/web/product/tiny/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
                                    doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
                                }
                                if(!ftp_put($fc, "/web/product/big/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
                                    doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
                                }
                                if(!ftp_put($fc, "/web/product/medium/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
                                    doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
                                }
                                if(!ftp_put($fc, "/web/product/small/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
                                    doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
                                }
                                if(!ftp_put($fc, "/web/product/extra/big/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
                                    doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
                                }
                                if(!ftp_put($fc, "/web/product/extra/medium/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
                                    doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
                                }
                                if(!ftp_put($fc, "/web/product/extra/small/".$folderdate.'/'.$v->realfilename, $source_file, FTP_BINARY)){
                                    doMsgLocationHB('파일을 지정한 디렉토리로 복사 하는 데 실패했습니다.');
                                }
                                
                                if($represent_thumb_seq == $v->seq || ($represent_thumb_seq == '' && $k == 0)){
                                    $detail_image .= '"/web/product/big/'.$folderdate.'/'.$v->realfilename.'"';
                                    $list_image .= '"/web/product/medium/'.$folderdate.'/'.$v->realfilename.'"';
                                    $tiny_image .= '"/web/product/tiny/'.$folderdate.'/'.$v->realfilename.'"';
                                    $small_image .= '"/web/product/small/'.$folderdate.'/'.$v->realfilename.'"';
                                }else{
                                	if($additional_image != '') $additional_image = $additional_image.',';
                                	$additional_image .= '"'.$folderdate.'/'.$v->realfilename.'"';
                                }
                            }
                        }
                        ftp_close($fc);
                        if($additional_image) $additional_image = '['.$additional_image.']';
                        
                    }

                    if(!$additional_image) $additional_image = '""';
                    if(!$detail_image) $detail_image = '""';
                    if(!$list_image) $list_image = '""';
                    if(!$tiny_image) $tiny_image = '""';
                    if(!$small_image) $small_image = '""';
                    $add_category_no_str = '';
                    if($add_category_no){
                        $add_category_no_str = '"add_category_no": '.$add_category_no.',';   
                    }
                    
                    $data = '{
                        "shop_no": 1,
                        "request": {
                            "display": "'.$display.'",
                            '.$add_category_no_str.'
                            "main": '.$main.',
                            "summary_description": "'.$summary_description.'",
                            "simple_description": "'.$simple_description.'",
                            "description": "'.$description.'",
                            "supply_price": '.$supply_price.',
                            "price": "'.$price.'",
                            "retail_price": "'.$retail_price.'",
                            "origin_classification": "E",
                            "origin_place_no": 1800,
                            "origin_place_value": "'.$origin_place_value.'",
                            "selling": "'.$selling.'",
                            "product_name": "'.$product_name.'",
                            "model_name": "'.$product_name.'",
                            "custom_product_code": "'.$custom_product_code.'",
                            "additional_image": '.$additional_image.',
                            "tax_type": "'.$tax_type.'",
                            "tax_amount": "'.$tax_amount.'",
                            "detail_image": '.$detail_image.',
                            "list_image": '.$list_image.',
                            "tiny_image": '.$tiny_image.',
                            "small_image": '.$small_image.',
                            "image_upload_type": "C"
                        }
                    }';
                    
                    $curl = curl_init();
                    
                    //메인진열을 우선 제거해야함
                    $main_arr = array('2','3','4','5','6');
                    foreach($main_arr as $v){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://wiselux.cafe24api.com/api/v2/admin/mains/'.$v.'/products/'.$product_no,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => 'DELETE',
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: Bearer '.$access_token,
                                'Content-Type: application/json'
                            ),
                        ));
                        curl_exec($curl);
                    }

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://wiselux.cafe24api.com/api/v2/admin/products/'.$product_no,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'PUT',
                        CURLOPT_POSTFIELDS => $data,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer '.$access_token,
                            'Content-Type: application/json'
                        )
                    ));
                    
                    $response = curl_exec($curl);
                    //echo $response; exit();
                    $response = json_decode($response, true);
                    if(isset($response['error'])){
                        doMsgLocationHB('[카페24수정실패] '.$response['error']['message']);
                        $fn = '/home/wiselux/www/log/cafe24_u_errlog';
                        $fp = fopen($fn, "a");
                        fwrite($fp, date('Y-m-d H:i:s').PHP_EOL.$response['error']['message'].PHP_EOL.$data.PHP_EOL.PHP_EOL);
                        fclose($fp);
                    }

                    // $fn = '/home/wiselux/www/log/cafe24_data_log';
                    // $fp = fopen($fn, "a");
                    // fwrite($fp, date('Y-m-d H:i:s').PHP_EOL.$product_name.PHP_EOL.$data.PHP_EOL.PHP_EOL);
                    // fclose($fp);
                    
                    curl_close($curl);
                    
                }
                
                break;
                
            case "D":
                
                /*------------------------------------------------------------
                 * 삭제
                 *------------------------------------------------------------*/
                
                if($product_no){
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://wiselux.cafe24api.com/api/v2/admin/products/'.$product_no,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'DELETE',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer '.$access_token,
                            'Content-Type: application/json'
                        ),
                    ));
                    
                    curl_exec($curl);
                    curl_close($curl);
                }
                
                break;
                
            case "G":
                
                /*------------------------------------------------------------
                 * 조회
                 *------------------------------------------------------------*/
                
                if($product_no){
                    
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://wiselux.cafe24api.com/api/v2/admin/products/'.$product_no,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer '.$access_token,
                            'Content-Type: application/json'
                        ),
                    ));
                    
                    $response = curl_exec($curl);
                    curl_close($curl);

                    $response = json_decode($response, true);
                    return $response;
                    
                }
                
                break;
        }
    }
    
    function chkRefreshToken()
    {
        /*
         * refresh_token이 만료 되면 처음부터 재발급 받아야 되므로 refresh_token 만료에 대해서는 서버에서 crontab으로 돌아가면서 수행 (1일에 새벽에 한번 돌면 됨)
         * 0 5 * * * curl http://wiseluxserver.cafe24.com/admn/cafe24api/chkRefreshToken <-- php로 수정(로그인문제)
         */
        $tokeninfo = $this->Cafe24api_model->getToken();
        $refresh_token_expires_at = $tokeninfo->refresh_token_expires_at;
        
        if(strtotime($refresh_token_expires_at) <= strtotime('-2 Days')){
            $this->getAccessToken(); //재발급
        }
    }
    
    function getApiCategory(){
        $access_token = $this->getAccessToken();
        $oCurl = curl_init();
        $offset = 0;

        $this->db->query("delete from tb_cafe24_category");
        
        while(true){
            $sEndPointUrl = 'https://wiselux.cafe24api.com/api/v2/admin/categories?shop_no=1&limit=100&offset='.$offset;
            curl_setopt_array($oCurl, array(
                CURLOPT_URL            => $sEndPointUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => array(
                    'Authorization: Bearer ' . $access_token,
                    'Content-Type: application/json'
                )
            ));
            $sResponse = curl_exec($oCurl);
            $sResponse = json_decode($sResponse, true);
            
            if(count($sResponse['categories']) == 0) break;
            
            foreach($sResponse['categories'] as $v){
                $category_no = $v['category_no'];
                $parent_category_no = $v['parent_category_no'];
                $category_depth = $v['category_depth'];
                $category_name = $v['category_name'];
                $category_name = addslashes($category_name);
                
                $this->Cafe24api_model->setCategory($category_no, $parent_category_no, $category_depth, $category_name);
            }
            $offset = $offset + 100;
        }
        curl_close($oCurl);
    }
    
    function getApiBrand(){
        $access_token = $this->getAccessToken();
        $oCurl = curl_init();
        $offset = 0;
        while(true){
            $sEndPointUrl = 'https://wiselux.cafe24api.com/api/v2/admin/brands?shop_no=1&limit=100&offset='.$offset;
            curl_setopt_array($oCurl, array(
                CURLOPT_URL            => $sEndPointUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => array(
                    'Authorization: Bearer ' . $access_token,
                    'Content-Type: application/json'
                )
            ));
            $sResponse = curl_exec($oCurl);
            $sResponse = json_decode($sResponse, true);
            if(count($sResponse['brands']) == 0) break;
            
            foreach($sResponse['brands'] as $v){
                $brand_code = $v['brand_code'];
                $brand_name = $v['brand_name'];
                $brand_name = addslashes($brand_name);
                $brand_name = htmlspecialchars_decode($brand_name);
                
                $this->Cafe24api_model->setBrand($brand_code, $brand_name);
            }
            $offset = $offset + 100;
        }
        curl_close($oCurl);
    }
    
    function getCategory(){
        $depth = $this->input->post('depth', TRUE);
        $no = $this->input->post('no', TRUE);
        $r = $this->Cafe24api_model->getCategory($depth, $no);
        echo json_encode($r);
    }
    
    function getCategoryStr(){
        $no = $this->input->post('no', TRUE);
        $str = '';
        $r = $this->Cafe24api_model->getCategoryStr($no);
        $str = $r->category_name;
        if($r->parent_category_no != '1'){
            $r = $this->Cafe24api_model->getCategoryStr($r->parent_category_no);
            $str .= ' > '.$r->category_name;
        }
        if($r->parent_category_no != '1'){
            $r = $this->Cafe24api_model->getCategoryStr($r->parent_category_no);
            $str .= ' > '.$r->category_name;
        }
        echo $str;
    }

    function delc24key(){
        $seq = $this->input->post('seq', TRUE);
        if($seq) $this->Goods_model->delc24key($seq);
    }
}
