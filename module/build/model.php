<?php
/**
 * The model file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class buildModel extends model
{
    /**
     * Get build info.
     * 
     * @param  int    $buildID 
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getByID($buildID, $setImgSize = false)
    {
        $build = $this->dao->select('t1.*, t2.name as projectName, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.id')->eq((int)$buildID)
            ->orderBy('t1.id DESC')
            ->fetch();
        if(!$build) return false;
        if($setImgSize) $build->desc = $this->loadModel('file')->setImgSize($build->desc);
        return $build;
    }

    /**
     * Get builds of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getProjectBuilds($projectID)
    {
        return $this->dao->select('t1.*, t2.name as projectName, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.date DESC, t1.id desc')
            ->fetchAll();
    }

    /**
     * Get builds of a project in pairs. 
     * 
     * @param  int    $projectID 
     * @param  int    $productID 
     * @param  string $params       noempty|notrunk, can be a set of them
     * @access public
     * @return array
     */
    public function getProjectBuildPairs($projectID, $productID, $params = '')
    {
        $sysBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => 'Trunk');

        $builds = $this->dao->select('id,name')->from(TABLE_BUILD)
            ->where('project')->eq((int)$projectID)
            ->beginIF($productID)->andWhere('product')->eq((int)$productID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('date desc, id desc')->fetchPairs();
        if(!$builds) return $sysBuilds;
        $releases = $this->dao->select('build,name')->from(TABLE_RELEASE)
            ->where('build')->in(array_keys($builds))
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
        foreach($releases as $buildID => $releaseName) $builds[$buildID] = $releaseName;
        return $sysBuilds + $builds;
    }

    /**
     * Get builds of a product in pairs. 
     * 
     * @param  int    $productID 
     * @param  string $params       noempty|notrunk, can be a set of them
     * @access public
     * @return string
     */
    public function getProductBuildPairs($productID, $params = '')
    {
        $sysBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => 'Trunk');

        $builds = $this->dao->select('id,name')->from(TABLE_BUILD)
            ->where('product')->eq((int)$productID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('date desc, id desc')->fetchPairs();
        if(!$builds) return $sysBuilds;
        return $sysBuilds + $builds;
    }

    /**
     * Create a build
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function create($projectID)
    {
        $build->stories = '';
        $build->bugs    = '';

        $build = fixer::input('post')->stripTags('name')
            ->join('stories', ',')
            ->join('bugs', ',')
            ->remove('allchecker')
            ->add('project', (int)$projectID)->get();
        $this->dao->insert(TABLE_BUILD)->data($build)->autoCheck()->batchCheck($this->config->build->create->requiredFields, 'notempty')->check('name','unique')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /**
     * Update a build.
     * 
     * @param  int    $buildID 
     * @access public
     * @return void
     */
    public function update($buildID)
    {
        $oldBuild = $this->getByID($buildID);
        $build = fixer::input('post')
            ->stripTags('name')
            ->setDefault('stories', '')
            ->setDefault('bugs', '')
            ->join('stories', ',')
            ->join('bugs', ',')
            ->remove('allchecker')
            ->get();
        $this->dao->update(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$buildID)
            ->check('name','unique', "id != $buildID")
            ->exec();
        if(!dao::isError()) return common::createChanges($oldBuild, $build);
    }
}
