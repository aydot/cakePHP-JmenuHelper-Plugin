# Jmenu Helper for CakePHP

This helper generates menu using the jMenu plugin 
	- options and usage can be found here: http://www.myjqueryplugins.com/jquery-plugin/jmenu
	- This helper can generate menu based on permission aco
	- you can add to the menu on the fly from any view 

## Requirements

The master branch has the following requirements:

* CakePHP 2.3.0 or greater.
* PHP 5.3.0 or greater.

## Installation

* Clone/Copy the files in this directory into `app/Plugin/Jmenu`
* Ensure the plugin is loaded in `app/Config/bootstrap.php` by calling `CakePlugin::load('Jmenu');`
* Include the jquery/jmenu css/js files in `app/View/Layouts/default.ctp` in between your header tags`
	* `echo $this->Html->css('/Jmenu/css/jquery/plugins/jquery/jquery.min');`
	* `echo $this->Html->css('/Jmenu/css/jquery/plugins/jmenu/jmenu');`
	* `echo $this->Html->script('/Jmenu/js/jquery/jquery.min');`
	* `echo $this->Html->script('/Jmenu/js/jquery/plugins/jmenu/jMenu.jquery.min');`
	* `echo $this->Html->script('/Jmenu/js/jquery/plugins/jquery-ui/jquery-ui.min');`
* Include the Jmenu helper in your `AppController.php`:
   * `public $helpers = array('Jmenu.Jmenu');`
* For permission based menu, pass in your user model as an option:
   * `public $helpers = array('Jmenu.Jmenu' => array('userModel' => 'Contact'));`

## Versions

* `1.0.1` is compatible with CakePHP 2.3.0 and greater; now pass in user model to the helper option, used to setup the aro. no need to overwrite the JmenuHelper::getUser function
* `1.0` is compatible with CakePHP 2.3.0 and greater


## Usage Example

#### Usage

	Set your menu with the input function
	$options =  array('url' => 'cake based url path', 'permission' => 'cake based permision aco'); // usually null for parent menu
	$this->Jmenu->input('menu name', $options); 
	call echo $this->Jmenu->show();
	
####	Example
	
	Parent menu must be set
	$this->Jmenu->input('Category'); 
	
	for child menus, use the common CakePHP dot notation 
	$this->Jmenu->input('Category.Category1');
	
	To associate Category1 menu to a url
	$this->Jmenu->input('Category.Category1', array('url' => array('controller' =>'companies', 'action' => 'index'))); //menu will be displayed only if the parent menu(Category) is declared first ($this->Jmenu->input('Category');)
	
	To associate Category1 menu to a permission ( overide the _getUser() function to return the correct aro data - see example in Installation section )
	$this->Jmenu->input('Category.Category1', array('url' => array('controller' =>'companies', 'action' => 'index'), 'permission' => array('controller' =>'companies', 'action' => 'view'))); // if access was denied to Category, then Category1 won't be displayed 
	
	$this->Jmenu->input('Category.Category1.Category12');
	
	$this->Jmenu->input('Category.Category1.Category13');
	
	You can set permission at any level
	$this->Jmenu->input('Category.Category1.Category13.Category14', array('url' => array('controller' =>'companies', 'action' => 'edit', 2), 'permission' => array('controller' =>'companies', 'action' => 'view')));
	
	$this->Jmenu->input('Category2');
	
	$this->Jmenu->input('Category3', array('url' => array('controller' =>'contact', 'action' => 'index'), 'permission' => array('controller' =>'contact', 'action' => 'index')));
	
	$this->Jmenu->input('Category4', array('url' => array('controller' =>'contact', 'action' => 'view'), 'permission' => array('controller' =>'companies', 'action' => 'index')));
	
	You have to call the show() function for the menu to be displayed
	echo $this->Jmenu->show();

####	Example of show() config options (refer to jMenu page for possible options):
	
	$this->Jmenu->openOpt(array('click' => true, 'time' => '300'))->show();
	$this->Jmenu->openOpt(array('click' => true, 'time' => '300'))->closeOpt(array('time' => '150'))->show();
	$this->Jmenu->openOpt(array('click' => true, 'time' => '300'))->closeOpt(array('time' => '150'))->show();
	$this->Jmenu->openOpt(array('click' => true, 'time' => '300'))->closeOpt(array('time' => '150'))->animatedText(array('animate' => true,'pixel' => '10px'))->show();
	$this->Jmenu->closeOpt(array('time' => '150'))->show();
