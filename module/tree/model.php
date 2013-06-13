<?php
/**
 * The model file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class treeModel extends model
{
    /**
     * Get module by ID.
     * 
     * @param  int    $moduleID 
     * @access public
     * @return object
     */
    public function getByID($moduleID)
    {
        return $this->dao->findById((int)$moduleID)->from(TABLE_MODULE)->fetch();
    }

    /**
     * Build the sql query.
     * 
     * @param  int    $rootID 
     * @param  string $type 
     * @param  int    $startModule 
     * @access public
     * @return void
     */
    public function buildMenuQuery($rootID, $type, $startModule)
    {
        /* Set the start module. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        if($type == 'bug' or $type == 'case')
        {
            /* Get createdVersion. */
            $createdVersion = $this->dao->select('createdVersion')->from(TABLE_PRODUCT) 
                ->where('id')->eq($rootID)
                ->fetch('createdVersion');

            if($createdVersion and version_compare($createdVersion, '4.1', '>'))
            {
                return $this->dao->select('*')->from(TABLE_MODULE)
                    ->where('root')->eq((int)$rootID)
                    ->andWhere('type')->in("story,$type")
                    ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                    ->orderBy('grade desc, type desc, `order`')
                    ->get();
            }
        }

        /* $createdVersion < 4.1 or $type == 'story','task'. */
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('type')->eq($type)
            ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
            ->orderBy('grade desc, `order`')
            ->get(); 
    }

    /**
     * Create an option menu in html.
     * 
     * @param  int    $rootID 
     * @param  string $type 
     * @param  int    $startModule 
     * @access public
     * @return string
     */
    public function getOptionMenu($rootID, $type = 'story', $startModule = 0)
    {
        $treeMenu = array();
        $stmt     = $this->dbh->query($this->buildMenuQuery($rootID, $type, $startModule));
        $modules  = array();
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

    /**
     * Create an option menu of task in html.
     * 
     * @param  int    $rootID 
     * @param  int    $startModule 
     * @access public
     * @return void
     */
    public function getTaskOptionMenu($rootID, $productID = 0, $startModule = 0)
    {
        /* If createdVersion <= 4.1, go to getOptionMenu(). */
        $createdVersion = $this->dao->select('openedVersion')->from(TABLE_PROJECT) 
            ->where('id')->eq($rootID)
            ->fetch('openedVersion');
        $products = $this->loadModel('product')->getProductsByProject($rootID);
        if(!$createdVersion or version_compare($createdVersion, '4.1', '<=') or !$products)
        {
            return $this->getOptionMenu($rootID, 'task', $startModule); 
        }

        /* createdVersion > 4.1. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) 
            {
                $startModulePath = $startModule->path . '%';
                $modulePaths = explode(",", $startModulePath);
                $rootModule  = $this->getById($modulePahts[0]);
                $productID   = $rootModule->root;
            }
        }
        $treeMenu   = array();
        $lastMenu[] = '/';
        foreach($products as $id => $product)
        {
            $modules  = $this->dao->select('*')->from(TABLE_MODULE)
                ->where("((root = $rootID and type = 'task') OR (root = $id and type = 'story'))")
                ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                ->orderBy('grade desc, type, `order`')
                ->fetchAll('id');
            foreach($modules as $module)
            {
                $parentModules = explode(',', $module->path);
                $moduleName = '/' . $product;
                foreach($parentModules as $parentModuleID)
                {
                    if(empty($parentModuleID) or !isset($modules[$parentModuleID])) continue;
                    $moduleName .= '/' . $modules[$parentModuleID]->name;
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
            foreach($topMenu as $menu)
            {
                if(!strpos($menu, '|')) continue;
                list($label, $moduleID) = explode('|', $menu);
                $lastMenu[$moduleID] = $label;
            }
        }
        return $lastMenu;
    }

    /**
     * Get the tree menu in html.
     * 
     * @param  int    $rootID 
     * @param  string $type 
     * @param  int    $startModule 
     * @param  string $userFunc     the function used to create link
     * @param  string $extra        extra params
     * @access public
     * @return string
     */
    public function getTreeMenu($rootID, $type = 'root', $startModule = 0, $userFunc, $extra = '')
    {
        $treeMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($rootID, $type, $startModule));
        while($module = $stmt->fetch())
        {
            $linkHtml = call_user_func($userFunc, $type, $module, $extra);

            if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id]))
            {
                if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';
                $treeMenu[$module->parent] .= "<li>$linkHtml";  
                $treeMenu[$module->parent] .= "<ul>" . $treeMenu[$module->id] . "</ul>\n";
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

        $lastMenu = "<ul class='tree'>" . @array_pop($treeMenu) . "</ul>\n";
        return $lastMenu; 
    }

    /**
     * Get the tree menu of task in html.
     * 
     * @param  int    $rootID 
     * @param  int    $productID 
     * @param  int    $startModule 
     * @param  int    $userFunc 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function getTaskTreeMenu($rootID, $productID = 0, $startModule = 0, $userFunc, $extra = '')
    {
        /* If createdVersion <= 4.1, go to getTreeMenu(). */
        $createdVersion = $this->dao->select('openedVersion')->from(TABLE_PROJECT) 
            ->where('id')->eq($rootID)
            ->fetch('openedVersion');
        $products = $this->loadModel('product')->getProductsByProject($rootID);
        if(!$createdVersion or version_compare($createdVersion, '4.1', '<=') or !$products)
        {
            return $this->getTreeMenu($rootID, 'task', $startModule, $userFunc, $extra); 
        }
        
        /* createdVersion > 4.1. */
        $menu = "<ul class='tree'>";

        /* Set the start module. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        foreach($products as $id => $product)
        {
            if($userFunc[1] == 'createTaskManageLink')
            {
                $menu .= "<li>" . $product;
            }
            else
            {
                $menu .= "<li>" . html::a(helper::createLink('project', 'task', "root=$rootID&status=byProduct&praram=$id"), $product);
            }

            /* tree menu. */
            $treeMenu = array();
            $query = $this->dao->select('*')->from(TABLE_MODULE)
                ->where("((root = $rootID and type = 'task') OR (root = $id and type = 'story'))")
                ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                ->orderBy('grade desc, type, `order`')
                ->get();
            $stmt = $this->dbh->query($query);
            while($module = $stmt->fetch())
            {
                $linkHtml = call_user_func($userFunc, $rootID, $id, $module, $extra);

                if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id]))
                {
                    if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';
                    $treeMenu[$module->parent] .= "<li>$linkHtml";  
                    $treeMenu[$module->parent] .= "<ul>" . $treeMenu[$module->id] . "</ul>\n";
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

            $lastMenu = "<ul class='tree'>" . @array_pop($treeMenu) . "</ul>\n";
            $menu .= $lastMenu . '</li>';
        }
        return $menu;
    }

    /**
     * Get the tree menu of product document library.
     * 
     * @access public
     * @return string
     */
    public function getProductDocTreeMenu()
    {
        $menu = "<ul class='tree'>";
        $products = $this->loadModel('product')->getPairs('nocode');
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

                /* If $projectModules not emtpy, append the project modules. */
                if($projectModules)
                {
                    $menu .= '<li>';
                    $menu .= html::a(helper::createLink('doc', 'browse', "libID=product&module=0&productID=$productID&projectID=int"), $this->lang->tree->projectDoc);
                    $menu .= '<ul>';
                    foreach($projectModules as $module)
                    {
                        $menu .= '<li>' . html::a(helper::createLink('doc', 'browse', "libID=product&module=$module->id&productID=$productID"), $module->name) . '</li>';
                    }
                    $menu .= '</ul></li>';
                }

                $menu .= '</ul>';
            }
        }

        $menu .= '</li>';
        return $menu;
    }

    /**
     * Get the tree menu of project document library.
     * 
     * @access public
     * @return void
     */
    public function getProjectDocTreeMenu()
    {
        $menu     = "<ul class='tree'>";
        $products = $this->loadModel('product')->getPairs('nocode');
        $projects = $this->loadModel('project')->getProductGroupList();
        $modules  = $this->dao->findByType('projectdoc')->from(TABLE_MODULE)->orderBy('`order`')->fetchAll();

        foreach($projects as $id => $project)
        {
            if($id == '') 
            {
                $projects[0] = $projects[''];
                unset($projects['']);
            }
        }

       if(!empty($projects[0])) $products[0] = $this->lang->project->noProduct;

        foreach($products as $productID => $productName)
        {
            $menu .= '<li>';
            $menu .= $productName;

            if(isset($projects[$productID]))
            {
                $menu .= '<ul>';
                foreach($projects[$productID] as $project)
                {
                    $menu .= '<li>' . html::a(helper::createLink('doc', 'browse', "libID=project&module=0&productID=0&projectID=$project->id"), $project->name);
                    if($modules)
                    {
                        $menu .= '<ul>';
                        foreach($modules as $module)
                        {
                            $menu .= '<li>' . html::a(helper::createLink('doc', 'browse', "libID=project&module=$module->id&productID=0&projectID=$project->id"), $module->name) . '</li>';
                        }
                        $menu .= '</ul>';
                    }
                    $menu .= '</li>';
                }
                $menu .='</ul>';
            }
            $menu .='</li>';
        }

        $menu .= '</ul>';
        return $menu;
    }

    /**
     * Create link of a story.
     * 
     * @param  object   $module 
     * @access public
     * @return string
     */
    public function createStoryLink($type, $module)
    {
        $linkHtml = html::a(helper::createLink('product', 'browse', "root={$module->root}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /**
     * Create link of a task.
     * 
     * @param  object   $module 
     * @access public
     * @return string
     */
    public function createTaskLink($rootID, $productID, $module)
    {
        $linkHtml = html::a(helper::createLink('project', 'task', "root={$rootID}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /**
     * Create link of a doc.
     * 
     * @param  object   $module 
     * @access public
     * @return string
     */
    public function createDocLink($type, $module)
    {
        $linkHtml = html::a(helper::createLink('doc', 'browse', "libID={$module->root}&&module={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /**
     * Create the manage link of a module.
     * 
     * @param  object   $module 
     * @access public
     * @return string
     */
    public function createManageLink($type, $module)
    {
        static $users;
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');
        $linkHtml  = $module->name;
        $linkHtml .= $module->type != 'story' ? ' [' . strtoupper(substr($module->type, 0, 1)) . ']' : '';
        if($type == 'bug' and $module->owner) $linkHtml .= '<span class="owner">[' . $users[$module->owner] . ']</span>';
        if($type != 'story' and $module->type == 'story')
        {
            if(common::hasPriv('tree', 'edit') and $type == 'bug') $linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit',   "module={$module->id}&type=$type"), $this->lang->tree->edit, '', 'class="iframe"');
            if(common::hasPriv('tree', 'browse')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browse', "root={$module->root}&type=$type&module={$module->id}"), $this->lang->tree->child);
        }
        else
        {
            if(common::hasPriv('tree', 'edit')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit',   "module={$module->id}&type=$type"), $this->lang->tree->edit, '', 'class="iframe"');
            if(common::hasPriv('tree', 'browse') and strpos('productdoc,projectdoc', $module->type) === false and $module->type != 'webapp') $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browse', "root={$module->root}&type=$type&module={$module->id}"), $this->lang->tree->child);
            if(common::hasPriv('tree', 'delete')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'delete', "root={$module->root}&module={$module->id}"), $this->lang->delete, 'hiddenwin');
            if(common::hasPriv('tree', 'updateorder')) $linkHtml .= ' ' . html::input("orders[$module->id]", $module->order, 'style="width:30px;text-align:center"');
        }
        return $linkHtml;
    }

    /**
     * Create the task manage link of a module.
     * 
     * @param  int    $productID 
     * @param  int    $module 
     * @access public
     * @return void
     */
    public function createTaskManageLink($rootID, $productID, $module)
    {
        $linkHtml  = $module->name;
        $linkHtml .= $module->type != 'story' ? ' [' . strtoupper(substr($module->type, 0, 1)) . ']' : '';
        if($module->type == 'story')
        {
            if(common::hasPriv('tree', 'browseTask')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browsetask', "rootID=$rootID&productID=$productID&module={$module->id}"), $this->lang->tree->child);
        }
        else
        {
            if(common::hasPriv('tree', 'edit'))        $linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit', "module={$module->id}&type=task"), $this->lang->tree->edit, '', 'class="iframe"');
            if(common::hasPriv('tree', 'browseTask'))  $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browsetask', "rootID=$rootID&productID=$productID&module={$module->id}"), $this->lang->tree->child);
            if(common::hasPriv('tree', 'delete'))      $linkHtml .= ' ' . html::a(helper::createLink('tree', 'delete', "root={$module->root}&module={$module->id}"), $this->lang->delete, 'hiddenwin');
            if(common::hasPriv('tree', 'updateorder')) $linkHtml .= ' ' . html::input("orders[$module->id]", $module->order, 'style="width:30px;text-align:center"');
        }
        return $linkHtml;
    }

    /**
     * Create link of a bug.
     * 
     * @param  object  $module 
     * @access public
     * @return string
     */
    public function createBugLink($type, $module)
    {
        $linkHtml = html::a(helper::createLink('bug', 'browse', "root={$module->root}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /**
     * Create link of a test case.
     * 
     * @param  object  $module 
     * @access public
     * @return string
     */
    public function createCaseLink($type, $module)
    {
        $linkHtml = html::a(helper::createLink('testcase', 'browse', "root={$module->root}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /**
     * Create link of a test task.
     * 
     * @param  object  $module 
     * @access public
     * @return string
     */
    public function createTestTaskLink($module, $extra)
    {
        $linkHtml = html::a(helper::createLink('testtask', 'cases', "taskID=$extra&type=byModule&module={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /**
     * Create Webapp Link 
     * 
     * @param  int    $module 
     * @param  int    $extra 
     * @access public
     * @return void
     */
    public function createWebappLink($module, $extra)
    {
        $linkHtml = html::a(helper::createLink('webapp', 'index', "module={$module->id}"), $module->name, '_self', "id='module{$module->id}'");
        return $linkHtml;
    }

    /**
     * Get sons of a module.
     * 
     * @param  int    $rootID 
     * @param  int    $moduleID 
     * @param  string $type 
     * @access public
     * @return array
     */
    public function getSons($rootID, $moduleID, $type = 'root')
    {
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('parent')->eq((int)$moduleID)
            ->beginIF($type == 'story')->andWhere('type')->eq($type)->fi()
            ->beginIF($type != 'story')->andWhere('type')->in("$type,story")->fi()
            ->orderBy('type desc,`order`')
            ->fetchAll();
    }
    
    /**
     * Get sons of a task module.
     * 
     * @param  int    $rootID 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function getTaskSons($rootID, $productID, $moduleID)
    {
        if($moduleID)
        {
            return $this->dao->select('*')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->andWhere('parent')->eq((int)$moduleID)
                ->andWhere('type')->in("task,story")
                ->orderBy('type,`order`')
                ->fetchAll();
        }
        else
        {
            return $this->dao->select('*')->from(TABLE_MODULE)
                ->where('parent')->eq(0)
                ->andWhere("root = $rootID and type = 'task'")
                ->orWhere("root = $productID and type = 'story'")
                ->orderBy('type,`order`')
                ->fetchAll();
        }
    }

    /**
     * Get id list of a module's childs.
     * 
     * @param  int     $moduleID 
     * @access public
     * @return array
     */
    public function getAllChildId($moduleID)
    {
        if($moduleID == 0) return array();
        $module = $this->getById((int)$moduleID);
        return $this->dao->select('id')->from(TABLE_MODULE)->where('path')->like($module->path . '%')->fetchPairs();
    }

    /**
     * Get parents of a module.
     * 
     * @param  int    $moduleID 
     * @access public
     * @return array
     */
    public function getParents($moduleID)
    {
        if($moduleID == 0) return array();
        $path = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq((int)$moduleID)->fetch('path');
        $path = trim($path, ',');
        if(!$path) return array();
        return $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in($path)->orderBy('grade')->fetchAll();
    }

    /**
     * Update modules' order.
     * 
     * @param  array   $orders 
     * @access public
     * @return void
     */
    public function updateOrder($orders)
    {
        asort($orders);
        $orderInfo = $this->dao->select('id,grade, parent')->from(TABLE_MODULE)->where('id')->in(array_keys($orders))->fetchAll('id');
        $newOrders = array();
        foreach($orders as $moduleID => $order)
        {
            $parent = $orderInfo[$moduleID]->parent;
            $grade  = $orderInfo[$moduleID]->grade;

            if(!isset($newOrders[$parent][$grade]))
            {
                $newOrders[$parent][$grade] = 1;
            }
            else
            {
                $newOrders[$parent][$grade] ++;
            }

            $newOrder = $newOrders[$parent][$grade] * 10;
            $this->dao->update(TABLE_MODULE)->set('`order`')->eq($newOrder)->where('id')->eq((int)$moduleID)->limit(1)->exec();
        }
    }

    /**
     * Manage childs of a module.
     * 
     * @param  int    $rootID 
     * @param  string $type 
     * @param  int    $parentModuleID 
     * @param  array  $childs 
     * @access public
     * @return void
     */
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

            /* The new modules. */
            if(is_numeric($moduleID))
            {
                $module->root    = $rootID;
                $module->name    = strip_tags($moduleName);
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
                $this->dao->update(TABLE_MODULE)->set('name')->eq(strip_tags($moduleName))->where('id')->eq($moduleID)->limit(1)->exec();
            }
        }
    }

    /**
     * Update a module.
     * 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
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
        $this->fixModulePath($self->root, $self->type);
    }

    /**
     * Delete a module.
     * 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function delete($moduleID)
    {
        $module = $this->getById($moduleID);
        $childs = $this->getAllChildId($moduleID);

        $this->dao->update(TABLE_MODULE)->set('grade = grade - 1')->where('id')->in($childs)->exec();                 // Update grade of all childs.
        $this->dao->update(TABLE_MODULE)->set('parent')->eq($module->parent)->where('parent')->eq($moduleID)->exec(); // Update the parent of sons to my parent.
        $this->dao->delete()->from(TABLE_MODULE)->where('id')->eq($moduleID)->exec();                                 // Delete my self.
        $this->fixModulePath($module->root, $module->type);

        if($module->type == 'story') $this->dao->update(TABLE_STORY)->set('module')->eq($module->parent)->where('module')->eq($moduleID)->exec();
        if($module->type == 'bug')   $this->dao->update(TABLE_BUG)->set('module')->eq($module->parent)->where('module')->eq($moduleID)->exec();
        if($module->type == 'case')  $this->dao->update(TABLE_CASE)->set('module')->eq($module->parent)->where('module')->eq($moduleID)->exec();
    }

    /**
     * Fix the path, grade fields according to the id and parent fields.
     *
     * @param  string    $root 
     * @param  string    $type 
     * @access public
     * @return void
     */
    public function fixModulePath($root, $type)
    {
        /* Get all modules grouped by parent. */
        $groupModules = $this->dao->select('id, parent')->from(TABLE_MODULE)->where('root')->eq($root)->andWhere('type')->eq($type)->fetchGroup('parent', 'id');
        $modules = array();

        /* Cycle the groupModules until it has no item any more. */
        while(count($groupModules) > 0)
        {
            $oldCounts = count($groupModules);    // Record the counts before processing.
            foreach($groupModules as $parentModuleID => $childModules)
            {
                /* If the parentModule doesn't exsit in the modules, skip it. If exists, compute it's child modules. */
                if(!isset($modules[$parentModuleID]) and $parentModuleID != 0) continue;
                if($parentModuleID == 0)
                {
                    $parentModule->grade = 0;
                    $parentModule->path  = ',';
                }
                else
                {
                    $parentModule = $modules[$parentModuleID];
                }

                /* Compute it's child modules. */
                foreach($childModules as $childModuleID => $childModule)
                {
                    $childModule->grade = $parentModule->grade + 1;
                    $childModule->path  = $parentModule->path . $childModule->id . ',';
                    $modules[$childModuleID] = $childModule;    // Save child module to modules, thus the child of child can compute it's grade and path.
                }
                unset($groupModules[$parentModuleID]);    // Remove it from the groupModules.
            }
            if(count($groupModules) == $oldCounts) break;   // If after processing, no module processed, break the cycle.
        }

        /* Save modules to database. */
        foreach($modules as $module)
        {
            $this->dao->update(TABLE_MODULE)->data($module)->where('id')->eq($module->id)->limit(1)->exec();
        }
    }
}
