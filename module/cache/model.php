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
    public function clear($needStart = true)
    {
        /* 先关闭缓存防止清空缓存过程中有新的数据写入。Close the cache first to prevent new data from being written during the cache clearing process. */
        $needStop = $this->config->cache->enable;
        if($needStop) $this->loadModel('setting')->setItem('system.common.cache.enable', 0);

        $this->mao->clearCache();

        if($needStop && $needStart) $this->loadModel('setting')->setItem('system.common.cache.enable', 1);
    }
}
