<?php
/**
 * The model file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
    /**
     * Get plan by id.
     * 
     * @param  int    $planID 
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getByID($planID, $setImgSize = false)
    {
        $plan = $this->dao->findByID((int)$planID)->from(TABLE_PRODUCTPLAN)->fetch();
        if($setImgSize) $plan->desc = $this->loadModel('file')->setImgSize($plan->desc);
        return $plan;
    }

    /**
     * Get list 
     * 
     * @param  int    $product 
     * @access public
     * @return object
     */
    public function getList($product = 0)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('product')->eq($product)
            ->andWhere('deleted')->eq(0)
            ->orderBy('begin')->fetchAll();
    }

    /**
     * Get plan pairs.
     * 
     * @param  int    $product 
     * @param  string $expired 
     * @access public
     * @return array
     */
    public function getPairs($product = 0, $expired = '')
    {
        $date = date('Y-m-d');
        return array('' => '') + $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)
            ->where('product')->eq((int)$product)
            ->andWhere('deleted')->eq(0)
            ->beginIF($expired == 'unexpired')
            ->andWhere('end')->gt($date)
            ->fi()
            ->orderBy('begin')->fetchPairs();
    }

    /**
     * Get plans for products 
     * 
     * @param  int    $products 
     * @access public
     * @return void
     */
    public function getForProducts($products)
    {
        return array('' => '') + $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in(array_keys($products))
            ->andWhere('deleted')->eq(0)
            ->orderBy('begin')->fetchPairs();
    }

    /**
     * Create a plan.
     * 
     * @access public
     * @return int
     */
    public function create()
    {
        $plan = fixer::input('post')->stripTags('title')->get();
        $this->dao->insert(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->batchCheck($this->config->productplan->create->requiredFields, 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /**
     * Update a plan
     * 
     * @param  int    $planID 
     * @access public
     * @return array
     */
    public function update($planID)
    {
        $oldPlan = $this->getById($planID);
        $plan = fixer::input('post')->stripTags('title')->get();
        $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->batchCheck($this->config->productplan->edit->requiredFields, 'notempty')->where('id')->eq((int)$planID)->exec();
        if(!dao::isError()) return common::createChanges($oldPlan, $plan);
    }

    /**
     * Link stories.
     * 
     * @param  int    $planID 
     * @access public
     * @return void
     */
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

    /**
     * Unlink story 
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function unlinkStory($storyID)
    {
        $planID = $this->dao->findByID($storyID)->from(TABLE_STORY)->fields('plan')->fetch('plan');
        $this->dao->update(TABLE_STORY)->set('plan')->eq(0)->where('id')->eq((int)$storyID)->exec();
        $this->loadModel('story')->setStage($storyID);
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromplan', '', $planID);
    }
}
