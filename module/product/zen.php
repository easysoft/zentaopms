<?php
declare(strict_types=1);
/**
 * The control file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     product
 * @link        http://www.zentao.net
 */

class productZen extends product
{
    /**
     * 为控制层的all函数设置共享环境数据。
     * Set shared environment data for all function of control layer.
     *
     * @access protected
     * @return void
     */
    protected function setEnvAll()
    {
        /* Set redirect URI. */
        $this->session->set('productList', $this->app->getURI(true), 'product');

        /* Set activated menu for mobile view. */
        if($this->app->viewType == 'mhtml')
        {
            $productID = $this->saveVisitState(0, $this->products);
            $this->setMenu($productID);
        }
    }

    /**
     * 设置项目页面导航菜单。
     * Set navigation menus for project page.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $preBranch
     * @access protected
     * @return void
     */
    protected function setProjectMenu(int $productID, string $branch, string $preBranch)
    {
        $branch = ($preBranch !== '' and $branch === '') ? $preBranch : $branch;
        setcookie('preBranch', $branch, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        $this->session->set('createProjectLocate', $this->app->getURI(true), 'product');

        $this->setMenu($productID, $branch);
    }

    /**
     * 为创建产品设置导航数据，主要是替换占位符。
     * Set menu for create product page.
     *
     * @param  int $programID
     * @access protected
     * @return void
     */
    protected function setCreateMenu(int $programID)
    {
        if($this->app->tab == 'program') $this->loadModel('program')->setMenu($programID);
        if($this->app->tab == 'doc')     unset($this->lang->doc->menu->product['subMenu']);

        if($this->app->getViewType() != 'mhtml') return;

        if($this->app->rawModule == 'projectstory' and $this->app->rawMethod == 'story') return $this->loadModel('project')->setMenu();
        $this->setMenu();
    }

    /**
     * 为编辑产品设置导航数据，主要是替换占位符。
     * Set menu for edit product page.
     *
     * @param  int $productID
     * @param  int $programID
     * @access protected
     * @return void
     */
    protected function setEditMenu(int $productID, int $programID)
    {
        if($programID) return $this->loadModel('program')->setMenu($programID);
        $this->setMenu($productID);
    }

    /**
     * 为showErrorNone方法，根据不同模块，设置不同的二级或三级导航配置。
     * Set menu for showErrorNone page.
     *
     * @param  string $moduleName   project|qa|execution
     * @param  string $activeMenu
     * @param  int $objectID
     * @access protected
     * @return void
     */
    protected function setShowErrorNoneMenu(string $moduleName, string $activeMenu, int $objectID)
    {
        if($this->app->getViewType() == 'mhtml') return $this->setMenu();

        if($moduleName == 'qa')        $this->setShowErrorNoneMenu4QA($activeMenu);
        if($moduleName == 'project')   $this->setShowErrorNoneMenu4Project($activeMenu, $objectID);
        if($moduleName == 'execution') $this->setShowErrorNoneMenu4Execution($activeMenu, $objectID);
    }

    /**
     * 为showErrorNone方法，设置在测试视图中的二级导航配置。
     * Set menu for showErrorNone page in qa.
     *
     * @param  string $activeMenu
     * @access private
     * @return void
     */
    private function setShowErrorNoneMenu4QA(string $activeMenu)
    {
        $this->loadModel('qa')->setMenu(array(), 0);
        $this->view->moduleName = 'qa';
        $this->app->rawModule   = $activeMenu;

        if($activeMenu == 'testcase')   unset($this->lang->qa->menu->testcase['subMenu']);
        if($activeMenu == 'testsuite')  unset($this->lang->qa->menu->testcase['subMenu']);
        if($activeMenu == 'testtask')   unset($this->lang->qa->menu->testtask['subMenu']);
        if($activeMenu == 'testreport') unset($this->lang->qa->menu->testtask['subMenu']);
    }

    /**
     * 为showErrorNone方法，设置在项目视图中的二级或三级导航配置。
     * Set menu for showErrorNone page in project.
     *
     * @param  string $activeMenu
     * @param  int    $projectID
     * @access private
     * @return void
     */
    private function setShowErrorNoneMenu4Project(string $activeMenu, int $projectID)
    {
        $this->loadModel('project')->setMenu($projectID);
        $this->app->rawModule  = $activeMenu;

        $project = $this->project->getByID($projectID);
        $model   = zget($project, 'model', 'scrum');
        $this->lang->project->menu      = $this->lang->{$model}->menu;
        $this->lang->project->menuOrder = $this->lang->{$model}->menuOrder;

        if($activeMenu == 'bug')            $this->lang->{$model}->menu->qa['subMenu']->bug['subModule']        = 'product';
        if($activeMenu == 'testcase')       $this->lang->{$model}->menu->qa['subMenu']->testcase['subModule']   = 'product';
        if($activeMenu == 'testtask')       $this->lang->{$model}->menu->qa['subMenu']->testtask['subModule']   = 'product';
        if($activeMenu == 'testreport')     $this->lang->{$model}->menu->qa['subMenu']->testreport['subModule'] = 'product';
        if($activeMenu == 'projectrelease') $this->lang->{$model}->menu->release['subModule']                   = 'projectrelease';
    }

    /**
     * 为showErrorNone方法，设置在执行视图中的三级导航配置。
     * Set menu for showErrorNone page in execution.
     *
     * @param  string $activeMenu
     * @param  int    $executionID
     * @access private
     * @return void
     */
    private function setShowErrorNoneMenu4Execution(string $activeMenu, int $executionID)
    {
        $this->loadModel('execution')->setMenu($executionID);
        $this->app->rawModule = $activeMenu;

        if($activeMenu == 'bug')        $this->lang->execution->menu->qa['subMenu']->bug['subModule']        = 'product';
        if($activeMenu == 'testcase')   $this->lang->execution->menu->qa['subMenu']->testcase['subModule']   = 'product';
        if($activeMenu == 'testtask')   $this->lang->execution->menu->qa['subMenu']->testtask['subModule']   = 'product';
        if($activeMenu == 'testreport') $this->lang->execution->menu->qa['subMenu']->testreport['subModule'] = 'product';
    }

    /**
     * 通过extra获取返回链接。
     * Get goback link for create by extra.
     *
     * @param string $extra
     * @access private
     * @return string
     */
    private function getBackLink4Create(string $extra): string
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $backLink = '';
        $from     = zget($output, 'from', '');
        if($from == 'qa')     $backLink = $this->createLink('qa', 'index');
        if($from == 'global') $backLink = $this->createLink('product', 'all');
        return $backLink;
    }

