<?php
class wechatapi
{
    public  $apiUrl = 'https://qyapi.weixin.qq.com/cgi-bin/';
    private $appKey;
    private $appSecret;
    private $token;
    private $expires;
    private $errors = array();

    /**
     * Construct
     *
     * @param  string $appKey
     * @param  string $appSecret
     * @param  string $agentId
     * @param  string $apiUrl
     * @access public
     */
    public function __construct($appKey, $appSecret, $agentId, $apiUrl = '')
    {
        $this->appKey    = $appKey;
        $this->appSecret = $appSecret;
        $this->agentId   = $agentId;
        if($apiUrl) $this->apiUrl = rtrim($apiUrl, '/') . '/';

        if(!$this->getToken()) return array('result' => 'fail', 'message' => $this->errors);
    }

    /**
     * Get token.
     *
     * @access public
     * @return string
     */
    public function getToken()
    {
        if($this->token and (time() - $this->expires) >= 0) return $this->token;

        $response = $this->queryAPI($this->apiUrl . "gettoken?corpid={$this->appKey}&corpsecret={$this->appSecret}");
        if($this->isError()) return false;

        $this->token   = $response->access_token;
        $this->expires = time() + $response->expires_in;
        return $this->token;
    }

    /**
     * Get all users.
     *
     * @access public
     * @return array
     */
    public function getAllUsers()
    {
        $depts = $this->getAgent();
        if($this->isError()) return array('result' => 'fail', 'message' => $this->errors);

        $users = array();
        foreach($depts->deptList as $deptID)
        {
            $response = $this->queryAPI($this->apiUrl . "user/simplelist?access_token={$this->token}&department_id={$deptID}&fetch_child=1");
            if($this->isError()) return array('result' => 'fail', 'message' => $this->errors);

            foreach($response->userlist as $user) $users[$user->name] = $user->userid;
        }

        $users = array_merge($users, $depts->userList);
        return array('result' => 'success', 'data' => $users);
    }

    /**
     * Get agent details.
     *
     * @access public
     * @return stdClass
     */
    public function getAgent()
    {
        $response = $this->queryAPI($this->apiUrl . "agent/get?access_token={$this->token}&agentid={$this->agentId}");

        $deptInfo = new stdClass();
        $deptInfo->userList = array();
        if(isset($response->allow_userinfos))
        {
            foreach ($response->allow_userinfos->user as $user)
            {
                $userInfo = $this->queryAPI($this->apiUrl . "user/get?access_token={$this->token}&userid={$user->userid}");
                $deptInfo->userList[$userInfo->name] = $userInfo->userid;
            }
        }

        $deptInfo->deptList = array();
        if(isset($response->allow_partys)) $deptInfo->deptList = $response->allow_partys->partyid;

        if($this->isError()) return false;

        return $deptInfo;
    }

    /**
     * Send message
     *
     * @param  string $userList
     * @param  string $message
     * @access public
     * @return array
     */
    public function send($userList, $message)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

        curl_setopt($curl, CURLOPT_URL, $this->apiUrl . 'message/send?access_token=' . $this->token);

        $message = json_decode($message);
        $message->agentid = $this->agentId;
        $message->touser  = str_replace(',', '|', $userList);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($message));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        if(isset($response->errcode) and $response->errcode == 0) return array('result' => 'success');

        $this->errors[$response->errcode] = "Errcode:{$response->errcode}, Errmsg:{$response->errmsg}";
        return array('result' => 'fail', 'message' => $this->errors);
    }

    /**
     * Query API.
     *
     * @param  string $url
     * @access public
     * @return string
     */
    public function queryAPI($url)
    {
        $response = json_decode(file_get_contents($url));
        if(isset($response->errcode) and $response->errcode == 0) return $response;

        $this->errors[$response->errcode] = "Errcode:{$response->errcode}, Errmsg:{$response->errmsg}";
        return false;
    }

    /**
     * Check for errors.
     *
     * @access public
     * @return bool
     */
    public function isError()
    {
        return !empty($this->errors);
    }

    /**
     * Get errors.
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        $errors = $this->errors;
        $this->errors = array();

        return $errors;
    }
}
