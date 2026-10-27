<?php
namespace Elisa\ProductApi\Model;

use Elisa\ProductApi\Api\OrderManagementInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrdersCollectionFactory;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class OrderManagement implements OrderManagementInterface
{
    /** @var OrderExtensionFactory  */
    protected $extensionFactory;
    /**
     * @var OrdersCollectionFactory
     */
    protected $ordersCollectionFactory;

    /**
     * @param OrdersCollectionFactory $ordersCollectionFactory
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(
        OrdersCollectionFactory $ordersCollectionFactory,
        OrderExtensionFactory $extensionFactory
    ) {
        $this->ordersCollectionFactory = $ordersCollectionFactory;
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * @inheritdoc
     */
    public function addElisaReferenceId(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        $refId = $order->getData('ref_id');

        if ($refId) {
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ?: $this->extensionFactory->create();
            $extensionAttributes->setRefId($refId);
            $order->setExtensionAttributes($extensionAttributes);
        }
    }

    /**
     * @inheritdoc
     */
    public function getList(int $timestamp): array
    {
        $ordersCollection = $this->ordersCollectionFactory->create();

        // A quote gets one link per applied cart request, and each request replaces the quote items.
        // The latest link is therefore the cart request the order was placed from.
        $latestLinkSelect = $ordersCollection->getConnection()->select()
            ->from(['latest' => 'elisa_productsapi_cartrequest_to_quote'], [new \Zend_Db_Expr('MAX(latest.id)')])
            ->where('latest.quote_id = main_table.quote_id');

        $ordersCollection->getSelect()->joinInner(
            ['crq' => 'elisa_productsapi_cartrequest_to_quote'],
            "crq.id = (" . $latestLinkSelect . ")",
            []
        );

        $ordersCollection->getSelect()->joinLeft(
            ['cr' => 'elisa_productsapi_cartrequest'],
            "crq.ref_id = cr.id",
            ['ref_id' => 'cr.ref_id']
        );

        $updatedAt = date("Y-m-d H:i:s", $timestamp ?: 0);

        $ordersCollection->addFieldToFilter('updated_at', ['gt' => $updatedAt]);
        $ordersCollection->addFieldToFilter('crq.ref_id', ['notnull' => true]);

        /** @var \Magento\Sales\Api\Data\OrderInterface[] $orders */
        $orders = $ordersCollection->getItems();

        foreach ($orders as $order) {
            $this->addElisaReferenceId($order);
        }

        return $orders;
    }

    /**
     * @inheritDoc
     */
    public function isOrderFromElisa(int $orderId): bool
    {
        $ordersCollection = $this->ordersCollectionFactory->create();

        $ordersCollection->addAttributeToFilter('entity_id', $orderId);

        $ordersCollection->getSelect()->joinInner(
            ['crq' => 'elisa_productsapi_cartrequest_to_quote'],
            "main_table.quote_id = crq.quote_id"
        );

        $ordersCollection->getSelect()->joinInner(
            ['cr' => 'elisa_productsapi_cartrequest'],
            "crq.ref_id = cr.id"
        );

        return $ordersCollection->getSize() > 0;
    }
}
