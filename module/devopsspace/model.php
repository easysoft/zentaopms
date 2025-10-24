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
            ->andWhere('(t2.account')->eq($account)
            ->orWhere('t1.owner')->eq($account)
            ->markRight()
            ->fetchAll('id');
    }

    /**
     * 获取空间列表键值对。
     * Get space list pairs.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getPairs(string $account = ''): array
    {
        $userSpaces = $this->getListByAccount($account);
        if(!$this->app->user->admin && empty($userSpaces)) return array();

        return $this->dao->select('id, name')->from(TABLE_DEVOPSSPACE)
            ->where('deleted')->eq(0)
            ->beginIf(!empty($userSpaces))->andWhere('(id')->in(array_keys($userSpaces))
            ->orWhere('owner')->eq($account)
            ->markRight()
            ->fi()
            ->fetchPairs('id');
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
        $userSpaces = $this->app->user->admin ? array() : $this->getListByAccount($this->app->user->account);
        return $this->dao->select('*, `desc`')->from(TABLE_DEVOPSSPACE)
            ->where('deleted')->eq(0)
            ->beginIf(!empty($userSpaces))->andWhere('id')->in(array_keys($userSpaces))->fi()
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 创建空间。
     * Create space.
     *
     * @param  object $formData
     * @access public
     * @return int|bool
     */
    public function create(object $formData): int|bool
    {
        $team = empty($formData->team) ? array() : explode(',', $formData->team);

        unset($formData->team);
        $this->dao->insert(TABLE_DEVOPSSPACE)->data($formData)
            ->check('name', 'unique')
            ->batchCheck($this->config->devopsspace->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $spaceID = $this->dao->lastInsertID();
        if(!empty($team))
        {
            foreach($team as $account) $this->dao->insert(TABLE_DEVOPSSPACEUSER)->data(array('space' => $spaceID, 'account' => $account))->exec();
            if(dao::isError()) return false;
        }

        return $spaceID;
    }

    /**
     * 通过空间ID获取空间信息。
     * Get space info by space ID.
     *
     * @param  int $spaceID
     * @access public
     * @return array|object
     */
    public function getByID(int $spaceID): array|object
    {
        $space = $this->dao->select('*')->from(TABLE_DEVOPSSPACE)->where('id')->eq($spaceID)->fetch();
        if(empty($space)) return array();

        $team = $this->dao->select('account')->from(TABLE_DEVOPSSPACEUSER)->where('space')->eq($spaceID)->fetchAll('account');
        $space->team = empty($team) ? array() : array_keys($team);
        return $space;
    }

    /**
     * 更新空间。
     * Update space.
     *
     * @param  object $space
     * @param  object $formData
     * @access public
     * @return bool|array
     */
    public function update(object $space, object $formData): false|array
    {
        $newTeam = empty($formData->team) ? array() : explode(',', $formData->team);
        unset($formData->team);

        $this->dao->update(TABLE_DEVOPSSPACE)->data($formData)
            ->check('name', 'unique', "`id` != '{$space->id}'")
            ->batchCheck($this->config->devopsspace->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($space->id)
            ->exec();
        if(dao::isError()) return false;

        if(array_intersect($newTeam, $space->team))
        {
            $this->dao->delete()->from(TABLE_DEVOPSSPACEUSER)->where('space')->eq($space->id)->exec();
            foreach($newTeam as $account) $this->dao->insert(TABLE_DEVOPSSPACEUSER)->data(array('space' => $space->id, 'account' => $account))->exec();
            if(dao::isError()) return false;

            $formData->team = implode(',', $newTeam);
            $space->team    = implode(',', $space->team);
        }

        return common::createChanges($space, $formData);
    }
}
