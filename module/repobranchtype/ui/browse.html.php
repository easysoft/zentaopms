<?php
declare(strict_types=1);
/**
 * The browse branch type view file of repobranchtype module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     repobranchtype
 * @link        https://www.zentao.net
 */
namespace zin;

// 获取 repoID（从配置菜单进入时 repo 为 null）
$currentRepoID = $repo ? $repo->id : $repoID;

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

// 按钮定义（只在有 repo 时使用）
$importBranchType = null;
if($repo)
{
    // 导入分支类型按钮
    $importBranchType = array('text' => $lang->repobranchtype->import, 'url' => createLink('repobranchtype', 'import', "repoID={$currentRepoID}"));
}
$createRepo = $repo ? $repo->id : 0;
// 创建按钮
$createBranchType = array('text' => $lang->repobranchtype->create, 'url' => createLink('repobranchtype', 'create', "repoID={$createRepo}"));

featureBar();

toolbar
(
    // 导入分支类型按钮（只在有 repo 时显示）
    ($repo && hasPriv('repobranchtype', 'import')) ? item(set($importBranchType + array
    (
        'icon'  => 'import',
        'class' => 'btn primary',
    )),
    set('data-app', $app->tab)) : null,
    // 创建按钮（只在有 repo 时显示）
     hasPriv('repobranchtype', 'create') ? item(set($createBranchType + array
    (
        'icon'  => 'plus',
        'class' => 'btn primary',
    )),
    set('data-app', $app->tab)) : null
);

// 非代码库下的分支类型不显示分支规则操作
if(!$repo) $config->repobranchtype->dtable->fieldList['actions']['menu'] = array('edit', 'delete');

// 初始化表格数据
$data = initTableData($branchTypeList, $config->repobranchtype->dtable->fieldList);

// 为每条数据添加 repoID 字段（用于操作链接）
// 注意：$item->repo 存储的是 gitfoxID，不是本地数据库的 repoID
// 所以这里始终使用 $currentRepoID
foreach($data as &$item)
{
    $item->repoID = $currentRepoID;
}

// 排序链接参数
$urlParams = array(
    'repoID'     => $currentRepoID,
    'orderBy'    => '{name}_{sortType}',
    'recTotal'   => isset($pager->recTotal) ? $pager->recTotal : 0,
    'recPerPage' => isset($pager->recPerPage) ? $pager->recPerPage : 20,
    'pageID'     => isset($pager->pageID) ? $pager->pageID : 1
);

dtable
(
    set::cols($config->repobranchtype->dtable->fieldList),
    set::data($data),
    set::orderBy($orderBy),
    set::sortLink(createLink('repobranchtype', 'browse', $urlParams)),
    set::onRenderCell(jsRaw('window.renderBranchTypeCell')),
    set::footPager(usePager())
);