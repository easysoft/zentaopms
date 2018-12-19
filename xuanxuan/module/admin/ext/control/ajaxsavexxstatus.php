<?php
class admin extends control
{
    public function ajaxSaveXXStatus($type)
    {
        $account = $this->app->user->account;
        if($type == 'noticed')
        {
            $this->loadModel('setting')->setItem("system.common.xxserver.noticed", 1);
        }
        else
        {
            $this->loadModel('setting')->setItem("system.common.xxclient.{$account}installed", 1);
        }
    }
}
