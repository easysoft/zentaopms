<?php
/**
 * The model file of group module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class groupModel extends model
{
    /* 为某一个公司添加分组。*/
    public function create()
    {
        $group = fixer::input('post')->specialChars('name, desc')->get();
        return $this->dao->insert(TABLE_GROUP)->data($group)->batchCheck($this->config->group->create->requiredFields, 'notempty')->exec();
    }

    /* 更新某一个分组信息。*/
    public function update($groupID)
    {
        $group = fixer::input('post')->specialChars('name, desc')->get();
        return $this->dao->update(TABLE_GROUP)->data($group)->batchCheck($this->config->group->edit->requiredFields, 'notempty')->where('id')->eq($groupID)->exec();
        return $this->dbh->exec($sql);
    }

    /* 复制一个分组。*/
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

    /* 拷贝权限。*/
    private function copyPriv($fromGroup, $toGroup)
    {
        $privs = $this->dao->findByGroup($fromGroup)->from(TABLE_GROUPPRIV)->fetchAll();
        foreach($privs as $priv)
        {
            $priv->group = $toGroup;
            $this->dao->insert(TABLE_GROUPPRIV)->data($priv)->exec();
        }
    }

    /* 拷贝用户。*/
    private function copyUser($fromGroup, $toGroup)
    {
        $users = $this->dao->findByGroup($fromGroup)->from(TABLE_USERGROUP)->fetchAll();
        foreach($users as $user)
        {
            $user->group = $toGroup;
            $this->dao->insert(TABLE_USERGROUP)->data($user)->exec();
        }
    }

    /* 获取某一个公司的分组列表。*/
    public function getList($companyID)
    {
        return $this->dao->findByCompany($companyID)->from(TABLE_GROUP)->fetchAll();
    }

    /* 获得分组的key => value对。*/
    public function getPairs()
    {
        return $this->dao->findByCompany($this->app->company->id)->fields('id, name')->from(TABLE_GROUP)->fetchPairs();
    }

    /* 通过 id获取某一个分组信息。*/
    public function getByID($groupID)
    {
        return $this->dao->findById($groupID)->from(TABLE_GROUP)->fetch();
    }

    /* 获得分组的权限列表。*/
    public function getPrivs($groupID)
    {
        $privs = array();
        $stmt  = $this->dao->select('module, method')->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->orderBy('module')->query();
        while($priv = $stmt->fetch()) $privs[$priv->module][$priv->method] = $priv->method;
        return $privs;
    }
    
    /* 获得分组的用户列表。*/
    public function getUserPairs($groupID)
    {
        return $this->dao->select('t2.account, t2.realname')
            ->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('`group`')->eq((int)$groupID)
            ->andWhere('t2.company')->eq($this->app->company->id)
            ->fetchPairs();
    }

    /* 删除一个分组信息。*/
    public function delete($groupID)
    {
        $this->dao->delete()->from(TABLE_GROUP)->where('id')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->exec();
    }

    /* 更新权限。*/
    public function updatePriv($groupID)
    {
        /* 先删除原来的记录。*/
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->exec();

        /* 然后插入新的记录。*/
        if($this->post->actions == false) return;
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
    }

    /* 更新成员。*/
    public function updateUser($groupID)
    {
        /* 先删除原来的记录。*/
        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->exec();

        /* 然后插入新的记录。*/
        if($this->post->members == false) return;
        foreach($this->post->members as $account)
        {
            $data->account = $account;
            $data->group   = $groupID;
            $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
        }
    }
}
