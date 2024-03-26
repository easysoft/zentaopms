<?php
declare(strict_types=1);
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@easycorp.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */
namespace zin;

form
(
    formGroup
    (
        checkbox
        (
            setClass('isCalcAll'),
            set::name('isCalcAll'),
            set::checked(false),
            set::text($lang->metric->recalculate)
        )
    ),
    set::actions(array()),
    btn
    (
        setClass('btn primary-outline'),
        bind::click('window.showRecalculateProgress()'),
        $lang->metric->recalculate
    )
);
