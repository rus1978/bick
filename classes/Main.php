<?php

class Main{
	
	public $isAtorized;
	public $db_link;
	private $charset= 'utf-8';
		
	function __construct(){
		error_reporting(0);
		session_start();
		
		$this->check_logout();
		$this->isAtorized= !!$_SESSION['user'];
		
		define(BASE_PATH, rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/');
		define(CHARSET, $this->charset);
	}
	function __destruct() {
		!$this->db_link or mysql_close($this->db_link);
	}
	
	public function check_logout(){
		if($_GET['mode'] != 'logout')return;
		
		unset( $_SESSION['user'] );
	
		$this->sendRedirect('/');
	}
	
	public function sendRedirect($s_url='/'){
		$str= 'Location: '
		.strtok($_SERVER['SERVER_PROTOCOL'], '/' )
		.'://'.$_SERVER['HTTP_HOST']. $s_url;
		header($str);
	
		exit;	
	}
	public function escape($str){
		return mysql_real_escape_string(trim( $str ));
	}	
	public function getTpl($s_file){
		$filename= BASE_PATH.'assets/templates/'.$s_file;
		
		if( empty($s_file) || !is_file($filename) ){
			return 'не найден файл шаблона '. $filename;
		}
		$tpl= file_get_contents($filename);
		return $tpl;
	}
	
	public function parseText($s_tpl, $a_phs){
		$s_out= str_replace(array_keys($a_phs), array_values($a_phs), $s_tpl);
		return $s_out;
	}
	
	public function connect(){
		$db_host= "localhost";
		$db_login= 'bick_u';
		$db_password= '07mkebXH';
		$db_name= 'bick_db';
		
		$this->db_link= mysql_connect($db_host, $db_login, $db_password);
		$this->db_link or die("Could not connect: ".mysql_error());
	
		mysql_select_db( $db_name ) or die("Could not select database");
		mysql_query("SET NAMES utf8");
		
		return $this->db_link;
	}
	
	public function _print($s_out){
		header('Content-Type:text/html;charset='.CHARSET);
		echo $s_out;
	}

}
?>