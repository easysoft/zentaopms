<?php
class feishuapi
{
    public  $apiUrl = 'https://open.feishu.cn/open-apis/';
    private $appId;
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
     * @return void
     */
    public function __construct($appId, $appSecret, $apiUrl = '')
    {
        $this->appId     = $appId;
        $this->appSecret = $appSecret;
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

        $response = $this->queryAPI($this->apiUrl . "auth/v3/tenant_access_token/internal/", json_encode(array('app_id' => $this->appId, 'app_secret' => $this->appSecret)));
        if($this->isError()) return false;

        $this->token   = $response->tenant_access_token;
        $this->expires = time() + $response->expire;
        return $this->token;
    }

    /**
     * Get users.
     * 
     * @param  string $selectedDepts
     * @access public
     * @return array
     */
    public function getAllUsers()
    {
        set_time_limit(0);
        $users = array();
        $response = $this->queryAPI($this->apiUrl . "contact/v3/users", json_encode(array('department_id' => 0)), array(CURLOPT_CUSTOMREQUEST => "GET"));

        if(isset($response->data->items))
        {
            foreach($response->data->items as $user) $users[$user->name] = $user->open_id;
        }

        return array('result' => 'success', 'data' => $users);
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
        $postData = json_decode($message, true);
        $postData['open_ids'] = explode(',', $userList);

        $url = $this->apiUrl . 'message/v4/batch_send/';
        $response = $this->queryAPI($url, json_encode($postData));

        if(isset($response->code) and $response->code == 0) return array('result' => 'success');
        return array('result' => 'fail', 'message' => $this->errors);
    }

    /**
     * Query API.
     * 
     * @param  string $url 
     * @access public
     * @return string
     */
    public function queryAPI($url, $data = '', $opt = array())
    {
        $headers = array();
        $headers[] = "Content-Type: application/json";
        if($this->token) $headers[] = "Authorization:Bearer {$this->token}";

        $response = common::http($url, $data, $opt, $headers);
        $errors   = commonModel::$requestErrors;

        $response = json_decode($response);
        if(isset($response->code) and $response->code == 0) return $response;

        if(empty($response)) $this->errors = $errors;
        if(isset($response->code)) $this->errors[$response->code] = "Errcode:{$response->code}, Errmsg:{$response->msg}";
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
