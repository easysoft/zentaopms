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
                set::url(helper::createLink('ci', "checkCompileStatus", "compileID={$build->id}"))
            ) : '',
            backBtn
            (
                set::icon('back'),
                set::type('secondary'),
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

render();