    /**
     * 获取创建产品页面的表单配置。
     * Get form fields for create.
     *
     * @param  int   $programID
     * @param  array $fields
     * @access private
     * @return array
     */
    private function getFormFields4Create(int $programID = 0, array $fields = array()): array
    {
        if(empty($fields)) $fields = $this->appendFlowFields($this->config->product->form->create);

        /* 准备数据。*/
        $this->loadModel('user');
        $poUsers = $this->user->getPairs('nodeleted|pofirst|noclosed');
        $qdUsers = $this->user->getPairs('nodeleted|qdfirst|noclosed');
        $rdUsers = $this->user->getPairs('nodeleted|devfirst|noclosed');
        $users   = $this->user->getPairs('nodeleted|noclosed');

        /* 追加字段的name、title属性，展开user数据。 */
        foreach($fields as $field => $attr)
        {
            if(isset($attr['options']) and $attr['options'] == 'users') $fields[$field]['options'] = $users;
            if(!isset($fields[$field]['name']))  $fields[$field]['name']  = $field;
            if(!isset($fields[$field]['title'])) $fields[$field]['title'] = zget($this->lang->product, $field);
        }

        /* 设置下拉菜单内容。 */
        $fields['PO']['options'] = $poUsers;
        $fields['QD']['options'] = $qdUsers;
        $fields['RD']['options'] = $rdUsers;
        if(isset($fields['program'])) $fields['program']['options'] = array('') + $this->loadModel('program')->getTopPairs('', 'noclosed');
        if($programID and isset($fields['line'])) $fields['line']['options'] = array('') + $this->product->getLinePairs($programID);

        return $fields;
    }

