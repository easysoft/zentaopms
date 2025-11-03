<?php
/**
 * The control file of zai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        $info = $this->zai->getVectorizedInfo();

        $this->view->info       = $info;
        $this->view->title      = $this->lang->zai->vectorized;
        $this->view->zaiSetting = $this->zai->getSetting();
        $this->view->syncTypes  = zaiModel::getSyncTypes();
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
            return $this->send(array('result' => 'failed', 'message' => $this->lang->zai->onlyPostRequest));
        }

        $force  = isset($_POST['force']) && $_POST['force'] === 'true';
        $result = $this->zai->enableVectorization($force);
        return $this->send($result);
    }

    /**
     * Ajax: 同步禅道向量化数据到 ZAI 知识库。
     * Ajax: Sync vectorized data of ZenTao to ZAI knowledge base.
     *
     * @access public
     * @return void
     */
    public function ajaxSyncVectorization()
    {
        $info = $this->zai->getVectorizedInfo();
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            return $this->send(array('result' => 'success', 'data' => $info));
        }

        if(empty($info->key)) return $this->send(array('result' => 'failed', 'message' => $this->lang->zai->vectorizedUnavailableHint, 'data' => $info));

        $force = isset($_POST['force']) && $_POST['force'] === 'true';
        if($info->status === 'synced' && $force)
        {
            $info->status          = 'wait';
            $info->syncedTime      = 0;
            $info->syncedCount     = 0;
            $info->syncFailedCount = 0;
            $info->syncTime        = 0;
            $info->syncingType     = zaiModel::getNextSyncType();
            $info->syncingID       = 0;
            $info->syncDetails     = new stdClass();
        }

        if($info->status !== 'wait' && $info->status !== 'syncing')
        {
            return $this->send(array('result' => 'success', 'data' => $info));
        }

        if($info->status !== 'syncing')
        {
            $info->status = 'syncing';
            $info->synced = 0;
        }

        $info->syncTime = time();
        $result = $this->zai->syncNextTarget($info->key, $info->syncingType, $info->syncingID);

        $syncingType = $info->syncingType;
        if(!isset($info->syncDetails->$syncingType))
        {
            $syncDetail = new stdClass();
            $syncDetail->failed = 0;
            $syncDetail->synced = 0;
            $info->syncDetails->$syncingType = $syncDetail;
        }
        if($result)
        {
            if(isset($result['fatal']) && $result['fatal'])
            {
                $info->status = 'synced';
                $info->syncFailedCount++;
                $info->syncDetails->$syncingType->failed++;
                $this->zai->setVectorizedInfo($info);
                return $this->send(array('result' => 'failed', 'message' => $result['message'], 'data' => $info, '$result' => $result));
            }
            if($result['result'] == 'success')
            {
                $info->syncedCount++;
                $info->syncDetails->$syncingType->synced++;
            }
            else
            {
                $info->syncFailedCount++;
                $info->syncDetails->$syncingType->failed++;
            }
            $info->syncingID = (isset($result['id']) ? $result['id'] : $info->syncingID) + 1;
        }
        else
        {
            $nextSyncType = zaiModel::getNextSyncType($syncingType);
            if(empty($nextSyncType))
            {
                $info->status      = 'synced';
                $info->syncedTime  = $info->syncTime;
                $info->syncingID   = 0;
                $info->syncingType = zaiModel::getNextSyncType();
            }
            else
            {
                $info->syncingType = $nextSyncType;
                $info->syncingID   = 0;
            }
        }
        $this->zai->setVectorizedInfo($info);
        return $this->send(array('result' => 'success', 'data' => $info));
    }

    /**
     * Ajax: 搜索知识库。
     * Ajax: Search knowledge base.
     *
     * @access public
     * @return void
     */
    public function ajaxSearchKnowledges(int $limit = 10)
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            return $this->send(array('result' => 'failed', 'message' => $this->lang->zai->onlyPostRequest));
        }

        $userPrompt = zget($_POST, 'userPrompt', '');
        $filters    = json_decode(zget($_POST, 'filters', '{}'), true);

        if(empty($userPrompt) || empty($filters)) return $this->send(array('result' => 'failed', 'message' => $this->lang->fail));

        $contents = array();
        foreach($filters as $collection => $setting)
        {
            $key = $this->zai->getCollectionKey($collection);
            if(empty($key)) continue;
            $searchContents = $this->zai->searchKnowledges($userPrompt, $key, $setting, $limit + 10);
            if($searchContents) $contents = array_merge($contents, $searchContents);
        }

        $prompts = array();
        array_multisort(array_column($contents, 'similarity'), SORT_DESC, $contents);
        foreach($contents as $content)
        {
            if(isset($content['key']))
            {
                [$objectType, $objectID] = explode('-', $content['key']);
                if(!$this->zai->isCanViewObject($objectType, $objectID, empty($content['attrs']) ? null : $content['attrs'])) continue;
            }

            $prompts[] = $content['content'];
            if(count($prompts) >= $limit) break;
        }

        return $this->send(array('result' => 'success', 'data' => array('prompt' => implode("\n\n", $prompts))));
    }
}
