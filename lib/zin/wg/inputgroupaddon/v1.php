<?php
declare(strict_types=1);
/**
 * The inputGroupAddon widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 输入框附加部分（inputGroupAddon）部件类。
 * The inputGroupAddon widget class.
 *
 * @author Hao Sun
 */
class inputGroupAddon extends wg
{
    protected function build(): wg
    {
        return h::span(setClass('input-group-addon'), set($this->props), $this->children());
    }
}
