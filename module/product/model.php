<?php
/**
 * The model file of product module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class productModel extends model
{
    /* 设置菜单。*/
    public function setMenu($products, $productID, $extra = '')
    {
        /* 获得当前的模块和方法，传递给switchProduct方法，供页面跳转使用。*/
        $currentModule = $this->app->getModuleName();
        $currentMethod = $this->app->getMethodName();

        $selectHtml = html::select('productID', $products, $productID, "onchange=\"switchProduct(this.value, '$currentModule', '$currentMethod', '$extra');\"");
        common::setMenuVars($this->lang->product->menu, 'list', $selectHtml . $this->lang->arrow);
        foreach($this->lang->product->menu as $key => $menu)
        {
            if($key != 'list') common::setMenuVars($this->lang->product->menu, $key, $productID);
        }
    }

    /* 检查权限。*/
    public function checkPriv($product)
    {
        /* 检查是否是管理员。*/
        $account = ',' . $this->app->user->account . ',';
        if(strpos($this->app->company->admins, $account) !== false) return true; 

        /* 访问级别为open，不做任何处理。*/
        if($product->acl == 'open') return true;

        /* 获得团队的成员列表，供后面判断。*/
        $teamMembers = $this->getTeamMemberPairs($product->id);

        /* 级别为private。*/
        if($product->acl == 'private')
        {
            return isset($teamMembers[$this->app->user->account]);
        }

        /* 级别为custom。*/
        if($product->acl == 'custom')
        {
            if(isset($teamMembers[$this->app->user->account])) return true;
            $userGroups    = $this->loadModel('user')->getGroups($this->app->user->account);
            $productGroups = explode(',', $product->whitelist);
            foreach($userGroups as $groupID)
            {
                if(in_array($groupID, $productGroups)) return true;
            }
            return false;
        }
    }

    /* 通过ID获取产品信息。*/
    public function getById($productID)
    {
        return $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
    }

    /* 获取产品列表。*/
    public function getList()
    {
        return $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchAll('id');
    }

    /* 获取产品id=>name列表。*/
    public function getPairs()
    {
        $mode = $this->cookie->productMode;
        $products = $this->dao->select('*')
            ->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF($mode == 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->fetchAll();
        $pairs = array();
        foreach($products as $product)
        {
            if($this->checkPriv($product)) $pairs[$product->id] = $product->name;
        }
        return $pairs;
    }

    /* 获取产品的的状态分组。*/
    public function getStatusGroups()
    {
        $products = $this->dao->select('id, name, status')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchGroup('status');
    }

    /* 新增产品。*/
    public function create()
    {
        /* 处理数据。*/
        $product = fixer::input('post')
            ->stripTags('name,code')
            ->specialChars('desc')
             ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->join('whitelist', ',')
            ->get();
        $this->dao->insert(TABLE_PRODUCT)
            ->data($product)
            ->autoCheck()
            ->batchCheck('name,code', 'notempty')
            ->check('name', 'unique')
            ->check('code', 'unique')
            ->exec();
        return $this->dao->lastInsertID();
    }

    /* 更新产品。*/
    public function update($productID)
    {
        /* 处理数据。*/
        $productID = (int)$productID;
        $oldProduct = $this->getById($productID);
        $product = fixer::input('post')
            ->stripTags('name,code')
            ->specialChars('desc')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->join('whitelist', ',')
            ->get();
        $this->dao->update(TABLE_PRODUCT)
            ->data($product)
            ->autoCheck()
            ->batchCheck('name,code', 'notempty')
            ->check('name', 'unique', "id != $productID")
            ->check('code', 'unique', "id != $productID")
            ->where('id')->eq($productID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldProduct, $product);
    }
    
    /* 获取产品的项目id=>value列表。*/
    public function getProjectPairs($productID)
    {
        $projects = $this->dao->select('t2.id, t2.name')
            ->from(TABLE_PROJECTPRODUCT)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->orderBy('t1.project desc')
            ->fetchPairs();
        $projects = array('' => '') +  $projects;
        return $projects;
    }

    /* 计算产品路线图。*/
    public function getRoadmap($productID)
    {
        $plans    = $this->loadModel('productplan')->getList($productID);
        $releases = $this->loadModel('release')->getList($productID);
        $roadmap  = array();
        if(is_array($releases)) $releases = array_reverse($releases);
        foreach($releases as $release)
        {
            $year = substr($release->date, 0, 4);
            $roadmap[$year][] = $release;
        }
        foreach($plans as $plan)
        {
            if($plan->end != '0000-00-00' and strtotime($plan->end) - time() <= 0) continue;
            $year = substr($plan->end, 0, 4);
            $roadmap[$year][] = $plan;
        }
        arsort($roadmap);
        return $roadmap;
    }

    /* 获取团队成员。*/
    public function getTeamMemberPairs($productID)
    {
        $projects = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->fetchPairs();
        if(!$projects) return array();
        return $this->dao->select('account')->from(TABLE_TEAM)->where('project')->in($projects)->fetchPairs();
    }
}
