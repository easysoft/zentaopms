<?php
declare(strict_types=1);
/**
 * The control file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @link        https://www.zentao.net
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
     * 展示当前仪表盘下的区块列表。
     * Display blocks for dashboard.
     *
     * @param  string $dashboard
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function dashboard(string $dashboard, int $projectID = 0)
    {
        /* 获取传入应用对应的区块列表 以及 获取当前应用下区块启用状态。 */
        $blocks      = $this->block->getMyDashboard($dashboard);
        $isInitiated = $this->block->getBlockInitStatus($dashboard);

        /* 判断用户是否为首次登录 ，判断条件 当前用户没有该 app 下的区块数据 且 没有设置过该 app 下的区块启用状态 且不是演示模式。 */
        if(empty($blocks) && !$isInitiated && !commonModel::isTutorialMode())
        {
            $this->blockZen->initBlock($dashboard);             // 初始化该 app 下区块数据。
            $blocks = $this->block->getMyDashboard($dashboard); // 获取初始化后的区块列表。
        }

        /* 处理页面需要的数据格式。 */
        $blocks = $this->blockZen->processBlockForRender($blocks, $projectID);

        /* 如果页面渲染方式为 json 的话，直接返回 json 格式数据 ，主要是为了给应用提供数据。 */
        if($this->app->getViewType() == 'json') return print(json_encode($blocks));

        /* 为项目仪表盘页面，设置1.5级导航的项目ID. */
        if($this->app->rawModule == 'project' && $this->app->rawMethod == 'index') $this->view->projectID = $this->session->project;
        if(($this->app->rawModule == 'qa' && $this->app->rawMethod == 'index') || ($this->app->rawModule == 'product' && $this->app->rawMethod == 'dashboard')) $this->view->productID = $this->session->product;

        $this->view->title     = zget($this->lang->block->dashboard, $dashboard, $this->lang->block->dashboard['default']);
        $this->view->blocks    = $blocks;
        $this->view->dashboard = $dashboard;
        $this->display();
    }

    /**
     * 输出一个区块信息。
     * Print a block.
     *
     * @param  int    $blockID
     * @param  string $params
     * @access public
     * @return void
     */
    public function printBlock(int $blockID, string $params = '')
    {
        $block = $this->block->getByID($blockID);

        /* 如果是外部调用，判断密码并组织外部需要的返回信息。  */
        if($this->blockZen->isExternalCall())
        {
            if(!$this->block->checkAPI($this->get->hash)) return;
            $this->blockZen->organizaExternalData($block); // 组织外部需要返回的信息。
        }

        if(empty($block)) return '';

        if($params)
        {
            $params = helper::safe64Decode($params);
            parse_str($params, $params);
        }

        /* 根据 block 的 code 值，选择性调用 zen 中 print + $code + Block 方法获取区块数据。 */
        if(isset($block->params->num) && !isset($block->params->count)) $block->params->count = $block->params->num;

        $code = $block->code;
        if($code == 'statistic' || $code == 'list' || $code == 'overview' || $code == 'team') $code = $block->module . ucfirst($code);

        $function = 'print' . ucfirst($code) . 'Block';
        if(method_exists('blockZen', $function)) $this->blockZen->$function($block, (array)$params);

        /* 补全 moreLink 信息。 */
        $projectID = isset($params['projectID']) ? $params['projectID'] : 0;
        $this->blockZen->createMoreLink($block, (int)$projectID);

        /* 组织渲染页面需要的数据。*/
        $this->view->moreLink       = $block->moreLink;
        $this->view->block          = $block;
        $this->view->longBlock      = $block->width >= 2;
        $this->view->isExternalCall = $this->blockZen->isExternalCall();
        $this->view->params         = $params;

        /* 根据 viewType 值 ，判断是否需要返回 json 数据。 */
        $viewType = (isset($block->params->viewType) && $block->params->viewType == 'json') ? 'json' : 'html';
        if($viewType == 'json') return $this->blockZen->printBlock4Json();

        $this->display('block', strtolower($code) . 'block');
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
        /* 处理表单提交事件。 */
        if($_POST)
        {
            /* 获取表单提交内容。 */
            $formData = form::data($this->config->block->form->create)->get();
            $formData->dashboard = $dashboard;
            $formData->account   = $this->app->user->account;
            $formData->vision    = $this->config->vision;

            /* 如果是HTML区块，则对html区块数据做处理后将内容存放到params字段中。 */
            if($formData->module == 'html')
            {
                $formData = $this->loadModel('file')->processImgURL($formData, 'html', $this->post->uid);
                $formData->params['html'] = $formData->html;
            }

            /* 将params数组转成json格式，方便在数据库中存储。 */
            $formData->params = json_encode($formData->params);
            unset($formData->html);

            /* 根据module和code生成区块的宽度和高度。 */
            $defaultSize = $this->config->block->defaultSize; // 默认为区块的统一默认尺寸。
            if(!empty($this->config->block->size[$formData->module][$formData->code]))                   $defaultSize      = $this->config->block->size[$formData->module][$formData->code];
            if(!empty($this->config->block->size[$formData->module][$formData->code][$formData->width])) $formData->height = $this->config->block->size[$formData->module][$formData->code][$formData->width];
            if(empty($formData->width))  $formData->width  = reset(array_keys($defaultSize));
            if(empty($formData->height)) $formData->height = reset($defaultSize);

            /* 设置区块距离左侧的宽度和距离顶部的高度。 */
            $formData->left = $formData->width == 1 ? 2 : 0;
            $formData->top  = $this->block->computeBlockTop($formData);

            /* 执行区块的数据插入并且返回数据给view层。 */
            $blockID = $this->block->create($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('block', 'set');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "loadComponent('#dashboard'); setTimeout(() => $('#dashboard .dashboard-block[data-id=\"$blockID\"]').scrollIntoView({behavior: 'smooth'}), 500);", 'closeModal' => true));
        }

        $modules = $this->blockZen->getAvailableModules($dashboard); // 不同的仪表盘获取不同的可选择的模块列表。
        unset($modules['']);

        if(empty($modules)) $module = $dashboard;                                       // 如果该仪表盘下没有模块列表，则模块同仪表盘。
        if(empty($module) && !empty($modules)) $module = current(array_keys($modules)); // 如果当前没有选择模块，则选中第一个。

        $codes  = $this->blockZen->getAvailableCodes($module);         // 根据仪表盘和模块获取可用的区块列表。
        $params = $this->blockZen->getAvailableParams($module, $code); // 根据所属模块和区块code获取参数配置项列表。

        $this->view->title      = $this->lang->block->createBlock;
        $this->view->dashboard  = $dashboard;
        $this->view->module     = $module;
        $this->view->code       = $code;
        $this->view->modules    = $modules;
        $this->view->codes      = $codes;
        $this->view->params     = $params;
        $this->view->blockTitle = $this->blockZen->getBlockTitle($modules, $module, $codes, $code, $params);
        $this->display();
    }

    /**
     * 编辑区块。
     * Edit a block.
     *
     * @param  int    $blockID
     * @param  string $module
     * @param  string $code
     * @access public
     * @return void
     */
    public function edit(int $blockID, string $module = '', string $code = '')
    {
        /* 处理表单提交事件。 */
        if($_POST)
        {
            /* 获取表单提交内容。 */
            $formData = form::data($this->config->block->form->edit)->get();
            $formData->id = $blockID;

            /* 如果是HTML区块，则对html区块数据做处理后将内容存放到params字段中。 */
            if($formData->module == 'html')
            {
                $formData = $this->loadModel('file')->processImgURL($formData, 'html', $this->post->uid);
                $formData->params['html'] = $formData->html;
            }

            /* 将params数组转成json格式，方便在数据库中存储。 */
            $formData->params = json_encode($formData->params);
            unset($formData->html);

            /* 根据表单中选择的宽度匹配配置项中的高度。 */
            if(!empty($this->config->block->size[$formData->module][$formData->code][$formData->width])) $formData->height = $this->config->block->size[$formData->module][$formData->code][$formData->width];

            /* 根据区块ID获取更新前区块信息，判断区块宽度是否发生变化。 */
            $block  = $this->block->getByID($blockID);
            $isWidthChanged = $block->width != $formData->width;

            /* 执行区块的数据变更并且返回数据给view层。 */
            $this->block->update($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('block', 'set');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => $isWidthChanged ? "loadComponent('#dashboard')" : "$('#dashboard').dashboard('load', '$blockID')", 'closeModal' => true));
        }

        $block  = $this->block->getByID($blockID);    // 根据区块ID获取区块信息。
        $module = $module ? $module : $block->module; // 获取当前选中的模块，默认取区块的所属模块。
        $code   = $code   ? $code   : $block->code;   // 获取当前选中的区块code，默认取区块的所属code。

        $modules = $this->blockZen->getAvailableModules($block->dashboard); // 根据所属仪表盘获取可选择的模块列表。
        $codes   = $this->blockZen->getAvailableCodes($module);             // 根据所属仪表盘和选中模块获取可选择的区块列表。
        $params  = $this->blockZen->getAvailableParams($module, $code);     // 根据当前选中模块和选中区块code获取参数配置项列表。

        $this->view->title      = $this->lang->block->editBlock;
        $this->view->block      = $block;
        $this->view->dashboard  = $block->dashboard;
        $this->view->module     = $module;
        $this->view->modules    = $this->blockZen->getAvailableModules($block->dashboard);
        $this->view->codes      = $codes;
        $this->view->code       = in_array($code, array_keys($codes)) ? $code : ''; // 当变更了所属模块(module)后, 判断当前code在是否还包含在codes中，没有的话置空code。
        $this->view->params     = $params;
        $this->view->blockTitle = $module == $block->module && $code == $block->code ? $block->title : $this->blockZen->getBlockTitle($modules, $module, $codes, $code, $params);
        $this->display();
    }

    /**
     * 根据区块ID删除一个区块。
     * Delete a block by id.
     *
     * @param  int    $blockID
     * @access public
     * @return void
     */
    public function delete(int $blockID)
    {
        /* 执行区块的数据删除并且返回数据给view层。 */
        $this->block->deleteBlock($blockID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('score')->create('block', 'set'); // 设置区块后的积分奖励操作。

        return $this->send(array('result' => 'success'));
    }

    /**
     * 永久关闭区块。
     * Close block forever.
     *
     * @param  int    $blockID
     * @access public
     * @return void
     */
    public function close(int $blockID)
    {
        /* 所有配置过该区块的人都要删掉此区块，所以要按照module和code来删除, 不能用ID来删。 */
        $block = $this->block->getByID($blockID);
        $this->block->deleteBlock(0, $block->module, $block->code);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        /* 将关闭的区块保存到配置信息以便恢复时使用。 */
        $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
        $this->loadModel('setting')->setItem('system.block.closed', $closedBlock . ",{$block->module}|{$block->code}");

        return $this->send(array('result' => 'success'));
    }

    /**
     * 将当前用户该仪表盘下的区块恢复默认布局。
     * Reset dashboard layout.
     *
     * @param  string $dashboard
     * @access public
     * @return void
     */
    public function reset(string $dashboard)
    {
        /* 执行恢复布局并且返回数据给view层。 */
        $this->block->reset($dashboard);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * 修改当前用户仪表盘区块的布局。
     * Change dashboard layout.
     *
     * @access public
     * @return void
     */
    public function layout()
    {
        if(!$_POST) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->noData));

        $blocksLayout = $this->post->block;
        $this->block->updateLayout($blocksLayout);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }
}
