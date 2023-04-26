<?php declare(strict_types=1);
/**
 * The zen file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming <sunguangming@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class projectZen extends project
{
    /**
     * Send variables to create page.
     * @param  string $model
     * @param  int    $programID
     * @param  int    $copyProjectID
     * @param  string $extra
     * @access protected 
     * @return void 
     */
    protected function buildCreateForm(string $model, int $programID, int $copyProjectID, string $extra):void
    {
        $this->loadModel('product');

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if($this->app->tab == 'program' and $programID)                   $this->loadModel('program')->setMenu($programID);
        if($this->app->tab == 'product' and !empty($output['productID'])) $this->loadModel('product')->setMenu($output['productID']);
        if($this->app->tab == 'doc') unset($this->lang->doc->menu->project['subMenu']);
        $this->session->set('projectModel', $model);

        $name          = '';
        $code          = '';
        $team          = '';
        $whitelist     = '';
        $acl           = 'private';
        $auth          = 'extend';
        $multiple      = 1;
        $hasProduct    = 1;
        $shadow        = 0;
        $products      = array();
        $productPlans  = array();
        $parentProgram = $this->loadModel('program')->getByID($programID);

        if($copyProjectID)
        {
            $copyProject = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($copyProjectID)->fetch();
            $name        = $copyProject->name;
            $code        = $copyProject->code;
            $team        = $copyProject->team;
            $whitelist   = $copyProject->whitelist;
            $acl         = $copyProject->acl;
            $auth        = $copyProject->auth;
            $multiple    = $copyProject->multiple;
            $hasProduct  = $copyProject->hasProduct;
            $programID   = $copyProject->parent;
            $products    = $this->product->getProducts($copyProjectID);

            if(!$copyProject->hasProduct) $shadow = 1;
            foreach($products as $product)
            {
                $branches = implode(',', $product->branches);
                $productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id, $branches, 'noclosed', true);
            }
        }

        if($this->view->globalDisableProgram) $programID = $this->config->global->defaultProgram;
        $topProgramID = $this->program->getTopByID($programID);

        if($model == 'kanban')
        {
            $this->lang->project->aclList    = $this->lang->project->kanbanAclList;
            $this->lang->project->subAclList = $this->lang->project->kanbanSubAclList;
        }

        $sprintConcept = empty($this->config->custom->sprintConcept) ?
        $this->config->executionCommonList[$this->app->getClientLang()][0] :
        $this->config->executionCommonList[$this->app->getClientLang()][1];

        $withProgram = $this->config->systemMode == 'ALM' ? true : false;
        $allProducts = array('0' => '') + $this->program->getProductPairs($programID, 'all', 'noclosed', '', $shadow, $withProgram);

        $this->view->title               = $this->lang->project->create;
        $this->view->gobackLink          = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('project', 'browse') : '';
        $this->view->pmUsers             = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->users               = $this->user->getPairs('noclosed|nodeleted');
        $this->view->copyProjects        = $this->project->getPairsByModel($model);
        $this->view->products            = $products;
        $this->view->allProducts         = $allProducts;
        $this->view->productPlans        = array('0' => '') + $productPlans;
        $this->view->branchGroups        = $this->loadModel('branch')->getByProducts(array_keys($products), 'noclosed');
        $this->view->programID           = $programID;
        $this->view->productID           = isset($output['productID']) ? $output['productID'] : 0;
        $this->view->branchID            = isset($output['branchID']) ? $output['branchID'] : 0;
        $this->view->multiBranchProducts = $this->product->getMultiBranchPairs($topProgramID);
        $this->view->model               = $model;
        $this->view->name                = $name;
        $this->view->code                = $code;
        $this->view->team                = $team;
        $this->view->acl                 = $acl;
        $this->view->auth                = $auth;
        $this->view->whitelist           = $whitelist;
        $this->view->multiple            = $multiple;
        $this->view->hasProduct          = $hasProduct;
        $this->view->copyProjectID       = $copyProjectID;
        $this->view->programList         = $this->program->getParentPairs();
        $this->view->parentProgram       = $parentProgram;
        $this->view->URSRPairs           = $this->loadModel('custom')->getURSRPairs();
        $this->view->availableBudget     = $this->program->getBudgetLeft($parentProgram);
        $this->view->budgetUnitList      = $this->project->getBudgetUnitList();

        $this->display();
    }

    /**
     * Append extras data to post data.
     * @param  object $postData
     * @access protected 
     * @return int|object 
     */
    protected function prepareStartExtras(object $postData):object
    {
        $postData->status         = 'doing';
        $postData->lastEditedBy   = $this->app->user->account;
        $postData->lastEditedDate = helper::now();

        return $postData;
    }

    /**
     * Send variables to view page.
     * @param  object $project
     * @access protected 
     * @return int|object 
     */
    protected function buildStartForm(object $project)
    {
        $this->view->title      = $this->lang->project->start;
        $this->view->position[] = $this->lang->project->start;
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $project->id);
        $this->display();
    }

    /**
     * After starting the project, do other operations.
     * @param  object $project
     * @param  array  $changes
     * @param  string $comment
     * @access protected 
     * @return int|object 
     */
    protected function responseAfterStart(object $project, array $changes, string $comment) :int|object
    {
        if($comment != '' or !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('project', $project->id, 'Started', $comment);
            $this->action->logHistory($actionID, $changes);
        }

        $this->loadModel('common')->syncPPEStatus($project->id);

        $this->executeHooks($project->id);
        return print(js::reload('parent.parent'));
    }
}
