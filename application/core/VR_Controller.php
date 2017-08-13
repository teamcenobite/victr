<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VR_Controller extends CI_Controller {

	public $is_admin = 0;
	public $requires_login = 0;
	
    function __construct($is_admin=0)
    {
        parent::__construct();                   
		$this->stencil->layout('default_layout');
		$this->stencil->slice(array('head_scripts'=>'layout/head_scripts','header'=>'layout/header','footer'=>'layout/footer','foot_scripts'=>'layout/foot_scripts')); // DEFAULTS        
    }       
    
    public function curl_it($url,$is_post=0,$data_string='',$http_headers=0){
	
		if(empty($url) || !$this->valid_url($url)){
			return false;
		}
		
		$ch = curl_init();
   		curl_setopt ($ch, CURLOPT_URL, $url);
   		if( $is_post ){
   			curl_setopt ($ch, CURLOPT_POST, 1);
   			curl_setopt ($ch, CURLOPT_POSTFIELDS, $data_string);
   			curl_setopt ($ch, CURLOPT_POSTFIELDSIZE, 0);
   		} else {
   			curl_setopt ($ch, CURLOPT_POST, 0);
   		}
   		
   		if( is_array($http_headers) && count($http_headers) ){
   			curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
   			//print_R($http_headers);
   		}
   		
   		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
   		curl_setopt ($ch, CURLOPT_HEADER, 0);
   		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
   		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
   		$output = curl_exec($ch); 
   		$curl_info = curl_getinfo($ch);   		
	   	$http_code = $curl_info['http_code'];

   		curl_close ($ch); 	
   		//echo $url . "\n";
   		//print_R($curl_info);

   		if (strstr($http_code,"200")){
       		return $output;
   		} else {
			return false;
		}   		
   		
    }
    
    public function redirect_it($url){
		if( !headers_sent() ){
			header("Location: $url");
			exit;
		} else {
			echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' . "\n";
			echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=$url\" />\n";
		}
	}
	
	public function valid_url($str) {
    	return (filter_var($str, FILTER_VALIDATE_URL) !== FALSE);
	}

}

/* End of file VR_Controller.php */ 
/* Location: ./application/core/VR_Controller.php */