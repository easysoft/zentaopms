<?php
declare(strict_types=1);
/**
 * The blame view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

dropmenu(set::module('repo'), set::tab('repo'), set::objectID($repo->id));

/* Prepare breadcrumb navigation data. */
$base64BranchID    = $branchID ? helper::safe64Encode(base64_encode($branchID)) : '';
$breadcrumbItems   = array();
$breadcrumbItems[] = h::a
(
    set::href($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID")),
    set('data-app', $app->tab),
    $repo->name
);
$breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));

$paths    = explode('/', $entry);
$fileName = array_pop($paths);
$postPath = '';
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

$breadcrumbItems[] = h::span
(
    setClass('ml-2 label secondary'),
    html($revisionName)
);

foreach($blames as $key => $blame)
{
    if(isset($blame['lines']))
    {
        if($repo->SCM != 'Subversion') $blames[$key]['revision'] = substr($blame['revision'], 0, 10);
    }

    $blames[$key]['content'] = htmlSpecialString($blame['content']);
}

foreach($blames as $key => $blame)
{
    $blame['content'] = str_replace(' ', '&nbsp;&nbsp;', $blame['content']);
    $blames[$key] = (object)$blame;
}

$blames = initTableData($blames, $config->repo->blameDtable->fieldList, $this->repo);

$encodePath = $this->repo->encodePath($entry);
$encodes    = array();
foreach($lang->repo->encodingList as $key => $val)
{
    $encodes[] = array('text' => $val, 'url' => inlink('blame', "repoID=$repoID&objectID=$objectID&entry=$encodePath&revision=$revision&encoding=$key"), 'data-app' => $app->tab);
}
$defaultEncode = $lang->repo->encodingList[$encoding];

\zin\featureBar(
    backBtn
    (
        setClass('mr-5'),
        set::icon('back'),
        set::type('secondary'),
        set::back('GLOBAL'),
        $lang->goback
    ),
    ...$breadcrumbItems
);

panel
(
    set::title($fileName),
    to::headingActions(
        btnGroup
        (
            btn($defaultEncode),
            dropdown
            (
                btn(setClass('btn dropdown-toggle'),
                setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                set::items($encodes),
                set::arrow(true),
                set::flip(true),
                set::placement('bottom-end')
            )
        )
    ),
    dtable
    (
        set::cols($config->repo->blameDtable->fieldList),
        set::data($blames)
    )
);

render();

