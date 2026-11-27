<?php

namespace Elisa\ProductApi\Api\Data\ElisaProduct;

use Elisa\ProductApi\Api\Data\ElisaProduct\ProductData\ConfigurableSetupInterface;

/**
 * @api
 */
interface ProductDataInterface
{
    /**
     * Get the configurable attribute options if the product is a variant
     *
     * @return \Elisa\ProductApi\Api\Data\ElisaProduct\ProductData\ConfigurableOptionInterface[]|null Configurable
     * attribute options
     */
    public function getConfigurableOptions(): ?array;

    /**
     * Get the configurable product setup if product is of type 'configurable'
     *
     * @return \Elisa\ProductApi\Api\Data\ElisaProduct\ProductData\ConfigurableSetupInterface|null Configurable product
     * setup
     */
    public function getConfigurableSetup(): ?ConfigurableSetupInterface;

    /**
     * Get Description
     *
     * @return string Description of product
     */
    public function getDescription(): string;

    /**
     * Get Entity ID
     *
     * @return int Entity ID of product
     */
    public function getEntityId(): int;

    /**
     * Get Main Image URL
     *
     * @return string Main Image URL of product
     */
    public function getMainImage(): string;

    /**
     * Get Name
     *
     * @return string Name of product
     */
    public function getName(): string;

    /**
     * Get array of URLs
     *
     * @return string[] Array of URLs of all images
     */
    public function getOtherImages(): array;

    /**
     * Get Parent Ids
     *
     * @return int[]|null Entity IDs of parent items
     */
    public function getParentIds(): ?array;

    /**
     * Get Price
     *
     * @return float Retail price of product
     */
    public function getPrice(): float;

    /**
     * Get SKU
     *
     * @return string SKU of product
     */
    public function getSku(): string;

    /**
     * Get Status
     *
     * @return int Status of product
     */
    public function getStatus(): int;

    /**
     * Get Suggested Retail Price
     *
     * @return float Suggested retail price of product
     */
    public function getSuggestedRetailPrice(): float;

    /**
     * Get Product Type ID
     *
     * @return string Type of the product
     */
    public function getTypeId(): string;

    /**
     * Get URL
     *
     * @return string URL of the product
     */
    public function getUrl(): string;

    /**
     * Get Frontend Visibility
     *
     * @return int Visibility of the product
     */
    public function getVisibility(): int;

    /**
     * Get Associated Website IDs
     *
     * @return int[] Website IDs to which the product is assigned
     */
    public function getWebsiteIds(): array;

    /**
     * Set the configurable attribute options if the product is a variant
     *
     * @param \Elisa\ProductApi\Api\Data\ElisaProduct\ProductData\ConfigurableOptionInterface[] $value
     * @return ProductDataInterface
     */
    public function setConfigurableOptions(array $value): ProductDataInterface;

    /**
     * Set the configurable setup if product is of type 'configurable'
     *
     * @param \Elisa\ProductApi\Api\Data\ElisaProduct\ProductData\ConfigurableSetupInterface $value
     * @return ProductDataInterface
     */
    public function setConfigurableSetup(ConfigurableSetupInterface $value): ProductDataInterface;

    /**
     * Set Description
     *
     * @param string $value
     * @return ProductDataInterface
     */
    public function setDescription(string $value): ProductDataInterface;

    /**
     * Set Entity ID
     *
     * @param int $value
     * @return ProductDataInterface
     */
    public function setEntityId(int $value): ProductDataInterface;

    /**
     * Set Main Image URL
     *
     * @param string $value
     * @return ProductDataInterface
     */
    public function setMainImage(string $value): ProductDataInterface;

    /**
     * Set Name
     *
     * @param string $value
     * @return ProductDataInterface
     */
    public function setName(string $value): ProductDataInterface;

    /**
     * Set array of URLs
     *
     * @param string[] $value
     * @return ProductDataInterface
     */
    public function setOtherImages(array $value): ProductDataInterface;

    /**
     * Set array of parent ids
     *
     * @param int[] $value
     * @return ProductDataInterface
     */
    public function setParentIds(array $value): ProductDataInterface;

    /**
     * Set Price
     *
     * @param float $value
     * @return ProductDataInterface
     */
    public function setPrice(float $value): ProductDataInterface;

    /**
     * Set SKU
     *
     * @param string $value
     * @return ProductDataInterface
     */
    public function setSku(string $value): ProductDataInterface;

    /**
     * Set Status
     *
     * @param int $value
     * @return ProductDataInterface
     */
    public function setStatus(int $value): ProductDataInterface;

    /**
     * Set Suggested Retail Price
     *
     * @param float $value
     * @return ProductDataInterface
     */
    public function setSuggestedRetailPrice(float $value): ProductDataInterface;

    /**
     * Set Product Type ID
     *
     * @param string $value
     * @return ProductDataInterface
     */
    public function setTypeId(string $value): ProductDataInterface;

    /**
     * Set URL
     *
     * @param string $value
     * @return ProductDataInterface
     */
    public function setUrl(string $value): ProductDataInterface;

    /**
     * Set Frontend Visibility
     *
     * @param int $value
     * @return ProductDataInterface
     */
    public function setVisibility(int $value): ProductDataInterface;

    /**
     * Set Associated Website IDs
     *
     * @param int[] $value
     * @return ProductDataInterface
     */
    public function setWebsiteIds(array $value): ProductDataInterface;
}
