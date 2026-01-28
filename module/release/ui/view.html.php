<?php
declare(strict_types=1);
/**
 * The activate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

$releaseModule = $app->rawModule == 'projectrelease' ? 'projectrelease' : 'release';
$isInModal     = isInModal();

jsVar('initLink', $link);
jsVar('type', $type);
jsVar('loadFileUrl', createLink($releaseModule, 'view', "releaseID={$release->id}&type=releaseInfo"));
$canBeChanged = common::canBeChanged($releaseModule, $release);
$actions      = $this->loadModel('common')->buildOperateMenu($release, $releaseModule);
foreach($actions as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actions[$actionType][$key]['className'] = isset($action['className']) ? $action['className'] . ' ghost' : 'ghost';
        $actions[$actionType][$key]['iconClass'] = isset($action['iconClass']) ? $action['iconClass'] . ' text-primary' : 'text-primary';
        $actions[$actionType][$key]['url']       = str_replace('{id}', (string)$release->id, $action['url']);
        if($actionType == 'suffixActions')
        {
            if($action['icon'] == 'edit')
            {
                $actions['suffixActions'][$key]['text']     = $lang->edit;
                $actions['suffixActions'][$key]['data-app'] = $app->tab;
            }
            if($action['icon'] == 'trash') $actions['suffixActions'][$key]['text'] = $lang->delete;
        }
    }
}
detailHeader
(
    to::prefix
    (
        $isInModal ? null : backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            set::url($releaseModule, 'browse', $releaseModule == 'projectrelease' ? "projectID={$this->session->project}" : "productID={$release->product}"),
            $lang->goback
        ),
        entityLabel(set(array('entityID' => $release->id, 'level' => 2, 'text' => zget($appList, $release->system) . $release->name))),
        $release->deleted ? span(setClass('label danger'), $lang->release->deleted) : null
    ),
    !$isInModal && (!empty($actions['mainActions']) || !empty($actions['suffixActions'])) ? to::suffix
    (
        btnGroup(set::items($actions['mainActions'])),
        !empty($actions['mainActions']) && !empty($actions['suffixActions']) ? div(setClass('divider')): null,
        btnGroup(set::items($actions['suffixActions']))
    ) : null
);

jsVar('releaseID', $release->id);
jsVar('showGrade', $showGrade);
jsVar('grades', $grades);

if(!empty($release->releases) || $release->deleted || ($app->tab == 'project' && !common::canModify('project', $project)))
{
    $config->release->dtable->story->fieldList['id']['type']   = 'ID';
    $config->release->dtable->bug->fieldList['id']['type']     = 'ID';
    $config->release->dtable->leftBug->fieldList['id']['type'] = 'ID';

    unset($config->release->dtable->story->fieldList['actions']);
    unset($config->release->dtable->bug->fieldList['actions']);
    unset($config->release->dtable->leftBug->fieldList['actions']);
}

/* Table data and setting for finished stories tab. */
jsVar('storyCases', $storyCases);
jsVar('confirmunlinkstory', $lang->release->confirmUnlinkStory);
jsVar('checkedSummary', $lang->product->checkedSRSummary);
jsVar('unlinkstoryurl', helper::createLink($releaseModule, 'unlinkStory', "releaseID={$release->id}&storyID=%s"));
$storyTableData = initTableData($stories, $config->release->dtable->story->fieldList, $this->release);

$canBatchUnlinkStory = !$isInModal && $canBeChanged && common::hasPriv($releaseModule, 'batchUnlinkStory');
$canBatchCloseStory  = !$isInModal && $canBeChanged && common::hasPriv('story', 'batchClose');

$storyFootToolbar = array();
if($canBatchUnlinkStory) $storyFootToolbar['items'][] = array('className' => 'btn primary size-sm batch-btn', 'text' => $lang->release->batchUnlink, 'data-type' => 'story', 'data-url' => createLink($releaseModule, 'batchUnlinkStory', "release={$release->id}"));
if($canBatchCloseStory)  $storyFootToolbar['items'][] = array('className' => 'btn primary size-sm batch-btn load-btn', 'text' => $lang->story->batchClose,    'data-type' => 'story', 'data-url' => createLink('story', 'batchClose', "productID={$release->product}"));

