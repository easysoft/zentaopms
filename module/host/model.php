<?php
/**
 * The model file of host module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     module
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class hostModel extends model
{
    /**
     * 获取主机列表。
     * Get host list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        $browseType = strtolower($browseType);

        $query = '';
        if($browseType == 'bysearch')
        {
            /* Concatenate the conditions for the query. */
            if($param)
            {
                $query = $this->loadModel('search')->getQuery($param);
                if($query)
                {
                    $this->session->set('hostQuery', $query->sql);
                    $this->session->set('hostForm', $query->form);
                }
                else
                {
                    $this->session->set('hostQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->hostQuery == false) $this->session->set('hostQuery', ' 1 = 1');
            }
            $query = $this->session->hostQuery;
        }

        $modules = 0;
        if($browseType == 'bymodule' && $param) $modules = $this->loadModel('tree')->getAllChildId($param);

        return $this->dao->select('*')->from(TABLE_HOST)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('normal')
            ->beginIF($modules)->andWhere('`group`')->in($modules)->fi()
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 创建主机。
     * create a host.
     *
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function create(object $formData): bool
    {
        $this->dao->insert(TABLE_HOST)->data($formData)->batchCheck($this->config->host->create->requiredFields, 'notempty')->autoCheck()->exec();
        if(dao::isError()) return false;

        $hostID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('host', $hostID, 'created');

        return !dao::isError();
    }

    /**
     * 更新主机。
     * Update a host.
     *
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function update(object $formData): bool
    {
        $oldHost = $this->fetchByID($formData->id);
        $this->dao->update(TABLE_HOST)->data($formData)->batchCheck($this->config->host->create->requiredFields, 'notempty')->autoCheck()->where('id')->eq($formData->id)->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldHost, $formData);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('host', $formData->id, 'Edited');
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 改变主机的状态。
     * Update a host status.
     *
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function updateStatus(object $formData): bool
    {
        $this->dao->update(TABLE_HOST)->data($formData, 'reason')->where('id')->eq($formData->id)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('host', $formData->id, $formData->status, $formData->reason);

        return !dao::isError();
    }

    /**
     * 获取物理拓扑图所需的数据结构。
     * Get tree map of server room.
     *
     * @access public
     * @return array
     */
    public function getServerroomTreemap(): array
    {
        $this->app->loadLang('serverroom');

        /* Get host list. */
        $stmt = $this->dao->select('t1.id,t1.name,t2.id as roomID,t2.city,t2.name as roomName,t1.extranet')->from(TABLE_HOST)->alias('t1')
            ->leftJoin(TABLE_SERVERROOM)->alias('t2')->on('t1.serverRoom=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->eq('normal')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.serverRoom')->ne(0)
            ->orderBy('t2.city,t2.id,t1.id')
            ->query();

        /* Group host by city and server room. */
        $hostGroup = array();
        while($host = $stmt->fetch()) $hostGroup[$host->city][$host->roomID][] = $host;

        $treeMap = $this->processTreemap($hostGroup);
        return $treeMap;
    }

    /**
     * 获取分组拓扑图所需的数据结构。
     * Get tree map by group.
     *
     * @access public
     * @return array
     */
    public function getGroupTreemap(): array
    {
        $this->app->loadLang('serverroom');

        /* Get host list by group. */
        $hosts   = $this->dao->select('id,name,`group`,extranet')->from(TABLE_HOST)->where('deleted')->eq(0)->andWhere('type')->eq('normal')->fetchGroup('group', 'id');
        $modules = $this->getTreeModules(0, $hosts);

        $treemap = array();
        $treemap['text']      = '/';
        $treemap['collapsed'] = false;
        $treemap['children']  = $this->processTreemap($modules);
        return $treemap;
    }

    /**
     * 判断操作按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $host
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable(object $host, string $action): bool
    {
        if(!$host->id) return false;

        if($action == 'online')  return $host->status != 'online';
        if($action == 'offline') return $host->status != 'offline';
        if($action == 'delete')  return $host->deleted == '0';
        if($action == 'edit')    return $host->deleted == '0';

        return true;
    }

    /**
     * 处理成树形图需要的数据结构。
     * Process to treemap data.
     *
     * @param  array   $datas
     * @access private
     * @return array
     */
    private function processTreemap(array $datas): array
    {
        $treeMap = array();
        foreach($datas as $key => $data)
        {
            $text = '';
            $host = is_array($data) ? reset($data) : $data;
            if(is_array($host))  $text = zget($this->lang->serverroom->cityList, $key);
            if(is_object($host)) $text = is_array($data) ? $host->roomName : $host->name;

            $children = array();
            $children['text'] = htmlspecialchars($text);
            if(is_array($data))
            {
                $children['collapsed'] = false;
                $children['children']  = $this->processTreemap($data);
            }
            elseif(!empty($data->children))
            {
                $children['collapsed'] = false;
                $children['children']  = $this->processTreemap($data->children);
            }
            else
            {
                $children['hostid'] = $data->id;
            }

            $treeMap[] = $children;
        }

        return $treeMap;
    }

    /**
     * 获取树状结构的模块数据。
     * Get tree modules.
     *
     * @param  int     $rootID
     * @param  array   $hosts
     * @access private
     * @return array
     */
    private function getTreeModules(int $rootID, array $hosts): array
    {
        $treemap = array();
        $modules = $this->dao->select('*')->from(TABLE_MODULE)->where('parent')->eq($rootID)->andWhere('type')->eq('host')->orderBy('`order`,id')->fetchAll();
        foreach($modules as $module)
        {
            $module->children = $this->getTreeModules($module->id, $hosts);
            $treemap[] = $module;
        }
        if(!empty($hosts[$rootID])) $treemap = array_merge($treemap, $hosts[$rootID]);

        return $treemap;
    }
}
