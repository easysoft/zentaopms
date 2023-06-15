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
     * @param  int    $prompt
     * @access public
     * @return void
     */
    public function promptAssignRole($prompt)
    {
        $this->view->title      = $this->lang->ai->prompts->assignRole . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Set data source of prompt, prompt editing step 3.
     *
     * @param  int    $prompt
     * @access public
     * @return void
     */
    public function promptSelectDataSource($prompt)
    {
        $this->view->title      = $this->lang->ai->prompts->selectDataSource . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Edit purpose and purpose elaboration of prompt, prompt editing step 4.
     *
     * @param  int    $prompt
     * @access public
     * @return void
     */
    public function promptSetPurpose($prompt)
    {
        $this->view->title      = $this->lang->ai->prompts->setPurpose . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Set target form of prompt, prompt editing step 5.
     *
     * @param  int    $prompt
     * @access public
     * @return void
     */
    public function promptSetTargetForm($prompt)
    {
        $this->view->title      = $this->lang->ai->prompts->setTargetForm . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }

    /**
     * Edit additional information of prompt, final prompt editing step.
     *
     * @param  int    $prompt
     * @access public
     * @return void
     */
    public function promptFinalize($prompt)
    {
        $this->view->title      = $this->lang->ai->prompts->finalize . " {$this->lang->colon} " . $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }
}