/* Table data and setting for resolved bugs tab. */
jsVar('confirmunlinkbug', $lang->release->confirmUnlinkBug);
jsVar('unlinkbugurl', helper::createLink($releaseModule, 'unlinkBug', "releaseID={$release->id}&bugID=%s"));

$config->release->dtable->bug->fieldList['resolvedBuild']['map'] = $builds;
$bugTableData = initTableData($bugs, $config->release->dtable->bug->fieldList, $this->release);
$bugTableData = array_map(function($bug)
{
    if(helper::isZeroDate($bug->resolvedDate)) $bug->resolvedDate = '';
    return $bug;
}, $bugTableData);

$canBatchUnlinkBug = !$isInModal && $canBeChanged && common::hasPriv($releaseModule, 'batchUnlinkBug');
$canBatchCloseBug  = !$isInModal && $canBeChanged && common::hasPriv('bug', 'batchClose');

$bugFootToolbar = array();
if($canBatchUnlinkBug) $bugFootToolbar['items'][] = array('className' => 'btn primary size-sm batch-btn', 'text' => $lang->release->batchUnlink, 'data-type' => 'bug', 'data-url' => createLink($releaseModule, 'batchUnlinkBug', "release={$release->id}"));
if($canBatchCloseBug)  $bugFootToolbar['items'][] = array('className' => 'btn primary size-sm batch-btn bug-batch-close', 'text' => $lang->bug->batchClose, 'data-type' => 'bug', 'data-url' => createLink('bug', 'batchClose', "release={$release->id}&viewType=release"));

/* Table data and setting for left bugs tab. */
jsVar('confirmunlinkleftbug', $lang->release->confirmUnlinkBug);
jsVar('unlinkleftbugurl', helper::createLink($releaseModule, 'unlinkBug', "releaseID={$release->id}&bugID=%s&type=leftBug"));

$releaseBuild = array();
foreach($release->builds as $build) $releaseBuild[] = $build->name;
$leftBugTableData = initTableData($leftBugs, $config->release->dtable->leftBug->fieldList, $this->release);
$leftBugTableData = array_map(function($bug)
{
    if(helper::isZeroDate($bug->resolvedDate)) $bug->resolvedDate = '';
    return $bug;
}, $leftBugTableData);
if(commonModel::hasPriv($releaseModule, 'unlinkBug'))
{
    foreach($leftBugTableData as $leftBug)
    {
        $openedBuilds = '';
        foreach(explode(',', $leftBug->openedBuild) as $openedBuild)
        {
            $openedBuilds .= zget($builds, $openedBuild) . ',';
        }
        $leftBug->openedBuild = trim($openedBuilds, ',');

        if(!empty($leftBug->actions)) continue;
        $leftBug->actions[] = array('name' => 'unlinkLeftBug', 'disabled' => false);
    }
}

$leftBugFootToolbar = array();
if($canBatchUnlinkBug) $leftBugFootToolbar['items'][] = array('className' => 'btn primary size-sm batch-btn', 'text' => $lang->release->batchUnlink, 'data-type' => 'bug', 'data-url' => createLink($releaseModule, 'batchUnlinkBug', "release={$release->id}&type=leftBug"));

/* Process release info data. */
$releaseBuild = array();
foreach($release->builds as $build) $releaseBuild[] = $build->name;

$releaseBranch = array();
if($product->type != 'normal')
{
    foreach($release->branches as $branchID) $releaseBranch[] = zget($branches, $branchID, '');
}

$releaseSystem = array();
foreach($linkedReleases as $linkedRelease) $releaseSystem[] = zget($appList, $linkedRelease->system) . $linkedRelease->name;

$releaseIncluded = array();
foreach($includedApps as $includedApp)
{
    $releaseIncluded[] = html::a
    (
        inLink('view', "releaseID={$includedApp->id}"),
        zget($appList, $includedApp->system) . $includedApp->name,
        '_blank'
    );
}

