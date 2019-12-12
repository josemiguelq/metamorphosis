<?php
namespace Tests\Connectors\Consumer;

use Metamorphosis\Connectors\Consumer\Config;
use Metamorphosis\Exceptions\ConfigurationException;
use Tests\LaravelTestCase;

class ConfigTest extends LaravelTestCase
{
    public function testShouldValidateConsumerConfig(): void
    {
        // Set
        $validate = new Config();
        $options = [
            'partition' => 0,
            'offset' => 0,
            'broker' => 'default',
        ];
        $arguments = [
            'topic' => 'default',
            'consumer_group' => 'default',
        ];

        $expected = [
            'topic' => 'default',
            'topic_id' => 'SOME-TOPIC-KAFKA-ID',
            'broker' => 'default',
            'offset_reset' => 'largest',
            'offset' => 0,
            'partition' => 0,
            'handler' => '\App\Kafka\Consumers\ConsumerExample',
            'timeout' => 20000,
            'consumer_group' => 'default',
            'connections' => 'kafka:6680',
            'schema_uri' => '',
            'auth' => [
                'type' => 'ssl',
                'ca' => storage_path('ca.pem'),
                'certificate' => storage_path('kafka.cert'),
                'key' => storage_path('kafka.key'),
            ],
        ];

        // Actions
        $validate->setOption($options, $arguments);

        // Assertions
        $this->assertArraySubset($expected, config('kafka.runtime'));
    }

    public function testShouldNotSetRuntimeConfigWhenOptionsIsInvalid(): void
    {
        // Set
        $validate = new Config();
        $options = [
            'partition' => 'one',
            'offset' => 0,
            'broker' => 'default',
        ];
        $arguments = [
            'topic' => 'default',
            'consumer_group' => 'default',
        ];

        // Actions
        $this->expectException(ConfigurationException::class);
        $validate->setOption($options, $arguments);

        // Assertions
        $this->assertEmpty(config('kafka.runtime'));
    }

    public function testShouldNotSetRuntimeConfigWhenKafkaConfigIsInvalid(): void
    {
        // Set
        config(['kafka.brokers.default.connections' => null]);
        $validate = new Config();
        $options = [
            'partition' => 0,
            'offset' => 0,
            'broker' => 'default',
        ];
        $arguments = [
            'topic' => 'default',
            'consumer_group' => 'default',
        ];

        // Actions
        $this->expectException(ConfigurationException::class);
        $validate->setOption($options, $arguments);

        // Assertions
        $this->assertEmpty(config('kafka.runtime'));
    }
}