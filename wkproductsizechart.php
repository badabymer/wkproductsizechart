<?php
/**
* 2010-2019 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through this link for complete license : https://store.webkul.com/license.html
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to https://store.webkul.com/customisation-guidelines/ for more information.
*
*  @author    Webkul IN <support@webkul.com>
*  @copyright 2010-2019 Webkul IN
*  @license   https://store.webkul.com/license.html
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'classes/WkScRequiredClasses.php';

class WkProductSizeChart extends Module
{
    public function __construct()
    {
        $this->name = 'wkproductsizechart';      /* Name of the Module */
        $this->tab = 'front_office_features';   /* Tab to Display [Categories in Backoffice Module Page] */
        $this->version = '4.3.0';               /* Module version display in module list */
        $this->author = 'Webkul';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        parent::__construct();
        $this->secure_key = Tools::encrypt($this->name);
        $this->displayName = $this->l('PrestaShop Product Size Chart');
        $this->description = $this->l('By this module admin can create and apply size chart on products.');
        $this->bootstrap = true;
        $this->confirmUninstall = $this->l('Are you sure?');
    }

    /**
     * Installation Process,
     *
     * Overriding the Module::install() function
     *
     * @return bool
     */
    public function install()
    {
        $objSizeChartDb = new WkProductSizeChartDb();
        if (!parent::install()
            || !$objSizeChartDb->createTables()
            || !$this->callInstallTab()
            || !$this->registerModuleHook()
            || !Configuration::updateValue('WK_DISPLAY_SIZE_CHART_ON_POPUP', true)) {
            return false;
        }
        return true;
    }

    /**
     * To register required hooks
     *
     * @return void
     */
    public function registerModuleHook()
    {
        return $this->registerHook(array(
            'displayAdminProductsExtra',
            'displayProductButtons',
            'displayProductAdditionalInfo',
            'actionObjectProductAddBefore',
            'actionObjectProductUpdateBefore',
            'actionObjectProductDeleteBefore',
            'actionProductSave',
            'actionObjectLanguageAddAfter',
            'actionAdminControllerSetMedia',
            'actionFrontControllerSetMedia'
        ));
    }

    public function callInstallTab()
    {
        $this->installTab('AdminWkProductSizeChart', 'Product Size Chart', 'AdminCatalog');
        $this->installTab('AdminWkSizeChart', 'Size Chart', 'AdminWkProductSizeChart');
        $this->installTab('AdminWkSizeChartFilter', 'Size Chart Bulk Action', 'AdminWkProductSizeChart');
        return true;
    }

    /**
     * To display size chart for applying on product at back end
     *
     * @param [type] $param
     * @return void
     */
    public function hookDisplayAdminProductsExtra($param)
    {
        $idProduct = (int)$param['id_product'];
        $objSizeChart = new WkSizeChart();
        $sizeCharts = $objSizeChart->getAllSizeChart($this->context->language->id);
        $this->getSizeChartValues($idProduct);
        $this->context->smarty->assign(array(
            'sizeCharts' => $sizeCharts,
            'idProduct' => $idProduct,
            'wkself' => dirname(__FILE__),
            'createSizeChartLink' => $this->context->link->getAdminLink('AdminWkSizeChart')
        ));
        return $this->display(__FILE__, 'apply-size-chart.tpl');
    }

    public function hookActionObjectProductDeleteBefore($params)
    {
        if (isset($params['object']->id) && $params['object']->id) {
            Db::getInstance()->delete(
                'wk_size_chart_product',
                'id_product = '.(int)$params['object']->id
            );
        }
    }

    public function hookActionObjectProductAddBefore()
    {
        $this->validationBeforeProductSave();
    }

    public function hookActionObjectProductUpdateBefore()
    {
        $this->validationBeforeProductSave();
    }

    public function validationBeforeProductSave()
    {
        if (Tools::getIsset('size_chart_list')) {
            $wkSelectedChart = Tools::getValue('size_chart_list');
            if (Tools::getValue('apply_chart')) {
                if ($wkSelectedChart == '0') {
                    $this->context->controller->errors['hooks_size_chart_list'] = array(
                        $this->l('Select a size chart.')
                    );
                }
            }
            if ($this->context->controller->errors) {
                http_response_code(400);
                die(json_encode($this->context->controller->errors));
            }
        }
    }

    /**
     * To save applied size chart on product
     *
     * @param [type] $params
     * @return void
     */
    public function hookActionProductSave($params)
    {
        if ($params['id_product'] && $params['product']
        && isset($this->context->controller->controller_name)
        && $this->context->controller->controller_name == 'AdminProducts') {
            $objProductSizeChart = new WkSizeChartProduct();
            $idProduct = $params['id_product'];
            $idSizeChart = (int)Tools::getValue("size_chart_list");
            $isExist = $objProductSizeChart->getProductSizeChart($idProduct);
            if ($idSizeChart) {
                if ($isExist) {
                    Media::addJsDef(
                        'idSizeChart',
                        $isExist['id_size_chart']
                    );
                    $idProductSizeChart = $isExist["id_size_chart_product"];
                    $objSizeChartProduct = new WkSizeChartProduct($idProductSizeChart);
                    if (Validate::isLoadedObject($objSizeChartProduct)) {
                        $objSizeChartProduct->id_product = $idProduct;
                        $objSizeChartProduct->id_size_chart = $idSizeChart;
                        $objSizeChartProduct->id_size_chart_filter = null;
                        $objSizeChartProduct->save();
                    }
                } else {
                    $objSizeChartProduct = new WkSizeChartProduct();
                    $objSizeChartProduct->id_product = $idProduct;
                    $objSizeChartProduct->id_size_chart = $idSizeChart;
                    $objSizeChartProduct->id_size_chart_filter = null;
                    $objSizeChartProduct->save();
                }
            } else {
                if ($isExist) {
                    $idProductSizeChart = $isExist["id_size_chart_product"];
                    $objSizeChartProduct = new WkSizeChartProduct($idProductSizeChart);
                    if (Validate::isLoadedObject($objSizeChartProduct)) {
                        $objSizeChartProduct->delete();
                    }
                }
            }
        }
    }

    /**
     * To display size chart button on product page
     */
    public function hookDisplayProductButtons()
    {
        $idProduct = (int)Tools::getValue('id_product');
        $idSizeChart = $this->getSizeChartValues($idProduct);
        $objSizeChart = new WkSizeChart($idSizeChart);
        if (isset($objSizeChart->active) && $objSizeChart->active) {
            $selectedChart = $idSizeChart;
        } else {
            $selectedChart = false;
        }
        if (!empty($selectedChart) && $selectedChart) {
            $this->context->smarty->assign(
                'call_ajax',
                Tools::getValue('action') ? Tools::getValue('action') : Tools::getValue('ajax')
            );
            return $this->display(__FILE__, 'display-size-chart-button.tpl');
        }
    }
	  public function hookDisplaySizeChartProduct()
    {
        $idProduct = (int)Tools::getValue('id_product');
        $idSizeChart = $this->getSizeChartValues($idProduct);
        $objSizeChart = new WkSizeChart($idSizeChart);
        if (isset($objSizeChart->active) && $objSizeChart->active) {
            $selectedChart = $idSizeChart;
        } else {
            $selectedChart = false;
        }
        if (!empty($selectedChart) && $selectedChart) {
            $this->context->smarty->assign(
                'call_ajax',
                Tools::getValue('action') ? Tools::getValue('action') : Tools::getValue('ajax')
            );
            return $this->display(__FILE__, 'display-size-chart-button.tpl');
        }
    }

    public function getSizeChartValues($idProduct)
    {
        $objProductSizeChart = new WkSizeChartProduct();
        $isExist = $objProductSizeChart->getProductSizeChart($idProduct);
        if ($isExist) {
            $idProductSizeChart = $isExist["id_size_chart_product"];
            $objSizeChartProduct = new WkSizeChartProduct($idProductSizeChart);
            if (Validate::isLoadedObject($objSizeChartProduct)) {
                $this->getAppliedChartValues($objSizeChartProduct->id_size_chart);
            }
            return $objSizeChartProduct->id_size_chart;
        }
        return false;
    }

    /**
     * To get size chart attributes & measurements values
     *
     * @param [int] $idSizeChart
     * @return void
     */
    public function getAppliedChartValues($idSizeChart)
    {
        $objSizeChart = new WkSizeChart($idSizeChart);
        if (Validate::isLoadedObject($objSizeChart)) {
            $objSizeChartAttribute = new WkSizeChartAttribute();
            $objSizeChartMeasurement = new WkSizeChartMeasurement();
            $sizeChartAttribute = $objSizeChartAttribute->getSizeChartAttribute($objSizeChart->id);
            $attributeNames = $sizeChartMeasurements = $measurementNames = array();
            foreach ($sizeChartAttribute as $sizeChartAttributes) {
                array_push($attributeNames, $objSizeChartAttribute->getAttributeName(
                    (int)$objSizeChart->size_chart_type,
                    (int)$sizeChartAttributes['id_attribute'],
                    $this->context->language->id
                ));
                $sizeChartMeasurement = $objSizeChartAttribute->getSizeChartMeasurement(
                    (int)$sizeChartAttributes["id_size_chart_attribute"]
                );
                if (!(bool)Configuration::get('WK_TEMPLATE_TYPE')) {
                    array_push($sizeChartMeasurements, $sizeChartMeasurement);
                }
            }
            foreach ($sizeChartMeasurement as $measurement) {
                if ((bool)Configuration::get('WK_TEMPLATE_TYPE')) {
                    $measurements = array();
                    foreach ($sizeChartAttribute as $sizeChartAttributes) {
                        array_push($measurements, $objSizeChartMeasurement->getMeasurementById(
                            (int)$sizeChartAttributes["id_size_chart_attribute"],
                            (int)$measurement["id_measurement"]
                        ));
                    }
                    array_push($sizeChartMeasurements, $measurements);
                }
                array_push($measurementNames, $objSizeChartMeasurement->getMeasurementName(
                    (int)$measurement["id_measurement"],
                    $this->context->language->id
                ));
            }
            if ((bool)$objSizeChart->checkChartActive($idSizeChart)) {
                $selectedChart = $idSizeChart;
            } else {
                $selectedChart = false;
            }

            $this->context->smarty->assign(array(
                'selectedSizeChart' => $selectedChart,
                'sizeChart' => $objSizeChart,
                'id_lang' => $this->context->language->id,
                'attributeNames' => $attributeNames,
                'measurements' => $sizeChartMeasurements,
                'measurementNames' => $measurementNames,
                'popUpView' => (bool)Configuration::get('WK_DISPLAY_SIZE_CHART_ON_POPUP'),
                'templateType' => (bool)Configuration::get('WK_TEMPLATE_TYPE')
            ));
        }
    }

    /**
     * To display size chart button on product page
     */
    public function hookDisplayProductAdditionalInfo()
    {
        return $this->hookDisplayProductButtons();
    }

    /**
     * If admin add any language then an entry will add in defined $lang_tables array's lang table same as prestashop do
     *
     * @param array $params
     */
    public function hookActionObjectLanguageAddAfter($params)
    {
        if ($params['object']->id) {
            $newIdLang = $params['object']->id;

            $objSizeChartDb = new WkProductSizeChartDb();
            $objSizeChartDb->updateMeasurementLangData($newIdLang);
            $objSizeChartDb->updateCustomAttrLangData($newIdLang);
            $objSizeChartDb->updateSizeChartLangData($newIdLang);
        }
    }

    /**
     * To set JS & CSS in back end
     */
    public function hookActionAdminControllerSetMedia()
    {
        if ('AdminProducts' === $this->context->controller->php_self) {
            Media::addJsDef(array(
                'WkProductSizeChart' => $this->context->link->getAdminLink('AdminWkSizeChart'),
                'popUpView' => true,
                'errorMsg' => array(
                    'chartRequired' => $this->l('Select a size chart.'),
                )
            ));
            $this->context->controller->addJS($this->_path . 'views/js/wk-size-chart-table.js?v='.$this->version);
            $this->context->controller->addCss($this->_path . 'views/css/wk-size-chart-form.css?v='.$this->version);
        }
    }

    /**
     * To set JS & CSS in front end
     */
    public function hookActionFrontControllerSetMedia()
    {
        if ('product' === $this->context->controller->php_self) {
            Media::addJsDef(array(
                'popUpView' => Configuration::get('WK_DISPLAY_SIZE_CHART_ON_POPUP'),
            ));
            $this->context->controller->addJS($this->_path . 'views/js/wk-size-chart-table.js?v='.$this->version);
            $this->context->controller->addCss($this->_path . '/views/css/wk-size-chart-form.css?v='.$this->version);
        }
    }

    /**
     * To install Tabs at back end
     *
     * @param [type] $className
     * @param [type] $tabName
     * @param [type] $parentTabName
     */
    public function installTab($className, $tabName, $parentTabName)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }
        $tab->id_parent = (int) Tab::getIdFromClassName($parentTabName);
        $tab->module = $this->name;
        return $tab->add();
    }

    /**
     * Load the configuration form
     *
     * By just defining this function, you can see the 'Configure' Button in the Module list
     */
    public function getContent()
    {
        if (Tools::isSubmit('submit'.$this->name)) {
            $this->postProcess();
        }

        return $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->submit_action = 'submit'.$this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display size chart as a pop up'),
                        'name' => 'WK_DISPLAY_SIZE_CHART_ON_POPUP',
                        'is_bool' => true,
                        'hint' => $this->l('If this setting is enabled then size chart will display as a popup else it will display on product page only.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                    'type' => 'radio',
                    'label' => $this->l('Size chart template view'),
                    'name' => 'WK_TEMPLATE_TYPE',
                    'hint' => $this->l('This will display the size chart attribute values row wise or column wise.'),
                    'values' => array(
                        array(
                            'id' => 'type_horizontal',
                            'value' => 0,
                            'label' => $this->l('Display attribute value row wise'),
                        ),
                        array(
                            'id' => 'type_vertical',
                            'value' => 1,
                            'label' => $this->l('Display attribute value column wise'),
                        )),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'WK_DISPLAY_SIZE_CHART_ON_POPUP' => (bool)Configuration::get('WK_DISPLAY_SIZE_CHART_ON_POPUP'),
            'WK_TEMPLATE_TYPE' => (bool)Configuration::get('WK_TEMPLATE_TYPE'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $formValues = $this->getConfigFormValues();

        foreach (array_keys($formValues) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }

        if (empty($this->context->controller->errors)) {
            Tools::redirectAdmin(
                $this->context->link->getAdminLink('AdminModules', false)
                . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name .
                '&token='. Tools::getAdminTokenLite('AdminModules') . '&activeTab=designSetting&conf=6'
            );
        }
    }

    /**
     * To uninstall Tabs at back end
     *
     * @return bool
     */
    public function uninstallTab()
    {
        $wkModuleTabs = Tab::getCollectionFromModule($this->name);
        if (!empty($wkModuleTabs)) {
            foreach ($wkModuleTabs as $wkModuleTab) {
                $wkModuleTab->delete();
            }
        }
        return true;
    }

    /**
     * Uninstallation Process,
     *
     * Overriding Module::uninstall()
     *
     * @return bool
     */
    public function uninstall()
    {
        $objSizeChartDb = new WkProductSizeChartDb();
        if (!parent::uninstall()
            || !$this->uninstallTab()
            || !$objSizeChartDb->deleteTables()
            || !Configuration::deleteByName('WK_DISPLAY_SIZE_CHART_ON_POPUP')
            || !Configuration::deleteByName('WK_TEMPLATE_TYPE')) {
            return false;
        }
        return true;
    }
}
