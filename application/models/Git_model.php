<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Git_model extends VR_Model {

    var $table   = 'git_repo';
    var $id_field = 'repository_id';

	/**
	 * Constructor
	 *
	 * @return void
	 */
    function __construct()    {
        // Call the Model constructor
        parent::__construct();
    }
    
    /**
	 * Insert function
	 *
	 * @param array $params
	 * @param string $tablename
	 *
	 * @return boolean
	 */
    function insert($params,$tablename='')    {
    	if( empty($tablename) ){
    		$tablename = $this->table;    		
    	}    	
        return parent::insert($params,$tablename);
    }

	/**
	 * Update function
	 *
	 * @param array $params
	 * @param string $tablename
	 * @param string or array $where
	 *
	 * @return boolean
	 */
    function update($params,$tablename='',$where=0)    {
		return parent::update($params,$tablename,$where);
    }       
    
    /**
	 * Updates repository info
	 * This method executes a REPLACE statement, which is basically the SQL standard for DELETE + INSERT, 
	 * using PRIMARY and UNIQUE keys as the determining factor. 
	 * In our case, it will save you from the need to implement complex logics with different combinations of 
	 * select(), update(), delete() and insert() calls.
	 *
	 * @param object $params
	 *
	 * @return boolean
	 */
    public function update_repository($repo_obj){

    	$repository_id = (int) $repo_obj->id;
    	if( empty($repository_id) ){
    		return false;
    	}
    	print_R($repo_obj);
    	$data = array(
    		"repository_id"=>$repository_id,
    		"name"=>$repo_obj->name, 
    		"description"=>$repo_obj->description,
    		"html_url"=>$repo_obj->html_url,
    		"created_at"=>$repo_obj->created_at,
    		"updated_at"=>$repo_obj->updated_at,
    		"stargazers_count"=>(int) $repo_obj->stargazers_count
    	);
    	$this->db->replace($this->table, $data);     	
    	return true;
    }
    
    /**
	 * Resets stargazers_count to 0 for all records
	 *
	 * @return boolean
	 */
    public function unstar(){
    	return parent::update(array("stargazers_count"=>0),$this->table);
    	return true;
    }
    
    /**
	 * Removes repos that have a stargazers_count = 0
	 *
	 * @return boolean
	 */
    public function remove_unstarred(){    
    	$this->db->where('stargazers_count', 0);
		$this->db->delete($this->table);
		return true;
    }    

	/**
	 * Delete function
	 *
	 * @return void
	 */
    function delete( $id=0 )
    {
       $result = parent::delete($id);
        return $result;
    }    
    
    /**
	 * Validate insert params
	 *
	 * @param array $params
	 *
	 * @return boolean
	 */
    function validate_insert($params){
    	return true;
    }
    
    /**
	 * Validate update params
	 *
	 * @param array $params
	 *
	 * @return boolean
	 */
    function validate_update($params){
    	return true;
    }      
    
}

/* End of file Git_model.php */ 
/* Location: ./application/models/Git_model.php */