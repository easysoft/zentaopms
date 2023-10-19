<?php
declare(strict_types=1);
/**
 * The batchCreate view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('batchCreateCount', $config->user->batchCreate);

$visibleFields  = array();
foreach(explode(',', $showFields) as $field)
{
    if(strpos(",{$config->user->availableBatchCreateFields},", ",{$field},") === false) continue;
    if($field) $visibleFields[$field] = '';
}

foreach(explode(',', $config->user->create->requiredFields) as $field)
{
    if($field && strpos(",{$config->user->availableBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
}

formBatchPanel
(
    set::headingClass('user-batchcreate-heading'),
    set::title($lang->user->batchCreate),
    on::change('[data-name="new"]', 'toggleNew'),
    on::keyup('[data-name="password"]', 'togglePasswordStrength'),
    to::headingActions
    (
        radioList
        (
            on::change('changeType'),
            set::name('type'),
            set::value('inside'),
            set::inline(true),
            set::items($lang->user->typeList),
        ),
    ),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->user->abbr->id),
        set::control('index'),
        set::width('38px'),
    ),
    formBatchItem
    (
        set::name('dept'),
        set::label($lang->user->dept),
        set::control('select'),
        set::width('200px'),
        set::items($depts),
        set::value($deptID),
        set::ditto(true),
        set::hidden(zget($visibleFields, 'dept', true, false)),
    ),
    formBatchItem
    (
        set::label($lang->user->company),
        set::control('inputGroup'),
        set::width('240px'),
        set::name('companyItem'),
        set::hidden(true),
        inputGroup
        (
            set::id('companyBox'),
            select
            (
                set::name('company'),
                set::items($companies),
                set::value(''),
            ),
            input
            (
                set::name('newCompany'),
                set::value(''),
                setClass('hidden'),
            ),
            checkbox
            (
                set::id('new'),
                set::name('new'),
                set::value(0),
                set::text($lang->company->create),
                set::rootClass('btn'),
                width('96px'),
            ),
        ),
    ),
    formBatchItem
    (
        set::name('account'),
        set::label($lang->user->account),
        set::control('input'),
        set::width('160px'),
    ),
    formBatchItem
    (
        set::name('realname'),
        set::label($lang->user->realname),
        set::control('input'),
        set::value(''),
        set::width('96px'),
    ),
    count($visionList) > 1 ? formBatchItem
    (
        set::name('visions'),
        set::label($lang->user->visions),
        set::control(array('type' => 'select', 'items' => $visionList, 'multiple' => true)),
        set::width('200px'),
        set::value(isset($visionList[$this->config->vision]) ? $this->config->vision : key($visionList)),
        set::ditto(true),
    ) : '',
    formBatchItem
    (
        set::name('role'),
        set::label($lang->user->role),
        set::control('select'),
        set::width('200px'),
        set::items($lang->user->roleList),
        set::value(''),
        set::ditto(true),
    ),
    formBatchItem
    (
        set::name('group'),
        set::label($lang->user->group),
        set::control(array('type' => 'select', 'items' => $groupList, 'multiple' => true)),
        set::width('200px'),
        set::value(''),
        set::ditto(true),
    ),
    formBatchItem
    (
        set::name('email'),
        set::label($lang->user->email),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'email', true, false)),
    ),
    formBatchItem
    (
        set::name('gender'),
        set::label($lang->user->gender),
        set::control('radioListInline'),
        set::items($lang->user->genderList),
        set::value('m'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'gender', true, false)),
    ),
    formBatchItem
    (
        set::name('password'),
        set::label($lang->user->password),
        set::control('inputGroup'),
        inputGroup
        (
            input
            (
                set::name('password'),
                set::value(''),
                set::placeholder(zget($lang->user->placeholder->passwordStrength, $config->safe->mode, '')),
            ),
            span
            (
                setClass('input-group-addon passwordStrength hidden'),
            ),
        ),
        set::width('200px'),
    ),
    formBatchItem
    (
        set::name('commiter'),
        set::label($lang->user->commiter),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'commiter', true, false)),
    ),
    formBatchItem
    (
        set::name('join'),
        set::label($lang->user->join),
        set::control('date'),
        set::width('160px'),
    ),
    formBatchItem
    (
        set::name('skype'),
        set::label($lang->user->skype),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'skype', true, false)),
    ),
    formBatchItem
    (
        set::name('qq'),
        set::label($lang->user->qq),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'qq', true, false)),
    ),
    formBatchItem
    (
        set::name('dingding'),
        set::label($lang->user->dingding),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'dingding', true, false)),
    ),
    formBatchItem
    (
        set::name('weixin'),
        set::label($lang->user->weixin),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'weixin', true, false)),
    ),
    formBatchItem
    (
        set::name('mobile'),
        set::label($lang->user->mobile),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'mobile', true, false)),
    ),
    formBatchItem
    (
        set::name('slack'),
        set::label($lang->user->slack),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'slack', true, false)),
    ),
    formBatchItem
    (
        set::name('whatsapp'),
        set::label($lang->user->whatsapp),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'whatsapp', true, false)),
    ),
    formBatchItem
    (
        set::name('phone'),
        set::label($lang->user->phone),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'phone', true, false)),
    ),
    formBatchItem
    (
        set::name('address'),
        set::label($lang->user->address),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'address', true, false)),
    ),
    formBatchItem
    (
        set::name('zipcode'),
        set::label($lang->user->zipcode),
        set::control('input'),
        set::width('160px'),
        set::hidden(zget($visibleFields, 'zipcode', true, false)),
    ),
    div
    (
        setClass('form-grid'),
        formGroup
        (
            setClass('flex verify-box'),
            set::width('400px'),
            set::label($lang->user->verifyPassword),
            set::labelClass('w-10 mr-2'),
            set::control('password'),
            set::name('verifyPassword'),
            set::value(''),
            set::required(true),
        ),
    ),
    formGroup
    (
        setClass('hidden'),
        set::name('verifyRand'),
        set::value($rand),
    ),
    formGroup
    (
        setClass('hidden'),
        set::name('userType'),
        set::value('inside'),
    ),
);

render();

