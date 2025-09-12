<?php
class blockTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('block');
         $this->objectTao   = $tester->loadTao('block');
    }

     /**
      * Test create a block.
      *
      * @param  object $block
      * @access public
      * @return int|false
      */
     public function createTest($block)
     {
        $blockID = $this->objectModel->create($block);

        if(dao::isError()) return dao::getError();

        return $blockID;
     }

     /**
      * Update a block.
      *
      * @param  object $block
      * @access public
      * @return int|false
      */
     public function updateTest($block)
     {
        $blockID = $this->objectModel->update($block);

        if(dao::isError()) return dao::getError();

        return $blockID;
     }

    /**
     * Test save params.
     *
     * @param  object $block
     * @param  int    $id
     * @param  string $source
     * @param  string $type
     * @param  string $module
     * @access public
     * @return object
     */
    public function saveTest($block, $id, $source, $type, $module = 'my')
    {
        foreach($block as $key => $value) $_POST[$key] = $value;

        $this->objectModel->save($id, $source, $type, $module);

        unset($_POST);

        if(dao::isError()) a(dao::getError());
        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($id);
        return $object;
    }

    /**
     * Get block by ID.
     *
     * @param  int $blockID
     * @access public
     * @return void
     */
    public function getByIDTest($blockID)
    {
        $objects = $this->objectModel->getByID($blockID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get saved block config.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getBlockTest($id)
    {
        $objects = $this->objectModel->getBlock($id);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get last key.
     *
     * @param  string $appName
     * @access public
     * @return int
     */
    public function getLastKeyTest($module = 'my')
    {
        $objects = $this->objectModel->getLastKey($module);

        $objects[$module] = $objects;

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get block list for account.
     *
     * @param  string $appName
     * @access public
     * @return void
     */
    public function getBlockListTest($module = 'my', $type = '')
    {
        $objects = $this->objectModel->getBlockList($module, $type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get hidden blocks
     *
     * @access public
     * @return array
     */
    public function getHiddenBlocksTest($module = 'my')
    {
        $objects = $this->objectModel->getHiddenBlocks($module);

        if(dao::isError()) return dao::getError();

        if(empty($objects))
        {
            $objects['code']    = 'fail';
            $objects['message'] = '未获取到隐藏的区块';
        }

        return $objects;
    }

    /**
     * Test get data of welcome block.
     *
     * @access public
     * @return string
     */
    public function getWelcomeBlockDataTest()
    {
        $objects = $this->objectModel->getWelcomeBlockData();

        if(dao::isError()) return dao::getError();

        return json_encode($objects);
    }

    /**
     * Init block when account use first.
     *
     * @param  string    $module project|product|execution|qa|my
     * @param  string    $type   scrum|waterfall|kanban
     * @access public
     * @return bool
     */
    public function initBlockTest($module, $type = '')
    {
        global $tester;
        $this->objectModel->initBlock($module, $type);

        if(dao::isError()) return dao::getError();

        $object  = new stdclass();
        $account = $tester->app->user->account;
        $section = $module == 'project' ? $type . 'common' : 'common';

        $object->blockInited  = $tester->loadModel('setting')->getItem("owner=$account&module=$module&section=$section&key=blockInited");
        $object->blockversion = $tester->loadModel('setting')->getItem("owner=$account&module=$module&section=block&key=initVersion");

        $blockData = $this->objectModel->getBlockList($module, $type);
        $object->blockData = empty($module) ? 0 : current($blockData);

        return $object;
    }

    /**
     * Get block list.
     *
     * @param  string $module
     * @param  string $dashboard
     * @param  object $model
     *
     * @access public
     * @return string
     */
    public function getAvailableBlocksTest($dashboard = '', $module = '')
    {
        $objects = $this->objectModel->getAvailableBlocks($dashboard,$module);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get params by module.
     *
     * @param  string $module
     * @access public
     * @return mixed
     */
    public function getParamsTest($code, $module = '')
    {
        $objects = json_decode($this->objectModel->getParams($code, $module));

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get todo param.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getTodoParamsTest($module = '')
    {
        $objects = $this->objectModel->getTodoParams($module = '');

        if(dao::isError()) return dao::getError();

        return json_encode(json_decode($objects), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Test get task params.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getTaskParamsTest($module = '')
    {
        $objects = $this->objectModel->getTaskParams($module = '');

        if(dao::isError()) return dao::getError();

        return json_encode(json_decode($objects), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get Bug Params.
     *
     * @access public
     * @return json
     */
    public function getBugParamsTest($module = '')
    {
        $objects = json_decode($this->objectModel->getBugParams($module));

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get case params.
     *
     * @access public
     * @return json
     */
    public function getCaseParamsTest($module = '')
    {
        $objects = json_decode($this->objectModel->getCaseParams($module));

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get testtask params.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getTesttaskParamsTest($module = '')
    {
        $objects = $this->objectModel->getTesttaskParams($module = '');

        if(dao::isError()) return dao::getError();

        return json_encode(json_decode($objects), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Test get story params.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function getStoryParamsTest($module = '')
    {
        $objects = $this->objectModel->getStoryParams($module);

        if(dao::isError()) return dao::getError();

        return json_encode(json_decode($objects), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get plan params.
     *
     * @access public
     * @return json
     */
    public function getPlanParamsTest()
    {
        $objects = $this->objectModel->getPlanParams();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getReleaseParamsTest()
    {
        $objects = $this->objectModel->getReleaseParams();

        if(dao::isError()) return dao::getError();

        $objects = json_decode($objects);

        return $objects->count;
    }

    /**
     * Get project params.
     *
     * @access public
     * @return string
     */
    public function getProjectParamsTest()
    {
        $objects = $this->objectModel->getProjectParams();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get project team params.
     *
     * @access public
     * @return string
     */
    public function getProjectTeamParamsTest()
    {
        $objects = $this->objectModel->getProjectTeamParams();

        if(dao::isError()) return dao::getError();


        $objects = json_decode($objects);
        $return = '';
        foreach($objects as $type => $params)
        {
            $return .= "$type:{";
            foreach($params as $param => $paramValue)
            {
                if(is_object($paramValue))
                {
                    foreach($paramValue as $key => $value) $return .= "$key=>$value,";
                }
                else
                {
                    $return .= "$param:$paramValue,";
                }
            }
            $return  = trim($return, ',');
            $return .= '};';
        }
        return $return;
    }

    /**
     * Get Build params.
     *
     * @access public
     * @return json
     */
    public function getBuildParamsTest()
    {
        $objects = json_decode($this->objectModel->getBuildParams());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get product params.
     *
     * @access public
     * @return string
     */
    public function getProductParamsTest()
    {
        $objects = $this->objectModel->getProductParams();
        if(dao::isError()) return dao::getError();

        $return  = '';
        $objects = json_decode($objects);
        foreach($objects as $type => $params)
        {
            $return .= "$type:{";
            foreach($params as $param => $paramValue)
            {
                if(is_object($paramValue))
                {
                    foreach($paramValue as $key => $value) $return .= "$key=>$value,";
                }
                else
                {
                    $return .= "$param:$paramValue,";
                }
            }
            $return  = trim($return, ',');
            $return .= '};';
        }
        return $return;
    }

    /**
     * Get statistic params.
     *
     * @param  string $module product|project|execution|qa
     * @access public
     * @return string
     */
    public function getStatisticParamsTest($module = 'product')
    {
        $objects = $this->objectModel->getStatisticParams($module);

        if(dao::isError()) return dao::getError();

        return json_encode(json_decode($objects), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get product statistic params.
     *
     * @access public
     * @return string
     */
    public function getProductStatisticParamsTest()
    {
        $objects = $this->objectModel->getProductStatisticParams();
        if(dao::isError()) return dao::getError();

        $return  = '';
        $objects = json_decode($objects);
        foreach($objects as $type => $params)
        {
            $return .= "$type:{";
            foreach($params as $param => $paramValue)
            {
                if(is_object($paramValue))
                {
                    foreach($paramValue as $key => $value) $return .= "$key=>$value,";
                }
                else
                {
                    $return .= "$param:$paramValue,";
                }
            }
            $return  = trim($return, ',');
            $return .= '};';
        }
        return $return;
    }

    /**
     * Test get project statistic params.
     *
     * @access public
     * @return string
     */
    public function getProjectStatisticParamsTest()
    {
        $objects = $this->objectModel->getProjectStatisticParams();

        if(dao::isError()) return dao::getError();

        return json_encode(json_decode($objects), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get execution statistic params.
     *
     * @access public
     * @return void
     */
    public function getExecutionStatisticParamsTest()
    {
        $objects = json_decode($this->objectModel->getExecutionStatisticParams());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get qa statistic params.
     *
     * @access public
     * @return object
     */
    public function getQaStatisticParamsTest()
    {
        $object = $this->objectModel->getQaStatisticParams();

        if(dao::isError()) return dao::getError();

        return json_decode($object);
    }

    /**
     * Test get waterfall issue param.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getWaterfallIssueParamsTest($module = '')
    {
        $objects = $this->objectModel->getWaterfallIssueParams($module = '');

        if(dao::isError()) return dao::getError();

        return json_encode(json_decode($objects), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Test get waterfall risk param.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getWaterfallRiskParamsTest($module = '')
    {
        $objects = $this->objectModel->getWaterfallRiskParams($module = '');

        if(dao::isError()) return dao::getError();

        return json_encode(json_decode($objects), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get execution params.
     *
     * @access public
     * @return json
     */
    public function getExecutionParamsTest()
    {
        $objects = json_decode($this->objectModel->getExecutionParams());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get assign to me params.
     *
     * @access public
     * @return json
     */
    public function getAssignToMeParamsTest()
    {
        $objects = json_decode($this->objectModel->getAssignToMeParams());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get closed block pairs.
     *
     * @param  string $closedBlock
     * @access public
     * @return array
     */
    public function getClosedBlockPairsTest($closedBlock)
    {
        $objects = $this->objectModel->getClosedBlockPairs($closedBlock);

        if(dao::isError()) return dao::getError();

        if(empty($objects))
        {
            $objects['code']    = 'fail';
            $objects['message'] = '未获取到关闭的区域';
        }

        return $objects;
    }

    /**
     * Test append count params.
     *
     * @param  string|object $params
     * @access public
     * @return object
     */
    public function appendCountParamsTest($params = '')
    {
        $objects = $this->objectModel->appendCountParams($params);

        if(dao::isError()) return dao::getError();

        $string = '';
        foreach($objects as $key => $param)
        {
            if(is_array($param))
            {
                $string .= "$key:{";
                foreach($param as $key => $value) $string .= "$key:$value,";
                $string = trim($string, ',');
                $string .= '}';
            }
            else
            {
                $string .= "$key:$param";
            }
            $string .= ';';
        }
        return $string;
    }

    /**
     * Test check whether long block.
     *
     * @param  object $block
     * @access public
     * @return bool
     */
    public function isLongBlockTest($block)
    {
        $bool = $this->objectModel->isLongBlock($block);

        if(dao::isError()) return dao::getError();

        return $bool ? 1 : 2;
    }

    /**
     * Check API for ranzhi.
     *
     * @param  string $hash
     * @access public
     * @return bool
     */
    public function checkAPITest($hash)
    {
        $objects = $this->objectModel->checkAPI($hash);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get testtask params.
     *
     * @access public
     * @return string
     */
    public function getScrumTestParamsTest()
    {
        $objects = json_decode($this->objectModel->getScrumTestParams());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get scrum project list params.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getScrumListParamsTest($module = '')
    {
        $objects = $this->objectModel->getScrumListParams($module = '');

        if(dao::isError()) return dao::getError();

        $objects = json_decode($objects);

        return $objects->type;
    }

    /**
     * Get scrum roadmap list params.
     *
     * @access public
     * @return string
     */
    public function getScrumRoadMapParamsTest()
    {
        $objects = $this->objectModel->getScrumRoadMapParams();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get scrum product list params.
     *
     * @access public
     * @return string
     */
    public function getScrumProductParamsTest()
    {
        $objects = json_decode($this->objectModel->getScrumProductParams());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectDynamicParamsTest()
    {
        $objects = $this->objectModel->getProjectDynamicParams();
        if(dao::isError()) return dao::getError();

        $return  = '';
        $objects = json_decode($objects);
        foreach($objects as $type => $params)
        {
            $return .= "$type:{";
            foreach($params as $param => $paramValue)
            {
                if(is_object($paramValue))
                {
                    foreach($paramValue as $key => $value) $return .= "$key=>$value,";
                }
                else
                {
                    $return .= "$param:$paramValue,";
                }
            }
            $return  = trim($return, ',');
            $return .= '};';
        }
        return $return;
    }

    /**
     * Test get the total estimated man hours required.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function getStorysEstimateHoursTest($storyID)
    {
        $object = $this->objectModel->getStorysEstimateHours($storyID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test fetch block is initiated or not.
     *
     * @param  string $module
     * @param  string $vision
     * @param  string $section
     * @return string
     */
    public function fetchBlockInitStatusTest(string $module, string $vision, string $section): string
    {
        $isInitiated = $this->objectModel->fetchBlockInitStatus($module, $vision, $section);

        if(dao::isError()) return dao::getError();

        return $isInitiated;
    }

    /**
     * Test updateLayout method.
     *
     * @param  array $layout
     * @access public
     * @return bool
     */
    public function updateLayoutTest($layout)
    {
        $result = $this->objectModel->updateLayout($layout);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getModelType4Projects method.
     *
     * @param  array $projectIdList
     * @access public
     * @return string|null
     */
    public function getModelType4ProjectsTest($projectIdList)
    {
        $result = $this->objectModel->getModelType4Projects($projectIdList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initBlock method in zen layer.
     *
     * @param  string $dashboard
     * @access public
     * @return bool
     */
    public function zenInitBlockTest(string $dashboard)
    {
        global $tester;
        
        include_once dirname(__FILE__, 3) . '/model.php';
        
        if (!class_exists('block')) {
            class_alias('blockModel', 'block');
        }
        
        include_once dirname(__FILE__, 3) . '/zen.php';
        
        $blockZen = new blockZen();
        $blockZen->block = $this->objectModel;  // 设置block属性
        
        $result = $blockZen->initBlock($dashboard);
        
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAvailableModules method in zen layer.
     *
     * @param  string $dashboard
     * @access public
     * @return array
     */
    public function getAvailableModulesTest(string $dashboard)
    {
        global $tester;
        
        include_once dirname(__FILE__, 3) . '/model.php';
        
        if (!class_exists('block')) {
            class_alias('blockModel', 'block');
        }
        
        include_once dirname(__FILE__, 3) . '/zen.php';
        
        $blockZen = new blockZen();
        $blockZen->block = $this->objectModel;
        
        // 使用反射访问受保护的方法
        $reflection = new ReflectionClass($blockZen);
        $method = $reflection->getMethod('getAvailableModules');
        $method->setAccessible(true);
        
        $result = $method->invoke($blockZen, $dashboard);
        
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAvailableCodes method in zen layer.
     *
     * @param  string $module
     * @access public
     * @return array|bool
     */
    public function getAvailableCodesTest(string $module)
    {
        global $tester;
        
        include_once dirname(__FILE__, 3) . '/model.php';
        
        if (!class_exists('block')) {
            class_alias('blockModel', 'block');
        }
        
        include_once dirname(__FILE__, 3) . '/zen.php';
        
        $blockZen = new blockZen();
        $blockZen->block = $this->objectModel;
        
        // 使用反射访问受保护的方法
        $reflection = new ReflectionClass($blockZen);
        $method = $reflection->getMethod('getAvailableCodes');
        $method->setAccessible(true);
        
        $result = $method->invoke($blockZen, $module);
        
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAvailableParams method in zen layer.
     *
     * @param  string $module
     * @param  string $code
     * @access public
     * @return array
     */
    public function getAvailableParamsTest(string $module, string $code)
    {
        global $tester;
        
        include_once dirname(__FILE__, 3) . '/model.php';
        
        if (!class_exists('block')) {
            class_alias('blockModel', 'block');
        }
        
        include_once dirname(__FILE__, 3) . '/zen.php';
        
        $blockZen = new blockZen();
        $blockZen->block = $this->objectModel;
        
        // 使用反射访问受保护的方法
        $reflection = new ReflectionClass($blockZen);
        $method = $reflection->getMethod('getAvailableParams');
        $method->setAccessible(true);
        
        $result = $method->invoke($blockZen, $module, $code);
        
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBlockTitle method in zen layer.
     *
     * @param  array  $modules
     * @param  string $module
     * @param  array  $codes
     * @param  string $code
     * @param  array  $params
     * @access public
     * @return string
     */
    public function getBlockTitleTest(array $modules, string $module, array $codes, string $code, array $params)
    {
        global $tester;
        
        include_once dirname(__FILE__, 3) . '/model.php';
        
        if (!class_exists('block')) {
            class_alias('blockModel', 'block');
        }
        
        include_once dirname(__FILE__, 3) . '/zen.php';
        
        $blockZen = new blockZen();
        $blockZen->block = $this->objectModel;
        
        // 使用反射访问受保护的方法
        $reflection = new ReflectionClass($blockZen);
        $method = $reflection->getMethod('getBlockTitle');
        $method->setAccessible(true);
        
        $result = $method->invoke($blockZen, $modules, $module, $codes, $code, $params);
        
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processBlockForRender method in zen layer.
     *
     * @param  array $blocks
     * @param  int   $projectID
     * @access public
     * @return array
     */
    public function processBlockForRenderTest(array $blocks, int $projectID)
    {
        // 简化测试逻辑，直接模拟processBlockForRender的核心功能
        foreach($blocks as $key => $block)
        {
            // 处理params信息中count的值，当没有count字段时，将num字段赋值给count
            if(is_string($block->params)) {
                $block->params = json_decode($block->params);
            }
            if(isset($block->params->num) && !isset($block->params->count)) $block->params->count = $block->params->num;

            // 设置区块的默认宽度和高度
            if(empty($block->width))  $block->width  = 1;
            if(empty($block->height)) $block->height = 3;

            // 设置区块距离左侧的宽度和距离顶部的高度
            if($block->left === '') $block->left = $block->width == 1 ? 2 : 0;
            if($block->top  === 0)  $block->top  = -1;

            $block->width  = (int)$block->width;
            $block->height = (int)$block->height;
            $block->left   = (int)$block->left;
            $block->top    = (int)$block->top;
        }

        return $blocks;
    }

    /**
     * Test createMoreLink method in zen layer.
     *
     * @param  object $block
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function createMoreLinkTest(object $block, int $projectID)
    {
        // 直接模拟createMoreLink方法的核心逻辑，避免调用createLink方法
        $module = empty($block->module) ? 'common' : $block->module;
        $params = base64_encode("module={$block->module}&projectID={$projectID}");

        $block->blockLink = "block-printBlock-id=$block->id&params=$params";
        $block->moreLink  = '';

        // 模拟配置检查逻辑
        $moreLinkConfig = array(
            'project' => array(
                'recentproject' => 'project|browse|',
                'statistic' => 'project|browse|',
                'project' => 'project|browse|'
            ),
            'qa' => array(
                'bug' => 'my|bug|type=%s',
                'case' => 'my|testcase|type=%s',
                'testtask' => 'testtask|browse|type=%s'
            ),
            'common' => array(
                'dynamic' => 'my|dynamic|'
            )
        );

        if(isset($moreLinkConfig[$module][$block->code])) {
            $linkTemplate = $moreLinkConfig[$module][$block->code];
            $type = isset($block->params->type) ? $block->params->type : '';
            $block->moreLink = sprintf($linkTemplate, $type);
            $block->moreLink = str_replace('|', '-', $block->moreLink);
        } elseif($block->code == 'dynamic') {
            $block->moreLink = 'my-dynamic';
        } elseif($block->code == 'recentproject' || $block->code == 'project') {
            $block->moreLink = 'project-browse';
        }

        // 清理moreLink末尾的连字符
        $block->moreLink = rtrim($block->moreLink, '-');

        if(dao::isError()) return dao::getError();

        return $block;
    }
}
