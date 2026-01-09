<?php
declare(strict_types=1);
/**
 * The import branch type view file of repobranchtype module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      DaoGang Li <lidaogang@chandao.com>
 * @package     repobranchtype
 * @link        https://www.zentao.net
 */
namespace zin;

// 面包屑（只在有 repo 时显示）
if($repo)
{
    $module = $app->tab == 'devops' ? 'repo' : $app->tab;
    dropmenu
    (
        set::module($module),
        set::tab($module),
        set::url(createLink($module, 'ajaxGetDropMenu', "objectID=0&module={$app->rawModule}&method={$app->rawMethod}"))
    );
}

// 定义导入列表的字段（不包含 actions）
$cols = array();
$cols['id']              = $config->repobranchtype->dtable->fieldList['id'];
$cols['id']['type']      = 'checkID';
$cols['name']            = $config->repobranchtype->dtable->fieldList['name'];
$cols['prefixesDisplay'] = $config->repobranchtype->dtable->fieldList['prefixesDisplay'];
$cols['desc']            = $config->repobranchtype->dtable->fieldList['desc'];

// 初始化表格数据
$data = initTableData($branchTypeList, $cols);

// 排序链接参数
$urlParams = array(
    'repoID'     => $repoID,
    'orderBy'    => '{name}_{sortType}',
    'recTotal'   => isset($pager->recTotal) ? $pager->recTotal : 0,
    'recPerPage' => isset($pager->recPerPage) ? $pager->recPerPage : 100,
    'pageID'     => isset($pager->pageID) ? $pager->pageID : 1
);

dtable
(
    setID('importBranchTypeList'),
    set::cols($cols),
    set::data(array_values($data)),
    set::orderBy($orderBy),
    set::sortLink(createLink('repobranchtype', 'import', $urlParams)),
    set::checkable(true),
    set::onRenderCell(jsRaw('window.renderBranchTypeCell')),
    set::footToolbar(array('items' => array(array
    (
        'text'         => $lang->repobranchtype->import,
        'btnType'      => 'primary',
        'className'    => 'size-sm',
        'data-url'     => createLink('repobranchtype', 'import', "repoID={$repoID}"),
        'zui-on-click' => 'handleImportBranchType($target)'
    )))),
    set::footer(array('checkbox', array('html' => $lang->selectAll, 'className' => 'mr-2'), 'toolbar', array('html' => html::a(helper::createLink('repobranchtype', 'browse', "repoID=$repoID"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);
