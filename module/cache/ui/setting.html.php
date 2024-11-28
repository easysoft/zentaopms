<?php
declare(strict_types=1);
/**
 * The setting view file of cache module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     cache
 * @link        https://www.zentao.net
 */
namespace zin;

$hiddenCache = $config->cache->enable ? '' : ' hidden';
$hiddenApcu  = !$hiddenCache && $config->cache->driver == 'apcu'  ? '' : ' hidden';
$hiddenRedis = !$hiddenCache && $config->cache->driver == 'redis' ? '' : ' hidden';
$canClear    = $config->cache->enable && hasPriv('cache', 'clear');

formPanel
(
    set::actions
    ([
        'submit',
        $canClear ? ['text' => $lang->cache->clear, 'url' => inlink('ajaxClear'), 'class' => 'secondary ajax-submit'] : null,
        'cancel'
    ]),
    on::change('input[name=enable]', 'toggleCache'),
    on::change('input[name=driver]', 'toggleDriver'),
    formGroup
    (
        set::label($lang->cache->status),
        set::required(),
        radioList
        (
            set::name('enable'),
            set::items($lang->cache->statusList),
            set::value($config->cache->enable),
            set::inline(true)
        )
    ),
    formGroup
    (
        setClass('cache' . $hiddenCache),
        set::label($lang->cache->driver),
        set::required(),
        radioList
        (
            setClass('w-1/3'),
            set::name('driver'),
            set::items($lang->cache->driverList),
            set::value($config->cache->driver),
            set::inline(true)
        ),
        span
        (
            setClass('apcu ml-4 mt-1.5' . $hiddenApcu),
            icon('info text-warning mr-2'),
            $lang->cache->apcu->notice
        ),
        span
        (
            setClass('redis ml-4 mt-1.5' . $hiddenRedis),
            icon('info text-warning mr-2'),
            $lang->cache->redis->notice
        )
    ),
    formGroup
    (
        setClass('cache' . $hiddenCache),
        set::label($lang->cache->scope),
        set::required(),
        radioList
        (
            setClass('w-1/3'),
            set::name('scope'),
            set::items($lang->cache->scopeList),
            set::value($config->cache->scope),
            set::inline(true)
        ),
        span
        (
            setClass('ml-4 mt-1.5'),
            icon('info text-warning mr-2'),
            $lang->cache->tips->scope
        )
    ),
    formGroup
    (
        setClass('cache' . $hiddenCache),
        set::label($lang->cache->namespace),
        set::required(),
        input
        (
            setClass('w-1/3'),
            set::name('namespace'),
            set::value($config->cache->namespace ?: $config->db->name)
        ),
        span
        (
            setClass('ml-4 mt-1.5'),
            icon('info text-warning mr-2'),
            $lang->cache->tips->namespace
        )
    ),
    formGroup
    (
        setClass('redis' . $hiddenRedis),
        set::label($lang->cache->redis->host),
        set::required(),
        input
        (
            setClass('w-1/3'),
            set::name('redis[host]'),
            set::value($config->redis->host)
        ),
        span
        (
            setClass('ml-4 mt-1.5'),
            icon('info text-warning mr-2'),
            $lang->cache->redis->tips->host
        )
    ),
    formGroup
    (
        setClass('redis' . $hiddenRedis),
        set::label($lang->cache->redis->port),
        set::required(),
        input
        (
            setClass('w-1/3'),
            set::name('redis[port]'),
            set::value($config->redis->port)
        )
    ),
    formGroup
    (
        setClass('redis' . $hiddenRedis),
        set::label($lang->cache->redis->username),
        input
        (
            setClass('w-1/3'),
            set::name('redis[username]'),
            set::value($config->redis->username)
        )
    ),
    formGroup
    (
        setClass('redis' . $hiddenRedis),
        set::label($lang->cache->redis->password),
        input
        (
            setClass('w-1/3'),
            set::name('redis[password]'),
            set::type('password'),
            set::value($config->redis->password)
        )
    ),
    formGroup
    (
        setClass('redis' . $hiddenRedis),
        set::label($lang->cache->redis->database),
        set::required(),
        input
        (
            setClass('w-1/3'),
            set::type('number'),
            set::min(0),
            set::step(1),
            set::name('redis[database]'),
            set::value($config->redis->database)
        ),
        span
        (
            setClass('ml-4 mt-1.5'),
            icon('info text-warning mr-2'),
            $lang->cache->redis->tips->database
        )
    ),
    formGroup
    (
        setClass('redis' . $hiddenRedis),
        set::label($lang->cache->redis->serializer),
        radioList
        (
            setClass('w-1/3'),
            set::name('redis[serializer]'),
            set::items($lang->cache->redis->serializerList),
            set::value($config->redis->serializer),
            set::inline(true)
        ),
        span
        (
            setClass('ml-4 mt-1.5'),
            icon('info text-warning mr-2'),
            $lang->cache->redis->tips->serializer
        )
    ),
    $config->cache->enable ? formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label($lang->cache->memory),
        div
        (
            setClass('w-1/3'),
            progressBar
            (
                set::percent($rate),
                set::width('100%'),
                set::color('rgb(var(--color-' . ($rate <= 50 ? 'success' : ($rate <= 80 ? 'warning' : 'danger')) . '-500-rgb))')
            )
        ),
        div
        (
            setClass('flex ml-4 gap-4'),
            span($rate . '%'),
            span(sprintf($lang->cache->usedMemory, $total, $used))
        )
    ) : null
);

render();
