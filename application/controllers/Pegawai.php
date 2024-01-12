<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once APPPATH.'controllers\Auth.php'; 
use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;


class Pegawai extends Auth {

	function __construct()
    {
        // Construct the parent class
        parent::__construct(); 
        $this->cekToken();
        $this->load->library('form_validation');  
		$this->load->model('Pegawai_Model'); 
    }
	

    public function index_get()
	{

        $data		= $this->Pegawai_Model->get_all_pegawai(); 

        if(!$data){
            $this->response( [
                'status' => false,
                'message' => 'Data Pegawai Kosong'
            ], 404 );
        }

        $this->response( [
            'status' 	=> true,
            'message'	=> 'Data Berhasil Didapatkan',
            'result'	=> $data, 
        ], 200 ); 
    }

}