<?php
declare(strict_types=1);
/**
 * The linkCommit view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('designID', $designID);
jsVar('errorDate', $lang->design->errorDate);

modalHeader(set::title($lang->design->linkCommit));
if(empty($repoID))
{
    div
    (
        setClass('no-data-box'),
        span
        (
            setClass('text-gray'),
            $lang->design->noCommit
        ),
    );
}
else
{
    featureBar
    (
        div
        (
            setClass('select-repo-box'),
            span
            (
                setClass('flex items-center repo-title'),
                $lang->repo->maintain
            ),
            picker
            (
                set::name('repo'),
                set::value($repoID),
                set::items($repos),
                set::width('200px'),
                on::change('loadCommit'),
                set::required(true)
            )
        ),
        div
        (
            setClass('select-date-box ml-8'),
            span
            (
                setClass('flex items-center date-title'),
                $lang->design->commitDate
            ),
            inputGroup
            (
                datePicker
                (
                    set::name('begin'),
                    set::value($begin),
                    on::change('loadCommit')
                ),
                $lang->to,
                datePicker
                (
                    set::name('end'),
                    set::value($end),
                    on::change('loadCommit')
                )
            )
        )
    );

    $footToolbar['items'][] = array(
        'text' => $lang->save,
        'class' => 'btn batch-btn secondary size-sm',
        'data-url' => inlink('linkCommit', "designID={$designID}&repoID={$repoID}&begin={$begin}&end={$end}")
    );

    dtable
    (
        set::className('mt-2'),
        set::userMap($users),
        set::cols($config->design->linkcommit->dtable->fieldList),
        set::data($revisions),
        set::footToolbar($footToolbar),
        set::checkInfo(jsRaw('function(checkedIDList){return \'\';}')),
        set::rowKey('revision'),
        set::footPager(usePager())
    );
}

/* ====== Render page ====== */
render();
