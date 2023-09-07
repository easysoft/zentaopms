<?php
declare(strict_types=1);
/**
 * The addwhitelist view file of personnel module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     personnel
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('objectID', $objectID);
jsVar('objectType', $objectType);
jsVar('module', $module);
jsVar('moduleMethod', 'addWhitelist');

$members = !empty($whitelist) ? array_values($whitelist) : array();
foreach($appendUsers as $account => $realname)
{
    $member = new stdclass();
    $member->account  = $account;
    $member->realname = $realname;
    $member->isAppend = '1';
    $members[]       = $member;
}

dropmenu(set::objectID($objectID));

formBatchPanel
(
    setClass('add-whitelist-panel'),
    set::title($lang->personnel->addWhitelist),
    set::onRenderRow(jsRaw('renderRowData')),
    set::data($members),
    set::headingClass('justify-start'),
    to::heading
    (
        div
        (
            setClass('select-dept-box ml-4'),
            span
            (
                set::className('flex items-center dept-title mr-2'),
                $lang->execution->selectDept
            ),
            select
            (
                set::id('dept'),
                set::name('dept'),
                set::value($deptID),
                set::items($depts),
                set('data-placeholder', $lang->execution->selectDeptTitle),
                on::change('setObjectUsers'),
            ),
        ),
        div
        (
            setClass('select-object-box ml-4'),
            span
            (
                setClass('flex items-center object-title mr-2'),
                $lang->personnel->copy
            ),
            select
            (
                set::id('object'),
                set::name('object'),
                set::value($copyID),
                set::items($objects),
                set('data-placeholder', $lang->personnel->selectObjectTips),
                on::change('setObjectUsers'),
            ),
        ),
    ),
    formBatchItem
    (
        set::name('account'),
        set::label($lang->team->account),
        set::control('select'),
        set::items($users),
        set::width('200px'),
    ),
);

render();
