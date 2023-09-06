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
            set::entityID($metric->id),
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
        setStyle('margin-bottom', '5px'),
        $lang->metric->implementInstructions
    ),
    div
    (
        setClass('leading-loose'),
        p
        (
            set::className('font-medium text-md'),
            setStyle('padding-top', '12px'),
            $lang->metric->implementTips[0]
        ),
        p
        (
            set::className('font-medium text-md'),
            setStyle('padding-top', '12px'),
            $lang->metric->implementTips[1]
        ),
        p
        (
            set::className('font-medium text-md'),
            setStyle('padding-top', '12px'),
            $lang->metric->implementTips[2]
        ),
        p
        (
            set::className('font-medium text-md'),
            setStyle('padding-top', '12px'),
            $lang->metric->implementTips[3]
        ),
    ),
    h1
    (
        setClass('font-black text-md'),
        setStyle('margin-top', '10px'),
        $lang->metric->verifyResult
    ),
);

dtable
(
    set::height(500),
    set::cols($resultHeader),
    set::data($resultData),
);

div
(
    setStyle('margin', '10px'),
);

center
(
    btnGroup
    (
        btn
        (
            set::type('primary'),
            set::url(helper::createLink('metric', 'implement', 'isVerify=true')),
            $lang->metric->verifyFile,
        ),
        div(),
        btn
        (
            set::type('primary'),
            set::url(helper::createLink('metric', 'publish')),
            $lang->metric->publish,
        ),
    )
);

render();
