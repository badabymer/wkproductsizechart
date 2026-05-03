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

class AdminWkSizeChartFilterController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap =true;
        $this->table = 'wk_size_chart_filter';
        $this->className = 'WkSizeChartFilter';
        $this->identifier = 'id_size_chart_filter';
        parent::__construct();

        $this->fields_list = array(
            'id_size_chart_filter' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'search_product_name' => array(
                'title' => $this->l('Product name keyword'),
                'align' => 'center',
                'callback' => 'getDashOnBlank',
            ),
            'id_categories' => array(
                'title' => $this->l('Total applied categories'),
                'align' => 'center',
                'callback' => 'getCategoryAppliedCount'
            ),
            'id_manufacturers' => array(
                'title' => $this->l('Total applied brands'),
                'align' => 'center',
                'callback' => 'getManufacturersAppliedCount'
            ),
            'id_suppliers' => array(
                'title' => $this->l('Total applied suppliers'),
                'align' => 'center',
                'callback' => 'getSuppliersAppliedCount'
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete Selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?'),
            )
        );
    }

    /**
     * Show dot if search pattern is null
     *
     * @param [type] $param
     * @return void
     */
    public function getDashOnBlank($param)
    {
        if (empty($param) && !$param) {
            return '--';
        } else {
            return $param;
        }
    }

    /**
     * Applied categories (show count)
     *
     * @param [type] $params
     *
     * @return void
     */
    public function getCategoryAppliedCount($params)
    {
        $categories = unserialize($params);
        if (is_array($categories)) {
            return count($categories);
        } else {
            return 0;
        }
    }

    /**
     * Applied categories (show count)
     *
     * @param [type] $params
     *
     * @return void
     */
    public function getManufacturersAppliedCount($params)
    {
        $manufacturers = unserialize($params);
        if (is_array($manufacturers)) {
            return count($manufacturers);
        } else {
            return 0;
        }
    }

    /**
     * Applied categories (show count)
     *
     * @param [type] $params
     *
     * @return void
     */
    public function getSuppliersAppliedCount($params)
    {
        $suppliers = unserialize($params);
        if (is_array($suppliers)) {
            return count($suppliers);
        } else {
            return 0;
        }
    }

    /**
     * Show list of size chart filter
     *
     * @return html
     */
    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    /**
     * Make add new filter btn
     *
     * @return void
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->l('Add new filter'),
                'icon' => 'process-icon-new'
            );
        }
    }

    /**
     * Make size chart filter form
     *
     * @return html
     */
    public function renderForm()
    {
        if (!$this->loadObject(true)) {
            return;
        }

        $editIdSizeChartFilter =  Tools::getValue('id_size_chart_filter');
        $idCategories = array();
        $idSuppliers = array();
        $idManufacturers = array();

        if ($editIdSizeChartFilter) {
            if (is_array(unserialize($this->object->id_categories))) {
                $idCategories = array_values(
                    unserialize($this->object->id_categories)
                );
            }

            if (is_array(unserialize($this->object->id_manufacturers))) {
                $idManufacturers = array_values(
                    unserialize($this->object->id_manufacturers)
                );
            }

            if (is_array(unserialize($this->object->id_suppliers))) {
                $idSuppliers = array_values(
                    unserialize($this->object->id_suppliers)
                );
            }

            $this->fields_value['id_manufacturers[]'] = $idManufacturers;
            $this->fields_value['id_suppliers[]'] = $idSuppliers;
        }

        $categoryTree = new HelperTreeCategories('plan-categories');
        $categoryTree->setAttribute('is_category_filter', (bool)'1')
            ->setInputName('wk_id_categories')
            ->setRootCategory(Category::getRootCategory()->id)
            ->setSelectedCategories($idCategories)
            ->setUseCheckBox(true);

        $objManufacture = new Manufacturer();
        $manufactureData = $objManufacture->getLiteManufacturersList();

        $objSupplier = new Supplier();
        $supplierCoreData = $objSupplier->getSuppliers();

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Size chart bulk action'),
                'icon' => 'icon-filter'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Product name pattern'),
                    'name' => 'search_product_name',
                    'col' => '6',
                    'desc' => $this->l('Enter at least three character for search product name pattern.')
                ),
                array(
                    'label' => $this->l('Categories'),
                    'type' => 'categories_select',
                    'name' => 'id_categories',
                    'class' => 'plan-categories',
                    'col' => '6',
                    'category_tree' => $categoryTree->render(),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Brand'),
                    'name' => 'id_manufacturers[]',
                    'class' => 'chosen chosen-select',
                    'selected' => true,
                    'multiple' => true,
                    'col' => '6',
                    'placeholder' => $this->l('Choose brand'),
                    'options' => array(
                        'query' => $manufactureData,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Suppliers'),
                    'name' => 'id_suppliers[]',
                    'class' => 'chosen',
                    'search' => true,
                    'multiple' => true,
                    'col' => '6',
                    'placeholder' => $this->l('Choose Suppliers'),
                    'options' => array(
                        'query' => $supplierCoreData,
                        'id' => 'id_supplier',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'html',
                    'name' => 'wk_search_button',
                    'html_content' => $this->searchbtn()
                ),
                array(
                    'type' => 'html',
                    'name' => 'wk_product_list',
                    'html_content' => $this->displayFilteredProduct()
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Save and Stay'),
                    'name' => 'submitAdd'.$this->table.'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save',
                ),
            ),
        );

        if (!$this->loadObject(true)) {
            return;
        }

        return parent::renderForm();
    }

    /**
     * Make search btn
     *
     * @return void
     */
    public function searchBtn()
    {
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/filter-search-btn.tpl'
        );
    }

    /**
     * Display product ( searched by ajax )
     *
     * @return void
     */
    public function displayFilteredProduct()
    {
        $filteredProduct = array();
        $objSizeChart = new WkSizeChart();
        $sizeCharts = $objSizeChart->getAllSizeChart($this->context->language->id);
        $this->context->smarty->assign(array(
            'sizeCharts' => $sizeCharts,
            'bulkAction' => true,
            'wkself' => dirname(__FILE__),
        ));

        if ($this->display == 'edit') {
            $idSizeChartFilter = (int)Tools::getValue('id_size_chart_filter');
            unserialize($this->object->id_categories);
            $objChartFilter = new WkSizeChartFilter($idSizeChartFilter);

            if (Validate::isLoadedObject($objChartFilter)) {
                $appliedFilters = $objChartFilter->getDataByFilterId(
                    $idSizeChartFilter
                );
                Media::addJsDef(array('editBulkAction' => true));
                if ($appliedFilters) {
                    $appliedProductIds = array();
                    foreach ($appliedFilters as $appliedFilter) {
                        array_push($appliedProductIds, $appliedFilter['id_product']);
                        $idSizeChart = $appliedFilter['id_size_chart'];
                    }

                    $chartFilterData = array(
                        'id_size_chart_filter' => Tools::getValue('id_size_chart_filter'),
                        'search_product_name' => $this->object->search_product_name,
                        'wk_id_categories' => unserialize($this->object->id_categories),
                        'id_manufacturers' => unserialize($this->object->id_manufacturers),
                        'id_suppliers' => unserialize($this->object->id_suppliers),
                        'applied_products' => $appliedProductIds,
                        'applied_chart' => $idSizeChart,
                    );
                    $responseChartFilterErrors = $objChartFilter->validateChartFilterData($chartFilterData);

                    if (!empty($responseChartFilterErrors)) {
                        $this->context->controller->errors = $responseChartFilterErrors;
                    }

                    $filteredProduct = $objChartFilter->getFilteredProducts(
                        $chartFilterData['search_product_name'],
                        $chartFilterData['wk_id_categories'],
                        $chartFilterData['id_manufacturers'],
                        $chartFilterData['id_suppliers'],
                        $this->context->language->id
                    );
                    if (!empty($this->context->controller->errors)) {
                        $filteredProduct = array();
                    }
                    if ((bool)$objSizeChart->checkChartActive($idSizeChart)) {
                        $selectedChart = $idSizeChart;
                    } else {
                        $selectedChart = false;
                    }

                    if (!empty($filteredProduct)) {
                        $this->context->smarty->assign(array(
                            'filteredList' => $filteredProduct,
                            'appliedProductId' => $appliedProductIds,
                            'selectedSizeChart' => $selectedChart,
                        ));
                    }
                }
                return $this->context->smarty->fetch(
                    _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/filter-product-on-edit.tpl'
                );
            }
        } else {
            return   $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/filter-product-on-add.tpl'
            );
        }
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddwk_size_chart_filter')) {
            if (Tools::getValue('id_size_chart_filter')) {
                $objSizeChartFilter = new WkSizeChartFilter(Tools::getValue('id_size_chart_filter'));
                $objSizeChartFilter->actionBeforeFilterDelete(
                    Tools::getValue('id_size_chart_filter')
                );
                $configMsg = 4;
            } else {
                $objSizeChartFilter = new WkSizeChartFilter();
                $configMsg = 3;
            }

            $chartFilterData = array(
                'id_size_chart_filter' => Tools::getValue('id_size_chart_filter'),
                'search_product_name' => Tools::getValue('search_product_name'),
                'wk_id_categories' => Tools::getValue('wk_id_categories'),
                'id_manufacturers' => Tools::getValue('id_manufacturers'),
                'id_suppliers' => Tools::getValue('id_suppliers'),
                'applied_products' => Tools::getValue('searchedProduct'),
                'applied_chart' => Tools::getValue('size_chart_list'),
            );
            $responseChartFilterErrors = $objSizeChartFilter->validateChartFilterData($chartFilterData);

            if (!empty($responseChartFilterErrors)) {
                $this->context->controller->errors = $responseChartFilterErrors;
            }

            if (empty($this->context->controller->errors)) {
                $objSizeChartFilter->search_product_name = Tools::getValue('search_product_name');
                $objSizeChartFilter->id_categories = serialize(Tools::getValue('wk_id_categories'));
                $objSizeChartFilter->id_suppliers = serialize(Tools::getValue('id_suppliers'));
                $objSizeChartFilter->id_manufacturers = serialize(Tools::getValue('id_manufacturers'));
                $objSizeChartFilter->save();

                $idProducts = Tools::getValue('searchedProduct');
                $idSizeChart = (int)Tools::getValue('size_chart_list');
                if (is_array($idProducts)) {
                    foreach ($idProducts as $idProduct) {
                        $objProductSizeChart = new WkSizeChartProduct();
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
                                    $objSizeChartProduct->id_size_chart_filter = $objSizeChartFilter->id;
                                    $objSizeChartProduct->save();
                                }
                            } else {
                                $objSizeChartProduct = new WkSizeChartProduct();
                                $objSizeChartProduct->id_product = $idProduct;
                                $objSizeChartProduct->id_size_chart = $idSizeChart;
                                $objSizeChartProduct->id_size_chart_filter = $objSizeChartFilter->id;

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
                if (Tools::isSubmit('submitAdd' . $this->table . 'AndStay')) {
                    if ($objSizeChartFilter->id) {
                        Tools::redirectAdmin(
                            AdminController::$currentIndex . '&id_size_chart_filter=' . $objSizeChartFilter->id .
                            '&updatewk_size_chart_filter' . '&token=' . $this->context->controller->token .
                            '&conf=' . $configMsg
                        );
                    }
                } elseif (Tools::isSubmit('submitAdd' . $this->table)) {
                    Tools::redirectAdmin(
                        AdminController::$currentIndex . '&token=' . $this->context->controller->token .
                        '&conf=' . $configMsg
                    );
                }
            }
        }
        parent::postProcess();
    }

    /**
     * To set JS & CSS for controller
     *
     * @return void
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        Media::addJsDef(array(
            'WkSizeChartFilter' => $this->context->link->getAdminLink('AdminWkSizeChartFilter'),
            'popUpView' => true,
            'errorMsg' => array(
                'productNotFound' => $this->l('No product is found for this filter.'),
                'patternInvalid' => $this->l('Invalid search product.'),
                'patternLength' => $this->l('Search product must be at least three character.'),
                'oneFilterRequired' => $this->l('At least one filter is required.'),
                'clickSearch' => $this->l('You need to click on search button in order to apply selected filter on the products.'),
                'productRequired' => $this->l('Select product.'),
                'chartRequired' => $this->l('Select a size chart.'),
            )
        ));
        $this->context->controller->addJS(_PS_MODULE_DIR_.$this->module->name.'/views/js/wk-size-chart-table.js');
        $this->context->controller->addCss(_PS_MODULE_DIR_.$this->module->name.'/views/css/wk-size-chart-form.css');
    }

    /**
     * Ajax to display filtered product list.
     *
     * @return void
     */
    public function ajaxProcessDisplayFilteredProducts()
    {
        $searchPattern = Tools::getValue('srcPattern');
        $idCategories = Tools::getValue('idCategories');
        $idManufacturers = Tools::getValue('idManufacturers');
        $idSuppliers = Tools::getValue('idSuppliers');
        $objChartFilter = new WkSizeChartFilter();
        $filteredList = $objChartFilter->getFilteredProducts(
            $searchPattern,
            $idCategories,
            $idManufacturers,
            $idSuppliers,
            $this->context->language->id
        );
        if ($filteredList) {
            $this->context->smarty->assign(array(
                'filteredList' => $filteredList,
            ));
            $this->ajaxdie(json_encode($this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/filter-product-list.tpl'
            )));
        }
    }
}
