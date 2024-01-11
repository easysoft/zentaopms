<?php
declare(strict_types=1);
/**
 * The batchEdit view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('tab', $app->tab);
jsVar('modules', $modules);
jsVar('products', $products);
jsVar('branchTagOption', $branchTagOption);
jsVar('productBugOptions', $productBugOptions);
jsVar('executionMembers', isset($executionMembers) ? $executionMembers : array());
jsVar('projectMembers', isset($projectMembers) ? $projectMembers : array());
jsVar('productMembers', isset($productMembers) ? $productMembers : array());
jsVar('isBranchProduct', $branchProduct ? 1 : 0);

$visibleFields  = array();
$requiredFields = array();
foreach(explode(',', $config->bug->custom->batchEditFields) as $field)
{
    if($field) $visibleFields[$field] = '';
}
foreach(explode(',', $config->bug->edit->requiredFields) as $field)
{
    if($field)
    {
        $requiredFields[$field] = '';
        if(strpos(",{$config->bug->list->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
    }
}

formBatchPanel
(
    set::mode('edit'),
    set::data(array_values($bugs)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[data-name="resolution"]', 'setDuplicate'),
    /* Field of id. */
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    ),
    /* Field of id index. */
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('50px')
    ),
    /* Field of type. */
    formBatchItem
    (
        set::name('type'),
        set::label($lang->typeAB),
        set::hidden(zget($visibleFields, 'type', true, false)),
        set::control('picker'),
        set::items($lang->bug->typeList),
        set::width('160px'),
        set::required(isset($requiredFields['type'])),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    /* Field of severity. */
    formBatchItem
    (
        set::name('severity'),
        set::label($lang->bug->severity),
        set::hidden(zget($visibleFields, 'severity', true, false)),
        set::control(array('type' => 'severityPicker', 'required' => true)),
        set::items($lang->bug->severityList),
        set::width('80px'),
        set::required(isset($requiredFields['severity']))
    ),
    /* Field of pri. */
    formBatchItem
    (
        set::name('pri'),
        set::label($lang->bug->pri),
        set::hidden(zget($visibleFields, 'pri', true, false)),
        set::control(array('type' => 'priPicker', 'required' => true)),
        set::items($lang->bug->priList),
        set::width('80px'),
        set::required(isset($requiredFields['pri']))
    ),
    /* Field of title. */
    formBatchItem
    (
        set::name('title'),
        set::control('colorInput'),
        set::label($lang->bug->title),
        set::width('240px'),
        set::required(true)
    ),
    /* Field of branch. */
    formBatchItem
    (
        set::name('branch'),
        set::label($lang->bug->branch),
        set::control('picker'),
        set::items(array()),
        set::width('200px'),
        set::required(isset($requiredFields['branch'])),
        set::hidden(!$branchProduct)
    ),
    /* Field of module. */
    formBatchItem
    (
        set::name('module'),
        set::label($lang->bug->module),
        set::control('picker'),
        set::items(array()),
        set::width('200px'),
        set::required(isset($requiredFields['module'])),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    /* Field of module. */
    formBatchItem
    (
        set::name('plan'),
        set::label($lang->bug->plan),
        set::control(array('type' => 'picker', 'required' => false)),
        set::items(array()),
        set::width('200px'),
        set::required(isset($requiredFields['plan'])),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    /* Field of assignedTo. */
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->bug->assignedTo),
        set::control('picker'),
        set::items($users),
        set::width('200px'),
        set::required(isset($requiredFields['assignedTo'])),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    /* Field of deadline. */
    formBatchItem
    (
        set::name('deadline'),
        set::label($lang->bug->deadline),
        set::hidden(zget($visibleFields, 'deadline', true, false)),
        set::control('date'),
        set::width('128px'),
        set::required(isset($requiredFields['deadline']))
    ),
    /* Field of os. */
    formBatchItem
    (
        set::name('os'),
        set::label($lang->bug->os),
        set::hidden(zget($visibleFields, 'os', true, false)),
        set::control('picker'),
        set::items($lang->bug->osList),
        set::multiple(true),
        set::width('200px'),
        set::required(isset($requiredFields['os']))
    ),
    /* Field of browser. */
    formBatchItem
    (
        set::name('browser'),
        set::label($lang->bug->browser),
        set::hidden(zget($visibleFields, 'browser', true, false)),
        set::control('picker'),
        set::items($lang->bug->browserList),
        set::multiple(true),
        set::width('200px'),
        set::required(isset($requiredFields['browser']))
    ),
    /* Field of keywords. */
    formBatchItem
    (
        set::name('keywords'),
        set::label($lang->bug->keywords),
        set::hidden(zget($visibleFields, 'keywords', true, false)),
        set::width('200px'),
        set::required(isset($requiredFields['keywords']))
    ),
    /* Field of resolvedBy. */
    formBatchItem
    (
        set::name('resolvedBy'),
        set::label($lang->bug->resolvedBy),
        set::control('picker'),
        set::items($users),
        set::width('200px'),
        set::required(isset($requiredFields['resolvedBy'])),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    /* Field of resolution. */
    formBatchItem
    (
        set::label($lang->bug->resolution),
        set::width('220px'),
        set::name('resolutionBox'),
        set::control('inputGroup'),
        inputGroup
        (
            picker
            (
                set::name('resolution'),
                set::items($lang->bug->resolutionList),
                set::required(isset($requiredFields['resolution']))
            ),
            picker
            (
                setClass('duplicate-select hidden'),
                set::name('duplicateBug'),
                set::items(array()),
                set::placeholder($lang->bug->placeholder->duplicate),
                set::required(true)
            )
        )
    )
);

render();

