<?php
helper::import('../../control.php');

class myIm extends im
{
    /**
     * Authorize and redirect user to url.
     *
     * @param  string $account
     * @param  string $token
     * @param  string $device
     * @param  string $url
     * @access public
     * @return void
     */
    public function authorize($account = '', $token = '', $device = '', $url = '')
    {
        if(!empty($url)) $url = str_replace('_', $this->config->requestFix, $url);
        if(empty($account) || empty($token)) die('Invalid params. Please provide account, token and url.');

        $user = $this->im->userIdentifyWithToken($account, $token, $device);
        if(!$user || is_string($user)) die('Invalid token.');

        if(empty($url)) die('Authorized, but no url to redirect to.');

        $user = $this->loadModel('user')->getByAccount($account);
        $user = $this->user->login($user);
        $url .= $this->config->requestType == 'GET' ? '&' : '?';
        $url .= "{$this->config->sessionVar}={$this->app->sessionID}";
        if ($this->config->requestType == 'GET') $url .= "&{$this->config->viewVar}=json";
        else $url = str_replace('.html', '.json', $url);

        header("Location: $url", true, 307);
    }
}
