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
     * @param int $programID
     * @access protected
     * @return void
     */
    protected function setMenu4Create(int $program): void
    {
        if($this->app->tab == 'program') $this->loadModel('program')->setMenu($programID);
        if($this->app->tab == 'doc') unset($this->lang->doc->menu->product['subMenu']);
        if($this->app->getViewType() != 'mhtml') return;

        if($this->app->rawModule == 'projectstory' and $this->app->rawMethod == 'story')
        {
            $this->loadModel('project')->setMenu();
            return;
        }
        $this->product->setMenu();
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
     * @param  int $programID
     * @access private
     * @return array
     */
    private function getFormFields4Create(int $programID = 0): array
    {
        $fields = $this->config->product->form->create;

        $this->loadModel('user');
        $poUsers = $this->user->getPairs('nodeleted|pofirst|noclosed');
        $qdUsers = $this->user->getPairs('nodeleted|qdfirst|noclosed');
        $rdUsers = $this->user->getPairs('nodeleted|devfirst|noclosed');
        $users   = $this->user->getPairs('nodeleted|noclosed');

        foreach($fields as $field => $attr)
        {
            if(isset($attr['options']) and $attr['options'] == 'users') $fields[$field]['options'] = $users;
            $fields[$field]['name']  = $field;
            $fields[$field]['title'] = $this->lang->product->$field;
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
    protected function buildCreateForm(int $programID = 0, string $extra = ''): void
    {
        $this->view->title      = $this->lang->product->create;
        $this->view->gobackLink = $this->getBackLink4Create($extra);
        $this->view->programID  = $programID;
        $this->view->fields     = $this->getFormFields4Create($programID);
        unset($this->lang->product->typeList['']);

        $this->display();
    }

    /**
     * 追加创建信息，处理白名单、评审者、项目集字段，还有富文本内容处理。
     * Prepare data for create.
     *
     * @param  int    $programID
     * @param  string $extra
     * @access protected
     * @return object
     */
    protected function prepareCreateExtras(object $data): object
    {
        $product = $data->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('createdVersion', $this->config->version)
            ->setIF($this->config->systemMode == 'light', 'program', (int)zget($this->config->global, 'defaultProgram', 0))
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->stripTags($this->config->product->editor->create['id'], $this->config->allowedTags)
            ->join('whitelist', ',')
            ->join('reviewer', ',')
            ->remove('uid,newLine,lineName,contactListMenu')
            ->get();

        $product = $this->loadModel('file')->processImgURL($product, $this->config->product->editor->create['id'], $this->post->uid);

        return $product;
    }

    /**
     * 成功插入产品数据后，其他的额外操作。
     * Process after create product.
     *
     * @param  int    $productID
     * @param  object $product
     * @access protected
     * @return void
     */
    protected function responseAfterCreate(int $productID, object $product): void
    {
        $fixData = new stdclass();
        $fixData->order = $productID * 5;
        if(!empty($_POST['lineName']))
        {
            $lineID = $this->product->createLine((int)$product->program);
            if($lineID) $fixData->line = $lineID;
        }

        $this->dao->update(TABLE_PRODUCT)->data($fixData)->where('id')->eq($productID)->exec();
        $this->file->updateObjectID($this->post->uid, $productID, 'product');

        $this->loadModel('personnel')->updateWhitelist(explode(',', $product->whitelist), 'product', $productID);
        if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');

        $this->product->createMainLib($productID);

        $this->loadModel('action')->create('product', $productID, 'opened');

        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;

        if($this->viewType == 'json')
        {
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $productID));
            return;
        }

        $this->sendCreateLocate($productID, (int)$product->program);
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
    private function sendCreateLocate(int $productID, int $programID): void
    {
        $tab = $this->app->tab;
        $moduleName = $tab == 'program'? 'program' : $this->moduleName;
        $methodName = $tab == 'program'? 'product' : 'browse';
        $param      = $tab == 'program' ? "programID=$programID" : "productID=$productID";
        $locate     = isonlybody() ? 'true' : $this->createLink($moduleName, $methodName, $param);
        if($tab == 'doc') $locate = $this->createLink('doc', 'productSpace', "objectID=$productID");

        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locate));
    }

    /**
     * 输出创建产品产生的错误。
     * Send error for create.
     *
     * @access protected
     * @return void
     */
    protected function sendError4Create(): void
    {
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }
}
