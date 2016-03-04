<?php
include 'router.class.php';
class myrouter extends router
{
    public function loadCommon()
    {
        $this->setModuleName('common');
        $commonModelFile = helper::setModelFile('common');
        if(file_exists($commonModelFile))
        {
            helper::import($commonModelFile);
            if(class_exists('extcommonModel'))
            {
                $commonClass = 'class common extends extcommonModel{}';
                eval($commonClass);
                return new extcommonModel();
            }
            elseif(class_exists('commonModel'))
            {
                $commonClass = 'class common extends commonModel{}';
                eval($commonClass);
                return new commonModel();
            }
            else
            {
                return false;
            }
        }
    }

    public function loadLang($moduleName, $appName = '')
    {
        $modulePath   = $this->getModulePath($appName, $moduleName);
        $mainLangFile = $modulePath . 'lang' . DS . $this->clientLang . '.php';
        $extLangPath        = $this->getModuleExtPath($appName, $moduleName, 'lang');
        $commonExtLangFiles = helper::ls($extLangPath['common'] . $this->clientLang, '.php');
        $siteExtLangFiles   = helper::ls($extLangPath['site'] . $this->clientLang, '.php');
        $extLangFiles       = array_merge($commonExtLangFiles, $siteExtLangFiles);

        /* Set the files to includ. */
        if(!is_file($mainLangFile))
        {
            if(empty($extLangFiles)) return false;  // also no extension file.
            $langFiles = $extLangFiles;
        }
        else
        {
            $langFiles = array_merge(array($mainLangFile), $extLangFiles);
        }

        global $lang;
        if(!is_object($lang)) $lang = new language();

        /* Set productCommon and projectCommon for flow. */
        if($moduleName == 'common')
        {
            $productProject = false;
            if($this->dbh and !empty($this->config->db->name)) $productProject = $this->dbh->query('SELECT value FROM' . TABLE_CONFIG . "WHERE `owner`='system' AND `module`='custom' AND `key`='productproject'")->fetch();

            $productCommon = $projectCommon = 0;
            if($productProject)
            {
                $productProject = $productProject->value;
                list($productCommon, $projectCommon) = explode('_', $productProject);
            }
            $lang->productCommon = isset($this->config->productCommonList[$this->clientLang][(int)$productCommon]) ? $this->config->productCommonList[$this->clientLang][(int)$productCommon] : $this->config->productCommonList['zh-cn'][0];
            $lang->projectCommon = isset($this->config->projectCommonList[$this->clientLang][(int)$projectCommon]) ? $this->config->projectCommonList[$this->clientLang][(int)$projectCommon] : $this->config->projectCommonList['zh-cn'][0];
        }

        static $loadedLangs = array();
        foreach($langFiles as $langFile)
        {
            if(in_array($langFile, $loadedLangs)) continue;
            include $langFile;
            $loadedLangs[] = $langFile;
        }

        /* Merge from the db lang. */
        if($moduleName != 'common' and isset($lang->db->custom[$moduleName]))
        {
            foreach($lang->db->custom[$moduleName] as $section => $fields)
            {
                foreach($fields as $key => $value)
                {
                    unset($lang->{$moduleName}->{$section}[$key]);
                    $lang->{$moduleName}->{$section}[$key] = $value;
                }
            }
        }

        $this->lang = $lang;
        return $lang;
    }
}
