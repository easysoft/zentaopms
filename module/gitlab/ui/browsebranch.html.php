<?php
declare(strict_types=1);
/**
 * The browsebranch view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    backBtn
    (
        set::text($lang->goback),
        set::url(createLink('gitlab', 'browseProject', "gitlabID={$gitlabID}"))
    )
);
hasPriv('instance', 'manage') ? toolBar
(
    item
    (
        setClass('btn primary'),
        set::text($lang->gitlab->createBranch),
        set::icon('plus'),
        set::url(createLink('gitlab', 'createBranch', "gitlabID={$gitlabID}&projectID={$projectID}"))
    )
) : null;

$branchList = initTableData($gitlabBranchList, $config->gitlab->dtable->branch->fieldList, $this->gitlab);
dtable
(
    set::cols($config->gitlab->dtable->branch->fieldList),
    set::data($branchList),
    set::sortLink(createLink('gitlab', 'browseBranch', "gitlabID={$gitlabID}&projectID={$projectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
