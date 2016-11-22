<?php
/**
 * The model file of tutorial module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     tutorial
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class tutorialModel extends model
{
    /**
     * Check novice. 
     * 
     * @access public
     * @return bool
     */
    public function checkNovice()
    {
        $account = $this->app->user->account;
        if($account == 'guest') return false;
        if(!empty($this->app->user->modifyPassword)) return false;

        $count = $this->dao->select('count(*) as count')->from(TABLE_ACTION)->where('actor')->eq($account)->fetch('count');
        if($count < 10) return true;

        $this->loadModel('setting')->setItem($account . '.common.global.novice', 'false');
        return false;

    }

    /**
     * Get tutorial product pairs.
     * 
     * @access public
     * @return array
     */
    public function getProductPairs()
    {
        return array(1 => 'Test product');
    }

    /**
     * Get tutorial product.
     * 
     * @access public
     * @return object
     */
    public function getProduct()
    {
        $product = new stdclass();
        $product->id             = 1;
        $product->name           = 'Test product';
        $product->code           = 'test';
        $product->type           = 'normal';
        $product->status         = 'normal';
        $product->desc           = '';
        $product->PO             = $this->app->user->account;
        $product->QD             = '';
        $product->RD             = '';
        $product->acl            = 'open';
        $product->createdBy      = $this->app->user->account;
        $product->createdDate    = helper::now();
        $product->createdVersion = '8.1.3';
        $product->order          = 10;
        $product->deleted        = '0';

        return $product;
    }

    /**
     * Get tutorial stories.
     * 
     * @access public
     * @return array
     */
    public function getStories()
    {
        $story = new stdclass();
        $story->id             = 1;
        $story->product        = 1;
        $story->branch         = 0;
        $story->module         = 0;
        $story->plan           = '';
        $story->planTitle      = '';
        $story->color          = '';
        $story->source         = 'po';
        $story->fromBug        = 0;
        $story->title          = 'Test story';
        $story->keywords       = '';
        $story->type           = '';
        $story->pri            = 3;
        $story->estimate       = 1;
        $story->status         = 'active';
        $story->stage          = 'wait';
        $story->openedBy       = $this->app->user->account;
        $story->openedDate     = helper::now();
        $story->assignedTo     = '';
        $story->assignedDate   = '';
        $story->reviewedBy     = $this->app->user->account;
        $story->reviewedDate   = helper::now();
        $story->closedBy       = '';
        $story->closedDate     = '';
        $story->closedReason   = '';
        $story->toBug          = 0;
        $story->childStories   = '';
        $story->linkStories    = '';
        $story->duplicateStory = 0;
        $story->version        = 1;
        $story->deleted        = '0';

        $stories = array();
        $stories[] = $story;
        $story = json_decode(json_encode($stories[0]));
        $story->id    = 2;
        $story->title = 'Test story 2';
        $stories[] = $story;
        return $stories;
    }

    /**
     * Get tutorial project pairs.
     * 
     * @access public
     * @return array
     */
    public function getProjectPairs()
    {
        return array(1 => 'Test project');
    }

    /**
     * Get tutorial project.
     * 
     * @access public
     * @return object
     */
    public function getProject()
    {
        $project = new stdclass();
        $project->id = 1;
        $project->type = 'sprint';
        $project->name = 'Test project';
        $project->code = 'test';
        $project->begin = date('Y-m-d', strtotime('-7 days'));
        $project->end   = date('Y-m-d', strtotime('+7 days'));
        $project->days  = 10;
        $project->status  = 'wait';
        $project->pri   = '1';
        $project->desc   = '';
        $project->goal   = '';
        $project->acl   = 'open';
        return $project;
    }

    /**
     * Get tutorial project products.
     * 
     * @access public
     * @return array
     */
    public function getProjectProducts()
    {
        $product = $this->getProduct();
        return array($product->id => $product);
    }

    /**
     * Get tutorial project stories.
     * 
     * @access public
     * @return array
     */
    public function getProjectStories()
    {
        $stories = $this->getStories();
        $story   = $stories[0];
        return array($story->id => $story);
    }

    /**
     * Get tutorial project story pairs.
     * 
     * @access public
     * @return array
     */
    public function getProjectStoryPairs()
    {
        $stories = $this->getStories();
        $story   = $stories[0];
        return array($story->id => $story->title);
    }

    /**
     * Get tutorial team members.
     * 
     * @access public
     * @return array
     */
    public function getTeamMembers()
    {
        $member = new stdclass();
        $member->project    = 1;
        $member->account    = $this->app->user->account;
        $member->role       = $this->app->user->role;
        $member->join       = $this->app->user->join;
        $member->days       = 10;
        $member->hours      = 7.0;
        $member->totalHours = 70.0;
        $member->realname   = $this->app->user->realname;
        return array($member->account => $member);
    }

    /**
     * Get tutorial user pairs.
     * 
     * @access public
     * @return void
     */
    public function getUserPairs()
    {
        $account = $this->app->user->account;

        $users['']       = '';
        $users[$account] = $account;
        $users['test']   = 'Test';
        return $users;
    }
}
