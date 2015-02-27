<?php 
//==============================================================================
// Wish List Extended - Multiple Lists 152
// 
// Author: Avvici, Opencart Vqmods
// E-mail: joe@opencartvqmods.com 
// Website: http://www.opencartvqmods.com
//==============================================================================
//Edit code with care. Always comment your code to take notes on what was changed so you can go back to it later.
//==============================================================================
class ControllerWishlistSharedWishList extends Controller {
	private $error = array();
	public function index() {

		$this->load->language('wishlist/shared_wishlist');
		 if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->load->model('wishlist/shared_wishlist');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if(isset($this->request->get['id'])) {
	            $customer_id = $this->request->get['id'];
		}else{
		        $customer_id = 0;
		}
		if(isset($this->request->get['listid'])) {
	            $list_id = $this->request->get['listid'];
		}else{
		        $list_id = 0;
		}
		
		if(isset($this->request->get['name'])) {
	            $name = $this->request->get['name'];
		}else{
		        $name = 0;
		}
      	
		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('wishlist/shared_wishlist&id=' . $customer_id),
        	'separator' => $this->language->get('text_separator')
      	);
			
		$this->data['heading_title'] = $this->language->get('heading_title');	
		$this->data['page_title'] = sprintf($this->language->get('page_title'), strtoupper($name));	
		$this->data['text_empty'] = $this->language->get('text_empty');
     		
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_stock'] = $this->language->get('column_stock');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_action'] = $this->language->get('column_action');
					
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['text_trash'] = $this->language->get('text_trash');
		$this->data['text_drag'] = $this->language->get('text_drag');
		$this->data['text_form_your_name'] = $this->language->get('text_form_your_name');
		$this->data['text_form_recipient_name'] = $this->language->get('text_form_recipient_name');
		$this->data['text_form_email'] = $this->language->get('text_form_email');
		$this->data['text_form_message'] = $this->language->get('text_form_message');
		$this->data['text_form_send'] = $this->language->get('text_form_send');
		$this->data['text_form_close'] = $this->language->get('text_form_close');
		$this->data['text_share_my_list'] = $this->language->get('text_share_my_list');
				$this->data['column_share'] = $this->language->get('column_share');

		
     	$this->data['column_add'] = $this->language->get('column_add');
		
		
		$this->data['products'] = array();
	
		$wishlist = $this->model_wishlist_shared_wishlist->getWishlist($customer_id, $list_id);
		
		if ($wishlist) {
	
		foreach ($wishlist as $key => $quantity) {
			$product = explode(":" , $key);
			$product_info = $this->model_catalog_product->getProduct($product[0]);
			
			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}
							
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
																			
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'options'      => $this->getListItemOptions($product_info['product_id'],$list_id),
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}	
	}

		$this->data['continue'] = $this->url->link('common/home');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/wishlist/shared_wishlist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/wishlist/shared_wishlist.tpl';
		} else {
			$this->template = 'default/template/wishlist/shared_wishlist.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
							
		$this->response->setOutput($this->render());		
	}
	private function getListItemOptionsTemp($optionsall,$pid){
		
		$option_data = array();
		  $quantity = 1;
					  $option_price = 0;
				$option_points = 0;
				$option_weight = 0;
					  if($optionsall){
					
					 $options = array_filter($optionsall);
      			foreach ($options as $product_option_id => $option_value) {
					$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$pid . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
					
					if ($option_query->num_rows) {
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
							$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
							
							if ($option_value_query->num_rows) {
								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}
															
								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}
								
								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
									$stock = false;
								}
								
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => $option_value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'name'                    => $option_query->row['name'],
									'option_value'            => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],									
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);								
							}
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($option_value)) {
							foreach ($option_value as $product_option_value_id) {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
								
								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}
																
									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}
									
									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
										$stock = false;
									}
									
									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'option_value'            => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);								
								}
							}						
						} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							$option_data[] = array(
								'product_option_id'       => $product_option_id,
								'product_option_value_id' => '',
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => '',
								'name'                    => $option_query->row['name'],
								'option_value'            => $option_value,
								'type'                    => $option_query->row['type'],
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',								
								'weight'                  => '',
								'weight_prefix'           => ''
							);						
						}
					}
					
      			} 
				}
		return $option_data;
	}
	private function getListItemOptions($product_id,$list_id){
		
		$option_data = array();
		
                   $optionscheck = $this->db->query("SELECT list_contents FROM `" . DB_PREFIX . "wishlist_multiple`  WHERE list_id = '".(int)$list_id."'");
					  if($optionscheck->num_rows){
					  $quantity = 1;
					  $option_price = 0;
				$option_points = 0;
				$option_weight = 0;
				$newlist = unserialize($optionscheck->row['list_contents']);
				
				 foreach ($newlist as $key => $value) {
				$product = explode(':', $key);
				if($product[0] === $product_id){
				$pid = $product[0];
				if (isset($product[1])) {
					$o = unserialize(base64_decode($product[1]));
					
				} else {
					$o = array();
				}
				break;
				}
				
				
				}
					 
					 $options = array_filter($o);
      			foreach ($options as $product_option_id => $option_value) {
					$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$pid . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
					
					if ($option_query->num_rows) {
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
							$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
							
							if ($option_value_query->num_rows) {
								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}
															
								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}
								
								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
									$stock = false;
								}
								
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => $option_value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'name'                    => $option_query->row['name'],
									'option_value'            => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],									
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);								
							}
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($option_value)) {
							foreach ($option_value as $product_option_value_id) {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
								
								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}
																
									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}
									
									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
										$stock = false;
									}
									
									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'option_value'            => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);								
								}
							}						
						} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							$option_data[] = array(
								'product_option_id'       => $product_option_id,
								'product_option_value_id' => '',
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => '',
								'name'                    => $option_query->row['name'],
								'option_value'            => $option_value,
								'type'                    => $option_query->row['type'],
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',								
								'weight'                  => '',
								'weight_prefix'           => ''
							);						
						}
					}
					
      			} 
				}
		return $option_data;
	}
	public function search() {
			$this->load->model('wishlist/shared_wishlist');	
			  $this->language->load('wishlist/shared_wishlist');
		$this->data['wishlistsearch'] = $this->url->link('wishlist/shared_wishlist/search', '');
		$this->data['text_trash'] = $this->language->get('text_trash');
		$this->data['text_form_your_name'] = $this->language->get('text_form_your_name');
		$this->data['text_form_recipient_name'] = $this->language->get('text_form_recipient_name');
		$this->data['text_form_email'] = $this->language->get('text_form_email');
		$this->data['text_form_message'] = $this->language->get('text_form_message');
		$this->data['text_form_send'] = $this->language->get('text_form_send');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		  
		   if($this->request->post['nameoremail'] != ""){
		   $name_email = $this->request->post['nameoremail'];
		   }else{
			  $name_email = '';  
		   }
		  if($this->request->post['townorcity'] != ""){
		   $town_city = $this->request->post['townorcity'];
		   }else{
			  $town_city = '';  
		   }
		   
		     $data = array(
				'townorcity'               => $town_city, 
				'nameoremail'               => $name_email
				
			);
			$this->data['name_lists'] = array();
		     $stuff = $this->model_wishlist_shared_wishlist->searchLists($data);
			 if($stuff){
				
				 $lists = array();
				foreach($stuff as $value){
				 $customers_lists = $this->db->query("SELECT list_name,list_id FROM `" . DB_PREFIX . "wishlist_multiple` WHERE  customer_id = '".(int)$value['customer_id']."'");
				 if($customers_lists){
					 foreach($customers_lists->rows as $list){
					 $lists[$list['list_id']] = $list['list_name'];
					 }
					 
					 }
					 
					 if($value['wishlist'] != ""){
					 $wishliststatus = "Active";
				 }else{
					 $wishliststatus = "Empty"; 
				 }
				    
					$this->data['name_lists'][] = array(
					'firstname'               => $value['firstname'], 
				    'lastname'               => $value['lastname'],	
					'customerid'               => $value['customer_id'],
					'status'               => $wishliststatus,
					'lists'               => $lists					
					
					);
				}
				
			 }else{
				$this->data['name_lists']  = array();
				
			 }
		}
			
		
		 if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/wishlist/shared_wishlist_results.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/wishlist/shared_wishlist_results.tpl';
		} else {
			$this->template = 'default/template/wishlist/shared_wishlist_results.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
		 
		 $this->response->setOutput($this->render());		
		
		
     
	}
	public function addNewList() {
				$this->language->load('wishlist/shared_wishlist');
		  $json = array();
if($this->request->post['name'] == ""){
	$json['error'] = $this->language->get('error_name_2');	
		
}else{
	$cleaned_name = preg_replace('/[^\w]/', '', $this->request->post['name']);
	 $name_check = $this->db->query("SELECT list_name FROM `" . DB_PREFIX . "wishlist_multiple` WHERE LOWER(list_name) = '".(string)strtolower($cleaned_name)."' AND customer_id = '".(int)$this->customer->getId()."' AND list_category_id = '" . (int)$this->request->post['categoryname'] . "'");
	
	if($name_check->num_rows){
			$json['error'] = $this->language->get('error_name_same');
	}else{
        $this->db->query("INSERT INTO `" . DB_PREFIX . "wishlist_multiple` SET list_name = '" . $this->db->escape($cleaned_name) . "', customer_id = '".(int)$this->customer->getId()."', list_category_id = '" . (int)$this->request->post['categoryname'] . "', date_created = NOW() ");
	    $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
		
		$this->session->data['success'] = $this->language->get('success_1');
        
		
	}
	}
	 $this->response->setOutput(json_encode($json));
	}
	
	public function removeCategory() {
		  $json = array();
$this->language->load('wishlist/shared_wishlist');
        $this->db->query("DELETE FROM `" . DB_PREFIX . "wishlist_categories` WHERE category_id = '" . (int)$this->request->get['catid'] . "'");
		 $this->db->query("DELETE FROM `" . DB_PREFIX . "wishlist_multiple` WHERE list_category_id = '" . (int)$this->request->get['catid'] . "'");
	    $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
		$this->session->data['success'] = $this->language->get('success_2');
        
		 $this->response->setOutput(json_encode($json));	 
	}
	
	public function editListName(){
		$this->language->load('wishlist/shared_wishlist');
		 $json = array();
         
		 if($this->request->post['list-name'] != "" && $this->request->post['changelistnameid'] != ""){
			 
        $this->db->query("UPDATE `" . DB_PREFIX . "wishlist_multiple` SET list_name = '" . $this->db->escape($this->request->post['list-name']) . "' WHERE list_id = '" . (int)$this->request->post['changelistnameid'] . "'");
	    $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
		$this->session->data['success'] = $this->language->get('success_3');
	}else{
		
		$json['error'] = $this->language->get('error_1');
	}
		 $this->response->setOutput(json_encode($json));	 
	}
	
	public function editCategoryName(){
		$this->language->load('wishlist/shared_wishlist');
		 $json = array();
         
		 if($this->request->post['catname'] != "" && $this->request->post['changecatnameid'] != ""){
			 
        $this->db->query("UPDATE `" . DB_PREFIX . "wishlist_categories` SET category_name = '" . $this->db->escape($this->request->post['catname']) . "' WHERE category_id = '" . (int)$this->request->post['changecatnameid'] . "'");
	    $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
		$this->session->data['success'] = $this->language->get('success_4');
	}else{
		
		$json['error'] = $this->language->get('error_1');
	}
		 $this->response->setOutput(json_encode($json));	 
	}


  public function getTemporaryList() {
		  $products = array();
        $this->language->load('wishlist/shared_wishlist');
		 
	   if(isset($this->session->data['wishlistqueue'])){
		   $this->load->model('catalog/product');
		   $this->load->model('tool/image');
					 foreach ($this->session->data['wishlistqueue'] as $key => $quantity) {
				$product = explode(':', $key);
				$product_id = $product[0];
				if (isset($product[1])) {
					$options = unserialize(base64_decode($product[1]));
				} else {
					$options = array();
				}
				$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}
							
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
																			
				$products[] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'options'       => $this->getListItemOptionsTemp($options,$product_info['product_id']),
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
					 }
		
			
		
	   
	   }else{
		  $products = array(); 
	   }
		
		 $this->response->setOutput(json_encode($products));	 
	}
	
	public function removeList() {
		  $json = array();
        $this->language->load('wishlist/shared_wishlist');
		  if(isset($this->request->get['listid'])){
			  $id = $this->request->get['listid'];
		  }else{
			 $id = '0';
			  
		  }
	    $this->db->query("DELETE FROM `" . DB_PREFIX . "wishlist_multiple` WHERE list_id = '".(int)$id."'");
		
			 $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
		
		
		$this->session->data['success'] = $this->language->get('success_5');
        
		 $this->response->setOutput(json_encode($json));	 
	}
	public function addNewCategory() {
		$this->language->load('wishlist/shared_wishlist');
		  $json = array();
		  
if($this->request->post['name'] == ""){
	$json['error'] = $this->language->get('error_name');	

             
				
}else{
	$cleaned_name = preg_replace('/[^\w]/', '', $this->request->post['name']);
	 $name_check = $this->db->query("SELECT category_name FROM `" . DB_PREFIX . "wishlist_categories` WHERE LOWER(category_name) = '".(string)strtolower($cleaned_name)."' AND customer_id = '".(int)$this->customer->getId()."'");
	
	if($name_check->num_rows){
			$json['error'] = $this->language->get('error_name_same');	
				}else{
        $this->db->query("INSERT INTO `" . DB_PREFIX . "wishlist_categories` SET category_name = '" . $this->db->escape($cleaned_name) . "' , customer_id = '".(int)$this->customer->getId()."' ,sort_order = '" . (int)$this->request->post['sort'] . "', date_created = NOW() ");
	    $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
		$this->session->data['success'] = $this->language->get('success_6');
				}
}
		 $this->response->setOutput(json_encode($json));	 
	}
	public function assignToOldList() {
		$this->language->load('wishlist/shared_wishlist');
		  $json = array();
       if(isset($this->request->get['listid'])){
		   $id = $this->request->get['listid'];
		   
	   }else{
		  $id = 0;  
	   }
	   
	  $list_count = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "wishlist_multiple` WHERE list_id = '".(int)$id."'");
	            $count = $list_count->row['total'];
				if($count > 0){
					$olditems = $this->db->query("SELECT wishlist FROM `" . DB_PREFIX . "customer` WHERE customer_id = '".(int)$this->customer->getId()."' AND wishlist_old = '1'");
					$list = $this->db->query("SELECT list_contents FROM `" . DB_PREFIX . "wishlist_multiple`  WHERE list_id = '".(int)$id."'");
					   if ($list->row['list_contents'] && is_string($list->row['list_contents'])) {
						  
						    $oldlist = unserialize($olditems->row['wishlist']);
						    $currentlist = unserialize($list->row['list_contents']);
							
							if(!is_array($currentlist)){
								 $currentlist = array();
							}
							
							foreach($oldlist as $pid1){
								$key1 = (int)$pid1;
								$currentlist[$key1] = 1;
								
							}
							
							
					   }else{
						 $subqueue = array(); 
						 
					   }
					
					$subqueue = $currentlist;
					
					
					$this->session->data['wishlist'] = array();
					 
					 
	$olditems = $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET wishlist_old = '0', wishlist = '".serialize($this->session->data['wishlist'])."' WHERE customer_id = '".(int)$this->customer->getId()."'");
	$this->db->query("UPDATE `" . DB_PREFIX . "wishlist_multiple` SET list_contents = '" . serialize($subqueue)  . "' , date_modified= NOW() WHERE list_id = '" . (int)$id . "'");

                 $this->session->data['success'] = $this->language->get('success_9');
				  if(isset($this->request->get['catpath'])){
					   $json['success'] = str_replace('&amp;', '&', $this->request->get['catpath']);
					  
				  }else{
					   $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
				  }
			   
				}else{
					
					$json['error'] = $this->language->get('error_1');
					
				}
        
		 $this->response->setOutput(json_encode($json));	 
	}
	
	public function assignToNewList() {
		$this->language->load('wishlist/shared_wishlist');
		  $json = array();
       if(isset($this->request->get['oldlistid'])){
		   $oldlistid = $this->request->get['oldlistid'];
		   
	   }else{
		   $oldlistid = 0;  
	   }
	   if(isset($this->request->get['newlistid'])){
		   $newlistid = $this->request->get['newlistid'];
		   
	   }else{
		   $newlistid = 0;  
	   }
	    if(isset($this->request->get['productid'])){
		   $pid = $this->request->get['productid'];
		   
	   }else{
		  $pid = 0;  
	   }
	  
	  $list_count = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "wishlist_multiple` WHERE list_id = '".(int)$newlistid."'");
	            $count = $list_count->row['total'];
				if($count > 0){
					 $golist = $this->db->query("SELECT list_contents FROM `" . DB_PREFIX . "wishlist_multiple`  WHERE list_id = '".(int)$newlistid."'");
					  $originallist = $this->db->query("SELECT list_contents FROM `" . DB_PREFIX . "wishlist_multiple`  WHERE list_id = '".(int)$oldlistid."'");
					  if ($originallist->row['list_contents'] && is_string($originallist->row['list_contents'])) {
					 $newlist = unserialize($golist->row['list_contents']);
					 $oldlist = unserialize($originallist->row['list_contents']);
					if(empty($oldlist)){
						
						$oldlist[$pid] = 1;
					 }else{
						
				
				 foreach ($oldlist as $key => $q) {
				$product = explode(':', $key);
				if($product[0] == (int)$pid){
				
				$quantity = $q;
				if (isset($product[1])) {
					$options = $product[1];
					
				} else {
					$options = array();
					
				}
				
				if (!$options) {
      		$key = (int)$pid;
    	} else {
      		$key = (int)$pid . ':' . $options;
    	}
						
						$newlist[$key] = $quantity;	
						break;
				}
				
					}
					
					}
					 }
					 
						
	
	    $this->db->query("UPDATE `" . DB_PREFIX . "wishlist_multiple` SET list_contents = '" . serialize($newlist) . "' , date_modified= NOW() WHERE list_id = '" . (int)$newlistid . "'");
		
	 //Delete old item if so
	 
	 if($this->request->get['deleteold'] == 1){
		
					
				 foreach ($oldlist as $key => $q) {
				$product = explode(':', $key);
				if($product[0] == (int)$pid){
					unset($oldlist[$key]);
				break;	
				}
				
				 }

		
 $this->db->query("UPDATE `" . DB_PREFIX . "wishlist_multiple` SET list_contents = '" . serialize($oldlist) . "' , date_modified= NOW() WHERE list_id = '" . (int)$oldlistid . "'");

	 }
			 
			 $this->session->data['success'] = $this->language->get('success_13');
				  if(isset($this->request->get['catpath'])){
					   $json['success'] = str_replace('&amp;', '&', $this->request->get['catpath']);
					  
				  }else{
					   $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
				  }
			   
				}else{
					
					$json['error'] = $this->language->get('error_1');
					
				}
        
		 $this->response->setOutput(json_encode($json));	 
	}
	public function assignToList() {
		$this->language->load('wishlist/shared_wishlist');
		  $json = array();
       if(isset($this->request->get['listid'])){
		   $id = $this->request->get['listid'];
		   
	   }else{
		  $id = 0;  
	   }
	   
	  $list_count = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "wishlist_multiple` WHERE list_id = '".(int)$id."'");
	            $count = $list_count->row['total'];
				if($count > 0){
					 $list = $this->db->query("SELECT list_contents FROM `" . DB_PREFIX . "wishlist_multiple`  WHERE list_id = '".(int)$id."'");
					  if ($list->row['list_contents'] && is_string($list->row['list_contents'])) {
					 $newlist = unserialize($list->row['list_contents']);
					 foreach ($newlist as $key => $quantity) {
				
				if (!array_key_exists($key, $this->session->data['wishlistqueue'])) {
						
			
						$this->session->data['wishlistqueue'][$key] = $quantity;
					}
				
					 }
				}		
		
	    $this->db->query("UPDATE `" . DB_PREFIX . "wishlist_multiple` SET list_contents = '" . $this->db->escape(isset($this->session->data['wishlistqueue']) ? serialize($this->session->data['wishlistqueue']) : '') . "' , date_modified= NOW() WHERE list_id = '" . (int)$id . "'");

if(isset($this->session->data['wishlistqueue'])){
		unset($this->session->data['wishlistqueue']);
		
}
                 $this->session->data['success'] = $this->language->get('success_9');
				  if(isset($this->request->get['catpath'])){
					   $json['success'] = str_replace('&amp;', '&', $this->request->get['catpath']);
					  
				  }else{
					   $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
				  }
			   
				}else{
					
					$json['error'] = $this->language->get('error_1');
					
				}
        
		 $this->response->setOutput(json_encode($json));	 
	}
	public function destroyOldList() {
		  $json = array();
        $this->language->load('wishlist/shared_wishlist');
		 $this->session->data['wishlist'] = array();
		  if(isset($this->request->get['listid'])){
			  $id = $this->request->get['listid'];
		  }else{
			 $id = '0';
			  
		  }
	   	$olditems = $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET wishlist_old = '0', wishlist = '".serialize($this->session->data['wishlist'])."' WHERE customer_id = '".(int)$this->customer->getId()."'");

		
			 $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', 'list_id='.$id, 'SSL'));
		
		
		$this->session->data['success'] = $this->language->get('success_12');
        
		 $this->response->setOutput(json_encode($json));	 
	}
	
	public function clearList() {
		  $json = array();
        $this->language->load('wishlist/shared_wishlist');
		  if(isset($this->request->get['listid'])){
			  $id = $this->request->get['listid'];
		  }else{
			 $id = '0';
			  
		  }
	    $this->db->query("UPDATE `" . DB_PREFIX . "wishlist_multiple` SET list_contents = '' WHERE list_id = '".(int)$id."'");
		
			 $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', 'list_id='.$id, 'SSL'));
		
		
		$this->session->data['success'] = $this->language->get('success_7');
        
		 $this->response->setOutput(json_encode($json));	 
	}
	public function addOldListAndAssign() {
		  $json = array();
$this->language->load('wishlist/shared_wishlist');
 if(isset($this->request->get['product_id'])){
		   $pid = $this->request->get['product_id'];
		   
	   }else{
		  $pid = 0;  
	   }
if($this->request->post['name_6'] == ""){
	$json['error'] = $this->language->get('error_name_2');	
		
}else if(!isset($this->request->post['categoryname_6'])){
	$json['error'] = $this->language->get('error_name_3');	
}else{
$cleaned_name = preg_replace('/[^\w]/', '', $this->request->post['name_6']);
	 $name_check = $this->db->query("SELECT list_name FROM `" . DB_PREFIX . "wishlist_multiple` WHERE LOWER(list_name) = '".(string)strtolower($cleaned_name)."' AND customer_id = '".(int)$this->customer->getId()."' AND list_category_id = '" . (int)$this->request->post['categoryname_6'] . "'");
	
	if($name_check->num_rows){
			$json['error'] = $this->language->get('error_name_same');
	}else{
        $this->db->query("INSERT INTO `" . DB_PREFIX . "wishlist_multiple` SET list_name = '" . $this->db->escape($cleaned_name) . "', customer_id = '".(int)$this->customer->getId()."', list_category_id = '" . (int)$this->request->post['categoryname_6'] . "', date_created = NOW() ");
	    
		//Now add the old wish list items and save.
		$list_id = $this->db->getLastId();
		
		
	  $list_count = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "wishlist_multiple` WHERE list_id = '".(int)$id."'");
	            $count = $list_count->row['total'];
				if($count > 0){
					$olditems = $this->db->query("SELECT wishlist FROM `" . DB_PREFIX . "customer` WHERE customer_id = '".(int)$this->customer->getId()."' AND wishlist_old = '1'");
					$list = $this->db->query("SELECT list_contents FROM `" . DB_PREFIX . "wishlist_multiple`  WHERE list_id = '".(int)$id."'");
					   if ($list->row['list_contents'] && is_string($list->row['list_contents'])) {
						  
						    $oldlist = unserialize($olditems->row['wishlist']);
						    $currentlist = unserialize($list->row['list_contents']);
							
							if(!is_array($currentlist)){
								 $currentlist = array();
							}
							
							foreach($oldlist as $pid1){
								$key1 = (int)$pid1;
								$currentlist[$key1] = 1;
								
							}
							
							
					   }else{
						 $subqueue = array(); 
						 
					   }
					
					$subqueue = $currentlist;
					$this->session->data['wishlist'] = array();
					 
					 
	$olditems = $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET wishlist_old = '0', wishlist = '".serialize($this->session->data['wishlist'])."' WHERE customer_id = '".(int)$this->customer->getId()."'");
	$this->db->query("UPDATE `" . DB_PREFIX . "wishlist_multiple` SET list_contents = '" . serialize($subqueue)  . "' , date_modified= NOW() WHERE list_id = '" . (int)$list_id . "'");

if(isset($this->request->get['catpath'])){
		  $json['success'] = str_replace('&amp;', '&', $this->request->get['catpath']);
		  
}else{
	  $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
}
		$this->session->data['success'] = $this->language->get('success_8');
		
       }else{
					
					$json['error'] = $this->language->get('error_1');
					
				} 
		  
	}
}
$this->response->setOutput(json_encode($json));	
	}
	
	public function addNewListAndAssign() {
		  $json = array();
$this->language->load('wishlist/shared_wishlist');
 if(isset($this->request->get['product_id'])){
		   $pid = $this->request->get['product_id'];
		   
	   }else{
		  $pid = 0;  
	   }
if($this->request->post['name'] == ""){
	$json['error'] = $this->language->get('error_name_2');	
		
}else if(!isset($this->request->post['categoryname'])){
	$json['error'] = $this->language->get('error_name_3');	
}else{
      //clean the name
	  $cleaned_name = preg_replace('/[^\w]/', '', $this->request->post['name']);
	 $name_check = $this->db->query("SELECT list_name FROM `" . DB_PREFIX . "wishlist_multiple` WHERE LOWER(list_name) = '".(string)strtolower($cleaned_name)."' AND customer_id = '".(int)$this->customer->getId()."' AND list_category_id = '" . (int)$this->request->post['categoryname'] . "'");
	
	if($name_check->num_rows){
			$json['error'] = $this->language->get('error_name_same');
	}else{
        $this->db->query("INSERT INTO `" . DB_PREFIX . "wishlist_multiple` SET list_name = '" . $this->db->escape($cleaned_name) . "', customer_id = '".(int)$this->customer->getId()."', list_category_id = '" . (int)$this->request->post['categoryname'] . "', date_created = NOW() ");
	    
		//Now add the list and save
		$list_id = $this->db->getLastId();
	  $list_count = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "wishlist_multiple` WHERE list_id = '".(int)$list_id."'");
	            $count = $list_count->row['total'];
				if($count > 0){
					 $list = $this->db->query("SELECT list_contents FROM `" . DB_PREFIX . "wishlist_multiple`  WHERE list_id = '".(int)$list_id."'");
					  if ($list->row['list_contents'] && is_string($list->row['list_contents'])) {
					 $newlist = unserialize($list->row['list_contents']);
					 foreach ($newlist as $product_id => $quantity) {
					if (!array_key_exists($product_id, $this->session->data['wishlistqueue'])) {
						$this->session->data['wishlistqueue'][$product_id] = $quantity;
					}
				}	
					  }
	    $this->db->query("UPDATE `" . DB_PREFIX . "wishlist_multiple` SET list_contents = '" . $this->db->escape(isset($this->session->data['wishlistqueue']) ? serialize($this->session->data['wishlistqueue']) : '') . "' , date_modified= NOW() WHERE list_id = '" . (int)$list_id . "'");
if(isset($this->session->data['wishlistqueue'])){
		unset($this->session->data['wishlistqueue']);
		
}
if(isset($this->request->get['catpath'])){
		  $json['success'] = str_replace('&amp;', '&', $this->request->get['catpath']);
		  
}else{
	  $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', '', 'SSL'));
}
		$this->session->data['success'] = $this->language->get('success_8');
		
       }else{
					
					$json['error'] = $this->language->get('error_1');
					
				} 
		  
	}
}
$this->response->setOutput(json_encode($json));	
	}
	public function addToCart() {
		$this->language->load('checkout/cart');
		
		$json = array();
		
		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		if (isset($this->request->get['quantity'])) {
				$quantity = $this->request->get['quantity'];
			} else {
				$quantity = 1;
			}
			if (isset($this->request->get['mainkey'])) {
			$mainkey = $this->request->get['mainkey'];
		} else {
			$mainkey = 0;
		}				
		
		$this->load->model('catalog/product');
						
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {			
										
			
			
							  $product = explode(":" , $mainkey);
							 if(isset($product[1])){
								
							 $options = unserialize(base64_decode($product[1]));
							
							 $option = array_filter($options);   
							 }else{
								
								$option = array(); 
							 }
						
					
			
			if (!$json) {
				$this->cart->add($product_id, $quantity, $option);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_id), $product_info['name'], $this->url->link('checkout/cart'));
				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				
				// Totals
				$this->load->model('setting/extension');
				
				$total_data = array();					
				$total = 0;
				$taxes = $this->cart->getTaxes();
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$sort_order = array(); 
					
					$results = $this->model_setting_extension->getExtensions('total');
					
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}
					
					array_multisort($sort_order, SORT_ASC, $results);
					
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);
				
							$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
						
						$sort_order = array(); 
					  
						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}
			
						array_multisort($sort_order, SORT_ASC, $total_data);			
					}
				}
				
				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->get['product_id']));
			}
		}
		
		$this->response->setOutput(json_encode($json));		
	}
	public function loadList() {
		  $json = array();
          $this->load->model('catalog/product');
		  $this->language->load('wishlist/shared_wishlist');
		  $this->load->model('tool/image');
		  if(isset($this->request->get['listid'])){
			  $id = $this->request->get['listid'];
		  }else{
			 $id = '0';
			  
		  }
        $list = $this->db->query("SELECT list_name FROM `" . DB_PREFIX . "wishlist_multiple`  WHERE list_id = '".(int)$id."'");
		if($list){
			
			
			$name = $list->row['list_name'];
			  
			 $json['success'] = str_replace('&amp;', '&', $this->url->link('account/wishlist', 'list_id='.$id, 'SSL'));
		}else{
		$json['error'] =  $this->language->get('error_3');
		}
	   
		
		$this->session->data['success'] = $this->language->get('success_10') .$name. $this->language->get('success_11');
        
		 $this->response->setOutput(json_encode($json));	 
	}
	public function sendList() {
		  $json = array();
		  $this->language->load('wishlist/shared_wishlist');
		 
		 
		  if(isset($this->request->get['link'])){
			  $passed_url = $this->request->get['link'];
		  }else{
			 $passed_url = $this->language->get('text_error_url').HTTP_SERVER; 
			  
		  }
		  $subject = $this->language->get('subject');
		  $store = $this->language->get('heading_title');
		 if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$json['error']['email'] = $this->language->get('text_error');
		  
		  }else{
		$json['error']['success'] = $this->language->get('text_success');
		//Send Mail
			$template = new Template();		
			if(!defined('HTTP_IMAGE')){
			$template->data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
			}else{
			$template->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');	
			}	
			$template->data['title'] = $this->language->get('text_title');
			$template->data['store_name'] = $this->config->get('store_name');
			$template->data['store_url'] = $this->config->get('store_url');
		      
			 
			  if($this->request->post['yourname'] != "" && $this->request->post['recipientsname'] != ""){
			  	$template->data['text'] = sprintf($this->language->get('text_greeting1'), html_entity_decode($this->request->post['recipientsname']));
				$template->data['text'] = sprintf($this->language->get('text_greeting2'), html_entity_decode($this->request->post['yourname']));

		     }else if($this->request->post['yourname'] != "" && $this->request->post['recipientsname'] == ""){
			  	$template->data['text'] = sprintf($this->language->get('text_greeting2'), html_entity_decode($this->request->post['yourname']));
				
				}else if($this->request->post['yourname'] == "" && $this->request->post['recipientsname'] != ""){
			  	$template->data['text'] = sprintf($this->language->get('text_greeting3'), html_entity_decode($this->request->post['recipientsname']). "\n\n");
			 }else{
				$template->data['text'] = $this->language->get('text_greeting_basic'). "\n\n"; 
			 }
			  if($this->request->post['message'] != ""){
				$template->data['text'] .= $this->language->get('text_message').$this->request->post['message']. "\n\n";
 
			  }
		      $template->data['text'] .= $this->language->get('text_url') . " ".$passed_url;
		      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/wishlist.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/mail/wishlist.tpl');
			} else {
				$html = $template->fetch('default/template/mail/wishlist.tpl');
			}
		       
			$mail = new Mail(); 
		    $mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');			
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($html);
			$mail->send();
			
		
		}
		$this->response->setOutput(json_encode($json));	
     
	}
	public function contact() {
		  $json = array();
		  $this->language->load('wishlist/shared_wishlist');		 
		 
		 
		  $subject = $this->language->get('subject2');
		  $store = $this->language->get('heading_title2');
		 if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$json['error']['email'] = $this->language->get('text_error');
		  
		 }elseif ((utf8_strlen($this->request->post['message']) < 5)) {
      		$json['error']['message'] = $this->language->get('error_4');
			
		  }else{
		$json['error']['success'] = $this->language->get('text_success2');
		//Send Mail
			$template = new Template();			
			$template->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');	
			$template->data['title'] = $this->language->get('text_title2');
			$template->data['store_name'] = $this->config->get('store_name');
			$template->data['store_url'] = $this->config->get('store_url');
		      $template->data['text'] = '';
			 
			  if($this->request->post['message'] != ""){
				$template->data['text'] .= $this->language->get('text_message').$this->request->post['message']. "\n\n";
 
			  }
		      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/wishlist.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/mail/wishlist.tpl');
			} else {
				$html = $template->fetch('default/template/mail/wishlist.tpl');
			}
		       
			$mail = new Mail(); 
		    $mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');			
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($html);
			$mail->send();
			
		
		}
		$this->response->setOutput(json_encode($json));	
     
	}
	public function printWishlist() {
		$this->language->load('account/wishlist');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		
		$this->document->setTitle("Print Wish List");	
      						
		$this->data['heading_title'] = "Print Wish List";	
		
		$this->data['text_empty'] = $this->language->get('text_empty');
     
		$this->data['products'] = array();
	$this->load->model('wishlist/shared_wishlist');
	$wishlist = $this->model_wishlist_shared_wishlist->getWishlist($this->customer->getId(), $this->request->get['list_id']);
		
		if ($wishlist) {

		foreach ($wishlist as $key => $quantity) {
			$product = explode(":" , $key);
			$product_info = $this->model_catalog_product->getProduct($product[0]);
			
			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}
							
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
																			
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'options'      => $this->getListItemOptions($product_info['product_id'],$this->request->get['list_id']),
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}	
	}
if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/print_wishlist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/mail/print_wishlist.tpl';
		} else {
			$this->template = 'default/template/mail/print_wishlist.tpl';
		}
		$this->response->setOutput($this->render());
	}
}
?>