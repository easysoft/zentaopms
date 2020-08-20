<?php
class design extends control
{
    public function browse($productID = 0,$type = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $productID = $this->loadModel('product')->saveState($productID, $this->product->getPairs('nocode'));

        $this->design->setProductMenu($productID);
        $product = $this->loadModel('product')->getById($productID);
        $project = $this->loadModel('project')->getById($product->program);

        $this->app->session->set('designList', $this->app->getURI(true));

        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPager = 10;
        $pager    = pager::init($recTotal, $recPerPage, $pageID);

        $designs = $this->design->getList($productID, $type, $orderBy, $pager);

        $this->view->title        = $this->lang->design->browse;
        $this->view->position[]   = $this->lang->design->browse;

        $this->view->designs      = $designs;
        $this->view->type         = $type;
        $this->view->recTotal     = $recTotal;
        $this->view->recPerPage   = $recPerPage;
        $this->view->pageID       = $pageID;
        $this->view->orderBy      = $orderBy;
        $this->view->productID    = $productID;
        $this->view->program      = $project;
        $this->view->pager        = $pager;

        $this->display();
    }

    public function create($productID = 0, $prevModule = '', $prevID = 0)
    {
        $this->design->setProductMenu($productID);

        if($_POST)
        {
            $productID = $_POST['product'];

            $designID = $this->design->create();
            if($designID)
            {
                $this->loadModel('action')->create('design', $designID, 'created');

                $response['result']  = 'success';
                $response['message'] = $this->lang->saveSuccess;
                $response['locate']  = $this->createLink('design', 'browse', "productID=$productID");
                $this->send($response);
            }

            $response['result']  = 'fail';
            $response['message'] = dao::getError();
            $this->send($response);
        }

        $this->view->title      = $this->lang->design->create;
        $this->view->position[] = $this->lang->design->create;

        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->stories    = $this->loadModel('story')->getProductStoryPairs($productID);
        $this->view->products   = $this->loadModel('product')->getPairs($this->session->program);
        $this->view->productID  = $productID;
        $this->view->program    = $this->loadModel('project')->getByID($this->session->program);

        $this->display();
    }

    public function view($designID = 0)
    {
        $data              = $this->design->getById($designID);
        $data->productName = $this->dao->findByID($data->product)->from(TABLE_PRODUCT)->fetch('name');
        $data->files       = $this->loadModel('file')->getByObject('design', $designID);

        $relations    = $this->loadModel('common')->getRelations('design', $data->id, 'commit');
        $data->commit = '';
        foreach($relations as $relation) $data->commit .= html::a(helper::createLink('design', 'revision', "repoID=$relation->BID", '', true), "#$relation->BID", '', "class='iframe' data-width='80%' data-height='550'");

        $storyTitle  = $this->dao->findByID($data->story)->from(TABLE_STORY)->fetch('title');
        $data->story = $storyTitle ? html::a($this->createLink('story', 'view', "id=$data->story"), $storyTitle) : '';

        $actions     = $this->loadModel('action')->getList('design', $data->id);

        $this->view->title      = $this->lang->design->designView;
        $this->view->position[] = $this->lang->design->designView;

        $this->view->productID = $data->product;
        $this->view->data      = $data;
        $this->view->relations = $relations;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions   = $actions;

        $this->display();
    }

    public function edit($designID = 0)
    {
        $design = $this->design->getByID($designID);
        $design = $this->design->getAffectedScope($design);
        $this->design->setProductMenu($design->product);

        if($_POST)
        {
            $changes = $this->design->update($designID);
            if($changes)
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

    public function commit($designID, $begin = '', $end = '', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID); 

        $program = $this->loadModel('project')->getByID($this->session->program);
        $begin   = $begin ? date('Y-m-d', strtotime($begin)) : $program->begin;
        $end     = $end ? date('Y-m-d', strtotime($end)) : helper::today();

        $repoID     = $this->session->repoID;
        $repo       = $this->loadModel('repo')->getRepoByID($repoID);
        $revisions  = $this->repo->getCommits($repo, '', 'HEAD', '', $pager, $begin, $end);

        if($_POST)
        {
            $this->design->linkCommit($designID);

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
}
