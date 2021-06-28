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
     * @var CountryFactory
     */
    protected $countryFactory;
    /**
     * @var Data
     */
    protected $data;

    public function __construct(
        CurrentCustomer $currentCustomer,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Block\Data $data
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->countryFactory = $countryFactory;
        $this->data = $data;
    }
     /**
     * 
     *
     * @return \Magento\Customer\CustomerData\Customer
     *
     * 
     */
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
