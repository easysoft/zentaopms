<?php
declare(strict_types=1);
/**
 * The browse view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

$isFromDoc = $from == 'doc';
$isFromAI  = $from == 'ai';
if($isFromDoc || $isFromAI)
{
    jsVar('blockID', $blockID);

    $this->app->loadLang('doc');
    $projectChangeLink = createLink('projectRelease', 'browse', "projectID={projectID}&executionID=$executionID&type=$type&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");

    jsVar('insertListLink', createLink('projectRelease', 'browse', "projectID={$project->id}&executionID=$executionID&type=$type&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}"));

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['projectRelease'])),
        set::actions(array()),
        set::showExtra(false),
        to::titleSuffix
        (
            span
            (
                setClass('text-muted text-sm text-gray-600 font-light'),
                span
                (
                    setClass('text-warning mr-1'),
                    icon('help'),
                ),
                $lang->doc->previewTip
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('project'),
                set::label($lang->doc->project),
                set::control(array('required' => false)),
                set::items($projects),
                set::value($project->id),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="project"]')->do("loadModal('$projectChangeLink'.replace('{projectID}', $(this).val()))")
            )
        )
    );
}

featureBar
(
    set::current($type),
    set::linkParams("projectID={$projectID}&executionID={$executionID}&type={key}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from={$from}&blockID={$blockID}"),
    set::isModal($isFromDoc || $isFromAI),
    set::modalTarget('#projectReleases_table')
);

$canManageSystem = hasPriv('system', 'browse') && common::canModify('project', $project);
toolbar
(
    setClass(array('hidden' => $isFromDoc || $isFromAI)),
    !$project->hasProduct && $canManageSystem ? item(set
    (
        array
        (
            'class' => 'primary',
            'text' => $lang->release->manageSystem,
            'url' => $this->createLink('system', 'browse', "productID=0&projectID={$projectID}"),
            'data-app' => 'project'
        )
    )) : null,

    common::canModify('project', $project) && hasPriv('projectrelease', 'create') ? item(set
    ([
        'text'  => $lang->release->create,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => $this->createLink('projectrelease', 'create', "projectID={$projectID}")
    ])) : ''

);

jsVar('markerTitle', $lang->release->marker);
jsVar('integratedLabel', $lang->release->integratedLabel);
jsVar('canViewProjectbuild', hasPriv('projectbuild', 'view'));

$cols = $this->loadModel('datatable')->getSetting('projectrelease');
if(!$showBranch) unset($cols['branch']);
if(isset($cols['branch']))  $cols['branch']['name'] = 'branchName';
if(isset($cols['product'])) $cols['product']['map'] = $products;
if(empty($project->hasProduct)) unset($cols['product']);

foreach(array_column($releases, 'system') as $system)
{
    if(!isset($appList[$system])) $appList[$system] = '';
}
if(!empty($cols['system'])) $cols['system']['map'] = array(0 => '') + $appList;

if($isFromDoc || $isFromAI)
{
    $cols['id']['type'] = 'checkID';

    if(isset($cols['actions'])) unset($cols['actions']);

    foreach($cols as $key => $col)
    {
        $cols[$key]['sortType'] = false;
        if(isset($col['link'])) unset($cols[$key]['link']);
        if($key == 'name') $cols[$key]['link'] = array('url' => createLink('projectrelease', 'view', "releaseID={id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}

foreach($releases as $release)
{
    $release->rowID = $release->id;
    if(empty($release->releases)) continue;

    foreach(explode(',', $release->releases) as $childID)
    {
        if(isset($childReleases[$childID]))
        {
            $child = clone $childReleases[$childID];
            $child->rowID  = "{$release->id}-{$childID}";
            $child->parent = $release->id;
            $releases[$child->rowID] = $child;
        }
    }
}

$createReleaseLink = hasPriv('projectrelease', 'create') ? createLink('projectrelease', 'create', "projectID={$projectID}") : '';
if($isFromDoc) $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => 'insertListToDoc'));
if($isFromAI)  $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToAI('#projectreleases', 'release')"));

$tableData = initTableData($releases, $cols);
dtable
(
    set::id('projectreleases'),
    set::cols(array_values($cols)),
    set::data($tableData),
    set::rowKey('rowID'),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager()),
    set::emptyTip($lang->release->noRelease),
    set::checkable($isFromDoc || $isFromAI),
    ($isFromDoc || $isFromAI) ? set::footToolbar($footToolbar) : set::footer([jsRaw("function(){return {html: '{$pageSummary}'};}"), 'flex', 'pager']),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    (!$isFromDoc && !$isFromAI) ? null : set::onCheckChange(jsRaw('window.checkedChange')),
    (!$isFromDoc && !$isFromAI) ? null : set::height(400),
    ($isFromDoc || $isFromAI) ? null : set::customCols(true),
    ($isFromDoc || $isFromAI) ? null : set::sortLink(createLink('projectrelease', 'browse', "projectID={$project->id}&executionID={$executionID}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    ($isFromDoc || $isFromAI) ? null : set::createTip($lang->release->create),
    ($isFromDoc || $isFromAI) ? null : set::createLink($createReleaseLink)
);

/* ====== Render page ====== */
render();
