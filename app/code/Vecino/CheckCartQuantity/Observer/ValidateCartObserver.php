<?php
namespace Vecino\CheckCartQuantity\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;

use Vecino\CheckCartQuantity\Helper\Data as HelperData;

class ValidateCartObserver implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * @var Cart
     */
    protected $cart;
    /**
    * @var HelperData
    */
    protected $helperData;

    /**
     * @param ManagerInterface $messageManager
     * @param RedirectInterface $redirect
     * @param CustomerCart $cart
     * @param HelperData $helperData
     */
    public function __construct(
        ManagerInterface $messageManager,
        RedirectInterface $redirect,
        CustomerCart $cart,
        HelperData $helperData
    ) {
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->cart = $cart;
        
        $this->helperData = $helperData;
    }

    /**
     * Validate Cart Before going to checkout
     * - event: controller_action_predispatch_checkout_index_index
     *
     * @param Observer $observer
     * @return void
     */
        
    public function execute(Observer $observer)
    {
        /* Get quote data from current cart */
        $quote          = $this->cart->getQuote();
        /* Get the Controller Action */
        $controller     = $observer->getControllerAction();
        /* Multiplier */
        $multiplier     = $this->helperData->getGeneralConfig('multiplier');
        /* Warning text */
        $warning_text   = $this->helperData->getGeneralConfig('display_text');
        /* 
        Array with excluded category ID's */
        if ( empty($this->helperData->getGeneralConfig('excluded_cats') ) ){
            $excludedCats = array();
        } else {
            $excludedCats   = explode( ',', $this->helperData->getGeneralConfig('excluded_cats'));
        }
        
        /* Set cart quantity to zero */
        $totalQty       = 0;
        
        foreach($quote->getAllVisibleItems() as $item) {
            /** Set check counter to 0 */
            $count = 0;
            
            /** Get category ID's this item is linked to */
            $cats = $item->getProduct()->getCategoryIds();
            
            /** Check if any of there ID's is in the excluded array */
            foreach ($cats as $cat) {
                if (in_array($cat, $excludedCats)) {
                    /** If so, up counter value */
                    $count++;
                }
            }
            /** Only if category is not in the excluded list, count as totalQty */
            if ($count == 0) {
                $totalQty += $item->getQty();
            }
        }

        if(0 != $totalQty % $multiplier) {
            //This adds error message and add error to the Quote
            $message = str_replace('%', $multiplier, $warning_text);
            $this->messageManager->addErrorMessage(
                __($message)
            );
            $this->redirect->redirect($controller->getResponse(), 'checkout/cart');
        }


    }
}