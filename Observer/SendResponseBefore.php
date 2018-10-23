<?php

namespace LoganStellway\PropelMedia\Observer;

/**
 * Dependencies
 */
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Before send response
 */
class SendResponseBefore implements ObserverInterface
{
    /**
     * @var \LoganStellway\PropelMedia\Helper\Data
     */
    protected $_helper;

    /**
     * @var \LoganStellway\PropelMedia\Model\Session
     */
    protected $_session;

    /**
     * @param \LoganStellway\PropelMedia\Helper\Data
     * @param \LoganStellway\PropelMedia\Model\Session
     */
    public function __construct(
        \LoganStellway\PropelMedia\Helper\Data $helper,
        \LoganStellway\PropelMedia\Model\Session $session
    ) {
        $this->_helper = $helper;
        $this->_session = $session;
    }

    /**
     * Search for token
     * @param  Observer  $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->_helper->getEnabled()) {
            $request = $observer->getRequest();
            $tokenParams = $this->_helper->getTokenParameters();

            foreach ($request->getQueryValue() as $key => $value) {
                if (in_array($key, $tokenParams)) {
                    $this->_session->setToken(urldecode($value));
                    return;
                }
            }
        }
    }
}
