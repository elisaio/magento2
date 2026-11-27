<?php

namespace Elisa\ProductApi\Model\Data\ElisaProduct;

use Elisa\ProductApi\Api\Data\ElisaProduct\ProductData\ConfigurableSetupInterface;
use Elisa\ProductApi\Api\Data\ElisaProduct\ProductDataInterface;
use Magento\Framework\DataObject;

class ProductData extends DataObject implements ProductDataInterface
{
    private const KEY_CONFIGURABLE_OPTIONS = 'configurable_options';
    private const KEY_CONFIGURABLE_SETUP = 'configurable_setup';
    private const KEY_DESCRIPTION = 'description';
    private const KEY_ENTITY_ID = 'entity_id';
    private const KEY_MAIN_IMAGE = 'main_image';
    private const KEY_NAME = 'name';
    private const KEY_OTHER_IMAGES = 'other_images';
    private const KEY_PARENT_IDS = 'parent_ids';
    private const KEY_PRICE = 'price';
    private const KEY_SKU = 'sku';
    private const KEY_STATUS = 'status';
    private const KEY_SUGGESTED_RETAIL_PRICE = 'suggested_retail_price';
    private const KEY_TYPE_ID = 'type_id';
    private const KEY_URL = 'url';
    private const KEY_VISIBILITY = 'visibility';
    private const KEY_WEBSITE_IDS = 'website_ids';

    /**
     * @inheritDoc
     */
    public function getConfigurableOptions(): ?array
    {
        return $this->getData(self::KEY_CONFIGURABLE_OPTIONS);
    }

    /**
     * @inheritDoc
     */
    public function getConfigurableSetup(): ?ConfigurableSetupInterface
    {
        return $this->getData(self::KEY_CONFIGURABLE_SETUP);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return (string)$this->getData(self::KEY_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId(): int
    {
        return (int)$this->getData(self::KEY_ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function getMainImage(): string
    {
        return (string)$this->getData(self::KEY_MAIN_IMAGE);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return (string)$this->getData(self::KEY_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getOtherImages(): array
    {
        return (array)$this->getData(self::KEY_OTHER_IMAGES);
    }

    /**
     * @inheritDoc
     */
    public function getParentIds(): array
    {
        return (array)$this->getData(self::KEY_PARENT_IDS);
    }

    /**
     * @inheritDoc
     */
    public function getPrice(): float
    {
        return (float)$this->getData(self::KEY_PRICE);
    }

    /**
     * @inheritDoc
     */
    public function getSku(): string
    {
        return (string)$this->getData(self::KEY_SKU);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return (int)$this->getData(self::KEY_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function getSuggestedRetailPrice(): float
    {
        return (float)$this->getData(self::KEY_SUGGESTED_RETAIL_PRICE);
    }

    /**
     * @inheritDoc
     */
    public function getTypeId(): string
    {
        return (string)$this->getData(self::KEY_TYPE_ID);
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return (string)$this->getData(self::KEY_URL);
    }

    /**
     * @inheritDoc
     */
    public function getVisibility(): int
    {
        return (int)$this->getData(self::KEY_VISIBILITY);
    }

    /**
     * @inheritDoc
     */
    public function getWebsiteIds(): array
    {
        return (array)$this->getData(self::KEY_WEBSITE_IDS);
    }

    /**
     * @inheritDoc
     */
    public function setConfigurableOptions(array $value): ProductDataInterface
    {
        return $this->setData(self::KEY_CONFIGURABLE_OPTIONS, $value);
    }

    /**
     * @inheritDoc
     */
    public function setConfigurableSetup(ConfigurableSetupInterface $value): ProductDataInterface
    {
        return $this->setData(self::KEY_CONFIGURABLE_SETUP, $value);
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $value): ProductDataInterface
    {
        return $this->setData(self::KEY_DESCRIPTION, $value);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId(int $value): ProductDataInterface
    {
        return $this->setData(self::KEY_ENTITY_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function setMainImage(string $value): ProductDataInterface
    {
        return $this->setData(self::KEY_MAIN_IMAGE, $value);
    }

    /**
     * @inheritDoc
     */
    public function setName(string $value): ProductDataInterface
    {
        return $this->setData(self::KEY_NAME, $value);
    }

    /**
     * @inheritDoc
     */
    public function setOtherImages(array $value): ProductDataInterface
    {
        return $this->setData(self::KEY_OTHER_IMAGES, $value);
    }

    /**
     * @inheritDoc
     */
    public function setParentIds(array $value): ProductDataInterface
    {
        return $this->setData(self::KEY_PARENT_IDS, $value);
    }

    /**
     * @inheritDoc
     */
    public function setPrice(float $value): ProductDataInterface
    {
        return $this->setData(self::KEY_PRICE, $value);
    }

    /**
     * @inheritDoc
     */
    public function setSku(string $value): ProductDataInterface
    {
        return $this->setData(self::KEY_SKU, $value);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $value): ProductDataInterface
    {
        return $this->setData(self::KEY_STATUS, $value);
    }

    /**
     * @inheritDoc
     */
    public function setSuggestedRetailPrice(float $value): ProductDataInterface
    {
        return $this->setData(self::KEY_SUGGESTED_RETAIL_PRICE, $value);
    }

    /**
     * @inheritDoc
     */
    public function setTypeId(string $value): ProductDataInterface
    {
        return $this->setData(self::KEY_TYPE_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUrl(string $value): ProductDataInterface
    {
        return $this->setData(self::KEY_URL, $value);
    }

    /**
     * @inheritDoc
     */
    public function setVisibility(int $value): ProductDataInterface
    {
        return $this->setData(self::KEY_VISIBILITY, $value);
    }

    /**
     * @inheritDoc
     */
    public function setWebsiteIds(array $value): ProductDataInterface
    {
        return $this->setData(self::KEY_WEBSITE_IDS, $value);
    }

}
