<?php
class MCrewscv extends CI_Model
{
	function __construct()
    {
      parent::__construct();
	  $this->CI = get_instance();	  
    }
	
	function getDataQueryLogin($query = "",$user = "",$pass = "")
    {
    	$dataOut = array();
    	$dataOut = $this->db->query($query,array($user, $pass))->result();
    	return $dataOut;
    }

    function getDataQuery($query = "")
    {
		$this->db = $this->load->database('default', TRUE);
		
    	$dataOut = array();
    	$dataOut = $this->db->query($query)->result();
    	return $dataOut;
    }

    function getData($slc = "*", $db = "", $whereNya = "", $order = "", $grp = "")
	{
		$this->db = $this->load->database('default', TRUE);
		$this->db->select($slc);
		$this->db->from($db);

		if (!empty($whereNya)) {
			if (is_array($whereNya)) {
				$this->db->where($whereNya);
			} else {
				$this->db->where($whereNya);
			}
		}

		if (!empty($order)) {
			$this->db->order_by($order);
		}

		if (!empty($grp)) {
			$this->db->group_by($grp);
		}

		$query = $this->db->get();
		return $query->result();
	}

	function getJoin2($slc = "",$db1 = "",$db2 = "",$joinOn = "",$typeJoin = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db = $this->load->database('default', TRUE);
		
		$this->db->select($slc);
		$this->db->from($db1);
		$this->db->join($db2,$joinOn,$typeJoin);
		if($whereNya != "")
		{
			$this->db->where($whereNya);
		}
		if($order != "")
		{
			$this->db->order_by($order);
		}
		if($grp != "")
		{
			$this->db->group_by($order);
		}
		
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function insData($db = "",$insData = "",$return = "")
	{
		$this->db = $this->load->database('default', TRUE);
		
		$this->db->insert($db,$insData);

		if($return != "")
		{
			return $this->db->insert_id();
		}
	}

	function delData($db = "",$idWhere = "")
	{
		$this->db = $this->load->database('default', TRUE);
		
		$this->db->where($idWhere);
  		$this->db->delete($db);
	}

	function updateData($whereNya = "",$data = "",$tbl = "")
	{
		$this->db = $this->load->database('default', TRUE);
		
		$this->db->where($whereNya);
		$this->db->update($tbl,$data);
	}

	function getDataQueryDB6($query = "")
    {
    	$dataOut = array();
    	$this->db6 = $this->load->database('myapps', TRUE);    	
    	$dataOut = $this->db6->query($query)->result();
    	return $dataOut;
    }

	function insDataDb6($dataIns = "",$dbNya = "")
	{
		$this->db6 = $this->load->database('myapps', TRUE);
		$this->db6->insert($dbNya,$dataIns);
		$getIdNya = $this->db6->insert_id();
		return $getIdNya;
	}

	function updateDataDb6($tbl = "",$dataUpdate = "",$whereNya = "")
	{
		$this->db6 = $this->load->database('myapps', TRUE);

		$this->db6->where($whereNya);
		$this->db6->update($tbl,$dataUpdate);
	}

}
?>