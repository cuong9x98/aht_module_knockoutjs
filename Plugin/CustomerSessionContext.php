<?php
namespace AHT\RequestSample\Plugin;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Helper\View;

class CustomerSessionContext
{
    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var View
     */
    private $customerViewHelper;
    protected $_countryCollectionFactory;
    protected $customerFactory;
    protected $addressFactory;
    protected $_country;
    protected $countryFactory;
    protected $data;

    public function __construct(
        CurrentCustomer $currentCustomer,
        View $customerViewHelper,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Block\Data $data
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->customerViewHelper = $customerViewHelper;
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->countryFactory = $countryFactory;

        $this->data = $data;
    }
    public function afterGetSectionData(\Magento\Customer\CustomerData\Customer $subject, $result)
    {
        $options = $this->data->getRegionCollection()->toOptionArray();
        $optionscountry = $this->data->getCountryCollection()
            ->setForegroundCountries($this->data->getTopDestinations())
            ->toOptionArray();
        $data = [];
        $data_country = [];
        foreach ($optionscountry as $array){
            $data[] =  $array['label'];
        }
        foreach ($options as $array){
            $data_country[] =  $array['title'];
        }
        $customer = $this->currentCustomer->getCustomer();
        $address = $customer->getAddresses()[0];
        $country = $this->countryFactory->create()->loadByCode($address->getCountryId());
        //


        $result = array_merge([
            'email'=> $customer->getEmail(),
            'address_customer'=> $address->getStreet()[0],
            'addressline_customer'=> $address->getStreet()[1],
            'city_customer'=> $address->getStreet()[2],
            'country_customer' =>$country->getName(),
            'numberphone_customer'=> $address->getTelephone(),
            'zipcode_customer'=> $address->getPostcode(),
            'country_all'=> $data,
            'state_all'=> $data_country,
            'state_customer'=>$address->getRegion()->getRegion()
        ],$result);
        return $result;
    }
}
