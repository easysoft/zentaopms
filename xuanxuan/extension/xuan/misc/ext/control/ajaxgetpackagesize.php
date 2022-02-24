<?php
include '../../control.php';
class myMisc extends misc
{
    /**
     * Ajax get client package size.
     * 
     * @access public
     * @return void
     */
    public function ajaxGetPackageSize()
    {
        $account     = $this->app->user->account;
        $packageFile = $this->app->wwwRoot . 'data/client/' . $account . '/zentaoclient.zip';

        $size = 0;
        if(file_exists($packageFile))
        {
            $size = filesize($packageFile);
            $size = $size ? round($size / 1048576, 2) : 0;
        }

        $response = array();
        $response['result'] = 'success';
        $response['size'] = $size;

        $this->send($response);
    }
}
