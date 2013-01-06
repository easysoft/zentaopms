<?php
/**
 * The model file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class groupModel extends model
{
    /**
     * Create a group.
     * 
     * @access public
     * @return bool
     */
    public function create()
    {
        $group = fixer::input('post')->specialChars('name, desc')->get();
        return $this->dao->insert(TABLE_GROUP)->data($group)->batchCheck($this->config->group->create->requiredFields, 'notempty')->exec();
    }

    /**
     * Update a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function update($groupID)
    {
        $group = fixer::input('post')->specialChars('name, desc')->get();
        return $this->dao->update(TABLE_GROUP)->data($group)->batchCheck($this->config->group->edit->requiredFields, 'notempty')->where('id')->eq($groupID)->exec();
    }

    /**
     * Copy a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function copy($groupID)
    {
        $group = fixer::input('post')->specialChars('name, desc')->remove('options')->get();
        $this->dao->insert(TABLE_GROUP)->data($group)->check('name', 'unique')->check('name', 'notempty')->exec();
        if($this->post->options == false) return;
        if(!dao::isError())
        {
            $newGroupID = $this->dao->lastInsertID();
            $options    = join(',', $this->post->options);
            if(strpos($options, 'copyPriv') !== false) $this->copyPriv($groupID, $newGroupID);
            if(strpos($options, 'copyUser') !== false) $this->copyUser($groupID, $newGroupID);
        }
    }

    /**
     * Copy privileges.
     * 
     * @param  string    $fromGroup 
     * @param  string    $toGroup 
     * @access public
     * @return void
     */
    public function copyPriv($fromGroup, $toGroup)
    {
        $privs = $this->dao->findByGroup($fromGroup)->from(TABLE_GROUPPRIV)->fetchAll();
        foreach($privs as $priv)
        {
            $priv->group = $toGroup;
            $this->dao->insert(TABLE_GROUPPRIV)->data($priv)->exec();
        }
    }

    /**
     * Copy user.
     * 
     * @param  string    $fromGroup 
     * @param  string    $toGroup 
     * @access public
     * @return void
     */
    public function copyUser($fromGroup, $toGroup)
    {
        $users = $this->dao->findByGroup($fromGroup)->from(TABLE_USERGROUP)->fetchAll();
        foreach($users as $user)
        {
            $user->group = $toGroup;
            $this->dao->insert(TABLE_USERGROUP)->data($user)->exec();
        }
    }

    /**
     * Get group lists.
     * 
     * @param  int    $companyID 
     * @access public
     * @return array
     */
    public function getList($companyID)
    {
        return $this->dao->findByCompany($companyID)->from(TABLE_GROUP)->orderBy('id')->fetchAll();
    }

    /**
     * Get group pairs.
     * 
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->dao->findByCompany($this->app->company->id)->fields('id, name')->from(TABLE_GROUP)->fetchPairs();
    }

    /**
     * Get group by id.
     * 
     * @param  int    $groupID 
     * @access public
     * @return object
     */
    public function getByID($groupID)
    {
        return $this->dao->findById($groupID)->from(TABLE_GROUP)->fetch();
    }

    /**
     * Get privileges of a groups.
     * 
     * @param  int    $groupID 
     * @access public
     * @return array
     */
    public function getPrivs($groupID)
    {
        $privs = array();
        $stmt  = $this->dao->select('module, method')->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->orderBy('module')->query();
        while($priv = $stmt->fetch()) $privs[$priv->module][$priv->method] = $priv->method;
        return $privs;
    }

    /**
     * Get user pairs of a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return array
     */
    public function getUserPairs($groupID)
    {
        return $this->dao->select('t2.account, t2.realname')
            ->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('`group`')->eq((int)$groupID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.company')->eq($this->app->company->id)
            ->orderBy('t2.account')
            ->fetchPairs();
    }

    /**
     * Delete a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function delete($groupID)
    {
        $this->dao->delete()->from(TABLE_GROUP)->where('id')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->exec();
    }

    /**
     * Update privilege of a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return bool
     */
    public function updatePrivByGroup($groupID, $menu)
    {
        /* Delete old. */
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->andWhere('module')->in($this->getMenuModules($menu))->exec();

        /* Insert new. */
        foreach($this->post->actions as $moduleName => $moduleActions)
        {
            foreach($moduleActions as $actionName)
            {
                $data->group = $groupID;
                $data->module = $moduleName;
                $data->method = $actionName;
                $this->dao->insert(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }
        return true;
    }

    /**
     * Update privilege by module.
     * 
     * @access public
     * @return void
     */
    public function updatePrivByModule()
    {
        if($this->post->module == false or $this->post->actions == false or $this->post->groups == false) return false;

        foreach($this->post->actions as $action)
        {
            foreach($this->post->groups as $group)
            {
                $data->group  = $group;
                $data->module = $this->post->module;
                $data->method = $action;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }
        return true;
    }

    /**
     * Update users.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function updateUser($groupID)
    {
        /* Delete old. */
        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->exec();

        /* Insert new. */
        if($this->post->members == false) return;
        foreach($this->post->members as $account)
        {
            $data->account = $account;
            $data->group   = $groupID;
            $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
        }
    }
    
    /**
     * Sort resource.
     * 
     * @access public
     * @return void
     */
    public function sortResource()
    {
        $resources = $this->lang->resource;
        $this->lang->resource = new stdclass();

        /* sort moduleOrder. */
        ksort($this->lang->moduleOrder, SORT_ASC);
        foreach($this->lang->moduleOrder as $moduleName)
        {
            $resource = $resources->$moduleName;
            unset($resources->$moduleName);
            $this->lang->resource->$moduleName = $resource;
        }
        foreach($resources as $key => $resource)
        {
            $this->lang->resource->$key = $resource;
        }
        
        /* sort methodOrder. */
        foreach($this->lang->resource as $moduleName => $resources)
        {
            $resources    = (array)$resources;
            $tmpResources = new stdclass();

            if(isset($this->lang->$moduleName->methodOrder))
            {
                ksort($this->lang->$moduleName->methodOrder, SORT_ASC);
                foreach($this->lang->$moduleName->methodOrder as $key)
                {
                    if(isset($resources[$key]))
                    {
                        $tmpResources->$key = $resources[$key];
                        unset($resources[$key]);
                    }
                }
                if($resources)
                {
                    foreach($resources as $key => $resource)
                    {
                        $tmpResources->$key = $resource;
                    }
                }
                $this->lang->resource->$moduleName = $tmpResources;
                unset($tmpResources);
            }
        }
    }

    /**
     * Check menu have module 
     * 
     * @param  string    $menu 
     * @param  string    $moduleName 
     * @access public
     * @return void
     */
    public function checkMenuModule($menu, $moduleName)
    {
        if(empty($menu)) return true;
        if($menu == 'other' and (isset($this->lang->menugroup->$moduleName) or isset($this->lang->menu->$moduleName))) return false;
        if($menu != 'other' and !($moduleName == $menu or (isset($this->lang->menugroup->$moduleName) and $this->lang->menugroup->$moduleName == $menu))) return false;
        return true;
    }

    /**
     * Get modules in menu
     * 
     * @param  string    $menu 
     * @access public
     * @return void
     */
    public function getMenuModules($menu)
    {
        $modules = array();
        foreach($this->lang->resource as $moduleName => $action)
        {
            if($this->checkMenuModule($menu, $moduleName)) $modules[] = $moduleName;
        }
        return $modules;
    }
}
