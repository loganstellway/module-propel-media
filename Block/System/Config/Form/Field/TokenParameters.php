<?php

namespace LoganStellway\PropelMedia\Block\System\Config\Form\Field;

/**
 * Dependencies
 */
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Token Parameters
 */
class TokenParameters extends AbstractFieldArray {
    /**
     * @var bool
     */
    protected $_addAfter = TRUE;

    /**
     * Construct
     */
    protected function _construct() {
        $this->setHtmlId('_propel_media_token_params');
        parent::_construct();
    }

    /**
     * Prepare to render the columns
     */
    protected function _prepareToRender() {
        $this->addColumn('name', [
            'label' => __('Name'),
        ]);
        $this->_addAfter = FALSE;
        $this->_addButtonLabel = __('Add Token');
    }
}
