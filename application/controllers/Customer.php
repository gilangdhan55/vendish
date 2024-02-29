<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once APPPATH.'controllers\Auth.php'; 
use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;


class Customer extends Auth {

	function __construct()
    {
        // Construct the parent class
        parent::__construct();  
        $this->load->model('Customer_model');
    }

	 
    public function list_customer_produk_get()
	{ 

		$file_name		= 'data_list_customer_produk';
		$Today          = date("Y-m-d"); 
		$Yesterday      = date("Y-m-d", strtotime("-1 days", strtotime($Today)));
		$LocChunkz		= 'chunk/';
 
		if($_SERVER['REQUEST_METHOD'] == 'GET'){  
			$FolderLocation     = FCPATH.'FileJson';
			$FolderTujuan      	= $FolderLocation.DIRECTORY_SEPARATOR.$Today;
			  
			if (!file_exists($FolderLocation)) { 
				if (!mkdir($FolderLocation, 0777, true)) { 
					die("Gagal membuat folder...");
				}
			}  

			if (!file_exists($FolderTujuan)) { 
				if (!mkdir($FolderTujuan, 0777, true)) { 
					die("Gagal membuat folder...");
				}
			}  

			$list_folder =  array_values(array_diff(scandir($FolderLocation), array(".", "..")));
		 
			if(count($list_folder) > 1){  
				foreach ($list_folder as $item) { 
					if (is_dir($FolderLocation.DIRECTORY_SEPARATOR.$item)) {
						 if($item < $Today){
							$cekFiles = glob($FolderLocation.DIRECTORY_SEPARATOR.$item.DIRECTORY_SEPARATOR.'*'); 
							foreach ($cekFiles as $cekFile) { 
								is_file($cekFile) && unlink($cekFile);  
							} 
							rmdir($FolderLocation.DIRECTORY_SEPARATOR.$item);  
						 } 
					}
				}
			}   

			$FileName          = $file_name.'_'.$Today.'.json';  
			$FileExistToday    = file_exists($FolderTujuan.DIRECTORY_SEPARATOR. $FileName);  
		 
			if(!$FileExistToday){
				$data		= $this->Customer_model->list_customer_produk_all(); 
				$dataPush   = json_encode($data);
				 
				file_put_contents($FolderTujuan.DIRECTORY_SEPARATOR. $FileName, $dataPush); 
			} 
			  
			$jsonData = file_get_contents($FolderTujuan.DIRECTORY_SEPARATOR. $FileName); 
			$dataJson = json_decode($jsonData, true);  
			 
			if($dataJson){
				$response['status']  = true;
				$response['message'] = 'Data Berhasil Didapatkan';
				$response['data'] 	 = $dataJson;  
			}else{ 
				$response['status']  = false; 
			}
			echo json_encode($response); 
		} 
        
    }
  
	// public function list_customer_produk_get()
	// { 
	 
    //     $data		= $this->Customer_model->list_customer_produk_all(); 
  
    //     if(!$data){
    //         $this->response( [
    //             'status' => false,
    //             'message' => 'Data List Customer Produk Tidak Kosong'
    //         ], 404 );
    //     }

    //     $this->response( [
    //         'status' 	=> true,
    //         'message'	=> 'Data Berhasil Didapatkan',
    //         'result'	=> $data, 
    //     ], 200 ); 
    // }


    public function list_customer_produk_post()
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
				"c.code" 	    => $search, 
				"c.name" 	    => $search, 
				"c.address"     => $search, 
				"c.group_ket" 	=> $search,  
            ],
		];
		 
		$result = $this->Customer_model->list_customer_produk($param);
     
		$data = array(); 
        $i = $this->input->post('start') + 1;
		if($result){
			foreach ($result as $n) { 
				$row    = array();  
                $row[]  = $i;
				$row[]  = $n->code_customer; 
				$row[]  = $n->name_customer; 
				$row[]  = $n->address_customer; 
				$row[]  = $n->group_name; 
				$row[]  = $n->count_must_check; 
				$row[]  = $n->count_medium; 
				$row[]  = $n->count_low; 
				$row[]  = $n->count_group; 
				$row[]  = '<div class="d-flex gap-2">
                <div class="edit">
                    <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal" data-bs-target="#showModal">Detail</button>
                </div> 
            </div>'; 
				$data[] = $row;
                $i++;
			}
			$CountResult 	= count($data); 
			$CountFilter	= $this->Customer_model->count_total();
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

}