<?php
namespace AHT\RequestSample\Block;

use Magento\Framework\App\Http\Context as AuthContext;

class Sample extends \Magento\Framework\View\Element\Template
{
    protected $authContext;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        AuthContext  $authContext,
        array $data = []
    ) {
        $this->authContext = $authContext;
        parent::__construct($context, $data);
    }
    public function isLogin()
    {
        return $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }
}
