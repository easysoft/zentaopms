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
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        $webhooks = $this->dao->select('*')->from(TABLE_WEBHOOK)->orderBy($orderBy)->page($pager)->fetchAll('id');
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
        foreach($logs as $log) $log->data = json_decode($log->data);
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
     * @access public
     * @return bool
     */
    public function create()
    {
        $webhook = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->setForce('params', implode(',', $this->post->params) . ',message')
            ->setForce('actions', helper::jsonEncode($this->post->actions))
            ->skipSpecial('url,actions')
            ->get();
        
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
            ->setForce('params', implode(',', $this->post->params) . ',message')
            ->setForce('actions', helper::jsonEncode($this->post->actions))
            ->skipSpecial('url,actions')
            ->get();

        $this->dao->update(TABLE_WEBHOOK)->data($webhook)
            ->batchCheck($this->config->webhook->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * Delete a webhook. 
     * 
     * @param  int    $id 
     * @param  object $null 
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        $this->dao->delete()->from(TABLE_WEBHOOK)->where('id')->eq($id)->exec();
        $this->dao->delete()->from(TABLE_LOG)->where('objectType')->eq('webhook')->andWhere('objectID')->eq($id)->exec();
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
            if(!in_array($actionType, $webhook->actions->$objectType)) continue;
            $postData = $this->buildData($objectType, $objectID, $actionType, $actionID, explode(',', $webhook->params));
            if($webhook->sendType == 'async')
            {
                $this->saveData($id, $actionID, $postData);
                continue;
            }
            
            $contentType = zget($this->config->webhook->contentTypes, $webhook->contentType, 'application/json');
            $httpCode    = $this->fetchHook($contentType, $webhook->url, $postData);

            $this->saveLog($id, $actionID, $webhook->url, $contentType, $postData, $httpCode);
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
     * @param  array  $params 
     * @access public
     * @return string
     */
    public function buildData($objectType, $objectID, $actionType, $actionID, $params)
    {
        static $users = array();
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');
        if(!isset($this->lang->action->label)) $this->loadModel('action');
        if(!isset($this->lang->action->label->$actionType)) return false;
        if(empty($this->config->objectTables[$objectType])) return false;
        $action = $this->dao->select('*')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch();
        $object = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
        $field  = $this->config->action->objectNameFields[$objectType];
        $title  = $object->$field;

        $oldOnlyBody = '';
        if(isset($_GET['onlybody']) and $_GET['onlybody'] == 'yes')
        {
            $oldOnlyBody = 'yes';
            unset($_GET['onlybody']);
        }
        $viewLink = helper::createLink($objectType, 'view', "id=$objectID");
        if($oldOnlyBody) $_GET['onlybody'] = $oldOnlyBody;

        $host = common::getSysURL();
        $data = new stdclass();
        foreach($params as $param)
        {
            if($param == 'message')
            {
                $data->text = $this->app->user->realname . $this->lang->action->label->$actionType . $this->lang->action->objectTypes[$objectType] . ' ' . "[#{$objectID}::{$title}](" . $host . $viewLink . ")";
                if($actionType == 'assigned') $data->text .= ' ' . $this->lang->webhooks->assigned . ' ' . zget($users, $object->assignedTo);
            }
            else
            {
                $data->$param = $action->$param;
            }
        }

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

        return helper::jsonEncode($data);
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
        $header[] = "Content-Type: $contentType";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

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
     * @param  int    $status 
     * @access public
     * @return bool 
     */
    public function saveLog($webhookID, $actionID, $url, $contentType, $data, $status)
    {
        $log = new stdclass();
        $log->objectType  = 'webhook';
        $log->objectID    = $webhookID;
        $log->action      = $actionID;
        $log->date        = helper::now();
        $log->url         = $url;
        $log->contentType = $contentType;
        $log->data        = $data;
        $log->status      = $status;

        $this->dao->insert(TABLE_LOG)->data($log)->exec();
        return !dao::isError();
    }
}
