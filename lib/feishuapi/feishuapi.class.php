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
        $depts = $this->getDepts();

        /* Get users by dept. */
        foreach($depts as $deptID => $count)
        {
            if($deptID and empty($count)) continue;

            $pageToken = '';
            while(true)
            {
                $response = $this->queryAPI($this->apiUrl . "contact/v3/users?department_id={$deptID}" . ($pageToken ? "&page_token={$pageToken}" : ''), '', array(CURLOPT_CUSTOMREQUEST => "GET"));
                if(isset($response->data->items))
                {
                    foreach($response->data->items as $user) $users[$user->name] = $user->open_id;
                }

                if(!isset($response->data->page_token)) break;
                $pageToken = $response->data->page_token;
            }
        }

        /* Get users in root. */
        $pageToken = '';
        while(true)
        {
            $response = $this->queryAPI($this->apiUrl . "contact/v3/users" . ($pageToken ? "?page_token={$pageToken}" : ''), '', array(CURLOPT_CUSTOMREQUEST => "GET"));
            if(isset($response->data->items))
            {
                foreach($response->data->items as $user) $users[$user->name] = $user->open_id;
            }

            if(!isset($response->data->page_token)) break;
            $pageToken = $response->data->page_token;
        }

        return array('result' => 'success', 'data' => $users);
    }

    /**
     * Get depts.
     * 
     * @access public
     * @return array
     */
    public function getDepts()
    {
        set_time_limit(0);

        $depts = array('0' => '0');

        /* Get depts by parent dept. */
        $pageToken = '';
        while(true)
        {
            $response = $this->queryAPI($this->apiUrl . "contact/v3/departments?parent_department_id=0" . ($pageToken ? "&page_token={$pageToken}" : '') . "&fetch_child=true", '', array(CURLOPT_CUSTOMREQUEST => "GET"));
            if(isset($response->data->items))
            {
                foreach($response->data->items as $dept) $depts[$dept->open_department_id] = $dept->member_count;
            }

            if(!isset($response->data->page_token)) break;
            $pageToken = $response->data->page_token;
        }

        /* Get depts by root. */
        $pageToken = '';
        while(true)
        {
            $response = $this->queryAPI($this->apiUrl . "contact/v3/departments?fetch_child=true" . ($pageToken ? "&page_token={$pageToken}" : ''), '', array(CURLOPT_CUSTOMREQUEST => "GET"));
            if(isset($response->data->items))
            {
                foreach($response->data->items as $dept) $depts[$dept->open_department_id] = $dept->member_count;
            }

            if(!isset($response->data->page_token)) break;
            $pageToken = $response->data->page_token;
        }

        return $depts;
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
