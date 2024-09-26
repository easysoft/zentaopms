<?php
class myMisc extends misc
{
    /**
     * Ajax get client package size.
     *
     * @param  string $type
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function ajaxGetPackageSize($type = 'client', $fileName = 'zentaoclient.zip')
    {
        $account     = $this->app->user->account;
        $packageFile = $this->app->wwwRoot . "data/{$type}/{$account}/{$fileName}";

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
