<?php
class Attendance_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // 리스트 조회
    function getList($condition, $scale="N", $first="0")
    {
        $this->db->select('a.*, m.name as worker_name, m.class as worker_class');
        $this->db->from('tb_attendance a');
        $this->db->join('tb_member m', 'a.worker_id = m.id', 'left');
        
        // 날짜 검색
        if(isset($condition['sdate']) && $condition['sdate']) {
            $this->db->where('a.att_date >=', $condition['sdate']); 
        }
        if(isset($condition['edate']) && $condition['edate']) {
            $this->db->where('a.att_date <=', $condition['edate']); 
        }
        
        // 근무유형 검색
        if(isset($condition['att_type']) && $condition['att_type']) {
            $this->db->where('a.att_type', $condition['att_type']); 
        }
        
        // 직원명 검색
        if(isset($condition['stype']) && isset($condition['skeyword'])) {
            if($condition['skeyword']) {
                if($condition['stype'] == 'worker_name') {
                    $this->db->like('m.name', $condition['skeyword']);
                } else if($condition['stype'] == 'worker_id') {
                    $this->db->like('a.worker_id', $condition['skeyword']);
                }
            }
        }
        
        if($scale != "N") $this->db->limit($scale, $first);
        $this->db->order_by('a.att_date', 'DESC');
        $this->db->order_by('m.ordernum', 'DESC');
        
        $result = $this->db->get()->result();
        return $result;
    }

    // 리스트 카운트
    function getListCnt($condition)
    {
        $this->db->select('count(*) as cnt');
        $this->db->from('tb_attendance a');
        $this->db->join('tb_member m', 'a.worker_id = m.id', 'left');
        
        if(isset($condition['sdate']) && $condition['sdate']) {
            $this->db->where('a.att_date >=', $condition['sdate']); 
        }
        if(isset($condition['edate']) && $condition['edate']) {
            $this->db->where('a.att_date <=', $condition['edate']); 
        }
        if(isset($condition['att_type']) && $condition['att_type']) {
            $this->db->where('a.att_type', $condition['att_type']); 
        }
        
        if(isset($condition['stype']) && isset($condition['skeyword'])) {
            if($condition['skeyword']) {
                if($condition['stype'] == 'worker_name') {
                    $this->db->like('m.name', $condition['skeyword']);
                } else if($condition['stype'] == 'worker_id') {
                    $this->db->like('a.worker_id', $condition['skeyword']);
                }
            }
        }
        
        $result = $this->db->get()->row();
        return $result->cnt;
    }

    // 달력용 전체 리스트 (날짜별 통계)
    function getAllList()
    {
        $this->db->select("
            att_date,
            SUM(CASE WHEN att_type = '연차' THEN 1 ELSE 0 END) as annual_cnt,
            SUM(CASE WHEN att_type = '반차' THEN 1 ELSE 0 END) as half_cnt,
            SUM(CASE WHEN att_type = '병가' THEN 1 ELSE 0 END) as sick_cnt
        ");
        $this->db->from('tb_attendance');
        $this->db->group_by('att_date');
        $result = $this->db->get()->result();
        return $result;
    }

    // 상세 정보 조회
    function getInfo($seq)
    {
        $this->db->select('a.*, m.name as worker_name, m.class as worker_class');
        $this->db->from('tb_attendance a');
        $this->db->join('tb_member m', 'a.worker_id = m.id', 'left');
        $this->db->where('a.seq', $seq);
        $result = $this->db->get()->row();
        return $result;
    }

    // 등록
    function insertList($data)
    {
        $this->db->insert('tb_attendance', $data);
        return $this->db->insert_id();
    }

    // 수정
    function updateList($seq, $data)
    {
        $this->db->where('seq', $seq);
        $this->db->update('tb_attendance', $data);
        return true;
    }

    // 삭제
    function deleteList($seq)
    {
        $this->db->where('seq', $seq);
        $this->db->delete('tb_attendance');
        return true;
    }

    // 특정 날짜의 직원별 근무현황 조회
    function getDateAttendance($date)
    {
        $this->db->select('a.*, m.name as worker_name, m.class as worker_class');
        $this->db->from('tb_attendance a');
        $this->db->join('tb_member m', 'a.worker_id = m.id', 'left');
        $this->db->where('a.att_date', $date);
        $this->db->order_by('m.ordernum', 'DESC');
        $result = $this->db->get()->result();
        return $result;
    }

    // 근무중인 직원 목록 조회 (근무현황 등록용)
    function getWorkerList()
    {
        $this->db->select('id, name, class, ordernum');
        $this->db->from('tb_member');
        $this->db->where('work_status', '근무중');
        $this->db->order_by('ordernum', 'DESC');
        $result = $this->db->get()->result();
        return $result;
    }

    // 중복 체크 (같은 날짜, 같은 직원)
    function checkDuplicate($worker_id, $att_date, $exclude_seq = null)
    {
        $this->db->select('count(*) as cnt');
        $this->db->from('tb_attendance');
        $this->db->where('worker_id', $worker_id);
        $this->db->where('att_date', $att_date);
        if($exclude_seq) {
            $this->db->where('seq !=', $exclude_seq);
        }
        $result = $this->db->get()->row();
        return $result->cnt;
    }

    // 통계: 연차 사용 건수
    function getAnnualCnt()
    {
        $this->db->select('count(*) as cnt');
        $this->db->from('tb_attendance');
        $this->db->where('att_type', '연차');
        $result = $this->db->get()->row();
        return $result->cnt;
    }

    // 통계: 반차 건수
    function getHalfCnt()
    {
        $this->db->select('count(*) as cnt');
        $this->db->from('tb_attendance');
        $this->db->where('att_type', '반차');
        $result = $this->db->get()->row();
        return $result->cnt;
    }

    // 통계: 병가 건수
    function getSickCnt()
    {
        $this->db->select('count(*) as cnt');
        $this->db->from('tb_attendance');
        $this->db->where('att_type', '병가');
        $result = $this->db->get()->row();
        return $result->cnt;
    }
}
