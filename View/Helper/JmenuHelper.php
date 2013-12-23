<?php
App::uses('AppHelper', 'View/Helper');

class JmenuHelper extends AppHelper {
        
	public $helpers = array('Html', 'Session', 'Jmenu.Permission');
	
	private $_menuValues = array();
	private $_menuString = null;
	private $_menuOption = array();
	
	private $_selector           = 'jMenu';
	private $_effectSpeedOpen    = '200';
	private $_effectSpeedClose   = '200';
	private $_effecttypeOpen     = 'slide';
	private $_effecttypeClose    = 'slide';
	private $_effectOpen         = 'linear';
	private $_effectClose        = 'linear';
	private $_timeBeforeOpening  = '100';
	private $_timeBeforeClosing  = '100';
	private $_animatedText       = 'false';
	private $_openClick          = 'false';
	private $_paddingLeft        = '1px';
	private $_ulWidth            = 'auto';
	
	public function input($menuName, $options = array()) {
			
		if ($this->_isValidMenuName($menuName) === true && $this->_permissionCheck($options) === true && $this->_parentMenuCheck($menuName, $options) === true) {
				$this->_menuValues[] = $menuName;
				$this->_setMenuOption($menuName, $options);
		}
	}
	
	public function get($selector){
		$this->_selector = $selector;
		return $this;
	}
	
	public function animatedText(array $values){
		if(isset($values['animate'])){
				$this->_animatedText   = $values['animate'];
		}
		if(isset($values['pixel'])){
				$this->_paddingLeft  = $values['pixel'];
		}
		return $this;
	}
	
	public function openOpt(array $values){
		if(isset($values['click'])){
				$this->_openClick   = $values['click'];
		}
		if(isset($values['time'])){
				$this->_timeBeforeOpening   = $values['time'];
		}
		if(isset($values['speed'])){
				$this->_effectSpeedOpen  = $values['speed'];
		}
		if(isset($values['type'])){
				$this->_effecttypeOpen  = $values['type'];
		}
		if(isset($values['effect'])){
				$this->_effectOpen  = $values['effect'];
		}
		return $this;
	}
	
	public function closeOpt(array $values){
		if(isset($values['time'])){
				$this->_timeBeforeClosing   = $values['time'];
		}
		if(isset($values['speed'])){
				$this->_effectSpeedClose  = $values['speed'];
		}
		if(isset($values['type'])){
				$this->_effecttypeClose  = $values['type'];
		}
		if(isset($values['effect'])){
				$this->_effectClose  = $values['effect'];
		}
		return $this;
	}
	
	public function show() {
		$out = null;
		$flippedMenuValues = array_flip($this->_menuValues);
		$menusInOneArray = Hash::expand($flippedMenuValues);
		$this->_constructMenuString($menusInOneArray);
		
		if ( ! empty($this->_menuString)) {
				$out .= $this->_getScript();
				$out .= "<ul id='{$this->_selector}'> {$this->_menuString} </ul>";
		}
		return $out;
	}
	
	private function _isValidMenuName($menuName) {
		return ( ! empty($menuName) && is_string($menuName));
	}
	
	private function _permissionCheck($options) { 
		if( ! empty($options['permission']) && is_array($options['permission'])) {
				return $this->Permission->check( $this->_getUser(), $options['permission']);
		}
		return true;
	}
	
	private function _getUser() {
		if (empty($this->settings['userModel'])){
			throw new CakeException("User Model option wasn't configured in the helper declaration.");
		}
		$user = $this->Session->read('Auth.User');
		$user = array($this->settings['userModel'] => $user);
		return $user;
	}
	
	private function _parentMenuCheck($menuName, $options) { 
		$menuParts = $this->_explodeAndIndexFilteredValueNumerically($menuName);
		
		$actualMenu = end($menuParts);
		
		reset($menuParts);

		if (count($menuParts) > 1) {
			foreach ($menuParts as $part) {
				$isAcutalMenu = ($actualMenu === $part);
				
				if ( ! $isAcutalMenu){
					$parentMenuExists = (array_key_exists($part, $this->_menuOption));
					if( ! $parentMenuExists){
							return false;
					}
				}
			}
		} 
		return true;
	}
	
	private function _setMenuOption($menuName, array $options) {
		$menuParts = $this->_explodeAndIndexFilteredValueNumerically($menuName);
		if (count($menuParts) > 1) {
			$actualMenu = end($menuParts);
		} else {
			$actualMenu = current($menuParts);
		}
		
		$this->_menuOption[$actualMenu] = $options;
	}
	
	private function _explodeAndIndexFilteredValueNumerically($menuName) {
		return array_values(Hash::filter(explode('.', $menuName)));
	}
	
	private function _constructMenuString($menus) {
		if ( ! empty($menus)) {
			foreach ($menus as $menuVal => $menu) {
				if (is_array( $menu)) {
					$this->_menuString .= "<li> {$this->_setMenuValueWithOptions($menuVal)} <ul>";
					$this->_constructMenuString($menu);
				} else {
					$this->_menuString .= "<li> {$this->_setMenuValueWithOptions($menuVal)} </li>";
				}
			}
			$this->_menuString .= "</ul></li>";
		}
	}
	
	private function _setMenuValueWithOptions($menuVal) {
		if ( ! empty($this->_menuOption[$menuVal])) {
				if ( ! empty($this->_menuOption[$menuVal]['url'])) {
						return $this->Html->link($menuVal, $this->_menuOption[$menuVal]['url']);
				}
		}
		return "<a> {$menuVal} </a>";
	}
	
	private function _getScript() {
		return "<script type=\"text/javascript\">
					jQuery(function()
					{
							jQuery('#{$this->_selector}').jMenu({
									openClick : {$this->_openClick},
									ulWidth   : '{$this->_ulWidth}',
									effects : {
											effectSpeedOpen   : '{$this->_effectSpeedOpen}',
											effectSpeedClose  : '{$this->_effectSpeedClose}',
											effectTypeOpen    : '{$this->_effecttypeOpen}',
											effectTypeClose   : '{$this->_effecttypeOpen}',
											effectOpen        : '{$this->_effectOpen}',
											effectClose       : '{$this->_effectClose}',
									},
									TimeBeforeOpening  : '{$this->_timeBeforeOpening}',
									TimeBeforeClosing  : '{$this->_timeBeforeClosing}',
									animatedText       : {$this->_animatedText},
									paddingLeft        : '{$this->_paddingLeft}',
									
							});
					});
			</script>";
	}
}