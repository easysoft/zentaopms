<?php
/**
 * The control file of translate of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     translate
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class translate extends control
{
    /**
     * construct.
     * 
     * @param  string $moduleName 
     * @param  string $methodName 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
    }

    /**
     * Index method of translate. 
     * 
     * @param  string $zentaoVersion 
     * @access public
     * @return void
     */
    public function index()
    {
        $itemCount  = $this->translate->getLangItemCount();
        $statistics = $this->translate->getLangStatistics();
        $finishedLangs = $translatingLangs = array();
        foreach($this->config->translate->defaultLang as $defaultLang) $finishedLangs[$defaultLang] = $this->config->langs[$defaultLang];
        foreach($statistics as $translateLang => $data)
        {
            if($data->progress == 1)
            {
                $finishedLangs[$translateLang] = $this->config->langs[$translateLang];
            }
            elseif(isset($this->config->langs[$translateLang]))
            {
                $data->name = $this->config->langs[$translateLang];
                $translatingLangs[$translateLang] = $data;
            }
        }

        $this->view->title            = $this->lang->translate->common;
        $this->view->position[]       = $this->lang->translate->common;
        $this->view->finishedLangs    = $finishedLangs;
        $this->view->translatingLangs = $translatingLangs;
        $this->view->itemCount        = $itemCount;
        $this->display();
    }

    /**
     * Add lang.
     * 
     * @access public
     * @return void
     */
    public function addLang()
    {
        if($_POST)
        {
            $response = array();
            $this->translate->addLang();
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('translate', 'index');
            $this->send($response);
        }

        $referenceList = array();
        foreach($this->config->translate->defaultLang as $defaultLang)
        {
            if(!isset($this->config->langs[$defaultLang])) continue;
            $referenceList[$defaultLang] = $this->config->langs[$defaultLang];
        }
        $this->view->cmd = $this->translate->checkDirPriv();

        $this->view->title         = $this->lang->translate->addLang;
        $this->view->position[]    = html::a($this->createLink('translate', 'index'), $this->lang->translate->common);
        $this->view->position[]    = $this->lang->translate->addLang;
        $this->view->referenceList = $referenceList;
        $this->display();
    }

    /**
     * Choose module to translate.
     * 
     * @param  string $language 
     * @access public
     * @return void
     */
    public function chooseModule($language)
    {
        $this->view->title      = $this->lang->translate->chooseModule;
        $this->view->position[] = html::a($this->createLink('translate', 'index'), $this->lang->translate->common);
        $this->view->position[] = $this->lang->translate->chooseModule;
        $this->view->modules    = $this->translate->getModules();
        $this->view->statistics = $this->translate->getModuleStatistics($language);
        $this->view->language   = $language;

        $this->display();
    }

    /**
     * Translate selected language.
     * 
     * @param  string $zentaoVersion 
     * @param  string $language 
     * @param  string $module 
     * @param  string $consultLang 
     * @access public
     * @return void
     */
    public function module($language, $module, $referLang = '')
    {
        $moduleGroup = $this->translate->getModules();
        foreach($moduleGroup as $group => $modules)
        {
            if(in_array($module, $modules)) break;
        }
        if(empty($referLang))
        {
            $langs = json_decode($this->config->global->langs, true);
            if(isset($langs[$language]))
            {
                $referLang = $langs[$language]['reference'];
            }
            else
            {
                $referLang = $language == 'zh-cn' ? 'en' : 'zh-cn';
            }
        }
        $this->view->cmd = $this->translate->checkDirPriv($module);

        if($_POST)
        {
            $this->translate->addTranslation($language, $module, $referLang);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $referItems = $this->translate->getModuleLangs($module, $referLang);
        $inputVars  = count($referItems) * 3;
        if($inputVars > ini_get('max_input_vars')) $this->view->cmd = sprintf($this->lang->translate->notice->failMaxInput, $inputVars);

        $this->view->title      = $this->lang->translate->module;
        $this->view->position[] = html::a($this->createLink('translate', 'index'), $this->lang->translate->common);
        $this->view->position[] = $this->lang->translate->module;

        $this->view->referItems    = $referItems;
        $this->view->translations  = $this->translate->getTranslations($language, $module);
        $this->view->moduleGroup   = $moduleGroup;
        $this->view->currentModule = $module;
        $this->view->currentGroup  = $group;
        $this->view->language      = $language;
        $this->view->referLang     = $referLang;
        $this->display();
    }

    /**
     * Review translate. 
     * 
     * @param  string $language 
     * @param  string $module 
     * @param  string $referLang 
     * @access public
     * @return void
     */
    public function review($language, $module, $referLang = '')
    {
        $moduleGroup = $this->translate->getModules();
        foreach($moduleGroup as $group => $modules)
        {
            if(in_array($module, $modules)) break;
        }
        if(empty($referLang))
        {
            $langs = json_decode($this->config->global->langs, true);
            if(isset($langs[$language]))
            {
                $referLang = $langs[$language]['reference'];
            }
            else
            {
                $referLang = $language == 'zh-cn' ? 'en' : 'zh-cn';
            }
        }

        $this->view->title      = $this->lang->translate->review;
        $this->view->position[] = html::a($this->createLink('translate', 'index'), $this->lang->translate->common);
        $this->view->position[] = $this->lang->translate->review;

        $this->view->referItems    = $this->translate->getModuleLangs($module, $referLang);
        $this->view->translations  = $this->translate->getTranslations($language, $module);
        $this->view->moduleGroup   = $moduleGroup;
        $this->view->currentModule = $module;
        $this->view->currentGroup  = $group;
        $this->view->language      = $language;
        $this->view->referLang     = $referLang;
        $this->display();
    }

    /**
     * Set review result.
     * 
     * @param  int    $translationID 
     * @param  string $result 
     * @access public
     * @return void
     */
    public function result($translationID, $result)
    {
        if($result == 'pass')
        {
            $this->dao->update(TABLE_TRANSLATION)->set('status')->eq('reviewed')->set('reviewer')->eq($this->app->user->account)->set('reviewTime')->eq(helper::now())->where('id')->eq($translationID)->exec();
            die(js::reload());
        }
        if($result == 'reject' and empty($_POST)) die($this->display());
        if($result == 'reject' and $_POST)
        {
            $data = fixer::input('post')->get();
            if(empty($data->reason)) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->error->notempty, $this->lang->translate->reason)));
            $this->dao->update(TABLE_TRANSLATION)->set('status')->eq('rejected')->set('reviewer')->eq($this->app->user->account)->set('reviewTime')->eq(helper::now())->set('reason')->eq($data->reason)->where('id')->eq($translationID)->exec();
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.reloadStatus($translationID)"));
        }
    }

    /**
     * Batch pass for review
     * 
     * @access public
     * @return void
     */
    public function batchPass()
    {
        $this->dao->update(TABLE_TRANSLATION)->set('status')->eq('reviewed')->set('reviewer')->eq($this->app->user->account)->set('reviewTime')->eq(helper::now())->where('id')->in($this->post->idList)->exec();
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
    }

    /**
     * Setting review or not
     * 
     * @access public
     * @return void
     */
    public function setting()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->loadModel('setting')->setItem('system.translate.needReview', $data->needReview);
            die(js::reload('parent.parent'));
        }
        $this->display();
    }

    /**
     * Export translation.
     * 
     * @param  string $language 
     * @access public
     * @return void
     */
    public function export($language)
    {
        if($_POST)
        {
            $account      = $this->app->user->account;
            $cacheRoot    = $this->app->getCacheRoot();

            $zfile = $this->app->loadClass('zfile');
            if(is_dir($cacheRoot . "{$account}_lang/")) $zfile->removeDir($cacheRoot . "{$account}_lang/");

            $downloadFile = $cacheRoot . "{$account}_lang/zentao.zip";
            $downloadPath = $cacheRoot . "{$account}_lang/zentao/";
            mkdir($downloadPath, 0777, true);

            $langs     = json_decode($this->config->global->langs, true);
            $referLang = isset($langs[$language]) ? $langs[$language]['reference'] : '';

            $moduleRoot = $this->app->getModuleRoot();
            foreach(glob($moduleRoot . '/*') as $modulePath)
            {
                $moduleName = basename($modulePath);
                if(!is_dir($downloadPath . "module/{$moduleName}/lang/")) mkdir($downloadPath . "module/{$moduleName}/lang/", 0777, true);
                if(!file_exists($modulePath . "/lang/{$language}.php") and !empty($referLang) and file_exists($modulePath . "/lang/{$referLang}.php"))
                {
                    $this->translate->buildLangFile($language, $moduleName, $referLang);
                }
                if(file_exists($modulePath . "/lang/{$language}.php")) copy($modulePath . "/lang/{$language}.php", $downloadPath . "module/{$moduleName}/lang/{$language}.php");
            }

            $this->app->loadClass('pclzip', true);
            $zip = new pclzip($downloadFile);
            $zip->create($downloadPath, PCLZIP_OPT_REMOVE_PATH, dirname($downloadPath));

            $content = file_get_contents($downloadFile);
            if(is_dir($cacheRoot . "{$account}_lang/")) $zfile->removeDir($cacheRoot . "{$account}_lang/");

            $this->loadModel('file')->sendDownHeader($this->post->fileName . '.zip', 'zip', $content);
        }
        $this->app->loadLang('file');
        $this->display();
    }

    /**
     * Ajax get translation status.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function ajaxGetStatus($id)
    {
        $translation = $this->dao->select('*')->from(TABLE_TRANSLATION)->where('id')->eq($id)->fetch();

        $status = zget($this->lang->translate->statusList, $translation->status);
        if($translation->status == 'rejected') $status .= " <span title='{$translation->reason}'><i class='icon icon-help'></i></span>";
        die($status);
    }
}
