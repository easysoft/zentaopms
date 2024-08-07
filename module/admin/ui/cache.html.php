<?php
declare(strict_types=1);
/**
 * The cache view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::actions
    ([
        'submit',
        $config->cache->dao->enable ? ['text' => $lang->admin->clearCache, 'url' => inlink('ajaxClearCache'), 'class' => 'secondary ajax-submit'] : null,
        'cancel'
    ]),
    formGroup
    (
        set::label($lang->admin->daoCache),
        radioList
        (
            set::name('dao[enable]'),
            set::items($lang->admin->cacheStatusList),
            set::value($config->cache->dao->enable),
            set::inline(true)
        ),
        span(setClass('ml-4 mt-1.5'), icon('info text-warning mr-2'), $lang->admin->apcuNotice)
    ),
    helper::isAPCuEnabled() && $this->config->cache->dao->driver == 'Apcu' ? formRow
    (
        formGroup
        (
            setClass('w-1/2'),
            setStyle(array('align-items' => 'center')),
            set::label($lang->admin->memory),
            progressBar
            (
                set::percent($rate),
                set::width('100%'),
                set::color('rgb(var(--color-' . ($rate <= 50 ? 'success' : ($rate <= 80 ? 'warning' : 'danger')) . '-500-rgb))')
            )
        ),
        formGroup
        (
            setClass('w-1/2 ml-4 gap-4'),
            setStyle(array('align-items' => 'center')),
            span($rate . '%'),
            span(sprintf($lang->admin->usedMemory, $total, $used))
        )
    ) : null
);

render();
