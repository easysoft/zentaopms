<?php
declare(strict_types=1);
/**
 * The database view view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

$count = $viewCount;

jsVar('viewNames', $viewNames);
jsVar('dbViewRegenerate', $lang->admin->dbViewRegenerate);
jsVar('dbViewSuccess', $lang->admin->dbViewSuccess);
jsVar('dbViewFail', $lang->admin->dbViewFail);
jsVar('dbViewResult', sprintf($lang->admin->dbViewResult, '%s', '%s'));
jsVar('dbViewFailed', $lang->admin->dbViewFailed);

if(empty($viewNames))
{
    panel
    (
        setClass('m-auto w-2/3'),
        set::title($lang->admin->dbView),
        set::headingClass('border-b'),
        div
        (
            setClass('flex items-center text-success'),
            icon(setClass('mr-2'), 'check-circle'),
            $lang->admin->noNeedUpdate
        )
    );
}
else
{
    panel
    (
        setClass('m-auto w-2/3'),
        set::title($lang->admin->dbView),
        set::headingClass('border-b'),
        to::headingActions
        (
            span(setID('viewProgress'), '0 / ' . $count)
        ),
        div
        (
            setClass('mb-4'),
            sprintf($lang->admin->dbViewTips, $count)
        ),
        div
        (
            setID('viewBox'),
            setClass('mb-4 overflow-y-auto overflow-x-hidden'),
            setStyle(['max-height' => 'calc(100vh - 16rem)'])
        ),
        div
        (
            setClass('center'),
            a
            (
                setID('startUpdate'),
                setClass('btn primary'),
                on::click('regenerateDbViews()'),
                $lang->admin->startUpdate
            )
        )
    );
}

render();
