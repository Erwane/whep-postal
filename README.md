# [Postal](https://docs.postalserver.io/) webhook handler for [WHEP](https://github.com/Erwane/whep-postal) project

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![codecov](https://codecov.io/gh/Erwane/whep-postal/graph/badge.svg?token=F848Z7Z1Z2)](https://codecov.io/gh/Erwane/whep-postal)
[![CI](https://github.com/Erwane/whep-postal/actions/workflows/ci.yml/badge.svg)](https://github.com/Erwane/whep-postal/actions/workflows/ci.yml)
[![Packagist Downloads](https://img.shields.io/packagist/dt/Erwane/whep-postal)](https://packagist.org/packages/Erwane/whep-postal)
[![Packagist Version](https://img.shields.io/packagist/v/Erwane/whep-postal)](https://packagist.org/packages/Erwane/whep-postal)

Webhook handler for [postal](https://docs.postalserver.io/) emailing provider.

## Usage

```shell
composer require erwane/whep-postal
```

```php
use WHEP\Exception\IpException;  
use WHEP\Exception\ProviderException;  
use WHEP\Factory;

try {
    $provider = Factory::provider('postal', [
        'allowed_ip' => ['my.postal.server.ipv4', 'my:postal:server::ipv6'],
        'client_ip' => $_SERVER['REMOTE_ADDR'] ?? null, // Use method from your framework to get the ServerRequest client ip.
        'callbacks' => [
            ProviderInterface::EVENT_BLOCKED => [$this, 'callbackInvalidate'],
            ProviderInterface::EVENT_BOUNCE_QUOTA => [$this, 'callbackUnsub'],
        ],
    ]);

    // process the data.
    $provider->process($webhookData);
    
    // Data available from provider getters.
    $recipient = $provider->getRecipient();
    
    // Launch callbacks
    $provider->callback();
} catch (IpException $e) {
    // log ?
} catch (ProviderException $e) {
    // log ?
}
```

See [WHEP Client README](https://github.com/Erwane/whep-client) for options and getters methods.
