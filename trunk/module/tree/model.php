<?php
/**
 * The model file of tree module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@gmail.com>
 * @package     tree
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class treeModel extends model
{
    /* 通过模块id获取模块信息。*/
    public function getByID($moduleID)
    {
        return $this->dbh->query("SELECT * FROM " . TABLE_MODULE . " WHERE id = '$moduleID'")->fetch();
    }

    /* 生成查询的sql语句。*/
    private function buildMenuQuery($productID, $viewType, $rootModuleID)
    {
        $sql  = "SELECT * FROM " . TABLE_MODULE . " WHERE product = '$productID' AND `view` = '$viewType'";
        if($rootModuleID > 0)
        {
            $rootModule = $this->getByID($rootModuleID);
            if($rootModule) $sql .= " AND `path` LIKE '$rootModule->path%'";
        }
        $sql .= " ORDER BY grade DESC, `order`";
        return $sql;
    }

    /* 获取模块的下类列表，用于生成select控件。*/
    public function getOptionMenu($productID, $viewType = 'product', $rootModuleID = 0)
    {
        $treeMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($productID, $viewType, $rootModuleID));
        $modules = array();
        while($module = $stmt->fetch()) $modules[$module->id] = $module;

        foreach($modules as $module)
        {
            $parentModules = explode(',', $module->path);
            $moduleName = '/';
            foreach($parentModules as $parentModuleID)
            {
                if(empty($parentModuleID)) continue;
                $moduleName .= $modules[$parentModuleID]->name . '/';
            }
            $moduleName = rtrim($moduleName, '/');
            $moduleName .= "|$module->id\n";

            if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id]))
            {
                if(isset($treeMenu[$module->parent]))
                {
                    $treeMenu[$module->parent] .= $moduleName;
                }
                else
                {
                    $treeMenu[$module->parent] = $moduleName;;
                }
                $treeMenu[$module->parent] .= $treeMenu[$module->id];
            }
            else
            {
                if(isset($treeMenu[$module->parent]) and !empty($treeMenu[$module->parent]))
                {
                    $treeMenu[$module->parent] .= $moduleName;
                }
                else
                {
                    $treeMenu[$module->parent] = $moduleName;
                }    
            }
        }

        $topMenu = @array_pop($treeMenu);
        $topMenu = explode("\n", trim($topMenu));
        $lastMenu[] = '/';
        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;
            list($label, $moduleID) = explode('|', $menu);
            $lastMenu[$moduleID] = $label;
        }
        return $lastMenu;
    }

    /* 获取树状的模块列表。*/
    public function getTreeMenu($productID, $viewType = 'product', $rootModuleID = 0, $userFunc)
    {
        $treeMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($productID, $viewType, $rootModuleID));
        while($module = $stmt->fetch())
        {
            $linkHtml = call_user_func($userFunc, $module);

            if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id]))
            {
                if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';
                $treeMenu[$module->parent] .= "<li>$linkHtml";  
                $treeMenu[$module->parent] .= "<ul>".$treeMenu[$module->id]."</ul>\n";
            }
            else
            {
                if(isset($treeMenu[$module->parent]) and !empty($treeMenu[$module->parent]))
                {
                    $treeMenu[$module->parent] .= "<li>$linkHtml\n";  
                }
                else
                {
                    $treeMenu[$module->parent] = "<li>$linkHtml\n";  
                }    
            }
            $treeMenu[$module->parent] .= "</li>\n"; 
        }

        $lastMenu = "<ul id='tree'>" . @array_pop($treeMenu) . "</ul>\n";
        return $lastMenu; 
    }

    /* 生成需求链接。*/
    private function createStoryLink($module)
    {
        $linkHtml = html::a(helper::createLink('product', 'browse', "product={$module->product}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /* 生成模块编辑链接。*/
    private function createManageLink($module)
    {
        $linkHtml  = $module->name;
        //$linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit',   "product={$module->product}&module={$module->id}"), $this->lang->tree->edit);
        if(common::hasPriv('tree', 'browse'))      $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browse', "product={$module->product}&viewType={$module->view}&module={$module->id}"), $this->lang->tree->child);
        if(common::hasPriv('tree', 'delete'))      $linkHtml .= ' ' . html::a(helper::createLink('tree', 'delete', "product={$module->product}&module={$module->id}"), $this->lang->delete, 'hiddenwin');
        if(common::hasPriv('tree', 'updateorder')) $linkHtml .= ' ' . html::input("orders[$module->id]", $module->order, 'style="width:30px;text-align:center"');
        return $linkHtml;
    }

    /* 生成Bug链接。*/
    private function createBugLink($module)
    {
        $linkHtml = html::a(helper::createLink('bug', 'browse', "product={$module->product}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /* 生成case链接。*/
    private function createCaseLink($module)
    {
        $linkHtml = html::a(helper::createLink('testcase', 'browse', "product={$module->product}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /* 获得某一个模块的直接下级模块。*/
    public function getSons($productID, $moduleID, $viewType = 'product')
    {
        $sql = "SELECT * FROM " . TABLE_MODULE . " WHERE product = '$productID' AND parent = '$moduleID' AND view = '$viewType' ORDER BY `order`";
        return $this->dbh->query($sql)->fetchAll();
    }
    
    /* 获得一个模块的id列表。*/
    public function getAllChildId($moduleID)
    {
        if($moduleID == 0) return array();
        $module = $this->getById($moduleID);
        $sql = "SELECT id FROM " . TABLE_MODULE . " WHERE path LIKE '{$module->path}%'";
        $stmt = $this->dbh->query($sql);
        $moduleIds = array();
        while($id = $stmt->fetchColumn()) $moduleIds[] = $id;
        return $moduleIds;
    }

    /* 获得一个模块的所有上级模块。*/
    public function getParents($moduleID)
    {
        if($moduleID == 0) return array();
        $sql  = "SELECT path FROM " . TABLE_MODULE . " WHERE id = '$moduleID'";
        $path = $this->dbh->query($sql)->fetchColumn();
        $path = substr($path, 1, -1);
        if(!$path) return array();
        $sql = "SELECT * FROM " . TABLE_MODULE . " WHERE id IN($path) ORDER BY grade";
        $parents = $this->dbh->query($sql)->fetchAll();
        return $parents;
    }

    /* 更新排序信息。*/
    public function updateOrder($orders)
    {
        foreach($orders as $moduleID => $order)
        {
            $sql = "UPDATE " . TABLE_MODULE . " SET `order` = '$order' WHERE id = '$moduleID' LIMIT 1";
            $this->dbh->exec($sql);
        }
    }

    /* 更新某一个模块的子模块。*/
    public function manageChild($productID, $viewType, $parentModuleID, $childs)
    {
        $parentModule = $this->getByID($parentModuleID);
        if($parentModule)
        {
            $grade      = $parentModule->grade + 1;
            $parentPath = $parentModule->path;
        }
        else
        {
            $grade      = 1;
            $parentPath = ',';
        }
        foreach($childs as $moduleID => $moduleName)
        {
            if(empty($moduleName)) continue;
            if(is_numeric($moduleID))
            {
                $sql = "INSERT INTO " . TABLE_MODULE . "(`product`, `name`, `parent`, `grade`, `view`) 
                        VALUES('$productID', '$moduleName', '$parentModuleID', '$grade', '$viewType')";
                $this->dbh->exec($sql);
                $moduleID  = $this->dbh->lastInsertID();
                $childPath = $parentPath . "$moduleID,";
                $sql = "UPDATE " . TABLE_MODULE . " SET `path` = '$childPath' WHERE id = '$moduleID' LIMIT 1";
                $this->dbh->exec($sql);
            }
            else
            {
                $moduleID = str_replace('id', '', $moduleID);
                $sql = "UPDATE " . TABLE_MODULE . " SET `name` = '$moduleName' WHERE id = '$moduleID' LIMIT 1";
                $this->dbh->exec($sql);
            }
        }
    }
    
    /* 删除一个模块。Todo: 需要修改下级目录的权限，还有对应的需求列表。*/
    public function delete($moduleID)
    {
        $module = $this->getById((int)$moduleID);
        $this->dao->delete()->from(TABLE_MODULE)->where('id')->eq($moduleID)->exec();
    }
}
