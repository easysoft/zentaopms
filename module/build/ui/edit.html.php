<?php
declare(strict_types=1);
/**
 * The edit view file of build module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     build
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('projectID', $build->project);
jsVar('executionID', $build->execution);
jsVar('build', $build);
jsVar('builds', $build->builds);
jsVar('buildID', $build->id);
jsVar('multipleSelect', $lang->build->placeholder->multipleSelect);
$productRow = '';
$testtaskID = $testtask ? $testtask->id : 0;
if(!$hidden)
{
    $productRow = formRow(
        formGroup
        (
            set::width('1/2'),
            set::name('product'),
            set::label($lang->build->product),
            set::value($build->product),
            set::items($products),
            set::required(true),
            set::disabled($build->stories || $build->bugs || $testtaskID),
            on::change('loadBranches')
        ),
        $build->stories || $build->bugs || $testtaskID ? formGroup
        (
            set::width('1/2'),
            setClass('items-center pl-4'),
            $lang->build->notice->changeProduct
        ) : ''
    );
}

$executionRow = '';
$buildRow     = '';
if(!$build->execution)
{
    $buildRow = formRow(
        formGroup
        (
            set::width('1/2'),
            set::label($lang->build->builds),
            picker
            (
                set::name('builds[]'),
                set::items($builds),
                set::value($build->builds),
                set::disabled(!empty($testtaskID)),
                set::placeholder($lang->build->placeholder->multipleSelect),
                set::multiple(true)
            )
        ),
        formGroup
        (
            set::width('1/2'),
            setClass('items-center pl-4'),
            $testtaskID ? $lang->build->notice->changeBuilds : $lang->build->notice->autoRelation
        )
    );
}
elseif(!empty($multipleProject))
{
    $executionRow = formRow(
        formGroup
        (
            set::width('1/2'),
            set::name('execution'),
            set::label($executionType ? $lang->build->executionAB : $lang->build->execution),
            set::value($build->execution),
            set::items($executions),
            set::disabled(!empty($testtaskID)),
            set::required(true)
        ),
        $testtaskID ? formGroup(
            set::width('1/2'),
            setClass('items-center pl-4'),
            $lang->build->notice->changeExecution
        ) : ''
    );
}

if(empty($product)) $product = new stdclass();
$productType     = zget($product, 'type', 'normal');
$productBranches = zget($product, 'branches', array());

formPanel
(
    set::title($lang->build->edit),
    $productRow,
    formRow
    (
        setClass(isset($product->type) && $product->type != 'normal' && !empty($build->execution) ? '' : 'hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->product->branchName[$productType]),
            picker
            (
                set::name('branch[]'),
                set::value($build->branch),
                set::items($branchTagOption),
                set::multiple(true)
            )
        )
    ),
    !$build->execution ? $buildRow : $executionRow,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->build->nameAB),
            set::value($build->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('builder'),
            set::label($lang->build->builder),
            set::value($build->builder),
            set::items($users)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('date'),
            set::label($lang->build->date),
            set::control('date'),
            set::value($build->date)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('scmPath'),
            set::label($lang->build->scmPath),
            set::placeholder($lang->build->placeholder->scmPath),
            set::value($build->scmPath)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('filePath'),
            set::label($lang->build->filePath),
            set::placeholder($lang->build->placeholder->filePath),
            set::value($build->filePath)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->build->files),
            upload()
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->build->desc),
            editor
            (
                set::name('desc'),
                html($build->desc)
            )
        )
    )
);

/* ====== Render page ====== */
render();
