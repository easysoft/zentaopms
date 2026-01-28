<?php
declare(strict_types=1);
/**
 * The step4 view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

if(isset($error))
{
    h::js("zui.Modal.alert({size: '480', message: '{$error}'}).then((res) => {openUrl('" . inlink('step3') . "')});");
    render('pagebase');
    return;
}

$trs = array();
foreach($disabledFeatures as $feature)
{
    if(is_array($feature) && empty($disabledProjectFeatures)) continue;
    $trs[] = h::tr
    (
        h::td
        (
            setClass('text-left'),
            is_array($feature) && !empty($disabledProjectFeatures) ? implode($lang->comma, $disabledProjectFeatures) : $lang->custom->features[$feature]
        ),
        h::td
        (
            icon
            (
                setClass('text-danger font-bold'),
                'ban-circle'
            )
        ),
        h::td
        (
            icon
            (
                setClass('text-success font-bold'),
                'check'
            )
        )
    );
}
foreach($config->custom->allFeatures as $feature)
{
    if(in_array($feature, $disabledFeatures)) continue;
    if($feature == 'projectDetail' && empty($enabledProjectFeatures)) continue;

    $trs[] = h::tr
    (
        h::td
        (
            setClass('text-left'),
            ($feature == 'projectDetail' && !empty($enabledProjectFeatures)) ? implode($lang->comma, $enabledProjectFeatures) : $lang->custom->features[$feature]
        ),
        h::td
        (
            icon
            (
                setClass('text-success font-bold'),
                'check'
            )
        ),
        h::td
        (
            icon
            (
                setClass('text-success font-bold'),
                'check'
            )
        )
    );
}

div
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('px-1 mt-2 w-full max-w-7xl'),
        formPanel
        (
            setClass('bg-canvas m-auto pb-8'),
            set::title($lang->install->selectMode),
            set::titleClass('text-xl'),
            set::actions(array()),
            on::click('.selectUsageBtn', 'selectUsage'),
            to::heading
            (
                setClass('justify-start gap-1'),
                span
                (
                    setClass('text-gray'),
                    icon
                    (
                        setClass('text-warning px-1'),
                        'help'
                    ),
                    $lang->upgrade->remarkDesc
                )
            ),
            h::table
            (
                setClass('table bordered text-center'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            width('1/3'),
                            $lang->custom->mode
                        ),
                        h::td
                        (
                            width('1/3'),
                            $lang->custom->modeList['light']
                        ),
                        h::td
                        (
                            width('1/3'),
                            $lang->custom->modeList['ALM']
                        )
                    )
                ),
                h::tbody
                (
                    h::tr
                    (
                        h::td(setClass('text-left'), $lang->custom->usage),
                        h::td($lang->custom->modeIntroductionList['light']),
                        h::td($lang->custom->modeIntroductionList['ALM']),
                    ),
                    $trs,
                    h::tr
                    (
                        h::td
                        (
                            setClass('select-usage font-bold'),
                            $lang->custom->selectUsage
                        ),
                        h::td
                        (
                            btn
                            (
                                set::id('light'),
                                set::btnType('submit'),
                                setClass('px-4 selectUsageBtn'),
                                $lang->custom->useLight
                            )
                        ),
                        h::td
                        (
                            btn
                            (
                                set::id('ALM'),
                                set::btnType('submit'),
                                setClass('px-4 selectUsageBtn'),
                                $lang->custom->useALM
                            )
                        )
                    )
                )
            ),
            contactUs(),
            input
            (
                set::name('mode'),
                set::type('hidden')
            )
        )
    )
);

render('pagebase');
