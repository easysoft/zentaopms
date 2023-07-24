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
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('user');
    }

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

            $errors = array();
            if(empty($modelConfig->type)) $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->type);
            if(empty($modelConfig->key))  $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->apiKey);
            if(!empty($modelConfig->proxyType) && empty($modelConfig->proxyAddr))
            {
                $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->proxyAddr);
            }
            if(!empty($errors)) return $this->send(array('result' => 'fail', 'message' => implode('<br>', $errors)));

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
        if($_POST)
        {
            $data = fixer::input('post')->get();
            if(isset($data->togglePromptStatus) && isset($data->promptId))
            {
                $this->ai->togglePromptStatus($data->promptId);

                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return $this->send(array('result' => 'success'));
            }
        }
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

        $this->display();
    }

    /**
     * View prompt details.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function promptView($id)
    {
        $prompt = $this->ai->getPromptById($id);

        $this->view->prompt      = $prompt;
        $this->view->preAndNext  = $this->loadModel('common')->getPreAndNextObject('prompt', $id);
        $this->view->actions     = $this->loadModel('action')->getList('prompt', $id);
        $this->view->dataPreview = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->title       = "{$this->lang->ai->prompts->common}#{$prompt->id} $prompt->name";

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
        $this->display();
    }

    /**
     * Edit a prompt.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function promptEdit($id)
    {
        $prompt = $this->ai->getPromptByID($id);

        if(strtolower($this->server->request_method) == 'post')
        {
            $data = fixer::input('post')->get();

            $prompt->name = $data->name;
            $prompt->desc = $data->desc;

            $this->ai->updatePrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'locate' => $this->inlink('promptView', "id={$prompt->id}") . '#app=admin'));
        }

        $this->view->prompt = $prompt;
        $this->view->title  = $this->lang->ai->prompts->edit;
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

            $originalPrompt = clone $prompt;

            $prompt->model            = 0; // TODO: use actual $role->model;
            $prompt->role             = $role->role;
            $prompt->characterization = $role->characterization;

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID") . '#app=admin'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptAssignRole', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = $this->lang->ai->prompts->assignRole . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
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

            $originalPrompt = clone $prompt;

            $prompt->module = $data->datagroup;
            $prompt->source = ",$data->datasource,";

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID") . '#app=admin'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->activeDataSource = empty($prompt->module) ? 'my' : $prompt->module;
        $this->view->dataSource       = $this->config->ai->dataSource;
        $this->view->prompt           = $prompt;
        $this->view->promptID         = $promptID;
        $this->view->lastActiveStep   = $this->ai->getLastActiveStep($prompt);
        $this->view->title            = $this->lang->ai->prompts->selectDataSource . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
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

            $originalPrompt = clone $prompt;

            $prompt->purpose     = $data->purpose;
            $prompt->elaboration = $data->elaboration;

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID") . '#app=admin'));
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->dataPreview    = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = $this->lang->ai->prompts->setPurpose . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
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

            $originalPrompt = clone $prompt;

            $prompt->targetForm = $data->targetForm;

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->goTesting)) // Go to testing object view.
            {
                $location = $this->ai->getTestingLocation($prompt);
                return $this->send(empty($location) ? array('result' => 'fail', 'target' => '#go-test-btn', 'msg' => $this->lang->ai->prompts->goingTestingFail) : array('result' => 'success', 'target' => '#go-test-btn', 'msg' => $this->lang->ai->prompts->goingTesting, 'locate' => $location));
            }

            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID") . '#app=admin'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->dataPreview    = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = $this->lang->ai->prompts->setTargetForm . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
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

            $originalPrompt = clone $prompt;

            $prompt->name = $data->name;
            $prompt->desc = $data->desc;

            $this->ai->updatePrompt($prompt, $originalPrompt);
            $this->ai->togglePromptStatus($prompt, 'active');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($isJumpToNext)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('prompts') . '#app=admin'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = $this->lang->ai->prompts->finalize . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Execute prompt on obejct, and redirect to target form page.
     *
     * @param  int    $promptId
     * @param  int    $objectId
     * @access public
     * @return void
     */
    public function promptExecute($promptId, $objectId)
    {
        $prompt = $this->ai->getPromptByID($promptId);
        if(empty($prompt) || !$this->ai->isExecutable($prompt)) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->execute->fail));

        $response = $this->ai->executePrompt($prompt, $objectId);
        if(empty($response)) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->execute->fail));

        $this->ai->setInjectData($prompt->targetForm, $response);
        $location = $this->ai->getTargetFormLocation($prompt, $objectId);
        if(empty($location)) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->execute->fail));

        $_SESSION['auditPrompt']['prompt'] = $prompt;
        if($prompt->status == 'draft')
        {
            $_SESSION['auditPrompt']['time'] = time();
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->ai->execute->success, 'locate' => $location));
    }

    /**
     * Audit prompt modal.
     *
     * @param  int    $promptId
     * @param  int    $objectId
     * @access public
     * @return void
     */
    public function promptAudit($promptId, $objectId)
    {
        $prompt = $this->ai->getPromptByID($promptId);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->role             = $data->role;
            $prompt->characterization = $data->characterization;
            $prompt->purpose          = $data->purpose;
            $prompt->elaboration      = $data->elaboration;

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($data->backLocation == 1)
            {
                $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "reloadPrompt($promptId, $objectId)");
            }
            else
            {
                $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true);
            }

            $this->sendSuccess($response);
        }
        $objectForPrompt = $this->ai->getObjectForPromptById($prompt, $objectId);
        if(empty($objectForPrompt))
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->execute->fail));
        }

        list($objectData, $object) = $objectForPrompt;

        $this->view->prompt     = $prompt;
        $this->view->object     = $object;
        $this->view->dataPrompt = $this->ai->serializeDataToPrompt($prompt->module, $prompt->source, $objectData);

        $this->display();
    }

    /**
     * Publish prompt, set status to active.
     *
     * @param  int    $id
     * @param  bool   $backToTestingLocation
     * @access public
     * @return void
     */
    public function promptPublish($id, $backToTestingLocation = false)
    {
        unset($_SESSION['auditPrompt']);
        $this->ai->togglePromptStatus($id, 'active');

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if($backToTestingLocation)
        {
            $prompt = $this->ai->getPromptByID($id);
            return $this->send(array('result' => 'success', 'message' => $this->lang->ai->prompts->action->publishSuccess, 'locate' => $this->ai->getTestingLocation($prompt)));
        }

        return $this->send(array('result' => 'success'));
    }

    /**
     * Unpublish prompt, set status to draft.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function promptUnpublish($id)
    {
        $this->ai->togglePromptStatus($id, 'draft');

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success'));
    }
}
