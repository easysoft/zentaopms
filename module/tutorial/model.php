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

        $this->loadModel('setting')->setItem($account . '.common.global.novice', 0);
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
        $story->order          = '0';

        $stories = array();
        $stories[] = $story;
        $story = json_decode(json_encode($stories[0]));
        $story->id    = 2;
        $story->title = 'Test story 2';
        $stories[] = $story;
        return $stories;
    }

    /**
     * Get tutorial Execution pairs.
     *
     * @access public
     * @return array
     */
    public function getExecutionPairs()
    {
        return array(1 => 'Test execution');
    }

    /**
     * Get tutorial execution.
     *
     * @access public
     * @return object
     */
    public function getExecution()
    {
        $execution = new stdclass();
        $execution->id = 1;
        $execution->type = 'sprint';
        $execution->name = 'Test execution';
        $execution->code = 'test';
        $execution->begin = date('Y-m-d', strtotime('-7 days'));
        $execution->end   = date('Y-m-d', strtotime('+7 days'));
        $execution->days  = 10;
        $execution->status  = 'wait';
        $execution->pri   = '1';
        $execution->desc   = '';
        $execution->goal   = '';
        $execution->acl   = 'open';
        return $execution;
    }

    /**
     * Get tutorial execution products.
     *
     * @access public
     * @return array
     */
    public function getExecutionProducts()
    {
        $product = $this->getProduct();
        return array($product->id => $product);
    }

    /**
     * Get tutorial execution stories.
     *
     * @access public
     * @return array
     */
    public function getExecutionStories()
    {
        $stories = $this->getStories();
        $story   = $stories[0];
        return array($story->id => $story);
    }

    /**
     * Get tutorial execution story pairs.
     *
     * @access public
     * @return array
     */
    public function getExecutionStoryPairs()
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
        $member->project     = 1;
        $member->account     = $this->app->user->account;
        $member->role        = $this->app->user->role;
        $member->join        = $this->app->user->join;
        $member->days        = 10;
        $member->hours       = 7.0;
        $member->totalHours  = 70.0;
        $member->realname    = $this->app->user->realname;
        $member->limited     = 'no';
        return array($member->account => $member);
    }

    /**
     * Get team members pairs.
     *
     * @access public
     * @return array
     */
    public function getTeamMembersPairs()
    {
        $account = $this->app->user->account;
        return array('' => '', $account => $this->app->user->realname);
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

    /**
     * Get tutorialed.
     *
     * @access public
     * @return string
     */
    public function getTutorialed()
    {
        return $this->dao->select('*')->from(TABLE_CONFIG)->where('module')->eq('tutorial')->andWhere('owner')->eq($this->app->user->account)->andWhere('section')->eq('tasks')->andWhere('`key`')->eq('setting')->fetch('value');
    }
}
