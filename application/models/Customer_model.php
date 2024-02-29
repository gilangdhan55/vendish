<?php 

class Customer_model extends CI_Model
{ 
    public function list_customer_produk($param){
 
        $order_by           = '';
        $map_like           = array();
        
        $order_by           = $param['order_by'] ?  : 'c.name asc';
         
        $limit              = $param['LIMIT'];
        $offset             = $param['OFFSET'];
        $search             = $param['orLike'];

        foreach ($search as $k => $v) {
            $map_like[] = "$k LIKE '%$v%'";
        }

        if($search != null){
            $OrLike          = 'AND ('. implode(" OR ", $map_like).')';
        }
        
        $sql        = "SELECT
                            c.code AS code_customer,
                            c.name AS name_customer,
                            c.address AS address_customer,
                            c.group_code,
                            c.group_ket AS group_name,
                            COUNT(DISTINCT CASE WHEN pc.category = 'MUST CHECK' THEN g.code_item END) AS count_must_check,
                            COUNT(DISTINCT CASE WHEN pc.category = 'MEDIUM' THEN g.code_item END) AS count_medium,
                            COUNT(DISTINCT CASE WHEN pc.category = 'LOW' THEN g.code_item END) AS count_low,
                            COUNT(DISTINCT g.code_item) AS count_group
                        FROM
                            master.customer c
                        LEFT JOIN
                            master.product_group_cust g ON (SELECT 'y' FROM master.groups s WHERE s.ket = g.prd_group) = 'y' AND c.code = g.customer_code
                        LEFT JOIN
                            master.product_category_cust pc ON pc.code_item = g.code_item
                        WHERE
                         (c.address IS NOT NULL OR c.group_ket IS NOT NULL)  $OrLike
                        GROUP BY
                            c.code, c.name, c.address, c.group_code, c.group_ket "; 
 
        $sql               .= " ORDER BY $order_by OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY "; 
      
        $query              = $this->db->query($sql); 
    
        $result             = $query->result();
       
        return $result;
 
         
    }

    public function count_total()
    {
        $sql    = "SELECT
            1 = 1
        FROM
            master.customer c
        LEFT JOIN
            master.product_group_cust g ON (SELECT 'y' FROM master.groups s WHERE s.ket = g.prd_group) = 'y' AND c.code = g.customer_code
        LEFT JOIN
            master.product_category_cust pc ON pc.code_item = g.code_item
        WHERE
            c.address IS NOT NULL OR c.group_ket IS NOT NULL or  c.group_code is not null
        GROUP BY
            c.code, c.name, c.address, c.group_code, c.group_ket
        ORDER BY
            c.group_ket ASC, c.name ASC";

        $query              = $this->db->query($sql);
        $result             = $query->result();
     
        return  count($result);
    }
    public function list_customer_produk_all(){
        $sql        = "SELECT
            c.code AS code_customer,
            c.name AS name_customer,
            c.address AS address_customer,
            c.group_code,
            c.group_ket AS group_name,
            COUNT(DISTINCT CASE WHEN pc.category = 'MUST CHECK' THEN g.code_item END) AS count_must_check,
            COUNT(DISTINCT CASE WHEN pc.category = 'MEDIUM' THEN g.code_item END) AS count_medium,
            COUNT(DISTINCT CASE WHEN pc.category = 'LOW' THEN g.code_item END) AS count_low,
            COUNT(DISTINCT g.code_item) AS count_group
        FROM
            master.customer c
        LEFT JOIN
            master.product_group_cust g ON (SELECT 'y' FROM master.groups s WHERE s.ket = g.prd_group) = 'y' AND c.code = g.customer_code
        LEFT JOIN
            master.product_category_cust pc ON pc.code_item = g.code_item
        WHERE
            c.address IS NOT NULL OR c.group_ket IS NOT NULL
        GROUP BY
            c.code, c.name, c.address, c.group_code, c.group_ket
        ORDER BY
            c.name ASC;
        ";
        
        $query      = $this->db->query($sql);
        $result     = $query->result(); 

        return $result;
    }
}