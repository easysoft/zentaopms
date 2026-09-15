<?php
declare(strict_types=1);
/**
 * The control file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     gitfox
 * @link        https://www.zentao.net
 */
class gitfox extends control
{
    /**
     * Ajax方式获取项目分支。
     * AJAX: Get project branches.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetProjectBranches(int $repoID)
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(!$repo) return print(array());

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $branches = $scm->branch();

        $options = array();
        $options[] = array('text' => '', 'value' => '');
        foreach($branches as $branch)
        {
            $options[] = array('text' => $branch, 'value' => $branch);
        }
        return print(json_encode($options));
    }

    /**
     * DevOps 介绍页面。
     * DevOps introduction page.
     *
     * @access public
     * @param  int $isInstall
     * @return void
     */
    public function devopsIntroduction(int $isInstall = 0)
    {
        $adminRegisterLink = $this->app->cookie->lang == 'zh-cn' ? helper::createLink('admin', 'register') : helper::createLink('index');
        $devopsLink        = helper::createLink('gitfox', 'installGitFox', 'isInstall=' . $isInstall);

        if($isInstall)
        {
            $adminRegisterLink .= $this->config->requestType == 'GET' ? '&_single=1' : '?_single=1';
            $devopsLink        .= $this->config->requestType == 'GET' ? '&_single=1' : '?_single=1';
        }

        $this->view->adminRegisterLink = $adminRegisterLink;
        $this->view->title             = $this->lang->gitfox->devopsIntroduction;
        $this->view->devopsLink        = $devopsLink;
        $this->view->isInstall         = $isInstall;
        $this->display();
    }

    /**
     * 安装GitFox.
     * Install GitFox.
     *
     * @access public
     * @param  int $isInstall
     * @return void
     */
    public function installGitFox(string $inPage = 'devops', int $skipInstall = 0, string $fromVersion = '')
    {
        $nextLink = $this->app->cookie->lang == 'zh-cn' ? helper::createLink('admin', 'register') : helper::createLink('index');

        if($inPage == 'install')
        {
            $nextLink .= $this->config->requestType == 'GET' ? '&_single=1' : '?_single=1';
        }
        elseif($inPage == 'upgrade')
        {
            $nextLink = $this->createLink('upgrade', 'afterExec', "fromVersion={$fromVersion}&processed=no&skipMoveFile=yes&skipUpdateDocs=yes&skipUpdateDocTemplates=yes&skipUpdateProjectReports=yes&skipInstallGitFox=yes");
        }
        else
        {
            $devopsLink = $this->config->devopsLink ? $this->config->devopsLink : 'repo-maintain';
            list($devopsModule, $devopsMethod) = explode('-', $devopsLink);
            $nextLink = helper::createLink($devopsModule, $devopsMethod);
        }

        if($skipInstall)
        {
            return $this->locate($nextLink);
        }

        $script = $this->gitfoxZen->buildGitFoxScript('install');

        $this->view->title       = $this->lang->gitfox->installGitFox;
        $this->view->script      = $script;
        $this->view->nextLink    = $nextLink;
        $this->view->inPage      = $inPage;
        $this->view->fromVersion = $fromVersion;
        $this->display();
    }

    /**
     * 升级GitFox.
     * Upgrade GitFox.
     *
     * @param  string $inPage
     * @param  int    $skipUpgrade
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function upgradeGitFox(string $inPage = 'devops', int $skipUpgrade = 0, string $fromVersion = '')
    {
        if($inPage == 'upgradeStart')
        {
            $nextLink = $this->createLink('upgrade', 'backup');
        }
        elseif($inPage == 'upgrade')
        {
            $nextLink = $this->createLink('upgrade', 'afterExec', "fromVersion={$fromVersion}&processed=no&skipMoveFile=yes&skipUpdateDocs=yes&skipUpdateDocTemplates=yes&skipUpdateProjectReports=yes&skipInstallGitFox=yes&skipUpgradeGitFox=yes");
        }
        else
        {
            $devopsLink = $this->config->devopsLink ? $this->config->devopsLink : 'repo-maintain';
            list($devopsModule, $devopsMethod) = explode('-', $devopsLink);
            $nextLink = helper::createLink($devopsModule, $devopsMethod);
        }

        if($skipUpgrade)
        {
            $this->session->set('skipUpgradeGitFox', true);
            return $this->locate($nextLink);
        }

        $script = $this->gitfoxZen->buildGitFoxScript('upgrade');

        $health         = $this->gitfox->getHealth();
        $currentVersion = $health ? zget($health, 'version', '') : '';

        $this->view->title           = $this->lang->gitfox->upgradeGitFox;
        $this->view->script          = $script;
        $this->view->inPage          = $inPage;
        $this->view->fromVersion     = $fromVersion;
        $this->view->currentVersion  = $currentVersion === '' ? '' : 'v' . ltrim((string)$currentVersion, 'vV');
        $this->view->requiredVersion = 'v' . ltrim((string)$this->config->devops->gitfoxVersion, 'vV');
        $this->display();
    }

    /**
     * 检查 GitFox 服务器是否可用。
     * Check if GitFox server is available.
     *
     * @access public
     * @return void
     */
    public function ajaxCheckGitFoxHealth()
    {
        $health = $this->gitfox->getHealth();
        if(!$health || dao::isError()) return $this->send(array('result' => 'fail', 'message' => $this->lang->gitfox->serverFail));

        $status = $this->gitfox->checkHealth($health);
        return $this->send(array('result' => 'success', 'status' => $status, 'version' => zget($health, 'version', '')));
    }
}
