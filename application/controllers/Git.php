<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Git extends VR_Controller {

	private $api_access_token = '92a3ec4efe4fb2a63b7c0381cbcad21f6995bf52';	

	public function __construct(){
		parent::__construct();	
		$this->load->model("Git_model");
	}

	public function index(){
		$this->view_starred();
	}
	
	public function search_starred(){
		$per_hour = 30;
		$interval = ((60 / $per_hour) * 60) + 1; // in seconds
		$per_run = 30;
		
		$ranges = [];
		$ranges[] = ">=100"; // since only 1000 results returned, this will only return the top 1000
		
		/*
		$ranges[] = ">=1000";
		$ranges[] = "750..999";
		$ranges[] = "500..749";
		$ranges[] = "400..499";
		$ranges[] = "350..399";
		$ranges[] = "300..349";
		$ranges[] = "250..299";
		$ranges[] = "200..249";
		//$ranges[] = "150..199";
		//$ranges[] = "100..149";		
		for($i=99;$i > 0;$i--){
			//$ranges[] = $i;
		}
		*/
		
		/*
		$ranges[] = "90..99";
		$ranges[] = "80..89";
		$ranges[] = "70..79";
		$ranges[] = "60..69";
		$ranges[] = "50..59";
		$ranges[] = "40..49";
		$ranges[] = "30..39";
		$ranges[] = "20..29";
		$ranges[] = "10..19";
		$ranges[] = "1..9";
		//$ranges[] = ">=100";
		*/
		
		//print_R($ranges);
		//return;
		
		// remove stars from all records
		$this->Git_model->unstar();
		//echo 'starting: ' . date("m-d-y h:i:s t") . "\n\n";
		foreach($ranges as $rg){
			$repos = 1;
			$page = 1;
			while ($repos && $page < 11) {
    			$result = $this->_do_repo_search($page,$rg);
    			$page++;
    			if($result){
    				$repos = count($result->items);
    				if( $repos ){
    					foreach($result->items as $item){
    						$this->Git_model->update_repository($item);
    					}
    				} else {
    					break;
    				}
    			} else {
    				break;
    			}    	
    			//sleep($interval); // keeps it from hitting abuse limit    			
			} 
		}		

		// remove any that have no stars
		$this->Git_model->remove_unstarred();
		//echo 'starting: ' . date("m-d-y h:i:s t") . "\n\n";
	} 
	
	function _do_repo_search($page=1,$range='>=500'){
		$http_headers = [];
		$http_headers[] = "User-Agent: Awesome-Octocat-App";
		$http_headers[] = 'Authorization: token ' . $this->api_access_token;
		$http_headers[] = "Accept: application/vnd.github.mercy-preview+json";
		echo "\n";
		echo $url = "https://api.github.com/search/repositories?page={$page}&q=stars:{$range}+is:public+language:php&sort=stars&order=desc&per_page=100";
		
		$response = parent::curl_it($url,0,'',$http_headers);
		//echo "\n";
		//print_R($response);

		if($response){
			$results = json_decode($response);
			return $results;
		} else {
			return false;
		}
	}
	
	public function view_starred($page=1){
	
		$this->stencil->css('datatables-plugins/dataTables.bootstrap.css');
		$this->stencil->css('datatables-responsive/dataTables.responsive.css');
		
		$this->stencil->js('datatables/jquery.dataTables.min.js');
		$this->stencil->js('datatables-plugins/dataTables.bootstrap.min.js');
		$this->stencil->js('datatables-responsive/dataTables.responsive.js');
		
		$this->stencil->paint('repos/git_starred');
	}
	
	
}

/* End of file Git.php */
/* Location: ./application/controllers/Git.php */