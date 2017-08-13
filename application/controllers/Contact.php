<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends QP_Controller {

	public function __construct(){
		parent::__construct();	
	}

	public function index($is_ajax=0){
		$this->stencil->title('Victr Demo - Contact Us');
		$data = array();		
		
		if( $_SERVER['REQUEST_METHOD'] =='POST'){
			$this->load->library('email');
			$this->email->clear();

			$data['email_name'] = "Victr Demo Admin";
			
			$email_message_body = "";
			$email_message_body .= "<div>From: {$_REQUEST['name']}</div>\n";
			$email_message_body .= "<div>Email: {$_REQUEST['email']}</div>\n";
			$email_message_body .= "<div>Subject: {$_REQUEST['subject']}</div>\n";
			$email_message_body .= "<div>Message: {$_REQUEST['message']}</div>\n";			
			
			$data['email_message_body'] = $email_message_body;						

			$this->email->set_mailtype("html");
			$this->email->to("info@quipist.com");
			$to_email = 'info@quipist.com';
			$data['messages']['success_message'] = "Your message has been sent.  Thank you for your comments, questions, and feedback.";
			$this->email->subject('Quipist Contact Form Submission - ' . $_REQUEST['subject']);
			$message = $this->load->view('email/default',$data,TRUE); // this will return you html data as message
			$this->email->message($message);		
       		
        	$this->email->from($_REQUEST['email'],$_REQUEST['name']);
        	
        	$this->email->send(); 	      	

		}
		
		
		$this->stencil->js('quipist/contact.js');
		
		$this->load->model("Pages_model");
		$data['excerpts'] = $this->Pages_model->get_pages(array("contact","contact-sidebar","contact-terms"));
		
		$data['page_heading'] = "Contact Quipist";
		//$this->_get_page_ads();
		$this->stencil->paint('contact',$data);
	}	
}

/* End of file Contact.php */
/* Location: ./application/controllers/Contact.php */