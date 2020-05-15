<?php

/**
 * This is the PHP-SDK class of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD,  www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author
 * @package     api
 * @version     v1.0.0
 * @link        http://www.zentao.net
 */
class zentao
{
    const ztURL        = '';            // ZenTaoPMS deploys domain names.
    const ztAccount    = '';            // ZenTaoPMS login account.
    const ztPassword   = '';            // ZenTaoPMS login password.
    const ztAccessMode = '';            // Parameter request method. [GET|PATH_INFO]

    public $sessionRand   = 0;          // Session random number for some encryption and verification.
    public $tokenAuth     = '';         // Session authentication.
    public $requestMethod = '';         // Set request method.
    public $params        = array();    // Interface request parameter.
    public $returnResult  = array('status' => 0, 'msg' => 'error', 'result' => array());         // Return result.

    /**
     * Get the session ID required for the session.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->login();
    }

    /**
     * User login verification.
     *
     * @access public
     * @return void
     */
    public function login()
    {
        /* Get token. */
        $result = $this->setParams(array('m' => 'api', 'f' => 'getSessionID'))
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $result          = json_decode($result->data);
        $this->tokenAuth = $result->sessionName . '=' . $result->sessionID;

        /* User authentication login. */
        $this->setParams(array('account' => self::ztAccount, 'password' => self::ztPassword))
            ->setRequestMethod('post')
            ->sendRequest(array('get' => self::ztURL . '?m=user&f=login&t=json', 'path_info' => self::ztURL . '/user-login.json'));
    }

