<?php
class Ajax extends Main{

	function __construct() {
		parent::__construct();
	}
	
	public function getLoginForm(){
		$s_form= $this->getTpl('contents/login-form.tpl');
	
		$a_res= array(
			'status'=> 'success',
			'html'=> $s_form
		);
		return $a_res;
	}
	public function logged(){

		$a_res= array(
			'status'=> 'error',
			'message'=> 'Не верный логин или пароль'
		);
		
		$a_row= mysql_fetch_assoc(mysql_query("
			SELECT
				id,
				username,
				firstname,
				lastname,
				country_id,
				city,
				address,
				published
			FROM users
			WHERE
				username='". $this->escape($_POST['login']) ."' AND
				password='".md5($_POST['password'])."'
			LIMIT 1"
		));
		
		if($a_row){
			$_SESSION['user']= $a_row;
			
			$a_res= array(
				'status'=> 'success',
				'message'=> 'Добро пожаловать на сайт '. $a_row['firstname']
			);
		}

		return $a_res;		
	}
	public function getListphonebook(){
		
		$s_cont= $this->getTpl('contents/phonebook.tpl');
		$s_list_out= $this->getTpl('chunks/phonebook/list-out.tpl');
		$s_list_row= $this->getTpl('chunks/phonebook/list-row.tpl');
		$s_list_viewbutt= $this->getTpl('chunks/phonebook/list-viewbutt.tpl');
		
		$a_item_row= array();
		$result=  mysql_query("
			SELECT
				id,
				firstname,
				lastname,
				published
			FROM users
			ORDER BY lastname ASC, firstname ASC
			LIMIT 999");
		while($a_row= mysql_fetch_assoc($result)){
			$a_item_row[]= $this->parseText($s_list_row, array(
				'[+uid+]'=> $a_row['id'],
				'[+firstname+]'=> $a_row['firstname'],
				'[+lastname+]'=> $a_row['lastname'],
				'[+list-viewbutt+]'=> $a_row['published'] ? $s_list_viewbutt : ''
			));
		}
		$s_listblock= $this->parseText($s_list_out, array(
			'[+wrapper+]'=> implode("\n", $a_item_row)
		));
		
		$s_out= $this->parseText($s_cont, array(
			'[+longtitle+]'=> 'Public Phonebook',
			'[+phonebook-list+]'=> $s_listblock
		));
		
		$a_res= array(
			'status'=> 'success',
			'html'=> $s_out
		);
		return $a_res;
	}
	public function getUserDetails(){
		$i_uid= (int)$_POST['uid'] or die('incorrect uid');

		$arr= $this->buildArrDetails($i_uid);
		$s_out= $this->setTplDetails($arr);
		
		if($arr){
			$a_res= array(
				'status'=> 'success',
				'html'=> $s_out
			);
		}
		else{
			$a_res= array(
				'status'=> 'error',
				'message'=> 'Нет детальной информации у этого пользователя'
			);
		}
	
		return $a_res;
	}
	private function buildArrDetails($i_uid){
		
		$arr= array();
		
		$sql= "
			SELECT
				u.id,
                c.name AS 'country',
                u.city,
                u.address,
				ue.id AS 'email_id',
                ue.email,
				up.id AS 'phone_id',
				up.phone
			FROM users u
            LEFT JOIN user_emails ue ON ue.uid= u.id AND ue.published= 1
			LEFT JOIN user_phones up ON up.uid= u.id AND up.published= 1
			LEFT JOIN countries c ON c.id= u.country_id
			WHERE
				u.id= ".$i_uid." AND
				u.published= 1";

		$result=  mysql_query($sql);
		while($a_row= mysql_fetch_assoc($result)){

			$arr['Address']['city']= $a_row['city'];
			$arr['Address']['address']= $a_row['address'];
			$arr['Address']['country']= $a_row['country'];

			
			$arr['Phone numbers'][ $a_row['phone_id'] ]= $a_row['phone'];
			$arr['Emails'][ $a_row['email_id'] ]= $a_row['email'];
		}
		
		return $arr;
	}
	private function setTplDetails($arr){
		if(!$arr)return false;
		
		$s_column= $this->getTpl('chunks/phonebook/details-column.tpl');
		$s_row= $this->getTpl('chunks/phonebook/details-row.tpl');
		$s_out= $this->getTpl('chunks/phonebook/details-out.tpl');
		
		$a_column_item= array();
		foreach($arr as $s_title=>$a_column){
			if(!$a_column)continue;
			
			$a_row_item= array();
			foreach($a_column as $s_item){
				$a_row_item[]= $this->parseText($s_row, array(
					'[+item+]'=> $s_item
				));
			}
			$a_column_item[]= $this->parseText($s_column, array(
				'[+title+]'=> $s_title,
				'[+wrapper+]'=> implode("\n", $a_row_item),
			));
		}
		
		
		$s_block= $this->parseText($s_out, array(
			'[+wrapper+]'=> implode("\n", $a_column_item),
		));
		
		return $s_block;
	}
	public function getContact(){
		if( !$this->isAtorized){
			return array(
				'status'=> 'error',
				'message'=> 'Вы не авторизованы!'
			);
		}

		$arr= $this->buildArrContact();
		
		$s_columns= $this->setTplContact($arr);
		
	
		
		$s_form= $this->getTpl('contents/my-contact.tpl');
		$s_cont= $this->parseText($s_form, array(
			'[+firstname-value+]'=> $_SESSION['user']['firstname'],
			'[+lastname-value+]'=> $_SESSION['user']['lastname'],
			'[+address-value+]'=> $_SESSION['user']['address'],
			'[+city-value+]'=> $_SESSION['user']['city'],
			'[+country-sel-options+]'=> $this->buildSelectCountry($_SESSION['user']['country_id']),
			'[+contact-publish-checked+]'=> ($_SESSION['user']['published'] ? 'checked' : ''),
			
			'[+wrapper+]'=> $s_columns
		));
		
	
		$a_res= array(
			'status'=> 'success',
			'html'=> $s_cont
		);
		return $a_res;
	}
	private function buildArrContact(){
		
		$arr= array();

		$sql= "
			SELECT
            	'email' AS 'key',
				'Emails' AS 'title', -- for html
				id,
				email AS 'value',
				published
			FROM user_emails e
			WHERE uid= ". (int)$_SESSION['user']['id'] ."

            UNION ALL
            
 			SELECT
            	'phone' AS 'key',
				'Phones' AS 'title', -- for html
				id,
				phone AS 'value',
				published
			FROM user_phones
			WHERE uid= ".(int)$_SESSION['user']['id'];

		$result=  mysql_query($sql);
		while($a_row= mysql_fetch_assoc($result)){
			$arr[ $a_row['key'] ][]= $a_row;
		}

		$arr= array_merge(//set default, min 1 row
			array(
				'email'=> array(array(
					'key' => 'email',
					'title'=> 'Emails',
					'id' => '',
					'value' => '',
					'published' => 1
				)),
				'phone'=> array(array(
					'key' => 'phone',
					'title'=> 'Phones',
					'id' => '',
					'value' => '',
					'published' => 1
				))
			),
			$arr
		);

		return $arr;
	}
	private function setTplContact($arr){
		if(!$arr)return false;
		
		$s_column= $this->getTpl('chunks/mycontact/column.tpl');
		$s_row= $this->getTpl('chunks/mycontact/row.tpl');

		
		$a_column_item= array();
		foreach($arr as $s_key=>$a_column){
			
			if(!$a_column)continue;
			
			$a_row_item= array();
			foreach($a_column as $a_item){
				$s_column_title= $a_item['title'];
				$a_row_item[]= $this->parseText($s_row, array(
					'[+nameid+]'=> ($a_item['id'] ? 'id-'.$a_item['id'] : ''),
					'[+name+]'=> $s_key,
					'[+value+]'=> $a_item['value'],
					'[+checked+]'=> ($a_item['published'] ? ' checked' : '')
				));
			}
			$a_column_item[]= $this->parseText($s_column, array(
				'[+title+]'=> $s_column_title,
				'[+wrapper+]'=> implode("\n", $a_row_item),
			));
		}
		
		
		$s_columns= implode("\n", $a_column_item);
		
		return $s_columns;
	}
	public function buildSelectCountry($country_id){
		
		$s_option= $this->getTpl('chunks/select-option.tpl');
		
		$result=  mysql_query("
			SELECT id, name
			FROM countries
			ORDER BY name ASC"
		);
		while($a_row= mysql_fetch_assoc($result)){
			$a_option[]= $this->parseText($s_option, array(
				'[+value+]'=> $a_row['id'],
				'[+title+]'=> $a_row['name'],
				'[+selected+]'=> $a_row['id']==$country_id ? ' selected' : ''
			));
		}
		return implode("\n", $a_option);
	}
	public function saveContact(){
		if( !$this->isAtorized){
			return array(
				'status'=> 'error',
				'message'=> 'Вы не авторизованы!'
			);
		} 
		
		$status_save= $this->saveContactForm();
		$status_save_phone_email= $this->savePhoneEmail();
		$status_get= $this->getContact();
		
		
		if( $status_save === true && $status_save_phone_email===true ){
			$a_res= $status_get;
		}
		else {
			$a_res= array(
				'status'=> 'error',
				'message'=> 'При сохранении данных были ошибки'
			);
		}

		return $a_res;
	}
	private function saveContactForm(){
		$user_id= (int)$_SESSION['user']['id'];
		
		$a_fields= array(
			'firstname'=> $this->escape($_POST['firstname']),
			'lastname'=> $this->escape($_POST['lastname']),
			'country_id'=> (int)$_POST['country_id'],
			'city'=> $this->escape($_POST['city']),
			'address'=> $this->escape($_POST['address']),
			'published'=> (isset($_POST['published']) ? 1 : 0)			
		);
		
		$sql= "
		UPDATE users SET
			firstname= '". $a_fields['firstname'] ."',
			lastname= '". $a_fields['lastname'] ."',
			country_id= ". $a_fields['country_id'] .",
			city= '". $a_fields['city'] ."',
			address= '". $a_fields['address'] ."',
			published= ". $a_fields['published'] ."
		WHERE id=". $user_id;
		mysql_query($sql);
		
		if( mysql_affected_rows() < 0 ){//error
			$status= false;
		}
		else{//success
			$status= true;
			$_SESSION['user']= array_merge($_SESSION['user'], $a_fields);
		}
		
		return $status;
	}
	private function savePhoneEmail(){
		$user_id= (int)$_SESSION['user']['id'];
		
		$status= true;
		
		foreach(array('email', 'phone') as $s_name){
			foreach($_POST[$s_name] as $s_key=>$s_value){
				list($s_flag, $i_itemId)= explode('-', $s_key);

				$s_published= (isset($_POST[$s_name.'-publ'][$s_key]) ? 1 : 0);
				
				$isRunQuery= false;
				
				$s_value= $this->escape($s_value);
				$tbl= "user_". $s_name ."s";

				if($s_flag=='id'){//exists
					if($s_value===''){
						$sql= "DELETE FROM ". $tbl;
					}
					else{
						$sql= "
							UPDATE ". $tbl ."
							SET
								".$s_name."= '". $s_value ."',
								published= ". $s_published;
					}
					mysql_query($sql. "
						WHERE
							uid= ". $user_id ." AND
							id= ". $i_itemId
					);
					$isRunQuery= true;
				}
				elseif($s_value!==''){//new
					mysql_query("
						INSERT INTO ". $tbl ." VALUES
						(NULL, ". $user_id .", '". $s_value ."', ". $s_published .")"
					);
					$isRunQuery= true;
				}
				
				
				if( $isRunQuery && mysql_affected_rows() < 0 ){//error
					$status= false;
				}
			}
		}
		
		return $status;
	}
	public function _print($a_out){
		header('Content-Type:application/json;charset='.CHARSET);
		die( json_encode($a_out) );
	}

}
?>