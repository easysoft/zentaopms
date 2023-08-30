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

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID(''),
            set::level(1),
            set::text($lang->metric->implement)
        )
    ),
);

div
(
    h1
    (
        setClass('font-black text-md'),
        setStyle('margin-bottom', '16px'),
        $lang->metric->implementInstructions
    ),
    div
    (
        setClass('leading-loose'),
        p
        (
            setClass('font-semibold text-md'),
            $lang->metric->implementTips[0]
        ),
        p
        (
            setClass('font-semibold text-md'),
            $lang->metric->implementTips[1]
        ),
        p
        (
            setClass('font-semibold text-md'),
            $lang->metric->implementTips[2]
        ),
        p
        (
            setClass('font-semibold text-md'),
            $lang->metric->implementTips[3]
        ),
    )
);

render();
