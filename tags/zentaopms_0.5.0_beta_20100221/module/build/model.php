<?php
/**
 * The model file of build module of ZenTaoMS.
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
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     build
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class buildModel extends model
{
    /* 获取build详细信息。*/
    public function getByID($buildID)
    {
        return $this->dao->select('t1.*, t2.name as projectName, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.id')->eq((int)$buildID)
            ->orderBy('t1.id DESC')
            ->fetch();
    }

    /* 查找项目中的build列表。*/
    public function getProjectBuilds($projectID)
    {
        return $this->dao->select('t1.*, t2.name as projectName, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.project')->eq((int)$projectID)
            ->orderBy('t1.id DESC')
            ->fetchAll();
    }

    /* 查找项目中的build key=>value对。*/
    public function getProjectBuildPairs($projectID)
    {
        $sysBuilds = array('' => '', 'trunk' => 'Trunk'); 
        $builds = $this->dao->select('id,name')->from(TABLE_BUILD)->where('project')->eq((int)$projectID)->orderBy('id desc')->fetchPairs();
        if(!$builds) return $sysBuilds;
        $releases = $this->dao->select('build,name')->from(TABLE_RELEASE)->where('build')->in(array_keys($builds))->fetchPairs();
        foreach($releases as $buildID => $releaseName) $builds[$buildID] = $releaseName;
        return $sysBuilds + $builds;
    }

    /* 查找产品中的build列表。*/
    public function getProductBuildPairs($productID)
    {
        $sysBuilds = array('' => '', 'trunk' => 'Trunk'); 
        $builds = $this->dao->select('id,name')->from(TABLE_BUILD)->where('product')->eq((int)$productID)->orderBy('id desc')->fetchPairs();
        if(!$builds) return $sysBuilds;
        $releases = $this->dao->select('build,name')->from(TABLE_RELEASE)->where('build')->in(array_keys($builds))->fetchPairs();
        foreach($releases as $buildID => $releaseName) $builds[$buildID] = $releaseName;
        return $sysBuilds + $builds;
    }

    /* 创建。*/
    public function create($projectID)
    {
        $build = fixer::input('post')
            ->stripTags('name')
            ->specialChars('desc')
            ->add('project', (int)$projectID)
            ->get();
        $this->dao->insert(TABLE_BUILD)->data($build)->autoCheck()->batchCheck('name,date,builder', 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /* 编辑。*/
    public function update($buildID)
    {
        $build = fixer::input('post')
            ->stripTags('name')
            ->specialChars('desc')
            ->get();
        $this->dao->update(TABLE_BUILD)->data($build)->autoCheck()->batchCheck('name,date,builder', 'notempty')->where('id')->eq((int)$buildID)->exec();
    }

    /* 删除build。*/
    public function delete($buildID)
    {
        return $this->dao->delete()->from(TABLE_BUILD)->where('id')->eq((int)$buildID)->exec();
    }
}
