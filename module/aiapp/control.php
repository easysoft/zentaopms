<?php

/**
 * The control file of aiapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
class aiapp extends control
{
    /**
     * aiapp model.
     *
     * @var aiappModel
     * @access public
     */
    public $aiapp;

    /**
     * ai model.
     *
     * @var aiModel
     * @access public
     */
    public $ai;

    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('ai');
    }

    public function view($id)
    {
        echo $this->fetch('aiapp', 'browseMiniProgram', "id=$id");
    }

    /**
     * Browse mini program by id from square.
     *
     * @param string $id
     * @access public
     * @return void
     */
    public function browseMiniProgram($id)
    {
        $miniProgram = $this->ai->getMiniProgramByID($id);
        if(empty($miniProgram)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->aiapp->noMiniProgram, 'locate' => $this->createLink('aiapp', 'square'))));

        $this->view->miniProgram  = $miniProgram;
        $this->view->messages     = $this->aiapp->getHistoryMessages($id);
        $this->view->fields       = $this->ai->getMiniProgramFields($id);
        $this->view->collectedIDs = $this->aiapp->getCollectedMiniProgramIDs($this->app->user->id);
        $this->view->title        = "{$this->lang->aiapp->title}#{$miniProgram->id} $miniProgram->name";
        $this->display();
    }

    /**
     * Mini program chat.
     *
     * @param string $id
     * @access public
     * @return void
     */
    public function miniProgramChat($id)
    {
        if(empty($_POST)) return $this->locate($this->createLink('ai', 'browseMiniProgram', "id=$id"));

        $miniProgram  = $this->ai->getMiniProgramByID($id);
        if(empty($miniProgram)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->aiapp->noMiniProgram, 'locate' => $this->createLink('aiapp', 'square'))));
        if($miniProgram->published == '0' && $this->post->test !== '1') return $this->send(array('result' => 'fail', 'message' => $this->lang->aiapp->unpublishedTip, 'reason' => 'unpublished'));

        $history = $this->post->history;
        $message = $this->post->message;
        $isRetry = $this->post->retry == 'true';

        $messages = json_decode($history);
        if(!$isRetry)
        {
            $messages[] = (object)array('role' => 'user', 'content' => $message);
            if($this->post->test !== '1') $this->aiapp->saveMiniProgramMessage($id, 'req', $message);
        }
        if($this->ai->hasModelsAvailable())
        {
            if(!empty($miniProgram->model))
            {
                /* Check if model required by miniProgram is available, fallback to default (first enabled) model if not. */
                $model = $this->ai->getLanguageModel($miniProgram->model);
                if(empty($model) || !$model->enabled)
                {
                    $defaultModel = $this->getDefaultLanguageModel();
                    if(empty($defaultModel)) return $this->send(array('result' => 'fail', 'message' => $this->lang->aiapp->noModelError, 'reason' => 'no model'));

                    $miniProgram->model = $defaultModel->id;
                }
            }

            $response = $this->ai->converse($miniProgram->model, $messages);
            if(empty($response)) return $this->send(array('result' => 'fail', 'message' => $this->lang->aiapp->chatNoResponse, 'reason' => 'no response'));

            if($this->post->test !== '1') $this->aiapp->saveMiniProgramMessage($id, 'res', is_array($response) ? current($response) : $response);
            return $this->send(array('result' => 'success', 'message' => array('time' => date("Y/n/j G:i"), 'role' => 'assistant', 'content' => is_array($response) ? current($response) : $response)));
        }

        return $this->send(array('result' => 'fail', 'message' => $this->lang->aiapp->noModelError, 'reason' => 'no model'));
    }

    /**
     * Mini program square.
     *
     * @param string $category
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function square($category = '', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $collectedIDs = null;

        if($category === '')
        {
            $collectedIDs = $this->aiapp->getCollectedMiniProgramIDs($this->app->user->id);
            $category = empty($collectedIDs) ? 'discovery' : 'collection';
        }

        if($category === 'collection') $collectedIDs = $this->aiapp->getCollectedMiniProgramIDs($this->app->user->id, $pager);
        elseif($collectedIDs === null) $collectedIDs = $this->aiapp->getCollectedMiniProgramIDs($this->app->user->id);

        if($category === 'collection')     $miniPrograms = $this->ai->getMiniProgramsByID($collectedIDs, true);
        else if($category === 'discovery') $miniPrograms = $this->ai->getMiniPrograms('', 'active', 'createdDate_desc', $pager);
        else if($category === 'latest')    $miniPrograms = $this->aiapp->getLatestMiniPrograms($pager);
        else                               $miniPrograms = $this->ai->getMiniPrograms($category, 'active', 'createdDate_desc', $pager);

        $squareCategoryArray = $this->aiapp->getSquareCategoryArray();
        $usedCategoryArray   = $this->aiapp->getUsedCategoryArray();

        $this->view->collectedIDs = $collectedIDs;
        $this->view->category     = $category;
        $this->view->categoryList = array_merge($squareCategoryArray, $usedCategoryArray);
        $this->view->pager        = $pager;
        $this->view->miniPrograms = $miniPrograms ?: array();
        $this->view->title        = $this->lang->aiapp->generalAgent;
        $this->display();
    }

    /**
     * Collect mini program by appid.
     *
     * @param string $appID
     * @param string $delete
     * @access public
     * @return void
     */
    public function collectMiniProgram($appID, $delete = 'false')
    {
        $this->ai->collectMiniProgram($this->app->user->id, $appID, $delete);
        return $this->send(array('status' => ($delete === 'true' ? '0' : '1')));
    }

    /**
     * 模型列表。
     * Models page.
     *
     * @access public
     * @return void
     */
    public function models()
    {
        $this->view->title = $this->lang->aiapp->models;
        $this->display();
    }

    /**
     * 智能会话页面。
     * Conversation page.
     *
     * @param string $chat   要打开的会话 ID，ID 中的 _ 会被替换为 -，如果设置为 NEW，则打开新会话。 The chat ID to open, _ will be replaced with -, if set to NEW, open a new chat. The default is an empty string.
     * @param string $params 会话参数，JSON 字符串，使用 base64 编码。 The chat parameters, JSON string, using base64 encoding.
     * @access public
     * @return void
     */
    public function conversation($chat = '', $params = '')
    {
        $this->view->title         = $this->lang->aiapp->conversation;
        $this->view->currentChatID = empty($chat) ? '' : str_replace('_', '-', $chat);
        $this->view->params        = empty($params) ? '' : helper::safe64Decode($params);
        $this->display();
    }
}
