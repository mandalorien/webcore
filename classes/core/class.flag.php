<?php
/**
 * This file is part of Webcore
 *
 * @license none
 *
 * Copyright (c) 2015-Present, Mandalorien
 * All rights reserved.
 *
 * create 2018 by  mandalorien
 */
class Flags{
	
	private $_User;
	private $_SQLPointer;
	private $_Template;
	private $_Parse = array();
	private $_Flags = array();
	
	public function __construct($SQLPointer = null,$User = null){
		
		$this->_SQLPointer = $SQLPointer;
		if(!is_null($this->_SQLPointer)){
			$this->_User = $User;
			$this->_Template = new Template();
			
			$Request  = "SELECT * FROM ".Entity_Flags::TABLE;
			$this->_Flags = $this->_SQLPointer->query(Pdo_request::FETCHALL,$Request);
		}
	}
	
	public function get_Flags(){
		return $this->_Flags;
	}
	
	/****** Parameters
	 * $valeur as la valeur selectionner dans la table produit par exemple
	 * $types as le type (mentions , etat etc ...) .
	 * $amount as le nombre de valeur dans la table Flag concernant le type 
	 */
	public function load_flag($valeur,$type)
	{
		$array = array();
		$Data = array();
		
		$Request = "SELECT COUNT(*) as total FROM ".Entity_Flags::TABLE." WHERE ". Entity_Flags::TYPE ." = ?";
		$Data[] = $type;
		$flags = $this->_SQLPointer->query(Pdo_request::FETCH_OBJECT,$Request,$Data);
		if($flags)
		{
			$amount = $flags->total;
			
			for($i = $amount-1; $i >= 0;$i--){
				if(pow(2,$i) <= $valeur && pow(2,$i) >= 0){
					if($valeur >= 0){
						array_push($array,intval(log(pow(2,$i))/log(2)));
					}
					$valeur = $valeur % pow(2,$i);
				}
			}
			
			// http://php.net/manual/fr/array.sorting.php
			sort($array); // c'est bien de trier un tableau par ordre croissant 
			return $array;
		}
		else{
			return false;
		}
	}
	
	public function form_flag($valeur,$type){
		
		$content = null;
		$Data = array();
		$i = 0;
		
		foreach($this->_Flags as $flag)
		{
			if($type == $flag->Type)
			{
				$this->_Parse['name'] = utf8_encode($flag->Name);
				$this->_Parse['id'] = $flag->ID;
				$this->_Parse['checkbox_name'] = $type;
				$this->_Parse['checkbox_val'] = intval($i);
				if($this->load_flag($valeur,$type)){
					if(in_array($i,$this->load_flag($valeur,$type))){
						if(pow(2,$i) == $flag->Value){
							
							$this->_Parse['is_checked'] = 'checked';
						}else{
							$this->_Parse['is_checked'] = null;
						}
					}else{
						$this->_Parse['is_checked'] = null;
					}
				}else{
					$this->_Parse['is_checked'] = null;
				}

				$content .= $this->_Template->displaytemplate('checkbox',$this->_Parse).PHP_EOL;
				$content .= '</br>';
				$i++;
			}
		}
		return $content;
	}
	
	public function loadNameFlag($Flag,$typeFlag){
		
		$compteur = 0;
		$array = array();

		$text = null;
		foreach($this->_Flags as $flag)
		{
			if($typeFlag == $flag->Type)
			{
				// $text .= $flag->name;
				$compteur ++;
			}
		}
		
		for($i = $compteur; $i >= 0;$i--){
			if(pow(2,$i) <= $Flag && pow(2,$i) >= 0){
				if($Flag >= 0){
					// array_push($array,intval(log(pow(2,$i))/log(2)));
					array_push($array,intval(pow(2,$i)));
				}
				$Flag = $Flag % pow(2,$i);
			}
		}
		
		foreach($this->_Flags as $flag)
		{
			if($typeFlag == $flag->Type)
			{
				if(in_array($flag->Value,$array)){
					$text .= utf8_encode($flag->Name) .' ,';
				}
			}
		}
		sort($array);
		
		$text = substr($text,0,-1);
		
		return $text;
	}
	
	public function loadNameFlagArray($Flag,$typeFlag){
		
		$compteur = 0;
		$array = array();
		$FlagsName = array();

		$text = null;
		foreach($this->_Flags as $flag)
		{
			if($typeFlag == $flag->Type)
			{
				// $text .= $flag->name;
				$compteur ++;
			}
		}
		
		for($i = $compteur; $i >= 0;$i--){
			if(pow(2,$i) <= $Flag && pow(2,$i) >= 0){
				if($Flag >= 0){
					// array_push($array,intval(log(pow(2,$i))/log(2)));
					array_push($array,intval(pow(2,$i)));
				}
				$Flag = $Flag % pow(2,$i);
			}
		}
		
		foreach($this->_Flags as $flag)
		{
			if($typeFlag == $flag->Type)
			{
				if(in_array($flag->Value,$array)){
					array_push($FlagsName,utf8_encode($flag->Name));
				}
			}
		}
		sort($array);
		sort($FlagsName);
		
		
		return $FlagsName;
	}
}
?>