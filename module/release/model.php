<?php
/**
 * The model file of release module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class releaseModel extends model
{
    /* 获取release详细信息。*/
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

    /* 查找release列表。*/
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

    /* 创建。*/
    public function create($productID)
    {
        $release = fixer::input('post')
            ->stripTags('name')
            ->specialChars('desc')
            ->add('product', (int)$productID)
            ->get();
        $this->dao->insert(TABLE_RELEASE)->data($release)->autoCheck()->batchCheck($this->config->release->create->requiredFields, 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /* 编辑。*/
    public function update($releaseID)
    {
        $oldRelease = $this->getByID($releaseID);
        $release = fixer::input('post')
            ->stripTags('name')
            ->specialChars('desc')
            ->get();
        $this->dao->update(TABLE_RELEASE)->data($release)->autoCheck()->batchCheck($this->config->release->edit->requiredFields, 'notempty')->where('id')->eq((int)$releaseID)->exec();
        if(!dao::isError()) return common::createChanges($oldRelease, $release);
    }
}
