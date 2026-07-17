<?php
declare(strict_types=1);
/**
 * The model file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     ci
 * @link        https://www.zentao.net
 */
class ciModel extends model
{
    /**
     * Set menu.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function setMenu(int $repoID = 0)
    {
        if($repoID) $this->session->set('repoID', $repoID);

        $homeMenuModule = array('gitlab', 'gogs', 'gitea', 'jenkins', 'sonarqube', 'space');
        if(!in_array("{$this->app->moduleName}", $homeMenuModule)) common::setMenuVars($this->config->vision == 'devops' ? 'repo' : 'devops', (int)$this->session->repoID);

        if($this->session->repoID)
        {
            $repo = $this->loadModel('repo')->getByID($this->session->repoID);
            if(!$repo) unset($this->lang->devops->menu->tag);
            if(!$repo) unset($this->lang->devops->menu->branch);
            $this->session->set('devopsSpace', empty($repo) ? 0 : $repo->spaceID);
            $this->loadModel('common')->resetDevOpsPriv(empty($repo) ? 0 : $repo->spaceID);

            if($repo && $this->repo->isSvn($repo))
            {
                unset($this->lang->devops->menu->branch);
                unset($this->lang->devops->menu->tag);
                unset($this->lang->devops->menu->ppm);
                unset($this->lang->devops->menu->artifact);
                unset($this->lang->devops->menu->settings);
                unset($this->lang->devops->menu->review);
                unset($this->lang->devops->menu->repoCodeScan);
            }

            if($repo && !empty($repo->mirror))
            {
                unset($this->lang->devops->menu->repoCodeScan);
                unset($this->lang->devops->menu->review);
                unset($this->lang->devops->menu->settings);
            }
        }

        if($this->app->rawModule == 'pullreq') $this->lang->repo->menu->review['subMenu']->ppm['exclude'] = 'ppm-browse';
    }

    /**
     * 把ansi文本转换成html样式。
     * Transform ansi text to html.
     *
     * @param  string $text
     * @access public
     * @return string
     */
    public function transformAnsiToHtml(string $text): string
    {
        $text = preg_replace("/\x1B\[31;40m/", '<font style="color: red">',       $text);
        $text = preg_replace("/\x1B\[32;1m/",  '<font style="color: green">',     $text);
        $text = preg_replace("/\x1B\[32;1m/",  '<font style="color: green">',     $text);
        $text = preg_replace("/\x1B\[36;1m/",  '<font style="color: cyan">',      $text);
        $text = preg_replace("/\x1B\[0;33m/",  '<font style="color: yellow">',    $text);
        $text = preg_replace("/\x1B\[1m/",     '<font style="font-weight:bold">', $text);
        $text = preg_replace("/\x1B\[0;m/",    '</font><br>', $text);
        return preg_replace("/\x1B\[0K/",     '<br>', $text);
    }

    /**
     * 发起一个请求。
     * Send request.
     *
     * @param  string $url
     * @param  object $data
     * @param  string $userPWD
     * @access public
     * @return string|int
     */
    public function sendRequest(string $url, object $data, string $userPWD = ''): string|int
    {
        if(!empty($data->PARAM_TAG)) $data->PARAM_REVISION = '';
        $response = common::http($url, $data, array(CURLOPT_HEADER => true, CURLOPT_USERPWD => $userPWD));

        if(preg_match("!Location: .*item/(.*)/!i", $response, $matches)) return $matches[1];
        return 0;
    }

    /**
     * 根据ztf结果更新测试单。
     * Save test task for ztf.
     *
     * @param  string $testType  unit|func
     * @param  int    $productID
     * @param  int    $compileID
     * @param  int    $taskID
     * @param  string $name
     * @access public
     * @return int|bool
     */
    public function saveTestTaskForZtf(string $testType = 'unit', int $productID = 0, int $compileID = 0, int $taskID = 0, string $name = ''): int|bool
    {
        $this->loadModel('testtask');
        if(!empty($taskID))
        {
            $testtask = $this->testtask->getByID($taskID);
            if(!$testtask) return false;

            $this->dao->update(TABLE_TESTTASK)->set('auto')->eq(strtolower($testType))->where('id')->eq($taskID)->exec();
        }
        else
        {
            if(empty($productID)) return false;

            $lastProject = $this->dao->select('t2.id,t2.project')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.project')->ne('0')
                ->orderBy('t2.id desc')
                ->limit(1)
                ->fetch();

            $testtask = new stdclass();
            $testtask->product     = $productID;
            $testtask->name        = !empty($name) ? $name : sprintf($this->lang->testtask->titleOfAuto, date('Y-m-d H:i:s'));
            $testtask->owner       = $this->app->user->account;
            $testtask->project     = $lastProject ? $lastProject->project : 0;
            $testtask->execution   = $lastProject ? $lastProject->id : 0;
            $testtask->build       = 'trunk';
            $testtask->auto        = strtolower($testType);
            $testtask->begin       = date('Y-m-d');
            $testtask->end         = date('Y-m-d', time() + 24 * 3600);
            $testtask->status      = 'done';
            $testtask->createdBy   = $this->app->user->account;
            $testtask->createdDate = helper::now();

            $this->dao->insert(TABLE_TESTTASK)->data($testtask)->exec();
            $taskID = $this->dao->lastInsertId();
            $this->loadModel('action')->create('testtask', $taskID, 'opened');
        }

        if($compileID) $this->dao->update(TABLE_COMPILE)->set('testtask')->eq($taskID)->where('id')->eq($compileID)->exec();
        if(dao::isError()) return dao::isError();
        return $taskID;
    }
}
