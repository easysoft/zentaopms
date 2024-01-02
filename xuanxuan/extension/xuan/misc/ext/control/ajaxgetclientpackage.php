<?php
class myMisc extends misc
{
    /**
     * Ajax get client package.
     *
     * @param  string $os
     * @access public
     * @return void
     */
    public function ajaxGetClientPackage($os = '')
    {
        ini_set('memory_limit', '256M'); // Temporarily handle the problem that the ZenTao client file is too large.

        set_time_limit (0);
        session_write_close();

        $response = array();
        $response['result']  = 'success';
        $response['message'] = '';

        $clientDir = $this->app->wwwRoot . 'data/client/';
        if(!is_dir($clientDir)) mkdir($clientDir, 0755, true);

        $version    = $this->config->xuanxuan->version;
        $packageDir = $clientDir . "/$version/";
        if(!is_dir($packageDir)) mkdir($packageDir, 0755, true);

        $account = $this->app->user->account;
        $tmpDir = $clientDir . "/$account/";
        if(!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $needCache   = false;
        $clientName  = "zentaoclient." . $os . ".zip";
        $packageFile = $packageDir . $clientName;
        if(!file_exists($packageFile))
        {
            $url       = "http://dl.cnezsoft.com/zentaoclient/$version/";
            $xxFile    = $url . $clientName . "?t=" . rand();
            $needCache = true;
        }
        else
        {
            $xxFile = $packageFile;
        }

        $clientFile = $tmpDir . 'zentaoclient.zip';
        if($xxHd = fopen($xxFile, "rb"))
        {
            if($clientHd = fopen($clientFile, "wb"))
            {
                while(!feof($xxHd))
                {
                    $result = fwrite($clientHd, fread($xxHd, 1024 * 8 ), 1024 * 8 );
                    if($result === false)
                    {
                        $response['result']  = 'fail';
                        $response['message'] = sprintf($this->lang->misc->client->errorInfo->manualOpt, $xxFile);
                        $this->send($response);
                    }
                }
            }
            else
            {
                $response['result'] = 'fail';
                $response['message'] = sprintf($this->lang->misc->client->errorInfo->manualOpt, $xxFile);
                $this->send($response);
            }
            fclose($xxHd);
            fclose($clientHd);
        }
        else
        {
            $response['result'] = 'fail';
            $response['message'] = sprintf($this->lang->misc->client->errorInfo->manualOpt, $xxFile);
            $this->send($response);
        }

        if($needCache) file_put_contents($packageFile, file_get_contents($clientFile));

        $this->send($response);
    }
}
