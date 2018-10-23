<?php

namespace LoganStellway\PropelMedia\Helper;

/**
 * Dependencies
 */
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Data helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Config constants
     */
    const CONFIG_PREFIX = 'propelmedia';
    const CONFIG_GENERAL = 'general';
    const CONFIG_CAMPAIGNS = 'campaigns';
    const CONFIG_REPORTING = 'reporting';
    const CURRENCY = 'USD';
    const REPORTING_PARAMS = ['token' => '{token}', 'value' => '{value}'];

    /**
     * Class variables
     */
    protected $_tokenParams;
    protected $_reportingParams;

    /**
     * Get config path
     * 
     * @param  string $type
     * @param  string $field
     * @return string
     */
    protected function getPath(string $type, string $field)
    {
        return implode('/', [self::CONFIG_PREFIX, $type, $field]);
    }

    /**
     * Get value
     * 
     * @param  string                $path
     * @param  ScopeConfigInterface  $scope
     * @param  int                   $scopeCode
     * @return mixed
     */
    protected function getValue($path = null, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->scopeConfig->getValue($path, $scope, $scopeCode);
    }

    /**
     * Get enabled
     * 
     * @param  ScopeConfigInterface  $scope
     * @param  int                   $scopeCode
     * @return mixed
     */
    public function getEnabled($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return (bool) $this->getValue(
            $this->getPath(self::CONFIG_GENERAL, 'enabled'),
            $scope,
            $scopeCode
        );
    }

    /**
     * Get token parameters
     * 
     * @param  ScopeConfigInterface  $scope
     * @param  int                   $scopeCode
     * @return array
     */
    public function getTokenParameters($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        if (!$this->_tokenParams) {
            $this->_tokenParams = [];
            $params = $this->getValue(
                $this->getPath(self::CONFIG_CAMPAIGNS, 'params'),
                $scope,
                $scopeCode
            );

            try {
                $params = json_decode($params, true);

                if (is_array($params)) {
                    foreach ($params as $row) {
                        if (isset($row['name'])) {
                            $this->_tokenParams[] = $row['name'];
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->_tokenParams = [];
            }
        }

        return $this->_tokenParams;
    }

    /**
     * Get reporting base URL
     * 
     * @param  ScopeConfigInterface  $scope
     * @param  int                   $scopeCode
     * @return string
     */
    public function getReportingUrl($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->getValue(
            $this->getPath(self::CONFIG_REPORTING, 'base_url'),
            $scope,
            $scopeCode
        );
    }

    /**
     * Get reporting parameters
     * 
     * @param  ScopeConfigInterface  $scope
     * @param  int                   $scopeCode
     * @return array
     */
    public function getReportingParameters($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        if (!$this->_reportingParams) {
            $this->_reportingParams = [];
            $params = $this->getValue(
                $this->getPath(self::CONFIG_REPORTING, 'params'),
                $scope,
                $scopeCode
            );

            try {
                $params = json_decode($params, true);

                if (is_array($params)) {
                    foreach ($params as $row) {
                        if (isset($row['name']) && isset($row['value'])) {
                            $this->_reportingParams[$row['name']] = $row['value'];
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->_reportingParams = [];
            }
        }

        return $this->_reportingParams;
    }

    /**
     * Build reporting URL
     * 
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function buildReportingUrl(array $params = [])
    {
        $query = $this->getReportingParameters();

        foreach ($query as $key => $value) {
            $value = trim($value);
            if ($key = array_search($value, self::REPORTING_PARAMS)) {
                $query[$key] = isset($params[$key]) ? $params[$key] : null;
            }
        }

        return explode('?', $this->getReportingUrl())[0] . '?' . http_build_query($query);
    }
}
