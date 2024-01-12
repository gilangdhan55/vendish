<?php 

class Pegawai_model extends CI_Model
{
    private $table = 'pegawai';

    public function get_all_pegawai(){
        $sql        = "SELECT * FROM pegawai";
        
        $query      = $this->db->query($sql);
        $result     = $query->result(); 

        return $result;
    }
}