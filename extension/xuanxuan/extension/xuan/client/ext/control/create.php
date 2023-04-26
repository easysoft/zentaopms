<?php
helper::import('../../control.php');
class myClient extends client
{
    public function create()
    {
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $this->app->loadLang('extension');
            $statusFile = str_replace('\\', '/', $statusFile);
            $this->view->error = sprintf($this->lang->extension->noticeOkFile, $statusFile, $statusFile);
            return print($this->display('client', 'safe'));
        }

        if($_POST) $_POST['desc'] = mb_substr($this->post->desc, 0, 100);

        parent::create();
    }
}
