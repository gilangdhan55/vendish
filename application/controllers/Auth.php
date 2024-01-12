<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Auth extends RestController {
	private $key;


	function __construct()
    {
        // Construct the parent class
        parent::__construct(); 
        $this->load->library('form_validation');  
		$this->load->model('Auth_Model');  
		$this->key	= 'gipk123giepk123';
    }
	
	 
	public function login_post()
	{
		
		$json_data = $this->input->raw_input_stream;

		// Mendekode data JSON menjadi array atau objek
		$input = json_decode($json_data);
	
		// Validasi form menggunakan library CodeIgniter
		$this->form_validation->set_data((array)$input);
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) { 

			$validationErrors = $this->form_validation->error_array();
			$this->response( [
						'status' => false,
						'message' => $validationErrors
					], 400 ); 
        } else { 
			$email		= $input->email;
			$password	= $input->password;
			 
            $data		= $this->Auth_Model->getData($email); 

			if(!$data){
				$this->response( [
					'status' => false,
					'message' => 'Data tidak ditemukan atau user tidak ditemukan'
				], 404 );
			}else{
				$date	= new DateTime();
				if(!password_verify($password, $data->password)){
					$this->response( [
						'status' => false,
						'message' => 'Password atau email salah'
					], 404 );
				}else{ 
					$createToken		= [
						'status' 	=> true,
						'iat'		=> $date->getTimestamp(),
						'exp'		=> $date->getTimestamp() + (60 * 3),
						'data' 		=> $data
					];
				 
					$token	= JWT::encode($createToken, $this->key, 'HS256');

					$response = [
						'message'	=> 'Login Berhasil', 
						'status' 	=> true,
						'token'		=> $token,
						'result'	=> [
							'id'	=> $data->id,
							'name'	=> $data->name,
							'email'	=> $data->email
						],
					];

					
					$this->response($response, 200);

					 
				}
				
			}
		 
        }  
	}

	protected function cekToken(){
		$jwt = $this->input->get_request_header("Authorization");
		$key = $this->key;
		 
		try{
			JWT::decode($jwt, new Key($key, 'HS256'));
		}catch(Exception $e){
			$this->response( [
				'status' => false,
				'message' => 'Invalid Token'
			], self::HTTP_UNAUTHORIZED );
		}
	}
}
