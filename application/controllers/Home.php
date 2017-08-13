<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends VR_Controller {

	public function __construct(){
		parent::__construct();	
	}

	/**
	 * Index Page for this controller.
	 *
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()	{
		$this->stencil->paint('home');
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */