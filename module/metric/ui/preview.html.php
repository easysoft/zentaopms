<?php
declare(strict_types=1);
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($scope),
    set::linkParams("scope={key}"),
);

div
(
    setClass('side'),
    div
    (
        setClass('canvas'),
        div
        (
            setClass('metric-name flex items-center'),
            span
            (
                setClass('name-color'),
                $metricList
            ),
        ),
        div
        (
            setClass('flex-none w-2/3'),
        ),
    ),
);
div
(
    setClass('main'),
    div
    (
        setClass('canvas flex p-4'),
    ),
);
render();
