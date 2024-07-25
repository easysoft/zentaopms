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

h::iframe
(
    set('width', '100%'),
    set('height', '100%'),
    set('scrolling', 'no'),
    set('frameborder', '0'),
    set('marginheight', '0'),
    set('src', $this->inlink('viewOld', "screenID={$screenID}&year={$year}&month={$month}&dept={$dept}&account={$account}"))
);
