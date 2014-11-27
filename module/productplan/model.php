<?php
/**
 * The model file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: model.php 4639 2013-04-11 02:06:35Z chencongzhi520@gmail.com $
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
     * Get last plan.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function getLast($productID)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->orderBy('end desc')
            ->fetch();
    }

    /**
     * Get list 
     * 
     * @param  int    $product 
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getList($product = 0, $pager = null, $orderBy = 'begin_desc')
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('product')->eq($product)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get plan pairs.
     * 
     * @param  array|int    $product 
     * @param  string       $expired 
     * @access public
     * @return array
     */
    public function getPairs($product = 0, $expired = '')
    {
        $date = date('Y-m-d');
        return array('' => '') + $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in($product)
            ->andWhere('deleted')->eq(0)
            ->beginIF($expired == 'unexpired')
            ->andWhere('end')->gt($date)
            ->fi()
            ->orderBy('begin desc')->fetchPairs();
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
            ->orderBy('begin desc')->fetchPairs();
    }

    /**
     * Create a plan.
     * 
     * @access public
     * @return int
     */
    public function create()
    {
        $plan = fixer::input('post')->stripTags($this->config->productplan->editor->create['id'], $this->config->allowedTags)->remove('delta')->get();
        $this->dao->insert(TABLE_PRODUCTPLAN)
            ->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->create->requiredFields, 'notempty')
            ->check('end', 'gt', $plan->begin)
            ->exec();
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
        $plan = fixer::input('post')->stripTags($this->config->productplan->editor->edit['id'], $this->config->allowedTags)->get();
        $this->dao->update(TABLE_PRODUCTPLAN)
            ->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->edit->requiredFields, 'notempty')
            ->check('end', 'gt', $plan->begin)
            ->where('id')->eq((int)$planID)
            ->exec();
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

    /**
     * Link bugs.
     * 
     * @param  int    $planID 
     * @access public
     * @return void
     */
    public function linkBug($planID)
    {
        $this->loadModel('story');
        $this->loadModel('action');
        foreach($this->post->bugs as $bugID)
        {
            $this->dao->update(TABLE_BUG)->set('plan')->eq((int)$planID)->where('id')->eq((int)$bugID)->exec();
            $this->action->create('bug', $bugID, 'linked2plan', '', $planID);
        }
    }

    /**
     * Unlink bug. 
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function unlinkBug($bugID)
    {
        $planID = $this->dao->findByID($bugID)->from(TABLE_BUG)->fields('plan')->fetch('plan');
        $this->dao->update(TABLE_BUG)->set('plan')->eq(0)->where('id')->eq((int)$bugID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfromplan', '', $planID);
    }
}
