<?php
declare(strict_types=1);
/**
 * The browseBranch view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
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

featureBar
(
    form
    (
        setID('searchForm'),
        set::actions(array()),
        formRow
        (
            input
            (
                set::placeholder($lang->searchAB),
                set::name('keyword'),
                set::value($keyword)
            ),
            btn
            (
                setClass('primary ml-2'),
                $lang->searchAB,
                on::click('searchList')
            )
        )
    )
);

$branchList = initTableData($branchList, $config->repo->dtable->branch->fieldList);
dtable
(
    set::cols($config->repo->dtable->branch->fieldList),
    set::data($branchList),
    set::sortLink(createLink('repo', 'browsebranch', "repoID={$repo->id}&objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
