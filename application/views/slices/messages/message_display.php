<?php
$flash_error = $this->session->flashdata('flash_error');
if(!empty($flash_error)){
	$this->load->view("slices/messages/flash_error");
} 
$flash_info = $this->session->flashdata('flash_info');
if(!empty($flash_info)){
	$this->load->view("slices/messages/flash_info");
}
$flash_warning = $this->session->flashdata('flash_warning');
if(!empty($flash_warning)){
	$this->load->view("slices/messages/flash_warning");
}
$flash_success = $this->session->flashdata('flash_success');
if(!empty($flash_success)){
	$this->load->view("slices/messages/flash_success");
}

if( !empty($messages['errors']) && count($messages['errors']) ){
	$this->load->view("slices/messages/error_list");
} else if(!empty($messages['error_message'])){
	$this->load->view("slices/messages/error");
} else if(!empty($messages['success_message'])){
	$this->load->view("slices/messages/success");
} else if(!empty($messages['info_message'])){
	$this->load->view("slices/messages/info");
} else if(!empty($messages['warning_message'])){
	$this->load->view("slices/messages/warning");
} else if(!empty($_SESSION['session_danger'])){
	$this->load->view("slices/messages/session_danger");
	unset($_SESSION['session_danger']);
} else if(!empty($_SESSION['session_info'])){
	$this->load->view("slices/messages/session_info");
	unset($_SESSION['session_info']);
} else if(!empty($_SESSION['session_success'])){
	$this->load->view("slices/messages/session_success");
	unset($_SESSION['session_success']);
}
