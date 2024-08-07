<?php
declare(strict_types=1);
/**
 * The yyy view file of xxx module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     xxx
 * @link        https://www.zentao.net
 */
namespace zin;

h::iframe
(
    set::width('100%'),
    set::id('iframe-instruction'),
    set::frameborder(0),
    set::src('https://www.zentao.net/book/zentaopms/978.html?fullScreen=zentao&theme=default'),
    on::init()->call('window.getIframeHeight')
);
