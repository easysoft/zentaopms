<?php
declare(strict_types=1);
/**
 * The implement file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     metric
 * @link        http://www.zentao.net
 */
namespace zin;

if(empty($resultData)) return null;
if(count($resultData) == 1 && count((array)$resultData[0]) == 1) return div
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

dtable
(
    set::height(180),
    set::cols($resultHeader),
    set::data($resultData)
);
