<?php
declare(strict_types=1);
/**
 * The diff view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */

namespace zin;

$module  = $app->tab == 'devops' ? 'repo' : $app->tab;
$inModal = isInModal() || !empty($fromModal);
$inModal ? null : dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

if($inModal)
{
    to::header(false);
    to::main(false);
}

h:css("#monacoTree .text-clip {overflow: visible;}");

jsVar('repo', $repo);
jsVar('repoLang', $lang->repo);
jsVar('objectID', $objectID);

/* Prepare repo dropdown data. */
$items = $this->repoZen->getBranchAndTagItems($repo, '');
$tabs     = array(array('name' => 'branchesAndTags', 'text' => $lang->repo->branch));
$menuData = array('branchesAndTags' => array(array('text' => $lang->repo->branch, 'items' => $items['branchMenus']), array('text' => $lang->repo->tag, 'items' => $items['tagMenus'])));

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

$oldRevision = $oldRevision == '^' ? "$newRevision" : $oldRevision;

$breadcrumbItems[] = input(set::type('hidden'), set::name('oldRevision'), set::value($oldRevision));
$breadcrumbItems[] = input(set::type('hidden'), set::name('newRevision'), set::value($newRevision));
$breadcrumbItems[] = input(set::type('hidden'), set::name('isBranchOrTag'), set::value($isBranchOrTag));
$breadcrumbItems[] = span($lang->repo->source . ':', setClass('ml-3'));

$isSvnRepo = $this->repo->isSvn($repo);
if($isSvnRepo)
{
    /* SVN 无分支/标签概念,source/target 直接由用户输入 revision 号。 */
    $breadcrumbItems[] = input
    (
        setID('source'),
        set::type('text'),
        set::value((string)$oldRevision),
        setClass('form-control w-20 mr-2'),
        set::placeholder($lang->repo->revision)
    );
}
else
{
    $breadcrumbItems[] = dropmenu
    (
        setID('source'),
        set::objectID($objectID),
        set::text($isBranchOrTag ? $oldRevision : mb_substr($oldRevision, 0, 10)),
        set::data(array('data' => $menuData, 'tabs' => $tabs))
    );
}
$breadcrumbItems[] = span(on::click()->call('changeDiff'), setID('exchange'), setClass('label label-exchange mr-2 text-white'), icon('exchange'));
$breadcrumbItems[] = span($lang->repo->target . ':');
if($isSvnRepo)
{
    $breadcrumbItems[] = input
    (
        setID('target'),
        set::type('text'),
        set::value((string)$newRevision),
        setClass('form-control w-20 mr-2'),
        set::placeholder($lang->repo->revision)
    );
}
else
{
    $breadcrumbItems[] = dropmenu
    (
        setID('target'),
        set::objectID($objectID),
        set::text($isBranchOrTag ? $newRevision : mb_substr($newRevision, 0, 10)),
        set::data(array('data' => $menuData, 'tabs' => $tabs))
    );
}
$breadcrumbItems[] = btn
(
    set::type('primary'),
    set::size('md'),
    $lang->repo->compare,
    on::click()->call('window.goDiff')
);

featureBar
(
    setClass($inModal ? 'hidden' : ''),
    backBtn(set::icon('back'), setClass('bg-transparent diff-back-btn'), set::back('GLOBAL'), $lang->goback),
    item(set::type('divider')),
    $breadcrumbItems
);

if($diffs)
{
    include 'diffeditor.html.php';
}
else
{
    div
    (
        setClass('dtable-empty-tip'),
        div
        (
            setClass('row gap-4 items-center'),
            span
            (
                setClass('text-gray'),
                $lang->repo->notice->noChanges
            )
        )
    );
}

jsVar('oldRevision', $oldRevision);
jsVar('newRevision', $newRevision);
