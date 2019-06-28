<?php
/**
 * The model file of translate module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     translate
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class translateModel extends model
{
    public function addLang()
    {
        $data  = fixer::input('post')->add('createdBy', $this->app->user->account)->get();
        if(empty($data->name)) dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->translate->name);
        if(empty($data->code)) dao::$errors['code'] = sprintf($this->lang->error->notempty, $this->lang->translate->code);
        if(dao::isError()) return false;

        $langs = empty($this->config->global->langs) ? array() : json_decode($this->config->global->langs, true);
        if(isset($langs[$data->code]))
        {
            dao::$errors['code'] = sprintf($this->lang->translate->notice->failUnique, $data->code);
            return false;
        }

        $langs[$data->code] = $data;
        $this->loadModel('setting')->setItem('system.common.global.langs', json_encode($langs));

        $modules = glob($this->app->getModuleRoot() . '*');
        foreach($modules as $modulePath)
        {
            $moduleName = basename($modulePath);
            $this->initModuleLang($moduleName, $data->code, $data->reference);

            $mainLangFile = $modulePath . DS . 'lang' . DS . $data->reference . '.php';
            if(file_exists($mainLangFile))
            {
                $targetFile = $modulePath . DS . 'lang' . DS . $data->code . '.php';
                if(!copy($mainLangFile, $targetFile)) dao::$errors[] = sprintf($this->lang->translate->notice->failCopyFile, $mainLangFile, $targetFile);
            }

            $extLangPath = $modulePath . DS . 'ext' . DS . 'lang' . DS . $data->reference;
            if(is_dir($extLangPath))
            {
                mkdir($modulePath . DS . 'ext' . DS . 'lang' . DS . $data->code);
                $extLangFiles = glob($extLangPath . DS . '*.php');
                foreach($extLangFiles as $extLangFile)
                {
                    $targetFile = $modulePath . DS . 'ext' . DS . 'lang' . DS . $data->code . DS . basename($extLangFile);
                    if(!copy($extLangPath, $targetFile)) dao::$errors[] = sprintf($this->lang->translate->notice->failCopyFile, $extLangFile, $targetFile);
                }
            }
        }

        return true;
    }

    public function initModuleLang($moduleName, $langCode, $referLang)
    {
        $this->app->loadLang('custom');
        $flows = array_keys($this->lang->custom->workingList);
        $now  = helper::now();
        foreach($flows as $flow)
        {
            $initLangs = $this->getModuleLangs($moduleName, $referLang, $flow);
            foreach($initLangs as $key => $value)
            {
                $translation = new stdclass();
                $translation->lang            = $langCode;
                $translation->module          = $moduleName;
                $translation->key             = $key;
                $translation->value           = $value;
                $translation->refer           = htmlspecialchars($translation->value);
                $translation->status          = 'waiting';
                $translation->version         = $this->config->version;
                $translation->translator      = $this->app->user->account;
                $translation->translationTime = $now;
                $translation->mode            = $flow;
                $this->dao->replace(TABLE_TRANSLATION)->data($translation)->exec();
            }
        }
    }

    public function getModuleLangs($moduleName, $langCode, $flow = '')
    {
        if(empty($flow))$flow = $this->config->global->flow;
        $modulePath   = $this->app->getModuleRoot() . $moduleName;
        $mainLangFile = $modulePath . "/lang/{$langCode}.php";
        $langItems    = array();
        if(file_exists($mainLangFile)) $langItems += $this->getActiveItemsByFile($mainLangFile, $flow);

        $extLangPath = $modulePath . DS . 'ext' . DS . 'lang' . DS . $langCode;
        if(is_dir($extLangPath))
        {
            $extLangFiles = glob($extLangPath . DS . '*.php');
            foreach($extLangFiles as $extLangFile) $langItems += $this->getActiveItemsByFile($extLangFile, $flow);
        }
        return $langItems;
    }

    public function getActiveItemsByFile($fileName, $flow = '')
    {
        if(empty($flow)) $flow = $this->config->global->flow;
        $lines       = file($fileName);
        $inCondition = false;
        $inFlow      = true;
        $level       = 0;
        $items       = array();
        foreach($lines as $i => $line)
        {
            $line = trim($line);
            if(empty($line)) continue;
            if(strpos($line, '$lang->menuOrder') === 0) continue;
            if(strpos($line, 'include') === 0 or strpos($line, 'global') === 0) continue;
            if(strpos($line, 'unset(') !== false) continue;
            if(strpos($line, '/*') === 0 or strpos($line, '//') === 0 or strpos($line, '<?php') === 0 or strpos($line, '*') === 0) continue;
            if(strpos($line, 'if') !== false and isset($lines[$i + 1]) and trim($lines[$i + 1]) == '{')
            {
                $inCondition = true;
                $level ++;
                if(strpos($line, 'config->global->flow') !== false and strpos($line, $flow) === false) $inFlow = false;
            }
            if($line == '}' and $inCondition)
            {
                $level --;
                if($level == 0)
                {
                    $inCondition = false;
                    $inFlow      = true;
                }
            }
            if($line == '{' or $line == '}' or strpos($line, 'if') === 0 or strpos($line, 'else') === 0) continue;
            if($inFlow and ($flow == 'full' or ($flow != 'full' and $inCondition)))
            {
                if(strpos($line, '$lang') === 0 and strpos($line, '=') !== false)
                {
                    $position = strpos($line, '=');
                    $key      = trim(substr($line, 0, $position));
                    $value    = trim(substr($line, $position + 1));
                    $items[$key] = $value;
                }
                elseif(isset($key) and strpos($key, '$lang') === 0)
                {
                    $items[$key] .= "\n" . $line;
                }
            }
        }

        foreach($items as $key => $value)
        {
            /* Remove Notes. */
            if(preg_match( '/; *\/\//', $value)) $value = preg_replace('/; *\/\/.*/', '', $value);
            $value = trim($value, ';');
            /* Trim ['] or ["] when not [.] . */
            if(preg_match('/["\'] *\.[^\.]/', $value) == 0 and preg_match('/[^\.]\. *["\']/', $value) == 0)
            {
                if($value{0} == '"' or $value{0} == "'") $value = trim($value, $value{0});
            }
            $items[$key] = $value;
        }
        return $items;
    }

    public function checkDirPriv($moduleName = '')
    {
        $cmd        = '';
        $moduleRoot = $this->app->getModuleRoot();
        $modules    = !empty($moduleName) ? array($moduleRoot . $moduleName) : glob($moduleRoot . '*');
        foreach($modules as $modulePath)
        {
            if(is_dir($modulePath . '/lang') and !is_writable($modulePath . '/lang')) $cmd .= "chmod 777 {$modulePath}/lang <br />";
            if(is_dir($modulePath . '/ext/lang') and !is_writable($modulePath . '/ext/lang')) $cmd .= "chmod -R 777 {$modulePath}/ext/lang <br />";
        }
        return $cmd;
    }

    public function getModules()
    {
        $this->loadModel('dev');
        foreach($this->lang->dev->endGroupList as $group => $groupName) $this->lang->dev->groupList[$group] = $groupName;

        $moduleList = glob($this->app->getModuleRoot() . '*');
        $modules    = array();
        foreach($moduleList as $module)
        {
            if(!is_dir($module . '/lang') and !is_dir($module . '/ext/lang')) continue;
            $module = basename($module);
            $group  = zget($this->config->dev->group, $module, 'other');
            $modules[$group][] = $module;
        }

        return $modules;
    }

    public function getLangItemCount()
    {
        $moduleGroups = $this->getModules();
        $moduleRoot   = $this->app->getModuleRoot();
        $itemCount    = 0;
        foreach($moduleGroups as $group => $modules)
        {
            foreach($modules as $module)
            {
                $items = $this->getModuleLangs($module, 'zh-cn');
                $itemCount += count($items);
            }
        }
        return $itemCount;
    }

    public function getLangStatistics()
    {
        $langs = $this->dao->select("`lang`,sum(if((status = 'translated'),1,0)) as translatedItems,sum(if((status = 'reviewed'),1,0)) as reviewedItems, count(*) as count")->from(TABLE_TRANSLATION)->groupBy('`lang`')->fetchAll('lang');
        foreach($langs as $lang => $data) $data->progress = round(($data->translatedItems + $data->reviewedItems) / $data->count, 3);
        return $langs;
    }

    public function getModuleStatistics($language)
    {
        $fields = 'lang,module,';
        foreach($this->lang->translate->statusList as $status => $title) $fields .= "sum(if((status = '$status'),1,0)) as $status,";
        $fields .= "count(*) as count";
        return $this->dao->select($fields)->from(TABLE_TRANSLATION)->where('lang')->eq($language)->groupBy('module')->fetchAll('module');
    }

    /**
     * Get Translations 
     * 
     * @param  string $version 
     * @param  string $language 
     * @param  string $module 
     * @param  array  $langKeys 
     * @access public
     * @return void
     */
    public function getTranslations($language, $module = '', $langKeys = array())
    {
        return $this->dao->select('*')->from(TABLE_TRANSLATION)
            ->where('lang')->eq($language)
            ->andWhere('mode')->eq($this->config->global->flow)
            ->beginIF(!empty($module))->andWhere('module')->eq($module)->fi()
            ->beginIF(!empty($langKeys))->andWhere('langKey')->in($langKeys)->fi()
            ->orderBy('id asc')
            ->fetchAll('key');
    }

    /**
     * Add translation in database
     * 
     * @param  string    $zentaoVersion 
     * @param  string    $language 
     * @param  string    $module 
     * @access public
     * @return void
     */
    public function addTranslation($language, $module, $referLang)
    {
        $referItems   = $this->getModuleLangs($module, $referLang);
        $dbItems      = $this->getTranslations($language, $module);
        $translations = array();
        $flow         = $this->config->global->flow;
        foreach($this->post->keys as $i => $key)
        {
            $dbItem = zget($dbItems, $key, '');
            $value  = $referItems[$key];
            $refer  = $this->post->refers[$i];
            $now    = helper::now();
            if(!empty($_POST['values'][$i])) $value = $this->post->values[$i];
            if($dbItem)
            {
                $translation = $dbItem;
                $translation->version = $this->config->version;
                if($dbItem->value != $value)
                {
                    $translation->value           = $value;
                    $translation->refer           = $refer;
                    $translation->status          = 'translated';
                    $translation->translator      = $this->app->user->account;
                    $translation->translationTime = $now;
                    if($translation->reason) $translation->reason = '';
                }
                elseif(!empty($_POST['values'][$i]) and strpos("waiting|changed", $translation->status) !== false)
                {
                    $translation->status = 'translated';
                }
            }
            else
            {
                $translation = new stdclass();
                $translation->lang            = $language;
                $translation->module          = $module;
                $translation->key             = $key;
                $translation->value           = $value;
                $translation->refer           = $refer;
                $translation->status          = 'translated';
                $translation->translator      = $this->app->user->account;
                $translation->translationTime = $now;
                $translation->version         = $this->config->version;
                $translation->mode            = $flow;
            }
            $translations[$key] = $translation;
        }

        $this->dao->delete()->from(TABLE_TRANSLATION)->where('lang')->eq($language)->andWhere('module')->eq($module)->andWhere('mode')->eq($flow)->exec();
        foreach($translations as $translation) $this->dao->replace(TABLE_TRANSLATION)->data($translation)->exec();
        if(!dao::isError()) $this->buildLangFile($language, $module, $referLang);
    }

    public function buildLangFile($language, $module, $referLang)
    {
        $moduleRoot   = $this->app->getModuleRoot();
        $newLangFile  = $moduleRoot . $module . "/lang/{$language}.php";
        $translations = $this->dao->select('*')->from(TABLE_TRANSLATION)->where('lang')->eq($language)->andWhere('module')->eq($module)->fetchGroup('mode', 'key');
        $newContent   = "<?php\n";

        $mainReferLangFile = $moduleRoot . $module . "/lang/{$referLang}.php";
        if(file_exists($mainReferLangFile)) $newContent .= $this->getTranslatedLang($mainReferLangFile, $translations) . "\n";
        if(is_dir($moduleRoot . $module . "/ext/lang/$referLang"))
        {
            $extLangFiles = glob($moduleRoot . $module . "/ext/lang/{$referLang}/*.php");
            foreach($extLangFiles as $extLangFile) $newContent .= $this->getTranslatedLang($extLangFile, $translations) . "\n";
        }
        file_put_contents($newLangFile, $newContent);
    }

    public function getTranslatedLang($referLang, $translations)
    {
        $lines   = file($referLang);
        $inFlow  = true;
        $level   = 0;
        $content = '';
        $flow    = 'full';
        $flows   = array_keys($translations);
        foreach($lines as $i => $line)
        {
            $line = trim($line);
            if(strpos($line, '/*') === 0 or strpos($line, '//') === 0 or strpos($line, '<?php') === 0 or strpos($line, '*') === 0) continue;
            if(empty($line))
            {
                $content .= "\n";
                continue;
            }
            if(strpos($line, '$lang->menuOrder') === 0 or strpos($line, 'include') === 0 or strpos($line, 'global') === 0 or strpos($line, 'unset(') !== false)
            {
                $content .= $line . "\n";
                continue;
            }
            if(strpos($line, 'if') !== false and isset($lines[$i + 1]) and trim($lines[$i + 1]) == '{')
            {
                $inCondition = true;
                $level ++;

                $inFlow = false;
                foreach($flows as $flow)
                {
                    if(strpos($line, 'config->global->flow') !== false and strpos($line, $flow) === false)
                    {
                        $inFlow = true;
                        break;
                    }
                }
            }
            if($line == '}' and $inCondition)
            {
                $level --;
                if($level == 0)
                {
                    $inCondition = false;
                    $inFlow      = true;
                    $flow        = 'full';
                }
            }
            if($line == '{' or $line == '}' or strpos($line, 'if') === 0 or strpos($line, 'else') === 0)
            {
                $content .= $line . "\n";
                continue;
            }
            if(!$inFlow and $inCondition)
            {
                $content .= $line . "\n";
            }
            elseif($inFlow and strpos($line, '$lang') === 0 and strpos($line, '=') !== false)
            {
                $position = strpos($line, '=');
                $key      = trim(substr($line, 0, $position));
                if(isset($translations[$flow][$key]))
                {
                    $translation = $translations[$flow][$key];
                    $value       = $translation->value;
                    if($this->checkNeedTranslate($value) and strpos($value, 'array(') === false)
                    {
                        $value = '"' . addslashes($value) . '"';
                    }
                    $content .= $key . " = $value;\n";
                }
                else
                {
                    $content .= $line . "\n";
                }
            }
        }
        return $content;
    }

    public function checkNeedTranslate($value)
    {
        $result = true;
        if($value == 'new stdclass()') $result = false;
        if(strpos($value, '$') === 0 and strpos($value, '$lang->productCommon') === false and strpos($value, '$lang->projectCommon') === false and preg_match('/["\'] *\.[^\.]/', $value) == 0 and preg_match('/[^\.]\. *["\']/', $value) == 0) $result = false;
        if($value == '$lang->productCommon' or $value == '$lang->projectCommon') $result = false;

        return $result;
    }

    public function compare()
    {
        $version      = $this->config->version;
        $translations = $this->dao->select('*')->from(TABLE_TRANSLATION)->where('version')->ne($version)->fetchAll();
        if(empty($translations)) return true;

        $translateGroups = array();
        foreach($translations as $translation) $translateGroups[$translation->lang][$translation->mode][$translation->module][$translation->key] = $translation;

        $moduleGroups = $this->getModules();
        $allModules   = array();
        foreach($moduleGroups as $group => $modules)
        {
            foreach($modules as $module) $allModules[$module] = $module;
        }

        $this->app->loadLang('custom');
        $flows = array_keys($this->lang->custom->workingList);
        $langs = json_decode($this->config->global->langs, true);
        $referGroups = array();
        foreach($langs as $code => $data) $referGroups[$data['reference']][$code] = $code;
        foreach($referGroups as $referLang => $languages)
        {
            foreach($languages as $langCode)
            {
                $langTranslations = zget($translateGroups, $translation->lang, array());
                foreach($flows as $flow)
                {
                    foreach($allModules as $moduleName)
                    {
                        $langItems    = $this->getModuleLangs($moduleName, $referLang, $flow);
                        $translations = isset($langTranslations[$flow][$moduleName]) ? $langTranslations[$flow][$moduleName] : array();
                        foreach($langItems as $langKey => $langValue)
                        {
                            $translation = isset($translations[$langKey]) ? $translations[$langKey] : '';
                            if(empty($translation))
                            {
                                $translation = new stdclass();
                                $translation->lang            = $langCode;
                                $translation->module          = $moduleName;
                                $translation->key             = $langKey;
                                $translation->value           = $langValue;
                                $translation->refer           = htmlspecialchars($langValue);
                                $translation->status          = 'waiting';
                                $translation->translator      = $this->app->user->account;
                                $translation->translationTime = helper::now();
                                $translation->version         = $version;
                                $translation->mode            = $flow;
                                $this->dao->replace(TABLE_TRANSLATION)->data($translation)->exec();
                            }
                            else
                            {
                                if($langValue == $translation->refer)
                                {
                                    $this->dao->update(TABLE_TRANSLATION)->set('version')->eq($version)->where('id')->eq($translation->id)->exec();
                                }
                                elseif($langValue != $translation->refer)
                                {
                                    $this->dao->update(TABLE_TRANSLATION)->set('version')->eq($version)->set('status')->eq('changed')->set('refer')->eq(htmlspecialchars($langValue))->where('id')->eq($translation->id)->exec();
                                }
                                unset($translations[$langKey]);
                            }
                            if($translations)
                            {
                                $idList = array();
                                foreach($translations as $translation) $idList[$translation->id] = $translation->id;
                                $this->dao->delete()->from(TABLE_TRANSLATION)->where('id')->in($idList)->exec();
                            }
                        }
                    }
                }
            }
        }
    }
}
