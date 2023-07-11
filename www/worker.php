<?php
/* Load the framework. */
include '../framework/zand/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

$app = zandRouter::createApp('pms', dirname(dirname(__FILE__)), 'zandRouter');

$common = $app->loadCommon();

if($_SERVER['RR_MODE'] === 'jobs')
{
    /* Jobs. */
    while($task = $app->consumer->waitTask())
    {
        if($task->getQueue() == 'crons')
        {
            $id   = $task->getValue('id');
            $type = $task->getValue('type');
            $cmd  = $task->getValue('command');

            if(!$id || !$type || !$cmd) continue;

            $output = '';
            $return = '';

            if($type == 'zentao')
            {
                parse_str($cmd, $params);
                if(!isset($params['moduleName']) || !isset($params['methodName'])) continue;

                $app->initRequest();
                $common->setUserConfig();

                $app->moduleName = $params['moduleName'];
                $app->methodName = $params['methodName'];
                $app->setControlFile();
                $app->loadModule();
                $output = ob_get_contents();

                $app->closeRequest();
            }
            elseif($type == 'system')
            {
                exec($cmd, $output, $return);
                if($output) $output = implode("\n", $output);
            }
        }

        $task->complete();
    }
}
else
{
    /* HTTP. */
    while(true)
    {
        try
        {
            $app->worker->waitRequest();
            $body = '';

            ob_start();

            /* installed or not. */
            if(!isset($config->installed) or !$config->installed) helper::header('location', 'install.php');

            /* Check for need upgrade. */
            $config->installedVersion = $app->getInstalledVersion();
            if($config->version != $config->installedVersion) helper::header('location', 'upgrade.php');

            $app->initRequest();
            $common->setUserConfig();

            /* Check the request is getconfig or not. */
            if(isset($_GET['mode']) and $_GET['mode'] == 'getconfig') helper::end(helper::removeUTF8Bom($app->exportConfig()));

            /* Remove install.php and upgrade.php. */
            if(file_exists('install.php') or file_exists('upgrade.php'))
            {
                $undeletedFiles = array();
                if(file_exists('install.php')) $undeletedFiles[] = '<strong style="color:#ed980f">install.php</strong>';
                if(file_exists('upgrade.php')) $undeletedFiles[] = '<strong style="color:#ed980f">upgrade.php</strong>';
                $wwwDir = dirname(__FILE__);
                if($undeletedFiles)
                {
                    echo "<html><head><meta charset='utf-8'></head>
                        <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>";
                    echo "<div style='margin-bottom:8px;'>安全起见，请删除 <strong style='color:#ed980f'>{$wwwDir}</strong> 目录下的 " . join(' 和 ', $undeletedFiles) . " 文件。</div>";
                    echo "<div>Please remove " . join(' and ', $undeletedFiles) . " under <strong style='color:#ed980f'>$wwwDir</strong> dir for security reason.</div>";
                    helper::end("</td></tr></table></body></html>");
                }
            }

            /* If client device is mobile and version is pro, set the default view as mthml. */
            if($app->clientDevice == 'mobile' and (strpos($config->version, 'pro') === 0 or strpos($config->version, 'biz') === 0 or strpos($config->version, 'max') === 0) and $config->default->view == 'html') $config->default->view = 'mhtml';
            if(!empty($_GET['display']) && $_GET['display'] == 'card') $config->default->view = 'xhtml';

            $app->parseRequest();

            if(!$app->setParams()) return;
            $common->checkPriv();

            if(!$common->checkIframe()) return;

            $app->loadModule();

            $body = helper::removeUTF8Bom(ob_get_clean());

            /* Flush the buffer. */
            $app->worker->respond($body);
        }
        catch(EndResponseException $e)
        {
            $body  = helper::removeUTF8Bom(ob_get_clean());
            $body .= $e->getContent();
            $app->worker->respond($body);
        }
        catch(Exception $e)
        {
            $app->worker->error($e);
        }

        $app->closeRequest();
    }
}
