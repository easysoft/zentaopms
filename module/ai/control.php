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
     * AI model.
     *
     * @var aiModel
     * @access public
     */
    public $ai;

    /**
     * User model.
     *
     * @var userModel
     * @access public
     */
    public $user;

    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('user');
    }

    /**
     * Check for user's privs and model configs, redirect to most relevant page.
     *
     * @access public
     * @return void
     */
    public function adminIndex()
    {
        /* Redirect to model edit ifuser has priv and model is unconfigured. */
        if(commonModel::hasPriv('ai', 'models') && !$this->ai->hasModelsAvailable()) return $this->locate($this->createLink('ai', 'models'));

        /* Redirect to prompts ifuser has priv. */
        if(commonModel::hasPriv('ai', 'prompts')) return $this->locate($this->createLink('ai', 'prompts'));

        /* Redirect to miniPrograms ifuser has priv. */
        if(commonModel::hasPriv('ai', 'miniPrograms')) return $this->locate($this->createLink('ai', 'miniPrograms'));

        /* Redirect to models ifuser has priv. */
        if(commonModel::hasPriv('ai', 'models')) return $this->locate($this->createLink('ai', 'models'));

        /* Redirect to assistants ifuser has priv. */
        if(commonModel::hasPriv('ai', 'assistants')) return $this->locate($this->createLink('ai', 'assistants'));

        /* User has no priv, deny access. */
        return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied, 'locate' => 'back'));
    }


    /**
     * List mini programs.
     *
     * @access public
     * @return void
     */
    public function miniPrograms($category = '', $status = '', $orderBy = 'createdDate_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->lang->aiapp->menu->generalAgent['subModule'] = 'ai';

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $order = common::appendOrder($orderBy);
        $categoryList = array_merge($this->lang->ai->miniPrograms->categoryList, $this->ai->getCustomCategories());

        $programs = $this->ai->getMiniPrograms($category, $status, $order, $pager);
        foreach($programs as $program)
        {
            $program->canPublish     = empty($program->published) && $this->ai->canPublishMiniProgram($program);
            $program->createdByLabel = $program->createdBy === 'system' ? $this->lang->admin->system : $this->loadModel('user')->getById($program->createdBy, 'account')->realname;
            $program->categoryLabel  = $categoryList[$program->category];
            $program->publishedLabel = $program->published === '1'
                ? $this->lang->ai->miniPrograms->statuses['active']
                : $this->lang->ai->miniPrograms->statuses['draft'];
        }

        $this->app->loadLang('aiapp');
        $this->view->title        = $this->lang->aiapp->manageGeneralAgent;
        $this->view->miniPrograms = $programs;
        $this->view->category     = $category;
        $this->view->categoryList = $categoryList;
        $this->view->status       = $status;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->display();
    }

    /**
     * Edit mini program category.
     *
     * @access public
     * @return void
     */
    public function editMiniProgramCategory()
    {
        $this->lang->aiapp->menu->generalAgent['subModule'] = 'ai';

        if(!empty($_POST))
        {
            $_POST = array_filter($_POST, function($key)
            {
                return strpos($key, 'custom') === 0;
            }, ARRAY_FILTER_USE_KEY);
            if($this->ai->checkDuplicatedCategory()) return $this->sendError($this->lang->ai->maintenanceGroupDuplicated);
            $this->ai->updateCustomCategories();
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $this->view->usedCustomCategories = $this->ai->getUsedCustomCategories();
        $this->view->categoryList         = $this->ai->getCustomCategories();
        $this->view->title                = $this->lang->ai->miniPrograms->common;
        $this->display();
    }

    /**
     * Publish a mini program.
     *
     * @param  string  $appID
     * @access public
     * @return void
     */
    public function publishMiniProgram($appID)
    {
        $result = $this->ai->publishMiniProgram($appID, '1');
        if($result) return $this->send(array('result' => 'success', 'load' => true, 'message' => $this->lang->ai->publishSuccess));
        $this->sendError(dao::getError());
    }

    /**
     * Unpublish a mini program.
     *
     * @param  string  $appID
     * @access public
     * @return void
     */
    public function unpublishMiniProgram($appID)
    {
        $result = $this->ai->publishMiniProgram($appID, '0');
        if($result) return $this->send(array('result' => 'success', 'load' => true, 'message' => $this->lang->ai->unpublishSuccess));
        $this->sendError(dao::getError());
    }

    /**
     * Import mini program from zip file.
     *
     * @access public
     * @return void
     */
    public function importMiniProgram()
    {
        if(empty($_FILES)) return $this->sendError(array('file' => sprintf($this->lang->error->notempty, $this->lang->ai->installPackage)));

        $failResponse = array('result' => 'fail', 'message' => $this->lang->ai->saveFail, 'locate' => $this->createLink('ai', 'miniprograms'));
        if(empty($_POST)) return $this->send($failResponse);

        $errors = $this->ai->verifyRequiredFields(array('category' => $this->lang->ai->miniPrograms->category, 'published' => $this->lang->ai->toPublish));
        if($errors !== false) return $this->sendError($errors);

        $file     = $_FILES['file'];
        $filePath = $file['tmp_name'];
        $result   = $this->ai->extractZtAppZip($filePath);
        if(!is_array($result)) return $this->send($failResponse);

        $info     = $result[0];
        $fileName = $info['filename'];
        if(!is_file($fileName)) return $this->send($failResponse);

        $content = file_get_contents($fileName);
        unlink($fileName);
        if(!empty($content) && strpos($content, '<?php') === 0)
        {
            $content = str_replace(['<?php', "\r", "\n"], '', $content);
            $pos = strpos($content, "\$ztApp = '");
            if($pos != 0) return $this->send($failResponse);

            $content = rtrim(substr($content, $pos + strlen("\$ztApp = '")), "';");
        }
        if(empty($content)) return $this->send($failResponse);

        $ztApp  = json_decode($content);
        if(!is_object($ztApp)) return $this->send($failResponse);

        $ztApp->name      = $this->ai->getUniqueAppName($ztApp->name);
        $ztApp->published = $_POST['published'];
        $ztApp->category  = $_POST['category'];
        $this->ai->createMiniProgram($ztApp);
        return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
    }

    /**
     * List prompts.
     *
     * @param  string $module
     * @param  string $status
     * @access public
     * @return void
     */
    public function prompts($module = '', $status = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('user');
        $users     = $this->user->getPairs('noletter');
        $userList  = $this->user->getList('nodeleted');

        /* Set pager and order. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $order = common::appendOrder($orderBy);

        $this->view->prompts    = $this->ai->getPrompts($module, $status, $order, $pager);
        $this->view->module     = $module;
        $this->view->status     = $status;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->title      = $this->lang->aiapp->zentaoAgent;
        $this->view->users      = $users;
        $this->view->userList   = $userList;

        if($this->config->edition == 'open')
        {
            $this->view->promptModules = array_map(function ($prompt)
            {
                return $prompt->module;
            }, $this->ai->getPrompts());
        }
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
        $this->view->title       = "{$this->lang->aiapp->zentaoAgent}#{$prompt->id} $prompt->name";

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
        if($_POST)
        {
            $prompt   = form::data($this->config->ai->form->createPrompt)->get();
            $promptID = $this->ai->createPrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $url = commonModel::hasPriv('ai', 'designPrompt') ? $this->createLink('ai', 'promptassignrole', "prompt=$promptID") : $this->createLink('ai', 'promptview', "id=$promptID");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $url));
        }

        $this->view->title = $this->lang->ai->prompts->create;
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

        if($_POST)
        {
            $data = form::data($this->config->ai->form->createPrompt)->get();

            $prompt->name = $data->name;
            $prompt->desc = $data->desc;

            $this->ai->updatePrompt($prompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->inlink('promptView', "id={$prompt->id}")));
        }

        $this->view->prompt = $prompt;
        $this->view->title  = $this->lang->ai->prompts->edit;
        $this->display();
    }

    /**
     * Delete a prompt.
     *
     * @param  int    $prompt
     * @access public
     * @return void
     */
    public function promptDelete($prompt)
    {
        $result = $this->ai->deletePrompt($prompt);

        if(dao::isError() || $result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->ai->prompts->action->deleteSuccess, 'load' => $this->inlink('prompts')));
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
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->model            = $data->model;
            $prompt->role             = $data->role;
            $prompt->characterization = $data->characterization;

            if(!empty($data->saveTemplate) && $data->saveTemplate == 'save')
            {
                $this->ai->createRoleTemplate($prompt->role, $prompt->characterization);
            }

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID")));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptAssignRole', "promptID=$promptID")));
        }

        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} $prompt->name {$this->lang->hyphen} " . $this->lang->ai->prompts->assignRole . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
        $this->view->roleTemplates  = $this->ai->getRoleTemplates();
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
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->module = $data->datagroup;
            $prompt->source = ",$data->datasource,";

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID")));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID")));
        }

        $this->view->activeDataSource = empty($prompt->module) ? current(array_keys($this->config->ai->dataSource)) : $prompt->module;
        $this->view->dataSource       = $this->config->ai->dataSource;
        $this->view->prompt           = $prompt;
        $this->view->promptID         = $promptID;
        $this->view->lastActiveStep   = $this->ai->getLastActiveStep($prompt);
        $this->view->title            = "{$this->lang->ai->prompts->common}#{$prompt->id} $prompt->name {$this->lang->hyphen} " . $this->lang->ai->prompts->selectDataSource . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
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
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        $prompt = $this->ai->getPromptByID($promptID);

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(!empty($_POST))
            {
                $data = fixer::input('post')->get();
            }
            else
            {
                $input = file_get_contents('php://input');
                $data  = json_decode($input);

                if(json_last_error() !== JSON_ERROR_NONE)
                {
                    return $this->send(array('result' => 'fail', 'message' => 'JSON解析失败：' . json_last_error_msg()));
                }
            }

            if(!is_object($data)) $data = new stdClass();

            $originalPrompt = clone $prompt;

            $prompt->purpose      = isset($data->purpose) ? $data->purpose : '';
            $prompt->elaboration  = '';
            $prompt->knowledgeLib = $data->knowledgeLib ?? '';

            if(isset($data->fields))
            {
                $fields = is_array($data->fields) ? $data->fields : array();
                $this->ai->savePromptFields($promptID, $fields);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID")));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID")));
        }

        $knowledgeLibIds = [];
        if(!empty($prompt->knowledgeLib)) $knowledgeLibIds = explode(',', $prompt->knowledgeLib);

        $knowledgeLibs = (empty($knowledgeLibIds)) ? [] : $this->ai->getKnowledgeLibsByIDs($knowledgeLibIds);

        $currentPrompt = $prompt->purpose;
        if(!empty($prompt->elaboration)) $currentPrompt .= "\n\n" . $prompt->elaboration;

        $this->view->dataPreview    = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->currentFields  = $this->ai->getPromptFields($promptID);
        $this->view->currentPrompt  = $currentPrompt;
        $this->view->knowledgeLibs  = $knowledgeLibs;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} $prompt->name {$this->lang->hyphen} " . $this->lang->ai->prompts->setPurpose . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
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
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->targetForm = $data->targetForm;

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->goTesting)) // Go to testing object view.
            {
                $location = $this->ai->getTestingLocation($prompt);
                return $this->send(empty($location) ? array('result' => 'fail', 'target' => '#go-test-btn', 'message' => $this->lang->ai->prompts->goingTestingFail) : array('result' => 'success', 'target' => '#go-test-btn', 'msg' => $this->lang->ai->prompts->goingTesting, 'locate' => $location));
            }

            if(!empty($data->jumpToNext)) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID")));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID")));
        }

        $this->view->dataPreview    = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} $prompt->name {$this->lang->hyphen} " . $this->lang->ai->prompts->setTargetForm . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
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
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->name = $data->name;
            $prompt->desc = $data->desc;

            $this->ai->updatePrompt($prompt, $originalPrompt);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) $this->ai->togglePromptStatus($prompt, 'active');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('prompts')));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID")));
        }

        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} $prompt->name {$this->lang->hyphen} " . $this->lang->ai->prompts->finalize . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Execute prompt on obejct, and redirect to target form page.
     *
     * @param  int    $promptId
     * @param  int    $objectId
     * @param  string $mode  Execution mode, 'testing' or 'normal'.
     * @access public
     * @return void
     */
    public function promptExecute(int $promptId, int $objectId, string $mode = 'testing')
    {
        $prompt = $this->ai->getPromptByID($promptId);
        if(empty($prompt)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noPrompt'])));

        $object = $this->ai->getObjectForPromptById($prompt, $objectId);
        if(empty($object)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noObjectData'])));

        list($objectData, $rawObject) = $object;

        list($location, $stop) = $this->ai->getTargetFormLocation($prompt, $rawObject);
        if(!empty($stop)) return header("location: $location", true, 302);

        /* Execute prompt and catch exceptions. */
        try
        {
            $response = $this->ai->executePrompt($prompt, $object);
        }
        catch (AIResponseException $e)
        {
            $output = array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $e->getMessage()));

            /* Audition shall quit on such exception. */
            if(isset($_SESSION['auditPrompt']) && time() - $_SESSION['auditPrompt']['time'] < 10 * 60)
            {
                $output['locate'] = $this->inlink('promptAudit', "promptID=$promptId&objectId=$objectId&exit=true");
            }
            return $this->send($output);
        }

        if(is_int($response)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->executeErrors["$response"]) . (empty($this->ai->errors) ? '' : implode(', ', $this->ai->errors))));
        if(empty($response))  return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noResponse'])));

        if(!empty($prompt->targetForm))
        {
            $targetFormPaths            = explode('.', $prompt->targetForm);
            $response['targetFormName'] = $this->lang->ai->targetForm[$targetFormPaths[0]][$targetFormPaths[1]];
            $response['dataPropNames']  = $this->lang->ai->dataSource[$prompt->module];
        }

        $response['objectID']     = $objectId;
        $response['objectType']   = $prompt->module;
        $response['object']       = $objectData;
        $response['formLocation'] = $location;
        $response['model']        = $prompt->model;

        return $this->send(array('result' => 'success', 'callback' => array('name' => 'parent.executeZentaoPrompt', 'params' => array($response, $mode === 'testing'))));
    }

    /**
     * Reset prompt execution.
     *
     * @param  bool   $failed  Whether the execution failed, triggers error message.
     * @access public
     * @return void
     */
    public function promptExecutionReset($failed = false)
    {
        /* Reset session. */
        unset($_SESSION['aiPrompt']);
        unset($_SESSION['auditPrompt']);

        if($failed) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noTargetForm']), 'locate' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));
        return $this->send(array('result' => 'success'));
    }

    /**
     * Audit prompt modal.
     *
     * @param  int      $promptId
     * @param  int      $objectId
     * @param  bool     $exit
     * @access public
     * @return void|int
     */
    public function promptAudit(int $promptId, int $objectId, bool $exit = false)
    {
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);

        if(!empty($exit))
        {
            unset($_SESSION['auditPrompt']);
            return $this->send(array('result' => 'success', 'load' => $this->inlink('promptview', "promptID=$promptId")));
        }

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

            if(empty($data->backLocation))
            {
                $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true);
            }
            else
            {
                $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => array('name' => 'getTestingLocation', 'params' => array($promptId)));
            }

            $this->sendSuccess($response);
        }

        $objectForPrompt = $this->ai->getObjectForPromptById($prompt, $objectId);
        if(empty($objectForPrompt)) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->execute->fail));

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
            return $this->send(array('result' => 'success', 'message' => $this->lang->ai->prompts->action->publishSuccess, 'load' => $this->inlink('promptview', "id=$id")));
        }

        return $this->send(array('result' => 'success', 'load' => true, 'message' => $this->lang->ai->prompts->action->publishSuccess));
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
        return $this->send(array('result' => 'success', 'message' => $this->lang->ai->prompts->action->unpublishSuccess, 'load' => true));
    }

    /**
     * Get testing location.
     *
     * @param  int    $promptID
     * @param  string $module
     * @param  string $targetForm
     * @access public
     * @return void
     */
    public function ajaxTestPrompt($promptID)
    {
        $prompt = $this->ai->getPromptByID($promptID);
        if(empty($prompt)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noPrompt'])));

        $object = $this->ai->getTestPromptData($prompt);
        list($objectData, $showText) = $object;

        /* Execute prompt and catch exceptions. */
        try
        {
            $response = $this->ai->executePrompt($prompt, $object);
        }
        catch (AIResponseException $e)
        {
            $output = array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $e->getMessage()));

            /* Audition shall quit on such exception. */
            if(isset($_SESSION['auditPrompt']) && time() - $_SESSION['auditPrompt']['time'] < 10 * 60)
            {
                $output['locate'] = $this->inlink('promptAudit', "promptID=$promptID&objectId=0&exit=true");
            }
            return $this->send($output);
        }

        if(is_int($response)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->executeErrors["$response"]) . (empty($this->ai->errors) ? '' : implode(', ', $this->ai->errors))));
        if(empty($response))  return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noResponse'])));

        if(!empty($prompt->targetForm) && $prompt->targetForm != 'empty')
        {
            $targetFormPaths            = explode('.', $prompt->targetForm);
            $response['targetFormName'] = $this->lang->ai->targetForm[$targetFormPaths[0]][$targetFormPaths[1]];
            $response['dataPropNames']  = $this->lang->ai->dataSource[$prompt->module];
        }

        $response['objectType']   = $prompt->module;
        $response['object']       = $objectData;
        $response['formLocation'] = '';
        $response['model']        = $prompt->model;
        $response['promptAudit']  = $this->ai->isClickable($prompt, 'promptaudit');
        $response['content']      = $showText;

        return $this->send(array('result' => 'success', 'data' => $response));
    }

    /**
     * Return html element for the role template list.
     * This is used in prompt designing step 2.
     * Return html will deserialize in the front end js and replace the original role template list.
     *
     * @access public
     * @return void
     */
    public function roleTemplates()
    {
        if($_POST)
        {
            $data   = fixer::input('post')->get();
            $method = $data->method;
            $result = false;
            $message = '';

            switch ($method)
            {
                case 'create':
                    $result = $this->ai->createRoleTemplate($data->role, $data->characterization);
                    $message = $result ? $this->lang->saveSuccess : $this->lang->ai->saveFail;
                    break;
                case 'delete':
                    $result = $this->ai->deleteRoleTemplate($data->id);
                    $message = $result ? $this->lang->ai->prompts->roleDelSuccess : $this->lang->ai->saveFail;
                    break;
                case 'edit':
                    $result = $this->ai->updateRoleTemplate($data->id, $data->role, $data->characterization);
                    $message = $result ? $this->lang->saveSuccess : $this->lang->ai->saveFail;
                    break;
            }

            $roleTemplates = $this->ai->getRoleTemplates();
            $roleTemplatesArray = array_values((array)$roleTemplates);

            return $this->send(array(
                'result' => $result ? 'success' : 'fail',
                'message' => $message,
                'data' => array(
                    'roleTemplates' => $roleTemplatesArray,
                    'method' => $method
                )
            ));
        }
    }
}
