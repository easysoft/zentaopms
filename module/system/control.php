<?php
declare(strict_types=1);
/**
 * The control file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.zentao.net
 */
class system extends control
{

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->loadModel('action');
        $this->loadModel('setting');
    }

    /**
     * 服务仪表盘。
     * Dashboard page.
     *
     * @param  int    $total
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function dashboard(int $total = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('cne');
        $this->app->loadClass('pager', true);
        $pager = new pager($total, $recPerPage, $pageID);

        $instances        = $this->loadModel('instance')->getList($pager, '', '', 'running');
        $instancesMetrics = $this->cne->instancesMetrics($instances);

        foreach($instances as $instance)
        {
            $metrics       = zget($instancesMetrics, $instance->id);
            $instance->cpu = $this->instance->printCpuUsage($instance, $metrics->cpu, 'array');
            $instance->mem = $this->instance->printMemUsage($instance, $metrics->memory, 'array');
        }

        $actions = $this->loadModel('action')->getDynamic('all', 'today');
        $cneMetrics = $this->cne->cneMetrics();

        $this->view->title      = $this->lang->my->common;
        $this->view->instances  = $instances;
        $this->view->actions    = $actions;
        $this->view->cneMetrics = $cneMetrics;
        $this->view->cpuInfo    = $this->systemZen->getCpuUsage($cneMetrics->metrics->cpu);
        $this->view->memoryInfo = $this->systemZen->getMemUsage($cneMetrics->metrics->memory);
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * 数据库列表。
     * Show database list.
     *
     * @access public
     * @return void
     */
    public function dbList()
    {
        $this->app->loadLang('instance');

        $this->view->title  = $this->lang->system->dbManagement;
        $this->view->dbList = $this->loadModel('cne')->allDBList();

        $this->display();
    }

    /**
     * 对象存储视图。
     * OSS view.
     *
     * @access public
     * @return void
     */
    public function ossView()
    {
        $this->loadModel('cne');

        $minioInstance = new stdclass;
        $minioInstance->k8name    = 'cne-operator';
        $minioInstance->spaceData = new stdclass;
        $minioInstance->spaceData->k8space = $this->config->k8space;

        $ossAccount = $this->cne->getDefaultAccount($minioInstance, 'minio');
        $ossDomain  = $this->cne->getDomain($minioInstance, 'minio');

        $this->view->title      = $this->lang->system->oss->common;
        $this->view->ossAccount = $ossAccount ? $ossAccount : new stdclass();
        $this->view->ossDomain  = $ossDomain;

        $this->display();
    }

    /**
     * 自定义域名配置。
     * Config customer's domain.
     *
     * @access public
     * @return void
     */
    public function configDomain()
    {
        $domainSettings = $this->system->getDomainSettings();
        if($domainSettings->customDomain) $this->locate($this->inLink('domainView'));
        $this->locate($this->inLink('editDomain'));
    }

    /**
     * 编辑自定义域名。
     * Edit customer's domain.
     *
     * @access public
     * @return void
     */
    public function editDomain()
    {
        if(!commonModel::hasPriv('system', 'configDomain')) $this->loadModel('common')->deny('system', 'configDomain', false);
        $this->loadModel('instance');

        if($_POST)
        {
            session_write_close();
            $settings = form::data($this->config->system->form->editDomain)
                ->setDefault('https', 'false')
                ->setIf(is_array($this->post->https) && in_array('true', $this->post->https), 'https', 'true')
                ->get();

            $this->system->saveDomainSettings($settings);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError(true)));

            return $this->send(array('result' => 'success', 'message' => $this->lang->system->notices->updateDomainSuccess, 'locate' => $this->inlink('domainView')));
        }

        $this->view->title          = $this->lang->system->domain->common;
        $this->view->domainSettings = $this->system->getDomainSettings();

        $this->display();
    }

    /**
     * 校验证书。
     * Ajax valid cert.
     *
     * @access public
     * @return void
     */
    public function ajaxValidateCert()
    {
        $certData = fixer::input('post')->get();

        if(!validater::checkREG($certData->customDomain, '/^((?!-)[a-z0-9-]{1,63}(?<!-)\\.)+[a-z]{2,6}$/'))
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->invalidDomain));
        }

        $certName = 'tls-' . str_replace('.', '-',$certData->customDomain);
        $result = $this->loadModel('cne')->validateCert($certName, $certData->certPem, $certData->certKey, $certData->customDomain);
        if($result->code == 200) return $this->send(array('result' => 'success', 'message' => $this->lang->system->notices->validCert));

        return $this->send(array('result' => 'fail', 'message' => $result->message));
    }

    /**
     * 更新域名的进度。
     * Show progress of updating domains.
     *
     * @access public
     * @return void
     */
    public function ajaxUpdatingDomainProgress()
    {
        session_write_close();

        $oldDomainQty  = $this->loadModel('instance')->countOldDomain();
        return print(sprintf($this->lang->system->domain->updatingProgress, $oldDomainQty));
    }

    /**
     * 域名设置视图。
     * Domain settings view.
     *
     * @access public
     * @return void
     */
    public function domainView()
    {
        $domainSettings = $this->system->getDomainSettings();
        $certName       = 'tls-' . str_replace('.', '-', $domainSettings->customDomain);
        $cert           = $this->loadModel('cne')->certInfo($certName);

        $notAfter = zget($cert, 'not_after', '');
        if($notAfter) $cert->expiredDate = date('Y-m-d H:i:s', $notAfter);

        $this->view->title          = $this->lang->system->domain->common;
        $this->view->domainSettings = $domainSettings;
        $this->view->cert           = $cert;

        $this->display();
    }

    /**
     * 生成数据库授权链接。
     * Generate database auth parameters and jump to login page.
     *
     * @access public
     * @return void
     */
    public function ajaxDBAuthUrl()
    {
        $post = fixer::input('post')
            ->setDefault('namespace', 'default')
            ->get();
        if(empty($post->dbName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->dbNameIsEmpty));

        $detail = $this->loadModel('cne')->dbDetail($post->dbName, $post->namespace);
        if(empty($detail)) return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->notFoundDB));

        $this->app->loadConfig('instance');
        $dbAuth = array();
        $dbAuth['driver']   = zget($this->config->instance->adminer->dbTypes, $post->dbType, '');
        $dbAuth['server']   = $detail->host . ':' . $detail->port;
        $dbAuth['username'] = $detail->username;
        $dbAuth['db']       = $detail->database;
        $dbAuth['password'] = $detail->password;

        $url = '/adminer?'. http_build_query($dbAuth);
        $this->send(array('result' => 'success', 'message' => '', 'data' => array('url' => $url)));
    }

    /**
     * 获取对象存储信息。
     * Get oss account and domain by ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxOssInfo()
    {
        $minioInstance = new stdclass;
        $minioInstance->k8name    = 'cne-operator';
        $minioInstance->spaceData = new stdclass;
        $minioInstance->spaceData->k8space = $this->config->k8space;

        $ossAccount = $this->loadModel('cne')->getDefaultAccount($minioInstance, 'minio');

        $ossDomain  = $this->cne->getDomain($minioInstance, 'minio');
        $ossDomain->domain = $ossDomain->access_host;

        $url = $this->loadModel('instance')->url($ossDomain);

        if($ossAccount and $ossDomain) return  $this->send(array('result' => 'success', 'message' => '', 'data' => array('account' => $ossAccount, 'url' => $url)));

        $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->failGetOssAccount));
    }
}
