<?php
class dingapi
{
    public $apiUrl = 'https://oapi.dingtalk.com/';
    private $appKey;
    private $appSecret;
    private $token;
    private $expires;
    private $errors = array();

    public function __construct($appKey, $appSecret, $agentId, $apiUrl = '')
    {
        $this->appKey    = $appKey;
        $this->appSecret = $appSecret;
        $this->agentId   = $agentId;
        if($apiUrl) $this->apiUrl = rtrim($apiUrl, '/') . '/';

        if(!$this->getToken()) return array('result' => 'fail', 'message' => $this->errors);
    }

    public function getToken()
    {
        if($this->token and (time() - $this->expires) >= 0) return $this->token;

        $response = $this->queryAPI($this->apiUrl . "gettoken?appkey={$this->appKey}&appsecret={$this->appSecret}");
        if($this->isError()) return false;

        $this->token   = $response->access_token;
        $this->expires = time() + $response->expires_in;
        return $this->token;
    }

    public function getAllUsers()
    {
        $depts = $this->getAllDepts();
        if($this->isError()) return array('result' => 'fail', 'message' => $this->errors);

        $users = array();
        foreach($depts as $deptID => $deptName)
        {
            $response = $this->queryAPI($this->apiUrl . "user/simplelist?access_token={$this->token}&department_id={$deptID}");
            if($this->isError()) return array('result' => 'fail', 'message' => $this->errors);

            foreach($response->userlist as $user) $users[$user->name] = $user->userid;
        }

        return array('result' => 'success', 'data' => $users);
    }

    public function getAllDepts()
    {
        $response = $this->queryAPI($this->apiUrl . "department/list?access_token={$this->token}");
        if($this->isError()) return false;

        $deptPairs = array();
        foreach($response->department as $dept) $deptPairs[$dept->id] = $dept->name;
        return $deptPairs;
    }

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

        $response = json_encode($response);
        if(isset($response->errcode) and $response->errcode == 0) return array('result' => 'success');

        $this->errors[$response->errcode] = "Errcode:{$response->errcode}, Errmsg:{$response->errmsg}";
        return array('result' => 'fail', 'message' => $this->errors);
    }

    public function queryAPI($url)
    {
        $response = json_decode(file_get_contents($url));
        if(isset($response->errcode) and $response->errcode == 0) return $response;

        $this->errors[$response->errcode] = "Errcode:{$response->errcode}, Errmsg:{$response->errmsg}";
        return false;
    }

    public function isError()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        $errors = $this->errors;
        $this->errors = array();

        return $errors;
    }
}
