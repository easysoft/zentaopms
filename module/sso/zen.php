<?php
declare(strict_types=1);
/**
 * The zen file of sso module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     sso
 * @link        https://www.zentao.net
 */
class ssoZen extends sso
{
    /**
     * Get feishu accessToken.
     *
     * @param  object    $appConfig
     * @access protected
     * @return array
     */
    protected function getFeishuAccessToken(object $appConfig): array
    {
        $appUrl    = $this->config->sso->feishuAppInfoAPI;
        $appParams = array('app_id' => $appConfig->appId, 'app_secret' => $appConfig->appSecret);
        $appResult = common::http($appUrl, $appParams, array(), array(), 'json');

        if(empty($appResult)) return array('result' => 'fail', 'message' => $this->lang->sso->feishuResponseEmpty);

        $appInfo = json_decode($appResult);
        if(!isset($appInfo->msg) || $appInfo->msg != 'ok') return array('result' => 'fail', 'message' => $appResult);
        return array('result' => 'success', 'token' => $appInfo->app_access_token);
    }

    /**
     * Get feishu userToken.
     *
     * @param  string    $code
     * @param  string    $accessToken
     * @access protected
     * @return array
     */
    protected function getFeishuUserToken(string $code, string $accessToken): array
    {
        $tokenUrl     = $this->config->sso->feishuTokenAPI;
        $tokenHeaders = array('Authorization: Bearer ' . $accessToken);
        $tokenParams  = array('grant_type' => 'authorization_code', 'code' => $code);
        $tokenResult  = common::http($tokenUrl, $tokenParams, array(), $tokenHeaders, 'json');

        if(empty($tokenResult)) return array('result' => 'fail', 'message' => $this->lang->sso->feishuResponseEmpty);

        $tokenInfo = json_decode($tokenResult);
        if(!isset($tokenInfo->msg) or $tokenInfo->msg != 'success') return array('result' => 'fail', 'message' => $tokenResult);
        return array('result' => 'success', 'token' => $tokenInfo->data->access_token);
    }

    /**
     * Get bind feishu user.
     *
     * @param  string    $userToken
     * @param  object    $feishuConfig
     * @access protected
     * @return array
     */
    protected function getBindFeishuUser(string $userToken, object $feishuConfig): array
    {
        $userUrl     = $this->config->sso->feishuUserInfoAPI;
        $userHeaders = array('Authorization: Bearer ' . $userToken);
        $userResult  = common::http($userUrl, array(), array(), $userHeaders, 'json');

        if(empty($userResult)) return array('result' => 'fail', 'message' => $this->lang->sso->feishuResponseEmpty);

        $userInfo = json_decode($userResult);
        if(!isset($userInfo->msg) or $userInfo->msg != 'success') return array('result' => 'fail', 'message' => $userResult);

        $openID = $userInfo->data->open_id;

        /* Get the user relationship bound in webhook. */
        $account  = $this->loadModel('webhook')->getBindAccount($feishuConfig->id, 'webhook', $openID);
        if(empty($account)) return array('result' => 'fail', 'message' => $this->lang->sso->unbound);

        $user = $this->loadModel('user')->getById($account);
        return array('result' => 'success', 'user' => $user);
    }

    /**
     * Build user data for createUser method.
     *
     * @access protected
     * @return object
     */
    protected function buildUserForCreate(): object
    {
        return form::data($this->config->sso->form->createUser)->setDefault('ranzhi', $this->post->account)->get();
    }

    /**
     * Idenfy from SSO.
     *
     * @param  string    $locate
     * @access protected
     * @return bool
     */
    protected function idenfyFromSSO(string $locate): bool
    {
        if($this->get->status != 'success' || md5($this->get->data) != $this->get->md5) return false;

        $last = $this->server->request_time;
        $data = json_decode(base64_decode($this->get->data));

        if($data->auth != $this->computeAuth($data->token)) return false;

        $user = $this->sso->getBindUser($data->account);
        if(!$user)
        {
            $this->session->set('ssoData', $data);
            return $this->locate($this->createLink('sso', 'bind', "referer=" . helper::safe64Encode($locate)));
        }

        if($this->loadModel('user')->isLogon() and $this->session->user->account == $user->account) return $this->locate($locate);

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

        return $this->locate($locate);
    }

    /**
     * Locate notify link.
     *
     * @param  string    $location
     * @param  string    $referer
     * @access protected
     * @return void
     */
    protected function locateNotifyLink(string $location, string $referer): void
    {
        $isGet       = strpos($location, '&') !== false;
        $requestType = $this->get->requestType;
        if(isset($requestType)) $isGet = $requestType == 'GET' ? true : false;

        if($isGet)  $location = $this->buildLocationByGET($location, $referer);
        if(!$isGet) $location = $this->buildLocationByPATHINFO($location, $referer);

        if(!empty($_GET['sessionid']))
        {
            $sessionConfig = json_decode(base64_decode($this->get->sessionid), false);
            $location     .= '&' . $sessionConfig->session_name . '=' . $sessionConfig->session_id;
        }
        $this->locate($location);
    }

    /**
     * Build location by GET.
     *
     * @param  string  $location
     * @param  string  $referer
     * @access private
     * @return string
     */
    private function buildLocationByGET(string $location, string $referer): string
    {
        if(strpos($location, '&') === false)
        {
            $position = strrpos($location, '/') + 1;
            $uri      = substr($location, 0 ,$position);
            $param    = str_replace('.html', '', substr($location, $position));
            list($module, $method) = explode('-', $param);
            $location = $uri . 'index.php?m=' . $module . '&f=' . $method;
        }
        return rtrim($location, '&') . '&' . $this->buildSSOParams($referer);
    }

    /**
     * Build location by PATH_INFO.
     *
     * @param  string $location
     * @param  string $referer
     * @access private
     * @return string
     */
    private function buildLocationByPATHINFO(string $location, string $referer): string
    {
        if(strpos($location, '&') !== false)
        {
            list($uri, $param) = explode('index.php', $location);
            $param = substr($param, 1);
            parse_str($param, $result);
            $location = $uri . $result['m'] . '-' . $result['f'] . '.html';
        }
        return rtrim($location, '?') . '?' . $this->buildSSOParams($referer);
    }

    /**
     * Build SSO params.
     *
     * @param  string  $referer
     * @access private
     * @return string
     */
    private function buildSSOParams(string $referer): string
    {
        $userIP   = helper::getRemoteIp();
        $token    = $this->get->token;
        $auth     = $this->computeAuth($token);
        $callback = urlencode(common::getSysURL() . inlink('login', "type=return"));
        return "token=$token&auth=$auth&userIP=$userIP&callback=$callback&referer=$referer";
    }

    /**
     * Compute auth.
     *
     * @access private
     * @return string
     */
    private function computeAuth(string $token): string
    {
        $userIP = helper::getRemoteIp();
        $code   = $this->config->sso->code;
        $key    = $this->config->sso->key;
        return md5($code . $userIP . $token . $key);
    }
}

