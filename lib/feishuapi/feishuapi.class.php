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
     * @param  string $appId
     * @param  string $appSecret
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
    public function getAllUsers($selectedDepts = '')
    {
        $selectedDepts = trim($selectedDepts);
        if(empty($selectedDepts)) return array('result' => 'fail', 'message' => 'nodept');

        set_time_limit(0);

        $users = array();
        $depts = explode(',', $selectedDepts);
        $depts = array_flip($depts);
        unset($depts[1]);
        if(empty($depts)) return array('result' => 'fail', 'message' => 'nodept');

        /* Get users by dept. */
        foreach($depts as $deptID => $count)
        {
            if(empty($deptID)) continue;

            $pageToken = '';
            while(true)
            {
                $response = $this->queryAPI($this->apiUrl . "contact/v3/users?department_id={$deptID}&page_size=50" . ($pageToken ? "&page_token={$pageToken}" : ''), '', array(CURLOPT_CUSTOMREQUEST => "GET"));
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
     * Get department tree structure.
     *
     * @param  string    $departmentID
     * @access public
     * @return array
     */
    public function getChildDeptTree($departmentID)
    {
        /* Get depts by parent dept. */
        $depts     = array();
        $pageToken = '';
        $index     = 0;
        while(true)
        {
            $response = $this->queryAPI($this->apiUrl . "contact/v3/departments?parent_department_id={$departmentID}" . ($pageToken ? "&page_token={$pageToken}" : '') . "&fetch_child=false&page_size=50", '', array(CURLOPT_CUSTOMREQUEST => "GET"));
            if(isset($response->data->items))
            {
                foreach($response->data->items as $key => $dept)
                {
                    $depts[$index]['id']   = $dept->open_department_id;
                    $depts[$index]['pId']  = empty($dept->parent_department_id) ? 1 : $dept->parent_department_id;
                    $depts[$index]['name'] = $dept->name;
                    $depts[$index]['open'] = 1;
                    $index++;
                }
            }

            if(!isset($response->data->page_token)) break;
            $pageToken = $response->data->page_token;
        }

        return $depts;
    }

    /**
     * Get the first tier department.
     *
     * @access public
     * @return array
     */
    public function getDeptTree()
    {
        $depts = array('data' => array());

        /* Gets the enterprise name. */
        $response = $this->queryAPI($this->apiUrl . "tenant/v2/tenant/query", '', array(CURLOPT_CUSTOMREQUEST => "GET"));
        $company  = array('id' => '1', 'pId' => '0', 'name' => $response->data->tenant->name, 'open' => 1);
        $depts    = array($company);

        $departmentIdList = $this->getScopes();

        $urls = array();
        foreach($departmentIdList as $departmentID) $urls[] = $this->apiUrl . "contact/v3/departments/{$departmentID}";
        $datas = $this->multiRequest($urls);

        foreach($datas as $index => $dept)
        {
            $index += 1;
            $dept   = json_decode($dept);

            $memberCount = $dept->data->department->member_count;
            $status      = $dept->data->department->status->is_deleted;

            $depts[$index]['id']   = $dept->data->department->open_department_id;
            $depts[$index]['pId']  = empty($dept->data->department->parent_department_id) ? 1 : $dept->data->department->parent_department_id;
            $depts[$index]['name'] = $dept->data->department->name;
            $depts[$index]['open'] = 1;
        }

        return $depts;
    }

    /**
     * Get the visible range of the application.
     *
     * @access public
     * @return array
     */
    public function getScopes()
    {
        $pageToken        = '';
        $departmentIdList = array();

        while(true)
        {
            $response      = $this->queryAPI($this->apiUrl . "contact/v3/scopes" . "?user_id_type=open_id&department_id_type=open_department_id&page_token={$pageToken}&page_size=100", '', array(CURLOPT_CUSTOMREQUEST => "GET"));
            $departmentIds = isset($response->data->department_ids) ? $response->data->department_ids : array();
            foreach($departmentIds as $id) $departmentIdList[] = $id;

            if(!isset($response->data->page_token)) break;
            $pageToken = $response->data->page_token;
        }

        return $departmentIdList;
    }

    /**
     * Handle the concurrency of requests.
     *
     * @param  array    $urls
     * @access public
     * @return array
     */
    public function multiRequest($urls)
    {
        $curl        = curl_multi_init();
        $urlHandlers = array();
        $urlData     = array();

        /* Set request header information. */
        $headers   = array();
        $headers[] = "Content-Type: application/json";
        if($this->token) $headers[] = "Authorization:Bearer {$this->token}";

        /* Initialize multiple request handles to one. */
        foreach($urls as $url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            if(!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] != 'on')
            {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }

            $urlHandlers[] = $ch;
            curl_multi_add_handle($curl, $ch);
        }

        $active = null;
        do
        {
            $mrc = curl_multi_exec($curl, $active);
        }
        while($mrc == CURLM_CALL_MULTI_PERFORM);

        while($active and $mrc == CURLM_OK)
        {
            usleep(50000);
            if(curl_multi_select($curl) != -1)
            {
                do
                {
                    $mrc = curl_multi_exec($curl, $active);
                }
                while($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        foreach($urlHandlers as $index => $ch)
        {
            $urlData[$index] = curl_multi_getcontent($ch);
            curl_multi_remove_handle($curl, $ch);
        }
        curl_multi_close($curl);
        return $urlData;
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
    public function queryAPI($url, $data = '', $options = array())
    {
        $headers = array();
        $headers[] = "Content-Type: application/json";
        if($this->token) $headers[] = "Authorization:Bearer {$this->token}";

        $response = common::http($url, $data, $options, $headers);
        $errors   = commonModel::$requestErrors;

        $response = json_decode($response);
        if(isset($response->code) and $response->code == 0) return $response;

        if(empty($response)) $this->errors = $errors;
        if(isset($response->result) and $response->result == 'fail') $this->errors['curl'] = $response->message;
        if(isset($response->code)) $this->errors[$response->code] = "Errcode:{$response->code}, Errmsg:{$response->msg}";
        if(!empty($this->errors))
        {
            if(helper::isAjaxRequest())
            {
                http_response_code(500);
                echo array_shift($this->errors);
                die();
            }
            else
            {
                echo js::error(array_shift($this->errors));
                die(js::locate(helper::createLink('webhook', 'browse')));
            }
        }
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
