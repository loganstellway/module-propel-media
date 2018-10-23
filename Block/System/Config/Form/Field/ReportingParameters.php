<?php

namespace LoganStellway\PropelMedia\Block\System\Config\Form\Field;

/**
 * Dependencies
 */
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Reporting Parameters
 */
class ReportingParameters extends AbstractFieldArray {
    /**
     * @var bool
     */
    protected $_addAfter = TRUE;

    /**
     * Construct
     */
    protected function _construct() {
        $this->setHtmlId('_propel_media_reporting_params');
        parent::_construct();
    }

    /**
     * Prepare to render the columns
     */
    protected function _prepareToRender() {
        $this->addColumn('name', [
            'label' => __('Name'),
        ]);
        $this->addColumn('value', [
            'label' => __('Value'),
        ]);
        $this->_addAfter = FALSE;
        $this->_addButtonLabel = __('Add Token');
    }
}
