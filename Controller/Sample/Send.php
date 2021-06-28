<?php
namespace AHT\RequestSample\Controller\Sample;

use Magento\Framework\Mail\Template\TransportBuilder;

class Send extends \Magento\Framework\App\Action\Action
{
    /**
     * @var RedirectInterface
     */
    protected $_redirect;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var Context
     */
    protected $messageManager;
    /**
     * @var ScopeConfigInterface
     */
    public $scopeConfig;
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\RedirectInterface $resultRedirect,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder
    )
    {
        $this->_storeManager = $storeManager;
        $this->_redirect = $resultRedirect;
        $this->messageManager = $context->getMessageManager();
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;

        return parent::__construct($context);
    }

    /**
     * Submit new review action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
         //-------------------------------------Send email
        try {
            if (isset($_POST['createbtn'])) {
                $receiverInfo = [
                    'name' => $this->getRequest()->getParam("name"),
                    'email' => $this->getRequest()->getParam("email"),
                    'question' => $this->getRequest()->getParam("question"),
                    'city' => $this->getRequest()->getParam("city"),
                    'address' => $this->getRequest()->getParam("address"),
                    'numberphone' => $this->getRequest()->getParam("numberphone")
                ];
                // --------------------------------------Send emai
                $store = $this->_storeManager->getStore();
                $templateParams = ['store' => $store, 'administrator_name' => $receiverInfo['name'],'administrator_email' => $receiverInfo['email'],'administrator_question' => $receiverInfo['question'],
                    'numberphone_customer'=>$receiverInfo['numberphone'],'address_customer'=>$receiverInfo['address'],'city_customer'=>$receiverInfo['city']];
                $email =  $this->scopeConfig->getValue('request/sample/receive_email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                if(empty($email)){
                    $email ='cuong9x98@gmail.com';
                }
                $transport = $this->transportBuilder->setTemplateIdentifier(
                    'email_template'
                )->setTemplateOptions(
                    ['area' => 'frontend', 'store' => $store->getId()]
                )->addTo(
                    $email, 'admin'
                )->setTemplateVars(
                    $templateParams
                )->setFrom(
                    'general'
                )->getTransport();
                $transport->sendMessage();
                //---------------------------------------End send email
            }
            $this->messageManager->addSuccessMessage("You send your request for moderation");
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage("We can\'t post your question right now.");
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
