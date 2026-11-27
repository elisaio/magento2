<?php

namespace Elisa\ProductApi\Model\ProductDataBuilder;

use Elisa\ProductApi\Api\Data\ElisaProduct\ProductDataInterface;
use Elisa\ProductApi\Api\ParentProductIdProviderInterface;
use Elisa\ProductApi\Api\ProductDataBuilderInterface;
use Elisa\ProductApi\Model\Config;
use Elisa\ProductApi\Model\Data\OptionSource\MappableProductDataFields;
use Elisa\ProductApi\Model\DataBuilderContext as Context;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\Area;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Basic implements ProductDataBuilderInterface
{
    /** @var Config */
    protected $config;
    /** @var Context */
    protected $context;
    /** @var ImageHelper */
    protected $imageHelper;
    /** @var ParentProductIdProviderInterface */
    protected $parentProductIdProvider;
    /** @var array|null */
    protected $elisaProductDataMap = null;

    /**
     * @param ParentProductIdProviderInterface $parentProductIdProvider
     * @param ImageHelper $imageHelper
     * @param Context $context
     * @param Config $config
     */
    public function __construct(
        ParentProductIdProviderInterface $parentProductIdProvider,
        ImageHelper $imageHelper,
        Context $context,
        Config $config
    ) {
        $this->config = $config;
        $this->context = $context;
        $this->imageHelper = $imageHelper;
        $this->parentProductIdProvider = $parentProductIdProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute(
        ProductDataInterface $productData,
        ProductInterface $product,
        ?ProductInterface $parentProduct = null
    ): ?array {
        if (($product instanceof \Magento\Catalog\Model\Product) === false) {
            return null;
        }

        $parentIds = $this->parentProductIdProvider->execute([$product->getId()]);

        if ($parentIds) {
            $productData->setParentIds($parentIds);
        }

        $storeId = $this->context->getStoreManager()->getStore()->getId();
        $this->context->getAppEmulation()->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);

        $productData->setEntityId($product->getId())
            ->setSku($product->getSku())
            ->setName(
                (string)$this->getElisaProductFieldData(
                    MappableProductDataFields::FIELD_NAME,
                    $product,
                    $product->getName()
                )
            )->setDescription((string)$product->getData('description'))
            ->setPrice($product->getFinalPrice())
            ->setSuggestedRetailPrice(
                (float)$this->getElisaProductFieldData(
                    MappableProductDataFields::FIELD_RECOMMENDED_RETAIL_PRICE,
                    $product,
                    $product->getPrice() ?? 0
                )
            )->setUrl($product->getProductUrl())
            ->setTypeId($product->getTypeId())
            ->setStatus($product->getStatus())
            ->setVisibility($product->getVisibility() ?? Visibility::VISIBILITY_NOT_VISIBLE)
            ->setWebsiteIds($product->getWebsiteIds());

        $mainImageUrl = null;
        $imageAttrValue = $product->getData('image');

        $productImages = $product->getMediaGalleryImages();
        $otherImages = [];

        foreach ($productImages as $productImage) {
            if ($imageAttrValue && $productImage->getFile() === $imageAttrValue) {
                $mainImageUrl = $productImage->getUrl();
                continue;
            }

            $otherImages[] = $productImage->getUrl();
        }

        if (!$mainImageUrl) {
            $mainImageUrl = $this->imageHelper->init($product, 'product_page_image_large')->getUrl();
        }

        $productData->setMainImage($mainImageUrl);
        $productData->setOtherImages($otherImages);

        $this->context->getAppEmulation()->stopEnvironmentEmulation();

        return null;
    }

    /**
     * Gets elisa product field data
     *
     * @param string $field
     * @param \Magento\Catalog\Model\Product $product
     * @param mixed $defaultValue
     * @return mixed
     */
    private function getElisaProductFieldData(
        string $field,
        \Magento\Catalog\Model\Product $product,
        $defaultValue = null
    ) {
        $map = $this->getElisaProductDataMap();

        if (!array_key_exists($field, $map)) {
            return $defaultValue;
        }

        return $product->hasData($map[$field]) ? $product->getData($map[$field]) : $defaultValue;
    }

    /**
     * Returns mapping of data fields to attribute code
     *
     * @return array
     */
    private function getElisaProductDataMap(): array
    {
        if ($this->elisaProductDataMap === null) {
            $this->elisaProductDataMap = [];
            $configMapping = $this->config->getProductDataMapping();

            foreach ($configMapping as $mapping) {
                $field = (string)$mapping['field'];
                $attributeCode = (string)$mapping['attribute_code'];

                if (!$attributeCode || !in_array($field, MappableProductDataFields::FIELDS)) {
                    continue;
                }

                $this->elisaProductDataMap[$field] = $attributeCode;
            }
        }

        return $this->elisaProductDataMap;
    }
}
