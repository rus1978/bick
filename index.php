<?php

include 'classes/Main.php';
$o_main= new Main;
$db_link= $o_main->connect();


$main_tpl= $o_main->getTpl('main.html');
$s_head= $o_main->getTpl('chunks/head.tpl');
$s_loader= $o_main->getTpl('chunks/loader.tpl');

if( $o_main->isAtorized ){
	$s_head_for_auth= $o_main->getTpl('chunks/head_for_auth.tpl');
	$s_menu= $o_main->getTpl('menues/menu-authorized.tpl');	
	$s_pagetitle= 'Добро пожаловать '.$_SESSION['user']['name'];
}
else{
	$s_head_for_auth= '';
	$s_menu= $o_main->getTpl('menues/menu-not-auth.tpl');
	$s_pagetitle= 'Главная страница';
}



$s_out= $o_main->parseText($main_tpl, array(
	'[+head+]'=> $s_head,
	'[+head_for_auth+]'=> $s_head_for_auth,
	'[+pagetitle+]'=> $s_pagetitle,
	'[+main-menu+]'=> $s_menu,
	'[+content+]'=> '',
	'[+loader+]'=> $s_loader
));

$o_main->_print($s_out);
?>
