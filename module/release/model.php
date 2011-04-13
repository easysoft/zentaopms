<?php
/**
 * The model file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class releaseModel extends model
{
    /**
     * Get release by id.
     * 
     * @param  int    $releaseID 
     * @access public
     * @return object
     */
    public function getByID($releaseID)
    {
        return $this->dao->select('t1.*, t2.name as buildName, t3.name as productName')
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.build = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.id')->eq((int)$releaseID)
            ->orderBy('t1.id DESC')
            ->fetch();
    }

    /**
     * Get list of releases.
     * 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function getList($productID)
    {
        return $this->dao->select('t1.*, t2.name as productName, t3.name as buildName')
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build = t3.id')
            ->where('t1.product')->eq((int)$productID)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id DESC')
            ->fetchAll();
    }

    /**
     * Create a release.
     * 
     * @param  int    $productID 
     * @access public
     * @return int
     */
    public function create($productID)
    {
        $release = fixer::input('post')->stripTags('name')->add('product', (int)$productID)->get();
        $this->dao->insert(TABLE_RELEASE)->data($release)->autoCheck()->batchCheck($this->config->release->create->requiredFields, 'notempty')->check('name','unique')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /**
     * Update a release.
     * 
     * @param  int    $releaseID 
     * @access public
     * @return void
     */
    public function update($releaseID)
    {
        $oldRelease = $this->getByID($releaseID);
        $release = fixer::input('post')->stripTags('name')->get();
        $this->dao->update(TABLE_RELEASE)->data($release)->autoCheck()->batchCheck($this->config->release->edit->requiredFields, 'notempty')->where('id')->eq((int)$releaseID)->check('name','unique')->exec();
        if(!dao::isError()) return common::createChanges($oldRelease, $release);
    }
}
