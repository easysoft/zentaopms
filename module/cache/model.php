<?php
declare(strict_types=1);
/**
 * The model file of cache module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     cache
 * @link        https://www.zentao.net
 */
class cacheModel extends model
{
    /**
     * 清空缓存。
     * Clear the cache.
     *
     * @access public
     * @return void
     */
    public function clear()
    {
        /* Redis 采用遍历删除的方式，所以需要先关闭缓存，清空之后再打开。Redis uses the method of traversing deletion, so you need to turn off the cache first, clear it, and then turn it on. */
        $needStop = $this->config->cache->driver == 'redis';
        if($needStop) $this->loadModel('setting')->setItem('system.common.cache.enable', 0);
        $this->mao->clearCache();
        if($needStop) $this->setting->setItem('system.common.cache.enable', 1);
    }
}
