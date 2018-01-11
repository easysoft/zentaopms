<?php
/**
 * The model file of message module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class messageModel extends model
{
    /**
     * Get objectTypes 
     * 
     * @access public
     * @return array
     */
    public function getObjectTypes()
    {
        $objectTypes = array();
        foreach($this->config->message->objectTypes as $objectType => $actions)
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
        foreach($this->config->message->objectTypes as $objectType => $actions)
        {
            foreach($actions as $action)
            {
                $objectActions[$objectType][$action] = str_replace($this->lang->webhook->trimWords, '', $this->lang->action->label->$action);
            }
        }
        return $objectActions;
    }

    /**
     * Check send.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $actionType 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function send($objectType, $objectID, $actionType, $actionID)
    {
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);

        if(isset($messageSetting['mail']))
        {
            $actions = $messageSetting['mail']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
                $this->loadModel($moduleName);
                if(method_exists($this->$moduleName, 'sendmail')) $this->$moduleName->sendmail($objectID, $actionID);
            }
        }

        if(isset($messageSetting['webhook']))
        {
            $actions = $messageSetting['webhook']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                $this->loadModel('webhook')->send($objectType, $objectID, $actionType, $actionID);
            }
        }
    }
}
