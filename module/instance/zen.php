<?php
declare(strict_types=1);
/**
 * The zen file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     instance
 * @link        https://www.zentao.net
 */
class instanceZen extends instance
{
    /**
     * 查看商店应用详情。
     * Show instance view.
     *
     * @param  int    $id
     * @param  string $tab
     * @access public
     * @return void
     */
    protected function storeView(int $id, string $tab = 'baseinfo')
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);

        $instance = $this->instance->getByID($id);
        if(empty($instance)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->instance->instanceNotExists, 'locate' => $this->createLink('space', 'browse'))));

        $instance->latestVersion = $this->store->appLatestVersion($instance->appID, $instance->version);

        $instance = $this->instance->freshStatus($instance);

        $instanceMetric = $this->cne->instancesMetrics(array($instance));
        $instanceMetric = $instanceMetric[$instance->id];

        if($instance->status == 'running') $this->instance->saveAuthInfo($instance);
        if(in_array($instance->chart, $this->config->instance->devopsApps))
        {
            $url      = strstr(getWebRoot(true), ':', true) . '://' . $instance->domain;
            $pipeline = $this->loadModel('pipeline')->getByUrl($url);
            $instance->externalID = !empty($pipeline) ? $pipeline->id : 0;
        }

        $this->view->title           = $instance->appName;
        $this->view->instance        = $instance;
        $this->view->actions         = $this->loadModel('action')->getList('instance', $id);
        $this->view->defaultAccount  = $this->cne->getDefaultAccount($instance);
        $this->view->instanceMetric  = $instanceMetric;
        $this->view->dbList          = $this->cne->appDBList($instance);
    }

    /**
     * 自动保存devops应用授权信息。
     * Auto save auth info of devops.
     *
     * @param  object     $instance
     * @access protected
     * @return void
     */
    protected function saveAuthInfo(object $instance): void
    {
        if(!in_array($instance->chart, $this->config->instance->devopsApps)) return;

        $url      = strstr(getWebRoot(true), ':', true) . '://' . $instance->domain;
        $pipeline = $this->loadModel('pipeline')->getByUrl($url);
        if(!empty($pipeline)) return;

        $tempMappings = $this->loadModel('cne')->getSettingsMapping($instance);
        if(empty($tempMappings)) return;

        $pipeline = new stdclass();
        $instance->type        = $instance->chart;
        $pipeline->type        = $instance->type;
        $pipeline->private     = md5(strval(rand(10,113450)));
        $pipeline->createdBy   = 'system';
        $pipeline->createdDate = helper::now();
        $pipeline->url         = $url;
        $pipeline->name        = $this->generatePipelineName($instance);
        $pipeline->token       = zget($tempMappings, 'api_token', '');
        $pipeline->account     = zget($tempMappings, 'z_username', '');
        $pipeline->password    = zget($tempMappings, 'z_password', '');
        if($instance->appID == 60) $pipeline->token = base64_encode($pipeline->token . ':');
        if(empty($pipeline->account)) $pipeline->account = zget($tempMappings, 'admin_username', '');

        $this->pipeline->create($pipeline);
        if(dao::isError()) dao::getError();
    }

    /**
     * 自动保存devops应用授权信息。
     * Auto save auth info of devops.
     *
     * @param  object     $instance
     * @access protected
     * @return string
     */
    protected function generatePipelineName(object $instance): string
    {
        $name = $instance->name;
        $type = $instance->type;
        if(empty($this->loadModel('pipeline')->getByNameAndType($name, $type))) return $name;
        if(empty($this->loadModel('pipeline')->getByNameAndType($name . '-' . $instance->appVersion, $type))) return $name . '-' . $instance->appVersion;

        for($times = 1; $times < 5; $times ++)
        {
            if(empty($this->loadModel('pipeline')->getByNameAndType($name . '-' . $times, $name))) return $name . '-' . $times;
        }
    }

    /**
     * 检查安装应用时数据合法性
     * Check for install.
     *
     * @param  object $customData
     * @access public
     * @return void
     */
    protected function checkForInstall(object $customData)
    {
        if(isset($this->config->instance->keepDomainList[$customData->customDomain]) || $this->instance->domainExists($customData->customDomain)) return $this->send(array('result' => 'fail', 'message' => $customData->customDomain . $this->lang->instance->errors->domainExists));

        if(!$customData->customName)
        {
            dao::$errors['customName'] = sprintf($this->lang->error->notempty, $this->lang->instance->name);
            return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        if(!$this->instance->checkAppNameUnique($customData->customName))   return $this->send(array('result' => false, 'message' => array('customName' => sprintf($this->lang->error->repeat, $this->lang->instance->name, $customData->customName))));

        if(!validater::checkLength($customData->customDomain, 20, 2))       return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->domainLength));
        if(!validater::checkREG($customData->customDomain, '/^[a-z\d]+$/')) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->wrongDomainCharacter));
    }
}

