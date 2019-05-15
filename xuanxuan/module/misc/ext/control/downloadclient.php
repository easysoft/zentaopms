<?php
include '../../control.php';
class myMisc extends misc
{
    /**
     * Download zentao client.
     * 
     * @access public
     * @param  string $action
     * @param  string $os 
     * @return void
     */
    public function downloadClient($action = 'check', $os = '')
    {
        if($_POST)
        {
            $os = $this->post->os;

            die(js::locate($this->createLink('misc', 'downloadClient', "action=getPackage&os=$os"), 'parent'));
        }

        if($action == 'check')
        {
            $error     = false;
            $errorInfo = '';

            $clientDir = $this->app->wwwRoot . 'data/client/' . $this->config->xuanxuan->version . '/';
            if(!is_dir($clientDir))
            {
                $result = mkdir($clientDir, 0755, true);
                if($result == false)
                {
                    $error = true;
                    $errorInfo = sprintf($this->lang->misc->client->errorInfo->dirNotExist, $clientDir, $clientDir);
                }
            }

            if(!is_writable($clientDir))
            {
                $error = true;
                $errorInfo = sprintf($this->lang->misc->client->errorInfo->dirNotWritable, $clientDir, $clientDir);
            }

            $this->view->error     = $error;
            $this->view->errorInfo = $errorInfo;

            if(!$error) die(js::locate($this->createLink('misc', 'downloadClient', "action=selectPackage")));
        }

        if($action == 'selectPackage')
        {
            $os = 'win64';
            $agentOS = helper::getOS();
            if(strpos($agentOS, 'Windows') !== false) $os = 'win64';
            if(strpos($agentOS, 'Linux') !== false)   $os = 'linux64';
            if(strpos($agentOS, 'Mac') !== false)     $os = 'mac';

            $this->view->os = $os;
        }

        if($action == 'getPackage')
        {
            $this->view->os      = $os;
            $this->view->account = $this->app->user->account; 
        }

        if($action == 'clearTmpPackage')
        {
            $account = $this->app->user->account;
            $tmpDir  = $this->app->wwwRoot . 'data/client/' . "$account/";

            if(is_dir($tmpDir))
            {
                $zfile = $this->app->loadClass('zfile');
                $zfile->removeDir($tmpDir);
            }

            die(js::closeModal('parent.parent', 'this'));
        }

        if($action == 'downloadPackage')
        {
            $account   = $this->app->user->account;
            $clientDir = $this->app->wwwRoot . 'data/client/' . "$account/";

            $clientFile = $clientDir . 'zentaoclient.zip';
            $zipContent = file_get_contents($clientFile);
            if(is_dir($clientDir))
            {
                $zfile = $this->app->loadClass('zfile');
                $zfile->removeDir($clientDir);
            }
            
            $this->fetch('file', 'sendDownHeader', array('fileName' => "zentao_chat_client." . $os . '.zip', 'zip', $zipContent));
        }

        $this->view->action = $action;
        $this->display();
    }
}
