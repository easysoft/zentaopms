<?php

namespace PHPSTORM_META {

    registerArgumentsSet('goridge_rpc_methods_jobs',
        'jobs.Push',
        'jobs.PushBatch',
        'jobs.Pause',
        'jobs.Resume',
        'jobs.List',
        'jobs.Declare',
        'jobs.Destroy',
        'jobs.Stat'
    );

    expectedArguments(\Spiral\Goridge\RPC\RPCInterface::call(), 0,
        argumentsSet('goridge_rpc_methods_jobs'));
    expectedArguments(\Spiral\Goridge\RPC\RPC::call(), 0,
        argumentsSet('goridge_rpc_methods_jobs'));
}
