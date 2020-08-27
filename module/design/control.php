<?php
/**
 * The control file of design currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     design
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class design extends control
{
    /**
     * Browse designs.
     *
     * @param  int    $productID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $type = 'all', $param = '',  $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $productID = $this->loadModel('product')->saveState($productID, $this->product->getPairs('nocode'));

        $queryID   = ($type == 'bySearch') ? (int)$param : 0;
        /* Build the search form. */
        $actionURL = $this->createLink('design', 'browse', "productID=$productID&type=bySearch&queryID=myQueryID");
        $this->design->buildSearchForm($queryID, $actionURL);

        $this->design->setProductMenu($productID);
        $product = $this->loadModel('product')->getById($productID);
        $program = $this->loadModel('project')->getById($product->program);

        $this->app->session->set('designList', $this->app->getURI(true));

        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPager = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $designs = $this->design->getList($productID, $type, $queryID, $orderBy, $pager);

        $this->view->title      = $this->lang->design->browse;
        $this->view->position[] = $this->lang->design->browse;

        $this->view->designs    = $designs;
        $this->view->type       = $type;
        $this->view->param      = $param;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
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
     * @param  string $prevModule
     * @param  int    $prevID
     * @access public
     * @return void
     */
    public function create($productID = 0)
    {
        $productID = $this->loadModel('product')->saveState($productID, $this->product->getPairs('nocode'));
        $this->design->setProductMenu($productID);

        if($_POST)
        {
            $productID = $this->post->product;
            $designID  = $this->design->create();
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('design', $designID, 'created');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('design', 'browse', "productID=$productID");
            $this->send($response);
        }

        $this->view->title      = $this->lang->design->create;
        $this->view->position[] = $this->lang->design->create;

        $this->view->users     = $this->loadModel('user')->getPairs('noclosed');
        $this->view->stories   = $this->loadModel('story')->getProductStoryPairs($productID);
        $this->view->products  = $this->loadModel('product')->getPairs($this->session->program);
        $this->view->productID = $productID;
        $this->view->program   = $this->loadModel('project')->getByID($this->session->program);

        $this->display();
    }

    /**
     * Batch create
     *
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
            $response['locate']  = inlink('browse');

            $this->send($response);
        }

        $this->view->title      = $this->lang->design->batchCreate;
        $this->view->position[] = $this->lang->design->batchCreate;

        $typeList             = (array)$this->lang->design->typeList;

        $this->view->typeList = $typeList;
        $this->view->stories  = $this->loadModel('story')->getProductStoryPairs($productID);
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed');
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

        $this->view->title      = $this->lang->design->designView;
        $this->view->position[] = $this->lang->design->designView;

        $this->view->design    = $design;
        $this->view->stories   = $this->loadModel('story')->getProductStoryPairs($design->product);
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions   = $this->loadModel('action')->getList('design', $design->id);

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

        $this->view->title      = $this->lang->design->edit;
        $this->view->position[] = $this->lang->design->edit;

        $this->view->design   = $design;
        $this->view->products = $this->loadModel('product')->getPairs($this->session->program);
        $this->view->program  = $this->loadModel('project')->getByID($this->session->program);
        $this->view->stories  = $this->loadModel('story')->getProductStoryPairs($design->product);

        $this->display();
    }

    /**
     * Commit a design.
     *
     * @param  int    $designID
     * @param  string $begin
     * @param  string $end
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function commit($designID, $repoID = 0, $begin = '', $end = '', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $program = $this->loadModel('project')->getByID($this->session->program);
        $begin   = $begin ? date('Y-m-d', strtotime($begin)) : $program->begin;
        $end     = $end ? date('Y-m-d', strtotime($end)) : helper::today();

        $repos     = $this->loadModel('repo')->getRepoPairs();
        $repoID    = $repoID ? $repoID : key($repos);
        $repo      = $repoID ? $this->loadModel('repo')->getRepoByID($repoID) : '';

        $revisions = $repo ? $this->repo->getCommits($repo, '', 'HEAD', '', $pager, $begin, $end) : '';

        if($_POST)
        {
            $this->design->linkCommit($designID, $repoID);

            $result['result']  = 'success';
            $result['message'] = $this->lang->saveSuccess;
            $result['locate']  = 'parent';
            $this->send($result);
        }

        $linkedRevisions = array();
        $relations = $this->loadModel('common')->getRelations('design', $designID, 'commit');
        foreach($relations as $relation) $linkedRevisions[] = $relation->BID;

        $this->view->title           = $this->lang->design->commit;
        $this->view->position[]      = $this->lang->design->commit;

        $this->view->repos           = $repos;
        $this->view->repoID          = $repoID;
        $this->view->repo            = $repo;
        $this->view->revisions       = $revisions;
        $this->view->linkedRevisions = $linkedRevisions;
        $this->view->designID        = $designID;
        $this->view->begin           = $begin;
        $this->view->end             = $end;
        $this->view->design          = $this->design->getByID($designID);
        $this->view->pager           = $pager;

        $this->display();
    }

    public function revision($repoID)
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
    public function delete($designID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->design->confirmDelete, inlink('delete', "designID=$designID&confirm=yes")));
        }
        else
        {
            $this->design->delete(TABLE_DESIGN, $designID);
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
    public function assignTo($designID)
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

        $this->view->title      = $this->lang->design->assignedTo;
        $this->view->position[] = $this->lang->design->assignedTo;

        $this->view->design = $this->design->getById($designID);
        $this->view->users  = $this->loadModel('user')->getPairs();
        $this->display();
    }
}
