<?php
class dingapi
{
    public $apiUrl = 'https://oapi.dingtalk.com/';
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
     * @return void
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

        $response = $this->queryAPI($this->apiUrl . "gettoken?appkey={$this->appKey}&appsecret={$this->appSecret}");
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
        $depts = $this->getAllDepts();
        if($this->isError()) return array('result' => 'fail', 'message' => $this->errors);

        set_time_limit(0);
        $users = array();
        foreach($depts as $deptID => $deptName)
        {
            $response = $this->queryAPI($this->apiUrl . "user/simplelist?access_token={$this->token}&department_id={$deptID}");
            if($this->isError())
            {
                $this->getErrors();
                continue;
            }

            foreach($response->userlist as $user) $users[$user->name] = $user->userid;
        }

        return array('result' => 'success', 'data' => $users);
    }

    /**
     * Get all depts.
     * 
     * @access public
     * @return array
     */
    public function getAllDepts()
    {
        $response = $this->queryAPI($this->apiUrl . "department/list?access_token={$this->token}");
        if($this->isError()) return false;

        $whiteList = array();
        if($whiteList)
        {
            $parentIdList    = array();
            $whiteListParent = array();
            foreach($response->department as $dept)
            {
                if(!empty($dept->parentid)) $parentIdList[$dept->id] = $dept->parentid;
                if(in_array($dept->name, $whiteList)) $whiteListParent[$dept->id] = $dept->id;
            }
        }

        $deptPairs = array();
        foreach($response->department as $dept)
        {
            if($whiteList)
            {
                if(empty($dept->parentid)) continue;
                if(isset($whiteListParent[$dept->id]))
                {
                    $deptPairs[$dept->id] = $dept->name;
                    continue;
                }

                $isWhiteList = false;
                $parentID    = $dept->parentid;
                while(isset($parentIdList[$parentID]))
                {
                    if(isset($whiteListParent[$parentID]))
                    {
                        $isWhiteList = true;
                        break;
                    }

                    $parentID = $parentIdList[$parentID];
                }
                if(!$isWhiteList) continue;
            }

            $deptPairs[$dept->id] = $dept->name;
        }
        return $deptPairs;
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
        curl_setopt($curl, CURLOPT_USERAGENT, 'Sae T OAuth2 v0.1');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);

        curl_setopt($curl, CURLOPT_URL, $this->apiUrl . 'topapi/message/corpconversation/asyncsend_v2?access_token=' . $this->token);
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);

        $postData = array();
        $postData['agent_id']    = $this->agentId;
        $postData['userid_list'] = $userList;
        $postData['msg']         = $message;
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

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
