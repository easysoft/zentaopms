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

jsVar('roleGroup', $roleGroup);
jsVar('passwordStrengthList', $lang->user->passwordStrengthList);
h::jsCall('$.getLib', 'md5.js', array('root' => $this->app->getWebRoot() . 'js/'));

formBatchPanel
(
    set::title($lang->user->batchCreate),
    set::customFields(array('list' => $listFields, 'show' => $showFields, 'key' => 'batchCreateFields')),
    on::change('input[name^=role]', 'batchChangeRole'),
    on::change('[data-name="new"]', 'batchToggleNew'),
    on::change('[data-name^=vision]', 'batchChangeVision'),
    on::keyup('[data-name="password"]', 'batchTogglePasswordStrength'),
    on::click('button[type=submit]', 'encryptPassword'),
    to::titleSuffix
    (
        div
        (
            setClass('text-base font-medium'),
            radioList
            (
                on::change('batchChangeType'),
                set::name('type'),
                set::value($type),
                set::inline(true),
                set::items($lang->user->typeList)
            )
        )
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
        set::label(''),
        set::name('type'),
        set::value($type),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('dept'),
        set::label($lang->user->dept),
        set::control('picker'),
        set::width('200px'),
        set::items($depts),
        set::value($deptID ? $deptID : ''),
        set::ditto(true),
        set::hidden(!in_array('dept', $showFields) || $type != 'inside')
    ),
    formBatchItem
    (
        set::label($lang->user->company),
        set::control('inputGroup'),
        set::width('240px'),
        set::name('companyItem'),
        set::hidden($type == 'inside'),
        inputGroup
        (
            set::id('companyBox'),
            picker
            (
                set::name('company'),
                set::items($companies),
            ),
            input
            (
                set::name('newCompany'),
                setClass('hidden')
            ),
            checkbox
            (
                set::id('new'),
                set::name('new'),
                set::value(1),
                set::text($lang->company->create),
                set::rootClass('btn'),
                width('96px')
            )
        )
    ),
    formBatchItem
    (
        set::name('account'),
        set::label($lang->user->account),
        set::control('input'),
        set::width('140px'),
        set::required(true)
    ),
    formBatchItem
    (
        set::name('realname'),
        set::label($lang->user->realname),
        set::control('input'),
        set::width('96px'),
        set::required(true)
    ),
    count($visions) > 1 ? formBatchItem
    (
        set::name('visions'),
        set::label($lang->user->visions),
        set::items($visions),
        set::control(array('type' => 'picker', 'multiple' => true)),
        set::width('240px'),
        set::value($config->vision),
        set::ditto(true),
        set::required(true)
    ) : '',
    formBatchItem
    (
        set::name('role'),
        set::label($lang->user->role),
        set::control('picker'),
        set::width('160px'),
        set::items($lang->user->roleList),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('group'),
        set::label($lang->user->group),
        set::control(array('type' => 'picker', 'multiple' => true)),
        set::items($groupList),
        set::width('200px'),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('email'),
        set::label($lang->user->email),
        set::control('input'),
        set::width('160px'),
        set::hidden(!in_array('email', $showFields))
    ),
    formBatchItem
    (
        set::name('gender'),
        set::label($lang->user->gender),
        set::control('radioListInline'),
        set::items($lang->user->genderList),
        set::value('m'),
        set::width('100px'),
        set::hidden(!in_array('gender', $showFields))
    ),
    formBatchItem
    (
        set::name('password'),
        set::label($lang->user->password),
        set::control
        (
            array
            (
                'type' => 'inputGroup',
                'class' => 'form-control',
                'items' => array(
                    input
                    (
                        set::name('password'),
                        set::placeholder(zget($lang->user->placeholder->passwordStrength, $config->safe->mode, ''))
                    ),
                    span
                    (
                        setClass('input-group-addon passwordStrength hidden')
                    )
                )
            )
        ),
        set::width('160px'),
        set::ditto(true),
        set::required(true)
    ),
    formBatchItem
    (
        set::name('commiter'),
        set::label($lang->user->commiter),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('commiter', $showFields) || $type != 'inside')
    ),
    formBatchItem
    (
        set::name('join'),
        set::label($lang->user->join),
        set::control('date'),
        set::width('120px'),
        set::hidden(!in_array('join', $showFields) || $type != 'inside')
    ),
    formBatchItem
    (
        set::name('skype'),
        set::label($lang->user->skype),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('skype', $showFields))
    ),
    formBatchItem
    (
        set::name('qq'),
        set::label($lang->user->qq),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('qq', $showFields))
    ),
    formBatchItem
    (
        set::name('dingding'),
        set::label($lang->user->dingding),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('dingding', $showFields))
    ),
    formBatchItem
    (
        set::name('weixin'),
        set::label($lang->user->weixin),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('weixin', $showFields))
    ),
    formBatchItem
    (
        set::name('mobile'),
        set::label($lang->user->mobile),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('mobile', $showFields))
    ),
    formBatchItem
    (
        set::name('slack'),
        set::label($lang->user->slack),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('slack', $showFields))
    ),
    formBatchItem
    (
        set::name('whatsapp'),
        set::label($lang->user->whatsapp),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('whatsapp', $showFields))
    ),
    formBatchItem
    (
        set::name('phone'),
        set::label($lang->user->phone),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('phone', $showFields))
    ),
    formBatchItem
    (
        set::name('address'),
        set::label($lang->user->address),
        set::control('input'),
        set::width('160px'),
        set::hidden(!in_array('address', $showFields))
    ),
    formBatchItem
    (
        set::name('zipcode'),
        set::label($lang->user->zipcode),
        set::control('input'),
        set::width('120px'),
        set::hidden(!in_array('zipcode', $showFields))
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
            set::required(true)
        )
    )
);

formHidden('verifyRand', $rand);

render();
