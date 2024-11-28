<?php
declare(strict_types=1);
/**
 * The zen file of misc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     misc
 * @link        https://www.zentao.net
 */
class miscZen extends misc
{
    /**
     * 打印 hello world。
     * print hello world.
     *
     * @access public
     * @return string
     */
    public function hello(): string
    {
        return 'hello world from hello()<br />';
    }
}
