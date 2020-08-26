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
        if(!$plan) return false;

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
     * @param  int    $branch
     * @param  int    $parent
     * @access public
     * @return object
     */
    public function getLast($productID, $branch = 0, $parent = 0)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->beginIF($parent <= 0)->andWhere('parent')->le((int)$parent)->fi()
            ->beginIF($parent > 0)->andWhere('parent')->eq((int)$parent)->fi()
            ->andWhere('product')->eq((int)$productID)
            ->andWhere('end')->ne('2030-01-01')
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
        $date  = date('Y-m-d');
        $plans = $this->dao->select('t1.*,t2.project')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on("t2.plan = t1.id and t2.product = '$product'")
            ->where('t1.product')->eq($product)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!empty($branch))->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($browseType == 'unexpired')->andWhere('t1.end')->ge($date)->fi()
            ->beginIF($browseType == 'overdue')->andWhere('t1.end')->lt($date)->fi()
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        if(!empty($plans))
        {
            $plans      = $this->reorder4Children($plans);
            $planIdList = array_keys($plans);

            $product = $this->loadModel('product')->getById($product);
            if($product->type == 'normal')
            {
                $storyGroups = $this->dao->select('id,plan,estimate')->from(TABLE_STORY)
                    ->where("plan")->in($planIdList)
                    ->andWhere('deleted')->eq(0)
                    ->fetchGroup('plan', 'id');
            }

            $bugs = $this->dao->select('*')->from(TABLE_BUG)->where("plan")->in($planIdList)->andWhere('deleted')->eq(0)->fetchGroup('plan', 'id');
            $parentStories = $parentBugs = $parentHour = array();
            foreach($plans as $plan)
            {
                if($product->type == 'normal')
                {
                    $stories    = zget($storyGroups, $plan->id, array());
                    $storyPairs = array();
                    foreach($stories as $story) $storyPairs[$story->id] = $story->estimate;
                }
                else
                {
                    $storyPairs = $this->dao->select('id,estimate')->from(TABLE_STORY)
                        ->where("CONCAT(',', plan, ',')")->like("%,{$plan->id},%")
                        ->andWhere('deleted')->eq(0)
                        ->fetchPairs('id', 'estimate');
                }
                $plan->stories   = count($storyPairs);
                $plan->bugs      = isset($bugs[$plan->id]) ? count($bugs[$plan->id]) : 0;
                $plan->hour      = array_sum($storyPairs);
                $plan->projectID = $plan->project;

                if(!isset($parentStories[$plan->parent])) $parentStories[$plan->parent] = 0;
                if(!isset($parentBugs[$plan->parent]))    $parentBugs[$plan->parent]    = 0;
                if(!isset($parentHour[$plan->parent]))    $parentHour[$plan->parent]    = 0;

                $parentStories[$plan->parent] += $plan->stories;
                $parentBugs[$plan->parent]    += $plan->bugs;
                $parentHour[$plan->parent]    += $plan->hour;
            }

            unset($parentStories[0]);
            unset($parentBugs[0]);
            unset($parentHour[0]);
            foreach($parentStories as $parentID => $count)
            {
                if(!isset($plans[$parentID])) continue;
                $plan = $plans[$parentID];
                $plan->stories += $count;
                $plan->bugs    += $parentBugs[$parentID];
                $plan->hour    += $parentHour[$parentID];
            }
        }
        return $plans;
    }

    /**
     * Get plan pairs.
     *
     * @param  array|int    $product
     * @param  int          $branch
     * @param  string       $expired
     * @access public
     * @return array
     */
    public function getPairs($product = 0, $branch = 0, $expired = '')
    {
        $date = date('Y-m-d');
        $plans = $this->dao->select('id,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in($product)
            ->andWhere('deleted')->eq(0)
            ->beginIF($branch)->andWhere("branch")->in("0,$branch")->fi()
            ->beginIF($expired == 'unexpired')->andWhere('end')->ge($date)->fi()
            ->orderBy('begin desc')
            ->fetchAll('id');

        if($expired == 'unexpired')
        {
            $plans += $this->dao->select('id,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
                ->where('product')->in($product)
                ->andWhere('deleted')->eq(0)
                ->andWhere('end')->lt($date)
                ->beginIF($branch)->andWhere("branch")->in("0,$branch")->fi()
                ->beginIF($plans)->andWhere("id")->notIN(array_keys($plans))->fi()
                ->orderBy('begin desc')
                ->limit(5)
                ->fetchAll('id');
        }

        $plans       = $this->reorder4Children($plans);
        $planPairs   = array();
        $parent      = 0;
        $parentTitle = '';
        foreach($plans as $plan)
        {
            if($plan->parent == '-1')
            {
                $parent      = $plan->id;
                $parentTitle = $plan->title;
            }
            if($plan->parent > 0 and $plan->parent == $parent) $plan->title = $parentTitle . ' /' . $plan->title;
            $planPairs[$plan->id] = $plan->title . " [{$plan->begin} ~ {$plan->end}]";
            if($plan->begin == '2030-01-01' and $plan->end == '2030-01-01') $planPairs[$plan->id] = $plan->title . ' ' . $this->lang->productplan->future;
        }
        return array('' => '') + $planPairs;
    }

    /**
     * Get plan pairs for story.
     *
     * @param  array|int    $product
     * @param  int          $branch
     * @access public
     * @return array
     */
    public function getPairsForStory($product = 0, $branch = 0)
    {
        $date = date('Y-m-d');
        $plans = $this->dao->select('id,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in($product)
            ->andWhere('deleted')->eq(0)
            ->andWhere('end')->ge($date)
            ->beginIF($branch)->andWhere("branch")->in("0,$branch")->fi()
            ->orderBy('begin desc')
            ->fetchAll('id');

        if(!$plans)
        {
            $plans = $this->dao->select('id,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
                ->where('product')->in($product)
                ->andWhere('deleted')->eq(0)
                ->andWhere('end')->lt($date)
                ->beginIF($branch)->andWhere("branch")->in("0,$branch")->fi()
                ->orderBy('begin desc')
                ->limit(5)
                ->fetchAll('id');
        }

        $plans       = $this->reorder4Children($plans);
        $planPairs   = array();
        $parent      = 0;
        $parentTitle = '';
        foreach($plans as $plan)
        {
            if($plan->parent == '-1')
            {
                $parent      = $plan->id;
                $parentTitle = $plan->title;
            }
            if($plan->parent > 0 and $plan->parent == $parent) $plan->title = $parentTitle . ' /' . $plan->title;
            $planPairs[$plan->id] = $plan->title . " [{$plan->begin} ~ {$plan->end}]";
            if($plan->begin == '2030-01-01' and $plan->end == '2030-01-01') $planPairs[$plan->id] = $plan->title . ' ' . $this->lang->productplan->future;
        }

        return array('' => '') + $planPairs;
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
        $plans = $this->dao->select('id,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in(array_keys($products))
            ->andWhere('deleted')->eq(0)
            ->orderBy('begin desc')
            ->fetchAll('id');

        $plans       = $this->reorder4Children($plans);
        $planPairs   = array();
        $parent      = 0;
        $parentTitle = '';
        foreach($plans as $plan)
        {
            if($plan->parent == '-1')
            {
                $parent      = $plan->id;
                $parentTitle = $plan->title;
            }
            if($plan->parent > 0 and $plan->parent == $parent) $plan->title = $parentTitle . ' /' . $plan->title;
            $planPairs[$plan->id] = $plan->title;
        }
        return array('' => '') + $planPairs;
    }

    /**
     * Get Children plan.
     * 
     * @param  int    $planID 
     * @access public
     * @return array
     */
    public function getChildren($planID)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('parent')->eq((int)$planID)->andWhere('deleted')->eq('0')->fetchAll();
    }

    /**
     * Create a plan.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $plan = fixer::input('post')->stripTags($this->config->productplan->editor->create['id'], $this->config->allowedTags)
            ->setIF($this->post->future || empty($_POST['begin']), 'begin', '2030-01-01')
            ->setIF($this->post->future || empty($_POST['end']), 'end', '2030-01-01')
            ->remove('delta,uid,future')
            ->get();
        if(!$this->post->future and strpos($this->config->productplan->create->requiredFields, 'begin') !== false and empty($_POST['begin']))
        {
            dao::$errors['begin'] = sprintf($this->lang->error->notempty, $this->lang->productplan->begin);
        }
        if(!$this->post->future and strpos($this->config->productplan->create->requiredFields, 'end') !== false and empty($_POST['end']))
        {
            dao::$errors['end'] = sprintf($this->lang->error->notempty, $this->lang->productplan->end);
        }
        if(dao::isError()) return false;

        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->productplan->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PRODUCTPLAN)
            ->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->create->requiredFields, 'notempty')
            ->checkIF(!$this->post->future && !empty($_POST['begin']) && !empty($_POST['end']), 'end', 'gt', $plan->begin)
            ->exec();
        if(!dao::isError())
        {
            $planID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $planID, 'plan');
            $this->loadModel('score')->create('productplan', 'create', $planID);
            if(!empty($plan->parent)) $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq('-1')->where('id')->eq($plan->parent)->andWhere('parent')->eq('0')->exec();
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
        $plan = fixer::input('post')->stripTags($this->config->productplan->editor->edit['id'], $this->config->allowedTags)
            ->setIF($this->post->future || empty($_POST['begin']), 'begin', '2030-01-01')
            ->setIF($this->post->future || empty($_POST['end']), 'end', '2030-01-01')
            ->remove('delta,uid,future')
            ->get();
        if(!$this->post->future and strpos($this->config->productplan->edit->requiredFields, 'begin') !== false and empty($_POST['begin']))
        {
            dao::$errors['begin'] = sprintf($this->lang->error->notempty, $this->lang->productplan->begin);
        }
        if(!$this->post->future and strpos($this->config->productplan->edit->requiredFields, 'end') !== false and empty($_POST['end']))
        {
            dao::$errors['end'] = sprintf($this->lang->error->notempty, $this->lang->productplan->end);
        }
        if(dao::isError()) return false;

        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->productplan->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_PRODUCTPLAN)
            ->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->edit->requiredFields, 'notempty')
            ->checkIF(!$this->post->future && !empty($_POST['begin']) && !empty($_POST['end']), 'end', 'gt', $plan->begin)
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
     * Change parent field by planID.
     * 
     * @param  int    $planID 
     * @access public
     * @return void
     */
    public function changeParentField($planID)
    {
        $plan = $this->getById($planID);
        if($plan->parent <= 0) return true;

        $childCount = count($this->getChildren($plan->parent));
        $parent     = $childCount == 0 ? '0' : '-1';

        $parentPlan = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->eq($plan->parent)->andWhere('deleted')->eq(0)->fetch();
        if($parentPlan)
        {
            $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq($parent)->where('id')->eq((int)$plan->parent)->exec();
        }
        else
        {
            $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq('0')->where('id')->eq((int)$planID)->exec();
        }
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

        $stories = $this->story->getByList($this->post->stories);
        $plan    = $this->getByID($planID);

        $currentOrder = $plan->order;
        foreach($this->post->stories as $storyID)
        {
            if(!isset($stories[$storyID])) continue;

            $story = $stories[$storyID];
            if(strpos(",$story->plan,", ",{$planID},") !== false) continue;

            /* Fix Bug #1538*/
            $currentOrder = $currentOrder . $storyID . ',';
            $oldOrder = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where("id")->eq($story->plan)->fetch('order'); 
            $oldOrder = explode(',', $oldOrder);
            unset($oldOrder[array_search($storyID,  $oldOrder)]);
            $oldOrder = implode(',', $oldOrder);
            $this->dao->update(TABLE_PRODUCTPLAN)->set("order")->eq($oldOrder)->where('id')->eq($story->plan)->exec();

            if($this->session->currentProductType == 'normal' or $story->branch != 0 or empty($story->plan))
            {
                $this->dao->update(TABLE_STORY)->set("plan")->eq($planID)->where('id')->eq((int)$storyID)->exec();
            }
            else
            {
                $plans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($story->plan)->fetchPairs('branch', 'id');
                $plans[$plan->branch] = $planID;
                $this->dao->update(TABLE_STORY)->set("plan")->eq(join(',', $plans))->where('id')->eq((int)$storyID)->andWhere('branch')->eq('0')->exec();
            }
            $this->action->create('story', $storyID, 'linked2plan', '', $planID);
            $this->story->setStage($storyID);

        }

        $this->dao->update(TABLE_PRODUCTPLAN)->set("order")->eq($currentOrder)->where('id')->eq((int)$planID)->exec();
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

        /* Fix Bug #1538. */ 
        $oldOrder = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where("id")->eq($story->plan)->fetch('order');
        $oldOrder = explode(',', $oldOrder);
        unset($oldOrder[array_search($storyID, $oldOrder)]);
        $oldOrder = implode(',', $oldOrder);
        $this->dao->update(TABLE_PRODUCTPLAN)->set('order')->eq($oldOrder)->where('id')->eq($story->plan)->exec();

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

        $bugs = $this->loadModel('bug')->getByList($this->post->bugs);
        foreach($this->post->bugs as $bugID)
        {
            if(!isset($bugs[$bugID])) continue;

            $bug = $bugs[$bugID];
            if($bug->plan == $planID) continue;

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

    /**
     * Reorder for children plans.
     * 
     * @param  array    $plans 
     * @access public
     * @return array
     */
    public function reorder4Children($plans)
    {
        /* Get children and unset. */
        $childrenPlans = array();
        foreach($plans as $plan)
        {
            if($plan->parent > 0)
            {
                $childrenPlans[$plan->parent][$plan->id] = $plan;
                if(isset($plans[$plan->parent])) unset($plans[$plan->id]);
            }
        }

        if(!empty($childrenPlans))
        {
            /* Append to parent plan. */
            $reorderedPlans = array();
            foreach($plans as $plan)
            {
                $reorderedPlans[$plan->id] = $plan;
                if(isset($childrenPlans[$plan->id]))
                {
                    $plan->children = count($childrenPlans[$plan->id]);
                    foreach($childrenPlans[$plan->id] as $childrenPlan) $reorderedPlans[$childrenPlan->id] = $childrenPlan;
                }
            }
            $plans = $reorderedPlans;
        }

        return $plans;
    }
}
