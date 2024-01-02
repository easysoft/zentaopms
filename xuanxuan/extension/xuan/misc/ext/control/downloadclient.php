<?php
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
        ini_set('memory_limit', '256M'); // Temporarily handle the problem that the ZenTao client file is too large.

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
                if($result === false)
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
            if(strpos($agentOS, 'Mac') !== false)     $os = 'mac64';

            $this->view->os = $os;

            /* Finish task #6990. */
            $releasedInDB = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('version')->eq($this->config->xuanxuan->version)->andWhere('status')->eq('released')->fetch();
            if($releasedInDB)
            {
                foreach(json_decode($releasedInDB->downloads) as $osKey => $link)
                {
                    if(empty($link))
                    {
                        $osKey = strtolower(str_replace('zip', '', $osKey));
                        if(isset($this->lang->misc->client->osList[$osKey]))
                        {
                            unset($this->lang->misc->client->osList[$osKey]);
                        }
                        elseif(strpos($osKey, 'mac') === 0)
                        {
                            unset($this->lang->misc->client->osList['mac64']);
                        }
                    }
                }
            }
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
            ini_set('memory_limit', '1G');
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
