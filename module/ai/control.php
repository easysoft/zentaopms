<?php
/**
 * The control file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
class ai extends control
{
    /**
     * List models.
     *
     * TODO: not fully implemented yet, currently shows the only model config.
     *
     * @access public
     * @return void
     */
    public function models()
    {
        $modelConfig = new stdclass();
        $modelConfig->type        = '';
        $modelConfig->key         = '';
        $modelConfig->proxyType   = '';
        $modelConfig->proxyAddr   = '';
        $modelConfig->description = '';
        $modelConfig->status      = '';

        $storedModelConfig = $this->loadModel('setting')->getItems('owner=system&module=ai');
        foreach($storedModelConfig as $item) $modelConfig->{$item->key} = $item->value;

        $this->view->modelConfig = $modelConfig;
        $this->view->title       = $this->lang->ai->models->title;
        $this->view->position[]  = $this->lang->ai->models->common;
        $this->display();
    }

    /**
     * Edit model configuration, store in system.ai settings.
     *
     * @access public
     * @return void
     */
    public function editModel()
    {
        if(strtolower($this->server->request_method) == 'post')
        {
            $modelConfig = fixer::input('post')->get();
            $this->loadModel('setting')->setItems('system.ai', $modelConfig);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'locate' => $this->inlink('models') . '#app=admin'));
        }

        $modelConfig = new stdclass();
        $modelConfig->type        = '';
        $modelConfig->key         = '';
        $modelConfig->proxyType   = '';
        $modelConfig->proxyAddr   = '';
        $modelConfig->description = '';
        $modelConfig->status      = '';

        $storedModelConfig = $this->loadModel('setting')->getItems('owner=system&module=ai');
        foreach($storedModelConfig as $item) $modelConfig->{$item->key} = $item->value;

        $this->view->modelConfig = $modelConfig;
        $this->view->title       = $this->lang->ai->models->title;
        $this->view->position[]  = $this->lang->ai->models->common;
        $this->display();
    }

    /**
     * Test connection to API endpoint.
     *
     * @access public
     * @return void
     */
    public function testConnection()
    {
        $modelConfig = fixer::input('post')->get();
        $this->ai->setConfig($modelConfig);

        $result = $this->ai->complete('test', 1); // Test completing 'test' with length of 1.
        if($result === false) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->models->testConnectionResult->fail));

        return $this->send(array('result' => 'success', 'message' => $this->lang->ai->models->testConnectionResult->success));
    }

    /**
     * List prompts.
     *
     * @param  string $module
     * @param  string $status
     * @access public
     * @return void
     */
    public function prompts($module = '', $status = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        /* Set pager and order. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $order = common::appendOrder($orderBy);

        $this->view->prompts    = $this->ai->getPrompts($module, $status, $order, $pager);
        $this->view->module     = $module;
        $this->view->status     = $status;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->title      = $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;

        $this->display();
    }

    /**
     * Create a prompt.
     *
     * @access public
     * @return void
     */
    public function createPrompt()
    {
        if(strtolower($this->server->request_method) == 'post')
        {
            $prompt   = fixer::input('post')->get();
            $promptID = $this->ai->createPrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'callback' => "gotoPrompt($promptID)"));
        }

        $this->view->title      = $this->lang->ai->prompts->create;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Edit role of prompt, prompt editing step 2.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptAssignRole($promptID)
    {
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $role = fixer::input('post')->get();

            $isJumpToNext = $role->jumpToNext == '1';
            unset($role->jumpToNext);

            $prompt->model            = $role->model;
            $prompt->role             = $role->role;
            $prompt->characterization = $role->characterization;

            $this->ai->updatePrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID") . '#app=admin'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptAssignRole', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->prompt     = $prompt;
        $this->view->promptID   = $promptID;
        $this->view->title      = $this->lang->ai->prompts->assignRole . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Set data source of prompt, prompt editing step 3.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptSelectDataSource($promptID)
    {
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $isJumpToNext = $data->jumpToNext == '1';
            unset($data->jumpToNext);

            $prompt->module = $data->datagroup;
            $prompt->source = ",$data->datasource,";

            $this->ai->updatePrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID") . '#app=admin'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->activeDataSource = empty($prompt->module) ? 'story' : $prompt->module;
        $this->view->dataSource       = $this->config->ai->dataSource;
        $this->view->prompt           = $prompt;
        $this->view->promptID         = $promptID;
        $this->view->title            = $this->lang->ai->prompts->selectDataSource . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[]       = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Edit purpose and purpose elaboration of prompt, prompt editing step 4.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptSetPurpose($promptID)
    {
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $isJumpToNext = $data->jumpToNext == '1';
            unset($data->jumpToNext);

            $prompt->purpose     = $data->purpose;
            $prompt->elaboration = $data->elaboration;

            $this->ai->updatePrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID") . '#app=admin'));
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->dataPreview = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->prompt      = $prompt;
        $this->view->promptID    = $promptID;
        $this->view->title       = $this->lang->ai->prompts->setPurpose . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[]  = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Set target form of prompt, prompt editing step 5.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptSetTargetForm($promptID)
    {
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $isJumpToNext = $data->jumpToNext == '1';
            unset($data->jumpToNext);

            $prompt->targetForm = $data->targetForm;

            $this->ai->updatePrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->goTesting)) // Go to testing object view.
            {
                $location = $this->ai->getTestingLocation($prompt);
                return $this->send(empty($location) ? array('result' => 'fail', 'message' => $this->lang->ai->prompts->goingTestingFail) : array('result' => 'success', 'message' => $this->lang->ai->prompts->goingTesting, 'locate' => $location));
            }

            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID") . '#app=admin'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->dataPreview = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->prompt      = $prompt;
        $this->view->promptID    = $promptID;
        $this->view->title       = $this->lang->ai->prompts->setTargetForm . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[]  = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Edit additional information of prompt, final prompt editing step.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptFinalize($promptID)
    {
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $isJumpToNext = $data->jumpToNext == '1';
            unset($data->jumpToNext);

            $prompt->name = $data->name;
            $prompt->desc = $data->desc;

            $this->ai->updatePrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('prompts') . '#app=admin'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->prompt     = $prompt;
        $this->view->promptID   = $promptID;
        $this->view->title      = $this->lang->ai->prompts->finalize . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }
}
