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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php
class treeModel extends model
{
    /* 通过模块id获取模块信息。*/
    public function getByID($moduleID)
    {
        return $this->dao->findById((int)$moduleID)->from(TABLE_MODULE)->fetch();
    }

    /* 生成查询的sql语句。*/
    private function buildMenuQuery($rootID, $type, $startModule)
    {
        /* 查找startModule。*/
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('type')->eq($type)
            ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /* 获取模块的下类列表，用于生成select控件。*/
    public function getOptionMenu($rootID, $type = 'story', $startModule = 0)
    {
        $treeMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($rootID, $type, $startModule));
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
    public function getTreeMenu($rootID, $type = 'root', $startModule = 0, $userFunc)
    {
        $treeMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($rootID, $type, $startModule));
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

    /* 获得系统文档库的树状列表。*/
    public function getSystemDocTreeMenu($libID)
    {
        $menu = "<ul id='tree'>";
        if($libID == 'product')
        {
            $products = $this->loadModel('product')->getPairs();
            $modules  = $this->dao->findByType('productdoc')->from(TABLE_MODULE)->orderBy('`order`')->fetchAll();
            $projectModules = $this->dao->findByType('projectdoc')->from(TABLE_MODULE)->orderBy('`order`')->fetchAll();
            
            foreach($products as $productID =>$productName)
            {
                $menu .= '<li>';
                $menu .= html::a(helper::createLink('doc', 'browse', "libID=product&module=0&productID=$productID"), $productName);
                if($modules)
                {
                    $menu .= '<ul>';
                    foreach($modules as $module)
                    {
                        $menu .= '<li>' . html::a(helper::createLink('doc', 'browse', "libID=product&module=$module->id&productID=$productID"), $module->name) . '</li>';
                    }

                    /* 如果项目文档模块不为空，则追加项目文档列表。*/
                    if($projectModules)
                    {
                        $menu .= '<li>';
                        $menu .= html::a(helper::createLink('doc', 'browse', "libID=product&module=0&productID=$productID&projectID=all"), $this->lang->tree->projectDoc);
                        $menu .= '<ul>';
                        foreach($projectModules as $module)
                        {
                            $menu .= '<li>' . html::a(helper::createLink('doc', 'browse', "libID=product&module=$module->id&productID=$productID"), $module->name) . '</li>';
                        }
                        $menu .= '</ul></li>';
                    }

                    $menu .= '</ul>';
                }
                $menu .= '</li>';
            }
        }
        elseif($libID == 'project')
        {
            $projects = $this->loadModel('project')->getPairs();
            $modules  = $this->dao->findByType('projectdoc')->from(TABLE_MODULE)->orderBy('`order`')->fetchAll();
            
            foreach($projects as $projectID =>$projectName)
            {
                $menu .= '<li>';
                $menu .= html::a(helper::createLink('doc', 'browse', "libID=project&moduleID=0&projectID=$projectID"), $projectName);
                if($modules)
                {
                    $menu .= '<ul>';
                    foreach($modules as $module)
                    {
                        $menu .= '<li>' . html::a(helper::createLink('doc', 'browse', "libID=project&module=$module->id&projectID=$projectID"), $module->name) . '</li>';
                    }
                    $menu .= '</ul>';
                }
                $menu .= '</li>';
            }
        }
 
        $menu .= '</ul>';
        return $menu;
    }

    /* 生成需求链接。*/
    private function createStoryLink($module)
    {
        $linkHtml = html::a(helper::createLink('product', 'browse', "root={$module->root}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /* 生成文档链接。*/
    private function createDocLink($module)
    {
        $linkHtml = html::a(helper::createLink('doc', 'browse', "libID={$module->root}&&module={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /* 生成模块编辑链接。*/
    private function createManageLink($module)
    {
        static $users;
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');
        $linkHtml  = $module->name;
        if($module->type == 'bug' and $module->owner) $linkHtml .= '<span class="owner">[' . $users[$module->owner] . ']</span>';
        if(common::hasPriv('tree', 'edit')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit',   "module={$module->id}"), $this->lang->tree->edit, '', 'class="iframe"');
        if(common::hasPriv('tree', 'browse') and strpos($module->type, 'doc') === false) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browse', "root={$module->root}&type={$module->type}&module={$module->id}"), $this->lang->tree->child);
        if(common::hasPriv('tree', 'delete')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'delete', "root={$module->root}&module={$module->id}"), $this->lang->delete, 'hiddenwin');
        if(common::hasPriv('tree', 'updateorder')) $linkHtml .= ' ' . html::input("orders[$module->id]", $module->order, 'style="width:30px;text-align:center"');
        return $linkHtml;
    }

    /* 生成Bug链接。*/
    private function createBugLink($module)
    {
        $linkHtml = html::a(helper::createLink('bug', 'browse', "root={$module->root}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /* 生成case链接。*/
    private function createCaseLink($module)
    {
        $linkHtml = html::a(helper::createLink('testcase', 'browse', "root={$module->root}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /* 获得某一个模块的直接下级模块。*/
    public function getSons($rootID, $moduleID, $type = 'root')
    {
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('parent')->eq((int)$moduleID)
            ->andWhere('type')->eq($type)
            ->orderBy('`order`')
            ->fetchAll();
    }
    
    /* 获得一个模块的id列表。*/
    public function getAllChildId($moduleID)
    {
        if($moduleID == 0) return array();
        $module = $this->getById((int)$moduleID);
        return $this->dao->select('id')->from(TABLE_MODULE)->where('path')->like($module->path . '%')->fetchPairs();
    }

    /* 获得一个模块的所有上级模块。*/
    public function getParents($moduleID)
    {
        if($moduleID == 0) return array();
        $path = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq((int)$moduleID)->fetch('path');
        $path = trim($path, ',');
        if(!$path) return array();
        return $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in($path)->orderBy('grade')->fetchAll();
    }

    /* 更新排序信息。*/
    public function updateOrder($orders)
    {
        foreach($orders as $moduleID => $order)
        {
            $this->dao->update(TABLE_MODULE)->set('`order`')->eq($order)->where('id')->eq((int)$moduleID)->limit(1)->exec();
        }
    }

    /* 更新某一个模块的子模块。*/
    public function manageChild($rootID, $type, $parentModuleID, $childs)
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
        $i = 1;
        foreach($childs as $moduleID => $moduleName)
        {
            if(empty($moduleName)) continue;

            /* 新增模块。*/
            if(is_numeric($moduleID))
            {
                $module->root = $rootID;
                $module->name    = $moduleName;
                $module->parent  = $parentModuleID;
                $module->grade   = $grade;
                $module->type    = $type;
                $module->order   = $this->post->maxOrder + $i * 10;
                $this->dao->insert(TABLE_MODULE)->data($module)->exec();
                $moduleID  = $this->dao->lastInsertID();
                $childPath = $parentPath . "$moduleID,";
                $this->dao->update(TABLE_MODULE)->set('path')->eq($childPath)->where('id')->eq($moduleID)->limit(1)->exec();
                $i ++;
            }
            else
            {
                $moduleID = str_replace('id', '', $moduleID);
                $this->dao->update(TABLE_MODULE)->set('name')->eq($moduleName)->where('id')->eq($moduleID)->limit(1)->exec();
            }
        }
    }

    /* 编辑一个模块。*/
    public function update($moduleID)
    {
        $module = fixer::input('post')->specialChars('name')->get();
        $self   = $this->getById($moduleID);
        $parent = $this->getById($this->post->parent);
        $childs = $this->getAllChildId($moduleID);
        $module->grade = $parent ? $parent->grade + 1 : 1;
        $this->dao->update(TABLE_MODULE)->data($module)->autoCheck()->check('name', 'notempty')->where('id')->eq($moduleID)->exec();
        $this->dao->update(TABLE_MODULE)->set('grade = grade + 1')->where('id')->in($childs)->andWhere('id')->ne($moduleID)->exec();
        $this->dao->update(TABLE_MODULE)->set('owner')->eq($this->post->owner)->where('id')->in($childs)->andWhere('owner')->eq('')->exec();
        $this->dao->update(TABLE_MODULE)->set('owner')->eq($this->post->owner)->where('id')->in($childs)->andWhere('owner')->eq($self->owner)->exec();
        $this->fixModulePath();
    }

    /* 删除一个模块。*/
    public function delete($moduleID)
    {
        $module = $this->getById($moduleID);
        $childs = $this->getAllChildId($moduleID);

        $this->dao->update(TABLE_MODULE)->set('grade = grade - 1')->where('id')->in($childs)->exec();                 // 更新所有的下级模块的grade。
        $this->dao->update(TABLE_MODULE)->set('parent')->eq($module->parent)->where('parent')->eq($moduleID)->exec(); // 更新直接下级的parent。
        $this->dao->delete()->from(TABLE_MODULE)->where('id')->eq($moduleID)->exec();                                 // 删除自己。
        $this->fixModulePath();

        if($module->type == 'story') $this->dao->update(TABLE_STORY)->set('module')->eq($module->parent)->where('module')->eq($moduleID)->exec();
        if($module->type == 'bug')   $this->dao->update(TABLE_BUG)->set('module')->eq($module->parent)->where('module')->eq($moduleID)->exec();
        if($module->type == 'case')  $this->dao->update(TABLE_CASE)->set('module')->eq($module->parent)->where('module')->eq($moduleID)->exec();
    }

    /* 修正modulePath字段。*/
    public function fixModulePath()
    {
        /* 获得最大的级别。*/
        $maxGrade = $this->dao->select('MAX(grade) AS grade')->from(TABLE_MODULE)->fetch('grade');
        $modules  = array();

        /* 依次处理每个级别的模块。*/
        for($grade = 1; $grade <= $maxGrade; $grade ++)
        {
            /* 当前级别的模块。*/
            $gradeModules = $this->dao->select('id, parent, grade')->from(TABLE_MODULE)->where('grade')->eq($grade)->fetchAll('id');
            foreach($gradeModules as $moduleID => $module)
            {
                if($grade == 1)
                {
                    $module->path = ",$moduleID,";
                }
                else
                {
                    /* 取parent模块的path。*/
                    if(isset($modules[$module->parent]))
                    {
                        $module->path  = $modules[$module->parent]->path . "$moduleID,";
                        $module->grade = $modules[$module->parent]->grade + 1;
                    }
                }
            }
            $modules += $gradeModules;
        }

        /* 最后更新每一个模块。*/
        foreach($modules as $moduleID => $module)
        {
            $this->dao->update(TABLE_MODULE)->data($module)->where('id')->eq($module->id)->limit(1)->exec();
        }
    }
}
