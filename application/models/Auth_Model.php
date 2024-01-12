<?php 

class Auth_Model extends CI_Model
{
    private $table = 'users';

    public function getData($email){
        $sql        = "SELECT * FROM users WHERE email = '$email'";
        
        $query      = $this->db->query($sql);
        $result     = $query->row(); 

        return $result;
    }
}