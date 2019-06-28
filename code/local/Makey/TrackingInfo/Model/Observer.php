<?php

class Makey_TrackingInfo_Model_Observer
{
    /**
     * Add new column to orders grid
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */

    public function prepareOrderGridCollection(Varien_Event_Observer $observer)
    {
        $collection = $observer->getEvent()->getOrderGridCollection();

        /*$orderCollection = Mage::getResourceModel('sales/order_collection'); //mageworx_ordersgrid_order_grid
        $orderCollection->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns(['order_id' => 'entity_id', 'coupon_rule_name']);

        $collection->getSelect()
            ->joinLeft(['order' => $orderCollection->getSelect()],
                'order.order_id = main_table.entity_id',
                ['coupon_rule_name']);*/
        //$shipping = "";
        $table = Mage::getSingleton('core/resource')
            ->getTableName('sales_flat_shipment_track');
        $shipping = "((select GROUP_CONCAT(t.current_status SEPARATOR ',') FROM `$table` as t where t.order_id=main_table.entity_id))";
        //$tmp = empty($shipping) ? $shipping : "Nodataavailable";
        $collection->getSelect()->from('', "$shipping as shipped_date2");

        return $this;
    }

    public function addColumnsToGrid(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();

        // Check whether the loaded block is the orders grid block
        if (!($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid)
            || $block->getNameInLayout() != 'sales_order.grid'
        ) {
            return $this;
        }

        // Add a new column right after the "Ship to Name" column
        $block->addColumnAfter('coupon_rule_name', [
            'header' => $block->__('Shipping Status'),
            'index' => 'shipped_date2',
        ], 'shipping_name');

        return $this;
    }

    public function update_tracking() {
        Mage::log("TEST success", null, "dev.log");

        /*$table = Mage::getSingleton('core/resource')
            ->getTableName('sales_flat_shipment_track');
        $shipping = "((select GROUP_CONCAT(t.current_status SEPARATOR ',') FROM `$table` as t where t.order_id=main_table.entity_id))";

        $connectionresource = Mage::getSingleton('core/resource');
        $readconnection = $connectionresource->getConnection('core_read');
        $allrecord = $readconnection->select()->from(array('customer'=>'customer_address_entity_varchar'),array('customer.value'))->where('customer.attribute_id=?', 143)->where('customer.entity_id=?', 567);
        $alldata =$readconnection->fetchOne($allrecord);
        echo $alldata;*/

    }

}