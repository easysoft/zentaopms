<?php
declare(strict_types=1);
/**
 * The story view file of projectstory module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     projectstory
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('gradeGroup', $gradeGroup);

$projectChangeLink = createLink('projectStory', 'story', "projectID={projectID}&productID=0&branch=&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");
$insertListLink    = createLink('projectStory', 'story', "projectID=$projectID&productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}");
formPanel
(
    setID('zentaolist'),
    setClass('mb-4-important'),
    set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['projectStory'])),
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
            set::value($projectID),
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

$queryMenuLink = createLink('projectStory', 'story', "projectID=$projectID&productID=$productID&branch=$branch&browseType=bySearch&param={queryID}&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");
featureBar
(
    set::param($param),
    set::current($browseType),
    set::link(createLink('projectStory', 'story', "projectID=$projectID&productID=$productID&branch=$branch&browseType={key}&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID")),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    set::isModal(),
    set::modalTarget('#stories_table'),
    li(searchToggle
    (
        set::simple(),
        set::open($browseType == 'bysearch'),
        set::module($config->product->search['module']),
        set::target('#docSearchForm'),
        set::onSearch(jsRaw('function(){$(this.element).closest(".modal").find("#featureBar .nav-item>.active").removeClass("active").find(".label").hide()}'))
    ))
);

div(setID('docSearchForm'));

/* datatable columns. */
$config->story->dtable->fieldlist['title']['title'] = $lang->story->title;
$config->$storyType->dtable->fieldlist['assignedto']['assignlink']['module'] = $storyType;

$setting = $this->loadmodel('datatable')->getsetting('product', 'browse', false, $storyType);

if($storyType != 'story') unset($setting['taskcount'], $setting['bugcount'], $setting['casecount']);
if($storyType == 'story' && $config->edition == 'ipd') unset($setting['roadmap']);
$setting['title']['nestedtoggle'] = false;

if(isset($setting['actions'])) unset($setting['actions']);
foreach($setting as $key => $col)
{
    if($key == 'assignedTo') $setting[$key]['type'] = 'user';
    $setting[$key]['sortType'] = false;
    if(isset($col['link'])) unset($setting[$key]['link']);
    if($key == 'pri') $setting[$key]['priList'] = $lang->story->priList;
    if($key == 'title') $setting[$key]['link']  = array('url' => helper::createLink('{type}', 'view', 'storyID={id}&version={version}&param=0&storyType={type}'), 'data-toggle' => 'modal', 'data-size' => 'lg');
}

$cols = array_values($setting);

/* datatable data. */
$data    = array();
$options = array('storytasks' => $storyTasks, 'storybugs' => $storyBugs, 'storycases' => $storyCases, 'modules' => $modules, 'plans' => (isset($plans) ? $plans : array()), 'users' => $users, 'execution' => $project, 'roadmaps' => $roadmaps, 'reports' => $reports);
foreach($stories as $story)
{
    $story->rawmodule    = $story->module;
    $story->from         = $app->tab;
    $options['branches'] = zget($branchOptions, $story->product, array());
    $data[] = $this->story->formatStoryForList($story, $options, $storyType, $maxGradeGroup);
}

$footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc('#projectStories', 'projectStory', $blockID, '$insertListLink')"));

dtable
(
    set::id('projectStories'),
    set::userMap($users),
    set::checkable(),
    set::cols($cols),
    set::data($data),
    set::noNestedCheck(),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager()),
    set::emptyTip($lang->story->noStory),
    set::footToolbar($footToolbar),
    set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    set::onCheckChange(jsRaw('window.checkedChange')),
    set::height(400)
);

render();
