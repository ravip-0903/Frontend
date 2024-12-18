<?php

	/* 	This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 Your code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			output_data : JSON (I am using)
			status_code : Send status message for headers 	*/

	class blocks extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
                
                function msort($array, $key, $sort_flags = SORT_REGULAR) {
                    if (is_array($array) && count($array) > 0) {
                        if (!empty($key)) {
                            $mapping = array();
                            foreach ($array as $k => $v) {
                                $sort_key = '';
                                if (!is_array($key)) {
                                    $sort_key = $v[$key];
                                } else {
                                    // @TODO This should be fixed, now it will be sorted as string
                                    foreach ($key as $key_key) {
                                        $sort_key .= $v[$key_key];
                                    }
                                    $sort_flags = SORT_STRING;
                                }
                                $mapping[$k] = $sort_key;
                            }
                            arsort($mapping, $sort_flags);
                            $sorted = array();
                            foreach ($mapping as $k => $v) {
                                $sorted[] = $array[$k];
                            }
                            return $sorted;
                        }
                    }
                    return $array;
                }
                

                public function hot_deals(){                    
                    //the category id has been set static. It needs to be changed manually whenever promotional link for hot deals changes in website.
                    if($this->get_request_method() != "GET"){
                        //$category_data = fn_get_category_data(1781, CART_LANGUAGE, '*');
                        $this->response('',406);                        
                    }                           
                    $category = new categories();
                    $result = $category->category(1);
                    /*
                    $products = $result['products']; 
                    //calculate discount percentage                    
                    foreach($products as $k => $v){                     
                        $dis_per = calculate_3rd_price_percentage($v, $v['third_price']);
                        $products[$k]['discount_percentage'] = $dis_per;
                    }
                    $products1 = $this->msort($products, 'discount_percentage', $sort_flags = SORT_DESC);
                    $result['products'] = $products1;
                    */
                    if(!empty($result['products'])){
                        $this->response($this->json($result), 200);                        
                    }
                    else{
                        $msg = array('status' => "Success", "msg" => fn_get_lang_var('api_no_deals'));
                        $this->response($this->json($msg), 200);                       
                    }
                                   
                }
		
                public function home_deals() {

                    //the block id has been set static. It needs to be changed manually whenever promotional link for home page deals changes in website.

                    if ($this->get_request_method() == "GET") {

                        $arr = db_get_array("select * from clues_mobile_deals");
                        $data = array();
                        $var = 0;
                        while (1) {
                            if ($var == 6)
                                break;
                            $data[$var]["deal_name" . ($var + 1)] = $arr[0]["deal_name" . ($var + 1)];
                            $data[$var]["banner" . ($var + 1) . "_url"] = $arr[0]["banner" . ($var + 1) . "_url"];
                            $data[$var]["object_type" . ($var + 1)] = $arr[0]["object_type" . ($var + 1)];
                            $data[$var]["object_id" . ($var + 1)] = $arr[0]["object_id" . ($var + 1)];
                            $var++;
                        }
                        $this->response($this->json($data), 200);
                        /* $params = array();
                          $params['block_data']['block_id'] = 156;                // 156 ---> block id of Main banner
                          $description = fn_get_html_content($params);
                          $this->response($this->json($description), 200); */
                    }
                    else {

                        $this->response('', 406);
                    }
                }
                
                public function featured_products(){
                                     
                    if($this->get_request_method() == "GET"){
                        
                            
                            $result = array();
                            $category = new categories();
                            $category->_request['cat_id'] = 3089;
                            $result = $category->category(1);
                            $this->response($this->json($result), 200);                                                           
                    }
                    else{
                        $this->response('',406);
                    }             
                }
                
                public function pages(){
                    
                    $page1 = $this->_request['page'];
                    $all_pages = array();
                    $block = array();
		    $block = Registry::get('config.api_blocks');
		    $page = Registry::get('config.api_pages');


                    if(isset($page1)){
                        if(!array_key_exists($page1,$page)){
                            $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_page'));
                            $this->response($this->json($msg), 400);                            
                        }   
                        
                        $data = fn_get_page_data($page[$page1], CART_LANGUAGE, false);
                        if($data['status'] != 'A'){
                            $all_pages[$page1]['page_data'] = fn_get_lang_var('api_page_not_available');
                            $status = 204;
                        }
                        else{
                            $all_pages[$page1]['page_data'] = $data;
                            $params['block_data']['block_id'] = $block[$page1];
                            $all_pages[$page1]['page_content'] = fn_get_html_content($params);
                            $status = 200;
                       }
                       $this->response($this->json($all_pages), $status);
                        
                    }
                    foreach($block as $k => $id){
                                          
                        $data = fn_get_page_data($page[$k], CART_LANGUAGE, false);
                        if($data['status'] != 'A')
                            $all_pages[$k]['page_data'] = fn_get_lang_var('api_page_not_available');
                        else{
                            $all_pages[$k]['page_data'] = $data;                            
                            $params['block_data']['block_id'] = $id;
                            $all_pages[$k]['page_content'] = fn_get_html_content($params);
                        }
                    }
                    
                    $this->response($this->json($all_pages), 200);
                                  
                }
               
                /*
		 *	Encode array into JSON
		*/
		
		function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
?>