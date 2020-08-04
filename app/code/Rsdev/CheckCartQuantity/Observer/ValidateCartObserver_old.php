<?php
/**
 * @author      Tsvetan Stoychev <t.stoychev@extendix.com>
 * @website     http://www.extendix.com
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 */

class Extendix_ValidateCartMultipleQty_Model_Observer
{

    /**
     * @param Varien_Event_Observer $observer
     * @return Extendix_ValidateCartMultipleQty_Model_Observer
     */
    public function validateCartTotalMultipleQty(Varien_Event_Observer $observer)
    {
        if(!$this->_getHelper()->canApply()) {
            return $this;
        }

        $multiplier = $this->_getHelper()->getMultiplier();
        $totalQty   = 0;
		
		/** Array with excluded category ID's */
		//$excludedCats = array(51,113,114);
		$excludedCats = $this->_getHelper()->getExcludedCategories();

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $observer->getEvent()->getQuote();

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
            $quote->addErrorInfo(
                'multiple_qty',
                'Extendix_ValidateCartMultipleQty',
                'not_multiple_total_cart_qty',
                // E.g.: "You can only order in multiples of 6. Please review your cart before checking out."
                $this->_getHelper()->getErrorMessage()
            );
        }

        return $this;
    }

    /**
     * @return Extendix_ValidateCartMultipleQty_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('extendix_validatecartmultipleqty');
    }

}