<?php

// require_once 'config.php';

/**
 * 
 */




class DBConnection
{
	public $conn;

	function __construct()
	{
		
	}

	public function mConnect(){
		$this->conn = mysqli_connect('localhost','aeshairc_iphixx','~K03u*+3fS.C','aeshairc_iphixx_api');

		if (mysqli_connect_errno()) {
			echo "Error: ". mysqli_connect_error();
		}else
		

		return $this->conn;
	}
	public function mConnect1(){
		$this->conn = mysqli_connect('localhost','aeshairc_iphixx','~K03u*+3fS.C','aeshairc_iphixx_api');

		if (mysqli_connect_errno()) {
			echo "Error: ". mysqli_connect_error();
		}else
		

		return $this->conn;
	}
}