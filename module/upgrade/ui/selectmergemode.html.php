<?php
declare(strict_types=1);
/**
 * The selectmergemode view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

$mergeModes = array();
foreach($lang->upgrade->mergeModes as $mode => $label)
{
    $tipLang = 'merge' . ucfirst($mode) . 'Tip';
    $mergeModes[] = div
    (
        setClass('radio-primary'),
        h::input
        (
            set::type('radio'),
            setID("projectType_{$mode}"),
            set::name('projectType'),
            set::value($mode),
            $mode == 'project' ? set('checked', 'checked') : ''
        ),
        h::label
        (
            set('for', "projectType_{$mode}"),
            $label,
            span
            (
                setClass('flex text-gray mt-1'),
                $lang->upgrade->{$tipLang} . ($systemMode == 'ALM' && $mode != 'manually' ? $lang->upgrade->createProgramTip : '')
            )
        )
    );
}

set::zui(true);

div
(
    setID('main'),
    div
    (
        setID('mainContent'),
        formPanel
        (
            setClass('bg-canvas'),
            width('1000px'),
            set::title($lang->upgrade->selectMergeMode),
            set::actions(array()),
            set::target('_self'),
            cell
            (
                setClass('flex flex-wrap'),
                col
                (
                    width('120px'),
                    setClass('text-right pt-1.5'),
                    $lang->upgrade->mergeMode
                ),
                col
                (
                    width('calc(100% - 120px)'),
                    div
                    (
                        setClass('check-list gap-4'),
                        $mergeModes
                    )
                )
            ),
            cell
            (
                setClass('flex justify-center'),
                btn
                (
                    setClass('px-6 mx-3'),
                    set::url(createLink('upgrade', 'to18guide', "fromVersion=$fromVersion")),
                    $lang->upgrade->back
                ),
                btn
                (
                    setClass('px-6 mx-3'),
                    set::type('primary'),
                    set::btnType('submit'),
                    $lang->upgrade->next
                )
            )
        )
    )
);

render('pagebase');
