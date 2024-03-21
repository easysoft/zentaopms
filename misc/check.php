<?php
/**
 * This file is used to check the language items and actions.
 */
/* Define an emtpty control class as the base class of every module. */
class control {}
$config = new stdclass();
$config->global = new stdclass();
$config->global->flow  = 'full';
$config->programLink   = '-';
$config->productLink   = '-';
$config->projectLink   = '-';
$config->executionLink = '-';
$config->systemMode    = 'new';
$config->URAndSR       = true;
$config->systemScore   = true;

/* set module root path and included the resource of group module. */
$lang = new stdclass();
$lang->common             = new stdclass();
$lang->index              = new stdclass();
$lang->my                 = new stdclass();
$lang->todo               = new stdclass();
$lang->block              = new stdclass();
$lang->program            = new stdclass();
$lang->programplan        = new stdclass();
$lang->product            = new stdclass();
$lang->project            = new stdclass();
$lang->design             = new stdclass();
$lang->stage              = new stdclass();
$lang->scrum              = new stdclass();
$lang->waterfall          = new stdclass();
$lang->execution          = new stdclass();
$lang->kanban             = new stdclass();
$lang->story              = new stdclass();
$lang->requirement        = new stdclass();
$lang->release            = new stdclass();
$lang->branch             = new stdclass();
$lang->productplan        = new stdclass();
$lang->review             = new stdclass();
$lang->milestone          = new stdclass();
$lang->qa                 = new stdclass();
$lang->doc                = new stdclass();
$lang->system             = new stdclass();
$lang->testcase           = new stdclass();
$lang->testtask           = new stdclass();
$lang->testreport         = new stdclass();
$lang->score              = new stdclass();
$lang->auditplan          = new stdclass();
$lang->cm                 = new stdclass();
$lang->nc                 = new stdclass();
$lang->pssp               = new stdclass();
$lang->stakeholder        = new stdclass();
$lang->task               = new stdclass();
$lang->build              = new stdclass();
$lang->bug                = new stdclass();
$lang->company            = new stdclass();
$lang->dept               = new stdclass();
$lang->group              = new stdclass();
$lang->user               = new stdclass();
$lang->bi                 = new stdclass();
$lang->screen             = new stdclass();
$lang->pivot              = new stdclass();
$lang->chart              = new stdclass();
$lang->report             = new stdclass();
$lang->repo               = new stdclass();
$lang->jenkins            = new stdclass();
$lang->gitlab             = new stdclass();
$lang->gitea              = new stdclass();
$lang->gogs               = new stdclass();
$lang->mr                 = new stdclass();
$lang->compile            = new stdclass();
$lang->job                = new stdclass();
$lang->svn                = new stdclass();
$lang->git                = new stdclass();
$lang->subject            = new stdclass();
$lang->admin              = new stdclass();
$lang->convert            = new stdclass();
$lang->upgrade            = new stdclass();
$lang->action             = new stdclass();
$lang->backup             = new stdclass();
$lang->extension          = new stdclass();
$lang->custom             = new stdclass();
$lang->mail               = new stdclass();
$lang->cron               = new stdclass();
$lang->dev                = new stdclass();
$lang->editor             = new stdclass();
$lang->entry              = new stdclass();
$lang->webhook            = new stdclass();
$lang->message            = new stdclass();
$lang->search             = new stdclass();
$lang->devops             = new stdclass();
$lang->team               = new stdclass();
$lang->automation         = new stdclass();
$lang->personnel          = new stdclass();
$lang->testsuite          = new stdclass();
$lang->caselib            = new stdclass();
$lang->ci                 = new stdclass();
$lang->datatable          = new stdclass();
$lang->tree               = new stdclass();
$lang->api                = new stdclass();
$lang->file               = new stdclass();
$lang->misc               = new stdclass();
$lang->acl                = new stdclass();
$lang->curd               = new stdclass();
$lang->sonarqube          = new stdclass();
$lang->app                = new stdclass();
$lang->host               = new stdclass();
$lang->zahost             = new stdclass();
$lang->zanode             = new stdclass();
$lang->holiday            = new stdclass();
$lang->contact            = new stdclass();
$lang->dimension          = new stdclass();
$lang->space              = new stdclass();
$lang->store              = new stdclass();
$lang->instance           = new stdclass();
$lang->CNE                = new stdclass();
$lang->account            = new stdclass();
$lang->ops                = new stdclass();
$lang->domain             = new stdclass();
$lang->service            = new stdclass();
$lang->deploy             = new stdclass();
$lang->metric             = new stdclass();
$lang->ai                 = new stdclass();
$lang->aiapp              = new stdclass();
$lang->serverroom         = new stdclass();
$lang->programstakeholder = new stdclass();
$lang->researchplan       = new stdclass();
$lang->workestimation     = new stdclass();
$lang->gapanalysis        = new stdclass();
$lang->executionview      = new stdclass();
$lang->managespace        = new stdclass();
$lang->systemteam         = new stdclass();
$lang->systemschedule     = new stdclass();
$lang->systemeffort       = new stdclass();
$lang->systemdynamic      = new stdclass();
$lang->systemcompany      = new stdclass();
$lang->pipeline           = new stdclass();
$lang->devopssetting      = new stdclass();
$lang->featureswitch      = new stdclass();
$lang->importdata         = new stdclass();
$lang->systemsetting      = new stdclass();
$lang->staffmanage        = new stdclass();
$lang->modelconfig        = new stdclass();
$lang->featureconfig      = new stdclass();
$lang->doctemplate        = new stdclass();
$lang->notifysetting      = new stdclass();
$lang->bidesign           = new stdclass();
$lang->personalsettings   = new stdclass();
$lang->projectsettings    = new stdclass();
$lang->dataaccess         = new stdclass();
$lang->executiongantt     = new stdclass();
$lang->executionkanban    = new stdclass();
$lang->executionburn      = new stdclass();
$lang->executioncfd       = new stdclass();
$lang->executionstory     = new stdclass();
$lang->executionqa        = new stdclass();
$lang->executionsettings  = new stdclass();
$lang->generalcomment     = new stdclass();
$lang->generalping        = new stdclass();
$lang->generaltemplate    = new stdclass();
$lang->generaleffort      = new stdclass();
$lang->productsettings    = new stdclass();
$lang->projectreview      = new stdclass();
$lang->projecttrack       = new stdclass();
$lang->projectqa          = new stdclass();
$lang->holidayseason      = new stdclass();
$lang->codereview         = new stdclass();
$lang->repocode           = new stdclass();
$lang->projectbuild       = new stdclass();
$lang->projectrelease     = new stdclass();
$lang->projectstory       = new stdclass();

