<?php
namespace AHT\RequestSample\Controller\Sample;

use Magento\Framework\App\RequestInterface;

class Checklogin extends \Magento\Framework\App\Action\Action
{
    /**
     * @var RedirectInterface
     */
    protected $_redirect;
    /**
     * @var Session
     */
    protected $_customerSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\RedirectInterface $resultRedirect,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->_customerSession = $customerSession;
        $this->_redirect = $resultRedirect;

        return parent::__construct($context);
    }

    public function execute()
    {
    }

    /**
     * Redirect to page login 
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     *
     * 
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->_customerSession->authenticate()) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            $this->_customerSession->setBeforeAuthUrl($this->_url->getUrl(
                $this->_redirect->getRefererUrl()
            ));
        }
        return parent::dispatch($request);
    }
}
