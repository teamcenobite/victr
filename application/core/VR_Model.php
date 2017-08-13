<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VR_Model extends CI_Model {

	public $id = 0;
	public $data = array();
	public $errors = array();
	protected $fields = array();
	protected $update_params = array();
	protected $update_fields = array();
	protected $update_values = array();	
	protected $class_name = "";

	/**
	 * Constructor
	 *
	 * @return void
	 */
    function __construct()
    {
        parent::__construct();                
    }
    
    public function set_id( $id=0 ){
    	$this->id = (int) $id;
    }
    
    public function get_last_query(){    	
    	return $this->db->last_query();    	
    }
    
    public function display_last_query(){    	
    	print_r($this->db->last_query());    	
    }
    
    public function getOne( $id=0, $tablename='', $filters=0 ){    	
    	
    	$where = array();
    	
    	if( empty($tablename) ){
    		$tablename = $this->table;
    	}    	
    	
    	if( !empty($id) ){
    		$this->set_id($id);
    		$where[$this->id_field] = $id;
    	}
    	
    	if( is_array($filters) && count($filters) ){
    		$this->get_table_fields($tablename);   		
    		foreach( $filters as $k=>$v ){
				if( !empty($this->fields[$k]) ){
					$where[$k] = $v;
				}
			}
    	}
    	
    	$this->db->select('*')->from($tablename)->where($where)->limit(1);
		$result = $this->db->get(); 
		$r = $result->result_array();
		if( count($r) ){
			return $r[0];
		}
		return array();
    }
    
    public function getAll( $filters=0, $tablename='', $orderby='' ){
    
    	if( empty($tablename) ){
    		$tablename = $this->table;
    	}
    
    	$list = array();
    	$where = array();
    	$this->get_table_fields($tablename);
    	if( is_array($filters) && count($filters) ){
    		foreach( $filters as $k=>$v ){
				if( !empty($this->fields[$k]) ){
					$where[$k] = $v;
				}
			}
    	}
    	if( !empty($orderby) ){
    		$this->db->order_by($orderby);
    	} else if(!empty($this->table_id)){
    		$this->db->order_by($this->table_id);
    	}
    	$this->db->where( $where );
    	$result = $this->db->get($tablename);
    	return $result->result_array();
    }
    
    public function getAllObjects( $filters=0, $tablename='', $orderby='' ){
    	if( empty($tablename) ){
    		$tablename = $this->table;
    	} 
    	$list = array();
    	$where = array();
    	$this->get_table_fields($tablename);
    	foreach( $filters as $k=>$v ){
			if( !empty($this->fields[$k]) ){
				$where[$k] = $v;
			}
		}
		if( !empty($orderby) ){
    		$this->db->order_by($orderby);
    	}
    	$this->db->select($this->id_field)->from($tablename)->where( $where );
    	$result = $this->db->get($tablename);
    	foreach($result->result_array as $row){
    		$list[] = new $this->class_name($row[$this->id_field]);
    	}
    	return $list;
    }    
    
    /**
	 * Sets the record data in the $this->data array
	 *
	 * @return void
	 */
    public function setData(){    	
    	$this->db->select('*')->from($this->table)->where($this->id_field, $this->id)->limit(1);
		$result = $this->db->get();  
		$this->setDataFromResult($result);
	}
	
	/**
	 * Formats the record data in the $this->data array
	 *
	 * @return void
	 */
	public function reformatData(){
		/*
		foreach( $this->data as $k=>$v ){
			$this->data[$k] = stripslashes($v);
		}
		*/
	}
	
	/**
	 * Sets the record data in the $this->data array
	 *
	 * @param CI result object
	 *
	 * @return void
	 */
	public function setDataFromResult($result){
		$r = $result->result_array();
		if( count($r) ){
			$this->data = $r[0];
		} else {
			$this->data = array();
			return $this->data;
		}
		$this->id = $this->data[$this->id_field];
		$this->reformatData();
		return $this->data;
	}
	
	public function getInsertId($result){
		return $result->result_array();
	}
	
	/**
	 * Sets the record data in the $this->data array based on the field/value passed in rather than the id field
	 *
	 * @return array $this->data
	 */
	public function setDataFromField($field,$value){
		if( empty($field) || empty($value) ){
			return false;
		}
		$this->get_table_fields($this->table);		
		if( empty($this->fields[$field]) ){
			return false;
		}				
		$this->db->select('*')->from($this->table)->where($field, $value)->limit(1);		
		$result = $this->db->get();  
		$this->setDataFromResult($result);		
		return $this->data;
	}   
    

	/**
	 * Shared insert function
	 *
	 * @param array $params
	 * @param string $tablename can set the tablename
	 *
	 * @return boolean
	 */
	function insert( $params, $tablename='', $skip_validation=0 )
    {
    	if( empty($tablename) ){
    		$tablename = $this->table;
    	}
    	if( empty($tablename) ){
    		$this->errors[] = "empty tablename";
    		return false;
    	}
    	
    	$this->update_params = $params;    	    	
   	
        if( $skip_validation || $this->validate_insert() ){            	    	
        	$this->set_update_params($this->update_params,$tablename);
        	$this->db->insert($tablename, $this->update_params);
        	$this->id = $this->db->insert_id();
        	return $this->id;
        } else {
        	return false;
        }
    }

	/**
	 * Shared update function
	 *
	 * @param array $params
	 * @param string $tablename can set the tablename
	 * @param array $where array or string for where clause
	 *
	 * @return boolean
	 */
    function update( $params, $tablename=0, $where=0, $skip_validation=0, $id=0 )
    {

    	if( empty($tablename) ){
    		$this->errors[] = "empty table name";
    		$tablename = $this->table;
    	}
    	if( empty($tablename) ){
    		return false;
    	}
    	if( empty($where) || !is_array($where) ){
    		if( !empty($where) ){
    			// where is a string hopefully
    		} else if( !empty($this->id_field) && !empty($this->id) ){
    			$where = array($this->id_field => $this->id);
    		} else {
    			$where = array();
    		}
    	}    	
    	
    	$this->update_params = $params;    	
    	
        if( $skip_validation || $this->validate_update($id) ){      
        	$this->set_update_params($this->update_params,$tablename);     
        	if( !count($this->update_params) ){
        		$this->errors[] = "missing params";
        		return false;
        	}    		
        	$this->db->update($tablename, $this->update_params, $where);
        	return true;
        } else {
        	return false;
        }
    }

	/**
	 * Shared delete function
	 *
	 * @param int $id if empty, uses the id set in constructor
	 *
	 * @return void
	 */
    function delete( $id=0,$where=0 )
    {
    	if( empty($id) ){
    		$id = $this->id;
    	}
    	if( empty($id) ){
    		return false;
    	}
		$this->db->delete($this->table, array($this->id_field => $id)); 
		return true;
    }
    
    /**
	 * Validates insert parameters
	 *
	 * @param array $params
	 *
	 * @return boolean
	 */
    protected function validate_insert(){    	
    	return true;
    }
    
    /**
	 * Validates update parameters
	 *
	 * @param array $params 
	 *
	 * @return boolean
	 */
    protected function validate_update(){
    	return true;
    }
    
    /**
	 * Sets the insert/update params in an array, checks that each field is really in table
	 *
	 * @param int $id 
	 * @param int $set_data
	 *
	 * @return boolean
	 */
    protected function set_update_params( $params, $tablename='' ){
    	$this->get_table_fields($tablename);
    	$this->update_params = array();
    	foreach( $params as $k=>$v ){
    		if( !empty($this->fields[$k]) ){
    			$this->update_params[$k] = $v;
    		}
    	}
    }
    
    /**
	 * Retrieves table fields to validate the update/insert params exist
	 *
	 * @param int $id 
	 * @param int $set_data
	 *
	 * @return void
	 */
    protected function get_table_fields( $table='' ){
    	if( empty($table) ){
    		$table = $this->table;
    	}
    	if( empty($table) ){
    		return false;
    	}
    	
    	$fields = $this->db->list_fields( $table );

		foreach ($fields as $field)
		{
			//echo $field;
			$this->fields[$field] = $field;
		}
    	return $this->fields;
    }    
    
}

/* End of file VR_Model.php */ 
/* Location: ./application/core/VR_Model.php */