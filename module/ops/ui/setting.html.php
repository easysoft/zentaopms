<?php
declare(strict_types=1);
/**
 * The setting view file of ops module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     ops
 * @link        https://www.zentao.net
 */

namespace zin;

$template = <<<EOT
<div class="form-row">
    <div class="form-group no-label grow-0" style="width: 150px;">
        <input class="form-control" type="text" autocomplete="off" name="keys[]" value="">
        <input type="hidden" name="systems[]" value="0">
    </div>
    <div class="form-group no-label grow-0 w-1/3 ml-3">
        <input class="form-control" type="text" autocomplete="off" name="values[]" value="" id="values">
    </div>
    <div class="ops-actions">
        <i class="icon icon-plus ml-2"></i>
        <i class="icon icon-close ml-2"></i>
    </div>
</div>
EOT;
jsVar('template', $template);
$formRows = array();
foreach($lang->$module->$fieldList as $key => $value)
{
    $formRows[] = formRow
    (
        formGroup
        (
            set::width('150px'),
            set::label($key === '' ? 'NULL' : $key),
            set::name('keys[]'),
            set::control('hidden'),
            set::value($key),
        ),
        input
        (
            set::name('systems[]'),
            set::type('hidden'),
            set::value(0),
        ),
        formGroup
        (
            setClass('ml-3'),
            set::width('1/3'),
            set::name('values[]'),
            set::value($value),
            set::readonly(empty($key) ? true : false)
        ),
        div
        (
            setClass('ops-actions'),
            icon('plus', setClass('ml-2')),
            icon('close', setClass('ml-2')),
        )
    );
}

$appliedTo = array($this->app->getClientLang() => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
$formRows[] = formRow
(
    formGroup
    (
        set::width('1/3'),
        set::name('lang'),
        set::control('radioList'),
        set::value(str_replace('_', '-', $currentLang)),
        set::inline(true),
        set::items($appliedTo),
    )
);

$hasSideBar = !empty($lang->{$module}->osNameList) && array_key_exists($field, $lang->{$module}->osNameList);
$actions    = array('submit');
if(common::hasPriv('custom', 'restore')) $actions[] = array('class' => 'ajax-submit', 'text' => $lang->custom->restore, 'data-confirm' => $lang->custom->confirmRestore, 'url' => $this->createLink('custom', 'restore', "module=$module&field=$fieldList&confirm=yes"));
formPanel
(
    setID('opsForm'),
    set::size('md'),
    $hasSideBar ? setClass('ops-ml-0') : null,
    set::title($lang->$module->common . '  >   ' . $lang->$module->$field),
    set::actions($actions),
    formRow
    (
        setClass('ops-header'),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->custom->key),
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->custom->value),
        ),
    ),
    $formRows,
);

if($hasSideBar)
{
    $osListWg = array();
    foreach($lang->{$module}->osNameList as $key => $value)
    {
        $osListWg[] = a(set::href(inLink('os', "lang=$currentLang&field=$key")), setClass($field == $key ? 'active' : ''), $value);
    }

    sidebar
    (
        div
        (
            setClass('flex osOpsList col p-4 bg-white'),
            $osListWg
        )
    );
}
