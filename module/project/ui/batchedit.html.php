<?php
declare(strict_types=1);
/**
 * The batchedit view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     project 
 * @link        https://www.zentao.net
 */
namespace zin;

$setCode = (isset($config->setCode) and $config->setCode == 1);
$aclList = (empty($globalDisableProgram) and $project->parent) ? $lang->program->subAcls : $lang->project->acls;

formBatchPanel
(
    set::mode('edit'),
    set::data(array_values($projects)),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true),
    ),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('38px'),
    ),
    formBatchItem
    (
        set::name('parent'),
        set::label($lang->project->program),
        set::control('picker'),
        set::items($programs),
        set::width('128px'),
    ),
    formBatchItem
    (
        set::name('name'),
        set::label($lang->project->name),
        set::width('240px'),
    ),
    $setCode ? formBatchItem
    (
        set::name('code'),
        set::label($lang->project->code),
        set::width('136px'),
    ) : null,
    formBatchItem
    (
        set::name('PM'),
        set::label($lang->project->PM),
        set::control('select'),
        set::ditto(true),
        set::defaultDitto('off'),
        set::items($PMUsers),
        set::width('136px'),
    ),
    formBatchItem
    (
        set::name('PO'),
        set::label($lang->project->PO),
        set::control('select'),
        set::ditto(true),
        set::defaultDitto('off'),
        set::items($poUsers),
        set::width('80px'),
        set::hidden(true),
    ),
    formBatchItem
    (
        set::name('begin'),
        set::label($lang->project->begin),
        set::control('date'),
        set::width('76px'),
    ),
    formBatchItem
    (
        set::name('end'),
        set::label($lang->project->end),
        set::control('date'),
        set::width('76px'),
    ),
    formBatchItem
    (
        set::name('acl'),
        set::label($lang->project->acl),
        set::control('select'),
        set::items($aclList),
        set::width('128px'),
    ),
);

render();
