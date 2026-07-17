<?php
/**
 * The logs view file of compile module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     compile
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;
$backUrl = createLink('pipeline', "execution", "space={$spaceID}&repoID={$repoID}&type={$type}");

if(!empty($repoID)) dropmenu(set::objectID($repoID), set::tab('repo'));

detailHeader(
    to::prefix(''),
    to::title(span(setClass('font-semibold'), $lang->pipeline->logs)),
    to::suffix
    (
        div
        (
            $pipeline->engine == 'gitlab' ? btn
            (
                set::id('refreshBtn'),
                set::className('mr-3 secondary'),
                set::icon('refresh'),
                set::text($lang->refresh),
                set::url(createLink('pipeline', 'ajaxexeclog', "id={$id}&space={$spaceID}&repoID={$repoID}&type={$type}"))
            ) : null,
            backBtn
            (
                set::icon('back'),
                set::type('secondary'),
                set::url($backUrl),
                $lang->goback
            )
        )
    )
);
detailBody
(
    sectionList
    (
        section
        (
            set::content($logs),
            set::useHtml(true)
        )
    )
);
