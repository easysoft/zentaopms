<?php
declare(strict_types=1);
/**
 * The model file of space module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   space
 * @link      https://www.zentao.net
 */
class spaceModel extends model
{
    /**
     * 获取用户的空间列表。
     * Get space list by user account.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getSpacesByAccount(string $account): array
    {
        return $this->dao->select('*')->from(TABLE_SPACE)
            ->where('deleted')->eq(0)
            ->andWhere('owner')->eq($account)
            ->fetchAll();
    }

    /**
     * 获取用户的默认空间。
     * Get user's default space by user account.
     *
     * @param  string $account
     * @access public
     * @return object
     */
    public function defaultSpace(string $account): ?object
    {
        $default = $this->dao->select('*')->from(TABLE_SPACE)
            ->where('deleted')->eq(0)
            ->andWhere('owner')->eq($account)
            ->orderBy('default desc')
            ->fetch();

        if(empty($default)) return $this->createDefaultSpace($account);
        return $default;
    }

    /**
     * 获取用户的系统空间。
     * Get system space.
     *
     * @param  string $account
     * @access public
     * @return object
     */
    public function getSystemSpace(string $account): ?object
    {
        if(empty($account)) $account = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq(0)->fetch('account');

        $sysSpace = $this->dao->select('*')->from(TABLE_SPACE)
            ->where('k8space')->eq($this->config->k8space)
            ->andWhere('owner')->eq($account)
            ->andWhere('deleted')->eq(0)
            ->fetch();
        if($sysSpace) return $sysSpace;

        $spaceData = new stdClass;
        $spaceData->name      = $this->lang->space->systemSpace;
        $spaceData->owner     = $account;
        $spaceData->k8space   = $this->config->k8space;
        $spaceData->default   = 0;
        $spaceData->createdAt = date('Y-m-d H:i:s');
        $this->dao->insert(TABLE_SPACE)->data($spaceData)->autoCheck()->exec();

        return $this->fetchByID($this->dao->lastInsertId());
    }

    /**
     * 创建默认空间。
     * Create default space by account
     *
     * @param  string $account
     * @access public
     * @return object
     */
    public function createDefaultSpace(string $account): ?object
    {
        if(empty($account)) $account = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq(0)->fetch('account');

        $default = new stdclass;
        $default->name      = $this->lang->space->defaultSpace;
        $default->k8space   = 'quickon-app';
        $default->owner     = $account;
        $default->default   = true;
        $default->createdAt = date('Y-m-d H:i:s');
        $this->dao->insert(TABLE_SPACE)->data($default)->autoCheck()->exec();

        return $this->fetchByID($this->dao->lastInsertId());
    }

    /**
     * 获取用户空间的应用列表。
     * Get app list in space by space id.
     *
     * @param  int    $spaceID
     * @param  string $status
     * @param  string $searchName
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getSpaceInstances(int $spaceID, string $status = 'all', string $searchName = '', object $pager = null): array
    {
        $instances = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->beginIF($spaceID)->andWhere('space')->eq($spaceID)->fi()
            ->beginIF($status !== 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF(!empty($searchName))->andWhere('name')->like("%{$searchName}%")->fi()
            ->orderBy('id desc')->page($pager)->fetchAll('id');

        $this->loadModel('store');
        foreach($instances as $instance) $instance->latestVersion = $this->store->appLatestVersion($instance->appID, $instance->version);

        $solutionIDList = helper::arrayColumn($instances, 'solution');
        $solutions      = $this->dao->select('*')->from(TABLE_SOLUTION)->where('id')->in($solutionIDList)->fetchAll('id');
        foreach($instances as $instance) $instance->solutionData = zget($solutions, $instance->solution, new stdclass);
        return $instances;
    }

    /**
     * 根据ID获取空间。
     * Get space by id.
     *
     * @param  int    $spaceID
     * @access public
     * @return object
     */
    public function getByID(int $spaceID): ?object
    {
        return $this->dao->select('*')->from(TABLE_SPACE)
            ->where('deleted')->eq(0)
            ->andWhere('id')->eq($spaceID)
            ->andWhere('owner')->eq($this->app->user->account)
            ->fetch();
    }

    /**
     * 获取应用市场应用对应的外部应用。
     * Get External app By store app.
     *
     * @param  object $instance
     * @access public
     * @return object|false
     */
    public function getExternalAppByApp(object $instance): object|false
    {
        return $this->dao->select('*')->from(TABLE_PIPELINE)
            ->where('deleted')->eq('0')
            ->andWhere('createdBy')->eq('system')
            ->andWhere('url')->like("%{$instance->domain}%")
            ->fetch();
    }
}
