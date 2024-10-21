<?php
declare(strict_types=1);
/**
 * The setting cache handler file.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class settingHandler
{
    /**
     * 获取系统及用户的配置信息。
     * Get system and personal config.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getSysAndPersonalConfig(string $account)
    {
        $owner = 'system,' . ($account ? $account : '');
        $records = $this->cache->select('*')->from(TABLE_CONFIG)
            ->where('owner')->in($owner)
            ->beginIF(!$this->app->upgrading)->andWhere('vision')->in(array('', $this->config->vision))->fi()
            ->orderBy('id')
            ->fetchAll('id');

        return $records;
    }
}
