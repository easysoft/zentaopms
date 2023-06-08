<?php
/**
 * The control file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        http://www.zentao.net
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
    public function prompts($module = '', $status = '')
    {
        $this->view->module     = $module;
        $this->view->status     = $status;
        $this->view->prompts    = $this->ai->getPrompts($module, $status);
        $this->view->title      = $this->lang->ai->prompts->common;
        $this->view->position[] = $this->lang->ai->prompts->common;
        $this->display();
    }
}