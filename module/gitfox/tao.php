<?php
declare(strict_types=1);
/**
 * The tao file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     gitfox
 * @link        https://www.zentao.net
 */
class gitfoxTao extends gitfoxModel
{
    /**
     * 保存同步日志。
     * Save sync log.
     *
     * @param  int    $id
     * @param  bool   $res
     * @param  int    $times
     * @access public
     * @return bool
     */
    public function saveSyncResult(int $id, bool $res, int $times): bool
    {
        if(!$res && $times >= 200) return true;

        $this->dao->update(TABLE_GITFOXSYNCLOG)
            ->set('times')->eq($times + 1)
            ->set('lastSync')->eq(date('Y-m-d H:i:s'))
            ->beginIF($res)->set('status')->eq('synced')->fi()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }
}
