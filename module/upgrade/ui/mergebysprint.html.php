<?php
declare(strict_types=1);
/**
 * The mergebysprint mode view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

$getMergeData = function($data)
{
    global $lang;
    include_once('createprogram.html.php');

    $checkBoxGroup = array();
    foreach($data->noMergedSprints as $sprintID => $sprint)
    {
        $checkBoxGroup[] = div
        (
            set::class('py-2'),
            div
            (
                set::class('sprintItem px-4'),
                checkbox(set::name("sprints[]"), set::text($sprint->name), set::value($sprint->id), set('data-on', 'change'), set('data-call', 'changeSprints'), set('data-params', 'event'))
            )
        );
    }

    return div
    (
        set::class('flex mt-4'),
        cell
        (
            set::id('source'),
            set::width('1/2'),
            set::class('border p-4 overflow-hidden'),
            div
            (
                set::class('flex'),
                cell
                (
                    set::flex('1'),
                    set::class('item checkbox-primary px-4 overflow-hidden'),
                    checkbox(set::id('checkAllSprints'), set::text($lang->projectCommon), set('data-on', 'change'), set('data-call', 'changeAllSprints'))
                )
            ),
            div
            (
                set::class('mt-4'),
                $checkBoxGroup
            )
        ),
        cell
        (
            set::width('1/2'),
            set::class('border ml-4 p-4'),
            set::id('programBox'), $createProgram($data)
        )
    );
};
