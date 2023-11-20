<?php
declare(strict_types=1);
/**
 * The trash view file of action module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang XuePeng <wangxuepeng@easycorp.ltd>
 * @package     tutorial
 * @link        https://www.zentao.net
 */
namespace zin;

div
(
    setID('start'),
    setClass('bg-primary'),
    div
    (
        setClass('start-icon'),
        icon('certificate icon-certificate-empty icon1 spin'),
        icon('flag icon2')
    ),
    h1($lang->tutorial->common),
    p($lang->tutorial->desc),
    a(
        setClass('btn btn-default btn-start-now btn-lg btn-info'),
        set::href($this->inlink('index')),
        set::target('_top'),
        span($lang->tutorial->start)
    )
);

render();
