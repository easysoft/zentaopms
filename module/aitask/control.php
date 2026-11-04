<?php
/**
 * The control file of aitask module of ZenTaoPMS.
 *
 * Open edition stub for cron. Paid editions use the biz extension module.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     aitask
 * @link        https://www.zentao.net
 */
class aitask extends control
{
    /**
     * Cron entry: execute AI teammate tasks.
     * Open edition stub. Paid editions override this in the biz extension.
     *
     * @param  int          $taskID
     * @param  string|bool  $force
     * @access public
     * @return void
     */
    public function exec(int $taskID = 0, $force = 'no')
    {
        echo 'success';
    }
}
