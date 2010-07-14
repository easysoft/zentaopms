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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id$
 * @link        http://www.zentaoms.com
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
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id DESC')
            ->fetchAll();
    }

    /* 查找项目中的build列表。params='noempty|notrunk' */
    public function getProjectBuildPairs($projectID, $productID, $params = '')
    {
        $sysBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => 'Trunk');

        $builds = $this->dao->select('id,name')->from(TABLE_BUILD)
            ->where('project')->eq((int)$projectID)
            ->andWhere('product')->eq((int)$productID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')->fetchPairs();
        if(!$builds) return $sysBuilds;
        $releases = $this->dao->select('build,name')->from(TABLE_RELEASE)
            ->where('build')->in(array_keys($builds))
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
        foreach($releases as $buildID => $releaseName) $builds[$buildID] = $releaseName;
        return $sysBuilds + $builds;
    }

    /* 查找产品中的build列表。params='noempty|notrunk' */
    public function getProductBuildPairs($productID, $params = '')
    {
        $sysBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => 'Trunk');

        $builds = $this->dao->select('id,name')->from(TABLE_BUILD)
            ->where('product')->eq((int)$productID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')->fetchPairs();
        if(!$builds) return $sysBuilds;
        $releases = $this->dao->select('build,name')->from(TABLE_RELEASE)
            ->where('build')->in(array_keys($builds))
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
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
        $this->dao->insert(TABLE_BUILD)->data($build)->autoCheck()->batchCheck($this->config->build->create->requiredFields, 'notempty')->check('name','unique')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /* 编辑。*/
    public function update($buildID)
    {
        $oldBuild = $this->getByID($buildID);
        $build = fixer::input('post')
            ->stripTags('name')
            ->specialChars('desc')
            ->get();
        $this->dao->update(TABLE_BUILD)->data($build)->autoCheck()->batchCheck($this->config->build->edit->requiredFields, 'notempty')->where('id')->eq((int)$buildID)->exec();
        if(!dao::isError()) return common::createChanges($oldBuild, $build);
    }
}
