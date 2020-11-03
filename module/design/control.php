<?php
/**
 * The control file of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: control.php 5107 2020-09-02 09:46:12Z tianshujie@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class design extends control
{
    public $products = array();

    /**
     * Construct function, load module auto.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $products = array();
        $this->loadModel('product');
        $this->view->products = $this->products = $this->product->getProductsByProject($this->session->PRJ);
    }

    /**
     * Browse designs.
     *
     * @param  int    $productID
     * @param  string $type all|bySearch|HLDS|DDS|DBDS|ADS
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $type = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session for design list and process product id. */
        $this->session->set('designList', $this->app->getURI(true));
        $productKeys = array_keys($this->products);
        if(!in_array($productID, $productKeys)) $productID = key($this->products);
        $this->design->setProductMenu($productID);

        /* Build the search form. */
        $queryID   = ($type == 'bySearch') ? (int)$param : 0;
        $actionURL = $this->createLink('design', 'browse', "productID=$productID&type=bySearch&queryID=myQueryID");
        $this->design->buildSearchForm($queryID, $actionURL);

        /* Init pager and get designs. */
        $this->app->loadClass('pager', $static = true);
        $pager   = pager::init(0, $recPerPage, $pageID);
        $designs = $this->design->getList($this->session->PRJ, $productID, $type, $queryID, $orderBy, $pager);

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->browse;
        $this->view->position[] = $this->lang->design->browse;

        $this->view->designs    = $designs;
        $this->view->type       = $type;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->productID  = $productID;
        $this->view->pager      = $pager;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Create a design.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function create($productID = 0)
    {
        if($_POST)
        {
            $designID = $this->design->create();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('design', $designID, 'created');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('design', 'browse', "productID={$this->post->product}");
            $this->send($response);
        }

        $this->design->setProductMenu($productID);

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->create;
        $this->view->position[] = $this->lang->design->create;

        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->stories    = $this->loadModel('story')->getProductStoryPairs($productID);
        $this->view->productID  = $productID;
        $this->view->program    = $this->loadModel('project')->getByID($this->session->PRJ);

        $this->display();
    }

    /**
     * Batch create designs.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function batchCreate($productID = 0)
    {
        if($_POST)
        {
            $this->design->batchCreate($productID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse', "productID=$productID");

            $this->send($response);
        }

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->batchCreate;
        $this->view->position[] = $this->lang->design->batchCreate;

        $this->view->stories = $this->loadModel('story')->getProductStoryPairs($productID);
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed');

        $this->display();
    }

    /**
     * View a design.
     *
     * @param  int    $designID
     * @access public
     * @return void
     */
    public function view($designID = 0)
    {
        $design = $this->design->getById($designID);
        $this->design->setProductMenu($design->product);

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->view;
        $this->view->position[] = $this->lang->design->view;

        $this->view->design  = $design;
        $this->view->stories = $this->loadModel('story')->getProductStoryPairs($design->product);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions = $this->loadModel('action')->getList('design', $design->id);

        $this->display();
    }

    /**
     * Edit a design.
     *
     * @param  int    $designID
     * @access public
     * @return void
     */
    public function edit($designID = 0)
    {
        $design = $this->design->getByID($designID);
        $design = $this->design->getAffectedScope($design);
        $this->design->setProductMenu($design->product);

        if($_POST)
        {
            $changes = $this->design->update($designID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('design', $designID, 'changed');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('design', 'view', "id=$design->id");
            $this->send($response);
        }

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->edit;
        $this->view->position[] = $this->lang->design->edit;

        $this->view->design   = $design;
        $this->view->program  = $this->loadModel('project')->getByID($this->session->PRJ);
        $this->view->stories  = $this->loadModel('story')->getProductStoryPairs($design->product);

        $this->display();
    }

    /**
     * Design link commits.
     *
     * @param  int    $designID
     * @param  int    $repoID
     * @param  string $begin
     * @param  string $end
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkCommit($designID = 0, $repoID = 0, $begin = '', $end = '', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        /* Get program and date. */
        $program = $this->loadModel('project')->getByID($this->session->PRJ);
        $begin   = $begin ? date('Y-m-d', strtotime($begin)) : $program->begin;
        $end     = $end ? date('Y-m-d', strtotime($end)) : helper::today();

        /* Get the repository information through the repoID. */
        $repos  = $this->loadModel('repo')->getRepoPairs($this->session->PRJ);
        $repoID = $repoID ? $repoID : key($repos);

        if(empty($repoID)) die(js::locate(helper::createLink('repo', 'create')));

        $repo      = $this->loadModel('repo')->getRepoByID($repoID);
        $revisions = $this->repo->getCommits($repo, '', 'HEAD', '', '', $begin, $end);

        if($_POST)
        {
            $this->design->linkCommit($designID, $repoID);

            $result['result']  = 'success';
            $result['message'] = $this->lang->saveSuccess;
            $result['locate']  = 'parent';
            $this->send($result);
        }

        /* Linked submission. */
        $linkedRevisions = array();
        $relations = $this->loadModel('common')->getRelations('design', $designID, 'commit');
        foreach($relations as $relation) $linkedRevisions[$relation->BID] = $relation->BID;

        foreach($revisions as $id => $commit)
        {
            if(isset($linkedRevisions[$commit->id])) unset($revisions[$id]);
        }

        /* Init pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal  = count($revisions);
        $pager     = new pager($recTotal, $recPerPage, $pageID);
        $revisions = array_chunk($revisions, $pager->recPerPage);

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->linkCommit;
        $this->view->position[] = $this->lang->design->linkCommit;

        $this->view->repos      = $repos;
        $this->view->repoID     = $repoID;
        $this->view->repo       = $repo;
        $this->view->revisions  = empty($revisions) ? $revisions : $revisions[$pageID - 1];
        $this->view->designID   = $designID;
        $this->view->begin      = $begin;
        $this->view->end        = $end;
        $this->view->design     = $this->design->getByID($designID);
        $this->view->pager      = $pager;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Design unlink commits.
     *
     * @param  int    $designID
     * @param  int    $commitID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unlinkCommit($designID = 0, $commitID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->design->confirmUnlink, inlink('unlinkCommit', "designID=$designID&commitID=$commitID&confirm=yes")));
        }
        else
        {
            $this->design->unlinkCommit($designID, $commitID);

            die(js::reload('parent'));
        }
    }

    /**
     * View a design's commit.
     *
     * @param  int    $designID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function viewCommit($designID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Init pager. */
        $this->app->loadClass('pager', $static = true);
        $pager   = pager::init(0, $recPerPage, $pageID);

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->submission;
        $this->view->position[] = $this->lang->design->submission;

        $this->view->design = $this->design->getCommit($designID, $pager);
        $this->view->pager  = $pager;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * A version of the repository.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function revision($repoID = 0)
    {
        $repo    = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->eq($repoID)->fetch();
        $repoURL = $this->createLink('repo', 'revision', "repoID=$repo->repo&revistion=$repo->revision");
        header("location:" . $repoURL);
    }

    /**
     * Delete a design.
     *
     * @param  int    $designID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($designID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->design->confirmDelete, inlink('delete', "designID=$designID&confirm=yes")));
        }
        else
        {
            $this->design->delete(TABLE_DESIGN, $designID);
            $this->dao->delete()->from(TABLE_RELATION)->where('Atype')->eq('design')->andWhere('AID')->eq($designID)->andWhere('Btype')->eq('commit')->andwhere('relation')->eq('completedin')->exec();
            $this->dao->delete()->from(TABLE_RELATION)->where('Atype')->eq('commit')->andWhere('BID')->eq($designID)->andWhere('Btype')->eq('design')->andwhere('relation')->eq('completedfrom')->exec();

            die(js::locate($this->session->designList, 'parent'));
        }
    }

    /**
     * Update assign of design.
     *
     * @param  int    $designID
     * @access public
     * @return void
     */
    public function assignTo($designID = 0)
    {
        if($_POST)
        {
            $changes = $this->design->assign($designID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action');
            if(!empty($changes))
            {
                $actionID = $this->action->create('design', $designID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('design', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->assignedTo;
        $this->view->position[] = $this->lang->design->assignedTo;

        $this->view->design = $this->design->getById($designID);
        $this->view->users  = $this->loadModel('user')->getPairs();
        $this->display();
    }
}
