<?php

namespace Rsc\Components\Observer;

use Magento\Framework\Event\ObserverInterface;
use Rsc\Components\Model\ComponentManager;

class BlockInstantiation implements ObserverInterface
{
    protected $ComponentManager;

    public function __construct(ComponentManager $ComponentManager)
    {
        $this->ComponentManager = $ComponentManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $observer->getBlock()->ComponentManager = $this->ComponentManager;
    }
}
