<?php
/**
 * The model file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        $plan = $this->loadModel('file')->replaceImgURL($plan, 'desc');
        if($setImgSize) $plan->desc = $this->file->setImgSize($plan->desc);
        return $plan;
    }

    /**
     * Get plans by idList 
     * 
     * @param  int    $planIDList 
     * @access public
     * @return array
     */
    public function getByIDList($planIDList)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($planIDList)->orderBy('begin desc')->fetchAll('id');
    }

    /**
     * Get last plan.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function getLast($productID, $branch = 0)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->eq($branch)->fi()
            ->orderBy('end desc')
            ->limit(1)
            ->fetch();
    }

    /**
     * Get list 
     * 
     * @param  int    $product 
     * @param  int    $branch
     * @param  string $browseType
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getList($product = 0, $branch = 0, $browseType = 'all', $pager = null, $orderBy = 'begin_desc')
    {
        $date = date('Y-m-d');
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('product')->eq($product)
            ->andWhere('deleted')->eq(0)
            ->beginIF(!empty($branch))->andWhere('branch')->eq($branch)->fi()
            ->beginIF($browseType == 'unexpired')->andWhere('end')->gt($date)->fi()
            ->beginIF($browseType == 'overdue')->andWhere('end')->le($date)->fi()
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
    public function getPairs($product = 0, $branch = 0, $expired = '')
    {
        $date = date('Y-m-d');
        $plans = $this->dao->select('id,CONCAT(title, " [", begin, " ~ ", end, "]") as title')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in($product)
            ->andWhere('deleted')->eq(0)
            ->beginIF($branch)->andWhere("branch")->in("0,$branch")->fi()
            ->beginIF($expired == 'unexpired')->andWhere('end')->gt($date)->fi()
            ->orderBy('begin desc')
            ->fetchPairs();

        if($expired == 'unexpired' and empty($plans))
        {
            $plans = $this->dao->select('id,CONCAT(title, " [", begin, " ~ ", end, "]") as title')->from(TABLE_PRODUCTPLAN)
                ->where('product')->in($product)
                ->andWhere('deleted')->eq(0)
                ->beginIF($branch)->andWhere("branch")->in("0,$branch")->fi()
                ->orderBy('begin desc')
                ->limit(5)
                ->fetchPairs();
        }

        return array('' => '') + $plans;
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
        $plan = fixer::input('post')->stripTags($this->config->productplan->editor->create['id'], $this->config->allowedTags)->remove('delta,uid')->get();
        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->plan->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PRODUCTPLAN)
            ->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->create->requiredFields, 'notempty')
            ->check('end', 'gt', $plan->begin)
            ->exec();
        if(!dao::isError())
        {
            $planID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $planID, 'plan');
            return $planID;
        }
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
        $oldPlan = $this->dao->findByID((int)$planID)->from(TABLE_PRODUCTPLAN)->fetch();
        $plan = fixer::input('post')->stripTags($this->config->productplan->editor->edit['id'], $this->config->allowedTags)->remove('delta,uid')->get();
        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->plan->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_PRODUCTPLAN)
            ->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->edit->requiredFields, 'notempty')
            ->check('end', 'gt', $plan->begin)
            ->where('id')->eq((int)$planID)
            ->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $planID, 'plan');
            return common::createChanges($oldPlan, $plan);
        }
    }

    /**
     * Batch update plan.
     * 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function batchUpdate($productID)
    {
        $data = fixer::input('post')->skipSpecial('desc')->get();
        $oldPlans = $this->getByIDList($data->id);

        $this->app->loadClass('purifier', true);
        $config   = HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $purifier = new HTMLPurifier($config);

        $plans = array();
        foreach($data->id as $planID)
        {
            $plan = new stdclass();
            $plan->title = $data->title[$planID];
            $plan->desc  = $purifier->purify($data->desc[$planID]);
            $plan->begin = $data->begin[$planID];
            $plan->end   = $data->end[$planID];

            if(empty($plan->title))die(js::alert(sprintf($this->lang->productplan->errorNoTitle, $planID)));
            if(empty($plan->begin))die(js::alert(sprintf($this->lang->productplan->errorNoBegin, $planID)));
            if(empty($plan->end))  die(js::alert(sprintf($this->lang->productplan->errorNoEnd, $planID)));
            if($plan->begin > $plan->end) die(js::alert(sprintf($this->lang->productplan->beginGeEnd, $planID)));

            $plans[$planID] = $plan;
        }

        $changes = array();
        foreach($plans as $planID => $plan)
        {
            $change = common::createChanges($oldPlans[$planID], $plan);
            if($change)
            {
                $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->where('id')->eq($planID)->exec();
                if(dao::isError()) die(js::error(dao::getError()));
                $changes[$planID] = $change;
            }
        }

        return $changes;
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
            if($this->session->currentProductType == 'normal')
            {
                $this->dao->update(TABLE_STORY)->set("plan")->eq($planID)->where('id')->eq((int)$storyID)->exec();
            }
            else
            {
                $this->dao->update(TABLE_STORY)->set("plan")->eq($planID)->where('id')->eq((int)$storyID)->andWhere('branch')->ne('0')->exec();
                $this->dao->update(TABLE_STORY)->set("plan=CONCAT(plan, ',', $planID)")->where('id')->eq((int)$storyID)->andWhere('branch')->eq('0')->exec();
            }
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
    public function unlinkStory($storyID, $planID)
    {
        $story = $this->dao->findByID($storyID)->from(TABLE_STORY)->fetch();
        $plans = array_unique(explode(',', trim(str_replace(",$planID,", ',', ',' . trim($story->plan) . ','). ',')));
        $this->dao->update(TABLE_STORY)->set('plan')->eq(join(',', $plans))->where('id')->eq((int)$storyID)->exec();
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
            $this->dao->update(TABLE_BUG)->set('plan')->eq($planID)->where('id')->eq((int)$bugID)->exec();
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
        $planID = $this->dao->findByID($bugID)->from(TABLE_BUG)->fetch('plan');
        $this->dao->update(TABLE_BUG)->set('plan')->eq(0)->where('id')->eq((int)$bugID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfromplan', '', $planID);
    }
}
