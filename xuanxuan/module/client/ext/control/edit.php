<?php
include '../../control.php';
class myClient extends client
{
    public function edit($clientID)
    {
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $this->app->loadLang('extension');
            $this->view->error = sprintf($this->lang->extension->noticeOkFile, str_replace('\\', '/', $statusFile));
            die($this->display('client', 'safe'));
        }

        parent::edit($clientID);
    }
}
