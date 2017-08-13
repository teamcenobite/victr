<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crons extends VR_Controller {

	public function __construct()
	{
		parent::__construct();	
		if( $_REQUEST['crauth'] != $this->config->item('cron_auth') ){
			echo "Unauthorized";
			exit;
		}
	}
	
	function index(){
		//phpinfo();
	}
	
	/**
	 * Database dump
	 *
	 * @return void
	 */
	function db_backup(){
		if(!$this->input->is_cli_request())
		{
			echo "db backup can only be accessed from the command line";
			return;
		}
	
		// Load the DB utility class
		$this->load->dbutil();
			
		$format = 'txt';
		$ext = "txt";
			
		$prefs = array(
               'tables'      => array(),  			// Array of tables to backup.
               'ignore'      => array(),			// List of tables to omit from the backup
               'format'      => $format,			// gzip, zip, txt
               'filename'    => date('Ymd') . '.' . time() . ".sql.{$ext}",    // File name - NEEDED ONLY WITH ZIP FILES
               'add_drop'    => TRUE,				// Whether to add DROP TABLE statements to backup file
               'add_insert'  => TRUE,				// Whether to add INSERT data to backup file
               'newline'     => "\n"				// Newline character used in backup file
             );

		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup($prefs); 

		// Load the file helper and write the file to your server
		$this->load->helper('file');
		$prefs['filename'] = "db." . strtolower(ENVIRONMENT) . ".sql"; 		
		write_file($this->config->item('DB_BACKUP_PATH') . $prefs['filename'], $backup); 
	}
}

/* End of file Crons.php */
/* Location: ./application/controllers/Crons.php */