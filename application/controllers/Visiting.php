<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once APPPATH.'controllers\Auth.php'; 
use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;


class Visiting extends Auth {

	function __construct()
    {
        // Construct the parent class
        parent::__construct();  
        $this->load->library('form_validation');  
		$this->load->model('Visiting_Model'); 
    }
	

    public function daily_visiting_get()
	{

        $data		= $this->Visiting_Model->get_all_visiting_hdr(); 

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

    public function daily_visiting_post()
	{

        $param		= array();
      
		// DEFINISI TERLEBIH DAHULU
		$order_by 	= '';
		$search		=  $this->input->post('search')['value'];
       
		$col 		= ['','c.code', 'c.name', 'c.address', 'c.group_ket', 'count_must_check', 'count_medium', 'count_low', 'count_group']; //INI HARUS SESUAI URUTAN KOLOM YANG ADA DI VIEW DIMULAI INDEX KE 0 PALING KIRI
		 
		if($this->input->post('order')){
			//ambil masing masing col untuk menentukan order by apa dan di kolom apa
			$order_column 	= $col[$this->input->post('order')[0]['column']];
			$by 			= $this->input->post('order')[0]['dir'];
			$order_by		= $order_column." ".$by; 
		}
		
		$param			= [
			"order_by"	=> $order_by, 
			"LIMIT"  	=> $this->input->post('length'),
			"OFFSET"  	=> $this->input->post('start'), 
			"orLike"	=> [
				"a.code" 	        => $search,  
				"a.sales_name" 	    => $search,  
				"a.address" 	    => $search,  
				"a.start_date" 	    => $search,  
				"a.ended_date" 	    => $search,  
            ],
		];
		 
		$result = $this->Visiting_Model->get_all_visiting_hdr_ajax($param);
      
		$data = array(); 
        $i = $this->input->post('start') + 1;
		if($result){
			foreach ($result as $n) {  
				$row    = array();   
				$row[]  = $n->customer_code; 
				$row[]  = $n->customer_name;  
				$row[]  = $n->address; 
				$row[]  = $n->code_pic;  
				$row[]  = $n->pic_name; 
				$row[]  = date("d-M-Y", strtotime($n->start_date)); 
				$row[]  = date("H:i:s", strtotime($n->start_date)); 
				$row[]  = date("H:i:s", strtotime($n->ended_date)); 
				$row[]  = '<button type="button" class="btn btn-sm btn-light">Details</button>'; 
				$row[]  = '<button type="button" class="btn btn-sm btn-light">Details</button>'; 
				$data[] = $row; 
			}
			$CountResult 	= count($data); 
			$CountFilter	= $this->Visiting_Model->count_total();
		}else{
			  
			$CountFilter = 0;
			$CountResult = 0;
		}
		$output = array( 
			"draw"					=> $this->input->post('draw'),
			"data"              	=> $data,
			"recordsTotal"      	=> $CountResult,
			"recordsFiltered"      	=> $CountFilter,
		); 
		$this->output->set_content_type('application/json')->set_output(json_encode($output, 200));
    }
	
    public function all_brand_post()
	{

        $param		= array();
      
		// DEFINISI TERLEBIH DAHULU
		$order_by 	= '';
		$search		=  $this->input->post('search')['value'];
		 
       
		$col 		= ['','code', 'name']; //INI HARUS SESUAI URUTAN KOLOM YANG ADA DI VIEW DIMULAI INDEX KE 0 PALING KIRI
		 
		if($this->input->post('order')){
			//ambil masing masing col untuk menentukan order by apa dan di kolom apa
			$order_column 	= $col[$this->input->post('order')[0]['column']];
			$by 			= $this->input->post('order')[0]['dir'];
			$order_by		= $order_column." ".$by; 
		}
		
		$param			= [
			"order_by"	=> $order_by, 
			"LIMIT"  	=> $this->input->post('length'),
			"OFFSET"  	=> $this->input->post('start'), 
			"orLike"	=> [
				"LOWER(code)" 	    => strtolower($search),  
				"LOWER(name)" 	    => strtolower($search),   
            ],
		];
		 
		$result = $this->Visiting_Model->get_all_brand_ajax($param);
      
		$data = array(); 
        $i = $this->input->post('start') + 1;
		if($result){
			foreach ($result as $n) {  
				$row    = array();   
				$row[]	= '<div><input type="checkbox" class="checked-brand" data-brand="'.$n->name.'"></div>';
				$row[]  = $n->code; 
				$row[]  = $n->name;   
				$data[] = $row; 
			}
			$CountResult 	= count($data); 
			$CountFilter	= $this->Visiting_Model->count_total_brand();
		}else{
			  
			$CountFilter = 0;
			$CountResult = 0;
		}
		$output = array( 
			"draw"					=> $this->input->post('draw'),
			"data"              	=> $data,
			"recordsTotal"      	=> $CountResult,
			"recordsFiltered"      	=> $CountFilter,
		); 
		$this->output->set_content_type('application/json')->set_output(json_encode($output, 200));
    }

    public function all_sku_post()
	{

        $param		= array();
      
		// DEFINISI TERLEBIH DAHULU
		$order_by 	= '';
		$search		=  $this->input->post('search')['value'];
		 
       
		$col 		= ['','code_item', 'name_item']; //INI HARUS SESUAI URUTAN KOLOM YANG ADA DI VIEW DIMULAI INDEX KE 0 PALING KIRI
		 
		if($this->input->post('order')){
			//ambil masing masing col untuk menentukan order by apa dan di kolom apa
			$order_column 	= $col[$this->input->post('order')[0]['column']];
			$by 			= $this->input->post('order')[0]['dir'];
			$order_by		= $order_column." ".$by; 
		}
		
		$param			= [
			"order_by"	=> $order_by, 
			"LIMIT"  	=> $this->input->post('length'),
			"OFFSET"  	=> $this->input->post('start'), 
			"orLike"	=> [
				"LOWER(code_item)" 	    => strtolower($search),  
				"LOWER(name_item)" 	    => strtolower($search),   
            ],
		];
		 
		$result = $this->Visiting_Model->get_all_sku_ajax($param);
      
		$data = array(); 
        $i = $this->input->post('start') + 1;
		if($result){
			foreach ($result as $n) {  
				$row    = array();   
				$row[]	= '<div><input type="checkbox" class="checked-sku" data-sku="'.$n->name_item.'"></div>';
				$row[]  = $n->code_item; 
				$row[]  = $n->name_item;   
				$data[] = $row; 
			}
			$CountResult 	= count($data); 
			$CountFilter	= $this->Visiting_Model->count_total_sku();
		}else{
			  
			$CountFilter = 0;
			$CountResult = 0;
		}
		$output = array( 
			"draw"					=> $this->input->post('draw'),
			"data"              	=> $data,
			"recordsTotal"      	=> $CountResult,
			"recordsFiltered"      	=> $CountFilter,
		); 
		$this->output->set_content_type('application/json')->set_output(json_encode($output, 200));
    }
	
	public function all_group_ajax_post()
	{

        $param		= array();
      
		// DEFINISI TERLEBIH DAHULU
		$order_by 	= '';
		$search		=  $this->input->post('search')['value'];
		 
       
		$col 		= ['','kode', 'ket']; //INI HARUS SESUAI URUTAN KOLOM YANG ADA DI VIEW DIMULAI INDEX KE 0 PALING KIRI
		 
		if($this->input->post('order')){
			//ambil masing masing col untuk menentukan order by apa dan di kolom apa
			$order_column 	= $col[$this->input->post('order')[0]['column']];
			$by 			= $this->input->post('order')[0]['dir'];
			$order_by		= $order_column." ".$by; 
		}
		
		$param			= [
			"order_by"	=> $order_by, 
			"LIMIT"  	=> $this->input->post('length'),
			"OFFSET"  	=> $this->input->post('start'), 
			"orLike"	=> [
				"LOWER(kode)" 	    => strtolower($search),  
				"LOWER(ket)" 	    => strtolower($search),   
            ],
		];
		 
		$result = $this->Visiting_Model->get_all_group_ajax($param);
      
		$data = array(); 
        $i = $this->input->post('start') + 1;
		if($result){
			foreach ($result as $n) {  
				$row    = array();   
				$row[]	= '<div><input type="checkbox" class="checked-group" data-group="'.$n->ket.'"></div>';
				$row[]  = $n->kode; 
				$row[]  = $n->ket;   
				$data[] = $row; 
			}
			$CountResult 	= count($data); 
			$CountFilter	= $this->Visiting_Model->count_total_group();
		}else{
			  
			$CountFilter = 0;
			$CountResult = 0;
		}
		$output = array( 
			"draw"					=> $this->input->post('draw'),
			"data"              	=> $data,
			"recordsTotal"      	=> $CountResult,
			"recordsFiltered"      	=> $CountFilter,
		); 
		$this->output->set_content_type('application/json')->set_output(json_encode($output, 200));
    }

	// public function allBrand_get()
	// {
	// 	$response = array();
    //     if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// 		$data		= $this->Visiting_Model->get_all_brand();
			
	// 		if($data){
	// 			$this->response( [
	// 				'status' 	=> true,
	// 				'message'	=> 'Data Berhasil Didapatkan',
	// 				'result'	=> $data, 
	// 			], 200 ); 
	// 		}else{
	// 			$this->response( [
	// 				'status' => false,
	// 				'message' => 'Data Brand tidak ada'
	// 			], 404 );
	// 		}
	// 	}else{
	// 		$this->response( [
	// 			'status' => false,
	// 			'message' => 'Koneksi Server gagal'
	// 		], 404 );
	// 	}
	// }

}