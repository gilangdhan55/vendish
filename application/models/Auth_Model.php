<?php 

class Auth_Model extends CI_Model
{
    private $table = 'users';

    public function getData($username){
        $sql        = "SELECT * FROM config.master_user WHERE username = '$username'";
        
        $query      = $this->db->query($sql);
        $result     = $query->row(); 

        return $result;
    }
}