$lang->projectCommon   = '';
$lang->storyCommon     = '';
$lang->SRCommon        = '';
$lang->URCommon        = '';
$lang->productCommon   = '';
$lang->executionCommon = '';

$config = new stdclass();
$config->disabledFeatures = '';
$config->URAndSR          = '';
$config->systemScore      = '';
$config->programLink      = 'program-browse';
$config->productLink      = 'product-browse';
$config->projectLink      = 'project-browse';
$config->executionLink    = 'execution-all';
$config->systemMode       = 'ALM';
$config->edition          = 'open';

$config->featureGroup = new stdclass();
$config->featureGroup->my            = array('score');
$config->featureGroup->product       = array('roadmap', 'track', 'UR');
$config->featureGroup->scrum         = array();
$config->featureGroup->waterfall     = array();
$config->featureGroup->agileplus     = array();
$config->featureGroup->waterfallplus = array();
$config->featureGroup->assetlib      = array();
$config->featureGroup->other         = array('devops', 'kanban', 'setCode');

$moduleRoot = '../module/';
include '../framework/helper.class.php';
include $moduleRoot . '/group/lang/resource.php';
foreach(glob($moduleRoot . '/group/ext/lang/zh-cn/*.php') as $resourceFile) include $resourceFile;
foreach(glob('../xuanxuan/module/group/ext/lang/zh-cn/*.php') as $resourceFile) include $resourceFile;

