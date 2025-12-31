<?php
declare(strict_types=1);
/**
 * The edit branch type view file of repobranchtype module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      DaoGang Li <lidaogang@chandao.com>
 * @package     repobranchtype
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('maxPrefixesTip', $lang->repobranchtype->tips->maxPrefixes);
jsVar('minPrefixesTip', $lang->repobranchtype->tips->minPrefixes);

/* 第一行只有添加按钮。 */
$firstRowBtns = array(
    array('class' => 'btn btn-link', 'icon' => 'plus', 'onclick' => 'addPrefixItem(this)')
);

/* 其他行有添加和删除按钮。 */
$prefixBtns = array(
    array('class' => 'btn btn-link', 'icon' => 'plus', 'onclick' => 'addPrefixItem(this)'),
    array('class' => 'btn btn-link', 'icon' => 'trash', 'onclick' => 'deletePrefixItem(this)')
);

$backUrl = $repoID != 0 ? createLink('repobranchtype', 'browse', "repoID=$repoID") : createLink('repobranchtype', 'browse');

// 构建前缀表单行
$prefixRows = array();
foreach($prefixes as $index => $prefix)
{
    $prefixRows[] = formRow
    (
        formGroup
        (
            setClass('prefixes'),
            set::width('1/2'),
            set::label($index == 0 ? $lang->repobranchtype->prefixes : ''),
            set::name("prefixes[{$index}]"),
            set::value($prefix),
            set::required($index == 0)
        ),
        formGroup
        (
            btnGroup(set::items($index == 0 ? $firstRowBtns : $prefixBtns))
        )
    );
}

formPanel
(
    set::title($lang->repobranchtype->edit),
    set::actions(array('submit', array('text' => $lang->cancel, 'url' => $backUrl))),
    formGroup
    (
        setID('name'),
        set::width('1/2'),
        set::label($lang->repobranchtype->name),
        set::name('name'),
        set::value($branchType->name),
        set::required(true)
    ),
    formGroup
    (
        setID('key'),
        set::width('1/2'),
        set::label($lang->repobranchtype->key),
        set::name('key'),
        set::value($branchType->key),
        set::placeholder($lang->repobranchtype->placeholder->key),
        set::disabled(true),
        set::required(true)
    ),
    $prefixRows,
    formGroup
    (
        setID('desc'),
        set::label($lang->repobranchtype->desc),
        set::name('desc'),
        set::value($branchType->desc ?? ''),
        set::control('textarea'),
        set::control(array('type' => 'textarea', 'rows' => 3)),
    )
);

render();
