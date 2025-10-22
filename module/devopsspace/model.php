<?php
declare(strict_types=1);
/**
 * The model file of devopsspace module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     repo
 * @property    repoTao $repoTao
 * @link        https://www.zentao.net
 */
class devopsspaceModel extends model
{
    /**
     * 通过用户账号获取空间列表。
     * Get space list by user account.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getListByAccount(string $account): array
    {
        return $this->dao->select('t1.*, t2.account as account')->from(TABLE_DEVOPSSPACE)->alias('t1')
            ->leftJoin(TABLE_DEVOPSSPACEUSER)->alias('t2')
            ->on('t1.id=t2.space')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.account')->eq($account)
            ->fetchAll('id');
    }

    /**
     * 获取空间列表。
     * Get space list.
     *
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getList($pager = null): array
    {
        return $this->dao->select('t1.*')->from(TABLE_DEVOPSSPACE)->alias('t1')
            ->leftJoin(TABLE_DEVOPSSPACEUSER)->alias('t2')
            ->on('t1.id=t2.space')
            ->where('t1.deleted')->eq(0)
            ->beginIf(!$this->app->user->admin)->andWhere('t2.account')->ne($this->app->user->account)->fi()
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll('id');
    }
}
