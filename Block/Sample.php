<?php
namespace AHT\RequestSample\Block;

use Magento\Framework\App\Http\Context as AuthContext;

class Sample extends \Magento\Framework\View\Element\Template
{
    private $_registry;
    protected $customerSession;
    protected $_storeManager;
    protected $authContext;
    protected $httpContext;
    protected $countryInformationAcquirer;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformationAcquirer,
        AuthContext  $authContext,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_registry = $registry;
        $this->_customerSession = $customerSession;
        $this->authContext = $authContext;
        $this->httpContext = $httpContext;
        $this->countryInformationAcquirer = $countryInformationAcquirer;

        parent::__construct($context, $data);
    }
    public function isLogin()
    {
        return $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getCustomerName()
    {
        return $this->httpContext->getValue('customer_name');
    }
    //
    public function getCountry()
    {
        $data = [];
        $countries = $this->countryInformationAcquirer->getCountriesInfo();
        foreach ($countries as $country) {
            $regions = [];
            if ($availableRegions = $country->getAvailableRegions()) {
                foreach ($availableRegions as $region) {
                    $regions[] = [
                        'id'   => $region->getId(),
                        'code' => $region->getCode(),
                        'name' => $region->getName()
                    ];
                }
            }
            $data[] = [
                'value'   => $country->getTwoLetterAbbreviation(),
                'label'   => __($country->getFullNameLocale()),
                'regions' => $regions
            ];
        }
        return $data;
    }
}