$whiteList[] = 'report-annualdata';
$whiteList[] = 'api-getsessionid';
$whiteList[] = 'admin-setflow';
$whiteList[] = 'bug-buildtemplates';
$whiteList[] = 'bug-sendmail';
$whiteList[] = 'board-managechild';
$whiteList[] = 'custom-menu';
$whiteList[] = 'company-create';
$whiteList[] = 'company-delete';
$whiteList[] = 'file-buildexporttpl';
$whiteList[] = 'file-buildform';
$whiteList[] = 'file-printfiles';
$whiteList[] = 'file-export2csv';
$whiteList[] = 'file-export2xml';
$whiteList[] = 'file-export2html';
$whiteList[] = 'file-export2excel';
$whiteList[] = 'file-export2word';
$whiteList[] = 'file-senddownheader';
$whiteList[] = 'file-read';
$whiteList[] = 'help-field';
$whiteList[] = 'index-testext';
$whiteList[] = 'productplan-commonaction';
$whiteList[] = 'project-managechilds';
$whiteList[] = 'execution-tips';
$whiteList[] = 'execution-commonaction';
$whiteList[] = 'project-sendmail';
$whiteList[] = 'release-commonaction';
$whiteList[] = 'task-commonaction';
$whiteList[] = 'task-sendmail';
$whiteList[] = 'testtask-sendmail';
$whiteList[] = 'user-login';
$whiteList[] = 'im-login';
$whiteList[] = 'im-debug';
$whiteList[] = 'im-sysgetserverinfo';
$whiteList[] = 'user-deny';
$whiteList[] = 'user-logout';
$whiteList[] = 'user-setreferer';
$whiteList[] = 'svn-run';
$whiteList[] = 'git-run';
$whiteList[] = 'admin-ignore';
$whiteList[] = 'admin-register';
$whiteList[] = 'admin-win2unix';
$whiteList[] = 'admin-bind';
$whiteList[] = 'admin-certifyztemail';
$whiteList[] = 'admin-certifyztmobile';
$whiteList[] = 'admin-ztcompany';
$whiteList[] = 'story-commonaction';
$whiteList[] = 'story-sendmail';
$whiteList[] = 'webapp-ajaxaddview';
$whiteList[] = 'report-remind';
$whiteList[] = 'sso-auth';
$whiteList[] = 'sso-depts';
$whiteList[] = 'sso-users';
$whiteList[] = 'sso-login';
$whiteList[] = 'sso-logout';
$whiteList[] = 'sso-bind';
$whiteList[] = 'sso-getuserpairs';
$whiteList[] = 'sso-getbindusers';
$whiteList[] = 'sso-binduser';
$whiteList[] = 'sso-createuser';
$whiteList[] = 'sso-gettodolist';
$whiteList[] = 'mail-asyncsend';
$whiteList[] = 'user-reset';
$whiteList[] = 'product-showerrornone';
$whiteList[] = 'tutorial-start';
$whiteList[] = 'tutorial-index';
$whiteList[] = 'tutorial-quit';
$whiteList[] = 'tutorial-wizard';
$whiteList[] = 'my-buildcontactlists';
$whiteList[] = 'mail-ztcloud';
$whiteList[] = 'doc-diff';
$whiteList[] = 'testreport-commonaction';
$whiteList[] = 'testsuite-library';
$whiteList[] = 'testsuite-createlib';
$whiteList[] = 'testsuite-createcase';
$whiteList[] = 'testsuite-libview';
$whiteList[] = 'admin-log';
$whiteList[] = 'admin-deletelog';
$whiteList[] = 'custom-required';
$whiteList[] = 'custom-score';
$whiteList[] = 'custom-resetrequired';
$whiteList[] = 'entry-browse';
$whiteList[] = 'entry-create';
$whiteList[] = 'entry-edit';
$whiteList[] = 'entry-delete';
$whiteList[] = 'entry-log';
$whiteList[] = 'score-rule';
$whiteList[] = 'score-reset';
$whiteList[] = 'testsuite-batchcreatecase';
$whiteList[] = 'testsuite-exporttemplet';
$whiteList[] = 'testsuite-import';
$whiteList[] = 'testsuite-showimport';
$whiteList[] = 'webhook-asyncsend';
$whiteList[] = 'testreport-setchartdatas';
$whiteList[] = 'chat-login';
$whiteList[] = 'entry-visit';
$whiteList[] = 'ci-initqueue';
$whiteList[] = 'ci-exec';
$whiteList[] = 'ci-checkcompilestatus';
$whiteList[] = 'im-userlogin';
$whiteList[] = 'user-refreshRandom';

/* checking actions of every module. */
echo '-------------action checking-----------------' . "\n";
foreach(array($moduleRoot, '../xuanxuan/module/') as $subModuleRoot)
{
    foreach(glob($subModuleRoot . '*') as $modulePath)
    {
        $moduleName  = basename($modulePath);
        if(strpos('install|upgrade|convert|common|misc|editor', $moduleName) !== false) continue;
        $controlFile = $modulePath . '/control.php';
        if(file_exists($controlFile))
        {
            include $controlFile;
            if(class_exists($moduleName))
            {
                if($moduleName == 'block') continue;
                $class   = new ReflectionClass($moduleName);
                $methods = $class->getMethods();
                foreach($methods as $method)
                {
                    $methodRef = new ReflectionMethod($method->class, $method->name);
                    if($methodRef->isPublic() and strpos($method->name, '__') === false)
                    {
                        $methodName = $method->name;
                        if(in_array($moduleName . '-' . strtolower($method->name), $whiteList)) continue;
                        if(strpos($methodName, 'ajax') !== false) continue;

                        $exits = false;
                        if(isset($lang->resource->$moduleName))
                        {
                            foreach($lang->resource->$moduleName as $key => $label)
                            {
                                if(strtolower($methodName) == strtolower($key)) $exits = true;
                            }
                        }
                        if(!$exits) echo $moduleName . "\t" . $methodName . " not in the list. \n";
                    }
                }
            }
        }

        /* Checking extension files. */
        $extControlFiles = glob($modulePath . '/ext/control/*.php');
        if($extControlFiles)
        {
            foreach($extControlFiles as $extControlFile)
            {
                $methodFile = substr($extControlFile, strrpos($extControlFile, '/') + 1);
                $methodName = substr($methodFile, 0, strpos($methodFile, '.'));
                if(in_array($moduleName . '-' . strtolower($methodName), $whiteList)) continue;
                if(strpos($methodName, 'ajax') !== false) continue;

                $exits = false;
                foreach($lang->resource->$moduleName as $key => $label)
                {
                    if(strtolower($methodName) == strtolower($key)) $exits = true;
                }
                if(!$exits) echo $moduleName . "\t" . $methodName . " not in the list. \n";
            }
        }
    }
}

