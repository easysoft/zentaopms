<?php
/**
 * The control file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: control.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class admin extends control
{
    /**
     * The gogs constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        if(!isset($this->config->global->sn))
        {
            $this->loadModel('setting');
            $this->setting->setItem('system.common.global.sn', $this->setting->computeSN());

            if(!isset($this->config->global)) $this->config->global = new stdclass();
            $this->config->global->sn = $this->setting->getItem('owner=system&module=common&section=global&key=sn');
        }
    }

    /**
     * Index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        set_time_limit(0);

        $community = zget($this->config->global, 'community', '');
        if(!$community or $community == 'na')
        {
            $this->view->bind    = false;
            $this->view->account = false;
            $this->view->ignore  = $community == 'na';
        }
        else
        {
            $this->view->bind    = true;
            $this->view->account = $community;
            $this->view->ignore  = false;
        }

        $this->loadModel('misc');

        $langNotCN  = common::checkNotCN();
        $dateUsed   = $this->admin->genDateUsed();
        $zentaoData = $this->admin->getZentaoData();

        $this->view->title       = $this->lang->admin->common;
        $this->view->position[]  = $this->lang->admin->index;
        $this->view->plugins     = $zentaoData->plugins;
        $this->view->patches     = $zentaoData->patches;
        $this->view->dateUsed    = $dateUsed;
        $this->view->hasInternet = $zentaoData->hasData;
        $this->view->isIntranet  = helper::isIntranet();
        $this->view->dynamics    = $zentaoData->news;
        $this->view->publicClass = $zentaoData->publicclass;
        $this->view->langNotCN   = $langNotCN;
        $this->display();
    }

    /**
     * Get zentao.net data by api.
     *
     * @access public
     * @return void
     */
    public function ajaxSetZentaoData()
    {
        if(helper::isIntranet()) return $this->send(array('result' => 'ignore'));

        $hasInternet = $this->admin->checkInternet();

        if($hasInternet)
        {
            $nextWeek   = date('Y-m-d', strtotime('-7 days'));
            $zentaoData = $this->loadModel('block')->getZentaoData($nextWeek);

            if(empty($zentaoData))
            {
                $this->admin->setExtensionsByAPI('plugin', 6);
                $this->admin->setExtensionsByAPI('patch', 5);
                $this->admin->setDynamicsByAPI(3);
                $this->admin->setPublicClassByAPI(3);
            }
        }

        return $this->send(array('result' => 'success'));
    }

    /**
     * Ignore notice of register and bind.
     *
     * @access public
     * @return void
     */
    public function ignore()
    {
        $account = $this->app->user->account;
        $this->loadModel('setting');
        $this->setting->deleteItems('owner=system&module=common&section=global&key=ztPrivateKey');
        $this->setting->setItem("$account.common.global.community", 'na');
        echo js::locate(inlink('index'), 'parent');
    }

    /**
     * Register zentao.
     *
     * @param  string $from
     * @access public
     * @return void
     */
    public function register($from = 'admin')
    {
        if($_POST)
        {
            $response = $this->admin->registerByAPI();
            $response = json_decode($response);
            if($response->result == 'success')
            {
                $user = $response->data;
                $data['community']    = $user->account;
                $data['ztPrivateKey'] = $user->private;

                $this->loadModel('setting');
                $this->setting->deleteItems('owner=system&module=common&section=global&key=community');
                $this->setting->deleteItems('owner=system&module=common&section=global&key=ztPrivateKey');
                $this->setting->setItems('system.common.global', $data);

                echo js::alert($this->lang->admin->registerNotice->success);
                if($from == 'admin') return print(js::locate(inlink('index'), 'parent'));
                if($from == 'mail') return print(js::locate($this->createLink('mail', 'ztcloud'), 'parent'));
            }

            $alertMessage = '';
            if(is_string($response->message))
            {
                $alertMessage = $response->message;
            }
            else
            {
                foreach($response->message as $item) $alertMessage .= is_array($item) ? join('\n', $item) . '\n' : $item . '\n';
            }
            $alertMessage = str_replace(array('<strong>', '</strong>'), '', $alertMessage);
            return print(js::alert($alertMessage));
        }

        $this->view->title      = $this->lang->admin->registerNotice->caption;
        $this->view->position[] = $this->lang->admin->registerNotice->caption;
        $this->view->register   = $this->admin->getRegisterInfo();
        $this->view->sn         = $this->config->global->sn;
        $this->view->from       = $from;
        $this->display();
    }

    /**
     * Bind zentao.
     *
     * @param  string $from
     * @access public
     * @return void
     */
    public function bind($from = 'admin')
    {
        if($_POST)
        {
            $response = $this->admin->bindByAPI();
            $response = json_decode($response);
            if($response->result == 'success')
            {
                $user = $response->data;
                $data['community']    = $user->account;
                $data['ztPrivateKey'] = $user->private;

                $this->loadModel('setting');
                $this->setting->deleteItems('owner=system&module=common&section=global&key=community');
                $this->setting->deleteItems('owner=system&module=common&section=global&key=ztPrivateKey');
                $this->setting->setItems('system.common.global', $data);

                echo js::alert($this->lang->admin->bind->success);
                if($from == 'admin') return print(js::locate(inlink('index'), 'parent'));
                if($from == 'mail') return print(js::locate($this->createLink('mail', 'ztcloud'), 'parent'));
            }
            else
            {
                if($response->result == 'fail') return print(js::alert($response->message));
            }
        }

        $this->view->title      = $this->lang->admin->bind->caption;
        $this->view->position[] = $this->lang->admin->bind->caption;
        $this->view->sn         = $this->config->global->sn;
        $this->view->from       = $from;
        $this->display();
    }

    /**
     * Account safe.
     *
     * @access public
     * @return void
     */
    public function safe()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->loadModel('setting')->setItems('system.common.safe', $data);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title      = $this->lang->admin->safe->common . $this->lang->colon . $this->lang->admin->safe->set;
        $this->view->position[] = $this->lang->admin->safe->common;
        $this->view->gdInfo     = function_exists('gd_info') ? gd_info() : array();
        $this->display();
    }

    /**
     * Check weak user.
     *
     * @access public
     * @return void
     */
    public function checkWeak()
    {
        $this->view->title      = $this->lang->admin->safe->common . $this->lang->colon . $this->lang->admin->safe->checkWeak;
        $this->view->position[] = html::a(inlink('safe'), $this->lang->admin->safe->common);
        $this->view->position[] = $this->lang->admin->safe->checkWeak;
        $this->view->weakUsers  = $this->loadModel('user')->getWeakUsers();
        $this->display();
    }

    /**
     * Config sso for ranzhi.
     *
     * @access public
     * @return void
     */
    public function sso()
    {
        if(!empty($_POST))
        {
            $ssoConfig = new stdclass();
            $ssoConfig->turnon   = $this->post->turnon;
            $ssoConfig->redirect = $this->post->redirect;
            $ssoConfig->addr     = $this->post->addr;
            $ssoConfig->code     = trim($this->post->code);
            $ssoConfig->key      = trim($this->post->key);

            if(!$ssoConfig->turnon) $ssoConfig->redirect = $ssoConfig->turnon;
            $this->loadModel('setting')->setItems('system.sso', $ssoConfig);
            if(dao::isError()) return print(js::error(dao::getError()));
            return print($this->locate(inlink('sso')));
        }

        $this->loadModel('sso');
        if(!isset($this->config->sso)) $this->config->sso = new stdclass();

        $this->view->title      = $this->lang->admin->sso;
        $this->view->position[] = $this->lang->admin->sso;

        $this->view->turnon   = isset($this->config->sso->turnon)   ? $this->config->sso->turnon   : 1;
        $this->view->redirect = isset($this->config->sso->redirect) ? $this->config->sso->redirect : 0;
        $this->view->addr     = isset($this->config->sso->addr)     ? $this->config->sso->addr     : '';
        $this->view->key      = isset($this->config->sso->key)      ? $this->config->sso->key      : '';
        $this->view->code     = isset($this->config->sso->code)     ? $this->config->sso->code     : '';
        $this->display();
    }

    /**
     * Set closed features config.
     *
     * @access public
     * @return void
     */
    public function setModule()
    {
        if($_POST)
        {
            $closedFeatures = '';
            if(isset($_POST['module']))
            {
                foreach($this->post->module as $module => $options)
                {
                    if($module == 'myScore') continue;
                    $checked = reset($options);
                    if(!$checked) $closedFeatures .= "$module,";
                }
            }
            $closedFeatures = rtrim($closedFeatures, ',');
            $this->loadModel('setting')->setItem('system.common.closedFeatures', $closedFeatures);
            $this->loadModel('setting')->setItem('system.common.global.scoreStatus', $this->post->module['myScore'][0]);
            $this->loadModel('setting')->setItem('system.custom.URAndSR', $this->post->module['productUR'][0]);
            $this->loadModel('custom')->processMeasrecordCron();
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'top'));
        }
        $this->view->title            = $this->lang->admin->setModuleIndex;
        $this->view->closedFeatures   = $this->loadModel('setting')->getItem('owner=system&module=common&section=&key=closedFeatures');
        $this->view->useScore         = $this->setting->getItem('owner=system&module=common&global&key=scoreStatus');
        $this->view->disabledFeatures = $this->setting->getItem('owner=system&module=common&section=&key=disabledFeatures');
        $this->display();
    }

    /**
     * Certify ztEmail.
     *
     * @param  string $email
     * @access public
     * @return void
     */
    public function certifyZtEmail($email = '')
    {
        if($_POST)
        {
            $response = $this->admin->certifyByAPI('mail');
            $response = json_decode($response);
            if($response->result == 'fail') return print(js::alert($response->message));
            return print(js::locate($this->createLink('mail', 'ztCloud'), 'parent'));
        }

        $this->view->title      = $this->lang->admin->certifyEmail;
        $this->view->position[] = $this->lang->admin->certifyEmail;

        $this->view->email = helper::safe64Decode($email);
        $this->display();
    }

    /**
     * Certify ztMobile.
     *
     * @param  string $mobile
     * @access public
     * @return void
     */
    public function certifyZtMobile($mobile = '')
    {
        if($_POST)
        {
            $response = $this->admin->certifyByAPI('mobile');
            $response = json_decode($response);
            if($response->result == 'fail') return print(js::alert($response->message));
            return print(js::locate($this->createLink('mail', 'ztCloud'), 'parent'));
        }

        $this->view->title      = $this->lang->admin->certifyMobile;
        $this->view->position[] = $this->lang->admin->certifyMobile;

        $this->view->mobile = helper::safe64Decode($mobile);
        $this->display();
    }

    /**
     * Set ztCompany.
     *
     * @access public
     * @return void
     */
    public function ztCompany($fields = 'company')
    {
        if($_POST)
        {
            $response = $this->admin->setCompanyByAPI();
            $response = json_decode($response);
            if($response->result == 'fail') return print(js::alert($response->message));
            return print(js::locate($this->createLink('mail', 'ztCloud'), 'parent'));
        }

        $this->view->title      = $this->lang->admin->ztCompany;
        $this->view->position[] = $this->lang->admin->ztCompany;

        $this->view->fields = explode(',', $fields);
        $this->display();
    }

    /**
     * Ajax send code.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxSendCode($type)
    {
        return print($this->admin->sendCodeByAPI($type));
    }

    /**
     * Set save days of log.
     *
     * @access public
     * @return void
     */
    public function log()
    {
        if($_POST)
        {
            if(!validater::checkInt($this->post->days)) return $this->send(array('result' => 'fail', 'message' => array('days' => sprintf($this->lang->admin->notice->int, $this->lang->admin->days))));

            $this->loadModel('setting')->setItem('system.admin.log.saveDays', $this->post->days);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->loadModel('message');
        $this->loadModel('webhook');

        $this->view->title      = $this->lang->admin->log;
        $this->view->position[] = html::a($this->createLink('webhook', 'browse'), $this->lang->admin->api);
        $this->view->position[] = $this->lang->admin->log;
        $this->view->position[] = $this->lang->admin->setting;
        $this->display();
    }

    /**
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
     * Reset password setting.
     *
     * @access public
     * @return void
     */
    public function resetPWDSetting()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.resetPWDByMail', $this->post->resetPWDByMail);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title = $this->lang->admin->resetPWDSetting;
        $this->display();
    }

    /**
     * Show table engine.
     *
     * @access public
     * @return void
     */
    public function tableEngine()
    {
        $this->view->title = $this->lang->admin->tableEngine;
        $this->view->tableEngines = $this->dao->getTableEngines();
        $this->display();
    }

    /**
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

            if($thisTable and empty($nextTable)) $nextTable = $table;
            if(empty($thisTable)) $thisTable = $table;
            if($thisTable and $nextTable) break;
        }

        if(empty($thisTable))
        {
            unset($_SESSION['errorTables']);
            $response['result'] = 'finished';
            return print(json_encode($response));
        }

        try
        {
            /* Check process this table or not. */
            $dbProcesses = $this->dbh->query("SHOW PROCESSLIST")->fetchAll();
            foreach($dbProcesses as $dbProcess)
            {
                if($dbProcess->db != $this->config->db->name) continue;
                if(!empty($dbProcess->Info) and strpos($dbProcess->Info, " {$thisTable} ") !== false)
                {
                    $response['message'] = sprintf($this->lang->upgrade->changingTable, $thisTable);
                    return print(json_encode($response));
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

        return print(json_encode($response));
    }

    /**
     * AJAX: Get drop menu.
     *
     * @param  string $currentMenuKey
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($currentMenuKey = '')
    {
        $this->admin->checkPrivMenu();
        $data = array();
        foreach($this->lang->admin->menuList as $menuKey => $menuGroup)
        {
            if($this->config->vision == 'lite' and !in_array($menuKey, $this->config->admin->liteMenuList)) continue;
            $data[] = array(
                'id'        => $menuKey,
                'name'      => $menuKey,
                'content'   => array('html' => "<div class='flex items-center my-2'><img class='mr-2' src='static/svg/admin-{$menuKey}.svg'/> {$menuGroup['name']}</div>"),
                'text'      => '',
                'title'     => $menuGroup['name'],
                'type'      => 'item',
                'disabled'  => $menuGroup['disabled'],
                'url'       => $menuGroup['disabled'] ? '' : $menuGroup['link'],
                'active'    => $currentMenuKey == $menuKey,
                'rootClass' => 'admin-menu-item',
                'attrs'     => array('disabled' => $menuGroup['disabled'])
            );
        }

        $this->view->data = $data;
        $this->display();
    }
}
