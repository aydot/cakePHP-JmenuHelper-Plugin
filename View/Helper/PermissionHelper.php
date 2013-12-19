<?php
App::uses('AppHelper', 'View/Helper');

class PermissionHelper extends AppHelper {
        
	public $helpers = array('Session');
	
	private $_permissionModel;
	
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		
		$this->_permissionModel = ClassRegistry::init('Permission');
	}
	
	public function check($aro, array $aco, $action = '*') {
		return  $this->_permissionModel->check($aro, $this->action($aco), $action );
	}
	
	public function action($permissionRequest, $path = '/:plugin/:controller/:action') {
		$plugin = empty($permissionRequest['plugin']) ? null : Inflector::camelize($permissionRequest['plugin']) . '/';
		$path = str_replace(
				array(':controller', ':action', ':plugin/'),
				array(Inflector::camelize($permissionRequest['controller']), $permissionRequest['action'], $plugin),
				$path
		);
		$path = str_replace('//', '/', $path);
		return trim($path, '/');
	}
}