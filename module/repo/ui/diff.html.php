<?php
declare(strict_types=1);
/**
 * The diff view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        http://www.zentao.net
 */

namespace zin;

dropmenu(set::module('repo'), set::tab('repo'));

$browser = helper::getBrowser();
jsVar('browser', $browser['name']);
jsVar('edition', $config->edition);

$breadcrumbItems = array();
$breadcrumbItems[] = h::a
(
    set::href($this->repo->createLink('browse', "repoID=$repoID&branchID=&objectID=$objectID"), $repo->name, '', "data-app='{$app->tab}'"),
    $repo->name,
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
        trim($pathName, '/'),
    );

    $breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));
}

if($fileName) $breadcrumbItems[] = h::span($fileName);

if(strpos($repo->SCM, 'Subversion') === false)
{
    $oldRevision = $oldRevision == '^' ? "$newRevision" : $oldRevision;
    $revisionWg  = h::span(setClass('label label-info diff-label'), substr($oldRevision, 0, 10) . " : " . substr($newRevision, 0, 10) . ' (' . zget($historys, $oldRevision, '') . ' : ' . zget($historys, $newRevision, ''));
}
else
{
    $oldRevision = $oldRevision == '^' ? $newRevision - 1 : $oldRevision;
    $revisionWg  = h::span(setClass('label label-info'), $oldRevision . ':' . $newRevision);

}

$breadcrumbItems[] = $revisionWg;
$breadcrumbItems[] = span(setClass('label label-exchange'), icon('exchange'));

featureBar
(
    backBtn(set::icon('back'), setClass('bg-transparent diff-back-btn'), $lang->goback),
    item(set::type('divider')),
    ...$breadcrumbItems,
);

if($diffs) include 'diffeditor.html.php';

jsVar('oldRevision', $oldRevision);
jsVar('newRevision', $newRevision);
