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
     * @param  string $type
     * @access public
     * @return void
     */
    public function login($type = 'notify')
    {
        $referer = empty($_GET['referer']) ? '' : $this->get->referer;
        $locate  = empty($referer) ? getWebRoot() : base64_decode($referer);

        $this->app->loadConfig('sso');
        if(!$this->config->sso->turnon) return print($this->locate($locate));

        $userIP = helper::getRemoteIp();
        $code   = $this->config->sso->code;
        $key    = $this->config->sso->key;
        if($type != 'return')
        {
            $token  = $this->get->token;
            $auth   = md5($code . $userIP . $token . $key);

            $callback = urlencode(common::getSysURL() . inlink('login', "type=return"));
            $location = $this->config->sso->addr;
            $isGet = strpos($location, '&') !== false;
            $requestType = $this->get->requestType;
            if(isset($requestType)) $isGet = $this->get->requestType == 'GET' ? true : false;
            if($isGet)
            {
                /* Update location when dburl is path_info but need get. */
                if(strpos($location, '&') === false)
                {
                    $index = strripos($location, '/');
                    $uri = substr($location, 0 ,$index + 1);
                    $param = str_replace('.html', '', substr($location, $index + 1));
                    list($module, $method) = explode('-', $param);
                    $location = $uri . 'index.php?m=' . $module . '&f=' . $method;
                }
                $location = rtrim($location, '&') . "&token=$token&auth=$auth&userIP=$userIP&callback=$callback&referer=$referer";
            }
            else
            {
                /* Update location when dburl is get but need path_info. */
                if(strpos($location, '&') !== false)
                {
                    list($uri, $param) = explode('index.php', $location);
                    $param = trim($param, "?");
                    list($module, $method) = explode('&', $param);
                    $module = substr($module, strpos($module, '=') + 1);
                    $method = substr($method, strpos($method, '=') + 1);
                    $location = $uri . $module . '-' . $method . '.html';
                }
                $location = rtrim($location, '?') . "?token=$token&auth=$auth&userIP=$userIP&callback=$callback&referer=$referer";
            }

            if(!empty($_GET['sessionid']))
            {
                $sessionConfig = json_decode(base64_decode($this->get->sessionid), false);
                $location     .= '&' . $sessionConfig->session_name . '=' . $sessionConfig->session_id;
            }
            $this->locate($location);
        }

        if($this->get->status == 'success' and md5($this->get->data) == $this->get->md5)
        {
            $last = $this->server->request_time;
            $data = json_decode(base64_decode($this->get->data));

            $token = $data->token;
            if($data->auth == md5($code . $userIP . $token . $key))
            {
                $user = $this->sso->getBindUser($data->account);
                if(!$user)
                {
                    $this->session->set('ssoData', $data);
                    $this->locate($this->createLink('sso', 'bind', "referer=" . helper::safe64Encode($locate)));
                }

                if($this->loadModel('user')->isLogon())
                {
                    if($this->session->user && $this->session->user->account == $user->account) return print($this->locate($locate));
                }

                $this->user->cleanLocked($user->account);
                /* Authorize him and save to session. */
                $user->admin    = strpos($this->app->company->admins, ",{$user->account},") !== false;
                $user->rights   = $this->user->authorize($user->account);
                $user->groups   = $this->user->getGroups($user->account);
                $user->view     = $this->user->grantUserView($user->account, $user->rights['acls']);
                $user->last     = date(DT_DATETIME1, $last);
                $user->lastTime = $user->last;
                $user->modifyPassword = ($user->visits == 0 and !empty($this->config->safe->modifyPasswordFirstLogin));
                if($user->modifyPassword) $user->modifyPasswordReason = 'modifyPasswordFirstLogin';
                if(!$user->modifyPassword and !empty($this->config->safe->changeWeak))
                {
                    $user->modifyPassword = $this->loadModel('admin')->checkWeak($user);
                    if($user->modifyPassword) $user->modifyPasswordReason = 'weak';
                }

                $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($userIP)->set('last')->eq($last)->where('account')->eq($user->account)->exec();

                $this->session->set('user', $user);
                $this->app->user = $this->session->user;
                $this->loadModel('action')->create('user', $user->id, 'login');

                return print($this->locate($locate));
            }
        }
        $this->locate($this->createLink('user', 'login', empty($referer) ? '' : "referer=$referer"));
    }

    /**
     * SSO logout.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function logout($type = 'notify')
    {
        if($type != 'return')
        {
            $code   = $this->config->sso->code;
            $userIP = helper::getRemoteIp();
            $token  = $this->get->token;
            $key    = $this->config->sso->key;
            $auth   = md5($code . $userIP . $token . $key);

            $callback = urlencode(common::getSysURL() . inlink('logout', "type=return"));
            $location = $this->config->sso->addr;
            if(strpos($location, '&') !== false)
            {
                $location = rtrim($location, '&') . "&token=$token&auth=$auth&userIP=$userIP&callback=$callback";
            }
            else
            {
                $location = rtrim($location, '?') . "?token=$token&auth=$auth&userIP=$userIP&callback=$callback";
            }
            $this->locate($location);
        }

        if($this->get->status == 'success')
        {
            session_destroy();
            helper::setcookie('za', false);
            helper::setcookie('zp', false);
            $this->locate($this->createLink('user', 'login'));
        }
        $this->locate($this->createLink('user', 'logout'));
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
            echo 'success';
        }
    }

    /**
     * Bind user.
     *
     * @param  string $referer
     * @access public
     * @return void
     */
    public function bind($referer = '')
    {
        if(!$this->session->ssoData) return;

        $ssoData = $this->session->ssoData;
        $userIP  = helper::getRemoteIp();
        $code    = $this->config->sso->code;
        $key     = $this->config->sso->key;
        if($ssoData->auth != md5($code . $userIP . $ssoData->token . $key)) return;

        $this->loadModel('user');
        if($_POST)
        {
            $user = $this->sso->bind();
            if(dao::isError()) return print(js::error(dao::getError()));

            /* Authorize him and save to session. */
            $user->rights = $this->user->authorize($user->account);
            $user->groups = $this->user->getGroups($user->account);

            $user->last  = date(DT_DATETIME1);
            $user->admin = strpos($this->app->company->admins, ",{$user->account},") !== false;
            $this->session->set('user', $user);
            $this->app->user = $this->session->user;
            $this->loadModel('action')->create('user', $user->id, 'login');
            unset($_SESSION['ssoData']);
            return print(js::locate(helper::safe64Decode($referer), 'parent'));
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
            if(dao::isError()) return print(dao::getError());
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
            $result = $this->sso->createUser();
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
    public function getTodoList($account = '')
    {
        if(!$this->sso->checkKey()) return false;
        $user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($account)->andWhere('deleted')->eq(0)->fetch();
        if($user) $account = $user->account;

        $datas = array();
        $datas['task'] = $this->dao->select("id, name")->from(TABLE_TASK)->where('assignedTo')->eq($account)->andWhere('status')->in('wait,doing')->andWhere('deleted')->eq(0)->fetchPairs();
        $datas['bug']  = $this->dao->select("id, title")->from(TABLE_BUG)->where('assignedTo')->eq($account)->andWhere('status')->eq('active')->andWhere('deleted')->eq(0)->fetchPairs();
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

        $url = "https://open.feishu.cn/open-apis/authen/v1/index?redirect_uri=%s&app_id=%s";
        $url = sprintf($url, $redirectURI, $appID, $state);
        helper::header('location', $url);
    }

    /**
     * Get the identity of the logged-in user.
     *
     * @param  string  $code
     * @access public
     * @return void
     */
    public function feishuLogin($code = '')
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
        $appUrl    = 'https://open.feishu.cn/open-apis/auth/v3/app_access_token/internal';
        $appParams = array('app_id' => $appConfig->appId, 'app_secret' => $appConfig->appSecret);
        $appResult = common::http($appUrl, $appParams, array(), array(), 'json');

        if(empty($appResult)) $this->showError($this->lang->sso->feishuResponseEmpty);
        $appInfo = json_decode($appResult);

        if(!isset($appInfo->msg) or $appInfo->msg != 'ok') $this->showError($appResult);
        $accessToken = $appInfo->app_access_token;

        /* Verify the identity of the logged in user. */
        $tokenUrl     = 'https://open.feishu.cn/open-apis/authen/v1/refresh_access_token';
        $tokenHeaders = array('Authorization: Bearer ' . $accessToken);
        $tokenParams  = array('grant_type' => 'authorization_code', 'code' => $code);
        $tokenResult  = common::http($tokenUrl, $tokenParams, array(), $tokenHeaders, 'json');

        if(empty($tokenResult)) $this->showError($this->lang->sso->feishuResponseEmpty);
        $tokenInfo = json_decode($tokenResult);

        if(!isset($tokenInfo->msg) or $tokenInfo->msg != 'success') $this->showError($tokenResult);
        $userToken = $tokenInfo->data->access_token;

        /* Get login user information. */
        $userUrl     = 'https://open.feishu.cn/open-apis/authen/v1/user_info';
        $userHeaders = array('Authorization: Bearer ' . $userToken);
        $userResult  = common::http($userUrl, array(), array(), $userHeaders, 'json');

        if(empty($userResult)) $this->showError($this->lang->sso->feishuResponseEmpty);
        $userInfo = json_decode($userResult);

        if(!isset($userInfo->msg) or $userInfo->msg != 'success') $this->showError($userResult);
        $openID = $userInfo->data->open_id;

        /* Get the user relationship bound in webhook. */
        $account  = $this->loadModel('webhook')->getBindAccount($feishuConfig->id, 'webhook', $openID);
        if(empty($account)) $this->showError($this->lang->sso->unbound);

        $user     = $this->loadModel('user')->getById($account);
        $password = $user->password;
        $this->session->set('rand', '');
        $user = $this->user->identify($account, $password);
        $this->user->login($user);

        $indexUrl = $this->createLink('my', 'index');
        helper::header('location', $indexUrl);
    }

    /**
     * Display the error message.
     *
     * @param  string  $message
     * @access public
     * @return void
     */
    public function showError($message = '')
    {
        $this->view->title   = $this->lang->sso->deny;
        $this->view->message = $message;
        $this->display('sso', 'error');
    }
}
