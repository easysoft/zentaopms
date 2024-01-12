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
foreach(array_keys($appendUsers) as $account)
{
    $member = new stdclass();
    $member->account  = $account;
    $member->realname = \zget($users, $account);
    $member->isAppend = '1';

    $members[] = $member;
}

$userItems = array();
foreach($members as $member)
{
    if(!empty($member->isAppend))  continue;

    $userItems[] = array('value' => $member->account, 'text' => $member->realname);
    unset($users[$member->account]);
}

$usersPickerItems = array();
foreach($users as $account => $realname) $usersPickerItems[] = array('value' => $account, 'text' => $realname);

jsVar('userItems', $userItems);
jsVar('usersPickerItems', $usersPickerItems);

dropmenu(set::objectID($objectID));

formBatchPanel
(
    setClass('add-whitelist-panel'),
    set::title($lang->personnel->addWhitelist),
    set::onRenderRow(jsRaw('renderRowData')),
    set::data($members),
    set::headingClass('justify-start'),
    on::change('[name^=account]', 'changeUsers'),
    on::click('.form-batch-row-actions button', 'changeUsers'),
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
            picker
            (
                set::name('dept'),
                set::value($deptID),
                set::items($depts),
                set::required(true),
                set('data-placeholder', $lang->execution->selectDeptTitle),
                on::change('setObjectUsers')
            )
        ),
        div
        (
            setClass('select-object-box ml-4'),
            span
            (
                setClass('flex items-center object-title mr-2'),
                $lang->personnel->copy
            ),
            picker
            (
                set::name('object'),
                set::value($copyID),
                set::items($objects),
                set('data-placeholder', $lang->personnel->selectObjectTips),
                on::change('setObjectUsers')
            )
        )
    ),
    formBatchItem
    (
        set::name('account'),
        set::label($lang->team->account),
        set::control('picker'),
        set::items($users),
        set::width('200px')
    )
);

render();
