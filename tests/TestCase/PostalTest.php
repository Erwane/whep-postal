<?php
/**
 * This file is part of WHEP library
 *
 * @copyright   Copyright (c) Erwane BRETON
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
declare(strict_types=1);

namespace TestCase;

use PHPUnit\Framework\TestCase;
use ResourceHelper\File;
use WHEP\Client;
use WHEP\ProviderInterface;

/**
 * @uses \WHEP\Provider\Postal
 */
class PostalTest extends TestCase
{
    public function testLoadNoData(): void
    {
        $p = Client::getProvider('postal');
        $p->process([]);

        $this->assertEquals(ProviderInterface::TYPE_ERROR, $p->getType());
        $this->assertEquals([], $p->getRaw());
        $this->assertNull($p->getEmail());
    }

    public static function dataLoad(): array
    {
        return [
            [
                'bounced_undelivered.json',
                ProviderInterface::TYPE_BOUNCED,
                'Undelivered Mail Returned to Sender',
                null,
                'recipient@example.com',
                null,
            ],
            [
                'delayed_bad_reputation.json',
                ProviderInterface::TYPE_ERROR,
                'No SMTP servers were available.',
                '554 IP=1.2.3.4 - None/bad reputation.',
                'recipient.name@example.com',
                null,
            ],
            [
                'delayed_defer_busy.json',
                ProviderInterface::TYPE_SOFT_FAIL,
                'No SMTP servers were available.',
                '451 DEFER - D: We are busy;',
                'recipient@example.com',
                null,
            ],
            [
                'delayed_no_smtp_softfail.json',
                ProviderInterface::TYPE_SOFT_FAIL,
                'No SMTP servers were available for mx.com. No hosts to try.',
                '',
                'recipient@example.com',
                null,
            ],
            [
                'delayed_quota.json',
                ProviderInterface::TYPE_QUOTA,
                'Temporary SMTP delivery error when sending to 1.2.3.4:25 (gmail-smtp-in.l.google.com)',
                "452-4.2.2 The recipient's inbox is out of storage space. Please direct the",
                'recipient@example.com',
                null,
            ],
            [
                'delivery_failed.json',
                ProviderInterface::TYPE_HARD_FAIL,
                'Permanent SMTP delivery error when sending to 1.2.3.4:25 (mx.com)',
                '550 5.4.1 Recipient address rejected: Access denied.',
                'recipient@example.com',
                null,
            ],
            [
                'link_clicked.json',
                ProviderInterface::TYPE_CLICK,
                null,
                null,
                'recipient@example.com',
                'https://company.com/landing_page',
            ],
        ];
    }

    /** @dataProvider dataLoad */
    public function testLoad($resource, $type, $details, $smtp, $email, $url): void
    {
        $json = File::getContent($resource);
        $data = json_decode($json, true);

        $p = Client::getProvider('postal');
        $p->process($data);

        $this->assertEquals($type, $p->getType());
        $this->assertEquals($details, $p->getDetails());
        $this->assertEquals($smtp, $p->getSmtpResponse());
        $this->assertEquals($email, $p->getEmail());
        $this->assertEquals($url, $p->getUrl());
    }
}
