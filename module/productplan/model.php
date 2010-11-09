<?php
/**
 * The model file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class productplanModel extends model
{
    /* 获取计划信息。*/
    public function getByID($planID)
    {
        return $this->dao->findByID((int)$planID)->from(TABLE_PRODUCTPLAN)->fetch();
    }

    /* 获取列表。*/
    public function getList($product = 0)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('product')->eq($product)
            ->andWhere('deleted')->eq(0)
            ->orderBy('begin')->fetchAll();
    }

    /* 获取name=>value的键值对。*/
    public function getPairs($product = 0)
    {
        return array('' => '') + $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)
            ->where('product')->eq((int)$product)
            ->andWhere('deleted')->eq(0)
            ->orderBy('begin')->fetchPairs();
    }

    /* 创建。*/
    public function create($product)
    {
        $plan = fixer::input('post')
            ->stripTags('title')
            ->specialChars('desc')
            ->get();
        $this->dao->insert(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->batchCheck($this->config->productplan->create->requiredFields, 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /* 编辑。*/
    public function update($planID)
    {
        $oldPlan = $this->getById($planID);
        $plan = fixer::input('post')
            ->stripTags('title')
            ->specialChars('desc')
            ->get();
        $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->batchCheck($this->config->productplan->edit->requiredFields, 'notempty')->where('id')->eq((int)$planID)->exec();
        if(!dao::isError()) return common::createChanges($oldPlan, $plan);

    }

    /* 关联需求。*/
    public function linkStory($planID)
    {
        $this->loadModel('story');
        $this->loadModel('action');
        foreach($this->post->stories as $storyID)
        {
            $this->dao->update(TABLE_STORY)->set('plan')->eq((int)$planID)->where('id')->eq((int)$storyID)->exec();
            $this->action->create('story', $storyID, 'linked2plan', '', $planID);
            $this->story->setStage($storyID);
        }        
    }

    /* 移除需求。*/
    public function unlinkStory($storyID)
    {
        $planID = $this->dao->findByID($storyID)->from(TABLE_STORY)->fields('plan')->fetch('plan');
        $this->dao->update(TABLE_STORY)->set('plan')->eq(0)->where('id')->eq((int)$storyID)->exec();
        $this->loadModel('story')->setStage($storyID);
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromplan', '', $planID);
    }
}
