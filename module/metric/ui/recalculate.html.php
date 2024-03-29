<?php
declare(strict_types=1);
/**
 * The recalculate file of metric module of ZenTaoPMS.
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
jsVar('code', $code);
jsVar('dateType', $dateType);
jsVar('calcType', $calcType);
jsVar('calcRange', $calcRange);

detailHeader
(
    to::title
    (
        entityLabel
        (
            setClass('text-xl font-black'),
            set::level(1),
            set::text($lang->metric->recalculateHistory)
        ),
        div
        (
            setClass('notice-recalculate'),
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
    )
);

panel
(
    setClass('clear-shadow'),
    set::bodyClass('relative'),
    div
    (
        div
        (
            setID('recalculate-log'),
            setClass('recalculate-log'),
        )
    ),
    set::footerActions(array(array('data-dismiss' => 'modal', 'class' => 'btn hidden exit', 'text' => $lang->metric->exit)))
);
