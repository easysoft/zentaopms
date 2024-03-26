<?php
declare(strict_types=1);
/**
 * The recalculateprogress file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     metric
 * @link        http://www.zentao.net
 */
namespace zin;
jsVar('startDate', $startDate);
jsVar('endDate', $endDate);
jsVar('recalculateLogText', $lang->metric->recalculateLog);

detailHeader
(
    to::title
    (
        entityLabel
        (
            setClass('text-xl font-black'),
            set::level(1),
            set::text($lang->metric->recalculate)
        ),
        label
        (
            to::before(icon
            (
                setClass('warning-ghost margin-left8'),
                'help',
            )),
            set::text($lang->metric->tips->noticeRecalculate),
            setClass('label ghost')
        )
    )
);

$fnGenerateDataDisplay = function() use($lang, $metric)
{
    if(empty($resultData)) return null;
    return div
    (
        set::className('card-data'),
        center
        (
            p
            (
                set::className('card-digit'),
                $resultData[0]->value
            ),
            p
            (
                set::className('card-title'),
                $lang->metric->objectList[$metric->object]
            )
        )

    );
};

panel
(
    setClass('clear-shadow'),
    set::bodyClass('relative'),
    div
    (
        h1
        (
            setClass('border-bottom margin-top24'),
            span
            (
                $lang->metric->verifyResult,
                setClass('gray-pale text-md font-bold')
            )
        ),
        empty($result) ? div
        (
            setClass('verify-content'),
        ) : $fnGenerateDataDisplay(),
    )
);
