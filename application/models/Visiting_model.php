<?php 

class Visiting_model extends CI_Model
{ 

    public function get_all_visiting_hdr(){
        $sql        = "SELECT 
                            id, a.code, a.sales_code, a.sales_name, a.latitude, a.longitude, a.customer_code, a.customer_name, a.address, a.start_date, a.ended_date, a.created_by as code_pic,  b.fullname as pic_name, a.created_date, a.is_send, a.latitude_end, a.longitude_end, a.batery, a.note 
                        FROM app.visit_hdr a 
                        inner join 
                            config.master_user b ON a.created_by = b.username 
                        ORDER  BY a.created_date desc    
                        limit 20 
                        ";
        
        $query      = $this->db->query($sql);
        $result     = $query->result(); 

        return $result;
    }

    public function get_all_visiting_hdr_ajax($param){
        $order_by           = '';
        $map_like           = array();
        
        $order_by           = $param['order_by'] ?  : 'a.start_date desc';
         
        $limit              = $param['LIMIT'];
        $offset             = $param['OFFSET'];
        $search             = $param['orLike'];

        foreach ($search as $k => $v) {
            $map_like[] = "$k LIKE '%$v%'";
        }

        if($search != null){
            $OrLike          = 'WHERE ('. implode(" OR ", $map_like).')';
        }
        
        $sql        = "SELECT 
                            id, a.code, a.sales_code, a.sales_name, a.latitude, a.longitude, a.customer_code, a.customer_name, a.address, a.start_date, a.ended_date, a.created_by as code_pic,  b.fullname as pic_name, a.created_date, a.is_send, a.latitude_end, a.longitude_end, a.batery, a.note 
                        FROM 
                            app.visit_hdr a 
                        inner join 
                            config.master_user b ON a.created_by = b.username "; 
 
        $sql               .= " ORDER BY $order_by OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY "; 
      
        $query              = $this->db->query($sql); 
    
        $result             = $query->result();
       
        return $result;
    }

    public function get_all_brand_ajax($param){
        $order_by           = '';
        $map_like           = array();
        
        
        $order_by           = $param['order_by'] ?  : 'name asc';
         
        $limit              = $param['LIMIT'];
        $offset             = $param['OFFSET'];
        $search             = $param['orLike'];    
        $OrLike             = '';

        foreach ($search as $k => $v) {
            $map_like[] = "$k LIKE '%$v%'";
        }

        if($search != null){
            $OrLike          = 'WHERE ('. implode(" OR ", $map_like).')';
        }
        
        
        $sql                = "SELECT  sid, code, name  FROM  master.brand $OrLike";

        $sql               .= " ORDER BY $order_by OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY "; 
      
        $query              = $this->db->query($sql); 
        
       
        $result             = $query->result();
       
        return $result;
    }

    public function get_all_sku_ajax($param){
        $order_by           = '';
        $map_like           = array();
         
        $order_by           = $param['order_by'] ?  : '';
         
        $limit              = $param['LIMIT'];
        $offset             = $param['OFFSET'];
        $search             = $param['orLike'];    
        $OrLike             = '';

        foreach ($search as $k => $v) {
            $map_like[] = "$k LIKE '%$v%'";
        }

        if($search != null){
            $OrLike          = 'WHERE ('. implode(" OR ", $map_like).')';
        }
         
        $sql                = "SELECT  id, code_item, name_item  FROM  master.product_category $OrLike";

        $sql               .=  !empty($order_by) ? 'ORDER BY '.$order_by : '' ."  OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY "; 
      
        $query              = $this->db->query($sql); 
        
       
        $result             = $query->result();
       
        return $result;
    }

    public function get_all_group_ajax($param){
        $order_by           = '';
        $map_like           = array();
        
        
        $order_by           = $param['order_by'] ?  : '';
         
        $limit              = $param['LIMIT'];
        $offset             = $param['OFFSET'];
        $search             = $param['orLike'];    
        $OrLike             = '';

        foreach ($search as $k => $v) {
            $map_like[] = "$k LIKE '%$v%'";
        }

        if($search != null){
            $OrLike          = 'WHERE ('. implode(" OR ", $map_like).')';
        }
        
        
        $sql                = "SELECT  sid, kode, ket  FROM  master.groups $OrLike";

        $sql               .=  !empty($order_by) ? 'ORDER BY '.$order_by : '' ."  OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY "; 
      
        $query              = $this->db->query($sql); 
        
       
        $result             = $query->result();
       
        return $result;
    }

    public function count_total(){
        $sql        = "SELECT 
                            id 
                        FROM app.visit_hdr a 
                        inner join 
                            config.master_user b ON a.created_by = b.username 
                        ORDER  BY a.created_date desc    
                        ";
        
        $query      = $this->db->query($sql);
        $result     = $query->result(); 

        return count($result);
    }

    public function count_total_brand(){
        $sql        = "SELECT sid  FROM master.brand";
        
        $query      = $this->db->query($sql);
        $result     = $query->result(); 

        return count($result);
    }

    public function count_total_sku(){
        $sql        = "SELECT id  FROM master.product_category";
        
        $query      = $this->db->query($sql);
        $result     = $query->result(); 

        return count($result);
    }

    public function count_total_group(){
        $sql        = "SELECT sid  FROM master.groups";
        
        $query      = $this->db->query($sql);
        $result     = $query->result(); 

        return count($result);
    }

    public function get_all_brand()
    {
        $sql        = "SELECT sid, code, name FROM master.brand where is_active = 'y'";
        $query      = $this->db->query($sql);
        $result     = $query->result(); 

        return $result;
    }
}