/* Right menus, export and link. */
$exportBtn = null;
if(!$isInModal && common::hasPriv($releaseModule, 'export') && ($summary || count($bugs) || count($leftBugs)))
{
    $exportBtn = btn(set(array(
        'text'        => $lang->release->export,
        'icon'        => 'export',
        'url'         => createLink($releaseModule, 'export', "releaseID={$release->id}"),
        'class'       => 'ghost',
        'data-size'   => 'sm',
        'data-toggle' => 'modal'
    )));
}

$decodeParam  = helper::safe64Decode($param);
$linkStoryBtn = $linkBugBtn = $linkLeftBtn = null;
jsVar('linkParams', $decodeParam);
jsVar('releaseModule', $releaseModule);

if(!$release->deleted && $canBeChanged && empty($release->releases))
{
    if(!$isInModal && common::hasPriv($releaseModule, 'linkStory'))
    {
        $linkStoryBtn = btn
            (
                set::text($lang->release->linkStory),
                set::icon('link'),
                set::type('primary'),
                set::className('linkStory-btn'),
                bind::click('window.showLink', array('params' => array('story')))
            );
    }

    if(!$isInModal && common::hasPriv($releaseModule, 'linkBug'))
    {
        $linkBugBtn = btn
            (
                set::text($lang->release->linkBug),
                set::icon('bug'),
                set::type('primary'),
                set::className('linkBug-btn'),
                bind::click('window.showLink', array('params' => array('bug')))
            );

        $linkLeftBtn = btn
            (
                set::text($lang->release->linkBug),
                set::icon('bug'),
                set::type('primary'),
                set::className('leftBug-btn'),
                bind::click('window.showLink', array('params' => array('leftBug')))
            );
    }
}

