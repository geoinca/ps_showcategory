<?php
 

if (!defined('_PS_VERSION_')) {
    exit;
}
class ps_showcategory extends Module{
	public function __construct()
		{
			$this->name = 'ps_showcategory'; //nombre del módulo el mismo que la carpeta y la clase.
			$this->tab = 'front_office_features'; // pestaña en la que se encuentra en el backoffice.
			$this->version = '1.0.0'; //versión del módulo
			$this->author ='"@geo_inca"'; // autor del módulo
			$this->need_instance = 0; //si no necesita cargar la clase en la página módulos,1 si fuese necesario.
			$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); //las versiones con las que el módulo es compatible.
			$this->bootstrap = true; //si usa bootstrap plantilla responsive.

			parent::__construct(); //llamada al contructor padre.
    
			$this->displayName = $this->l('Show Prestashop Category'); // Nombre del módulo
			$this->description = $this->l('Show Prestashop Category in the Homepage'); //Descripción del módulo
			$this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); //mensaje de alerta al desinstalar el módulo.
		}
 
 
	public function install() {
		// Call install parent method displayHome
		if (!parent::install() || !$this->registerHook('displayHome'))
			return false;

		return true;
	}

	public function uninstall() {
		if (!parent::uninstall() || !$this->unregisterHook('displayHome'))
			return false;
		return true;
	}
	//displaySliderFullWidth
	public function hookdisplayHome($params) {
		$id_customer = (int)$this->context->cookie->id_customer;
		$id_lang = (int)$this->context->language->id;
		$id_group = $id_customer? Customer::getDefaultGroupId($id_customer) :1;
			//$id_group = $id_customer ? Customer::getDefaultGroupId($id_customer) : _PS_DEFAULT_CUSTOMER_GROUP_;	
			   
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
			SELECT c.*, cl.*
			FROM `'._DB_PREFIX_.'category` c
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND `id_lang` = '.(int)$id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = c.`id_category`)
			WHERE level_depth > 1 And level_depth < 3
			AND c.`active` = 1
			AND cg.`id_group` = '.(int)$id_group.'
			ORDER BY `level_depth` ASC, c.`position` ASC');
			$category = new Category(1);
			$nb = intval(Configuration::get('HOME_categories_NBR'));
			  
			$img_cat_dir="/img/c/";
			global $link;
			$this->context->smarty->assign(array(
			   'categories' => $result,
				'category' => $category,
				'lang' => Language::getIsoById($id_lang),
				'Message'=> "MessageMessage",
				'img_cat_dir'	=> $img_cat_dir, 			
			   'link' => $link)); 

		$this->loadCSSandJS();

		return $this->display(__FILE__, 'views/templates/hook/ps_showcategory.tpl');	
	}
 
    public function loadCSSandJS()
    {
    		$this->context->controller->addJquery();
			if (version_compare(_PS_VERSION_, '1.7.0.0', '<') == 1) {
				$this->context->controller->addCss($this->_path.'views/css/ps_showcategory.css', 'all');
				$this->context->controller->addJs($this->_path.'views/js/ps_showcategory.css');
			} else {
				$this->context->controller->registerStylesheet('module-ps_showcategory-css', '/modules/'.$this->name.'/views/css/ps_showcategory.css', ['media' => 'all', 'priority' => 150]);
				$this->context->controller->registerJavascript('module-ps_showcategory-js', '/modules/'.$this->name.'/views/js/ps_showcategory.js',['position' => 'bottom', 'priority' => 150]);	
			}
			#Category::getRootCategories($id_lang, true)

    }
}


