<?php
declare(strict_types=1);
/**
 * The zen file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
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
    protected function setMenu4All()
    {
        /* Set redirect URI. */
        $this->session->set('productList', $this->app->getURI(true), 'product');

        /* Set activated menu for mobile view. */
        if($this->app->viewType == 'mhtml')
        {
            $productID = $this->product->checkAccess(0, $this->products);
            $this->product->setMenu($productID);
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
        helper::setcookie('preBranch', $branch);
        $this->session->set('createProjectLocate', $this->app->getURI(true), 'product');

        $this->product->setMenu($productID, $branch);
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
        if($this->app->tab == 'program') common::setMenuVars('program', $programID);
        if($this->app->tab == 'doc')     unset($this->lang->doc->menu->product['subMenu']);

        if($this->app->getViewType() != 'mhtml') return;

        if($this->app->rawModule == 'projectstory' and $this->app->rawMethod == 'story') return $this->loadModel('project')->setMenu();
        $this->product->setMenu();
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
        if($programID) return common::setMenuVars('program', $programID);
        $this->product->setMenu($productID);
    }

    /**
     * 设置跟踪矩阵导航
     * Set menu for track
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $projectID
     * @access protected
     * @return void
     */
    protected function setTrackMenu(int $productID, string $branch, int $projectID)
    {
        helper::setcookie('preBranch', $branch);

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('storyList',    $uri, 'product');
        $this->session->set('taskList',     $uri, 'execution');
        $this->session->set('designList',   $uri, 'project');
        $this->session->set('bugList',      $uri, 'qa');
        $this->session->set('caseList',     $uri, 'qa');
        $this->session->set('revisionList', $uri, 'repo');

        if($projectID) return $this->loadModel('project')->setMenu($projectID);

        $productID = $this->product->checkAccess($productID, $this->products);
        $this->product->setMenu($productID, $branch);
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
        if($this->app->getViewType() == 'mhtml') return $this->product->setMenu();

        if($moduleName == 'qa')        $this->setShowErrorNoneMenu4QA($activeMenu);
        if($moduleName == 'project')   $this->setShowErrorNoneMenu4Project($activeMenu, $objectID);
        if($moduleName == 'execution') $this->setShowErrorNoneMenu4Execution($activeMenu, $objectID);
    }

    /**
     * 为showErrorNone方法，设置在测试视图中的二级导航配置。
     * Set menu for showErrorNone page in qa.
     *
     * @param  string  $activeMenu
     * @access private
     * @return void
     */
    private function setShowErrorNoneMenu4QA(string $activeMenu)
    {
        $this->loadModel('qa')->setMenu();
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
     * @param  string  $activeMenu
     * @param  int     $projectID
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
    protected function getBackLink4Create(string $extra): string
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
     * 设置表单字段下拉选项。
     * Set form select options.
     *
     * @param  int     $programID
     * @param  array   $fields
     * @access private
     * @return array
     */
    private function setSelectFormOptions(int $programID, array $fields): array
    {
        $users = $this->loadModel('user')->getPairs('nodeleted|noclosed');

        /* 追加字段的name、title属性，展开user数据。 */
        foreach($fields as $field => $attr)
        {
            if(isset($attr['options']) and $attr['options'] == 'users') $fields[$field]['options'] = $users;
            if(!isset($fields[$field]['name']))  $fields[$field]['name']  = $field;
            if(!isset($fields[$field]['title'])) $fields[$field]['title'] = zget($this->lang->product, $field);
        }

        /* 设置下拉菜单内容。 */
        if(isset($fields['groups']))  $fields['groups']['options']  = $this->loadModel('group')->getPairs();
        if(isset($fields['program'])) $fields['program']['options'] = $this->loadModel('program')->getTopPairs('noclosed');
        if(isset($fields['line']))    $fields['line']['options']    = $this->product->getLinePairs($programID, true);

        if($this->config->edition != 'open' && isset($fields['workflowGroup']))
        {
            $groupPairs = $this->loadModel('workflowGroup')->getPairs('product', 'scrum', 1, 'normal', '0');
            $fields['workflowGroup']['options'] = $this->workflowGroup->appendBuildinLabel($groupPairs);
        }

        return $fields;
    }

    /**
     * 获取创建产品页面的表单配置。
     * Get form fields for create.
     *
     * @param  int       $programID
     * @param  string    $extra
     * @access protected
     * @return array
     */
    protected function getFormFields4Create(int $programID = 0, string $extra = ''): array
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $fields = $this->setSelectFormOptions($programID, $this->config->product->form->create);
        $fields['program']['default'] = $programID ? $programID : '';
        $fields['PO']['default']      = $this->app->user->account;

        /* Set required. */
        foreach($fields as $field => $attr)
        {
            if(!empty($output[$field])) $fields[$field]['default'] = $output[$field];
            if(strpos(",{$this->config->product->create->requiredFields},", ",$field,") !== false) $fields[$field]['required'] = true;
        }

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
    protected function getFormFields4Edit(object $product): array
    {
        /* Init fields. */
        $programID = (int)$product->program;
        $fields    = $this->setSelectFormOptions($programID, $this->config->product->form->edit);
        $fields['changeProjects'] = array('type' => 'string', 'control' => 'hidden', 'required' => false, 'default' => '');

        /* Check program priv, and append to program list that is not exist product's program. */
        $hasPrivPrograms = $this->app->user->view->programs;
        if($programID and strpos(",{$hasPrivPrograms},", ",{$programID},") === false) $fields['program']['control'] = 'hidden';
        if(isset($fields['program']) and !isset($fields['program']['options'][$programID]) and $programID)
        {
            $program = $this->loadModel('program')->getByID($programID);
            $fields['program']['options'] += array($programID => $program->name);
        }

        /* Set default value by product. */
        foreach($fields as $field => $attr)
        {
            if(isset($product->{$field})) $fields[$field]['default'] = $product->{$field};
            if(strpos(",{$this->config->product->edit->requiredFields},", ",$field,") !== false) $fields[$field]['required'] = true;
        }

        return $fields;
    }

    /**
     * 获取关闭产品页面的表单配置。
     * Get form fields for close product page.
     *
     * @access private
     * @return array
     */
    protected function getFormFields4Close(): array
    {
        /* Init fields. */
        $fields = $this->config->product->form->close;
        $fields['comment'] = array('type' => 'string',  'control' => 'editor', 'required' => false, 'default' => '', 'width' => 'full');

        return $fields;
    }

    /**
     * 获取激活产品页面的表单配置。
     * Get form fields for activate product page.
     *
     * @access private
     * @return array
     */
    protected function getFormFields4Activate(): array
    {
        /* Init fields. */
        $fields = $this->config->product->form->activate;
        $fields['comment'] = array('type' => 'string',  'control' => 'editor', 'required' => false, 'default' => '', 'width' => 'full');

        return $fields;
    }

    /**
     * Get product lines and product lines of program.
     *
     * @param  array     $programIdList
     * @access protected
     * @return array
     */
    protected function getProductLines(array $programIdList = array()): array
    {
        /* Get all product lines. */
        $productLines = $this->product->getLines($programIdList);

        /* Collect product lines of program lines. */
        $linePairs = array();
        foreach($programIdList as $programID) $linePairs[$programID] = array();
        foreach($productLines as $line) $linePairs[$line->root][$line->id] = $line->name;

        return array($productLines, $linePairs);
    }

    /**
     * 获取产品导出字段。
     * Get export fields.
     *
     * @access protected
     * @return array
     */
    protected function getExportFields(): array
    {
        $fieldList       = $this->loadModel('datatable') ->getSetting('product', 'all');
        $extendFieldList = $this->product->getFlowExtendFields();
        foreach($extendFieldList as $field => $name)
        {
            if(str_contains(strtolower($fieldName), 'epic') && !$this->config->enableER) continue;
            if(str_contains(strtolower($fieldName), 'requirement') && !$this->config->URAndSR) continue;

            $fieldName = trim($fieldName);
            $fieldPairs[$fieldName] = zget($productLang, $fieldName);

            $extCol = $config->product->dtable->extendField;
            $extCol['name']  = $field;
            $extCol['title'] = $name;

            $fieldList[$field] = $extCol;
        }

        $fieldPairs = array();
        foreach($fieldList as $fieldKey => $field)
        {
            if(isset($field['headerGroup'])) $field['title'] = $field['headerGroup'] . ' - ' . $field['title'];
            $fieldPairs[$fieldKey] = $field['title'];
        }
        if($this->config->systemMode == 'light') unset($fieldPairs['productLine'], $fieldPairs['program']);

        return $fieldPairs;
    }

    /**
     * 获取导出产品数据。
     * Get export product data.
     *
     * @param  int       $programID
     * @param  string    $browseType
     * @param  string    $orderBy
     * @param  int       $param
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getExportData(int $programID, string $browseType, string $orderBy, int $param = 0, ?object $pager = null): array
    {
        $users        = $this->user->getPairs('noletter');
        $products     = strtolower($browseType) == 'bysearch' ? $this->product->getListBySearch((int)$param) : $this->product->getList($programID, $browseType);
        $productStats = $this->product->getStats(array_keys($products), $orderBy, $pager, 'story', $programID);

        $data = array();
        foreach($productStats as $product) $data[] = $this->product->formatDataForList($product, $users);

        return $data;
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
    private function getUnauthProgramsOfProducts(array $products, array $authPrograms): array
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
     * 获取在ajaxGetDropMenu方法中使用的产品。
     * Get products for ajaxGetDropMenu method.
     *
     * @param  string    $shadow  0|all
     * @param  string    $module
     * @access protected
     * @return array
     */
    protected function getProducts4DropMenu(string $shadow = '0', string $module = ''): array
    {
        if($this->config->edition != 'open')
        {
            $flow = $this->loadModel('workflow')->getByModule($module);
            if($flow && $flow->belong == 'product') return $this->product->getList(0, 'all', 0, 0, $shadow);
        }

        if($this->app->tab == 'project')  return $this->product->getProducts($this->session->project);
        if($this->app->tab == 'feedback') return $this->loadModel('feedback')->getGrantProducts(false, false, 'all');
        return $this->product->getList(0, 'all', 0, 0, $shadow);
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
        $location   = isInModal() ? true : $this->createLink($moduleName, $methodName, $param);
        if($tab == 'doc') $location = $this->createLink('doc', 'productSpace', "objectID=$productID");

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $location, 'closeModal' => true);
    }

    /**
     * 编辑完成后，做页面跳转。
     * Locate after edit product.
     *
     * @param  int     $productID
     * @param  int     $programID
     * @access private
     * @return array
     */
    private function getEditedLocate(int $productID, int $programID): array
    {
        $moduleName = $programID ? 'program' : 'product';
        $methodName = $programID ? 'product' : 'view';
        $param      = $programID ? "programID=$programID" : "product=$productID";
        $location   = $this->createLink($moduleName, $methodName, $param);

        if(!$programID) $this->session->set('productList', $this->createLink('product', 'browse', $param), 'product');
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $location);
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
        if(in_array($this->config->systemMode, array('ALM', 'PLM')))
        {
            $authPrograms   = $this->loadModel('program')->getTopPairs();
            $unauthPrograms = $this->getUnauthProgramsOfProducts($products, $authPrograms);

            /* Get product lines by programs.*/
            $programIdList = array_merge(array(0 => ''), array_keys($authPrograms), array_keys($unauthPrograms));
            list(, $lines) = $this->getProductLines($programIdList);
        }

        /* 给view层赋值。 */
        $this->view->title          = $this->lang->product->batchEdit;
        $this->view->lines          = $lines;
        $this->view->products       = $products;
        $this->view->fields         = $this->setSelectFormOptions(0, $this->config->product->form->batchEdit);
        $this->view->programID      = $programID;
        $this->view->authPrograms   = $authPrograms;
        $this->view->unauthPrograms = $unauthPrograms;

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * 构建创建产品的数据。
     * Build product data for create.
     *
     * @param  int       $workflowGroup
     * @access protected
     * @return object
     */
    protected function buildProductForCreate(int $workflowGroup = 0): object
    {
        $productData = form::data($this->config->product->form->create, 0, $workflowGroup)
            ->setIF($this->config->systemMode == 'light', 'program', (int)zget($this->config->global, 'defaultProgram', 0))
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->setDefault('vision', $this->config->vision)
            ->get();

        return $this->loadModel('file')->processImgURL($productData, $this->config->product->editor->create['id'], $this->post->uid);
    }

    /**
     * 构建编辑产品的数据。
     * Build product data for edit.
     *
     * @param  int       $productID
     * @param  int       $workflowGroup
     * @access protected
     * @return object
     */
    protected function buildProductForEdit(int $productID, int $workflowGroup = 0): object
    {
        $productData = form::data($this->config->product->form->edit, $productID, $workflowGroup)
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->get();

        return $this->loadModel('file')->processImgURL($productData, $this->config->product->editor->edit['id'], $this->post->uid);
    }

    /**
     * 构建激活产品数据。
     * Build product data for activate.
     *
     * @param  int       $productID
     * @access protected
     * @return object
     */
    protected function buildProductForActivate(int $productID): object
    {
        $productData = form::data($this->config->product->form->activate, $productID)
            ->setIF($this->config->vision == 'or', 'status', 'normal')
            ->get();

        return $this->loadModel('file')->processImgURL($productData, $this->config->product->editor->activate['id'], $this->post->uid);
    }

    /**
     * 构建关闭产品数据。
     * Build product data for close.
     *
     * @param  int       $productID
     * @access protected
     * @return object
     */
    protected function buildProductForClose(int $productID): object
    {
        $productData = form::data($this->config->product->form->close, $productID)->get();
        return $this->loadModel('file')->processImgURL($productData, $this->config->product->editor->close['id'], $this->post->uid);
    }

    /**
     * 预处理产品线数据。
     * Prepare manage line extras.
     *
     * @param  form      $form
     * @access protected
     * @return array|false
     */
    protected function prepareManageLineExtras(form $form): array|false
    {
        $data = $form->get();

        /* 拼装产品线列表。列表项目集编号为键，该项目集下的产品线名称列表为值。 */
        /* Build product line list. The program id as key, line name list as value. */
        $lines = array();
        foreach($data->modules as $id => $name)
        {
            if(empty($name)) continue;

            $programID = $data->programs[$id];
            if(!isset($lines[$programID])) $lines[$programID] = array();
            if(in_array($name, $lines[$programID]))
            {
                dao::$errors["modules[$id]"] = sprintf($this->lang->product->nameIsDuplicate, $name);
                return false;
            }
            $lines[$programID][$id] = $name;
        }
        return $lines;
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
     * @access protected
     * @return array
     */
    protected function responseAfterEdit(int $productID, int $programID): array
    {
        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;

        return $this->getEditedLocate($productID, $programID);
    }

    /**
     * 成功批量更新产品数据后，其他的额外操作。
     * Response after batch edit products.
     *
     * @param  int   $programID
     * @access protected
     * @return array
     */
    protected function responseAfterBatchEdit(int $programID): array
    {
        /* Get location. */
        $location = $this->createLink('program', 'product', "programID=$programID");
        if(empty($programID)) $location = $this->createLink('program', 'productView');
        if($this->app->tab == 'product') $location = $this->createLink('product', 'all');
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $location);
    }

    /**
     * 获取研发需求列表页面关联的产品信息。
     * Get the product of the browse page.
     *
     * @param  int         $productID
     * @access protected
     * @return object|null
     */
    protected function getBrowseProduct(int $productID): object|null
    {
        $product = $this->product->getById($productID);
        if(empty($product)) return null;

        /* If product does not exist in $this->products list, then attach it to the list. */
        if(!isset($this->products[$product->id])) $this->products[$product->id] = $product->name;

        return $product;
    }

    /**
     * 获取产品分支ID。
     * Get branchID.
     *
     * @param  object|null $product
     * @param  string      $branch
     * @access protected
     * @return string
     */
    protected function getBranchID(object|null $product, string $branch): string
    {
        if(empty($product) || $product->type == 'normal') return 'all';

        /* 获取分支和分支ID。*/
        $branchPairs = $this->loadModel('branch')->getPairs($product->id, 'all');
        return ($this->cookie->preBranch !== '' and $branch === '' and isset($branchPairs[$this->cookie->preBranch])) ? (string)$this->cookie->preBranch : $branch;
    }

    /**
     * 设置导航菜单。
     * Set navigation menu.
     *
     * @param  int       $projectID
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $storyType
     * @access protected
     * @return void
     */
    protected function setMenu4Browse(int $projectID, int $productID, string $branch, string $storyType): void
    {
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($projectID);
            return;
        }

        $this->product->setMenu($productID, $branch, $storyType);
    }

    /**
     * browse方法保存并修改cookie数据。
     * Save and modify cookies for browse function.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $param
     * @param  string    $storyType
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function saveAndModifyCookie4Browse(int $productID, string $branch, int $param, string $browseType, string $orderBy): void
    {
        /* Set module ID of story. */
        if($this->cookie->preProductID != $productID or $this->cookie->preBranch != $branch or $browseType == 'bybranch')
        {
            $_COOKIE['storyModule'] = 0;
            helper::setcookie('storyModule', '0', 0);
        }

        if($browseType == 'bymodule' or $browseType == '')
        {
            helper::setcookie('storyModule', (string)$param, 0);

            /* The module ID from project app. */
            if($this->app->tab == 'project') helper::setcookie('storyModuleParam', (string)$param, 0);

            /* Re-init the story branch. */
            $_COOKIE['storyBranch'] = 'all';
            helper::setcookie('storyBranch', 'all', 0);

            /* For rendering module tree. */
            if($browseType == '') helper::setcookie('treeBranch', $branch, 0);
        }

        if($browseType == 'bybranch') helper::setcookie('storyBranch', $branch, 0);

        /* Save sort order of product stories list. */
        helper::setcookie('productStoryOrder', $orderBy, 0);

        /* Set product ID and branch of the pre visited product. */
        helper::setcookie('preProductID', (string)$productID);
        helper::setcookie('preBranch', $branch, 0);
    }

    /**
     * 获取模块ID。
     * Get module ID.
     *
     * @param  int       $param
     * @param  string    $browseType
     * @access protected
     * @return int
     */
    protected function getModuleId(int $param, string $browseType): int
    {
        if($browseType == 'bymodule') return $param;

        $cookieModule = $this->app->tab == 'project' ? $this->cookie->storyModuleParam : $this->cookie->storyModule;
        if($browseType != 'bysearch' && $browseType != 'bybranch' && $cookieModule) return (int)$cookieModule;

        return 0;
    }

    /**
     * 获取模块的树形数据结构。
     * Get the tree structure of modules.
     *
     * @param  int       $projectID
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $param
     * @param  string    $storyType
     * @param  string    $browseType
     * @access protected
     * @return array|string
     */
    protected function getModuleTree(int $projectID, int $productID, string &$branch, int $param, string $storyType, string $browseType): array|string
    {
        if(common::isTutorialMode()) return array();

        /* Set moduleTree. */
        $createModuleLink = 'createStoryLink';
        if($storyType == 'requirement') $createModuleLink = 'createRequirementLink';
        if($storyType == 'epic')        $createModuleLink = 'createEpicLink';

        if($browseType == '')
        {
            $browseType = 'unclosed';
            $branch     = is_bool($this->cookie->treeBranch) && empty($this->cookie->treeBranch) ? 'all' : $this->cookie->treeBranch;
        }

        /* If invoked by projectstory module and not choose product, then get the modules of project story. */
        if(!isset($this->tree)) $this->loadModel('tree');
        $project = $projectID ? $this->loadModel('project')->getByID($projectID) : null;
        if(!empty($projectID) && !empty($project->hasProduct) && $this->app->rawModule == 'projectstory')
        {
            return $this->tree->getProjectStoryTreeMenu($projectID, 0, array('treeModel', $createModuleLink), array('storyType' => $storyType));
        }

        /* Pre generate parameters. */
        $userFunc = array('treeModel', $createModuleLink);
        $extra    = array('projectID' => $projectID, 'productID' => $productID);

        return $this->tree->getTreeMenu($productID, 'story', 0, $userFunc, $extra, $branch, "&param=$param&storyType=$storyType");
    }

    /**
     * 获取项目下的产品列表。
     * Get products of the project.
     *
     * @param  int       $projectID
     * @param  bool      $isProjectStory
     * @access protected
     * @return array
     */
    protected function getProjectProductList(int $projectID, bool $isProjectStory): array
    {
        if($isProjectStory) return $this->product->getProducts($projectID);

        return array();
    }

    /**
     * 获取产品计划。
     * Get product plans of the project.
     *
     * @param  array     $projectProducts
     * @param  int       $projectID
     * @param  string    $storyType
     * @param  bool      $isProjectStory
     * @access protected
     * @return array
     */
    protected function getProductPlans(array $projectProducts, int $projectID, string $storyType, bool $isProjectStory): array
    {
        if($isProjectStory && $storyType == 'story') return $this->loadModel('execution')->getPlans(array_keys($projectProducts), 'skipParent,unexpired,noclosed', $projectID);

        return array();
    }

    /**
     * 获取需求列表及分页对象。
     * Get stories.
     *
     * @param  int       $projectID
     * @param  int       $productID
     * @param  string    $branchID
     * @param  string    $moduleID
     * @param  int       $param
     * @param  string    $storyType  requirement|story
     * @param  string    $browseType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    public function getStories(int $projectID, int $productID, string $branchID = '', int $moduleID = 0, int $param = 0, string $storyType = 'all', string $browseType = 'allstory', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $isProjectStory = $this->app->rawModule == 'projectstory';
        if(!isset($this->story)) $this->loadModel('story');

        /* Get stories. */
        if($isProjectStory)
        {
            $this->products = $this->product->getProducts($projectID, 'all', '', false);

            if($browseType == 'bybranch') $param = $branchID;
            $stories = $this->story->getExecutionStories($projectID, $productID, $sort, $browseType, (string)$param, 'all', '', $pager);
        }
        else
        {
            $queryID = ($browseType == 'bysearch') ? $param : 0;
            $stories = $this->product->getStories($productID, $branchID, $browseType, (int)$queryID, $moduleID, $storyType, $sort, $pager);
        }

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

        return $stories;
    }

    /**
     * 根据需求类型获取需求。
     * Get stories by story type.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $storyType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getStoriesByStoryType(int $productID, string $branch = '', string $storyType = 'all', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $parentIdList = array();
        if($storyType == 'requirement,story' && !empty($this->config->enableER)) $parentIdList = $this->dao->select('id')->from(TABLE_STORY)->where('type')->eq('epic')->andWhere('isParent')->eq(1)->fetchPairs('id', 'id');
        if($storyType == 'story' && (!empty($this->config->URAndSR) || $this->config->edition == 'ipd')) $parentIdList = $this->dao->select('id')->from(TABLE_STORY)->where('type')->eq('requirement')->andWhere('isParent')->eq(1)->fetchPairs('id', 'id');

        $productQuery = $this->loadModel('story')->buildProductsCondition($productID, $branch);
        $stories = $this->dao->select("*,plan,path")->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere($productQuery)
            ->andWhere('parent', true)->eq(0)
            ->beginIF($parentIdList)->orWhere("parent")->in($parentIdList)->fi()
            ->markRight(1)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->beginIF($storyType != 'all')->andWhere('type')->in($storyType)->fi()
            ->beginIF(empty($this->config->enableER))->andWhere('type')->ne('epic')->fi()
            ->beginIF(empty($this->config->URAndSR) && $this->config->edition != 'ipd')->andWhere('type')->ne('requirement')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $stories;
    }

    /**
     * 获取分支的配置数据。
     * Get branch options.
     *
     * @param  array     $projectProducts
     * @param  int       $projectID
     * @access protected
     * @return array
     */
    protected function getBranchOptions(array $projectProducts, int $projectID): array
    {
        $this->loadModel('branch');

        $branchOptions = array();
        foreach($projectProducts as $product)
        {
            if(!$product || $product->type == 'normal') continue;

            $branches = $this->branch->getList($product->id, $projectID, 'all');
            foreach($branches as $branchInfo) $branchOptions[$product->id][$branchInfo->id] = $branchInfo->name;
        }

        return $branchOptions;
    }

    /**
     * 获取分支和分支标签的显示项。
     * Get options of branch and branch tag.
     *
     * @param  int         $productID
     * @param  object|null $product
     * @param  bool        $isProjectStory
     * @access protected
     * @return array[]
     */
    protected function getBranchAndTagOption(int $projectID, object|null $product, bool $isProjectStory): array
    {
        $branchOption    = array();
        $branchTagOption = array();

        if(empty($product) and $isProjectStory) return [$branchOption, $branchTagOption];

        if($product and $product->type != 'normal')
        {
            $branches = $this->loadModel('branch')->getList($product->id, $projectID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id]    = $branchInfo->name;
                $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }

        return [$branchOption, $branchTagOption];
    }

    /**
     * 获取产品看板。
     * Get kanban list for product.
     *
     * @param  string    $browseType
     * @access protected
     * @return array
     */
    protected function getKanbanList(string $browseType = 'my'): array
    {
        $kanbanList = array();

        list($productList, $planList, $projectList, $projectProduct, $latestExecutions, $releaseList) = $this->product->getStats4Kanban($browseType);
        $programPairs = array(0 => $this->lang->project->noProgram) + $this->loadModel('program')->getPairs(true, 'order_asc');
        $productList  = $this->getProductList4Kanban($productList, $planList, $projectList, $releaseList, $projectProduct);

        foreach($productList as $programID => $productList)
        {
            $region = array();

            $heading = new stdclass();
            $heading->title = zget($programPairs, $programID, $programID);

            $region['key']     = $programID;
            $region['id']      = $programID;
            $region['heading'] = $heading;

            $lanes       = array();
            $items       = array();
            $columnCards = array();
            foreach($productList as $laneKey => $laneData)
            {
                $lanes[] = array('name' => $laneKey, 'title' => $laneData->name);
                $columns = array();

                foreach(array('unexpiredPlan', 'doing', 'doingProject', 'doingExecution', 'normalRelease') as $columnKey)
                {
                    $column = array('name' => $columnKey, 'title' => $this->lang->product->{$columnKey});
                    if($columnKey == 'doingExecution' || $columnKey == 'doingProject') $column['parentName'] = 'doing';
                    $columns[] = $column;

                    $cardList = !empty($laneData->{$columnKey}) ? $laneData->{$columnKey} : array();
                    foreach($cardList as $card)
                    {
                        $items[$laneKey][$columnKey][] = array('id' => $card->id, 'name' => $card->id, 'title' => isset($card->name) ? $card->name : $card->title, 'status' => isset($card->status) ? $card->status : '', 'cardType' => $columnKey, 'delay' => !empty($card->delay) ? $card->delay : 0, 'progress' => isset($card->progress) ? $card->progress : 0, 'marker' => isset($card->marker) ? $card->marker : 0);

                        if(!isset($columnCards[$columnKey])) $columnCards[$columnKey] = 0;
                        $columnCards[$columnKey] ++;

                        if($columnKey == 'doingProject')
                        {
                            if(!empty($latestExecutions[$card->id]))
                            {
                                $execution = $latestExecutions[$card->id];
                                $items[$laneKey]['doingExecution'][] = array('id' => $execution->id, 'name' => $execution->id, 'title' => $execution->name, 'status' => $execution->status, 'cardType' => 'doingExecution', 'delay' => !empty($execution->delay) ? $execution->delay : 0, 'progress' => $execution->progress);

                                if(!isset($columnCards['doingExecution'])) $columnCards['doingExecution'] = 0;
                                $columnCards['doingExecution'] ++;
                            }
                        }
                    }
                }
            }

            foreach($columns as $key => $column) $columns[$key]['cards'] = !empty($columnCards[$column['name']]) ? $columnCards[$column['name']] : 0;
            $groupData['key']           = $programID;
            $groupData['data']['lanes'] = $lanes;
            $groupData['data']['cols']  = $columns;
            $groupData['data']['items'] = $items;

            $region['items'] = array($groupData);
            $kanbanList[] = $region;
        }

        return $kanbanList;
    }

    /**
     * 保存需求页面session变量。
     * Save session variables for browse page.
     *
     * @param  object|null $product
     * @param  string      $browseType
     * @access protected
     * @return void
     */
    protected function saveSession4Browse(object|null $product, string $browseType): void
    {
        $uri = $this->app->getURI(true);

        /* For setMenu. */
        if($this->app->tab == 'project')
        {
            $this->session->set('storyList', $uri, 'project');
        }
        else
        {
            $this->session->set('productList', $uri, 'product');
            $this->session->set('storyList',   $uri, 'product');
        }

        if(!empty($product)) $this->session->set('currentProductType', $product->type);

        /* Save browse type into session for buildSearchForm. */
        if($browseType != 'bymodule' && $browseType != 'bybranch') $this->session->set('storyBrowseType', $browseType);
    }

    /**
     * 构建搜索表单。
     * Build search form.
     *
     * @param  object|bool $project
     * @param  int         $projectID
     * @param  int         $productID
     * @param  string      $branch
     * @param  int         $param
     * @param  string      $storyType
     * @param  string      $browseType
     * @param  bool        $isProjectStory
     * @access protected
     * @return void
     */
    protected function buildSearchFormForBrowse(object|null $project, int $projectID, int &$productID, string $branch, int $param, string $storyType, string $browseType, bool $isProjectStory, string $from, int $blockID): void
    {
        if($isProjectStory && !$productID && !empty($this->products)) $productID = (int)key($this->products); // If toggle a project by the #swapper component on the story page of the projectstory module, the $productID may be empty. Make sure it has value.

        if($this->config->edition == 'ipd' && $storyType == 'story') unset($this->config->product->search['fields']['roadmap']);

        if(isset($project->hasProduct) && empty($project->hasProduct))
        {
            /* The none-product project don't need display the product in the search form. */
            unset($this->config->product->search['fields']['product']);
            unset($this->config->product->search['params']['product']);

            /* The none-product and none-scrum project don't need display the plan in the search form. */
            if($project->model != 'scrum')
            {
                unset($this->config->product->search['fields']['plan']);
                unset($this->config->product->search['params']['plan']);
            }
        }

        if($this->config->edition == 'ipd' && $storyType == 'requirement')
        {
            $this->config->product->search['params']['roadmap']['values'] = $this->loadModel('roadmap')->getPairs($productID);
        }

        /* Build search form. */
        $params    = $isProjectStory ? "projectID=$projectID&productID=0" : "productID=$productID";
        $actionURL = $this->createLink($this->app->rawModule, $this->app->rawMethod, $params . "&branch=$branch&browseType=bySearch&queryID=myQueryID&storyType=$storyType&orderBy=&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID&from=$from&blockID=$blockID");

        $this->config->product->search['onMenuBar'] = 'yes';
        $this->config->product->search['module']    = $storyType;
        if($this->app->rawModule != 'product') $this->config->product->search['module'] = $this->app->rawModule;
        $queryID = ($browseType == 'bysearch') ? $param : 0;
        $this->product->buildSearchForm($productID, $this->products, $queryID, $actionURL, $storyType, $branch, $projectID);
    }

    /**
     * 构建矩阵搜索表单。
     * Build search form for track.
     *
     * @param  int         $productID
     * @param  string      $branch
     * @param  int         $projectID
     * @param  string      $browseType
     * @param  int         $param
     * @param  string      $storyType
     * @access protected
     * @return void
     */
    protected function buildSearchFormForTrack(int &$productID, string $branch, int $projectID, string $browseType, int $param, string $storyType): void
    {
        if($this->config->edition == 'ipd' && $storyType == 'story') unset($this->config->product->search['fields']['roadmap']);
        if($this->config->edition == 'ipd' && $storyType == 'requirement')
        {
            $this->config->product->search['params']['roadmap']['values'] = $this->loadModel('roadmap')->getPairs($productID);
        }

        /* Build search form. */
        $actionURL = $this->createLink('product', 'track', "productID={$productID}&branch={$branch}&projectID={$projectID}&browseType=bySearch&param=myQueryID&storyType=$storyType");
        if($this->app->rawModule == 'projectstory') $actionURL = $this->createLink('projectstory', 'track', "projectID={$projectID}&productID={$productID}&branch={$branch}&browseType=bySearch&param=myQueryID&storyType=$storyType");

        $this->config->product->search['module'] = $this->app->rawModule . 'Track';
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        $this->product->buildSearchForm($productID, $this->products, $queryID, $actionURL, $storyType, $branch, $projectID);
    }

    /**
     * 获取需求的ID列表。
     * Get ID list of stories.
     *
     * @param  array     $stories
     * @access protected
     * @return array
     */
    protected function getStoryIdList(array $stories): array
    {
        $storyIdList = array();
        foreach($stories as $story)
        {
            $storyIdList[$story->id] = $story->id;
            if(!empty($story->children))
            {
                foreach($story->children as $child) $storyIdList[$child->id] = $child->id;
            }
        }

        return $storyIdList;
    }

    /**
     * 路线图页面保存session数据。
     * Save session variables data.
     *
     * @access protected
     * @return void
     */
    protected function saveSession4Roadmap(): void
    {
        $this->session->set('releaseList',     $this->app->getURI(true), 'product');
        $this->session->set('productPlanList', $this->app->getURI(true), 'product');
    }

    /**
     * 回复产品不存在提示消息。
     * Response product not found message.
     *
     * @access protected
     * @return void
     */
    protected function responseNotFound4View(): void
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api')
        {
            $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return;
        }

        $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('product', 'all'))));
        return;
    }

    /**
     * 将返回链接保存到session中。
     * Save back uri in session.
     *
     * @access protected
     * @return void
     */
    protected function saveBackUriSessionForDynamic(): void
    {
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('projectList',     $uri, 'project');
        $this->session->set('executionList',   $uri, 'execution');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');
    }

    /**
     * 获取操作记录。
     * Get actions of the product.
     *
     * @param  string    $account
     * @param  string    $orderBy
     * @param  int       $productID
     * @param  string    $type
     * @param  string    $date
     * @param  string    $direction next|pre
     * @access protected
     * @return [array, object]
     */
    protected function getActionsForDynamic(string $account, string $orderBy, int $productID, string $type, string $date, string $direction): array
    {
        /* Build parameters. */
        $period     = $type == 'account' ? 'all'  : $type;
        $date       = empty($date) ? '' : date('Y-m-d', (int)$date);
        $actions    = $this->loadModel('action')->getDynamicByProduct($productID, $account, $period, $orderBy, 50, $date, $direction);
        $dateGroups = $this->action->buildDateGroup($actions, $direction);

        return array($actions, $dateGroups);
    }

    /**
     * 获取产品仪表盘操作记录。
     * Get actions of the product for dashboard.
     *
     * @param  int       $productID
     * @access protected
     * @return array
     */
    protected function getActions4Dashboard(int $productID): array
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 30, 1);

        return $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager, $productID);
    }

    /**
     * 保存产品仪表盘的返回链接session。
     * Save back uri to session.
     *
     * @access protected
     * @return void
     */
    protected function saveBackUriSession4Dashboard(): void
    {
        $uri = $this->app->getURI(true);
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
    }

    /**
     * 获取产品看板页面的产品列表。
     * Get product list for Kanban.
     *
     * @param  array     $productList
     * @param  array     $planList
     * @param  array     $projectList
     * @param  array     $releaseList
     * @param  array     $projectProduct
     * @access protected
     * @return array
     */
    protected function getProductList4Kanban(array $productList, array $planList, array $projectList, array $releaseList, array $projectProduct): array
    {
        $kanbanList = array();
        foreach($productList as $productID => $product)
        {
            if($product->status != 'normal') continue;

            $projects = array();
            if(isset($projectProduct[$productID]))
            {
                foreach($projectProduct[$productID] as $projectID => $project)
                {
                    if(isset($projectList[$projectID])) $projects[$projectID] = $projectList[$projectID];
                }
            }

            $product->unexpiredPlan = zget($planList, $productID, array());
            $product->normalRelease = zget($releaseList, $productID, array());
            $product->doingProject  = $projects;

            $kanbanList[$product->program][$productID] = $product;
        }

        return $kanbanList;
    }

    /**
     * 获取空的小时对象数据。
     * Get empty hour data object.
     *
     * @access protected
     * @return object
     */
    protected function getEmptyHour(): object
    {
        $hour = new stdclass();

        $hour->totalEstimate = 0;
        $hour->totalConsumed = 0;
        $hour->totalLeft     = 0;
        $hour->progress      = 0;

        return $hour;
    }

    /**
     * 设置需求列表数据。
     * Set the data of requirement list.
     *
     * @param  array       $stories
     * @param  string      $browseType
     * @param  string      $storyType
     * @param  bool        $isProjectStory
     * @param  object|null $product
     * @param  object|null $project
     * @param  string      $branch
     * @param  string      $branchID
     * @param  string      $from
     * @access protected
     * @return void
     */
    protected function assignBrowseData(array $stories, string $browseType, string $storyType, bool $isProjectStory, object|null $product, object|null $project, string $branch, string $branchID, string $from = '')
    {
        $productID       = $product ? (int)$product->id : 0;
        $projectID       = $project ? (int)$project->id : 0;
        $productName     = ($isProjectStory && empty($product)) ? $this->lang->product->all : $this->products[$productID];
        $storyIdList     = $this->getStoryIdList($stories);
        $projectProducts = $this->getProjectProductList($projectID, $isProjectStory);
        list($branchOpt, $branchTagOpt) = $this->getBranchAndTagOption($projectID, $product, $isProjectStory);

        $gradeList  = $this->loadModel('story')->getGradeList('');
        $gradeGroup = array();
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        /* Set show module by config. */
        $showModule = empty($this->config->product->browse->showModule) ? 0 : $this->config->product->browse->showModule;
        if($isProjectStory) $showModule = empty($this->config->projectstory->story->showModule) ? 0 : $this->config->projectstory->story->showModule;

        $this->view->title           = $productName . $this->lang->hyphen . ($storyType === 'story' ? $this->lang->product->browse : $this->lang->product->requirement);
        $this->view->productID       = $productID;
        $this->view->product         = $product;
        $this->view->projectID       = $projectID;
        $this->view->project         = $project;
        $this->view->stories         = $stories;
        $this->view->gradeGroup      = $gradeGroup;
        $this->view->gradeMenu       = $this->loadModel('story')->getGradeMenu($storyType, $project);
        $this->view->gradePairs      = $this->story->getGradePairs($storyType);
        $this->view->maxGradeGroup   = $this->story->getMaxGradeGroup();
        $this->view->storyType       = $storyType;
        $this->view->browseType      = $browseType;
        $this->view->isProjectStory  = $isProjectStory;
        $this->view->branch          = $branch;
        $this->view->branchID        = $branchID;
        $this->view->modulePairs     = !empty($showModule) ? $this->loadModel('tree')->getModulePairs($productID, 'story', $showModule) : array();
        $this->view->branchOptions   = (empty($product) && $isProjectStory) ? $this->getBranchOptions($projectProducts, $projectID) : array($productID => $branchOpt);
        $this->view->branchTagOption = $branchTagOpt;
        $this->view->projectProducts = $projectProducts;

        $module = $this->app->tab == 'product' || $from == 'doc' ? $storyType : $this->app->tab;
        $this->view->showGrades = !empty($this->config->{$module}->showGrades) ? $this->config->{$module}->showGrades : $this->story->getDefaultShowGrades($this->view->gradeMenu);

        $storyType = $isProjectStory ? 'all' : $storyType;
        $this->view->summary    = $this->product->summary($stories, $storyType);
        $this->view->plans      = $this->loadModel('productplan')->getPairs($productID, isset($projectProducts[$productID]) ? array(BRANCH_MAIN) + $projectProducts[$productID]->branches : (($branch === 'all' || empty($branch)) ? '' : $branch), 'unexpired,noclosed', true);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->modules    = $this->loadModel('tree')->getOptionMenu($productID, 'story', 0, $branchID);
        $this->view->storyTasks = $this->loadModel('task')->getStoryTaskCounts($storyIdList);
        $this->view->storyBugs  = $this->loadModel('bug')->getStoryBugCounts($storyIdList);
        $this->view->storyCases = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);
        $this->view->reports    = in_array($this->config->edition, array('max', 'ipd')) ? $this->loadModel('researchreport')->getPairs() : array();

        $this->view->roadmaps         = ($this->config->edition == 'ipd' && $storyType != 'story') ? array(0 => '') + $this->loadModel('roadmap')->getPairs($productID) : array();
        $this->view->noclosedRoadmaps = ($this->config->edition == 'ipd' && $storyType != 'story') ? array(0 => '') + $this->loadModel('roadmap')->getPairs($productID, '', 'noclosed') : array();

        $this->display();
    }

    /**
     * 处理项目列表展示数据。
     * Process project list display data.
     *
     * @param  array     $projectList
     * @access protected
     * @return array
     */
    protected function processProjectListData(array $projectList): array
    {
        $userPairs = $this->loadModel('user')->getPairs();
        $userList  = $this->user->getListByAccounts(array_keys($userPairs), 'account');

        $this->loadModel('story');
        $this->loadModel('execution');
        $this->loadModel('project');
        foreach($projectList as $project)
        {
            $project = $this->project->formatDataForList($project, $userList);

            $projectStories = $this->story->getExecutionStories($project->id);
            $project->storyCount  = count($projectStories);
            $project->storyPoints = 0;
            foreach($projectStories as $story) $project->storyPoints += $story->estimate;
            $project->storyPoints .= ' ' . $this->config->hourUnit;

            $executions = $this->execution->getStatData($project->id, 'all', 0, 0, false, 'skipParent');
            $project->executionCount = count($executions);

            $project->from    = 'project';
            $project->actions = $this->project->buildActionList($project);
        }

        return array_values($projectList);
    }

    /**
     * 为跟踪矩阵设置自定义列。
     * Get custom fields for track.
     *
     * @param  string    $storyType   epic|requirement|story
     * @access protected
     * @return array
     */
    public function getCustomFieldsForTrack(string $storyType): array
    {
        $listFields = array();
        $listFields['requirement'] = $this->lang->URCommon;
        $listFields['story']       = $this->lang->SRCommon;
        $listFields['project']     = $this->lang->story->project;
        $listFields['execution']   = $this->lang->story->execution;
        $listFields['design']      = $this->lang->story->design;
        $listFields['commit']      = $this->lang->story->repoCommit;
        $listFields['task']        = $this->lang->story->tasks;
        $listFields['bug']         = $this->lang->story->bugs;
        $listFields['case']        = $this->lang->story->cases;

        if($storyType == 'requirement' || $storyType == 'story') unset($listFields['requirement']);
        if($storyType == 'story') unset($listFields['story']);

        $systemShowFields  = $this->loadModel('setting')->getItem("owner=system&module=product&section=trackFields&key={$storyType}");
        $accountShowFields = $this->setting->getItem("owner={$this->app->user->account}&module=product&section=trackFields&key={$storyType}");

        $showFields = empty($accountShowFields) ? $systemShowFields : $accountShowFields;
        $showFields = empty($showFields) ? array_keys($listFields) : explode(',', $showFields);
        return array('list' => $listFields, 'show' => array_merge(array($storyType), $showFields));
    }

    /**
     * 获取根据矩阵可用的需求类型。
     * Get active storyType list for track.
     *
     * @param  int       $projectID
     * @param  int       $productID
     * @access protected
     * @return array
     */
    public function getActiveStoryTypeForTrack(int $projectID = 0, int $productID = 0): array
    {
        $storyTypeList = $this->lang->story->typeList;

        /* Check story type by linked story's type. */
        if($this->app->rawModule == 'projectstory' && $projectID)
        {
            $stories = $this->getStories($projectID, $productID);
            if($stories)
            {
                $havingType = array();
                foreach($stories as $story) $havingType[$story->type] = $story->type;
                if(!isset($havingType['epic'])) unset($storyTypeList['epic']);
                if(!isset($havingType['epic']) && !isset($havingType['requirement'])) unset($storyTypeList['requirement']);
            }
        }

        return $storyTypeList;
    }
}
