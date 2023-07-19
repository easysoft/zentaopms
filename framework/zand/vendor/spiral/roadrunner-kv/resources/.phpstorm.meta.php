<?php

namespace PHPSTORM_META {

    //
    // RPC Methods
    //

    registerArgumentsSet('goridge_rpc_methods_kv',
        'kv.Has',
        'kv.Set',
        'kv.MGet',
        'kv.MExpire',
        'kv.TTL',
        'kv.Delete',
        'kv.Clear',
    );

    expectedArguments(\Spiral\Goridge\RPC\RPCInterface::call(), 0,
        argumentsSet('goridge_rpc_methods_kv'));
    expectedArguments(\Spiral\Goridge\RPC\RPC::call(), 0,
        argumentsSet('goridge_rpc_methods_kv'));
}