    /**
     * 获取编辑产品页面的表单配置。
     * Get form fields for create.
     *
     * @param  object $product
     * @access private
     * @return array
     */
    private function getFormFields4Edit(object $product): array
    {
        /* Init fields. */
        $programID = (int)$product->program;
        $fields    = $this->getFormFields4Create($programID, $this->appendFlowFields($this->config->product->form->edit));
        $fields['changeProjects'] = array('type' => 'string', 'control' => 'hidden', 'required' => false, 'default' => '');

        /* Check program priv, and append to program list that is not exist product's program. */
        $hasPrivPrograms = $this->app->user->view->programs;
        if($programID and strpos(",{$hasPrivPrograms},", ",{$programID},") === false) $fields['program']['control'] = 'hidden';
        if(isset($fields['program']) and !isset($fields['program']['options'][$programID]) and $programID)
        {
            $program = $this->program->getByID($programID);
            $fields['program']['options'] += array($programID => $program->name);
        }

        /* Set default value by product. */
        foreach($fields as $field => $attr)
        {
            if(isset($product->{$field})) $fields[$field]['default'] = $product->{$field};
        }

        return $fields;
    }

    /**
     * 获取批量编辑产品页面的表单配置。
     * Get form fields config for batch edit page.
     *
     * @access private
     * @return array
     */
    private function getFormFields4BatchEdit(): array
    {
        /* Init fields. */
        $fields = $this->getFormFields4Create(0, $this->appendFlowFields($this->config->product->form->batchEdit, 'batch'));

        /* Remove not show fields. */
        $showFields = explode(',', $this->config->product->custom->batchEditFields);
        foreach($fields as $field => $attr)
        {
            if(!in_array($field, $showFields) and !$attr['required']) unset($fields[$field]);
        }

        return $fields;
    }

    /**
     * Get product lines and product lines of program.
     *
     * @param  array  $programIdList
     * @param  string $params         hasempty
     * @access protected
     * @return array
     */
    protected function getProductLines(array $programIdList = array(), string $params = ''): array
    {
        /* Get all product lines. */
        $productLines = $this->product->getLines($programIdList);

        /* Collect product lines of program lines. */
        $initArray = strpos($params, 'hasempty') !== false ? array('') : array();
        $linePairs = $initArray;
        foreach($programIdList as $programID) $linePairs[$programID] = $initArray;
        foreach($productLines as $line) $linePairs[$programID][$line->id] = $line->name;

        return array($productLines, $linePairs);
    }

    /**
     * 根据产品和已授权项目集，获取关联产品的未授权项目集。
     * Get unauthorized programs by products and authorized programs.
     *
     * @param  array $products
     * @param  array $authPrograms
     * @access private
     * @return array
     */
    private function getUnauthProductPrograms(array $products, array $authPrograms): array
    {
        $unauthPrograms = array();
        $programIdList  = array();
        foreach($products as $product)
        {
            if($product->program and !isset($authPrograms[$product->program])) $programIdList[$product->program] = $product->program;
        }
        if($programIdList) $unauthPrograms = $this->program->getPairsByList($programIdList);

        return $unauthPrograms;
    }

    /**
     * Get project PM List
     *
     * @param  array $projectStats
     * @access private
     * @return string[]
     */
    private function getPMList(array $projectStats): array
    {
        $accounts = array();
        foreach($projectStats as $project) $accounts[] = $project->PM;
        $accounts = array_filter(array_unique($accounts));

        if(empty($accounts)) return array();
        return $this->user->getListByAccounts($accounts, 'account');
    }