    /**
     * Get a list of departments.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getDeptList($optionalParams = array(), $extraFields = array())
    {
        $result = $this->setParams(array('m' => 'dept', 'f' => 'browse'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'deptID', 'parentDepts', 'sons', 'tree'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Add a new department.
     *
     * @param array $optionalParams
     * @access public
     * @return string
     */
    public function addDept($optionalParams = array())
    {
        $result = $this->setParams(array(), $optionalParams)
            ->setRequestMethod('post')
            ->sendRequest(array('get' => self::ztURL . '?m=dept&f=manageChild&t=json', 'path_info' => self::ztURL . '/dept-manageChild.json'));

        $returnResult = $this->returnResult;
        if (strpos($result, 'reload')) $returnResult = array('status' => 1, 'msg' => 'success', 'result' => array());

        return json_encode($returnResult, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get user list.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getUserList($optionalParams = array(), $extraFields = array())
    {
        $optionalParams = array_merge(array('param'=> 0, 'type'=> 'bydept', 'orderBy'=> 'id', 'recTotal'=> 999999, 'recPerPage' => 999999), $optionalParams);
        $result = $this->setParams(array('m' => 'company', 'f' => 'browse'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'users'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Add user optional information.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getUserCreateParams($optionalParams = array(), $extraFields = array())
    {
        $result = $this->setParams(array('m' => 'user', 'f' => 'create'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields       = array_merge(array('title', 'depts', 'groupList', 'roleGroup'), $extraFields);
        $returnResult      = $this->getResultData($result, $extraFields);
        $result            = json_decode($result->data);
        $this->sessionRand = $result->rand;
        return $returnResult;
    }

    /**
     * New users.
     *
     * @param array $optionalParams
     * @access public
     * @return string
     */
    public function addUser($optionalParams = array())
    {
        // Get the random number required for encryption.
        $this->getUserCreateParams();

        $optionalParams['password1']      = md5($optionalParams['password1'] . $this->sessionRand);
        $optionalParams['password2']      = md5($optionalParams['password2'] . $this->sessionRand);
        $optionalParams['verifyPassword'] = md5(md5(self::ztPassword) . $this->sessionRand);
        $requestURL['get']                = self::ztURL . '?m=user&f=create&dept=' . $optionalParams['dept'] . '&t=json';
        $requestURL['path_info']          = self::ztURL . '/user-create-' . $optionalParams['dept'] . '.json';

        $responseData = $this->setParams(array(), $optionalParams)
            ->setRequestMethod('post')
            ->sendRequest($requestURL, true);

        $returnResult = $this->getResultData($responseData);
        return $returnResult;
    }

    /**
     * Get product list.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getProductList($optionalParams = array(), $extraFields = array())
    {
        $optionalParams = array_merge(array('productID ' => 0, 'line' => 0, 'status' => 'all', 'orderBy' => 'order_desc', 'recTotal' => 999999, 'recPerPage' => 999999), $optionalParams);
        $result = $this->setParams(array('m' => 'product', 'f' => 'all'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'products', 'productStats'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Get added product optional information.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getProductCreateParams($optionalParams = array(), $extraFields = array())
    {
        $result = $this->setParams(array('m' => 'product', 'f' => 'create'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'products', 'lines', 'poUsers', 'qdUsers', 'rdUsers', 'groups'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Add a single product.
     *
     * @param array $optionalParams
     * @access public
     * @return string
     */
    public function addProduct($optionalParams = array())
    {
        $requestURL['get']       = self::ztURL . '?m=product&f=create&t=json';
        $requestURL['path_info'] = self::ztURL . '/product-create.json';

        $responseData = $this->setParams(array(), $optionalParams)
            ->setRequestMethod('post')
            ->sendRequest($requestURL, true);

        $returnResult = $this->getResultData($responseData);
        return $returnResult;
    }

    /**
     * Get project list.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getProjectList($optionalParams = array(), $extraFields = array())
    {
        $optionalParams = array_merge(array('status' => 'all', 'projectID' => 0, 'orderBy' => 'order_desc', 'productID' => 0, 'recTotal' => 999999, 'recPerPage' => 999999), $optionalParams);
        $result = $this->setParams(array('m' => 'project', 'f' => 'all'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'projects', 'projectStats', 'teamMembers', 'users'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Get optional information for adding items.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getProjectCreateParams($optionalParams = array(), $extraFields = array())
    {
        $result = $this->setParams(array('m' => 'project', 'f' => 'create'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'projects', 'groups', 'allProducts'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Add a single item.
     *
     * @param array $optionalParams
     * @access public
     * @return string
     */
    public function addProject($optionalParams = array())
    {
        $requestURL['get']       = self::ztURL . '?m=project&f=create&t=json';
        $requestURL['path_info'] = self::ztURL . '/project-create.json';

        $responseData = $this->setParams(array(), $optionalParams)
            ->setRequestMethod('post')
            ->sendRequest($requestURL, true);

        $returnResult = $this->getResultData($responseData);
        return $returnResult;
    }

    /**
     * Get task list.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getTaskList($optionalParams = array(), $extraFields = array())
    {
        $optionalParams = array_merge(array('projectID' => 0, 'status' => 'all', 'param' => 0, 'orderBy' => '', 'recTotal' => 999999, 'recPerPage' => 999999), $optionalParams);
        $result = $this->setParams(array('m' => 'project', 'f' => 'task'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'projects', 'project', 'products', 'tasks'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Add task optional information.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getTaskCreateParams($optionalParams = array(), $extraFields = array())
    {
        $result = $this->setParams(array('m' => 'task', 'f' => 'create'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'projects', 'users', 'stories', 'moduleOptionMenu', 'project'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Add a single task.
     *
     * @param array $optionalParams
     * @access public
     * @return string
     */
    public function addTask($optionalParams = array())
    {
        $requestURL['get']       = self::ztURL . '?m=task&f=create&project=' . $optionalParams['project'] . '&t=json';
        $requestURL['path_info'] = self::ztURL . '/task-create-' . $optionalParams['project'] . '.json';

        $responseData = $this->setParams(array('status' => 'wait', 'after' => 'toTaskList'), $optionalParams)
            ->setRequestMethod('post')
            ->sendRequest($requestURL, true);

        $returnResult = $this->getResultData($responseData);
        return $returnResult;
    }

    /**
     * Optional information for completing a single task.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getTaskFinishParams($optionalParams = array(), $extraFields = array())
    {
        $result = $this->setParams(array('m' => 'task', 'f' => 'finish'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'users', 'task', 'project', 'actions'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Complete a single task.
     *
     * @param array $optionalParams
     * @access public
     * @return string
     */
    public function addTaskFinish($optionalParams = array())
    {
        $taskID = $optionalParams['taskID'];
        unset($optionalParams['taskID']);
        $requestURL['get']       = self::ztURL . '?m=task&f=finish&taskID=' . $taskID . '&t=json';
        $requestURL['path_info'] = self::ztURL . '/task-finish-' . $taskID . '.json';

        $result = $this->setParams(array('status' => 'done'), $optionalParams)
            ->setRequestMethod('post')
            ->sendRequest($requestURL, false);

        $returnResult = $this->returnResult;
        if (strpos($result, 'task-view-' . $taskID . '.json') || strpos($result, 'taskID=' . $taskID)) $returnResult = array('status' => 1, 'msg' => 'success', 'result' => array());

        return json_encode($returnResult, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get BUG List.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getBugList($optionalParams = array(), $extraFields = array())
    {
        $optionalParams = array_merge(array('productID' => 0, 'branch' => 0, 'browseType' => 'all', 'param' => 0, 'orderBy' => '', 'recTotal' => 999999, 'recPerPage' => 999999), $optionalParams);
        $result = $this->setParams(array('m' => 'bug', 'f' => 'browse'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'products', 'productID', 'productName', 'product', 'moduleName', 'modules', 'browseType', 'bugs'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Add single BUG optional information.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getBugCreateParams($optionalParams = array(), $extraFields = array())
    {
        $result = $this->setParams(array('m' => 'bug', 'f' => 'create'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'productID', 'productName', 'projects', 'moduleOptionMenu', 'users', 'stories', 'builds'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Add a single bug.
     *
     * @param array $optionalParams
     * @access public
     * @return string
     */
    public function addBug($optionalParams = array())
    {
        $requestURL['get']       = self::ztURL . '?m=bug&f=create&productID=' . $optionalParams['product'] . '&t=json';
        $requestURL['path_info'] = self::ztURL . '/bug-create-' . $optionalParams['product'] . '.json';

        $responseData = $this->setParams(array('status' => 'active'), $optionalParams)
            ->setRequestMethod('post')
            ->sendRequest($requestURL, true);

        $returnResult = $this->getResultData($responseData);
        return $returnResult;
    }

    /**
     * Optional information for solving a single bug.
     *
     * @param array $optionalParams
     * @param array $extraFields
     * @access public
     * @return string
     */
    public function getBugResolveParams($optionalParams = array(), $extraFields = array())
    {
        $result = $this->setParams(array('m' => 'bug', 'f' => 'resolve'), $optionalParams)
            ->setRequestMethod('get')
            ->sendRequest(array('get' => self::ztURL), true);

        $extraFields  = array_merge(array('title', 'products', 'bug', 'users', 'builds', 'actions'), $extraFields);
        $returnResult = $this->getResultData($result, $extraFields);
        return $returnResult;
    }

    /**
     * Solve a single bug.
     *
     * @param array $optionalParams
     * @access public
     * @return string
     */
    public function addBugResolve($optionalParams = array())
    {
        $bugID                   = $optionalParams['bugID'];
        $requestURL['get']       = self::ztURL . '?m=bug&f=resolve&bugID=' . $bugID . '&t=json';
        $requestURL['path_info'] = self::ztURL . '/bug-resolve-' . $bugID . '.json';
        unset($optionalParams['bugID']);

        $result = $this->setParams(array('status' => 'resolved'), $optionalParams)
            ->setRequestMethod('post')
            ->sendRequest($requestURL);

        $returnResult = $this->returnResult;
        if (strpos($result, 'bug-view-' . $bugID . '.json') || strpos($result, 'bugID=' . $bugID)) $returnResult = array('status' => 1, 'msg' => 'success', 'result' => array());

        return json_encode($returnResult, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Send a get request.
     *
     * @param string $url
     * @access public
     * @return string
     */
    public function getUrl($url)
    {
        $ch = curl_init();
        if (self::ztAccessMode == 'GET')
        {
            $this->params = array_merge($this->params, array('t' => 'json'));
            $url         .= strpos($url, '?') ? http_build_query($this->params) : '?' . http_build_query($this->params);
        }
        elseif (self::ztAccessMode == 'PATH_INFO')
        {
            $params = implode('-', $this->params);
            $url    = $url . '/' . $params . '.json';
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_COOKIE, $this->tokenAuth);
        curl_setopt($ch, CURLOPT_REFERER, self::ztURL);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Send a post request.
     *
     * @param string $url
     * @access public
     * @return string
     */
    public function postUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_COOKIE, $this->tokenAuth);
        curl_setopt($ch, CURLOPT_REFERER, self::ztURL);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (count($this->params)) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Processing request parameters.
     *
     * @param array $requiredParams
     * @param array $optionalParams
     * @access public
     * @return $this
     */
    public function setParams($requiredParams = array(), $optionalParams = array())
    {
        $this->params = array();
        $this->params = array_merge($requiredParams, $optionalParams);
        return $this;
    }

    /**
     * Set request method.
     *
     * @param string $requestMethod
     * @access public
     * @return $this
     */
    public function setRequestMethod($requestMethod = 'get')
    {
        $this->requestMethod = strcmp($requestMethod, 'get') === 0 ? 'get' : 'post';
        return $this;
    }

    /**
     * Send a request for response results.
     *
     * @param array $requestURL
     * @param bool $isDecode
     * @access public
     * @return string $result
     */
    public function sendRequest($requestURL = array(), $isDecode = false)
    {
        if ($this->requestMethod == 'get')  $result = $this->getUrl($requestURL['get']);
        if ($this->requestMethod == 'post') $result = self::ztAccessMode == 'GET' ? $this->postUrl($requestURL['get']) : $this->postUrl($requestURL['path_info']);
        $result = $isDecode ? json_decode($result) : $result;
        return $result;
    }

    /**
     * Get the specified result.
     *
     * @param string $responseData
     * @param array $extraFields
     * @access public
     * @return string $result
     */
    public function getResultData($responseData = '', $extraFields = array())
    {
        $returnResult = $this->returnResult;
        if (!empty($responseData->status) && strcmp($responseData->status, 'success') === 0)
        {
            $returnResult = array('status' => 1, 'msg' => 'success');
            $responseData = json_decode($responseData->data);
            foreach ($extraFields as $k => $v)
            {
                isset($responseData->$v) ? $returnResult['result'][$v] = $responseData->$v : $returnResult['result'][$v] = array();
            }
            if (count($extraFields) == 0) $returnResult['result'] = $responseData;
        }
        elseif (!empty($responseData->result))
        {
            if (strcmp($responseData->result, 'success') === 0) $returnResult = array('status' => 1, 'msg' => 'success');
            $returnResult['result'] = $responseData->message;
        }
        return json_encode($returnResult, JSON_UNESCAPED_UNICODE);
    }
}
