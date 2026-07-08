<?php
declare(strict_types=1);
/**
 * The plan view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

if($repoID) dropmenu(set::objectID($repoID), set::tab('repo'));
jsVar('scanLang', $lang->codescan);

featureBar
(
    set::current($status),
    set::linkParams("repoID={$repoID}&status={key}"),
    //set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
);

hasPriv('codescan', 'createplan') ? toolbar
(
    item(set(array(
        'text'        => $lang->codescan->createPlan,
        'url'         => inLink('createplan', "repoID={$repoID}"),
        'class'       => 'primary',
        'icon'        => 'plus'
    )))
) : null;
$config->codescan->plan->dtable->fieldList['repoID']['map']         = $repoList;
$config->codescan->plan->dtable->fieldList['solutions']['delemiter'] = ',';
$config->codescan->plan->dtable->fieldList['solutions']['map']       = $solutionList;
if($repoID) unset($config->codescan->plan->dtable->fieldList['repo_id']);

$cols = $this->loadModel('datatable')->getSetting('codescan', 'plan');
$cols['actions']['list']['editPlan']['url']['params']   = "planID={id}&serviceRepoID={repoID}&repoID={$repoID}";
$cols['actions']['list']['deletePlan']['url']['params'] = "serviceRepoID={repoID}&planID={id}&repoID={$repoID}";
$cols['actions']['list']['trigger']['url']['params']    = "serviceRepoID={repoID}&planID={id}&repoID={$repoID}&type=trigger";
$cols['actions']['list']['task']['url']['params']       = "serviceRepoID={repoID}&planID={id}&repoID={$repoID}&type=task";
if(!hasPriv('codescan', 'task')) unset($cols['actions']['list']['task']);

if(hasPriv('codescan', 'planview'))
{
    if(isset($cols['name']))         $cols['name']['link']['params']         = "serviceRepoID={repoID}&planID={id}&repoID={$repoID}&type=view";
    if(isset($cols['triggerCount'])) $cols['triggerCount']['link']['params'] = "serviceRepoID={repoID}&planID={id}&repoID={$repoID}&type=trigger";
}

$planList = initTableData($plans, $cols);

dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($planList),
    set::sortLink(createLink('codescan', 'plan', "repoID={$repoID}&status={$status}&orderBy={name}_{sortType}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.renderPlanCell'))
);
