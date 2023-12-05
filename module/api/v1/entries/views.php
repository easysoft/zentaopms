<?php
/**
 * The views entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class viewsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $position = $this->param('position', '');
        $tab      = $this->param('tab', '');
        $lite     = $this->param('lite', '');

        if(empty($position)) return $this->sendError(400, 'Need position param.');
        if($position != 'header' and $position != 'footer') return $this->sendError(400, 'Value of position param only is header or footer.');
        if(!empty($lite)) $lite .= 'lite.';

        if($tab)
        {
            $_COOKIE['tab'] = $tab;
            $this->app->setOpenApp();
        }

        $viewFile = $this->app->moduleRoot . "common/view/{$position}.{$lite}html.php";
        if(!file_exists($viewFile)) return $this->sendError(400, 'This view file is not exists.');

        $controller = new control();
        $viewHookFiles = glob($this->app->moduleRoot . "common/ext/view/{$position}.*.html.hook.php");

        $output  = '';
        if($position == 'header') $output .= $controller->printViewFile($viewFile);
        foreach($viewHookFiles as $hookFile) $output .= $controller->printViewFile($hookFile);
        if($position == 'footer') $output .= $controller->printViewFile($viewFile);

        $sysURL = common::getSysURL();
        $output = str_replace("href='{$this->config->webRoot}", "href='{$sysURL}{$this->config->webRoot}", $output);
        $output = str_replace("src='{$this->config->webRoot}", "src='{$sysURL}{$this->config->webRoot}", $output);

        return $this->send(200, array('html' => $output));
    }
}
