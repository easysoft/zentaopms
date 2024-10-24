<?php
declare(strict_types=1);
/**
 * The cache handler file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     cache
 * @link        https://www.zentao.net
 */
class bugHandler
{
    /**
     * 获取指派给某人的bug的缓存键名。
     * Get the cache key name of bugs assigned to someone.
     *
     * @param  string $account
     * @access public
     * @return string
     */
    public function assignedTo(string $account): string
    {
        return 'res:bug:assignedTo:' . $account;
    }
}