detailBody
(
    set::hasExtraMain(false),
    on::click('.batch-btn > a, .batch-btn')->call('handleClickBatchBtn', jsRaw('$this')),
    setClass('release-view-body'),
    sectionList(
        tabs
        (
            set::className('w-full'),
            set::id('releaseTabs'),
            set::headerClass('border-b'),

            /* Linked story table. */
            tabPane
            (
                to::prefix(icon('lightbulb')),
                set::key('finishedStory'),
                set::title($lang->release->stories),
                set::active($type == 'story'),
                div
                (
                    setClass('tab-actions'),
                    $exportBtn,
                    $linkStoryBtn
                ),
                dtable
                (
                    setID('finishedStoryDTable'),
                    set::style(array('min-width' => '100%')),
                    set::cols(array_values($config->release->dtable->story->fieldList)),
                    set::data($storyTableData),
                    set::userMap($users),
                    set::checkable(empty($release->releases) && ($canBatchUnlinkStory || $canBatchCloseStory)),
                    set::sortLink(createLink($releaseModule, 'view', "releaseID={$release->id}&type=story&link={$link}&param={$param}&orderBy={name}_{sortType}")),
                    set::orderBy($orderBy),
                    set::onRenderCell(jsRaw('window.renderStoryCell')),
                    set::extraHeight('+144'),
                    set::footToolbar($storyFootToolbar),
                    set::footPager(usePager('storyPager', '', array('recPerPage' => $storyPager->recPerPage, 'recTotal' => $storyPager->recTotal, 'linkCreator' => createLink($releaseModule, 'view', "releaseID={$release->id}&type=story&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}")))),
                    set::checkInfo(jsRaw("function(checkedIDList){return window.setStoryStatistics(this, checkedIDList, '{$summary}');}")),
                )
            ),

            /* Resolved bug table. */
            tabPane
            (
                to::prefix(icon('bug')),
                set::key('resolvedBug'),
                set::title($lang->release->bugs),
                set::active($type == 'bug'),
                div
                (
                    setClass('tab-actions'),
                    $exportBtn,
                    $linkBugBtn
                ),
                dtable
                (
                    setID('resolvedBugDTable'),
                    set::style(array('min-width' => '100%')),
                    set::userMap($users),
                    set::cols(array_values($config->release->dtable->bug->fieldList)),
                    set::data($bugTableData),
                    set::checkable(empty($release->releases) && ($canBatchUnlinkBug || $canBatchCloseBug)),
                    set::sortLink(createLink($releaseModule, 'view', "releaseID={$release->id}&type=bug&link={$link}&param={$param}&orderBy={name}_{sortType}")),
                    set::orderBy($orderBy),
                    set::extraHeight('+144'),
                    set::footToolbar($bugFootToolbar),
                    set::footPager(usePager('bugPager', '', array('recPerPage' => $bugPager->recPerPage, 'recTotal' => $bugPager->recTotal, 'linkCreator' => createLink($releaseModule, 'view', "releaseID={$release->id}&type=bug&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$bugPager->recTotal}&recPerPage={recPerPage}&page={page}"))))
                )
            ),

            /* Left bug table. */
            tabPane
            (
                to::prefix(icon('bug')),
                set::key('leftBug'),
                set::title($lang->release->generatedBugs),
                set::active($type == 'leftBug'),
                div
                (
                    setClass('tab-actions'),
                    $exportBtn,
                    $linkLeftBtn
                ),
                dtable
                (
                    setID('leftBugDTable'),
                    set::style(array('min-width' => '100%')),
                    set::userMap($users),
                    set::cols(array_values($config->release->dtable->leftBug->fieldList)),
                    set::data($leftBugTableData),
                    set::checkable(empty($release->releases) && ($canBatchUnlinkBug || $canBatchCloseBug)),
                    set::sortLink(createLink($releaseModule, 'view', "releaseID={$release->id}&type=leftBug&link={$link}&param={$param}&orderBy={name}_{sortType}")),
                    set::orderBy($orderBy),
                    set::extraHeight('+144'),
                    set::footToolbar($leftBugFootToolbar),
                    set::footPager(usePager('leftBugPager', '', array('recPerPage' => $leftBugPager->recPerPage, 'recTotal' => $leftBugPager->recTotal, 'linkCreator' => createLink($releaseModule, 'view', "releaseID={$release->id}&type=leftBug&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$leftBugPager->recTotal}&recPerPage={recPerPage}&page={page}"))))
                )
            ),

            /* Basic info block. */
            tabPane
            (
                to::prefix(icon('flag')),
                set::key('releaseInfo'),
                set::title($lang->release->basicInfo),
                set::active($type == 'releaseInfo'),
                div
                (
                    setClass('tab-actions'),
                    $exportBtn
                ),
                div(
                    section(
                        set::title($lang->release->basicInfo),
                        tableData
                        (
                            item
                            (
                                set::name($lang->release->product),
                                $release->productName
                            ),
                            !empty($releaseBranch) ? item
                            (
                                set::name($lang->release->branch),
                                implode($lang->comma, $releaseBranch)
                            ) : null,
                            item
                            (
                                set::name($lang->release->system),
                                zget($appList, $release->system)
                            ),
                            item
                            (
                                set::name($lang->release->name),
                                $release->name
                            ),
                            empty($releaseBuild) ? null : item
                            (
                                set::name($lang->release->includedBuild),
                                implode($lang->comma, $releaseBuild)
                            ),
                            empty($releaseSystem) ? null : item
                            (
                                set::name($lang->release->includedSystem),
                                implode($lang->comma, $releaseSystem)
                            ),
                            item
                            (
                                set::name($lang->release->status),
                                $this->processStatus('release', $release)
                            ),
                            item
                            (
                                set::name($lang->release->date),
                                $release->date
                            ),
                            item
                            (
                                set::name($lang->release->releasedDate),
                                $release->releasedDate
                            ),
                            empty($releaseIncluded) ? null : item
                            (
                                set::name($lang->release->includedApp),
                                html(implode($lang->comma, $releaseIncluded))
                            ),
                            item
                            (
                                set::name($lang->release->desc),
                                html($release->desc)
                            )
                        )
                    ),
                    html($this->printExtendFields($release, 'html', 'position=all', false)),
                    fileList(set::files($release->files)),
                    h::hr(set::className('mt-6')),
                    history(set::objectID($release->id), set::objectType('release'))
                )
            )
        )
    )
);

render();
