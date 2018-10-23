<?php

namespace LoganStellway\PropelMedia\Observer;

/**
 * Dependencies
 */
use Magento\Framework\Event;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Before send response
 */
class CheckoutSubmitAllAfter implements ObserverInterface
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
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $_curl;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @param \LoganStellway\PropelMedia\Helper\Data
     * @param \LoganStellway\PropelMedia\Model\Session
     */
    public function __construct(
        \LoganStellway\PropelMedia\Helper\Data $helper,
        \LoganStellway\PropelMedia\Model\Session $session,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ) {
        $this->_helper = $helper;
        $this->_session = $session;
        $this->_curl = $curl;
        $this->_priceCurrency = $priceCurrency;
    }

    /**
     * Search for token
     * 
     * @param  Observer  $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->_helper->getEnabled()) {
            $token = $this->_session->getToken();

            if ($token) {
                $orders = $this->extractOrders($observer->getEvent());

                if (null === $orders) {
                    return;
                }

                foreach ($orders as $order) {
                    $value = $this->_priceCurrency->convertAndRound(
                        $order->getGrandTotal(),
                        $order->getStoreId(),
                        $this->_helper::CURRENCY
                    );

                    // Send conversion to PropelMedia
                    $curl = $this->_curl->get(
                        $this->_helper->buildReportingUrl(compact('token', 'value'))
                    );
                }
            }
        }
    }

    /**
     * Returns Orders entity list from Event data container
     *
     * @param Event $event
     * @return OrderInterface[]|null
     */
    private function extractOrders(Event $event)
    {
        $order = $event->getData('order');
        if (null !== $order) {
            return [$order];
        }

        return $event->getData('orders');
    }
}
