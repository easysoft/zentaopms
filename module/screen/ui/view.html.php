<?php
declare(strict_types = 1);
/**
 * The browse view file of screen module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     screen
 * @link        https://www.zentao.net
 */
namespace zin;

h::importJs($this->app->getWebRoot() . 'static/js/index.js');

jsVar('screen', $screen);
jsVar('dept', $dept);
jsVar('account', $account);

div
(
    setID('appProvider'),
    setStyle('display: none'),

);
div
(
    setID('app'),
    div
    (
        setClass('first-loading-wrp'),
        div
        (
            setClass('loading-wrp'),
            span
            (
                setClass('dot dot-spin'),
            ),
        ),
    ),
);
