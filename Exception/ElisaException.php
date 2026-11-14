<?php

namespace Elisa\ProductApi\Exception;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class ElisaException extends LocalizedException
{
    /**
     * @inheritDoc
     */
    public function __construct(?Phrase $phrase = null, ?\Throwable $cause = null, int $code = 0)
    {
        parent::__construct(
            $phrase,
            ($cause instanceof \Exception) || $cause === null
                ? $cause
                : new \Exception("Non-Exception Error: " . $cause->getMessage(), $cause->getCode(), $cause),
            $code
        );
    }
}
