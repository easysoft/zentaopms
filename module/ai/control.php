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
     * List models.
     *
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function models($orderBy = 'id_asc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Adapt order. */
        $actualOrdering = $orderBy;
        if(strpos(strtolower($actualOrdering), 'usesproxy') !== false) $actualOrdering = str_ireplace('usesproxy', 'proxy', $actualOrdering);

        $models = $this->ai->getLanguageModels('', false, $pager, $actualOrdering);

        /* Process model props to displayable format. */
        foreach($models as $model)
        {
            $model->usesProxy = !empty($model->proxy) ? 'on' : 'off';
            $model->vendor    = $this->lang->ai->models->vendorList->{$model->type}[$model->vendor];

            if(empty($model->name)) $model->name = $this->lang->ai->models->typeList[$model->type];
        }

        $this->view->models  = $models;
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;
        $this->view->title   = $this->lang->ai->models->title;
        $this->display();
    }

    /**
     * View language model configuration.
     *
     * @param  int     $modelID
     * @access public
     * @return void
     */
    public function modelView($modelID)
    {
        $model = $this->ai->getLanguageModel($modelID);
        if(empty($model)) return $this->locate($this->createLink('ai', 'models'));

        $this->view->model = $this->ai->unserializeModel($model);
        $this->view->title = $this->lang->ai->models->view;
        $this->display();
    }

    /**
     * Create language model configuration.
     *
     * @access public
     * @return void
     */
    public function modelCreate()
    {
        if(strtolower($this->server->request_method) == 'post')
        {
            $modelConfig = fixer::input('post')->get();

            /* Check for required credentials. */
            $credentials = array();
            if(!empty($this->config->ai->vendorList[$modelConfig->vendor]['credentials'])) $credentials = $this->config->ai->vendorList[$modelConfig->vendor]['credentials'];
            foreach($credentials as $credKey)
            {
                if(empty($modelConfig->$credKey)) dao::$errors[$credKey][] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->$credKey);
                if(!empty($modelConfig->proxyType) && empty($modelConfig->proxyAddr)) dao::$errors['proxyAddr'][] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->proxyAddr);
            }
            if(!empty(dao::$errors)) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $result = $this->ai->createModel($modelConfig);

            if($result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('modelView', "modelID=$result") . '#app=admin'));
        }

        $this->view->title = $this->lang->ai->models->create;
        $this->display();
    }

    /**
     * Edit language model configuration.
     *
     * @param  int     $modelID
     * @access public
     * @return void
     */
    public function modelEdit($modelID)
    {
        if(strtolower($this->server->request_method) == 'post')
        {
            $modelConfig = fixer::input('post')->get();

            /* Check for required credentials. */
            $credentials = array();
            if(!empty($this->config->ai->vendorList[$modelConfig->vendor]['credentials'])) $credentials = $this->config->ai->vendorList[$modelConfig->vendor]['credentials'];
            foreach($credentials as $credKey)
            {
                if(empty($modelConfig->$credKey)) dao::$errors[$credKey][] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->$credKey);
                if(!empty($modelConfig->proxyType) && empty($modelConfig->proxyAddr)) dao::$errors['proxyAddr'][] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->proxyAddr);
            }
            if(!empty(dao::$errors)) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $result = $this->ai->updateModel($modelID, $modelConfig);

            if($result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('modelView', "modelID=$modelID") . '#app=admin'));
        }

        $model = $this->ai->getLanguageModel($modelID);
        if(empty($model)) return $this->locate($this->createLink('ai', 'models'));

        $modelData = $this->ai->unserializeModel($model);
        if(empty($model->name)) $modelData->name = ''; // Set name to empty if it was not set.

        $this->view->model = $modelData;
        $this->view->title = $this->lang->ai->models->edit;
        $this->display();
    }

    /**
     * Enable language model.
     *
     * @param  int     $modelID
     * @access public
     * @return void
     */
    public function modelEnable($modelID)
    {
        $result = $this->ai->toggleModel($modelID, true);
        if($result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->sendSuccess(array('load' => true, 'message' => ''));
    }

    /**
     * Disable language model.
     *
     * @param  int     $modelID
     * @access public
     * @return void
     */
    public function modelDisable($modelID)
    {
        $result = $this->ai->toggleModel($modelID, false);
        if($result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->sendSuccess(array('load' => true, 'message' => ''));
    }

    /**
     * Delete language model.
     *
     * @param  int     $modelID
     * @access public
     * @return void
     */
    public function modelDelete($modelID)
    {
        $result = $this->ai->deleteModel($modelID);
        if($result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'load' => array('locate' => inlink('models'))));
    }

    /**
     * Test connection with model configuration.
     *
     * @param  int     $modelID
     * @access public
     * @return void
     */
    public function modelTestConnection($modelID = 0)
    {
        $result = false;

        if(strtolower($this->server->request_method) == 'post')
        {
            /* Test model connection from form if method is POST. */
            $modelConfig = fixer::input('post')->get();

            $currentVendor = empty($modelConfig->vendor) ? key($this->lang->ai->models->vendorList->{empty($modelConfig->type) ? key($this->lang->ai->models->typeList) : $modelConfig->type}) : $modelConfig->vendor;
            $vendorRequiredFields = $this->config->ai->vendorList[$currentVendor]['credentials'];

            $errors = array();
            if(empty($modelConfig->type)) $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->type);
            foreach($vendorRequiredFields as $field)
            {
                if(empty($modelConfig->$field)) $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->$field);
            }
            if(!empty($modelConfig->proxyType) && empty($modelConfig->proxyAddr))
            {
                $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->proxyAddr);
            }
            if(!empty($errors)) return $this->send(array('result' => 'fail', 'message' => implode(' ', $errors)));

            $this->ai->setModelConfig($modelConfig);

            if($this->config->ai->models[$modelConfig->type] == 'ernie' || $currentVendor == 'azure' || $modelConfig->type == 'openai-gpt4' || $modelConfig->vendor == 'openaiCompatible')
            {
                $messages = array((object)array('role' => 'user', 'content' => 'test'));
                $result = $this->ai->converse(null, $messages, array('maxTokens' => 1));
            }
            else
            {
                $result = $this->ai->complete(null, 'test', 1); // Test completing 'test' with length of 1.
            }
        }
        else
        {
            /* Test model with id if not POST. */
            $result = $this->ai->testModelConnection($modelID);
        }

        if($result === false)
        {
            return $this->send(array('result' => 'fail', 'message' => empty($this->ai->errors) ? $this->lang->ai->models->testConnectionResult->fail : sprintf($this->lang->ai->models->testConnectionResult->failFormat, implode(', ', $this->ai->errors))));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->ai->models->testConnectionResult->success));
    }

    /**
     * List mini programs.
     *
     * @access public
     * @return void
     */
    public function miniPrograms($category = '', $status = '', $orderBy = 'createdDate_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
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

        $this->view->title        = $this->lang->ai->miniPrograms->common;
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
        if(!empty($_POST))
        {
            if($this->ai->checkDuplicatedCategory()) return $this->sendError($this->lang->ai->maintenanceGroupDuplicated);
            $this->ai->updateCustomCategories();
            return $this->sendSuccess(array());
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
        if($result) return $this->send(array('result' => 'success', 'locate' => $this->server->http_referer));
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
        if($result) return $this->send(array('result' => 'success', 'locate' => $this->server->http_referer));
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

        if(!empty($_POST))
        {
            $errors = $this->ai->verifyRequiredFields(array('category' => $this->lang->ai->miniPrograms->category, 'published' => $this->lang->ai->toPublish));
            if($errors !== false) return $this->sendError($errors);

            $file = $_FILES['file'];
            $filePath = $file['tmp_name'];
            $result = $this->ai->extractZtAppZip($filePath);
            if(is_array($result))
            {
                $info = $result[0];
                $fileName = $info['filename'];
                include_once($fileName);
                if(isset($ztApp))
                {
                    $ztApp = json_decode($ztApp);
                    $ztApp->name      = $this->ai->getUniqueAppName($ztApp->name);
                    $ztApp->published = $_POST['published'];
                    $ztApp->category  = $_POST['category'];
                    $this->ai->createMiniProgram($ztApp);
                    unlink($fileName);
                    return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'locate' => 'reload'));
                }
                unlink($fileName);
            }

            return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->saveFail, 'locate' => $this->createLink('ai', 'miniprograms')));
        }
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
        $this->loadModel('user');
        $users = $this->user->getPairs('noletter');

        if($_POST)
        {
            $data = fixer::input('post')->get();
            if(isset($data->togglePromptStatus) && isset($data->promptId))
            {
                if(!$this->ai->hasModelsAvailable()) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->models->noModelError, 'locate' => $this->inlink('models') . '#app=admin'));

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
        $this->view->users      = $users;

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

        $model = empty($prompt->model) ? null : $this->ai->getLanguageModel($prompt->model);

        $this->view->prompt      = $prompt;
        $this->view->modelName   = empty($model) ? $this->lang->ai->models->default : (empty($model->name) ? $this->lang->ai->models->typeList[$model->type] : $model->name);
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
        return $this->send(array('result' => 'success'));
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
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->model            = $data->model == 'default' ? 0 : $data->model;
            $prompt->role             = $data->role;
            $prompt->characterization = $data->characterization;

            if(!empty($data->saveTemplate) && $data->saveTemplate == 'save')
            {
                $this->ai->createRoleTemplate($prompt->role, $prompt->characterization);
            }

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID") . '#app=admin'));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptAssignRole', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->models         = $this->ai->getLanguageModelNamesWithDefault();
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
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->module = $data->datagroup;
            $prompt->source = ",$data->datasource,";

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID") . '#app=admin'));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID") . '#app=admin'));
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
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->purpose     = $data->purpose;
            $prompt->elaboration = $data->elaboration;

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID") . '#app=admin'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID") . '#app=admin'));
        }

        $this->view->dataPreview    = $this->ai->generateDemoDataPrompt($prompt->module, $prompt->source);
        $this->view->prompt         = $prompt;
        $this->view->promptID       = $promptID;
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

            if(!empty($data->jumpToNext)) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID") . '#app=admin'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetTargetForm', "promptID=$promptID") . '#app=admin'));
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

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('prompts') . '#app=admin'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptFinalize', "promptID=$promptID") . '#app=admin'));
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
     * @access public
     * @return void
     */
    public function promptExecute($promptId, $objectId)
    {
        if(!$this->ai->hasModelsAvailable()) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->models->noModelError, 'locate' => $this->inlink('models') . '#app=admin'));

        $prompt = $this->ai->getPromptByID($promptId);
        if(empty($prompt) || !$this->ai->isExecutable($prompt)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noPrompt'])));

        $object = $this->ai->getObjectForPromptById($prompt, $objectId);
        if(empty($object)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noObjectData'])));

        list($objectData, $rawObject) = $object;

        list($location, $stop) = $this->ai->getTargetFormLocation($prompt, $rawObject);
        if(empty($location)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noTargetForm'])));
        if(!empty($stop))    return header("location: $location", true, 302);

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

        $this->ai->setInjectData($prompt->targetForm, $response);

        $_SESSION['aiPrompt']['prompt']   = $prompt;
        $_SESSION['aiPrompt']['objectId'] = $objectId;

        if($prompt->status == 'draft') $_SESSION['auditPrompt']['time'] = time();

        $this->view->formLocation   = $location;
        $this->view->promptViewLink = $this->inlink('promptview', "promptID=$promptId");
        $this->display();
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
    public function promptAudit($promptId, $objectId, $exit = false)
    {
        if(!empty($exit))
        {
            unset($_SESSION['auditPrompt']);
            return $this->send(array('result' => 'success', 'locate' => $this->inlink('promptview', "promptID=$promptId") . '#app=admin'));
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
                $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "reloadPrompt($promptId, $objectId)");
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
        if(!$this->ai->hasModelsAvailable()) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->models->noModelError, 'load' => $this->inlink('models') . '#app=admin'));

        unset($_SESSION['auditPrompt']);
        $this->ai->togglePromptStatus($id, 'active');

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if($backToTestingLocation)
        {
            return $this->send(array('result' => 'success', 'message' => $this->lang->ai->prompts->action->publishSuccess, 'load' => $this->inlink('promptview', "id=$id") . '#app=admin'));
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
        if(!$this->ai->hasModelsAvailable()) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->models->noModelError, 'load' => $this->inlink('models') . '#app=admin'));
        $this->ai->togglePromptStatus($id, 'draft');

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success'));
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
            switch ($method)
            {
                case 'create':
                    $this->ai->createRoleTemplate($data->role, $data->characterization);
                    break;
                case 'delete':
                    $this->ai->deleteRoleTemplate($data->id);
                    break;
                case 'edit':
                    $this->ai->updateRoleTemplate($data->id, $data->role, $data->characterization);
                    break;
            }
        }

        $this->view->roleTemplates = $this->ai->getRoleTemplates();
        $this->display();
    }

    /**
     * Chat with LLMs.
     *
     * @access public
     * @return void
     */
    public function chat()
    {
        $messages = array();

        if(!empty($_POST))
        {
            $history = $this->post->history;
            $message = $this->post->message;
            $isRetry = $this->post->retry == 'true';

            $messages = json_decode($history);
            if(empty($messages)) $messages[] = (object)array('role' => 'system', 'content' => $this->lang->ai->chatSystemMessage);

            if(!$isRetry) $messages[] = (object)array('role' => 'user', 'content' => $message);

            if($this->ai->hasModelsAvailable())
            {
                $response = $this->ai->converse(null, $messages);
                if(empty($response))
                {
                    $this->view->error = $this->lang->ai->chatNoResponse;
                }
                else
                {
                    $messages[] = (object)array('role' => 'assistant', 'content' => is_array($response) ? current($response) : $response);
                }
            }
            else
            {
                $this->view->error = $this->lang->ai->models->noModelError;
            }
        }

        $this->view->title    = $this->lang->ai->chat;
        $this->view->messages = $messages;
        $this->display();
    }
}
