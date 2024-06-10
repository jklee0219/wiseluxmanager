<?php
class Bloggerlist_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function getList($condition,$scale="N",$first="0")
    {
        $this->db->select('*');
        $this->db->from('tb_bloggerlist');

        //조건문
        if(isset($condition['stype']) && isset($condition['skeyword'])) {
            $stype = $condition['stype'];
            $skeyword = $condition['skeyword'];
            $skeyword_arr = explode(' ', $skeyword);
            $skeyword_cond = "";
            foreach($skeyword_arr as $v){
                if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
                $skeyword_cond .= $stype." like '%".$v."%'";
            }
            $this->db->where($skeyword_cond, NULL, FALSE);
        }
        if(isset($condition['splace'])){
            $this->db->where('place', $condition['splace']); 
        }
        if(isset($condition['svisdate'])) $this->db->where("visdate >= '".$condition['svisdate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['evisdate'])) $this->db->where("visdate <= '".$condition['evisdate']." 23:59:59'", NULL, FALSE);
        if(isset($condition['spaydate'])) $this->db->where("paydate >= '".$condition['spaydate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['epaydate'])) $this->db->where("paydate <= '".$condition['epaydate']." 23:59:59'", NULL, FALSE);

        if($scale != "N") $this->db->limit($scale, $first);
        $this->db->order_by('seq', 'DESC');
        $result = $this->db->get()->result();
        return $result;
    }

    function getListCnt($condition)
    {
        $this->db->select('count(*) as cnt');
        $this->db->from('tb_bloggerlist');
        
        //조건문
        if(isset($condition['stype']) && isset($condition['skeyword'])) {
            $stype = $condition['stype'];
            $skeyword = $condition['skeyword'];
            $skeyword_arr = explode(' ', $skeyword);
            $skeyword_cond = "";
            foreach($skeyword_arr as $v){
                if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
                $skeyword_cond .= $stype." like '%".$v."%'";
            }
            $this->db->where($skeyword_cond, NULL, FALSE);
        }
        if(isset($condition['splace'])){
            $this->db->where('place', $condition['splace']); 
        }
        if(isset($condition['svisdate'])) $this->db->where("visdate >= '".$condition['svisdate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['evisdate'])) $this->db->where("visdate <= '".$condition['evisdate']." 23:59:59'", NULL, FALSE);
        if(isset($condition['spaydate'])) $this->db->where("paydate >= '".$condition['spaydate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['epaydate'])) $this->db->where("paydate <= '".$condition['epaydate']." 23:59:59'", NULL, FALSE);

        $result = $this->db->get()->row();
        return $result->cnt;
    }

    function deleteList($seqs)
    {
        $this->db->where('seq in ('.$seqs.')', NULL, FALSE);
        $this->db->delete('tb_bloggerlist');
    }

    function insertList($data)
    {
        $this->db->insert('tb_bloggerlist', $data);
    }

    function updateList($data, $seq)
    {
        $this->db->where('seq', $seq);
        $this->db->update('tb_bloggerlist', $data);
    }

    function getView($seq)
    {
        $this->db->select('*', FALSE);
        $this->db->from('tb_bloggerlist');
        $this->db->where('seq', $seq);
        $result = $this->db->get()->row();
        return $result;
    }

    function getPayCnt($condition)
    {
        $this->db->select('count(*) as cnt');
        $this->db->from('tb_bloggerlist');
        
        //조건문
        if(isset($condition['stype']) && isset($condition['skeyword'])) {
            $stype = $condition['stype'];
            $skeyword = $condition['skeyword'];
            $skeyword_arr = explode(' ', $skeyword);
            $skeyword_cond = "";
            foreach($skeyword_arr as $v){
                if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
                $skeyword_cond .= $stype." like '%".$v."%'";
            }
            $this->db->where($skeyword_cond, NULL, FALSE);
        }
        if(isset($condition['splace'])){
            $this->db->where('place', $condition['splace']); 
        }
        if(isset($condition['svisdate'])) $this->db->where("visdate >= '".$condition['svisdate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['evisdate'])) $this->db->where("visdate <= '".$condition['evisdate']." 23:59:59'", NULL, FALSE);
        if(isset($condition['spaydate'])) $this->db->where("paydate >= '".$condition['spaydate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['epaydate'])) $this->db->where("paydate <= '".$condition['epaydate']." 23:59:59'", NULL, FALSE);

        $this->db->where("payprice > 0", NULL, FALSE);
        $result = $this->db->get()->row();
        return $result->cnt;
    }

    function getTotpay($condition)
    {
        $this->db->select('sum(payprice) as totpay');
        $this->db->from('tb_bloggerlist');
        
        //조건문
        if(isset($condition['stype']) && isset($condition['skeyword'])) {
            $stype = $condition['stype'];
            $skeyword = $condition['skeyword'];
            $skeyword_arr = explode(' ', $skeyword);
            $skeyword_cond = "";
            foreach($skeyword_arr as $v){
                if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
                $skeyword_cond .= $stype." like '%".$v."%'";
            }
            $this->db->where($skeyword_cond, NULL, FALSE);
        }
        if(isset($condition['splace'])){
            $this->db->where('place', $condition['splace']); 
        }
        if(isset($condition['svisdate'])) $this->db->where("visdate >= '".$condition['svisdate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['evisdate'])) $this->db->where("visdate <= '".$condition['evisdate']." 23:59:59'", NULL, FALSE);
        if(isset($condition['spaydate'])) $this->db->where("paydate >= '".$condition['spaydate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['epaydate'])) $this->db->where("paydate <= '".$condition['epaydate']." 23:59:59'", NULL, FALSE);

        $this->db->where("payprice > 0", NULL, FALSE);
        $result = $this->db->get()->row();
        return $result->totpay;
    }

}
