# [Postal](https://docs.postalserver.io/) webhook handler for [WHEP](https://github.com/Erwane/whep-postal) project

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![codecov](https://codecov.io/gh/Erwane/whep-postal/branch/2.x/graph/badge.svg?token=F848Z7Z1Z2)](https://codecov.io/gh/Erwane/whep-postal)
[![Build Status](https://github.com/Erwane/whep-postal/actions/workflows/ci.yml/badge.svg?branch=2.x)](https://github.com/Erwane/whep-postal/actions)
[![Packagist Downloads](https://img.shields.io/packagist/dt/Erwane/whep-postal)](https://packagist.org/packages/Erwane/whep-postal)
[![Packagist Version](https://img.shields.io/packagist/v/Erwane/whep-postal)](https://packagist.org/packages/Erwane/whep-postal)

Webhook handler for [postal](https://docs.postalserver.io/) emailing provider.

## Usage

```shell
composer require erwane/whep-postal
```

```php
use WHEP\Client;
use WHEP\WebhookProviderException;

$provider = Client::getProvider('postal', [
    'callbacks' => [
        ProviderInterface::EVENT_BLOCKED => [$this, 'callbackInvalidate'],
        ProviderInterface::EVENT_BOUNCE_QUOTA => [$this, 'callbackUnsub'],
    ],
]);

try {
    // process the data.
    $provider->process($webhookData);
    
    // Data available from provider getters.
    $email = $provider->getRecipient();
    
    // Launch callbacks
    $provider->callback();
} catch (WebhookProviderException $e) {
    // log ?
}
```

See [WHEP Client README](https://github.com/Erwane/whep-client) for getters.
