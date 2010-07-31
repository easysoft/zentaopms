<?php
/**
 * The model file of doc module of ZenTaoMS.
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
 * @package     doc
 * @version     $Id: model.php 881 2010-06-22 06:50:32Z chencongzhi520 $
 * @link        http://www.zentaoms.com
 */
?>
<?php
class docModel extends model
{
    /* 设置菜单。*/
    public function setMenu($libs, $libID, $extra = '')
    {
        /* 获得当前的模块和方法，传递给switchDocLib方法，供页面跳转使用。*/
        $currentModule = $this->app->getModuleName();
        $currentMethod = $this->app->getMethodName();

        $selectHtml = html::select('libID', $libs, $libID, "onchange=\"switchDocLib(this.value, '$currentModule', '$currentMethod', '$extra');\"");
        common::setMenuVars($this->lang->doc->menu, 'list', $selectHtml . $this->lang->arrow);
        foreach($this->lang->doc->menu as $key => $menu)
        {
            if($key != 'list') common::setMenuVars($this->lang->doc->menu, $key, $libID);
        }
    }

    /* 通过ID获取文档库信息。*/
    public function getLibById($libID)
    {
        return $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch();
    }

    /* 获取文档库列表。*/
    public function getLibs()
    {
        $libs = $this->dao->select('id, name')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->fetchPairs();
        return $this->lang->doc->systemLibs + $libs;
    }

    /* 新增文档库。*/
    public function createLib()
    {
        /* 处理数据。*/
        $lib = fixer::input('post')->stripTags('name')->get();
        $this->dao->insert(TABLE_DOCLIB)
            ->data($lib)
            ->autoCheck()
            ->batchCheck('name', 'notempty')
            ->check('name', 'unique')
            ->exec();
        return $this->dao->lastInsertID();
    }

    /* 编辑文档库。*/
    public function updateLib($libID)
    {
        /* 处理数据。*/
        $libID  = (int)$libID;
        $oldLib = $this->getLibById($libID);
        $lib = fixer::input('post')->stripTags('name')->get();
        $this->dao->update(TABLE_DOCLIB)
            ->data($lib)
            ->autoCheck()
            ->batchCheck('name', 'notempty')
            ->check('name', 'unique', "id != $libID")
            ->where('id')->eq($libID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldLib, $lib);
    }

    /* 获得文档列表。*/
    public function getDocs($libID, $productID, $projectID, $module, $orderBy, $pager)
    {
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->beginIF(is_numeric($libID))->andWhere('lib')->eq($libID)->fi()
            ->beginIF($libID == 'product')->andWhere('product')->gt(0)->fi()
            ->beginIF($libID == 'project')->andWhere('project')->gt(0)->fi()
            ->beginIF($productID > 0)->andWhere('product')->eq($productID)->fi()
            ->beginIF($projectID > 0)->andWhere('project')->eq($projectID)->fi()
            ->beginIF((string)$projectID == 'int')->andWhere('project')->gt(0)->fi()
            ->beginIF($module)->andWhere('module')->in($module)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /* 获得某一个产品的文档列表。*/
    public function getProductDocs($productID)
    {
        return $this->dao->findByProduct($productID)->from(TABLE_DOC)->fetchAll();
    }

    /* 获得某一个项目的文档列表。*/
    public function getProjectDocs($projectID)
    {
        return $this->dao->findByProject($projectID)->from(TABLE_DOC)->fetchAll();
    }

    /* 获得产品的文档模块列表*/
    public function getProductModulePairs()
    {
        return $this->dao->findByType('productdoc')->from(TABLE_MODULE)->fetchPairs('id', 'name');
    }

    /* 获得项目的文档模块列表。*/
    public function getProjectModulePairs()
    {
        return $this->dao->findByType('projectdoc')->from(TABLE_MODULE)->andWhere('type')->eq('projectdoc')->fetchPairs('id', 'name');
    }
}
