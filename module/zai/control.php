<?php
/**
 * The control file of zai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      tenghuaian <tenghuaian@chandao.com>
 * @link        https://www.zentao.net
 */
class zai extends control
{
    /**
     * 配置ZAI。
     * Configure ZAI.
     *
     * @param string $mode
     * @access public
     * @return void
     */
    public function setting($mode = 'view')
    {
        if(!empty($_POST))
        {
            $setting = new stdClass();
            $setting->appID      = trim($_POST['appID']);
            $setting->host       = trim($_POST['host']);
            $setting->port       = trim($_POST['port']);
            $setting->token      = trim($_POST['token']);
            $setting->adminToken = trim($_POST['adminToken']);

            if(empty($setting->host)) $setting = null;
            $this->zai->setSetting($setting);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('zai', 'setting')));
        }

        $setting = $this->zai->getSetting(true);
        if($mode == 'view')
        {
            if(!empty($setting->token)) $setting->token = str_repeat('*', strlen($setting->token));
            if(!empty($setting->adminToken)) $setting->adminToken = str_repeat('*', strlen($setting->adminToken));
        }

        $this->view->title   = $this->lang->zai->setting;
        $this->view->setting = $setting;
        $this->view->mode    = $mode;
        $this->display();
    }

    /**
     * Ajax: 获取当前用户的ZAI Authorization token。
     * Ajax: Get ZAI Authorization Token of current user.
     *
     * @access public
     * @return void
     */
    public function ajaxGetToken()
    {
        return $this->send($this->zai->getToken());
    }

    /**
     * 禅道数据向量化。
     * Vectorized data of ZenTao.
     *
     * @access public
     * @return void
     */
    public function vectorized()
    {
        if(!empty($_POST))
        {
            $result = $this->zai->enableVectorization();
            if($result['result'] !== 'success') return $this->send(array('result' => 'fail', 'message' => $result['message']));
            return $this->send(array('result' => 'success', 'message' => $result['message'], 'load' => true));
        }

        $info       = $this->zai->getVectorizedInfo();
        $zaiSetting = $this->zai->getSetting();
        $syncTypes  = $this->zai->getSyncTypes();

        $status         = empty($zaiSetting) ? 'unavailable' : $info->status;
        $syncFailed     = ($status === 'synced' && empty($info->syncedCount) && !empty($info->syncFailedCount));
        $displayStatus  = $syncFailed ? 'failed' : $status;
        $pendingEnqueue = ($status !== 'disabled' && $status !== 'unavailable') ? $this->zai->hasPendingEnqueueTargets() : false;

        $this->view->title          = $this->lang->zai->vectorized;
        $this->view->info           = $info;
        $this->view->zaiSetting     = $zaiSetting;
        $this->view->syncTypes      = $syncTypes;
        $this->view->status         = $status;
        $this->view->displayStatus  = $displayStatus;
        $this->view->syncFailed     = $syncFailed;
        $this->view->pendingEnqueue = $pendingEnqueue;
        $this->view->progressList   = $this->zai->buildProgressList($info, $syncTypes);
        $this->view->lastSyncTime   = $info->syncTime ? date('Y-m-d H:i:s', (int)$info->syncTime) : '';
        $this->display();
    }

    /**
     * Ajax: 启用数据向量化。
     * Ajax: Enable data vectorization.
     *
     * @access public
     * @return void
     */
    public function ajaxEnableVectorization()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->zai->onlyPostRequest));
        }

        $force  = isset($_POST['force']) && $_POST['force'] === 'true';
        $result = $this->zai->enableVectorization($force);
        return $this->send($result);
    }

    /**
     * Ajax: 分批将历史数据写入向量化队列。
     * Ajax: Enqueue historical targets into vectorization queue in batches.
     *
     * @param  string $type
     * @param  int    $lastID
     * @access public
     * @return void
     */
    public function ajaxEnqueueTargets(string $type = '', int $lastID = 0)
    {
        $result = $this->zai->batchEnqueueTargets($type, $lastID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if(!empty($result['finished']))
        {
            $this->zai->refreshVectorizedStatus();
            return $this->send(array('result' => 'finished', 'message' => $result['message']));
        }

        if(isset($result['result']) && $result['result'] === 'fail')
        {
            $message = isset($result['message']) ? $result['message'] : $this->lang->zai->syncRequestFailed;
            return $this->send(array('result' => 'fail', 'message' => $message));
        }

        return $this->send(array(
            'result'  => 'unfinished',
            'message' => $result['message'],
            'type'    => $result['type'],
            'count'   => $result['count'],
            'lastID'  => $result['lastID'],
            'next'    => $result['next']
        ));
    }

    /**
     * 计划任务：自动同步向量化数据（消费队列；必要时补历史入队）。
     * Cron: Auto sync vectorization data (consume queue; backfill enqueue if needed).
     *
     * @access public
     * @return void
     */
    public function syncVectorization()
    {
        $result = $this->zai->syncVectorization();
        if($result['result'] === 'skip')
        {
            echo "VECTORIZATION DISABLED";
            return;
        }

        echo "OK";
    }

    /**
     * Ajax: 搜索知识库。
     * Ajax: Search knowledge base.
     *
     * @param string $type 'chunk'（块） | 'content'（内容）
     * @param int    $limit
     * @access public
     * @return void
     */
    public function ajaxSearchKnowledges(string $type = 'chunk', int $limit = 5)
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->zai->onlyPostRequest));
        }

        $userPrompt = zget($_POST, 'userPrompt', '');
        $filters    = json_decode(zget($_POST, 'filters', '{}'), true);

        if(empty($userPrompt) || empty($filters)) return $this->send(array('result' => 'fail', 'message' => $this->lang->fail));

        $knowledges = $this->zai->searchKnowledgesInCollections($userPrompt, $filters, $type, $limit, 0.5);
        $results    = [];
        foreach($knowledges as $knowledge)
        {
            if($type === 'chunk')
            {
                $results[] = ['key' => $knowledge['content_key'], 'similarity' => $knowledge['similarity'], 'id' => $knowledge['chunk_id'], 'content' => $knowledge['chunk_content'], 'attrs' => $knowledge['content_attrs']];
            }
            else
            {
                $results[] = ['key' => $knowledge['key'], 'similarity' => $knowledge['similarity'], 'id' => $knowledge['id'], 'content' => $knowledge['content'], 'attrs' => $knowledge['attrs']];
            }
            if(count($results) >= $limit) break;
        }

        return $this->send(array('result' => 'success', 'data' => $results));
    }

    /**
     * Ajax: 获取当前用户的ZAI agent。
     * Ajax: Get ZAI agent of current user.
     *
     * @param string $type 'custom' | 'executor'
     * @access public
     * @return void
     */
    public function ajaxGetUserAgent(string $type = '')
    {
        if(!in_array($type, array('custom', 'executor'))) $type = '';

        $userAgent = $this->zai->getUserAgent($type);
        if(!$userAgent) $userAgent = $this->zai->createUserAgent($this->app->user->account, $type);

        return $this->send(array('result' => 'success', 'data' => $userAgent));
    }
}