/* checking actions of every module. */
echo '-------------lang checking-----------------' . "\n";
include '../module/common/lang/zh-cn.php';
include '../config/config.php';
foreach(array($moduleRoot, '../xuanxuan/module/') as $subModuleRoot)
{
    foreach(glob($subModuleRoot . '*') as $modulePath)
    {
        unset($lang);
        $moduleName   = basename($modulePath);
        $mainLangFile = $modulePath . '/lang/zh-cn.php';
        if(!file_exists($mainLangFile)) continue;
        $mainLines = file($mainLangFile);

        foreach($config->langs as $langKey => $langName)
        {
            if($langKey == 'zh-cn' or $langKey == 'zh-tw') continue;
            $langFile = $modulePath . '/lang/' . $langKey . '.php';
            if(!file_exists($langFile)) continue;
            $lines = file($langFile);
            foreach($mainLines as $lineNO => $line)
            {
                if(!isset($lines[$lineNO]) OR empty(trim($lines[$lineNO]))) continue;
                if(empty(trim($line))) continue;
                if(strpos($line, '$lang') === 0)
                {
                    if(strpos($line, '=') !== false && strpos($lines[$lineNO], '=') !== false)
                    {
                        list($mainKey, $mainValue) = explode('=', $line);
                        list($key, $value) = explode('=', $lines[$lineNO]);
                    }
                    if((strpos($line, '=') === false and $line != $lines[$lineNO]) or trim($mainKey) != trim($key))
                    {
                        $key = trim($key);
                        $lineNO = $lineNO + 1;
                        $referLang = $langKey != 'en' ? 'en' : 'zh-cn';
                        echo "module $moduleName need checking, command is:";
                        echo " vimdiff -O +$lineNO ../module/$moduleName/lang/$referLang.php ../module/$moduleName/lang/$langKey.php \n";
                        break;
                    }
                }
            }
        }

        foreach(glob($modulePath . '/ext/lang/zh-cn/*.php') as $extMainLangFile)
        {
            $extMainLines = file($extMainLangFile);
            $extLangFile  = basename($extMainLangFile);
            $extEnFile    = $modulePath . '/ext/lang/en/' . $extLangFile;
            $extLines     = file($extEnFile);
            foreach($extMainLines as $lineNO => $line)
            {
                if(strpos($line, '$lang') === false)
                {
                    //if($line != $lines[$lineNO]) echo $moduleName . ' ' . $langKey . ' ' . $lineNO . "\n";
                }
                else
                {
                    list($mainKey, $mainValue) = explode('=', $line);
                    list($key, $value) = explode('=', $extLines[$lineNO]);
                    if(trim($mainKey) != trim($key))
                    {
                        $key = trim($key);
                        $lineNO = $lineNO + 1;
                        echo "module $moduleName need checking, command is:";
                        echo " vimdiff -O +$lineNO ../../module/$moduleName/ext/lang/zh-cn/$extLangFile ../../module/$moduleName/ext/lang/en/$extLangFile \n";
                        break;
                    }
                }
            }
        }
    }
}

echo '-------------demo data checking. -----------------' . "\n";
$demoSQL = file("../db/demo.sql");
foreach($demoSQL as $line => $sql)
{
    if(strpos($sql, 'INSERT') === false) continue;

    if(strpos($sql, $config->db->prefix . 'config')  !== false or
       strpos($sql, $config->db->prefix . 'company') !== false or
       strpos($sql, $config->db->prefix . 'group')   !== false)
    {
        die('line ' . ($line + 1) . " has error\n");
    }
}
