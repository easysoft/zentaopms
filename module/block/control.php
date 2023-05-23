<?php
declare(strict_types=1);
 /**
 * The control file of block of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @link        http://www.zentao.net
 */
class block extends control
{
    /**
     * construct.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        /* 如果为外部调用，判断密钥，如果密钥不通过，返回空字符串。 */
        if($this->methodName != 'admin' && $this->methodName != 'dashboard' && $this->isExternalCall() && !$this->loadModel('sso')->checkKey()) helper::end('');
    }

    /**
     * 创建区块。
     * Create a block under a dashboard.
     *
     * @param  string $dashboard
     * @param  string $module
     * @param  string $code
     * @access public
     * @return void
     */
    public function create(string $dashboard, string $module = '', string $code = '')
    {
        if($_POST)
        {
            $formData = form::data($this->config->block->form->create)->get();
            $formData->dashboard = $dashboard;
            $formData->account   = $this->app->user->account;
            $formData->vision    = $this->config->vision;
            $formData->params    = json_encode($formData->params);

            $this->block->create($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title     = $this->lang->block->createBlock;
        $this->view->dashboard = $dashboard;
        $this->view->module    = $module;
        $this->view->code      = $code;
        $this->view->modules   = $this->blockZen->getAvailableModules($dashboard);
        $this->view->codes     = $this->blockZen->getAvailableCodes($dashboard, $module);
        $this->view->params    = $this->blockZen->getAvailableParams($module, $code);
        $this->display();
    }

    /**
     * 编辑区块。
     * Update a block.
     *
     * @param  string $dashboard
     * @param  string $module
     * @param  string $code
     * @access public
     * @return void
     */
    public function edit(string $blockID, string $module = '', string $code = '')
    {
        $blockID = (int)$blockID;
        if($_POST)
        {
            $formData = form::data($this->config->block->form->edit)->get();
            $formData->id     = $blockID;
            $formData->params = helper::jsonEncode($formData->params);

            $this->block->update($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        /* 如果没传 $code 说明是首次进入页面，直接使用待编辑 $block 的 $code。 */
        /* If no $code is passed, it indicates that you are entering the page for the first time, and you can directly use the $code of the $block to be edited. */
        $block  = $this->block->getByID($blockID);
        $module = $module ? $module : $block->module;
        $code   = $code ? $code : $block->code;
        $codes  = $this->blockZen->getAvailableCodes($block->dashboard, $module);

        $this->view->title     = $this->lang->block->editBlock;
        $this->view->block     = $block;
        $this->view->dashboard = $block->dashboard;
        $this->view->module    = $module;
        $this->view->modules   = $this->blockZen->getAvailableModules($block->dashboard);
        $this->view->codes     = $codes;
        /* $codes 包含 $code 时，页面才选中对应的 $code，否则为空。 */
        /* Only when $codes have a value and contain $code, the corresponding $code is selected on the page, otherwise it is $blank. */
        $this->view->code      = in_array($code, array_keys($codes)) ? $code : '';
        $this->view->params    = $this->blockZen->getAvailableParams($this->view->module, $this->view->code);
        $this->display();
    }

    /**
     * 根据区块ID删除一个区块。
     * Delete a block by id.
     *
     * @param  string $blockID
     * @access public
     * @return void
     */
    public function delete(string $blockID)
    {
        $blockID = (int)$blockID;
        $this->block->deleteBlock($blockID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('score')->create('block', 'set');
        return $this->send(array('result' => 'success'));
    }

    /**
     * 重新构建区块的布局。
     * build the layout of blocks.
     *
     * @access public
     * @return void
     */
    public function buildLayout()
    {
        $orders = explode(',', $orders);
        foreach($orders as $order => $blockID) $this->block->setOrder($blockID, $order);

        if(dao::isError()) return $this->send(array('result' => 'fail'));

        $this->loadModel('score')->create('block', 'set');
        return $this->send(array('result' => 'success'));
    }

    /**
     * 永久关闭区块。
     * Close block forever.
     *
     * @param  string $blockID
     * @access public
     * @return void
     */
    public function close(string $blockID)
    {
        $blockID = (int)$blockID; // 强制转换为 int 类型，防止调用 model、tao 方法时报错。

        /* 永久关闭区块。 */
        $block = $this->block->getByID($blockID);
        $this->block->deleteBlock(0, $block->module, $block->code);

        /* 将关闭的区块保存到配置信息。 */
        $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
        $this->loadModel('setting')->setItem('system.block.closed', $closedBlock . ",{$block->module}|{$block->code}");
        return $this->send(array('result' => 'success'));
    }

    /**
     * 将当前仪表盘下的区块恢复默认。
     * Reset dashboard blocks.
     *
     * @param  string $dashboard
     * @access public
     * @return void
     */
    public function reset(string $dashboard)
    {
        $this->block->reset($dashboard);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * 展示当前仪表盘下的区块列表。
     * Display blocks for dashboard.
     *
     * @param  string $dashboard
     * @param  string $projectID
     * @access public
     * @return void
     */
    public function dashboard(string $dashboard, string $projectID = '0')
    {
        $projectID = (int)$projectID; // 强制转换为 int 类型，防止调用 model、tao 方法时报错。

        /* 获取传入应用对应的区块列表 以及 获取当前应用下区块启用状态。 */
        $blocks      = $this->block->getMyDashboard($dashboard);
        $isInitiated = $this->block->getBlockInitStatus($dashboard);

        /* 判断用户是否为首次登录 ，判断条件 当前用户没有该 app 下的区块数据 且 没有设置过该 app 下的区块启用状态 且不是演示模式。 */
        if(empty($blocks) && !$isInitiated && !defined('TUTORIAL'))
        {
            $this->block->initBlock($dashboard); // 初始化该 app 下区块数据。
            $blocks = $this->block->getMyDashboard($dashboard); // 获取初始化后的区块列表。
        }

        /* 处理页面需要的数据格式。 */
        $blocks = $this->blockZen->processBlockForRender($blocks, $projectID);

        /* 如果页面渲染方式为 json 的话，直接返回 json 格式数据 ，主要是为了给应用提供数据。 */
        if($this->app->getViewType() == 'json') return print(json_encode($blocks));

        /* 组织渲染页面需要数据。 */
        $this->view->title     = zget($this->lang->block->dashboard, $dashboard, $this->lang->block->dashboard['default']);
        $this->view->blocks    = $blocks;
        $this->view->dashboard = $dashboard;
        $this->render();
    }

    /**
     * 输出一个区块信息。
     * Print a block.
     *
     * @param  int    $blockID
     * @access public
     * @return string
     */
    public function printBlock(int $blockID)
    {
        $block   = $this->block->getByID($blockID);

        /* 如果是外部调用，判断密码并组织外部需要的返回信息。  */
        if($this->blockZen->isExternalCall())
        {
            if(!$this->block->checkAPI($this->get->hash)) return;
            $this->blockZen->organizaExternalData($block); // 组织外部需要返回的信息。
        }

        if(empty($block)) return '';

        /* 根据 block 的 code 值，选择性调用 zen 中 print + $code + Block 方法获取区块数据。 */
        if(isset($block->params->num) && !isset($block->params->count)) $block->params->count = $block->params->num;

        $code = $block->code;
        if($code == 'statistic' || $code == 'list' || $code == 'overview') $code = $block->module . ucfirst($code);

        $function = 'print' . ucfirst($code) . 'Block';
        if(method_exists('blockZen', $function)) $this->blockZen->$function($block);

        /* 补全 moreLink 信息。 */
        $this->blockZen->createMoreLink($block, 0);

        /* 组织渲染页面需要的数据。*/
        $this->view->moreLink       = $block->moreLink;
        $this->view->title          = $block->title;
        $this->view->block          = $block;
        $this->view->isExternalCall = $this->blockZen->isExternalCall();

        /* 根据 viewType 值 ，判断是否需要返回 json 数据。 */
        $viewType = (isset($block->params->viewType) && $block->params->viewType == 'json') ? 'json' : 'html';
        if($viewType == 'json') return $this->blockZen->printBlock4Json();

        $this->display('block', strtolower($code) . 'block');
    }
}
