<?php
declare(strict_types=1);
/**
 * The create branch type view file of repobranchtype module of ZenTaoPMS.
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

$backUrl = $repoID != 0 ? createLink('repobranchtype', 'browse', "repoID=$repoID") : createLink('repobranchtype', 'browse');

// 面包屑（只在有 repo 时显示）
if($repoID != 0)
{
    $module = $app->tab == 'devops' ? 'repo' : $app->tab;
    dropmenu
    (
        set::module($module),
        set::tab($module),
        set::url(createLink($module, 'ajaxGetDropMenu', "objectID=0&module={$app->rawModule}&method={$app->rawMethod}"))
    );
}

formPanel
(
    set::title($lang->repobranchtype->create),
    set::actions(array('submit', array('text' => $lang->cancel, 'url' => $backUrl))),
    formGroup
    (
        setID('name'),
        set::width('1/2'),
        set::label($lang->repobranchtype->name),
        set::name('name'),
        set::required(true)
    ),
    formGroup
    (
        setID('key'),
        set::width('1/2'),
        set::label($lang->repobranchtype->key),
        set::name('key'),
        set::placeholder($lang->repobranchtype->placeholder->key),
        set::required(true)
    ),
    formRow
    (
        formGroup
        (
            setClass('prefixes'),
            set::width('1/2'),
            set::label($lang->repobranchtype->prefixes),
            set::name('prefixes[0]'),
            set::required(true)
        ),
        formGroup
        (
            btnGroup(set::items($firstRowBtns))
        )
    ),
    formGroup
    (
        setID('desc'),
        set::label($lang->repobranchtype->desc),
        set::name('desc'),
        set::control('textarea'),
        set::control(array('type' => 'textarea', 'rows' => 3)),
    )
);

render();
