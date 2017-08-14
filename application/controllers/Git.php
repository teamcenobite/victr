<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Git extends VR_Controller {

	private $api_access_token = '';	

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();	
		$this->load->model("Git_model");
	}

	/**
	 * Default controller function
	 *
	 * @return void
	 */
	public function index(){
		$this->view_starred();
	}
	
	/**
	 * Search & Update top starred Git repositories
	 *
	 * @return void
	 */
	public function search_starred($do_update=0){
		$data = [];
		
		if(!empty($do_update)){		
			$per_hour = 30;
			$interval = ((60 / $per_hour) * 60) + 1; // in seconds
		
			$ranges = [];
			$ranges[] = ">=100"; // since only 1000 results returned, this will only return the top 1000		
		
			// remove stars from all records
			$this->Git_model->unstar();
			foreach($ranges as $rg){
				$repos = 1;
				$page = 1;
				while ($repos && $page < 11) {
    				$result = $this->_do_repo_search($page,$rg);
    				$page++;
    			
    				if( !empty($result->error_message) ){
    					$this->errors[] = $result->error_message;
    					break;
    				} else if($result){
    					$repos = count($result->items);
    					if( $repos ){
    						foreach($result->items as $item){
    							$this->Git_model->update_repository($item);
    						}
    					} else {
    						break;
    					}
    				} else {
    					$this->errors[] = "Unspecified Error";
    					break;
    				}    	
    				//sleep($interval); // keeps it from hitting abuse limit    			
				} 
			}		
		
			if( $page != '11' ){
				$this->errors[] = "Only received data from " . ($pages - 1) . " searches...should have been 10.";
			}		

			// remove any that have no stars
			$this->Git_model->remove_unstarred();
		
			if( count($this->errors) ){
				// notify of error(s)
				$data['messages']['errors'] = $this->errors;
			} else {
				// notify of success
				$data['messages']['success_message'] = "The listing of Git repositories has been updated.";
			}
		}		
		$this->stencil->paint('repos/update_starred',$data);
		
	} 
	
	/**
	 * Internal function to actually perform a public PHP repos search based on stars
	 *
	 * @param int $page
	 * @param string $range
	 * @param string $language
	 * @param string $sort
	 * @param string $public_private
	 *
	 * @return array or false
	 */
	protected function _do_repo_search($page=1,$range='>=500',$language='php',$sort='stars',$public_private='public'){
		$http_headers = [];
		$http_headers[] = "User-Agent: Awesome-Octocat-App";
		if( !empty($this->api_access_token) ){
			$http_headers[] = 'Authorization: token ' . $this->api_access_token;
		}		
		$http_headers[] = "Accept: application/vnd.github.mercy-preview+json";
		
		$url = "https://api.github.com/search/repositories?page={$page}&q=stars:{$range}+is:{$public_private}+language:{$language}&sort={$sort}&order=desc&per_page=100";
		
		$response = parent::curl_it($url,0,'',$http_headers);
		
		$result_code = $response['result_code'];
		$output = $response['output'];	

		if($result_code == '200'){
			$results = json_decode($output);
			return $results;
		} else {
			$results = json_decode($output);
			$results->error_message = $results->message;
			$results->error_url = $url;
			return $results;
		}
	}
	
	/**
	 * View table of top starred Git repositories
	 *
	 * @return void
	 */
	public function view_starred($page=1){
	
		$this->stencil->css('datatables-plugins/dataTables.bootstrap.css');
		$this->stencil->css('datatables-responsive/dataTables.responsive.css');
		
		$this->stencil->js('datatables/jquery.dataTables.min.js');
		$this->stencil->js('datatables-plugins/dataTables.bootstrap.min.js');
		$this->stencil->js('datatables-responsive/dataTables.responsive.js');
		
		$data = [];
		$data['repo_list'] = $this->Git_model->getAll(0,$this->Git_model->table,'stargazers_count DESC');
		$data['repo_list_count'] = count($data['repo_list']);
		
		$this->stencil->paint('repos/git_starred',$data);
	}	
	
}

/* End of file Git.php */
/* Location: ./application/controllers/Git.php */