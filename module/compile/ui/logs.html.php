<?php
declare(strict_types=1);
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

if(!empty($repoID))
{
    $repoName = $this->dao->select('name')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch('name');
    dropmenu(set::objectID($repoID), set::text($repoName), set::tab('repo'));
}

jsVar('buildID', $build->id);
detailHeader(
    to::prefix(''),
    to::title(span(setClass('font-semibold'), $lang->compile->logs)),
    to::suffix
    (
        div
        (
            $job->engine == 'gitlab' ? btn
            (
                set::id('refreshBtn'),
                set::className('mr-3 secondary'),
                set::icon('eye'),
                set::text($lang->compile->refresh),
                on::click('refreshLogs'),
            ) : '',
            backBtn
            (
                set::icon('back'),
                set::type('secondary'),
                set::back('GLOBAL'),
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
            set::title(' '),
            set::content($logs),
            set::useHtml(true)
        ),
    )
);

render();
