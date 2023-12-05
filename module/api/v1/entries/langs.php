<?php
/**
 * The langs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @return string
     */
    public function get()
    {
        $modules  = $this->param('modules', '');
        $language = $this->param('lang', '');

        if($language and !isset($this->config->langs[$language])) return $this->sendError(400, 'Error lang parameter');
        if(empty($modules)) return $this->sendError(400, 'Need modules');

        if(empty($language)) $language = 'zh-cn';
        $this->app->setClientLang($language);

        global $filter;
        $rule    = $filter->default->moduleName;
        $modules = explode(',', $modules);
        foreach($modules as $module)
        {
            if($module == 'all')
            {
                $loadedModule = array();
                foreach(glob($this->app->getModuleRoot() . '*') as $modulePath)
                {
                    if(!is_dir($modulePath)) continue;

                    $moduleName = basename($modulePath);
                    if(!validater::checkByRule($moduleName, $rule)) continue;
                    $this->app->loadLang($moduleName);

                    $loadedModule[$moduleName] = $moduleName;
                }

                foreach(glob($this->app->getExtensionRoot() . '*') as $extensionPath)
                {
                    if(!is_dir($extensionPath)) continue;

                    $edition = basename($extensionPath);
                    if($edition == 'lite')  continue;
                    if($edition == 'biz' or $edition == 'max')
                    {
                        if($this->config->edition == 'open') continue;
                        if($this->config->edition != 'open' and $this->config->edition != $edition) continue;
                    }

                    foreach(glob($extensionPath . '/*') as $modulePath)
                    {
                        if(!is_dir($modulePath)) continue;

                        $moduleName = basename($modulePath);
                        if(!validater::checkByRule($moduleName, $rule)) continue;
                        if(isset($loadedModule[$moduleName])) continue;

                        $this->app->loadLang($moduleName);

                        $loadedModule[$moduleName] = $moduleName;
                    }
                }
                break;
            }

            if(validater::checkByRule($module, $rule)) $this->app->loadLang($module);
        }

        return $this->send(200, $this->lang);
    }
}
