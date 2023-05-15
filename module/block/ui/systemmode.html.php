<?php
declare(strict_types=1);
/**
* The system mode view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

function printSystemMode()
{
    global $lang, $config;

    $modes = array();
    $usedMode = zget($config->global, 'mode', 'light');
    foreach($lang->block->customModes as $mode => $modeName)
    {
        $modes[] = cell
        (
            set('class', 'flex-1 block mr-4 ' . ($usedMode == $mode ? 'active' : '')),
            div
            (
                set('class', 'w-full state'),
                img
                (
                    set('class', 'p-4'),
                    set('src', $config->webRoot . "theme/default/images/guide/{$mode}_" . (common::checkNotCN() ? 'en' : 'cn') . ".png")
                ),
                div
                (
                    set('class', 'px-4 pb-2'),
                    div(set('class', 'pb-2'), span(set('class', 'font-bold'), $modeName)),
                    span(set('class', 'text-sm text-gray'), $lang->block->customModeTip->{$mode})
                )
            )
        );
    }

    return div
    (
        set('class', 'mode-switch p-4'),
        div
        (
            span(set('class', 'py-2'), $lang->block->customModeTip->common),
            div
            (
                set('class','flex mt-2'),
                $modes
            )
        )
    );
}
