<?php
/**
 * The model file of ops module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     ops
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class hostModel extends model
{
    /**
     * Get host by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getById($id)
    {
        $host = $this->dao->select('*,id as hostID')->from(TABLE_HOST)
            ->where('id')->eq($id)
            ->fetch();

        return $host;
    }

    /**
     * Get hosts by id list.
     *
     * @param  int    $idList
     * @access public
     * @return array
     */
    public function getByIdList($idList)
    {
        return $this->dao->select('*,id as hostID')->from(TABLE_HOST)
            ->where('id')->in($idList)
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');
    }

    /**
     * Get host list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($browseType = 'all', $param = 0, $orderBy = 'id_desc', $pager = null)
    {
        $query   = '';
        $modules = '';
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
        elseif($browseType == 'bymodule')
        {
            $modules = $param ? $this->loadModel('tree')->getAllChildId($param) : '0';
        }

        $orderBy = str_replace('t1.', '', $orderBy);
        $host = $this->dao->select('*,id as hostID')->from(TABLE_HOST)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('normal')
            ->beginIF($modules)->andWhere('`group`')->in($modules)->fi()
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        return $host;
    }

    /**
     * Get pairs by services.
     *
     * @param  string $services
     * @access public
     * @return array
     */
    public function getPairsByService($services)
    {
        $servers = $this->dao->select('id,hosts')->from(TABLE_SERVICE)->where('id')->in($services)->andWhere('hosts')->ne('')->fetchPairs('id', 'hosts');
        $hostIdList = array_unique(explode(',', join(',', $servers)));

        return $this->dao->select('id,name')->from(TABLE_HOST)
            ->where('deleted')->eq('0')
            ->andWhere('`id`')->in($hostIdList)
            ->andWhere('type')->eq('normal')
            ->orderBy('`group`')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get pairs.
     *
     * @param  string  $moduleIdList
     * @param  string  $idFrom
     * @access public
     * @return array
     */
    public function getPairs($moduleIdList = 0)
    {
        $modules = array();
        if($moduleIdList)
        {
            $this->loadModel('tree');
            foreach(explode(',', $moduleIdList) as $moduleID)
            {
                if(empty($moduleID)) continue;
                $modules += $this->tree->getAllChildId($moduleID);
            }
        }

        return $this->dao->select("id,name")->from(TABLE_HOST)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('normal')
            ->beginIF($modules)->andWhere('`group`')->in($modules)->fi()
            ->orderBy('`group`')
            ->fetchPairs('id', 'name');
    }

    /**
     * Create a host.
     *
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $hostInfo = fixer::input('post')
            ->setDefault('hardwareType', 'server')
            ->setDefault('cpuNumber,cpuCores,diskSize,memory', 0)
            ->get();

        $hostInfo->admin      = intval($hostInfo->admin);
        $hostInfo->serverRoom = intval($hostInfo->serverRoom);
        $this->dao->update(TABLE_HOST)->data($hostInfo)
            ->batchCheck($this->config->host->create->requiredFields, 'notempty')
            ->batchCheck('diskSize,memory', 'float');
        if(dao::isError()) return false;

        $intFields = explode(',', $this->config->host->create->intFields);
        foreach($intFields as $field)
        {
            if(!preg_match("/^-?\d+$/", $hostInfo->{$field}))
            {
                dao::$errors[$field] = sprintf($this->lang->host->notice->int, $this->lang->host->{$field});
                return false;
            }
        }

        $ipFields = explode(',', $this->config->host->create->ipFields);
        foreach($ipFields as $field)
        {
            if(!preg_match('/((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3}/', $hostInfo->{$field}))
            {
                dao::$errors[$field] = sprintf($this->lang->host->notice->ip, $this->lang->host->{$field});
                return false;
            }
        }

        $hostInfo->type        = 'normal';
        $hostInfo->createdBy   = $this->app->user->account;
        $hostInfo->createdDate = helper::now();
        $this->dao->insert(TABLE_HOST)->data($hostInfo)->autoCheck()->exec();
        if(!dao::isError())
        {
            $hostID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('host', $hostID, 'created');
            return true;
        }

        return false;
    }

    /**
     * Update a host.
     *
     * @param  int    $id
     * @param  int    $hostID
     * @access public
     * @return array|bool
     */
    public function update($id)
    {
        $oldHost  = $this->getById($id);
        $hostInfo = fixer::input('post')
            ->setDefault('hardwareType', 'server')
            ->setDefault('cpuNumber,cpuCores,diskSize,memory', 0)
            ->get();

        $hostInfo->admin      = intval($hostInfo->admin);
        $hostInfo->serverRoom = intval($hostInfo->serverRoom);

        $this->dao->update(TABLE_HOST)->data($hostInfo)
            ->batchCheck($this->config->host->create->requiredFields, 'notempty')
            ->batchCheck('diskSize,memory', 'float');
        if(dao::isError()) return false;

        $intFields = explode(',', $this->config->host->create->intFields);
        foreach($intFields as $field)
        {
            if(!preg_match("/^-?\d+$/", $hostInfo->{$field}))
            {
                dao::$errors[$field] = sprintf($this->lang->host->notice->int, $this->lang->host->{$field});
                return false;
            }
        }

        $ipFields = explode(',', $this->config->host->create->ipFields);
        foreach($ipFields as $field)
        {
            if(!preg_match('/((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3}/', $hostInfo->{$field}))
            {
                dao::$errors[$field] = sprintf($this->lang->host->notice->ip, $this->lang->host->{$field});
                return false;
            }
        }

        $hostInfo->editedBy   = $this->app->user->account;
        $hostInfo->editedDate = helper::now();
        $this->dao->update(TABLE_HOST)->data($hostInfo)->autoCheck()->where('id')->eq($id)->exec();
        return common::createChanges($oldHost, $hostInfo);
    }

    /**
     * Update a host status.
     *
     * @param  int    $hostID
     * @param  int    $status
     * @access public
     * @return void
     */
    public function updateStatus($hostID, $status)
    {
        $this->dao->update(TABLE_HOST)->set('status')->eq($status)->where('id')->eq($hostID)->exec();
    }

    /**
     * Get tree map of server room.
     *
     * @access public
     * @return string
     */
    public function getServerroomTreemap()
    {
        /* Get host list. */
        $this->app->loadLang('serverroom');
        $stmt = $this->dao->select('t1.id,t1.name,t3.id as roomID,t3.city,t3.name as roomName,t1.extranet')->from(TABLE_HOST)->alias('t1')
            ->leftJoin(TABLE_SERVERROOM)->alias('t3')->on('t1.serverRoom=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->eq('normal')
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t1.serverRoom')->ne(0)
            ->orderBy('t3.city,t3.id,t1.id')
            ->query();

        /* Group host by city and server room. */
        $hostGroup = array();
        while($host = $stmt->fetch())
        {
            $hostGroup[$host->city][$host->roomID][] = $host;
        }
        $treeMap = array();
        foreach($hostGroup as $city => $rooms)
        {
            $children = array();
            $children['text']      = zget($this->lang->serverroom->cityList, $city);
            $children['collapsed'] = false;
            $children['children']  = array();

            foreach($rooms as $roomID => $cabinets)
            {
                $host = reset($cabinets);
                if(is_array($host)) $host = reset($host);

                $subChildren = array();
                $subChildren['text']      = htmlspecialchars($host->roomName);
                $subChildren['collapsed'] = false;
                $subChildren['children']  = array();

                foreach($cabinets as $cabinet => $hosts)
                {
                    if(is_array($hosts))
                    {
                        $hostNameList = array();
                        foreach($hosts as $host) $hostNameList[] = array('text' => htmlspecialchars($host->name), 'hostid' => $host->id);
                        $subChildren['children'][] = array(
                            'text'      => htmlspecialchars($cabinet),
                            'collapsed' => false,
                            'children'  => $hostNameList
                        );
                    }
                    else
                    {
                        $subChildren['children'][] = array('text' => htmlspecialchars($hosts->name), 'hostid' => $hosts->id);
                    }
                }
                $children['children'][] = $subChildren;
            }

            $treeMap[] = $children;
        }
        return $treeMap;
    }

    /**
     * Get tree map by group.
     *
     * @access public
     * @return string
     */
    public function getGroupTreemap()
    {
        /* Get host list by group. */
        $this->app->loadLang('serverroom');
        $hostGroups = $this->dao->select('id,name,`group`,extranet')->from(TABLE_HOST)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('normal')
            ->fetchGroup('group', 'id');

        /* Get module list by host group. */
        $modules = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in(array_keys($hostGroups))->fetchAll();
        $paths   = array();
        foreach($modules as $module)
        {
            foreach(explode(',', trim($module->path)) as $path) $paths[$path] = $path;
        }
        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('id')->in($paths)
            ->orderBy('grade_desc,`order`,id')
            ->fetchAll();

        $treemap = array();
        foreach($modules as $module)
        {
            if(!isset($treemap[$module->parent])) $treemap[$module->parent] = array('text' => '', 'collapsed' => false, 'children' => array());
            $treemap[$module->parent]['text'] = htmlspecialchars($module->name);
            if(isset($treemap[$module->id]))
            {
                $treemap[$module->parent]['children'][] = $treemap[$module->id];
                unset($treemap[$module->id]);
            }

            if(isset($hostGroups[$module->id]))
            {
                $treemap[$module->id] = array('text' => $module->name, 'collapsed' => false, 'children' => array());
                foreach($hostGroups[$module->id] as $host)
                {
                    $treemap[$module->id]['children'][] = array('text' => htmlspecialchars($host->name), 'hostid' => $host->id);
                }
            }
        }

        if(isset($treemap[0]) or isset($hostGroups[0]))
        {
            if(isset($treemap[0])) $groupTree = $treemap[0];

            if(isset($hostGroups[0]))
            {
                foreach($hostGroups[0] as $host) $groupTree['children'][] = array('text' => htmlspecialchars($host->name), 'hostid' => $host->id);
            }
            $treemap[0] = $groupTree;
        }

        $treeData['text']      = '/';
        $treeData['collapsed'] = false;
        $treeData['children']  = array_values($treemap);
        return $treeData;
    }

    /**
     * 判断列表页操作按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param object $host
     * @param string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($host, $action)
    {
        if(!$host->id)                                return false;
        if (!common::hasPriv('host', 'changeStatus')) return false;

        if($host->status == 'online'  && $action == 'online')  return false;
        if($host->status == 'offline' && $action == 'offline') return false;

        return true;
    }
}
