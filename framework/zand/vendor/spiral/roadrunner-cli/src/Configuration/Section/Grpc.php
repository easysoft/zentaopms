<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Grpc extends AbstractSection
{
    private const NAME = 'grpc';

    public function render(): array
    {
        return [
            self::NAME => [
                'listen' => 'tcp://127.0.0.1:9001',
                'proto' => [
                    'first.proto',
                    'second.proto'
                ],
//                'tls' => [
//                    'key' => '',
//                    'cert' => '',
//                    'root_ca' => '',
//                    'client_auth_type' => 'no_client_certs'
//                ],
//                'max_send_msg_size' => 50,
//                'max_recv_msg_size' => 50,
//                'max_connection_idle' => '0s',
//                'max_connection_age' => '0s',
//                'max_connection_age_grace' => '0s8h',
//                'max_concurrent_streams' => 10,
//                'ping_time' => '1s',
//                'timeout' => '200s',
//                'pool' => [
//                    'num_workers' => 2,
//                    'max_jobs' => 0,
//                    'allocate_timeout' => '60s',
//                    'destroy_timeout' => 60
//                ]
            ]
        ];
    }

    public function getRequired(): array
    {
        return [
            Server::class
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
