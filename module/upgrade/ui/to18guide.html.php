<?php
declare(strict_types=1);
/**
 * The to18guide view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$trs = array();
foreach($disabledFeatures as $feature)
{
    if(is_array($feature) && empty($disabledScrumFeatures)) continue;
    $trs[] = h::tr
    (
        h::td
        (
            is_array($feature) && !empty($disabledScrumFeatures) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $disabledScrumFeatures)) : $this->lang->custom->features[$feature],
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
    if($feature == 'scrumDetail' && empty($enabledScrumFeatures)) continue;

    $trs[] = h::tr
    (
        h::td
        (
            ($feature == 'scrumDetail' && !empty($enabledScrumFeatures)) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $enabledScrumFeatures)) : $this->lang->custom->features[$feature]
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
    div
    (
        setID('mainContent'),
        formPanel
        (
            setClass('bg-canvas'),
            set::title($lang->install->selectMode),
            set::titleClass('text-xl'),
            set::actions(array()),
            set::target('_self'),
            to::heading
            (
                span
                (
                    setClass('text-gray'),
                    icon
                    (
                        setClass('text-warning px-1'),
                        'help'
                    ),
                    $this->lang->upgrade->remarkDesc
                )
            ),
            h::table
            (
                setClass('table bordered'),
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
                            $this->lang->custom->modeList['light']
                        ),
                        h::td
                        (
                            width('1/3'),
                            $this->lang->custom->modeList['ALM']
                        )
                    )
                ),
                h::tbody
                (
                    h::tr
                    (
                        h::td
                        (
                            $lang->custom->usage
                        ),
                        h::td
                        (
                            $this->lang->custom->modeIntroductionList['light']
                        ),
                        h::td
                        (
                            $this->lang->custom->modeIntroductionList['ALM']
                        )
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
                                on::click('selectUsage'),
                                setID('light'),
                                set::btnType('submit'),
                                setClass('px-4'),
                                $lang->custom->useLight
                            )
                        ),
                        h::td
                        (
                            btn
                            (
                                on::click('selectUsage'),
                                setID('ALM'),
                                set::btnType('submit'),
                                setClass('px-4'),
                                $lang->custom->useALM
                            )
                        )
                    )
                )
            ),
            input
            (
                set::name('mode'),
                set::type('hidden')
            )
        )
    )
);

render('pagebase');
