<?php
//==============================================================================
// Wish List Extended
// 
// Author: Avvici, Involution Media
// E-mail: joseph@involutionmedia.com
// Website: http://www.involutionmedia.com
//==============================================================================
//Edit code with care. Always comment your code to take notes on what was changed so you can go back to it later.
//==============================================================================
class ModelWishlistSharedWishlist extends Model {

	
	public function getWishlist($customer_id,$list_id) {

		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "wishlist_multiple wm LEFT JOIN " . DB_PREFIX . "customer c ON(c.customer_id = wm.customer_id) WHERE wm.list_id = '".(int)$list_id."' AND wm.customer_id = '" . (int)$customer_id . "' AND c.wishlist_show = '1'");
			if ($customer_query->num_rows) {
				$wishlist = unserialize($customer_query->row['list_contents']);
			} else {
				$wishlist = '';
			}
		return $wishlist;
	}
	public function searchLists($data = array()) {
		
			 $sql = "SELECT * FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "address a ON (c.address_id = a.address_id)";				 
			   
			    if (!empty($data['nameoremail']) && !empty($data['townorcity'])) {
					
					$sql .= " WHERE c.status = '1'";
					$sql .= " AND LCASE(a.city)  = '" . $this->db->escape(utf8_strtolower($data['townorcity'])) . "'";
					if(stristr( $data['nameoremail'], ' ' )){
					$combined = explode(" ", $data['nameoremail']);

					$sql .= " AND LCASE(c.firstname) = '" . $this->db->escape(utf8_strtolower($combined[0])) . "'";	
					$sql .= " AND LCASE(c.lastname) = '" . $this->db->escape(utf8_strtolower($combined[1])) . "'";
					
					}else{
					$sql .= " AND LCASE(c.firstname) = '" . $this->db->escape(utf8_strtolower($data['nameoremail'])) . "'";	
					$sql .= " OR LCASE(c.lastname) = '" . $this->db->escape(utf8_strtolower($data['nameoremail'])) . "'";	
					}
					$sql .= " OR LCASE(c.email) = '" . $this->db->escape(utf8_strtolower($data['nameoremail'])) . "'";
					
			    }else if (!empty($data['nameoremail']) && empty($data['townorcity'])) {
					$sql .= " WHERE c.status = '1'";	
					if(stristr( $data['nameoremail'], ' ' )){
					$combined = explode(" ", $data['nameoremail']);

					$sql .= " AND LCASE(c.firstname) = '" . $this->db->escape(utf8_strtolower($combined[0])) . "'";	
					$sql .= " AND LCASE(c.lastname) = '" . $this->db->escape(utf8_strtolower($combined[1])) . "'";
					
					}else{
					$sql .= " AND LCASE(c.firstname) = '" . $this->db->escape(utf8_strtolower($data['nameoremail'])) . "'";	
					$sql .= " OR LCASE(c.lastname) = '" . $this->db->escape(utf8_strtolower($data['nameoremail'])) . "'";	
					}
					$sql .= " OR LCASE(c.email) = '" . $this->db->escape(utf8_strtolower($data['nameoremail'])) . "'";
					
							  			
				}else if (empty($data['nameoremail']) && !empty($data['townorcity'])) {
					$sql .= " WHERE c.status = '1'";
				    $sql .= " AND LCASE(a.city) = '" . $this->db->escape(utf8_strtolower($data['townorcity'])) . "'";
	  			
				}else{
					
					$sql .= " WHERE LCASE(c.firstname) = '4434298776543FDSSDFFFDDF59330QQQOXSSFFDS4455445'";
				}
				$query = $this->db->query($sql);
				if ($query->num_rows) {
					
					$stuff = $query->rows;
				
				}else{
				$stuff = '';	
				}
			
		return $stuff;
	}
}
?>