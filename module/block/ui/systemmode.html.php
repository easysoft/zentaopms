<?php
declare(strict_types=1);
/**
* The system mode view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

function printSystemMode()
{
    global $lang, $config, $app;

    $modes = array();
    $usedMode = zget($config->global, 'mode', 'light');
    foreach($lang->block->customModes as $mode => $modeName)
    {
        $modes[] = cell
        (
            set('width', '50%'),
            set('class', 'flex-1 block mr-4 ' . ($usedMode == $mode ? 'active' : 'state')),
            $usedMode != $mode && $mode == 'light' && !empty($config->programs) ? modalTrigger
            (
                div
                (
                    set('class', 'w-full'),
                    img
                    (
                        set('class', 'p-2'),
                        set('src', $config->webRoot . "theme/default/images/guide/{$mode}_" . (common::checkNotCN() ? 'en' : 'cn') . ".png")
                    ),
                    div
                    (
                        set('class', 'px-4 pb-2'),
                        div(set('class', 'pb-2'), span(set('class', 'font-bold'), $modeName)),
                        span(set('class', 'text-sm text-gray'), $lang->block->customModeTip->{$mode})
                    )
                ),
                set('size', '550'),
                modal
                (
                    set::title($lang->custom->selectDefaultProgram),
                    form
                    (
                        set::actions(array('submit')),
                        set::url(helper::createLink('custom', 'mode')),
                        div(set('class', 'secondary-pale p-4'), span($lang->custom->selectProgramTips)),
                        formGroup
                        (
                            set('class', 'hidden'),
                            set::name('mode'),
                            set::value('light'),
                            set::control(array('type' => 'input'))
                        ),
                        formGroup
                        (
                            set::value($config->programID),
                            set::label($lang->custom->defaultProgram),
                            picker
                            (
                                set::name('program'),
                                set::items($config->programs),
                                set::required(true),
                            )
                        )
                    )
                )
            ) : div
            (
                set('class', 'w-full mode-block'),
                set('data-mode', $mode),
                img
                (
                    set('class', 'p-2'),
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
