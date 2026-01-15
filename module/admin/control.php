<?php
declare(strict_types=1);
/**
 * The control file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: control.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class admin extends control
{
    /**
     *
     * @access public
     * @return void
     */
    public function index()
    {
        set_time_limit(0);

        /* 设置导航信息。*/
        /* Set the nav. */
        $this->admin->setMenu();

        /* 处理社区登记。*/
        /* Process community registration. */
        $community = zget($this->config->global, 'community', '');
        if(!$community || $community == 'na')
        {
            $this->view->bind    = false;
            $this->view->ignore  = $community == 'na';
        }
        else
        {
            $this->view->bind    = true;
            $this->view->ignore  = false;
        }

        $this->view->title       = $this->lang->admin->common;
        $this->view->zentaoData  = $this->adminZen->getZentaoData();
        $this->view->dateUsed    = $this->admin->genDateUsed();
        $this->view->hasInternet = $this->admin->checkInternet();
        $this->display();
    }

    /**
     * 获取禅道官网数据。
     * Get zentao.net data by api.
     *
     * @access public
     * @return void
     */
    public function ajaxSetZentaoData()
    {
        session_write_close();
        if(helper::isIntranet()) return $this->send(array('result' => 'ignore'));

        if($this->admin->checkInternet())
        {
            $lastSyncDate = !empty($this->config->zentaoWebsite->lastSyncDate) ? $this->config->zentaoWebsite->lastSyncDate : '';
            $nextWeek     = date('Y-m-d', strtotime('-7 days'));

            $needSync   = empty($lastSyncDate) || $lastSyncDate <= $nextWeek;
            $zentaoData = $this->adminZen->getZentaoData();

            if($needSync || empty($zentaoData->plugins))  $this->adminZen->syncExtensions('plugin', 6);
            if($needSync || empty($zentaoData->patches))  $this->adminZen->syncExtensions('patch', 5);
            if($needSync || empty($zentaoData->dynamics)) $this->adminZen->syncDynamics(3);
            if($needSync || empty($zentaoData->classes))  $this->adminZen->syncPublicClasses(3);

            if($needSync) $this->loadModel('setting')->setItem('system.common.zentaoWebsite.lastSyncDate', date('Y-m-d'));
        }

        return $this->send(array('result' => 'success'));
    }

    /**
     * 系统安全设置。
     * System security Settings.
     *
     * @access public
     * @return void
     */
    public function safe()
    {
        if($_POST)
        {
            $data = form::data()->get();
            $this->loadModel('setting')->setItems('system.common.safe', $data);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $this->view->title  = $this->lang->admin->safe->common . $this->lang->hyphen . $this->lang->admin->safe->set;
        $this->view->gdInfo = function_exists('gd_info') ? gd_info() : array();
        $this->display();
    }

    /**
     * 弱口令扫描。
     * Check weak user.
     *
     * @access public
     * @return void
     */
    public function checkWeak()
    {
        $this->view->title     = $this->lang->admin->safe->common . $this->lang->hyphen . $this->lang->admin->safe->checkWeak;
        $this->view->weakUsers = $this->loadModel('user')->getWeakUsers();
        $this->display();
    }

    /**
     * ZDOO集成。
     * Config sso for ranzhi.
     *
     * @access public
     * @return void
     */
    public function sso()
    {
        if(!empty($_POST))
        {
            $data = form::data()->get();
            if(!$data->turnon) $data->redirect = $data->turnon;
            $this->loadModel('setting')->setItems('system.sso', $data);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('sso')));
        }

        $this->loadModel('sso');
        if(!isset($this->config->sso)) $this->config->sso = new stdclass();

        $this->view->title    = $this->lang->admin->sso;
        $this->view->turnon   = isset($this->config->sso->turnon)   ? $this->config->sso->turnon   : 1;
        $this->view->redirect = isset($this->config->sso->redirect) ? $this->config->sso->redirect : 0;
        $this->view->addr     = isset($this->config->sso->addr)     ? $this->config->sso->addr     : '';
        $this->view->key      = isset($this->config->sso->key)      ? $this->config->sso->key      : '';
        $this->view->code     = isset($this->config->sso->code)     ? $this->config->sso->code     : '';
        $this->display();
    }

    /**
     * 系统功能配置。
     * Set closed features config.
     *
     * @access public
     * @return void
     */
    public function setModule()
    {
        $this->loadModel('setting');
        $this->loadModel('project');

        if($_POST)
        {
            $data           = form::data()->get();
            $closedFeatures = array();
            foreach($this->config->featureGroup as $group => $features)
            {
                foreach($features as $feature)
                {
                    $code = $group . ucfirst($feature);
                    if(empty($data->module[$code])) $closedFeatures[] = $code;
                }
            }

            foreach($closedFeatures as $closedFeature)
            {
                if(in_array($closedFeature, array('productER', 'productUR')))
                {
                    $this->project->unlinkStoryByType(0, $closedFeature == 'productER' ? 'epic' : 'requirement');
                }

                /* 关闭项目变更后将交付物解冻。*/
                if($closedFeature == 'projectChange')
                {
                    $deliverableReviewPairs = $this->admin->getdeliverableReviewPairs();
                    if(!empty($deliverableReviewPairs)) $this->loadModel('deliverable')->setFrozen(array_filter($deliverableReviewPairs), '', 'all');
                }
            }

            $enableER = $this->config->edition == 'ipd' ? 1 : zget($data->module, 'productER', 0);
            $URAndSR  = $this->config->edition == 'ipd' ? 1 : zget($data->module, 'productUR', 0);

            $this->setting->setItem('system.common.closedFeatures', implode(',', $closedFeatures));
            $this->setting->setItem('system.common.global.scoreStatus', zget($data->module, 'myScore', 0));
            $this->setting->setItem('system.custom.enableER', $enableER);
            $this->setting->setItem('system.custom.URAndSR',  $URAndSR);
            $this->setting->setItem('system.common.setCode', in_array('otherSetCode', $closedFeatures) ? 0 : 1);
            $this->loadModel('custom')->processMeasrecordCron();
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => '$.apps.updateAppsMenu(true);loadCurrentPage();'));
        }

        $closedFeatures = $this->setting->getItem('owner=system&module=common&section=&key=closedFeatures');
        if(empty($this->config->setCode) && strpos(",{$closedFeatures},", ',otherSetCode,') === false) $closedFeatures .= ',otherSetCode';

        $this->view->title            = $this->lang->admin->setModuleIndex;
        $this->view->closedFeatures   = $closedFeatures;
        $this->view->useScore         = $this->setting->getItem('owner=system&module=common&global&key=scoreStatus');
        $this->view->disabledFeatures = $this->setting->getItem('owner=system&module=common&section=&key=disabledFeatures');
        $this->display();
    }

    /**
     * 认证邮箱。
     * Certify ztEmail.
     *
     * @param  string $email
     * @access public
     * @return void
     */
    public function certifyZtEmail(string $email = '')
    {
        if($_POST)
        {
            $response = $this->adminZen->certifyByAPI('mail');
            $response = json_decode($response);
            if($response->result == 'fail') return print(js::alert($response->message));
            return print(js::locate($this->createLink('mail', 'ztCloud'), 'parent'));
        }

        $this->view->title = $this->lang->admin->certifyEmail;
        $this->view->email = helper::safe64Decode($email);
        $this->display();
    }

    /**
     * 认证手机。
     * Certify ztMobile.
     *
     * @param  string $mobile
     * @access public
     * @return void
     */
    public function certifyZtMobile(string $mobile = '')
    {
        if($_POST)
        {
            $response = $this->adminZen->certifyByAPI('mobile');
            $response = json_decode($response);
            if($response->result == 'fail') return print(js::alert($response->message));
            return print(js::locate($this->createLink('mail', 'ztCloud'), 'parent'));
        }

        $this->view->title  = $this->lang->admin->certifyMobile;
        $this->view->mobile = helper::safe64Decode($mobile);
        $this->display();
    }

    /**
     * 认证公司。
     * Set ztCompany.
     *
     * @param  string $fields
     * @access public
     * @return void
     */
    public function ztCompany(string $fields = 'company')
    {
        if($_POST)
        {
            $response = $this->adminZen->setCompanyByAPI();
            $response = json_decode($response);
            if($response->result == 'fail') return print(js::alert($response->message));
            return print(js::locate($this->createLink('mail', 'ztCloud'), 'parent'));
        }

        $this->view->title  = $this->lang->admin->ztCompany;
        $this->view->fields = explode(',', $fields);
        $this->display();
    }

    /**
     * 获取验证码。
     * Get the verification code.
     *
     * @param  string $type mobile|email
     * @access public
     * @return void
     */
    public function ajaxSendCode(string $type)
    {
        return print($this->adminZen->sendCodeByAPI($type));
    }

    /**
     * 设置日志保存天数。
     * Set save days of log.
     *
     * @access public
     * @return void
     */
    public function log()
    {
        if($_POST)
        {
            $days = form::data()->get()->days;
            if(!validater::checkInt($days)) return $this->send(array('result' => 'fail', 'message' => array('days' => sprintf($this->lang->admin->notice->int, $this->lang->admin->days))));

            $this->loadModel('setting')->setItem('system.admin.log.saveDays', (string)$days);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->app->loadLang('webhook');

        $this->view->title = $this->lang->webhook->setting;
        $this->display();
    }

    /**
     * 删除过期日志。
     * Delete logs older than save days.
     *
     * @access public
     * @return bool
     */
    public function deleteLog()
    {
        $date = date(DT_DATE1, strtotime("-{$this->config->admin->log->saveDays} days"));
        $this->dao->delete()->from(TABLE_LOG)->where('date')->lt($date)->exec();
        return !dao::isError();
    }

    /**
     * 重置密码设置。
     * Reset password setting.
     *
     * @access public
     * @return void
     */
    public function resetPWDSetting()
    {
        if($_POST)
        {
            $resetPWDByMail = form::data()->get()->resetPWDByMail;
            $this->loadModel('setting')->setItem('system.common.resetPWDByMail', (string)$resetPWDByMail);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $this->view->title = $this->lang->admin->resetPWDSetting;
        $this->display();
    }

    /**
     * 表引擎。
     * Show table engine.
     *
     * @access public
     * @return void
     */
    public function tableEngine()
    {
        $this->view->title        = $this->lang->admin->tableEngine;
        $this->view->tableEngines = $this->dao->getTableEngines();
        $this->display();
    }

    /**
     * 更新度量库表的索引。
     * Upgrade metriclib table index.
     *
     * @param  int    $key
     * @access public
     * @return void
     */
    public function metriclib(int $key = 0)
    {
        $sql = $this->config->admin->metricLib->updateSQLs[$key] ?? '';
        if($sql)
        {
            set_time_limit(0);
            session_write_close();

            try
            {
                $this->dbh->exec($sql);
            }
            catch(PDOException $e){}
            if(isset($this->config->admin->metricLib->updateSQLs[++$key])) return $this->send(['result' => 'success', 'key' => $key]);
            return $this->send(['result' => 'success']);
        }

        $this->view->title = $this->lang->metriclib->common;
        $this->display();
    }

    /**
     * 更换表引擎为InnoDB。
     * Ajax change table engine.
     *
     * @access public
     * @return void
     */
    public function ajaxChangeTableEngine()
    {
        $response = array();
        $response['result']    = 'success';
        $response['message']   = '';
        $response['thisTable'] = '';
        $response['nextTable'] = '';

        $tableEngines = $this->dao->getTableEngines();

        $thisTable = '';
        $nextTable = '';
        foreach($tableEngines as $table => $engine)
        {
            if($engine == 'InnoDB') continue;
            if(strpos(",{$this->session->errorTables},", ",{$table},") !== false) continue;

            if(stripos($table, 'searchindex') !== false)
            {
                $mysqlVersion = $this->loadModel('install')->getDatabaseVersion();
                if($mysqlVersion < 5.6) continue;
            }

            if($thisTable && empty($nextTable)) $nextTable = $table;
            if(empty($thisTable)) $thisTable = $table;
            if($thisTable && $nextTable) break;
        }

        if(empty($thisTable))
        {
            unset($_SESSION['errorTables']);
            $response['result'] = 'finished';
            return $this->send($response);
        }

        try
        {
            /* Check process this table or not. */
            $dbProcesses = $this->dbh->query("SHOW PROCESSLIST")->fetchAll();
            foreach($dbProcesses as $dbProcess)
            {
                if($dbProcess->db != $this->config->db->name) continue;
                if(!empty($dbProcess->Info) && strpos($dbProcess->Info, " {$thisTable} ") !== false)
                {
                    $response['message'] = sprintf($this->lang->admin->changingTable, $thisTable);
                    return $this->send($response);
                }
            }
        }
        catch(PDOException $e){}

        $response['thisTable'] = $thisTable;
        $response['nextTable'] = $nextTable;

        try
        {
            $sql = "ALTER TABLE `$thisTable` ENGINE='InnoDB'";
            $this->dbh->exec($sql);
            $response['message'] = sprintf($this->lang->admin->changeSuccess, $thisTable);
        }
        catch(PDOException $e)
        {
            $this->session->set('errorTables', $this->session->errorTables . ',' . $thisTable);

            $response['result']  = 'fail';
            $response['message'] = sprintf($this->lang->admin->changeFail, $thisTable, htmlspecialchars($e->getMessage()));
        }

        return $this->send($response);
    }

    /**
     * 设置1.5级后台下拉菜单。
     * AJAX: Get drop menu.
     *
     * @param  string $currentMenuKey
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(string $currentMenuKey = '')
    {
        $this->admin->checkPrivMenu();

        $data = array();
        foreach($this->lang->admin->menuList as $menuKey => $menuGroup)
        {
            if($this->config->vision == 'lite' && !in_array($menuKey, $this->config->admin->liteMenuList)) continue;

            $data[] = array(
                'id'         => $menuKey,
                'name'       => $menuKey,
                'content'    => array('html' => "<div class='flex items-center my-0.5'>" . (!empty($menuGroup['icon']) ? "<i class='icon icon-{$menuGroup['icon']} svg-icon mr-2 rounded-lg content-center bg-{$menuGroup['bg']} text-white'></i>" : "<img class='mr-2' src='static/svg/admin-{$menuKey}.svg'/>") . " {$menuGroup['name']}</div>"),
                'text'       => $menuGroup['name'],
                'titleClass' => 'hidden',
                'type'       => 'item',
                'disabled'   => $menuGroup['disabled'],
                'url'        => $menuGroup['link'],
                'active'     => $currentMenuKey == $menuKey,
                'rootClass'  => 'admin-menu-item',
                'attrs'      => array('disabled' => $menuGroup['disabled'])
            );
        }

        $this->view->data = $data;
        $this->display();
    }

    /**
     * 将队列中的SQL语句同步到SQLite中。
     * Execute sql from SQLite queue.
     *
     * @access public
     * @return void
     */
    public function execSqliteQueue()
    {
        $now = helper::now();
        $sqlite = $this->app->connectSqlite();

        $querys = $this->dao->select('*')->from(TABLE_SQLITE_QUEUE)->where('status')->eq('wait')->fetchAll();

        $sqlite->beginTransaction();
        foreach($querys as $query)
        {
            $sqlite->exec($query->sql);
            $this->dao->update(TABLE_SQLITE_QUEUE)
                ->set('status')->eq('done')
                ->set('execDate')->eq($now)
                ->where('id')->eq($query->id)
                ->exec();
        }
        $sqlite->commit();

        echo 'success';
    }

    /**
     *  绑定社区账号。
     *  Bind community account.
     *
     * @access public
     * @return void
     */
    public function register()
    {
        if($this->loadModel('user')->isLogon() && !$this->app->user->admin) $this->locate(helper::createLink('user', 'deny', 'module=admin&method=register'));

        $bindCommunity = $this->config->admin->register->bindCommunity == 'true';
        $agreeUX       = $this->config->admin->register->agreeUX == 'true';

        $this->view->bindCommunity = $bindCommunity;
        $this->view->agreeUX       = $agreeUX;

        if($bindCommunity)
        {
            if(!$this->loadModel('user')->isLogon()) $this->locate(helper::createLink('user', 'deny', 'module=admin&method=register'));
            $bindCommunityMobile = $this->config->admin->register->bindCommunityMobile;
            $this->view->bindCommunityMobile = $bindCommunityMobile;
        }
        else
        {
            if(!empty($_POST))
            {
                $data = form::data($this->config->admin->form->register)->get();

                $apiRoot    = $this->config->admin->register->apiRoot;
                $apiURL     = $apiRoot . "/user-apiRegister.json";

                if(empty($this->config->global->sn)) $this->loadModel('setting')->setSN();
                $httpData['sn']     = $this->config->global->sn;
                $httpData['mobile'] = $data->mobile;
                $httpData['code']   = $data->code;
                $httpData['token']  = md5(session_id());

                $response = common::http($apiURL, $httpData);

                $response = json_decode($response, true);

                if(isset($response['result']) && $response['result'] == 'success')
                {
                    $this->loadModel('setting')->setItem('system.admin.register.bindCommunity', 'true');
                    $this->loadModel('setting')->setItem('system.admin.register.bindCommunityMobile', $data->mobile);
                    $this->config->admin->register->bindCommunity = 'true';
                    $this->config->admin->register->bindCommunityMobile = $data->mobile;

                    $agreeUX = $data->agreeUX;
                    $agreeUX = $agreeUX == 'on' ? 'true' : 'false';
                    $this->loadModel('setting')->setItem('system.admin.register.agreeUX', $agreeUX);
                    $this->config->admin->agreeUX = $agreeUX;

                    $eventData = new stdClass();
                    $eventData->fingerprint = $data->fingerprint;
                    $eventData->location    = 'join-community';
                    $this->loadModel('misc')->sendInstallEvent($eventData);

                    $callBack = $this->loadModel('user')->isLogon() ? 'loadToRegister()' : 'loadToIndex()';

                    return $this->send(array('result' => 'success', 'message' => $this->lang->admin->community->joinSuccess, 'callback' => $callBack));
                }
                return $this->send(array('result' => 'fail', 'message' => isset($response['message']) ? $response['message'] : $this->lang->admin->community->loginFailed));
            }
        }

        $this->view->title = $this->lang->admin->community->registerTitle;
        $this->display();
    }

    /**
     *  解绑社区账号
     *  Unbind community account。
     *
     * @access public
     * @return void
     */
    public function unBindCommunity()
    {
        $this->loadModel('setting')->setItem('system.admin.register.bindCommunity', 'false');
        $this->loadModel('setting')->setItem('system.admin.register.bindCommunityMobile', '');
        $this->loadModel('setting')->setItem('system.admin.register.agreeUX', 'false');
        $this->config->admin->register->bindCommunity       = 'false';
        $this->config->admin->register->bindCommunityMobile = '';
        $this->config->admin->register->agreeUX             = 'false';
        return $this->send(array('result' => 'success', 'message' => $this->lang->admin->community->unBind->success, 'load' => inlink('register')));
    }

    /**
     *  切换同意改进计划
     *  Change the agreement to improve the plan。
     *
     * @access public
     * @return void
     */
    public function changeAgreeUX()
    {
        $agreeUX = $this->post->agreeUX;
        $this->loadModel('setting')->setItem('system.admin.register.agreeUX', $agreeUX);
        $this->config->admin->register->agreeUX = $agreeUX;
        $message = $agreeUX == 'true' ? $this->lang->admin->community->uxPlan->agree : $this->lang->admin->community->uxPlan->cancel;
        return $this->send(array('result' => 'success', 'message' => $message));
    }

    /**
     *  获取图形验证码
     *  Obtain graphical captcha。
     *
     * @access public
     * @return void
     */
    public function getCaptcha()
    {
        $apiRoot    = $this->config->admin->register->apiRoot;
        $apiURL     = $apiRoot . "/guarder-apiGetCaptcha.json";

        $httpData['token'] = md5(session_id());

        $response = common::http($apiURL, $httpData);
        $response = json_decode($response, true);
        return $this->send($response);
    }

    /**
     *  发动短信验证码
     *  Activate SMS verification code
     *
     * @access public
     * @return void
     */
    public function sendCode()
    {
        $apiRoot    = $this->config->admin->register->apiRoot;
        $apiURL     = $apiRoot . "/sms-apiSendCode.json";

        $_POST['token'] = md5(session_id());

        $response   = common::http($apiURL, $_POST);
        $response   = json_decode($response, true);
        return $this->send($response);
    }

    /**
     *  用户体验改进计划详情
     *
     * @access public
     * @return void
     */
    public function planModal()
    {
        $this->display();
    }

    /**
     *  填写信息表单
     *
     * @access public
     * @return void
     */
    public function giftPackage()
    {
        if(!empty($_POST))
        {
            $data = form::data($this->config->admin->form->giftPackage)->get();

            $bindCommunityMobile = $this->config->admin->register->bindCommunityMobile;
            if(!$bindCommunityMobile) return $this->send(array('result' => 'fail', 'message' => $this->lang->admin->community->giftPackageFailed));

            $apiRoot    = $this->config->admin->register->apiRoot;
            $apiURL     = $apiRoot . "/user-apiSaveProfile.json";

            if(empty($this->config->global->sn)) $this->loadModel('setting')->setSN();
            $httpData['sn']             = $this->config->global->sn;
            $httpData['nickname']       = $data->nickname;
            $httpData['position']       = $data->position;
            $httpData['company']        = $data->company;
            $httpData['solvedProblems'] = json_encode($data->solvedProblems);
            $httpData['mobile']         = $bindCommunityMobile;
            $httpData['token']          = md5(session_id());

            $response = common::http($apiURL, $httpData);

            $response = json_decode($response, true);

            if(isset($response['result']) && $response['result'] == 'success')
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->admin->community->giftPackageSuccess, 'closeModal' => true));
            }
            return $this->send(array('result' => 'fail', 'message' => isset($response['message']) ? $response['message'] : $this->lang->admin->community->giftPackageFailed));
        }

        $companyID = isset($this->app->company->id) ? $this->app->company->id : 1;
        $this->loadModel('company');
        $company = $this->company->getByID($companyID);

        $this->view->company = $company->name;
        $this->display();
    }
}
