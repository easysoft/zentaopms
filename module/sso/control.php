<?php
/**
 * The control file of sso module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id: control.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class sso extends control
{
    /**
     * SSO login.
     *
     * @param  string $type  notify|return
     * @access public
     * @return void
     */
    public function login(string $type = 'notify')
    {
        $referer = empty($_GET['referer']) ? '' : $this->get->referer;
        $locate  = empty($referer) ? getWebRoot() : base64_decode($referer);

        $this->app->loadConfig('sso');
        if(!$this->config->sso->turnon) return print($this->locate($locate));

        if($type != 'return') return $this->ssoZen->locateNotifyLink($locate, $referer);

        $this->ssoZen->idenfyFromSSO($locate);
        return $this->locate($this->createLink('user', 'login', empty($referer) ? '' : "referer=$referer"));
    }

    /**
     * SSO logout.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function logout(string $type = 'notify')
    {
        if($type != 'return')
        {
            $userIP   = helper::getRemoteIp();
            $token    = $this->get->token;
            $auth     = $this->ssoZen->computeAuth($token);
            $callback = urlencode(common::getSysURL() . inlink('logout', "type=return"));

            $location = $this->config->sso->addr;
            $sign     = strpos($location, '&') !== false ? '&' : '?';
            $location = rtrim($location, $sign) . "{$sign}token={$token}&auth={$auth}&userIP={$userIP}&callback={$callback}";
            return $this->locate($location);
        }

        if($this->get->status == 'success')
        {
            session_destroy();
            helper::setcookie('za', false);
            helper::setcookie('zp', false);
            return $this->locate($this->createLink('user', 'login'));
        }
        return $this->locate($this->createLink('user', 'logout'));
    }

    /**
     * Ajax set config.
     *
     * @access public
     * @return void
     */
    public function ajaxSetConfig()
    {
        if(!$this->app->user->admin) return print('deny');

        if($_POST)
        {
            $ssoConfig = new stdclass();
            $ssoConfig->turnon = 1;
            $ssoConfig->addr   = $this->post->addr;
            $ssoConfig->code   = trim($this->post->code);
            $ssoConfig->key    = trim($this->post->key);

            $this->loadModel('setting')->setItems('system.sso', $ssoConfig);
            if(dao::isError()) return print('fail');
            return print('success');
        }
    }

    /**
     * Bind user.
     *
     * @param  string $referer
     * @access public
     * @return void
     */
    public function bind(string $referer = '')
    {
        if(!$this->session->ssoData) return;

        $ssoData = $this->session->ssoData;
        if($ssoData->auth != $this->computeAuth($ssoData->token)) return;

        $this->loadModel('user');
        if($_POST)
        {
            $user = $this->sso->bind();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $user->last = date(DT_DATETIME1);
            $this->user->login($user);

            unset($_SESSION['ssoData']);
            return $this->send(array('result' => 'success', 'load' => helper::safe64Decode($referer)));
        }

        $this->view->title = $this->lang->sso->bind;
        $this->view->users = $this->user->getPairs('noclosed|nodeleted');
        $this->view->data  = $ssoData;
        $this->display();
    }

    /**
     * Get pairs of user.
     *
     * @access public
     * @return void
     */
    public function getUserPairs()
    {
        if(!$this->sso->checkKey()) return false;
        $users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        echo json_encode($users);
    }

    /**
     * Get bind users with ranzhi.
     *
     * @access public
     * @return void
     */
    public function getBindUsers()
    {
        if(!$this->sso->checkKey()) return false;
        $users = $this->sso->getBindUsers();
        echo json_encode($users);
    }

    /**
     * Bind user from ranzhi.
     *
     * @access public
     * @return void
     */
    public function bindUser()
    {
        if($_POST)
        {
            $this->dao->update(TABLE_USER)->set('ranzhi')->eq('')->where('ranzhi')->eq($this->post->ranzhiAccount)->exec();
            $this->dao->update(TABLE_USER)->set('ranzhi')->eq($this->post->ranzhiAccount)->where('account')->eq($this->post->zentaoAccount)->exec();
            if(dao::isError()) return print(implode("\n", dao::getError()));
            return print('success');
        }
    }

    /**
     * Create user from ranzhi.
     *
     * @access public
     * @return void
     */
    public function createUser()
    {
        if($_POST)
        {
            $user   = $this->ssoZen->buildUserForCreate();
            $result = $this->sso->createUser($user);
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $result['id']));
            if($result['status'] != 'success') return print($result['data']);
            return print('success');
        }
    }

    /**
     * Get todo list for ranzhi.
     *
     * @param  string  $account
     * @access public
     * @return void
     */
    public function getTodoList(string $account = '')
    {
        if(!$this->sso->checkKey()) return false;
        $user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($account)->andWhere('deleted')->eq(0)->fetch();
        if($user) $account = $user->account;

        $datas = array();
        $datas['task'] = $this->dao->select("id,name")->from(TABLE_TASK)->where('assignedTo')->eq($account)->andWhere('status')->in('wait,doing')->andWhere('deleted')->eq(0)->fetchPairs();
        $datas['bug']  = $this->dao->select("id,title")->from(TABLE_BUG)->where('assignedTo')->eq($account)->andWhere('status')->eq('active')->andWhere('deleted')->eq(0)->fetchPairs();
        echo json_encode($datas);
    }

    /**
     * Get the link to the Feishu single sign-on configuration.
     *
     * @access public
     * @return void
     */
    public function getFeishuSSO()
    {
        $httpType = ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $applicationHome = $httpType . $_SERVER['HTTP_HOST'] . $this->createLink('sso', 'feishuAuthen');
        $redirectLink    = $httpType . $_SERVER['HTTP_HOST'] . $this->createLink('sso', 'feishuLogin');

        echo $this->lang->sso->homeURL . $applicationHome;
        echo '<br>';
        echo $this->lang->sso->redirectURL . $redirectLink;
    }

    /**
     * Get the pre-authorization code for Feishu code.
     *
     * @access public
     * @return void
     */
    public function feishuAuthen()
    {
        $httpType    = ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $redirectURI = $httpType . $_SERVER['HTTP_HOST'] . $this->createLink('sso', 'feishuLogin');
        $redirectURI = urlencode($redirectURI);

        $feishuConfig = $this->loadModel('webhook')->getByType('feishuuser');
        if(empty($feishuConfig)) $this->showError($this->lang->sso->feishuConfigEmpty);

        $appConfig = json_decode($feishuConfig->secret);
        $appID     = $appConfig->appId;

        $url = sprintf($this->config->sso->feishuAuthAPI, $redirectURI, $appID, $state);
        $this->locate($url);
    }

    /**
     * Get the identity of the logged-in user.
     *
     * @param  string  $code
     * @access public
     * @return void
     */
    public function feishuLogin(string $code = '')
    {
        if($this->config->requestType == 'PATH_INFO')
        {
            $params = $_SERVER["QUERY_STRING"];
            parse_str($params, $params);
            if(isset($params['code'])) $code = $params['code'];
        }

        $feishuConfig = $this->loadModel('webhook')->getByType('feishuuser');
        if(empty($feishuConfig)) $this->showError($this->lang->sso->feishuConfigEmpty);
        $appConfig = json_decode($feishuConfig->secret);

        /* Obtain the access credentials of the Feishu app. */
        $appResult = $this->ssoZen->getFeishuAccessToken($appConfig);
        if($appResult['result'] == 'fail') return $this->showError($appResult['message']);
        $accessToken = $appResult['token'];

        /* Verify the identity of the logged in user. */
        $tokenResult = $this->ssoZen->getFeishuUserToken($code, $accessToken);
        if($tokenResult['result'] == 'fail') return $this->showError($tokenResult['message']);
        $accessToken = $tokenResult['token'];

        /* Get login user information. */
        $userResult = $this->ssoZen->getBindFeishuUser($userToken, $feishuConfig);
        if($userResult['result'] == 'fail') return $this->showError($userResult['message']);
        $user = $userResult['user'];

        $this->session->set('rand', '');
        $user = $this->loadModel('user')->identify($user->account, $user->password);
        $this->user->login($user);

        $this->locate($this->createLink('my', 'index'));
    }

    /**
     * Display the error message.
     *
     * @param  string  $message
     * @access public
     * @return void
     */
    public function showError(string $message = '')
    {
        $this->view->title   = $this->lang->sso->deny;
        $this->view->message = $message;
        $this->display('sso', 'error');
    }
}
