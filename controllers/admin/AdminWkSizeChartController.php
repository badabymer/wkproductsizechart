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

class AdminWkSizeChartController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap =true;
        $this->lang = true;
        $this->table = 'wk_size_chart';
        $this->className = 'WkSizeChart';
        $this->identifier = 'id_size_chart';
        parent::__construct();

        $this->fields_list = array(
            'id_size_chart' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xl',
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'search' => false,
                'callback' => 'displayImageThumbnail',
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'align' => 'center',
                'class' => 'fixed-width-xxl',
            ),
            'size_chart_type' => array(
                'title' => $this->l('Type'),
                'align' => 'center',
                'class' => 'fixed-width-xxl',
                'callback' => 'getChartType',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xl',
                'align' => 'center',
                'orderby' => false,
            )
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete Selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?'),
            )
        );

        Context::getContext()->smarty->assign('languages', Language::getLanguages());
        Context::getContext()->smarty->assign('total_languages', count(Language::getLanguages()));
        Context::getContext()->smarty->assign(
            'current_lang',
            Language::getLanguage((int) $this->context->language->id)
        );

        Media::addJsDef(array(
            'languages' => Language::getLanguages(),
            'total_languages' => count(Language::getLanguages()),
        ));
    }

    public function getChartType($val)
    {
        if ($val == WkSizeChart::WK_PREDEFINED_SIZE_CHART) {
            return $this->l('Predefined');
        } elseif ($val == WkSizeChart::WK_CUSTOM_SIZE_CHART) {
            return $this->l('Custom');
        } elseif ($val == WkSizeChart::WK_IMAGE_SIZE_CHART) {
            return $this->l('Image');
        }
    }

    public function displayImageThumbnail($img)
    {
        $this->context->smarty->assign('chartImage', $img);
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/size-chart-icon.tpl'
        );
    }

    /**
     * To render list for size chart
     *
     * @return void
     */
    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }

    /**
     * To display an add button for size chart on size chart page
     *
     * @return void
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->l('Add new size chart'),
                'icon' => 'process-icon-new'
            );
        }
    }

    /**
     * To change the title of add size chart page
     *
     * @return void
     */
    public function initToolbarTitle()
    {
        parent::initToolbarTitle();

        switch ($this->display) {
            case 'add':
                $this->toolbar_title[] = $this->l('Add New Size Chart', null, null, false);
                $this->addMetaTitle($this->l('Add New Size Chart', null, null, false));
                break;
        }
    }

    /**
     * To render the form for creation of size chart
     *
     * @return void
     */
    public function renderForm()
    {
        $attributeGroups = array_merge(
            array(
                0 => array(
                    "name" => $this->l('Select'),
                    "id_attribute_group" => 0,
                )
            ),
            AttributeGroup::getAttributesGroups($this->context->language->id)
        );

        if (!($objSizeChart = $this->loadObject(true))) {
            return;
        }
        if (Tools::getIsset('updatewk_size_chart') || $this->display == 'edit') {
            $objSizeChartAttribute = new WkSizeChartAttribute();
            $attributeType = (int)$objSizeChart->size_chart_type;

            $sizeChartAttributes = $objSizeChartAttribute->getSizeChartAttribute($objSizeChart->id);
            $attributeNameData = $measurementData = $attributeId = array();
            $totalAttribute = $totalMeasurement = 0;
            if (!empty($sizeChartAttributes) && $sizeChartAttributes) {
                foreach ($sizeChartAttributes as $sizeChartAttribute) {
                    $attributeNames = array();
                    foreach (Language::getLanguages() as $lang) {
                        array_push($attributeNames, $objSizeChartAttribute->getAttributeName(
                            $attributeType,
                            (int)$sizeChartAttribute['id_attribute'],
                            $lang['id_lang']
                        ));
                    }
                    $attributeNameData[$sizeChartAttribute['id_attribute']] = $attributeNames;
                    $totalAttribute++;
                    array_push($attributeId, (int)$sizeChartAttribute["id_attribute"]);
                    $measurements = $objSizeChartAttribute->getSizeChartMeasurement(
                        (int)$sizeChartAttribute["id_size_chart_attribute"]
                    );
                }
                if (isset($measurements) && $measurements) {
                    $objSizeChartMeasurement = new WkSizeChartMeasurement();
                    foreach ($measurements as &$measurement) {
                        $measurementNames = array();
                        foreach (Language::getLanguages() as $lang) {
                            array_push($measurementNames, $objSizeChartMeasurement->getMeasurementName(
                                (int)$measurement['id_measurement'],
                                $lang['id_lang']
                            ));
                        }
                        $measurementData[$measurement['id_measurement']]['name'] = $measurementNames;
                        foreach ($sizeChartAttributes as $chartKey => $sizeChartAttribute) {
                            $measurementData[$measurement['id_measurement']]['value'][$chartKey] =
                            $objSizeChartMeasurement->getMeasurementById(
                                (int)$sizeChartAttribute["id_size_chart_attribute"],
                                (int)$measurement['id_measurement']
                            );
                        }
                        $totalMeasurement++;
                    }
                }
            }

            Media::addJsDef(array(
                'totalMeasurement' => $totalMeasurement,
                'totalAttribute' => $totalAttribute,
                'attributeType' => $attributeType,
                'attributeId' => $attributeId,
                'deleteImageLink' => $this->context->link->getAdminLink('AdminWkSizeChart').'&id_size_chart='.
                Tools::getValue('id_size_chart').'&updatewk_size_chart&delete_image'
            ));
            $this->context->smarty->assign(array(
                'edit_chart' => 1,
                'attributeType' => $attributeType,
                'attributeId' => $attributeId,
                'attributeNameData' => $attributeNameData,
                'measurementData' => $measurementData,
            ));
        }
        if (isset($objSizeChart->image) && $objSizeChart->image) {
            $image = _PS_MODULE_DIR_.'wkproductsizechart/views/img/'.$objSizeChart->image;
            $ext = strtolower(pathinfo($objSizeChart->image, PATHINFO_EXTENSION));
            if ($ext === 'webp') {
                $imageUrl = _MODULE_DIR_.'wkproductsizechart/views/img/'.htmlspecialchars($objSizeChart->image);
            } else {
                $imageUrl = ImageManager::thumbnail(
                    $image,
                    $this->table.'_'.(int)$objSizeChart->id.'.jpg',
                    150,
                    'jpg',
                    true,
                    true
                );
            }
            $imageSize = file_exists($image) ? filesize($image) / 1000 : false;
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Size Chart'),
                'icon' => 'icon-indent-right'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'required' => true,
                    'lang' =>true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'description',
                    'required' => false,
                    'lang' =>true,
                    'autoload_rte' => true,
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image'),
                    'name' => 'image',
                    'display_image' => true,
                    'image' => isset($imageUrl) && $imageUrl ? $imageUrl : false,
                    'size' => isset($imageSize) ? $imageSize : false,
                    'required' => false,
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Size Chart Type'),
                    'name' => 'size_chart_type',
                    'required' => true,
                    'values' => array(
                        array(
                            'id' => 'type_predefined',
                            'value' => WkSizeChart::WK_PREDEFINED_SIZE_CHART,
                            'label' => $this->l('Predefined'),
                        ),
                        array(
                            'id' => 'type_custom',
                            'value' => WkSizeChart::WK_CUSTOM_SIZE_CHART,
                            'label' => $this->l('Custom'),
                        ),
                        array(
                            'id' => 'type_image',
                            'value' => WkSizeChart::WK_IMAGE_SIZE_CHART,
                            'label' => $this->l('Image'),
                        ),
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Attribute'),
                    'name' => 'id_attribute_group',
                    'required' => true,
                    'options' => array(
                        'query' => $attributeGroups,
                        'id' => 'id_attribute_group',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'textbutton',
                    'label' => $this->l('Custom Attribute'),
                    'name' => 'custom_attribute',
                    'required' => true,
                    'desc' => $this->l('Add custom attributes separated by comma ') .'(,)',
                    'button' => array(
                        'label' => $this->l('Continue'),
                        'attributes' => array(
                            'name' => 'create_custom_attribute',
                            'id' => 'create_custom_attribute',
                        ),
                    ),
                ),
                array(
                    'type' => 'html',
                    'name' => 'wk_size_chart_table',
                    'html_content' => $this->context->smarty->fetch(
                        _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/display-attributes.tpl'
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
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

        return parent::renderForm();
    }

    /**
     * To delete the image
     *
     * @return void
     */
    public function postProcess()
    {
        parent::postProcess();
        if (Tools::getIsset('delete_image')) {
            if ($objSizeChart = $this->loadObject(true)) {
                $image = _PS_MODULE_DIR_.'wkproductsizechart/views/img/'.$objSizeChart->image;
                if (file_exists($image)) {
                    if (!unlink($image)) {
                        $this->context->controller->errors[] = $this->l('Error while image deletion.');
                    } else {
                        $objSizeChart->image = null;
                        $objSizeChart->save();
                    }
                }
            }
        }
    }

    /**
     * To save the data of size chart
     *
     * @return void
     */
    public function processSave()
    {
        $defaultLangId = (int)Configuration::get('PS_LANG_DEFAULT');
        $defaultLang = Language::getLanguage((int)$defaultLangId);
        $attributeIds = array();
        if (Tools::isSubmit('submitAdd' . $this->table) || Tools::isSubmit('submitAdd' . $this->table . 'AndStay')) {
            if (empty(trim(Tools::getValue('title_'.$defaultLangId)))) {
                $this->context->controller->errors[] = $this->l('The field title is required at least in ').
                $defaultLang['name'];
            }
            foreach (Language::getLanguages() as $lang) {
                if (!Validate::isGenericName(Tools::getValue('title_'.$lang['id_lang']))) {
                    $this->context->controller->errors[] = $this->l('The title field is invalid.');
                }
                if (!Validate::isCleanHtml(Tools::getValue('description_'.$lang['id_lang']))) {
                    $this->context->controller->errors[] = $this->l('The description field is invalid.');
                }
            }
            $chartImage = $_FILES["image"];
            if (isset($chartImage['name']) && $chartImage['name']) {
                $allowedExts = array('jpg', 'jpeg', 'png', 'webp');
                $uploadExt = strtolower(pathinfo($chartImage['name'], PATHINFO_EXTENSION));
                if (!in_array($uploadExt, $allowedExts)) {
                    $this->context->controller->errors[] = $this->l('Invalid Image. Allowed formats: jpg, jpeg, png, webp.');
                }
                $maxSizeByte = (int)Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024;
                if ($chartImage['size'] > $maxSizeByte) {
                    $this->context->controller->errors[] = $this->l('Maximum size of image is ').
                    Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'). $this->l('MB');
                }
            }
            $chartType = (int)Tools::getValue('size_chart_type');
            $allowedTypes = array(
                WkSizeChart::WK_PREDEFINED_SIZE_CHART,
                WkSizeChart::WK_CUSTOM_SIZE_CHART,
                WkSizeChart::WK_IMAGE_SIZE_CHART,
            );
            if (!in_array($chartType, $allowedTypes)) {
                $this->context->controller->errors[] = $this->l('The size chart type field is invalid.');
            }
            if ($chartType === WkSizeChart::WK_IMAGE_SIZE_CHART) {
                $attributeIds = array();
            } elseif ($chartType === WkSizeChart::WK_CUSTOM_SIZE_CHART) {
                //If custom size chart type selected
                if (empty(trim(Tools::getValue('custom_attribute')))) {
                    $this->context->controller->errors[] = $this->l('The custom attribute field is required.');
                }
                if (!Validate::isGenericName(Tools::getValue('custom_attribute'))) {
                    $this->context->controller->errors[] = $this->l('The custom attribute field is invalid.');
                }

                $attributeIds = explode(',', Tools::getValue('wk_custom_attribute_id'));
                if ($attributeIds) {
                    foreach ($attributeIds as $attrNameKey => $name) {
                        if (isset($name)) {
                            $attrNameIndex = $attrNameKey+1;
                            if (empty(trim(Tools::getValue('pre_attributes_value'.$attrNameIndex.'_'.
                            $defaultLangId)))) {
                                $this->context->controller->errors[] =
                                $this->l('The custom attribute is required at least in '). $defaultLang['name'];
                            }
                            foreach (Language::getLanguages() as $lang) {
                                if (!Validate::isGenericName(Tools::getValue('pre_attributes_value'.
                                $attrNameIndex.'_'.$lang['id_lang']))) {
                                    $this->context->controller->errors[] = $this->l('The custom attribute is invalid.');
                                }
                            }
                        }
                    }
                }
            } else {
                //If predifined size chart type selected
                if (!Validate::isUnsignedId(Tools::getValue('id_attribute_group'))) {
                    $this->context->controller->errors[] = $this->l('The select attribute field is invalid.');
                }
                if (!Tools::getValue('attribute_list') && empty(Tools::getValue('attribute_list'))) {
                    $this->context->controller->errors[] = $this->l('Please choose atleast one attribute value.');
                } else {
                    foreach (explode(',', Tools::getValue('wk_predefined_attribute_id')) as $selected) {
                        array_push($attributeIds, $selected);
                    }
                }
            }
            if (isset($attributeIds) && !empty($attributeIds) && is_array($attributeIds)) {
                if ((int)Tools::getValue('total_measurement')) {
                    for ($count = 1; $count <= (int)Tools::getValue('total_measurement'); $count = $count + 1) {
                        if (Tools::getIsset('measurement'.$count.'_'.$lang['id_lang'])
                            && empty(trim(Tools::getValue('measurement'.$count.'_'.$defaultLangId)))) {
                            $this->context->controller->errors[] = $this->l('The measurement is required at least in ').
                             $defaultLang['name'];
                        } else {
                            foreach (Language::getLanguages() as $lang) {
                                if (!Validate::isGenericName(Tools::getValue('measurement'.$count.'_'.
                                $lang['id_lang']))) {
                                    $this->context->controller->errors[] = $this->l('The measurement name is invalid.');
                                }
                            }
                        }

                        foreach ($attributeIds as $attributeId) {
                            if (!Validate::isGenericName(Tools::getValue('measurement_value'.$count.'_'.
                            $attributeId))) {
                                $this->context->controller->errors[] = $this->l('The measurement value is invalid.');
                            }
                        }
                    }
                }
            }
            if (empty($this->context->controller->errors)) {
                $idSizeChart = Tools::getValue('id_size_chart');
                if (isset($idSizeChart) && $idSizeChart) {
                    $objSizeChart = new WkSizeChart($idSizeChart);
                    $configMsg = 4;
                } else {
                    $objSizeChart = new WkSizeChart();
                    $configMsg = 3;
                }
                if ($objSizeChart = $this->loadObject(true)) {
                    foreach (Language::getLanguages() as $lang) {
                        if (!empty(trim(Tools::getValue('title_'.$lang['id_lang'])))) {
                            $objSizeChart->title[$lang['id_lang']] = trim(Tools::getValue('title_'.$lang['id_lang']));
                        } else {
                            $objSizeChart->title[$lang['id_lang']] = trim(Tools::getValue('title_'.$defaultLangId));
                        }
                        if (!empty(trim(Tools::getValue('description_'.$lang['id_lang'])))) {
                            $objSizeChart->description[$lang['id_lang']] = trim(Tools::getValue(
                                'description_'.$lang['id_lang']
                            ));
                        }
                    }
                    $chartImage = $_FILES["image"];
                    if (isset($chartImage['name']) && $chartImage['name']) {
                        $allowedExts = array('jpg', 'jpeg', 'png', 'webp');
                        $uploadExt = strtolower(pathinfo($chartImage['name'], PATHINFO_EXTENSION));
                        if (in_array($uploadExt, $allowedExts)) {
                            if (isset($objSizeChart->image) && $objSizeChart->image) {
                                $oldImagePath = _PS_MODULE_DIR_.$this->module->name.'/views/img/'.$objSizeChart->image;
                                if (file_exists($oldImagePath)) {
                                    @unlink($oldImagePath);
                                }
                                $oldThumb = _PS_TMP_IMG_DIR_.$this->table.'_'.(int)$objSizeChart->id.'.jpg';
                                if (file_exists($oldThumb)) {
                                    @unlink($oldThumb);
                                }
                            }
                            $newImageName = Tools::passwdGen(6).'.'.$uploadExt;
                            $targetPath = _PS_MODULE_DIR_.$this->module->name.'/views/img/'.$newImageName;
                            copy($chartImage['tmp_name'], $targetPath);
                            $objSizeChart->image = $newImageName;
                        }
                    }
                    if (in_array((int)Tools::getValue('size_chart_type'), $allowedTypes)) {
                        $objSizeChart->size_chart_type = (int)Tools::getValue('size_chart_type');
                    }
                    if ((int)Tools::getValue('id_attribute_group')) {
                        $objSizeChart->id_attribute_group = (int)Tools::getValue('id_attribute_group');
                    }
                    $objSizeChart->active = Tools::getValue('active');
                    $objSizeChart->save();

                    if ((int)$objSizeChart->size_chart_type !== WkSizeChart::WK_IMAGE_SIZE_CHART) {
                        $objSizeChartAttribute = new WkSizeChartAttribute();
                        $isExist = $objSizeChartAttribute->getSizeChartAttribute((int)Tools::getValue('id_size_chart'));
                        if (empty($isExist) && !$isExist) {
                            //Add size chart attributes
                            $this->saveAttributes($objSizeChart->id, $attributeIds);
                        } else {
                            //Edit size chart attributes
                            foreach ($isExist as $existAttribute) {
                                $measurements = $objSizeChartAttribute->getSizeChartMeasurement(
                                    $existAttribute['id_size_chart_attribute']
                                );
                                foreach ($measurements as $measurement) {
                                    $objSizeChartMeasurement = new WkSizeChartMeasurement(
                                        $measurement['id_size_chart_measurement']
                                    );
                                    if (Validate::isLoadedObject($objSizeChartMeasurement)) {
                                        $objSizeChartMeasurement->delete();
                                    }
                                }
                                $objChartAttribute = new WkSizeChartAttribute(
                                    $existAttribute['id_size_chart_attribute']
                                );
                                if (Validate::isLoadedObject($objChartAttribute)) {
                                    $objChartAttribute->delete();
                                }
                            }
                            $this->saveAttributes($objSizeChart->id, $attributeIds);
                        }
                    }
                }
                if (Tools::isSubmit('submitAdd' . $this->table . 'AndStay')) {
                    if ($objSizeChart->id) {
                        Tools::redirectAdmin(
                            AdminController::$currentIndex . '&id_size_chart=' . $objSizeChart->id .
                            '&updatewk_size_chart' . '&token=' . $this->context->controller->token .
                            '&conf=' . $configMsg
                        );
                    }
                } elseif (Tools::isSubmit('submitAdd' . $this->table)) {
                    Tools::redirectAdmin(
                        AdminController::$currentIndex . '&token=' . $this->context->controller->token .
                        '&conf=' . $configMsg
                    );
                }
            } else {
                parent::processSave();
            }
        }
    }

    public function saveAttributes($idSizechart, $attributeIds)
    {
        $defaultLangId = (int)Configuration::get('PS_LANG_DEFAULT');
        $sizeChartAttributes = array();
        if ((bool)Tools::getValue('size_chart_type')) {
            //If custom size chart type selected
            foreach ($attributeIds as $attrKey => $wkAttributeId) {
                $count = $attrKey + 1;
                $attributeName = array();
                foreach (Language::getLanguages() as $lang) {
                    if (!empty(trim(Tools::getValue('pre_attributes_value'.$count.'_'.$lang['id_lang'])))) {
                        array_push(
                            $attributeName,
                            trim(Tools::getValue('pre_attributes_value'.$count.'_'.$lang['id_lang']))
                        );
                    } else {
                        array_push(
                            $attributeName,
                            trim(Tools::getValue('pre_attributes_value'.$count.'_'.$defaultLangId))
                        );
                    }
                }
                $objSizeChartAttribute = new WkSizeChartAttribute();
                if ($wkAttributeId) {
                    $maxIdCustom = $wkAttributeId;
                    foreach (Language::getLanguages() as $langKey => $lang) {
                        $objSizeChartAttribute->updateCustomAttribute(
                            $maxIdCustom,
                            $lang['id_lang'],
                            $attributeName[$langKey]
                        );
                    }
                } else {
                    $maxIdCustom = (int)$objSizeChartAttribute->getMaxIdCustom();

                    if (!$maxIdCustom) {
                        $maxIdCustom = 0;
                    }
                    $maxIdCustom = $maxIdCustom + 1;
                    foreach (Language::getLanguages() as $langKey => $lang) {
                        $objSizeChartAttribute->insertCustomAttribute(
                            $maxIdCustom,
                            $lang['id_lang'],
                            $attributeName[$langKey]
                        );
                    }
                }
                $objSizeChartAttribute->id_size_chart = $idSizechart;
                $objSizeChartAttribute->is_custom = (int)Tools::getValue('size_chart_type');
                $objSizeChartAttribute->id_attribute = (int)$maxIdCustom;
                $objSizeChartAttribute->save();
                array_push($sizeChartAttributes, $objSizeChartAttribute);
            }
        } else {
            //If predifined size chart type selected
            if ($attributeIds) {
                foreach ($attributeIds as $idAttribute) {
                    $objSizeChartAttribute = new WkSizeChartAttribute();
                    $objSizeChartAttribute->id_size_chart = $idSizechart;
                    $objSizeChartAttribute->is_custom = (int)Tools::getValue('size_chart_type');
                    $objSizeChartAttribute->id_attribute = (int)$idAttribute;
                    $objSizeChartAttribute->save();
                    array_push($sizeChartAttributes, $objSizeChartAttribute);
                }
            }
        }
        if (isset($sizeChartAttributes) && !empty($sizeChartAttributes)) {
            if ((int)Tools::getValue('total_measurement')) {
                for ($countMeasure = 1; $countMeasure <= (int)Tools::getValue('total_measurement');
                 $countMeasure = $countMeasure + 1) {
                    $measurementName = array();
                    foreach (Language::getLanguages() as $lang) {
                        if (Tools::getIsset('measurement'.$countMeasure.'_'.$lang['id_lang'])
                            && !empty(trim(Tools::getValue('measurement'.$countMeasure.'_'.$defaultLangId)))) {
                            if (!empty(trim(Tools::getValue('measurement'.$countMeasure.'_'.$lang['id_lang'])))) {
                                array_push(
                                    $measurementName,
                                    trim(Tools::getValue('measurement'.$countMeasure.'_'.$lang['id_lang']))
                                );
                            } else {
                                array_push(
                                    $measurementName,
                                    trim(Tools::getValue('measurement'.$countMeasure.'_'.$defaultLangId))
                                );
                            }
                        }
                    }
                    $objSizeChartAttribute = new WkSizeChartAttribute();
                    $isEmpty = true;
                    if (!empty($measurementName)) {
                        $isMeasurementExist = false;
                        if (!Tools::isEmpty($measurementName)) {
                            $isEmpty = false;
                            foreach (Language::getLanguages() as $langKey => $lang) {
                                $isMeasurementExist = (int)$objSizeChartAttribute->getMeasurementId(
                                    $defaultLangId,
                                    $measurementName[$langKey]
                                );
                            }
                        }
                        if (isset($isMeasurementExist) && $isMeasurementExist) {
                            $maxIdMeasurement = $isMeasurementExist;
                            foreach (Language::getLanguages() as $langKey => $lang) {
                                $objSizeChartAttribute->updateMeasurement(
                                    $maxIdMeasurement,
                                    $lang['id_lang'],
                                    $measurementName[$langKey]
                                );
                            }
                        } else {
                            if (!$isEmpty) {
                                $maxIdMeasurement = (int)$objSizeChartAttribute->getMaxIdMeasurement();
                                if (!$maxIdMeasurement) {
                                    $maxIdMeasurement = 0;
                                }
                                $maxIdMeasurement = $maxIdMeasurement + 1;
                                foreach (Language::getLanguages() as $langKey => $lang) {
                                    $objSizeChartAttribute->insertMeasurement(
                                        $maxIdMeasurement,
                                        $lang['id_lang'],
                                        $measurementName[$langKey]
                                    );
                                }
                            }
                        }
                        if (!$isEmpty) {
                            foreach ($sizeChartAttributes as $attribute) {
                                $objSizeChartMeasurement = new WkSizeChartMeasurement();
                                $objSizeChartMeasurement->id_size_chart_attribute = $attribute->id;
                                $objSizeChartMeasurement->id_measurement = $maxIdMeasurement;

                                if (Tools::getIsset('measurement_value'.$countMeasure.'_'.$attribute->id_attribute)) {
                                    $objSizeChartMeasurement->value = trim(Tools::getValue(
                                        'measurement_value'.$countMeasure.'_'.$attribute->id_attribute
                                    ));
                                }
                                $objSizeChartMeasurement->save();
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * To set JS & CSS for controller
     *
     * @return void
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $defaultLangId = (int)Configuration::get('PS_LANG_DEFAULT');
        $defaultLang = Language::getLanguage((int)$defaultLangId);
        $objSizeChartAttribute = new WkSizeChartAttribute();
        $maxIdCustom = (int)$objSizeChartAttribute->getMaxIdCustom();
        if (!$maxIdCustom) {
            $maxIdCustom = 0;
        }
        $maxIdCustom = $maxIdCustom + 1;
        if (Tools::getIsset('updatewk_size_chart') || $this->display == 'edit') {
            $editPage = true;
        } else {
            $editPage =false;
        }
        $maxSizeByte = (int)Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE');

        Media::addJsDef(array(
            'WkSizeChartLink' => $this->context->link->getAdminLink('AdminWkSizeChart'),
            'idLang' => $this->context->language->id,
            'editPage'=> $editPage,
            'predefined_type' => WkSizeChart::WK_PREDEFINED_SIZE_CHART,
            'custom_type' => WkSizeChart::WK_CUSTOM_SIZE_CHART,
            'image_type' => WkSizeChart::WK_IMAGE_SIZE_CHART,
            'maxIdCustom' => $maxIdCustom,
            'defaultLangId' => $defaultLangId,
            'maxSizeByte' => $maxSizeByte*1024*1024,
            'errorMsg' => array(
                'titleRequired' => $this->l('Title field is required at least in ') . $defaultLang['name'],
                'titleInvalid' => $this->l('Title field is invalid.'),
                'imageInvalid' => $this->l('Image is invalid'),
                'imageSize' => $this->l('Maximum size of image is '). $maxSizeByte. $this->l('MB'),
                'selectRequired' => $this->l('Select attribute field is required.'),
                'chooseRequired' => $this->l('Choose attribute value field is required.'),
                'customRequired' => $this->l('Custom attribute field is required.'),
                'customInvalid' => $this->l('Custom attribute field is invalid.'),
                'commaRequired' => $this->l('Comma is required to add custom attribute.'),
                'measurementInvalid' => $this->l('Measurement is invalid.'),
                'measurementRequired' => $this->l('Measurement is required at least in ') . $defaultLang['name'],
                'attributeInvalid' => $this->l('Attribute is invalid.'),
            )
        ));
        $this->context->controller->addJs(_PS_MODULE_DIR_.$this->module->name.'/views/js/wk-size-chart-form.js');
        $this->context->controller->addCss(_PS_MODULE_DIR_.$this->module->name.'/views/css/wk-size-chart-form.css');
    }

    /**
     * Ajax to display attribute checkbox list.
     *
     * @return void
     */
    public function ajaxProcessDisplayAttributesList()
    {
        $attributes = AttributeGroup::getAttributes(
            $this->context->language->id,
            Tools::getValue('idAttributeGroup')
        );
        $attributeData = $attributes;
        foreach ($attributes as $attrkey => $attribute) {
            $attributeNames = array();
            foreach (Language::getLanguages() as $lang) {
                $objSizeChartAttribute = new WkSizeChartAttribute();
                $attributeNames[$lang['id_lang']] = $objSizeChartAttribute->getAttributeName(
                    false,
                    $attribute['id_attribute'],
                    $lang['id_lang']
                );
            }
            $attributeData[$attrkey]['attributeName'] = json_encode($attributeNames);
        }
        $this->context->smarty->assign(array(
            'attributes' => $attributeData,
            'selectedAttributes' => Tools::getValue('attributeId'),
        ));
        $this->ajaxdie(json_encode($this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/attribute-checkbox.tpl'
        )));
    }

    /**
     * Ajax to display selected attributes
     *
     * @return void
     */
    public function ajaxProcessDisplayAttributes()
    {
        if (trim(Tools::getValue('isCustom')) == 'true') {
            if (is_array(Tools::getValue('nameAttribute'))) {
                $attributes = Tools::getValue('nameAttribute');
            } else {
                $attributes = explode(',', trim(Tools::getValue('attributeIds')));
            }
            $isCustom = WkSizeChart::WK_CUSTOM_SIZE_CHART;
        } else {
            $attributes = Tools::getValue('nameAttribute');
            $isCustom = WkSizeChart::WK_PREDEFINED_SIZE_CHART;
        }
        $this->context->smarty->assign(array(
            'attributeType' => $isCustom,
            'attributes' => $attributes,
            'attributeNames' => explode(',', Tools::getValue('nameAttribute'))
        ));

        $this->ajaxdie(json_encode($this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/add-attribute-row.tpl'
        )));
    }

    /**
     * ajax to add blank rows
     *
     * @return void
     */
    public function ajaxProcessAddBlankRows()
    {
        if (trim(Tools::getValue('isCustom')) == 'true') {
            $attributes = explode(',', trim(Tools::getValue('nameAttribute')));
        } else {
            $attributes = Tools::getValue('nameAttribute');
        }
        $this->context->smarty->assign(array(
            'attributes' => $attributes,
            // 'attributeType' => $isCustom,
            'countMeasure' => Tools::getValue('count'),
            'isBlankRow' => true,
        ));
        $this->ajaxdie(json_encode($this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/add-attribute-row.tpl'
        )));
    }

    /**
     * Ajax to display applied size chart on product admin page
     *
     * @return void
     */
    public function ajaxProcessDisplayProductSizeChart()
    {
        $objProSizeChart = new WkProductSizeChart();
        $objProSizeChart->getAppliedChartValues((int)Tools::getValue('idSizeChart'));
        $this->ajaxdie(json_encode($this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/display-size-chart.tpl'
        )));
    }
}
