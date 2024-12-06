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
        /* 多应用共享时采用遍历删除的方式，所以需要先关闭缓存，清空之后再打开。When multiple applications share the cache, the cache needs to be closed first, cleared, and then opened. */
        $needStop = $this->config->cache->enable && $this->config->cache->scope == 'shared';
        if($needStop) $this->loadModel('setting')->setItem('system.common.cache.enable', 0);

        $this->mao->clearCache();

        if($needStop && $needStart) $this->loadModel('setting')->setItem('system.common.cache.enable', 1);
    }
}
