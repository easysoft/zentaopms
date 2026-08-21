<?php
declare(strict_types=1);
/**
 * The model file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.zentao.net
 * @property  cneModel $cne
 */
class systemModel extends model
{
    /**
     * 获取应用列表。
     * Get app list.
     *
     * @param  int    $productID
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(int $productID, string $status = 'active', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getSystemList();

        return $this->dao->select('*')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->beginIF($status && $status != 'all')->andWhere('status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 获取应用键值对。
     * Get app pairs.
     *
     * @param  int    $productID
     * @param  string $integrated
     * @param  string $status
     * @access public
     * @return array
     */
    public function getPairs(int $productID = 0, string $integrated = '', string $status = ''): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getSystemPairs();

        return $this->dao->select('id, name')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->beginIF($integrated !== '')->andWhere('integrated')->eq($integrated)->fi()
            ->orderBy('id DESC')
            ->fetchPairs('id', 'name');
    }

    /**
     * 根据产品ids列表获取状态正常的非集成应用键值对。
     * @param int[] $products
     * @return array
     */
    public function getPairsByProducts(array $products): array
    {
        $products = array_values(array_filter($products));
        return $this->dao->select('id, name')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->andWhere('status')->eq('active')
            ->andWhere('integrated')->eq(0)
            ->orderBy('id DESC')
            ->fetchPairs('id', 'name');
    }

    /**
     * 根据ID列表获取应用。
     * Get apps by id list.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function getByIdList(array $idList): array
    {
        return $this->dao->select('*')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->andWhere('id')->in($idList)
            ->fetchAll('id');
    }

    /**
     * 根据应用ID列表获取产品ID列表。
     * Get product id list by system id list.
     *
     * @param  array $systemIDs
     * @return array
     */
    public function getProductListBySystemIds(array $systemIDs)
    {
        return $this->dao->select('t1.id,t1.name as `appName`,t1.product,t2.name as productName')
            ->from(TABLE_SYSTEM)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.id')->in(implode(',', $systemIDs))
            ->fetchAll('id');
    }

    /**
     * 创建应用。
     * Create an app.
     *
     * @param  object $formData
     * @access public
     * @return bool|int
     */
    public function create(object $formData): bool|int
    {
        $this->dao->insert(TABLE_SYSTEM)->data($formData)
            ->check('name', 'unique')
            ->batchCheck($this->config->system->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError())
        {
            if($this->app->rawModule != 'system' && !empty(dao::$errors['name']))
            {
                dao::$errors['systemName'] = dao::$errors['name'];
                unset(dao::$errors['name']);
            }
            return false;
        }

        $systemID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('system', $systemID, 'created', '', '', zget($formData, 'createdBy', $this->app->user->account));
        return $systemID;
    }

    /**
     * 编辑应用。
     * Edit an app.
     *
     * @param  int    $id
     * @param  object $formData
     * @param  string $type
     * @access public
     * @return bool
     */
    public function update(int $id, object $formData, string $type = 'edit'): bool
    {
        $oldSystem = $this->fetchByID($id);
        $change    = common::createChanges($oldSystem, $formData);
        if(empty($change)) return true;

        $this->dao->update(TABLE_SYSTEM)->data($formData)
            ->check('name', 'unique', '`id` != ' . $id)
            ->autoCheck()
            ->beginIF($type == 'edit')->batchCheck($this->config->system->edit->requiredFields, 'notempty')->fi()
            ->where('id')->eq($id)
            ->exec();
        if(dao::isError()) return false;

        $actionType = $type == 'edit' ? 'edited' : $type;

        $actionID = $this->loadModel('action')->create('system', $id, $actionType);
        if($actionID) $this->action->logHistory($actionID, $change);
        return !dao::isError();
    }

    /**
     * 检查按钮是否可用。
     * Check if the button is clickable.
     *
     * @param  object $system
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $system, string $action): bool
    {
        if($action == 'active') return $system->status == 'inactive';
        if($action == 'inactive') return $system->status == 'active';
        return true;
    }

    /**
     * 更新应用的最新发布信息。
     * Update the latest release of the app.
     *
     * @param  int    $systemID
     * @param  int    $releaseID
     * @param  string $releasedDate
     * @access public
     * @return bool
     */
    public function setSystemRelease(int $systemID, int $releaseID, string $releasedDate = ''): bool
    {
        $system = $this->fetchByID($systemID);
        if(!$system) return false;

        if(empty($releasedDate))
        {
            if($releaseID != $system->latestRelease) return false;

            $release      = $this->dao->select('id,`createdDate`')->from(TABLE_RELEASE)->where('deleted')->eq(0)->andWhere('system')->eq($systemID)->orderBy('id DESC')->fetch();
            $releaseID    = $release ? $release->id : 0;
            $releasedDate = $release ? $release->createdDate : null;
        }

        $this->dao->update(TABLE_SYSTEM)->set('latestDate')->eq($releasedDate)->set('latestRelease')->eq($releaseID)->where('id')->eq($systemID)->exec();
        return !dao::isError();
    }

    /**
     * 根据ID获取发布信息。
     * Get release information by ID.
     *
     * @param  int $systemID
     * @access public
     * @return array
     */
    public function getReleasesByID(int $systemID): array
    {
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('system')->eq($systemID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 根据ID获取构建信息。
     * Get build information by ID.
     *
     * @param  int $systemID
     * @access public
     * @return array
     */
    public function getBuildsByID(int $systemID): array
    {
        return $this->dao->select('*')->from(TABLE_BUILD)
            ->where('system')->eq($systemID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 初始化应用。
     * Initialize application.
     *
     * @access public
     * @return bool
     */
    public function initSystem(): bool
    {
        $productPairs = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchPairs('id', 'name');
        $releasePairs = $this->dao->select('id,product,date,`createdDate`')->from(TABLE_RELEASE)->where('deleted')->eq('0')->fetchAll('product');

        $systemPairs = array();
        $systemNames = array();
        $systemList  = $this->dao->select('id,product,name')->from(TABLE_SYSTEM)->where('deleted')->eq('0')->fetchAll();
        foreach($systemList as $system)
        {
            $system->name = strtolower($system->name);
            $systemNames[$system->name]    = $system->id;
            $systemPairs[$system->product] = $system->id;
        }

        $system = new stdclass();
        $system->createdDate = helper::now();
        $system->createdBy   = 'system';
        foreach($productPairs as $productID => $productName)
        {
            $systemID = zget($systemPairs, $productID, 0);
            if(!$systemID)
            {
                if(isset($systemNames[strtolower($productName)])) $productName .= '-1';

                $system->name          = $productName;
                $system->product       = $productID;
                $system->latestDate    = null;
                $system->latestRelease = 0;
                if(isset($releasePairs[$productID]))
                {
                    $system->latestDate    = $releasePairs[$productID]->createdDate ? $releasePairs[$productID]->createdDate : "{$releasePairs[$productID]->date} 00:00:00";
                    $system->latestRelease = $releasePairs[$productID]->id;
                }
                $systemID = $this->create($system);

                if(dao::isError()) continue;
            }

            $this->dao->update(TABLE_BUILD)->set('system')->eq($systemID)->where('product')->eq($productID)->andWhere('system')->eq(0)->exec();
            $this->dao->update(TABLE_RELEASE)->set('system')->eq($systemID)->where('product')->eq($productID)->andWhere('system')->eq(0)->exec();
        }

        if(!dao::isError()) $this->dao->delete()->from(TABLE_CRON)->where('command')->eq('moduleName=system&methodName=initSystem')->exec();

        return dao::isError();
    }

    /**
     * 根据ID获取应用信息。
     * Get app info by id.
     *
     * @param  int $systemID
     * @access public
     * @return object
     */
    public function getByID(int $systemID): object
    {
        return $this->fetchByID($systemID);
    }
}
