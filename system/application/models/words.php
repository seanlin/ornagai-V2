<?php
class Words extends Model {
    
    function Words()
    {
        // Call the Model constructor
        parent::Model();
    }
    
    function add($word,$state,$def)
    {
        
        $this->load->library('session');
        
        $data = array(
               'Word' => $word ,
               'state' => $state ,
               'def' => $def,
               'approve' => 0,
               'usr_id' =>$this->session->userdata('user_id')
            );

		$this->db->insert('dblist', $data);
	
	
		//Add Myanmar English Database
	
		$arr=preg_split("/။/",$def);
	
		foreach ($arr as $val)
		{
	    	$val=trim(str_replace("(","",$val));
	    	$val=trim(str_replace(")","",$val));
	    
	    	if($val!="" and $val!=" ")
	    	{		    
				$this->load->library("zawgyi");
				$mya_def=$this->zawgyi->normalize($val,"|",true);
		 		$mya_def="|".$mya_def;
				$data = array(
		   			'Word' => $word ,
		   			'state' => $state ,
		   			'def' => $mya_def,
		   			'approve' => 0,
		   			'usr_id' =>$this->session->userdata('user_id')
		     	);

				$this->db->insert('mydblist', $data);
	    	}
		}
    }
    
    function get_en_unapprove()
    {
    	$this->db->select('dblist.id as word_id,Word,state,def,approve,usr_id,username');
		$this->db->from('dblist');
    	$this->db->join('user', 'dblist.usr_id=user.id');
    	    	$this->db->where("approve",0);
    	$result=$this->db->get();
    	return $result;
    }
    
    
    function get_my_unapprove()
    {
    	$this->db->where("approve",0);
    	$result=$this->db->get("mydblist");
    	return $result;
    }
    
    
}
?>