    /**
     * 创建完成后，做页面跳转。
     * Locate after create product.
     *
     * @param  int   $productID
     * @param  int   $programID
     * @access private
     * @return array
     */
    private function getCreatedLocate(int $productID, int $programID): array
    {
        $tab = $this->app->tab;
        $moduleName = $tab == 'program' ? 'program' : $this->moduleName;
        $methodName = $tab == 'program' ? 'product' : 'browse';
        $param      = $tab == 'program' ? "programID=$programID" : "productID=$productID";
        $locate     = isonlybody() ? 'true' : $this->createLink($moduleName, $methodName, $param);
        if($tab == 'doc') $locate = $this->createLink('doc', 'productSpace', "objectID=$productID");

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locate);
    }

    /**
     * 编辑完成后，做页面跳转。
     * Locate after edit product.
     *
     * @param  int   $productID
     * @param  int   $programID
     * @access private
     * @return array
     */
    private function getEditedLocate(int $productID, int $programID): array
    {
        $moduleName = $programID ? 'program' : 'product';
        $methodName = $programID ? 'product' : 'view';
        $param      = $programID ? "programID=$programID" : "product=$productID";
        $locate     = $this->createLink($moduleName, $methodName, $param);

        if(!$programID) $this->session->set('productList', $this->createLink('product', 'browse', $param), 'product');
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate);
    }

    /**
     * 构建创建产品页面数据。
     * Build form fields for create.
     *
     * @param  int    $programID
     * @param  string $extra
     * @access protected
     * @return void
     */
    protected function buildCreateForm(int $programID = 0, string $extra = '')
    {
        $this->view->title      = $this->lang->product->create;
        $this->view->gobackLink = $this->getBackLink4Create($extra);
        $this->view->programID  = $programID;
        $this->view->fields     = $this->getFormFields4Create($programID);

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * 构建编辑产品页面数据。
     * Build form fields for edit.
     *
     * @param  object $product
     * @access protected
     * @return void
     */
    protected function buildEditForm(object $product)
    {
        $this->view->title   = $this->lang->product->edit . $this->lang->colon . $product->name;
        $this->view->product = $product;
        $this->view->fields  = $this->getFormFields4Edit($product);

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * 构建批量编辑产品页面数据。
     * Build form for batch edit page
     *
     * @param  int   $programID
     * @param  array $productIdList
     * @access protected
     * @return void
     */
    protected function buildBatchEditForm(int $programID, array $productIdList)
    {
        $products = $this->product->getByIdList($productIdList);

        /* 获取项目集和产品线，并将项目集分成授权和非授权。以便view的下拉菜单使用对应的数据。 */
        $authPrograms   = array();
        $unauthPrograms = array();
        $lines          = array();
        if($this->config->systemMode == 'ALM')
        {
            $authPrograms   = $this->loadModel('program')->getTopPairs();
            $unauthPrograms = $this->getUnauthProductPrograms($products, $authPrograms);

            /* Get product lines by programs.*/
            $programIdList = array_merge(array_keys($authPrograms), array_keys($unauthPrograms));
            list(, $lines) = $this->getProductLines($programIdList);
        }

        /* 给view层赋值。 */
        $this->view->title          = $this->lang->product->batchEdit;
        $this->view->lines          = $lines;
        $this->view->products       = $products;
        $this->view->programID      = $programID;
        $this->view->authPrograms   = array('' => '') + $authPrograms;
        $this->view->unauthPrograms = $unauthPrograms;

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * 为view层设置要用数据。
     * Set view variables and display for project page.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $status
     * @param  bool   $involved   $this->cookie->involved or $involved
     * @param  string $orderBy
     * @param  object $pager
     * @access protected
     * @return void
     */
    protected function displayProjectPage(int $productID, string $branch, string $status, bool $involved, string $orderBy, object $pager)
    {
        $this->app->loadLang('execution');
        $projectStats = $this->product->getProjectStatsByProduct($productID, $status, $branch, $involved, $orderBy, $pager);

        $product  = $this->product->getByID($productID);
        $projects = $this->loadModel('project')->getPairsByProgram($product->program, 'all', false, 'order_asc', '', '', 'product');
        foreach($projectStats as $project) unset($projects[$project->id]);

        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->product->project;
        $this->view->projectStats = $projectStats;
        $this->view->PMList       = $this->getPMList($projectStats);
        $this->view->product      = $product;
        $this->view->projects     = $projects;
        $this->view->status       = $status;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->branchID     = $branch;
        $this->view->branchStatus = $this->loadModel('branch')->getByID($branch, 0, 'status');
        $this->view->pager        = $pager;
        $this->display();
    }

    /**
     * 追加创建信息，处理白名单、项目集字段，还有富文本内容处理。
     * Prepare data for create.
     *
     * @param  form   $data
     * @param  string $acl
     * @param  string $uid
     * @access protected
     * @return object
     */
    protected function prepareCreateExtras(form $data, string $acl, string $uid = ''): object
    {
        $product = $data->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('createdVersion', $this->config->version)
            ->setIF($this->config->systemMode == 'light', 'program', (int)zget($this->config->global, 'defaultProgram', 0))
            ->setIF($acl == 'open', 'whitelist', '')
            ->stripTags($this->config->product->editor->create['id'], $this->config->allowedTags)
            ->get();

        return $this->loadModel('file')->processImgURL($product, $this->config->product->editor->create['id'], $uid);
    }

    /**
     * 处理白名单和富文本内容。
     * Prepare data for edit.
     *
     * @param  form   $data
     * @param  string $acl
     * @param  string $uid
     * @access protected
     * @return object
     */
    protected function prepareEditExtras(form $data, string $acl, string $uid = ''): object
    {
        $product = $data->setIF($acl == 'open', 'whitelist', '')
            ->stripTags($this->config->product->editor->edit['id'], $this->config->allowedTags)
            ->get();

        return $this->loadModel('file')->processImgURL($product, $this->config->product->editor->edit['id'], $uid);
    }

    /**
     * 预处理批量编辑产品数据，将按字段为分组数据重组为产品分组的数据。
     * Prepare batch edit extras.
     *
     * @param  form $data
     * @access protected
     * @return array
     */
    protected function prepareBatchEditExtras(form $data): array
    {
        $formConfig = $data->rawconfig;
        $editForm   = $this->config->product->form->edit;
        $data       = $data->get();
        $products   = array();

        /* 将按字段为分组数据重组为产品分组的数据。*/
        foreach($data->name as $productID => $productName)
        {
            $productID = (int)$productID;

            /* 根据表单配置构造产品数据。*/
            $product     = new stdClass();
            $product->id = $productID;
            foreach($formConfig as $field => $attr)
            {
                /* 获取对应的字段数据。*/
                $product->{$field} = zget($data->{$field}, $productID, $attr['default']);

                /* 根据配置规则，格式化字段数据。 */
                if(isset($editForm[$field]) and $editForm[$field]['type'] == 'int') $product->{$field} = (int)$product->{$field};
                if(!empty($attr['filter']) and $attr['filter'] == 'trim') $product->{$field} = trim($product->{$field});
                if(is_array($product->$field)) $product->{$field} = implode(',', $product->{$field});

                /* 检查必填项。 */
                if(!empty($attr['required']) and validater::checkEmpty($product->{$field})) dao::$errors[] = 'product #' . $productID . sprintf($this->lang->error->notempty, zget($attr, 'title', zget($this->lang->product, $field)));
            }

            $products[$productID] = $product;
        }

        return $products;
    }

    /**
     * 成功插入产品数据后，其他的额外操作。
     * Process after create product.
     *
     * @param  int    $productID
     * @param  int    $programID
     * @access protected
     * @return array
     */
    protected function responseAfterCreate(int $productID, int $programID): array
    {
        $this->loadModel('action')->create('product', $productID, 'opened');

        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;

        /* 移动到control。*/
        if($this->viewType == 'json') return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $productID);
        return $this->getCreatedLocate($productID, $programID);
    }

    /**
     * 成功更新产品数据后，其他的额外操作。
     * Process after edit product.
     *
     * @param  int    $productID
     * @param  int    $programID
     * @param  array  $changes
     * @access protected
     * @return array
     */
    protected function responseAfterEdit(int $productID, int $programID, array $changes): array
    {
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('product', $productID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;

        return $this->getEditedLocate($productID, $programID);
    }

    /**
     * 成功批量更新产品数据后，其他的额外操作。
     * Response after batch edit products.
     *
     * @param  array $allChanges
     * @param  int   $programID
     * @access protected
     * @return array
     */
    protected function responseAfterBatchEdit(array $allChanges, int $programID): array
    {
        /* Get locate. */
        $locate = $this->createLink('program', 'product', "programID=$programID");
        if($this->app->tab == 'product') $locate = $this->createLink('product', 'all');
        $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locate);

        if(empty($allChanges)) return $response;

        /* Save actions. */
        $this->loadModel('action');
        foreach($allChanges as $productID => $changes)
        {
            if(empty($changes)) continue;

            $actionID = $this->action->create('product', $productID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        return $response;
    }

    /**
     * 从产品统计数据中统计项目集。
     * Statistics program data from statistics data of product.
     *
     * @param  array     $productStats
     * @access protected
     * @return array
     */
    protected function statisticProgram(array $productStats): array
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProductStats();

        $programStructure = array();

        foreach($productStats as $product)
        {
            $programStructure[$product->program][$product->line]['products'][$product->id] = $product;

            /* Generate line data. */
            if($product->line)
            {
                $programStructure[$product->program][$product->line]['lineName'] = $product->lineName;
                $programStructure[$product->program][$product->line] = $this->statisticProductData('line', $programStructure, $product);
            }

            /* Generate program data. */
            if($product->program)
            {
                $programStructure[$product->program]['programName'] = $product->programName;
                $programStructure[$product->program]['programPM']   = $product->programPM;
                $programStructure[$product->program]['id']          = $product->program;
                $programStructure[$product->program]                = $this->statisticProductData('program', $programStructure, $product);
            }
        }

        return $programStructure;
    }

    /**
     * 统计项目集内的产品数据
     * Statistic product data.
     *
     * @param  string    $type line|program
     * @param  array     $programStructure
     * @param  object    $product
     * @access protected
     * @return array
     */
    protected function statisticProductData(string $type, array $programStructure, object|null $product): array
    {
        if(empty($programStructure)) return $programStructure;

        /* Init vars. */
        $data = $type == 'program' ? $programStructure[$product->program] : $programStructure[$product->program][$product->line];
        foreach($this->config->product->statisticFields as $key => $fields)
        {
            /* Get the total number of requirements and stories. */
            if(strpos('stories|requirements', $key) !== false)
            {
                $totalObjects = 0;
                foreach($product->$key as $status => $number) if(isset($this->lang->story->statusList[$status])) $totalObjects += $number;

                $fieldType = $key == 'stories' ? 'Stories' : 'Requirements';
                if(!isset($data['total' . $fieldType])) $data['total' . $fieldType] = 0;
                $data['total' . $fieldType] += $totalObjects;
            }
            elseif($key == 'bugs')
            {
                $fieldType = 'Bugs';
            }

            foreach($fields as $field)
            {
                if(!isset($data[$field])) $data[$field] = 0;

                $status = $field;
                if(strpos($field, 'Requirements') !== false or strpos($field, 'Stories') !== false or $field == 'unResolvedBugs')
                {
                    $length = strpos($field, $fieldType);
                    $status = substr($field, 0, $length);
                }

                if(strpos('requirements|stories', $key) !== false)
                {
                    $objects = $product->$key;
                    $data[$field] += $objects[$status];
                }
                else
                {
                    $data[$field] += $product->$status;
                }
            }
        }

        return $data;
    }

    /**
     * 追加工作流配置字段。
     * Append flow fields.
     *
     * @param  array  $fields
     * @param  string $type     single|batch
     * @access protected
     * @return array
     */
    protected function appendFlowFields(array $fields, string $type = 'single'): array
    {
        $extendFields = $this->product->getFlowExtendFields();
        if(empty($extendFields)) return $fields;

        /* 构造表单属性，并追加到表单配置中。 */
        foreach($extendFields as $extendField)
        {
            $control = $extendField->control;
            if($control == 'richtext') $control = 'editor';
            if($control == 'input')    $control = 'text';

            $field = $extendField;
            $fields[$field] = array();
            $fields[$field]['type']    = $type == 'single' ? 'string' : 'array';
            $fields[$field]['control'] = $control;
            $fields[$field]['title']   = $extendField->name;
            $fields[$field]['default'] = $extendField->default;
            $fields[$field]['options'] = $extendField->options;
        }

        return $fields;
    }
}
