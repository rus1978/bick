<?php


sleep(1);//для демо :)





if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
	die("Голодный кролик атакует.");
}


include 'classes/Main.php';
include 'classes/Ajax.php';

$o_ajax= new Ajax;
$o_ajax->connect();


switch($_REQUEST['mode']){

	case'loginForm':
		$a_out= $o_ajax->getLoginForm();
	break;
	
	case'logged':
		$a_out= $o_ajax->logged();
	break;

	case'phonebook':
		$a_out= $o_ajax->getListphonebook();
	break;
	
	case'get-user-details':
		$a_out= $o_ajax->getUserDetails();
	break;

	case'contact':
		$a_out= $o_ajax->getContact();
	break;	
	
	case'contact-save':
		$a_out= $o_ajax->saveContact();
	break;
	
	default:
		$a_out= array(
			'status'=> 'error',
			'message'=> 'unknown method'
		);
}

$o_ajax->_print( $a_out );

?>