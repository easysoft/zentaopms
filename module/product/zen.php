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
            $productID = $this->product->saveState(0, $this->products);
            $this->product->setMenu($productID);
        }
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
        if($this->app->tab == 'doc') unset($this->lang->doc->menu->product['subMenu']);
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
        if($programID) return $this->loadModel('program')->setMenu($programID);
        $this->product->setMenu($productID);
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

        if($moduleName == 'qa')        return $this->setShowErrorNoneMenu4QA($activeMenu);
        if($moduleName == 'project')   return $this->setShowErrorNoneMenu4Project($activeMenu, $objectID);
        if($moduleName == 'execution') return $this->setShowErrorNoneMenu4Execution($activeMenu, $objectID);
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
        if(empty($fields)) $fields = $this->config->product->form->create;

        $this->loadModel('user');
        $poUsers = $this->user->getPairs('nodeleted|pofirst|noclosed');
        $qdUsers = $this->user->getPairs('nodeleted|qdfirst|noclosed');
        $rdUsers = $this->user->getPairs('nodeleted|devfirst|noclosed');
        $users   = $this->user->getPairs('nodeleted|noclosed');

        foreach($fields as $field => $attr)
        {
            if(isset($attr['options']) and $attr['options'] == 'users') $fields[$field]['options'] = $users;
            $fields[$field]['name']  = $field;
            $fields[$field]['title'] = zget($this->lang->product, $field);
        }

        $fields['program']['options'] = array('') + $this->loadModel('program')->getTopPairs('', 'noclosed');
        $fields['PO']['options']      = $poUsers;
        $fields['QD']['options']      = $qdUsers;
        $fields['RD']['options']      = $rdUsers;

        if($programID) $fields['line']['options'] = array('') + $this->product->getLinePairs($programID);
        if(empty($programID) or $this->config->systemMode != 'ALM') unset($fields['line']);

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
        $programID = (int)$product->program;
        $fields    = $this->getFormFields4Create($programID, $this->config->product->form->edit);

        /* Check program priv, and append to program list that is not exist product's program. */
        $hasPrivPrograms = $this->app->user->view->programs;
        if($programID and strpos(",{$hasPrivPrograms},", ",{$programID},") === false) $fields['program']['control'] = 'hidden';
        if(!isset($fields['program']['options'][$programID]) and $programID)
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
     * Get product lines and product lines of program.
     *
     * @access protected
     * @return array
     */
    protected function getProductLines(): array
    {
        /* Get all product lines. */
        /* TODO use model of module. */
        $productLines = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('deleted')->eq(0)->orderBy('`order` asc')->fetchAll();

        /* Collect product lines of program lines. */
        $programLines = array();
        foreach($productLines as $productLine)
        {
            if(!isset($programLines[$productLine->root])) $programLines[$productLine->root] = array();
            $programLines[$productLine->root][$productLine->id] = $productLine->name;
        }

        return array($productLines, $programLines);
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
     * 追加创建信息，处理白名单、项目集字段，还有富文本内容处理。
     * Prepare data for create.
     *
     * @param  object $data
     * @param  string $acl
     * @param  string $uid
     * @access protected
     * @return object
     */
    protected function prepareCreateExtras(object $data, string $acl, string $uid): object
    {
        $product = $data->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('createdVersion', $this->config->version)
            ->setIF($this->config->systemMode == 'light', 'program', (int)zget($this->config->global, 'defaultProgram', 0))
            ->setIF($acl == 'open', 'whitelist', '')
            ->stripTags($this->config->product->editor->create['id'], $this->config->allowedTags)
            ->remove('uid,newLine,lineName,contactListMenu')
            ->get();

        return $this->loadModel('file')->processImgURL($product, $this->config->product->editor->create['id'], $uid);
    }

    /**
     * 处理白名单和富文本内容。
     * Prepare data for edit.
     *
     * @param  object $data
     * @param  string $acl
     * @param  string $uid
     * @access protected
     * @return object
     */
    protected function prepareEditExtras(object $data, string $acl, string $uid): object
    {
        $product = fixer::input('post')->setIF($acl == 'open', 'whitelist', '')
            ->stripTags($this->config->product->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid,changeProjects,contactListMenu')
            ->get();

        return $this->loadModel('file')->processImgURL($product, $this->config->product->editor->edit['id'], $uid);
    }

    /**
     * 成功插入产品数据后，其他的额外操作。
     * Process after create product.
     *
     * @param  int    $productID
     * @param  object $product
     * @param  string $uid
     * @param  string $lineName
     * @access protected
     * @return void
     */
    protected function responseAfterCreate(int $productID, object $product, string $uid, string $lineName = '')
    {
        /* Fix order and line fields for product. */
        $fixData = new stdclass();
        $fixData->order = $productID * 5;
        if(!empty($lineName))
        {
            $lineID = $this->product->createLine((int)$product->program, $lineName);
            if($lineID) $fixData->line = $lineID;
        }
        $this->dao->update(TABLE_PRODUCT)->data($fixData)->where('id')->eq($productID)->exec();

        /* Update and create linked data. */
        $this->loadModel('file')->updateObjectID($uid, $productID, 'product');
        $this->product->createMainLib($productID);
        if($product->whitelist)     $this->loadModel('personnel')->updateWhitelist(explode(',', $product->whitelist), 'product', $productID);
        if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');

        $this->loadModel('action')->create('product', $productID, 'opened');

        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $productID));
        $this->sendCreateLocate($productID, (int)$product->program);
    }

    /**
     * 成功更新产品数据后，其他的额外操作。
     * Process after edit product.
     *
     * @param  int    $productID
     * @param  int    $programID
     * @param  array  $changes
     * @access protected
     * @return void
     */
    protected function responseAfterEdit(int $productID, int $programID, array $changes)
    {
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('product', $productID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;

        return $this->sendCreateLocate($productID, $programID);
    }

    /**
     * 创建完成后，做页面跳转。
     * Locate after create product.
     *
     * @param  int   $productID
     * @param  int   $programID
     * @access private
     * @return void
     */
    private function sendCreateLocate(int $productID, int $programID)
    {
        $tab = $this->app->tab;
        $moduleName = $tab == 'program' ? 'program' : $this->moduleName;
        $methodName = $tab == 'program' ? 'product' : 'browse';
        $param      = $tab == 'program' ? "programID=$programID" : "productID=$productID";
        $locate     = isonlybody() ? 'true' : $this->createLink($moduleName, $methodName, $param);
        if($tab == 'doc') $locate = $this->createLink('doc', 'productSpace', "objectID=$productID");

        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locate));
    }

    /**
     * 编辑完成后，做页面跳转。
     * Locate after edit product.
     *
     * @param  int   $productID
     * @param  int   $programID
     * @access private
     * @return void
     */
    private function sendEditLocate(int $productID, int $programID)
    {
        $moduleName = $programID ? 'program' : 'product';
        $methodName = $programID ? 'product' : 'view';
        $param      = $programID ? "programID=$programID" : "product=$productID";
        $locate     = $this->createLink($moduleName, $methodName, $param);

        if(!$programID) $this->session->set('productList', $this->createLink('product', 'browse', $param), 'product');
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
    }

    /**
     * 输出数据库操作产生的错误。
     * Send error for dao.
     *
     * @access protected
     * @return void
     */
    protected function sendDaoError()
    {
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }
}
