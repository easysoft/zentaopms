<?php
/**
 * The model file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class webhookModel extends model
{
    /**
     * Get a webhook by id. 
     * 
     * @param  int    $id
     * @access public
     * @return array
     */
    public function getByID($id)
    {
        $webhook = $this->dao->select('*')->from(TABLE_WEBHOOK)->where('id')->eq($id)->fetch();
        $webhook->actions = json_decode($webhook->actions);
        return $webhook;
    }

    /**
     * Get webhook list. 
     * 
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($type = '', $orderBy = 'id_desc', $pager = null)
    {
        $webhooks = $this->dao->select('*')->from(TABLE_WEBHOOK)
            ->where('deleted')->eq('0')
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        foreach($webhooks as $webhook) $webhook->actions = json_decode($webhook->actions);
        return $webhooks;
    }

    /**
     * Get log list of a webhook. 
     * 
     * @param  int    $id
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array 
     */
    public function getLogList($id, $orderBy = 'date_desc', $pager = null)
    {
        $logs = $this->dao->select('*')->from(TABLE_LOG)
            ->where('objectType')->eq('webhook')
            ->andWhere('objectID')->eq($id)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $actions = array();
        foreach($logs as $log) $actions[] = $log->action;

        $this->loadModel('action');
        $actions = $this->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id');

        foreach($logs as $log)
        {
            if(!isset($actions[$log->action]))
            {
                $log->action = '';
                continue;
            }

            $action = $actions[$log->action];

            $text   = $this->app->user->realname . $this->lang->action->label->{$action->action}. $this->lang->action->objectTypes[$action->objectType];
            $object = $this->dao->select('*')->from($this->config->objectTables[$action->objectType])->where('id')->eq($action->objectID)->fetch();
            $field  = $this->config->action->objectNameFields[$action->objectType];
            $text  .= "[#{$action->objectID}::{$object->$field}]";
            if($action->action == 'assigned') $text .= ' ' . $this->lang->webhook->assigned . ' ' . zget($users, $object->assignedTo);

            $log->action    = $text;
            $log->actionURL = $this->getViewLink($action->objectType, $action->objectID);
        }
        return $logs;
    }

    /**
     * Get saved data list. 
     * 
     * @access public
     * @return array
     */
    public function getDataList()
    {
        return $this->dao->select('*')->from(TABLE_WEBHOOKDATA)->where('status')->eq('wait')->orderBy('id')->fetchAll('id');
    }

    /**
     * Get object types. 
     * 
     * @access public
     * @return array
     */
    public function getObjectTypes()
    {
        $objectTypes = array();
        foreach($this->config->webhook->objectTypes as $objectType => $actions)
        {
            $objectTypes[$objectType] = $this->lang->action->objectTypes[$objectType];
        }
        return $objectTypes;
    }

    /**
     * Get object actions. 
     * 
     * @access public
     * @return array
     */
    public function getObjectActions()
    {
        $objectActions = array();
        foreach($this->config->webhook->objectTypes as $objectType => $actions)
        {
            foreach($actions as $action)
            {
                $objectActions[$objectType][$action] = str_replace($this->lang->webhook->trimWords, '', $this->lang->action->label->$action);
            }
        }
        return $objectActions;
    }

    /**
     * Create a webhook. 
     * 
     * @param  string $type
     * @access public
     * @return bool
     */
    public function create($type)
    {
        $webhook = fixer::input('post')
            ->add('type', $type)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('products', ',')
            ->join('projects', ',')
            ->skipSpecial('url')
            ->get();
        $webhook->params  = $this->post->params ? implode(',', $this->post->params) . ',text' : 'text';
        $webhook->actions = $this->post->actions ? json_encode($this->post->actions) : '[]';
        
        $this->dao->insert(TABLE_WEBHOOK)->data($webhook)
            ->batchCheck($this->config->webhook->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        return !dao::isError();
    }

    /**
     * Update a webhook. 
     * 
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $webhook = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->join('products', ',')
            ->join('projects', ',')
            ->skipSpecial('url')
            ->get();
        $webhook->params  = $this->post->params ? implode(',', $this->post->params) . ',text' : 'text';
        $webhook->actions = $this->post->actions ? json_encode($this->post->actions) : '[]';

        $this->dao->update(TABLE_WEBHOOK)->data($webhook)
            ->batchCheck($this->config->webhook->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * Send data. 
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $actionType 
     * @param  int    $actionID 
     * @access public
     * @return bool 
     */
    public function send($objectType, $objectID, $actionType, $actionID)
    {
        static $webhooks = array();
        if(!$webhooks) $webhooks = $this->getList();
        if(!$webhooks) return true;

        $snoopy = $this->app->loadClass('snoopy');
        foreach($webhooks as $id => $webhook)
        {
            if(!isset($webhook->actions->$objectType) or !in_array($actionType, $webhook->actions->$objectType)) continue;
            $postData = $this->buildData($objectType, $objectID, $actionType, $actionID, $webhook);
            if(!$postData) continue;

            if($webhook->sendType == 'async')
            {
                $this->saveData($id, $actionID, $postData);
                continue;
            }
            
            $contentType = zget($this->config->webhook->contentTypes, $webhook->contentType, 'application/json');
            $result      = $this->fetchHook($contentType, $webhook->url, $postData);
            $this->saveLog($id, $actionID, $webhook->url, $contentType, $postData, $result);
        }
        return !dao::isError();
    }

    /**
     * Build data. 
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $actionType 
     * @param  int    $actionID
     * @param  object $webhook
     * @access public
     * @return string
     */
    public function buildData($objectType, $objectID, $actionType, $actionID, $webhook)
    {
        if(!isset($this->lang->action->label)) $this->loadModel('action');
        if(!isset($this->lang->action->label->$actionType)) return false;
        if(empty($this->config->objectTables[$objectType])) return false;
        $action = $this->dao->select('*')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch();
        if($webhook->products)
        {
            $webhookProducts = explode(',', trim($webhook->products, ','));
            $actionProduct   = explode(',', trim($action->product, ','));
            $intersect       = array_intersect($webhookProducts, $actionProduct);
            if(!$intersect) return false;
        }
        if($webhook->projects)
        {
            if(strpos(",$webhook->projects,", ",$action->project,") === false) return false;
        }

        static $users = array();
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');

        $object   = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
        $field    = $this->config->action->objectNameFields[$objectType];
        $text     = $object->$field;
        $host     = common::getSysURL();
        $viewLink = $this->getViewLink($objectType, $objectID);

        $data = new stdclass();
        if($webhook->type == 'dingding')
        {
            $title = $this->app->user->realname . $this->lang->action->label->$actionType . $this->lang->action->objectTypes[$objectType];
            if($actionType == 'assigned') $title .= ' ' . $this->lang->webhook->assigned . ' ' . zget($users, $object->assignedTo);

            $link = new stdclass();
            $link->text       = "[#{$objectID}::{$text}]";
            $link->title      = $title;
            $link->picUrl     = '';
            $link->messageUrl = $host . $viewLink;

            $data->msgtype = 'link';
            $data->link    = $link;

            return helper::jsonEncode($data);
        }

        foreach(explode(',', $webhook->params) as $param)
        {
            if($param == 'text')
            {
                $data->text = $this->app->user->realname . $this->lang->action->label->$actionType . $this->lang->action->objectTypes[$objectType] . ' ' . "[#{$objectID}::{$text}](" . $host . $viewLink . ")";
                if($actionType == 'assigned') $data->text .= ' ' . $this->lang->webhook->assigned . ' ' . zget($users, $object->assignedTo);
            }
            else
            {
                $data->$param = $action->$param;
            }
        }
        $data = $this->getFiles($data, $objectType, $objectID);

        return helper::jsonEncode($data);
    }

    /**
     * Get view link. 
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @access public
     * @return string
     */
    public function getViewLink($objectType, $objectID)
    {
        $oldOnlyBody = '';
        if(isset($_GET['onlybody']) and $_GET['onlybody'] == 'yes')
        {
            $oldOnlyBody = 'yes';
            unset($_GET['onlybody']);
        }
        $viewLink = helper::createLink($objectType, 'view', "id=$objectID");
        if($oldOnlyBody) $_GET['onlybody'] = $oldOnlyBody;

        return $viewLink;
    }

    /**
     * Get files. 
     * 
     * @param  object $data 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @access public
     * @return object 
     */
    public function getFiles($data, $objectType, $objectID)
    {
        if(!empty($_FILES['files']['name'][0]))
        {
            $this->loadModel('file');
            $files = $this->dao->select('*')->from(TABLE_FILE)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->orderBy('addedDate_desc,id_desc')->limit(count($_FILES['files']['name']))->fetchAll();
            if($files)
            {
                foreach($files as $file)
                {
                    $attachment = array();
                    $attachment['title'] = $file->title;
                    $attachment['images'][]['url'] = $data->host . $this->file->webPath . $file->pathname; 
                    $data->attachments[] = $attachment;
                }
            }
        }
        return $data;
    }

    /**
     * Post hook data. 
     * 
     * @param  string $contentType 
     * @param  string $url 
     * @param  string $sendData 
     * @access public
     * @return int 
     */
    public function fetchHook($contentType, $url, $sendData)
    {
        $header[] = "Content-Type: $contentType;charset=utf-8";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if($error)  return $error;
        if($result) return $result;
        return $httpCode;
    }

    /**
     * Save datas. 
     * 
     * @param  int    $webhookID 
     * @param  int    $actionID 
     * @param  string $data 
     * @access public
     * @return bool
     */
    public function saveData($webhookID, $actionID, $data)
    {
        $webhookData = new stdclass();
        $webhookData->webhook     = $webhookID;
        $webhookData->action      = $actionID;
        $webhookData->data        = $data;
        $webhookData->createdBy   = $this->app->user->account;
        $webhookData->createdDate = helper::now();

        $this->dao->insert(TABLE_WEBHOOKDATA)->data($webhookData)->exec();
        return !dao::isError();
    }

    /**
     * Save log. 
     * 
     * @param  int    $webhookID 
     * @param  int    $actionID 
     * @param  string $url 
     * @param  string $contentType 
     * @param  string $data 
     * @param  string $result
     * @access public
     * @return bool 
     */
    public function saveLog($webhookID, $actionID, $url, $contentType, $data, $result)
    {
        $log = new stdclass();
        $log->objectType  = 'webhook';
        $log->objectID    = $webhookID;
        $log->action      = $actionID;
        $log->date        = helper::now();
        $log->url         = $url;
        $log->contentType = $contentType;
        $log->data        = $data;
        $log->result      = $result;

        $this->dao->insert(TABLE_LOG)->data($log)->exec();
        return !dao::isError();
    }
}
