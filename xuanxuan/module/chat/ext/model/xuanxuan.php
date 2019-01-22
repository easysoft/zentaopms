<?php
public function getExtensionList($userID)
{
    return $this->loadExtension('xuanxuan')->getExtensionList($userID);
}

public function getUserListOutput($idList = array(), $userID)
{
    $output = new stdclass();
    $output->module = 'chat';
    $output->method = 'userGetList';

    $users = $this->getUserList($status = '', $idList, $idAsKey = false);
    if(dao::isError())
    {
        $output->result  = 'fail';
        $output->message = 'Get userlist failed.';
    }
    else
    {
        $output->result = 'success';
        $output->users  = !empty($userID) ? array($userID) : array();
        $output->data   = $users;

        $this->app->loadLang('user', 'sys');
        $roles = $this->lang->user->roleList;

        $allDepts = $this->loadModel('dept')->getListByType('dept');
        $depts = array();
        foreach($allDepts as $id => $dept)
        {
            $depts[$id] = array('name' => $dept->name, 'order' => (int)$dept->order, 'parent' => (int)$dept->parent);
        }
        $output->roles = $roles;
        $output->depts = $depts;
    }
    return $output;
}
