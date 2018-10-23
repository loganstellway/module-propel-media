<?php

namespace LoganStellway\PropelMedia\Model;

/**
 * Session model
 */
class Session extends \Magento\Framework\Session\SessionManager
{
    const PROPELMEDIA_TOKEN = 'propelmedia_token';

    /**
     * Set token value
     *
     * @param string|null $id
     * @return $this
     */
    public function setToken($value)
    {
        $this->storage->setData(self::PROPELMEDIA_TOKEN, $value);
        return $this;
    }

    /**
     * Get token value
     *
     * @api
     * @return int|null
     */
    public function getToken()
    {
        return $this->storage->getData(self::PROPELMEDIA_TOKEN);
    }
}
