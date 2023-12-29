<?php
declare(strict_types=1);
/**
 * The browsetag view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('protectedTag', $lang->gitlab->tag->protected);

if(!empty($permissionError))
{
    jsCall('alertJump', array($permissionError, $errorJump));
    return;
}

featureBar
(
    backBtn
    (
        set::text($lang->goback),
        set::url(createLink('gitlab', 'browseProject', "gitlabID={$gitlabID}"))
    ),
    form
    (
        setID('searchForm'),
        setClass('ml-4'),
        set::actions(array()),
        formRow
        (
            input
            (
                set::placeholder($lang->gitlab->tag->placeholderSearch),
                set::name('keyword'),
                set::value($keyword)
            ),
            btn
            (
                setClass('primary'),
                $lang->gitlab->search,
                on::click('search')
            )
        )
    )
);
hasPriv('instance', 'manage') ? toolBar
(
    item
    (
        setClass('btn primary'),
        set::text($lang->gitlab->createTag),
        set::icon('plus'),
        set::url(createLink('gitlab', 'createTag', "gitlabID={$gitlabID}&projectID={$projectID}"))
    )
) : null;

$tagList = initTableData($gitlabTagList, $config->gitlab->dtable->tag->fieldList, $this->gitlab);
dtable
(
    set::cols($config->gitlab->dtable->tag->fieldList),
    set::data($tagList),
    set::sortLink(createLink('gitlab', 'browseTag', "gitlabID={$gitlabID}&projectID={$projectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);
