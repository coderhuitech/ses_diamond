<?php
class View_model extends CI_Model{	
	function get_subjects(){
		$query=$this->db->get('subjects');
		return $query->result_array(); //returning total result
	}
	function get_chapters_by_id($subject_id){
		$this->db->where('subject_id',$subject_id);
		$result=$this->db->get('chapters');
		return $result->result_array();
	}
	
}
?>
