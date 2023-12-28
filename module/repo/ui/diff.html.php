<?php
declare(strict_types=1);
/**
 * The diff view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */

namespace zin;

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

jsVar('repo', $repo);
jsVar('repoLang', $lang->repo);
jsVar('objectID', $objectID);

/* Prepare repo dropdown data. */
if($repo->SCM != 'Subversion')
{
    $items = $this->repoZen->getBranchAndTagItems($repo, '');
    $tabs     = array(array('name' => 'branchesAndTags', 'text' => $lang->repo->branch));
    $menuData = array('branchesAndTags' => array(array('text' => $lang->repo->branch, 'items' => $items['branchMenus']), array('text' => $lang->repo->tag, 'items' => $items['tagMenus'])));
}

$browser = helper::getBrowser();
jsVar('browser', $browser['name']);
jsVar('edition', $config->edition);

$breadcrumbItems = array();
$breadcrumbItems[] = h::a
(
    set::href($this->repo->createLink('browse', "repoID=$repoID&branchID=&objectID=$objectID")),
    set('data-app', $app->tab),
    $repo->name
);

$breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));

$paths          = explode('/', $entry);
$fileName       = array_pop($paths);
$postPath       = '';
$base64BranchID = helper::safe64Encode(base64_encode($branchID));

foreach($paths as $pathName)
{
    $postPath .= $pathName . '/';
    $breadcrumbItems[] = h::a
    (
        set::href($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID&path=" . $this->repo->encodePath($postPath))),
        set('data-app', $app->tab),
        trim($pathName, '/')
    );

    $breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));
}

if($fileName) $breadcrumbItems[] = h::span($fileName);

if($repo->SCM != 'Subversion')
{
    $oldRevision = $oldRevision == '^' ? "$newRevision" : $oldRevision;

    $breadcrumbItems[] = input(set::type('hidden'), set::name('oldRevision'), set::value($oldRevision));
    $breadcrumbItems[] = input(set::type('hidden'), set::name('newRevision'), set::value($newRevision));
    $breadcrumbItems[] = input(set::type('hidden'), set::name('isBranchOrTag'), set::value($isBranchOrTag));
    $breadcrumbItems[] = span($lang->repo->source . ':', setClass('ml-3'));
    $breadcrumbItems[] = dropmenu
    (
        setID('source'),
        set::objectID($objectID),
        set::text($oldRevision),
        set::data(array('data' => $menuData, 'tabs' => $tabs))
    );
    $breadcrumbItems[] = span(setClass('label label-exchange mr-2'), icon('exchange'));
    $breadcrumbItems[] = span($lang->repo->target . ':');
    $breadcrumbItems[] = dropmenu
    (
        setID('target'),
        set::objectID($objectID),
        set::text($newRevision),
        set::data(array('data' => $menuData, 'tabs' => $tabs))
    );
    $breadcrumbItems[] = btn
    (
        set::id('diffForm'),
        set::type('primary'),
        set::size('md'),
        $lang->repo->compare,
        on::click('goDiff')
    );
}
else
{
    $oldRevision = $oldRevision == '^' ? $newRevision - 1 : $oldRevision;

    $breadcrumbItems[] = input(set::type('hidden'), set::name('isBranchOrTag'), set::value($isBranchOrTag));
    $breadcrumbItems[] = input
    (
        setClass('svn-version mr-2'),
        setStyle('width', '160px'),
        set::name('oldRevision'),
        set::value($oldRevision),
        set::placeholder($lang->repo->source)
    );
    $breadcrumbItems[] = span(setClass('label label-exchange mr-2'), icon('exchange'));
    $breadcrumbItems[] = input
    (
        setClass('svn-version mr-2'),
        setStyle('width', '160px'),
        set::name('newRevision'),
        set::value($newRevision),
        set::placeholder($lang->repo->target)
    );
    $breadcrumbItems[] = btn
    (
        set::id('diffForm'),
        set::type('primary'),
        set::size('md'),
        $lang->repo->compare,
        on::click('goDiff')
    );
}

\zin\featureBar
(
    backBtn(set::icon('back'), setClass('bg-transparent diff-back-btn'), set::back('GLOBAL'), $lang->goback),
    item(set::type('divider')),
    ...$breadcrumbItems
);

if($diffs) include 'diffeditor.html.php';

jsVar('oldRevision', $oldRevision);
jsVar('newRevision', $newRevision);
