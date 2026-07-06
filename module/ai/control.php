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
            $program->publishedLabel = $program->published == '1'
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
        $this->app->loadClass('pager', true);
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

        $knowledgeLibIDs = array();
        if(!empty($prompt->knowledgeLib)) $knowledgeLibIDs = array_filter(explode(',', trim($prompt->knowledgeLib, ',')));

        $knowledgeLibs = array();
        if(!empty($knowledgeLibIDs) && method_exists($this->ai, 'getKnowledgeLibsByIDs')) $knowledgeLibs = $this->ai->getKnowledgeLibsByIDs($knowledgeLibIDs);

        $skill = false;
        if(!empty($prompt->skill) && method_exists($this->ai, 'getSkillByID')) $skill = $this->ai->getSkillByID((int)$prompt->skill, false);

        $this->view->prompt        = $prompt;
        $this->view->preAndNext    = $this->loadModel('common')->getPreAndNextObject('prompt', $id);
        $this->view->actions       = $this->loadModel('action')->getList('prompt', $id);
        $this->view->dataPreview   = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->title         = "{$this->lang->aiapp->zentaoAgent}#{$prompt->id} " . htmlspecialchars($prompt->name);
        $this->view->fieldConfig   = $this->ai->getPromptFields($id);
        $this->view->knowledgeLibs = $knowledgeLibs;
        $this->view->skill         = $skill;

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
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $url));
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
     * Set basic info of prompt.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptBasicInfo(int $promptID = 0)
    {
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);

        $prompt = empty($promptID) ? new stdclass() : $this->ai->getPromptByID($promptID);
        if(empty($prompt)) $prompt = new stdclass();
        if(!empty($prompt->status) && $prompt->status == 'active') return $this->locate($this->inlink('promptView', "id={$prompt->id}"));

        if($_POST)
        {
            $data = form::data($this->config->ai->form->promptBasicInfo)->get();

            if(empty($prompt->id))
            {
                $promptID = $this->ai->createPrompt($data);
            }
            else
            {
                $originalPrompt = clone $prompt;

                $prompt->module          = $data->module;
                $prompt->actionPurpose   = $data->actionPurpose;
                $prompt->displayPosition = $data->displayPosition;
                $prompt->name            = $data->name;
                $prompt->model           = $data->model;
                $prompt->desc            = $data->desc;

                $this->ai->updatePrompt($prompt, $originalPrompt);
                $promptID = $prompt->id;
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $nextMethod = $data->displayPosition == 'form' ? 'promptSetInputForm' : 'promptSetInputFields';
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink($nextMethod, "promptID=$promptID")));
        }

        if(empty($prompt->id)) $prompt->id = 0;
        if(!isset($prompt->name)) $prompt->name = '';
        if(!isset($prompt->module)) $prompt->module = '';
        if(!isset($prompt->actionPurpose)) $prompt->actionPurpose = '';
        if(!isset($prompt->displayPosition)) $prompt->displayPosition = '';
        if(!isset($prompt->model)) $prompt->model = '';
        if(!isset($prompt->desc)) $prompt->desc = '';

        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = $this->lang->ai->promptBasicInfo;
        $this->display();
    }

    /**
     * Edit role of prompt, prompt editing step 2.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptAssignRole(int $promptID)
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
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} {$prompt->name} {$this->lang->hyphen} " . $this->lang->ai->prompts->assignRole . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
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
    public function promptSelectDataSource(int $promptID)
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

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetInputForm', "promptID=$promptID")));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID")));
        }

        $this->view->activeDataSource = empty($prompt->module) ? current(array_keys($this->config->ai->dataSource)) : $prompt->module;
        $this->view->dataSource       = $this->ai->getDataSource();
        $this->view->prompt           = $prompt;
        $this->view->promptID         = $promptID;
        $this->view->lastActiveStep   = $this->ai->getLastActiveStep($prompt);
        $this->view->title            = "{$this->lang->ai->prompts->common}#{$prompt->id} {$prompt->name} {$this->lang->hyphen} " . $this->lang->ai->prompts->selectDataSource . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Set input fields of prompt.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptSetInputFields(int $promptID = 0)
    {
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        if(empty($promptID)) return $this->locate($this->inlink('promptBasicInfo'));

        $prompt = $this->ai->getPromptByID($promptID);
        if(empty($prompt)) return $this->locate($this->inlink('promptBasicInfo'));
        if($prompt->displayPosition == 'form') return $this->locate($this->inlink('promptSetInputForm', "promptID=$promptID"));

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;
            $prompt->source = ",$data->datasource,";

            $this->ai->updatePrompt($prompt, $originalPrompt);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetInputForm', "promptID=$promptID")));
        }

        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->dataSource     = $this->ai->getDataSource();
        $this->view->currentFields  = $this->ai->getPromptFields($promptID);
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} {$prompt->name} {$this->lang->hyphen} " . $this->lang->ai->designStepNav['setinputfields'] . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Edit purpose and purpose elaboration of prompt, prompt editing step 4.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptSetPurpose(int $promptID)
    {
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        $prompt = $this->ai->getPromptByID($promptID);

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $error = '';
            $data  = $this->aiZen->getPostData($error);
            if($error) return $this->send(array('result' => 'fail', 'message' => $error));
            if(!is_object($data)) $data = new stdClass();

            $originalPrompt = clone $prompt;

            $prompt->purpose      = isset($data->purpose) ? $data->purpose : '';
            $prompt->elaboration  = '';
            $prompt->role         = isset($data->role) ? $data->role : '';
            $prompt->knowledgeLib = $data->knowledgeLib ?? '';
            $prompt->skill        = !empty($data->skill) ? (int)$data->skill : 0;

            if(isset($data->fields))
            {
                $fields = is_array($data->fields) ? $data->fields : array();
                $this->ai->savePromptFields($promptID, $fields);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID")));
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
        $this->view->skill          = $this->ai->getSkillByID((int)$prompt->skill, false);
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} {$prompt->name} {$this->lang->hyphen} " . $this->lang->ai->prompts->setPurpose . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Set input form of prompt.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptSetInputForm(int $promptID = 0)
    {
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        if(empty($promptID)) return $this->locate($this->inlink('promptBasicInfo'));

        $prompt = $this->ai->getPromptByID($promptID);

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $error = '';
            $data  = $this->aiZen->getPostData($error);
            if($error) return $this->send(array('result' => 'fail', 'message' => $error));
            if(!is_object($data)) $data = new stdClass();

            $fields = isset($data->fields) && is_array($data->fields) ? $data->fields : array();
            $this->ai->savePromptFields($promptID, $fields);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID")));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetInputForm', "promptID=$promptID")));
        }

        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->currentFields  = $this->ai->getPromptFields($promptID);
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} {$prompt->name} {$this->lang->hyphen} " . $this->lang->ai->designStepNav['setinputform'] . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Set target form of prompt, prompt editing step 5.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptSetTargetForm(int $promptID)
    {
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->targetForm    = $data->targetForm;
            $prompt->actionPurpose = $data->targetForm;

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->goTesting)) // Go to testing object view.
            {
                $location = $this->ai->getTestingLocation($prompt);
                return $this->send(empty($location) ? array('result' => 'fail', 'target' => '#go-test-btn', 'message' => $this->lang->ai->prompts->goingTestingFail) : array('result' => 'success', 'target' => '#go-test-btn', 'message' => $this->lang->ai->prompts->goingTesting, 'locate' => $location));
            }

            if(!empty($data->jumpToNext)) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID")));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID")));
        }

        $this->view->dataPreview    = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} {$prompt->name} {$this->lang->hyphen} " . $this->lang->ai->prompts->setTargetForm . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Edit additional information of prompt, final prompt editing step.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptFinalize(int $promptID)
    {
        if(!common::hasPriv('ai', 'designPrompt')) $this->loadModel('common')->deny('ai', 'designPrompt', false);
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            if(!empty($data->goTesting))
            {
                $location = $this->ai->getTestingLocation($prompt);
                return $this->send(empty($location) ? array('result' => 'fail', 'target' => '#go-test-btn', 'message' => $this->lang->ai->prompts->goingTestingFail) : array('result' => 'success', 'target' => '#go-test-btn', 'message' => $this->lang->ai->prompts->goingTesting, 'locate' => $location));
            }

            $publish = !empty($data->publish);
            if($publish) $this->ai->togglePromptStatus($prompt, 'active');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($publish) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('prompts')));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID")));
        }

        $currentPrompt = $prompt->purpose;
        if(!empty($prompt->elaboration)) $currentPrompt .= "\n\n" . $prompt->elaboration;

        $this->view->dataPreview    = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->currentPrompt  = $currentPrompt;
        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
        $this->view->lastActiveStep = $this->ai->getLastActiveStep($prompt);
        $this->view->title          = "{$this->lang->ai->prompts->common}#{$prompt->id} {$prompt->name} {$this->lang->hyphen} " . $this->lang->ai->prompts->finalize . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
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

        if(!$this->loadModel('zai')->canViewObject($prompt->module, $objectId)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->error->accessDenied)));

        $object = $this->ai->getObjectForPromptById($prompt, $objectId);
        if(empty($object)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noObjectData'])));

        list($objectData, $rawObject) = $object;

        list($location, $stop) = $this->ai->getTargetFormLocation($prompt, $rawObject);
        if(!empty($stop))
        {
            header("location: $location", true, 302);
            return;
        }

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

        if(!empty($prompt->actionPurpose) && $prompt->actionPurpose != 'empty.empty') $response = array_merge($response, $this->buildPromptFormMeta($prompt, (array)$this->lang->ai->moduleList[$prompt->module], $prompt->actionPurpose));

        $fields = array_values($this->ai->getPromptFields($promptId));
        if($fields)
        {
            $response['fields']     = $fields;
            $response['formConfig'] = [];
            $response['formConfig']['title']         = $this->lang->ai->prompts->formDefaultTitle;
            $response['formConfig']['submitBtnText'] = $this->lang->ai->prompts->formSubmitBtnText;
        }

        $response['objectID']     = $objectId;
        $response['objectType']   = $prompt->module;
        $response['knowledgeLib'] = $prompt->knowledgeLib;
        $response['object']       = $objectData;
        $response['formLocation'] = $location;
        $response['model']        = $prompt->model;
        $this->appendPromptSkillToResponse($response, $prompt);

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
            $prompt->elaboration      = '';

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

        $currentPrompt = $prompt->purpose;
        if(!empty($prompt->elaboration)) $currentPrompt .= "\n\n" . $prompt->elaboration;

        $this->view->prompt        = $prompt;
        $this->view->currentPrompt = $currentPrompt;
        $this->view->object        = $object;
        $this->view->dataPrompt    = $this->ai->serializeDataToPrompt($prompt->module, $prompt->source, $objectData);

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

        if(!empty($prompt->actionPurpose) && $prompt->actionPurpose != 'empty.empty') $response = array_merge($response, $this->buildPromptFormMeta($prompt, (array)$this->lang->ai->moduleList[$prompt->module], $prompt->actionPurpose));

        $fields = array_values($this->ai->getPromptFields($promptID));
        if($fields)
        {
            $response['fields']     = $fields;
            $response['formConfig'] = array();
            $response['formConfig']['title']         = $this->lang->ai->prompts->formDefaultTitle;
            $response['formConfig']['submitBtnText'] = $this->lang->ai->prompts->formSubmitBtnText;
        }

        $response['objectType']   = $prompt->module;
        $response['object']       = $objectData;
        $response['formLocation'] = '';
        $response['model']        = $prompt->model;
        $response['promptAudit']  = $this->ai->isClickable($prompt, 'promptaudit');
        $response['content']      = $showText;
        $this->appendPromptSkillToResponse($response, $prompt);

        return $this->send(array('result' => 'success', 'data' => $response));
    }

    /**
     * 执行通用表单提示词
     * Execute universal form prompt for target form pages.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function executeUniversalPrompt($promptID)
    {
        $prompt = $this->ai->getPromptByID($promptID);
        if(empty($prompt)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noPrompt'])));

        if(!common::hasPriv('ai', 'promptExecute')) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));

        $formSchema = json_decode($_POST['formSchema'] ?? '{}', true);
        if(empty($formSchema)) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->execute->failReasons['noFormSchema']));

        if(empty($prompt->displayPosition) || $prompt->displayPosition !== 'form' || empty($prompt->actionPurpose)) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->execute->failReasons['noFormSchema']));

        $targetForm = $prompt->actionPurpose;

        $targetFormParts = explode('.', $targetForm, 2);
        if(count($targetFormParts) !== 2) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->execute->failReasons['noFormSchema']));

        $contextFields  = $this->config->ai->formContextFields[$targetFormParts[0]][$targetFormParts[1]] ?? $this->config->ai->formContextFields['_default'] ?? array();
        $contextObjects = $this->ai->loadFormContextObjects($formSchema, $contextFields, $this->config->ai->contextRelations ?? array());
        $contextDesc    = $this->ai->buildContextDescription($contextObjects);
        $allowedFields  = $this->config->ai->universalFormFields[$targetFormParts[0]][$targetFormParts[1]] ?? array();
        $filteredFields = $this->ai->filterAllowedFields($formSchema['fields'] ?? array(), $allowedFields);

        $isBatchForm = strpos($targetFormParts[1], 'batch') === 0;
        $promptLang  = $this->lang->ai->prompts;
        if($isBatchForm)
        {
            $headers   = array();
            $values    = array();
            $fieldDefs = array();
            foreach($formSchema['fields'] as $name => $field)
            {
                $label    = $field['label'] ?? $name;
                $input    = $field['controlType'] ?? 'input';
                $value    = $field['currentValue'] ?? '';
                $required = !empty($field['required']) ? 'true' : 'false';

                $headers[] = $label;

                $displayValue = $value;
                if($value !== '' && !empty($field['options']) && is_array($field['options']))
                {
                    foreach($field['options'] as $opt)
                    {
                        if((string)($opt['value'] ?? '') === (string)$value)
                        {
                            $displayValue = $opt['text'] ?? $value;
                            break;
                        }
                    }
                }
                $values[] = $displayValue;

                $def = "- {$name}({$label}): {$input}" . ($required === 'true' ? " {$promptLang->requiredField}" : '');
                if(!empty($field['options']) && is_array($field['options']))
                {
                    $optStrs = array();
                    foreach($field['options'] as $opt)
                    {
                        $optVal    = $opt['value'] ?? '';
                        $optText   = $opt['text'] ?? '';
                        $optStrs[] = $optText ? "{$optVal}({$optText})" : $optVal;
                    }
                    $def .= "{$promptLang->fieldSeparator}{$promptLang->optionsLabel}" . implode(', ', $optStrs);
                }
                $fieldDefs[] = $def;
            }

            $sepLine     = '| ' . implode(' | ', array_fill(0, count($headers), '---')) . ' |';
            $fullPrompt  = "{$promptLang->pageContext}\n{$contextDesc}\n\n";
            $fullPrompt .= "{$promptLang->batchFormData}\n\n";
            $fullPrompt .= '| ' . implode(' | ', $headers) . " |\n";
            $fullPrompt .= $sepLine . "\n";
            $fullPrompt .= '| ' . implode(' | ', $values) . " |\n\n";
            $fullPrompt .= "{$promptLang->fieldDefinition}\n" . implode("\n", $fieldDefs) . "\n\n";
            $fullPrompt .= "{$promptLang->targetFormInfo}\n";
            $fullPrompt .= sprintf($promptLang->formLabel, $prompt->name ?? $targetForm) . "\n\n";
            if(!empty($filteredFields))
            {
                $fullPrompt .= "{$promptLang->fillableFields}\n";
                foreach($filteredFields as $fName => $fField) $fullPrompt .= "- {$fName}\n";
            }
            $fullPrompt .= "\n{$promptLang->returnJSONArray}\n";
        }
        else
        {
            $formDataLines = array();
            if(!empty($formSchema['fields']))
            {
                foreach($formSchema['fields'] as $name => $field)
                {
                    $label    = $field['label'] ?? $name;
                    $input    = $field['controlType'] ?? 'input';
                    $valType  = $field['valueType'] ?? 'string';
                    $value    = $field['currentValue'] ?? '';
                    $required = !empty($field['required']) ? 'true' : 'false';
                    $line     = "- {$label}\n  name: {$name}\n  input: {$input}\n  type: {$valType}\n  required: {$required}\n  value: {$value}";

                    if(!empty($field['options']) && is_array($field['options']))
                    {
                        $optStrs = array();
                        foreach($field['options'] as $opt)
                        {
                            $optVal    = $opt['value'] ?? '';
                            $optText   = $opt['text'] ?? '';
                            $optStrs[] = $optText ? "{$optVal}({$optText})" : $optVal;
                        }
                        $line .= "\n  options: " . implode(', ', $optStrs);
                    }

                    $formDataLines[] = $line;
                }
            }

            $fillableDesc = $this->ai->getFormSchemaDescription($prompt, $filteredFields);

            $fullPrompt  = "{$promptLang->pageContext}\n{$contextDesc}\n\n";
            $fullPrompt .= "{$promptLang->currentFormData}\n" . implode("\n", $formDataLines) . "\n\n";
            $fullPrompt .= $fillableDesc;
        }

        $schema       = $this->ai->buildDynamicSchema($filteredFields, $prompt, $isBatchForm);
        $location     = $_POST['pageUrl'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        $formMetaData = $this->buildPromptFormMeta($prompt, $filteredFields, $targetForm);

        $originObject = new stdclass();
        foreach($filteredFields as $name => $field)
        {
            if(isset($field['currentValue']) && $field['currentValue'] !== '') $originObject->$name = $field['currentValue'];
        }

        $extraFields = array_values($this->ai->getPromptFields($promptID));
        $formConfig  = array();
        if($extraFields)
        {
            $formConfig['title']         = $this->lang->ai->prompts->formDefaultTitle;
            $formConfig['submitBtnText'] = $this->lang->ai->prompts->formSubmitBtnText;
        }

        $callbackData = array(
            'role'           => $prompt->role . (!empty($prompt->characterization) ? "\n{$prompt->characterization}" : ''),
            'schema'         => $schema,
            'dataPrompt'     => $fullPrompt,
            'name'           => $prompt->name,
            'purpose'        => $prompt->purpose,
            'targetForm'     => $targetForm,
            'promptID'       => $prompt->id,
            'formLocation'   => $location,
            'objectID'       => 0,
            'objectType'     => $prompt->module,
            'object'         => array($prompt->module => $originObject),
            'model'          => $prompt->model,
            'targetFormName' => $formMetaData['targetFormName'],
            'dataPropNames'  => $formMetaData['dataPropNames'],
            'knowledgeLib'   => $prompt->knowledgeLib ?? '',
            'fields'         => $extraFields,
            'formConfig'     => $formConfig,
        );
        $this->appendPromptSkillToResponse($callbackData, $prompt);

        return $this->send(array('result' => 'success', 'callback' => array('name' => 'parent.executeZentaoPrompt', 'params' => array($callbackData, false))));
    }

    /**
     * Append mounted skill info for AI chat.
     *
     * @param  array  $response
     * @param  object $prompt
     * @access private
     * @return void
     */
    private function appendPromptSkillToResponse(array &$response, object $prompt): void
    {
        $response['skill']     = $prompt->skill ?? 0;
        $response['skillID']   = '';
        $response['skillName'] = '';

        if(empty($prompt->skill)) return;

        $skill = $this->ai->getSkillByID((int)$prompt->skill, false);
        if(!$skill || empty($skill->skillID)) return;

        $response['skillID']   = $skill->skillID;
        $response['skillName'] = $skill->name;
    }

    /**
     * Build prompt target form metadata.
     *
     * @param  object $prompt
     * @param  array  $fields
     * @param  string $targetForm
     * @access private
     * @return array
     */
    private function buildPromptFormMeta(object $prompt, array $fields, string $targetForm): array
    {
        $targetFormPaths = explode('.', $targetForm, 2);
        $targetFormName  = count($targetFormPaths) === 2 ? ($this->lang->ai->targetForm[$targetFormPaths[0]][$targetFormPaths[1]] ?? '') : '';

        $dataPropNames = new stdclass();
        $dataPropNames->{$prompt->module} = new stdclass();
        $dataPropNames->{$prompt->module}->common = $prompt->name ?: $targetForm;
        foreach($fields as $name => $field) $dataPropNames->{$prompt->module}->{$name} = is_array($field) ? ($field['label'] ?? $name) : $field;

        return array('targetFormName' => $targetFormName, 'dataPropNames' => $dataPropNames);
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
                default:
                    $message = $this->lang->ai->saveFail;
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
