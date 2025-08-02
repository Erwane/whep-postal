<?php
/**
 * This file is part of WHEP library
 *
 * @copyright   Copyright (c) Erwane BRETON
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
declare(strict_types=1);

namespace WHEP\Provider;

use WHEP\AbstractProvider;
use WHEP\ProviderInterface;

/**
 * Postal server provider.
 *
 * @link https://docs.postalserver.io/
 */
class Postal extends AbstractProvider
{
    protected $_typesMap = [
        'MessageLoaded' => ProviderInterface::TYPE_OPENED,
        'MessageLinkClicked' => ProviderInterface::TYPE_CLICK,
        'Sent' => ProviderInterface::TYPE_SENT,
        'SoftFail' => ProviderInterface::TYPE_SOFT_FAIL,
        'HardFail' => ProviderInterface::TYPE_HARD_FAIL,
        'MessageBounced' => ProviderInterface::TYPE_BOUNCED,
    ];

    /**
     * @inheritDoc
     */
    protected function _load(array $data): void
    {
        parent::_load($data);

        $payload = $data['payload'] ?? $data;

        $status = $payload['status'] ?? $data['event'] ?? null;
        $message = $payload['message'] ?? $payload['original_message'] ?? [];
        $bounce = $payload['bounce'] ?? null;

        // Type
        $this->_type = $this->_typesMap[$status] ?? ProviderInterface::TYPE_ERROR;

        // Details
        $this->_details = $payload['details'] ?? $bounce['subject'] ?? null;

        // SMTP output
        $this->_smtp = $payload['output'] ?? null;

        // Raw
        $this->_raw = $payload;

        // E-mail
        $this->_email = $message['to'] ?? null;

        if ($this->_type === self::TYPE_CLICK) {
            $this->_url = $payload['url'] ?? null;
        }
    }
}
