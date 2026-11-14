<?php

namespace Elisa\ProductApi\Exception;

use Magento\Framework\Phrase;

class ServiceException extends ElisaException
{
    /**
     * @inheritDoc
     */
    public function __construct(?Phrase $phrase = null, ?\Throwable $cause = null, int $code = 0)
    {
        parent::__construct(
            $phrase ?? __('Elisa Service Error.'),
            $cause,
            $code
        );
    }
}
