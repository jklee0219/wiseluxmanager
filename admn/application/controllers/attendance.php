<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        include $_SERVER['DOCUMENT_ROOT'].'/comm/func.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/commvar.php';
        include $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/loginchk.php';
        $this->load->model('Attendance_model');
        
        // 접속체크
        $this->load->model('Access_model');
        $this->Access_model->gc_ip();
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
    
    // 메인 리스트
    public function index()
    {
        $condition = array();
        $page      = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sdate     = $this->input->get('sdate', TRUE);
        $edate     = $this->input->get('edate', TRUE);
        $att_type  = $this->input->get('att_type', TRUE);
        $stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        $scale     = 20;
        
        if($sdate) $condition['sdate'] = $sdate;
        if($edate) $condition['edate'] = $edate;
        if($att_type) $condition['att_type'] = $att_type;
        if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        
        $board_cnt = $this->Attendance_model->getListCnt($condition);
        
        // 통계
        $annualCnt = $this->Attendance_model->getAnnualCnt();
        $earlyCnt = $this->Attendance_model->getEarlyCnt();
        
        // 페이징
        $total_page  = 0;
        if($board_cnt > 0) $total_page = ceil($board_cnt/$scale);
        $first       = $scale * ($page - 1);
        $total_block = ceil($total_page / 15);
        $block       = ceil($page / 15);
        $first_page  = ($block - 1) * 15;
        $last_page   = $total_block <= $block ? $total_page : $block * 15;
        $go_page     = $first_page + 1;
        
        $param2 = "&sdate=".$sdate."&edate=".$edate."&att_type=".$att_type."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        $board_list = $this->Attendance_model->getList($condition, $scale, $first);
        
        // 페이징 HTML 생성
        $paging_html = '';
        if($scale < $board_cnt)
        {
            $paging_html = '<ul class="pagination pagination-sm">';
            if($block > 1)
            {
                $paging_html .= '<li><a href="/admn/attendance?page='.($go_page-15).$param2.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }
            
            for($go_page; $go_page <= $last_page; $go_page++)
            {
                if($page == $go_page)
                {
                    $paging_html .= '<li class="active"><a>'.$go_page.'</a></li>';
                } else
                {
                    $paging_html .= '<li><a href="/admn/attendance?page='.$go_page.$param2.'">'.$go_page.'</a></li>';
                }
            }
            
            if($block < $total_block) {
                $paging_html .= '<li><a href="/admn/attendance?page='.$go_page.$param2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            }
            $paging_html .= '</ul>';
        }

        // 달력용 전체 리스트
        $alllist = $this->Attendance_model->getAllList();
        
        $data = array(
            "sdate" => $sdate,
            "edate" => $edate,
            "att_type" => $att_type,
            "stype" => $stype,
            "skeyword" => $skeyword,
            "scale" => $scale,
            "page" => $page,
            "board_list" => $board_list,
            "paging_html" => $paging_html,
            "param" => $param,
            "annualCnt" => $annualCnt,
            "earlyCnt" => $earlyCnt,
            'alllist' => $alllist,
        );
        
        $this->load->view('attendance/list', $data);
    }
    
    // 등록 페이지
    function write()
    {
        $page     = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sdate    = $this->input->get('sdate', TRUE);
        $edate    = $this->input->get('edate', TRUE);
        $att_type = $this->input->get('att_type', TRUE);
        $stype    = $this->input->get('stype', TRUE);
        $skeyword = $this->input->get('skeyword', TRUE);
        
        $param2 = "&sdate=".$sdate."&edate=".$edate."&att_type=".$att_type."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        // 근무중인 직원 목록
        $worker_list = $this->Attendance_model->getWorkerList();
        
        $data = array(
            "param" => $param,
            "worker_list" => $worker_list,
        );
        
        $this->load->view('attendance/write', $data);
    }
    
    // 등록 처리
    function writeproc()
    {
        $page     = $this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1;
        $sdate    = $this->input->post('sdate', TRUE);
        $edate    = $this->input->post('edate', TRUE);
        $att_type_filter = $this->input->post('att_type_filter', TRUE);
        $stype    = $this->input->post('stype', TRUE);
        $skeyword = $this->input->post('skeyword', TRUE);
        
        $param2 = "&sdate=".$sdate."&edate=".$edate."&att_type=".$att_type_filter."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        $worker_id   = $this->input->post('worker_id', TRUE);
        $att_date    = $this->input->post('att_date', TRUE);
        $att_type    = $this->input->post('att_type', TRUE);
        $note        = $this->input->post('note', TRUE);
        
        // 유효성 검사
        if(!$worker_id || !$att_date || !$att_type) {
            doMsgBack('필수 항목을 입력해주세요.');
            return;
        }
        
        // 중복 체크
        $duplicate = $this->Attendance_model->checkDuplicate($worker_id, $att_date);
        if($duplicate > 0) {
            doMsgBack('해당 날짜에 이미 등록된 직원입니다.');
            return;
        }
        
        // 직원 정보 조회
        $this->load->model('Workers_model');
        $worker_info = $this->Workers_model->getList(array('sid' => $worker_id));
        if(count($worker_info) == 0) {
            doMsgBack('존재하지 않는 직원입니다.');
            return;
        }
        $worker = $worker_info[0];
        
        $data = array(
            'worker_id' => $worker_id,
            'worker_name' => $worker->name,
            'worker_class' => $worker->class,
            'att_date' => $att_date,
            'att_type' => $att_type,
            'note' => $note,
        );
        
        $this->Attendance_model->insertList($data);
        doMsgLocation('등록되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/attendance?".$param);
    }
    
    // 수정 페이지
    function modify()
    {
        $seq      = $this->input->get('seq', TRUE);
        $page     = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sdate    = $this->input->get('sdate', TRUE);
        $edate    = $this->input->get('edate', TRUE);
        $att_type = $this->input->get('att_type', TRUE);
        $stype    = $this->input->get('stype', TRUE);
        $skeyword = $this->input->get('skeyword', TRUE);
        
        $param2 = "&sdate=".$sdate."&edate=".$edate."&att_type=".$att_type."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        $info = $this->Attendance_model->getInfo($seq);
        $worker_list = $this->Attendance_model->getWorkerList();
        
        $data = array(
            "param" => $param,
            "info" => $info,
            "worker_list" => $worker_list,
        );
        
        $this->load->view('attendance/modify', $data);
    }
    
    // 수정 처리
    function modifyproc()
    {
        $seq      = $this->input->post('seq', TRUE);
        $page     = $this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1;
        $sdate    = $this->input->post('sdate', TRUE);
        $edate    = $this->input->post('edate', TRUE);
        $att_type_filter = $this->input->post('att_type_filter', TRUE);
        $stype    = $this->input->post('stype', TRUE);
        $skeyword = $this->input->post('skeyword', TRUE);
        
        $param2 = "&sdate=".$sdate."&edate=".$edate."&att_type=".$att_type_filter."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        $worker_id   = $this->input->post('worker_id', TRUE);
        $att_date    = $this->input->post('att_date', TRUE);
        $att_type    = $this->input->post('att_type', TRUE);
        $note        = $this->input->post('note', TRUE);
        
        if(!$worker_id || !$att_date || !$att_type) {
            doMsgBack('필수 항목을 입력해주세요.');
            return;
        }
        
        // 중복 체크 (자기 자신 제외)
        $duplicate = $this->Attendance_model->checkDuplicate($worker_id, $att_date, $seq);
        if($duplicate > 0) {
            doMsgBack('해당 날짜에 이미 등록된 직원입니다.');
            return;
        }
        
        // 직원 정보 조회
        $this->load->model('Workers_model');
        $worker_info = $this->Workers_model->getList(array('sid' => $worker_id));
        if(count($worker_info) == 0) {
            doMsgBack('존재하지 않는 직원입니다.');
            return;
        }
        $worker = $worker_info[0];
        
        $data = array(
            'worker_id' => $worker_id,
            'worker_name' => $worker->name,
            'worker_class' => $worker->class,
            'att_date' => $att_date,
            'att_type' => $att_type,
            'note' => $note,
        );
        
        $this->Attendance_model->updateList($seq, $data);
        doMsgLocation('수정되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/attendance?".$param);
    }
    
    // 삭제
    function delproc()
    {
        $seq      = $this->input->get('seq', TRUE);
        $page     = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;
        $sdate    = $this->input->get('sdate', TRUE);
        $edate    = $this->input->get('edate', TRUE);
        $att_type = $this->input->get('att_type', TRUE);
        $stype    = $this->input->get('stype', TRUE);
        $skeyword = $this->input->get('skeyword', TRUE);
        
        $param2 = "&sdate=".$sdate."&edate=".$edate."&att_type=".$att_type."&stype=".$stype."&skeyword=".$skeyword;
        $param = "page=".$page.$param2;
        
        $this->Attendance_model->deleteList($seq);
        doMsgLocation('삭제되었습니다.',"http://".$_SERVER['HTTP_HOST']."/admn/attendance?".$param);
    }
    
    // 엑셀 다운로드
    function excel()
    {
        if(!in_array($this->session->userdata('ADM_AUTH'), array(3,9))){
            doMsgLocation('잘못된 요청 입니다.', "http://".$_SERVER['HTTP_HOST']);
        }
        
        $condition = array();
        $sdate     = $this->input->get('sdate', TRUE);
        $edate     = $this->input->get('edate', TRUE);
        $att_type  = $this->input->get('att_type', TRUE);
        $stype     = $this->input->get('stype', TRUE);
        $skeyword  = $this->input->get('skeyword', TRUE);
        
        if($sdate) $condition['sdate'] = $sdate;
        if($edate) $condition['edate'] = $edate;
        if($att_type) $condition['att_type'] = $att_type;
        if($stype) $condition['stype'] = $stype;
        if($skeyword) $condition['skeyword'] = $skeyword;
        
        $board_list = $this->Attendance_model->getList($condition);
        
        if(count($board_list) == 0){
            doMsgLocation('다운받을 데이터가 존재하지 않습니다.');
            return true;
        }
        
        $filename = date('Y-m-d').'_근무현황_'.count($board_list).'건';
        
        $this->load->library("PHPExcel");
        
        $objPHPExcel = new PHPExcel();
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A1", '날짜')
        ->setCellValue("B1", '직원명')
        ->setCellValue("C1", '직책')
        ->setCellValue("D1", '연차사용')
        ->setCellValue("E1", '조퇴사용')
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
            $att_date = $v->att_date;
            $worker_name = $v->worker_name;
            $worker_class = $v->worker_class;
            $annual_use = ($v->att_type == '연차') ? 'O' : '';
            $early_use = ($v->att_type == '조퇴') ? 'O' : '';
            $note = $v->note;
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".($k+2), $att_date)
            ->setCellValue("B".($k+2), $worker_name)
            ->setCellValue("C".($k+2), $worker_class)
            ->setCellValue("D".($k+2), $annual_use)
            ->setCellValue("E".($k+2), $early_use)
            ->setCellValue("F".($k+2), $note);
            
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
