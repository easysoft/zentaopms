<?php
/**
 * The langs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class langsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        $modules  = $this->param('modules', '');
        $language = $this->param('lang', '');

        if($language and !isset($this->config->langs[$language])) return $this->sendError(400, 'Error lang parameter');
        if(empty($modules)) return $this->sendError(400, 'Need modules');

        if(empty($language)) $language = 'zh-cn';
        $this->app->setClientLang($language);

        $modules = explode(',', $modules);
        foreach($modules as $module)
        {
            if($module == 'all')
            {
                foreach(glob($this->app->getModuleRoot() . '*') as $modulePath)
                {
                    if(!is_dir($modulePath)) continue;

                    $moduleName = basename($modulePath);
                    $this->app->loadLang($moduleName);
                }
                break;
            }

            $this->app->loadLang($module);
        }

        return $this->send(200, $this->lang);
    }
}
