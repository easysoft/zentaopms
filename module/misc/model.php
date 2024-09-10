<?php
declare(strict_types=1);
/**
 * The model file of misc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php
class miscModel extends model
{
    /**
     * 获取禅道数据库的表名和状态。
     * Get table and status.
     *
     * @param  string      $type check|repair
     * @access public
     * @return array|false
     */
    public function getTableAndStatus(string $type = 'check'): array|false
    {
        if($type != 'check' && $type != 'repair') return false;

        $tables = array();
        $stmt   = $this->dao->query("show full tables");
        while($table = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $tableName = $table["Tables_in_{$this->config->db->name}"];
            $tableType = strtolower($table['Table_type']);
            if($tableType == 'base table')
            {
                $tableStatus        = $this->dao->query("$type table $tableName")->fetch();
                $tables[$tableName] = strtolower($tableStatus->Msg_text);
            }
        }
        return $tables;
    }

    /**
     * 获取新增年度总结功能的通知。
     * Get remind.
     *
     * @access public
     * @return string
     */
    public function getRemind(): string
    {
        $remind = '';
        if(!empty($this->config->global->showAnnual) && empty($this->config->global->annualShowed))
        {
            $remind  = '<h4>' . $this->lang->misc->showAnnual . '</h4>';
            $remind .= '<p>' . sprintf($this->lang->misc->annualDesc, helper::createLink('report', 'annualData')) . '</p>';
            $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.annualShowed", 1);
        }
        return $remind;
    }

    /**
     * 获取插件到期通知。
     * Get the notification information about plugin expiration.
     *
     * @access public
     * @return string
     */
    public function getPluginRemind(): string
    {
        $plugins = $this->loadModel('extension')->getExpiringPlugins();
        $remind  = '';

        $today = helper::today();
        $showPluginRemind = (empty($this->config->global->showPluginRemind) || $this->config->global->showPluginRemind != $today) ? true : false;
        if(!empty($plugins) && $this->app->user->admin && $showPluginRemind)
        {
            $pluginButton = html::a(helper::createLink('extension', 'browse'), $this->lang->misc->view, '', "id='pluginButton' class='btn primary wide mr-2' data-app='admin'");
            $remind  = '<p>' . sprintf($this->lang->misc->expiredTipsForAdmin, count($plugins)) . '</p>';
            $remind .= '<p class="text-center mt-4">' . $pluginButton . '</p>';

            $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.showPluginRemind", $today);
        }
        return $remind;
    }

    /**
     * 检查一键安装包的安全性。
     * Check one click package.
     *
     * @access public
     * @return array
     */
    public function checkOneClickPackage(): array
    {
        $weakSites = array();
        if(strpos('|/zentao/|/biz/|/max/|', "|{$this->config->webRoot}|") !== false)
        {
            $databases = array('zentao' => 'zentao', 'zentaobiz' => 'zentaobiz', 'zentaoep' => 'zentaoep', 'zentaomax' => 'zentaomax');
            $basePath  = dirname($this->app->getBasePath());
            foreach($databases as $database)
            {
                $zentaoDirName = $database;
                if(!is_dir($basePath . '/' . $zentaoDirName))
                {
                    if($zentaoDirName == 'zentaobiz' && !is_dir($basePath . '/zentaoep'))  continue;
                    if($zentaoDirName == 'zentaoep'  && !is_dir($basePath . '/zentaobiz')) continue;
                    if($zentaoDirName == 'zentao'    || $zentaoDirName == 'zentaomax')     continue;

                    if($zentaoDirName == 'zentaobiz') $zentaoDirName = 'zentaoep';
                }

                try
                {
                    $webRoot = "/{$database}/";
                    if($database == 'zentao')    $webRoot = '/zentao/';
                    if($database == 'zentaobiz') $webRoot = '/biz/';
                    if($database == 'zentaoep')  $webRoot = '/biz/';
                    if($database == 'zentaomax') $webRoot = '/max/';

                    $user = $this->dbh->query("select * from {$database}.`zt_user` where account = 'admin' and password='" . md5('123456') . "'")->fetch();
                    if($user)
                    {
                        $site = array();
                        $site['path'] = basename($basePath) . '/' . $zentaoDirName;
                        $site['database'] = $database;
                        $weakSites[$database] = $site;
                    }
                }
                catch(Exception $e){}
            }
        }

        return $weakSites;
    }

    /**
     * 获取升级提示的通知。
     * Get upgrade remind.
     *
     * @access public
     * @return bool
     */
    public function getUpgradeRemind(): bool
    {
        if(!empty($this->config->global->hideUpgradeGuide)) return false;

        $remind = false;
        if(empty($this->config->global->showUpgradeGuide))
        {
            $remind = true;
            $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.showUpgradeGuide", 1);
        }
        return $remind;
    }


    /**
     * 获取最近版本列表.
     * Get the latest version list
     * @param  string       $url
     * @param  string|array $data
     * @param  array        $options   This is option and value pair, like CURLOPT_HEADER => true. Use curl_setopt function to set options.
     * @param  array        $headers   Set request headers.
     * @param  string       $dataType
     * @param  string       $method    POST|PATCH|PUT
     * @param  int          $timeout
     * @param  bool         $httpCode
     * @param  bool         $log
     * @static
     * @access public
     * @return string
     */
    public function getLatestVersionList($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
    {
        /* Module detection. */
        global $lang, $app;
        if(!extension_loaded('curl'))
        {
            if($dataType == 'json') return print($lang->error->noCurlExt);
            return json_encode(array('result' => 'fail', 'message' => $lang->error->noCurlExt));
        }


        /* Set HTTP request header. */
        commonModel::$requestErrors = array();
        $requestType = 'GET';
        if(!is_array($headers)) $headers = (array)$headers;

        $headers[] = 'API-RemoteIP: ' . helper::getRemoteIp(); /* Real IP of real user. */
        $headers[] = 'API-LocalIP: ' . zget($_SERVER, 'SERVER_ADDR', ''); /* Server IP of self. */

        if($dataType == 'json')
        {
            $headers[] = 'Content-Type: application/json;charset=utf-8';
            if(!empty($data)) $data = json_encode($data);
        }

        /* Set curl configuration options. */
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'ZenTao PMS ' . $app->config->version);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING,'');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_HEADER, $httpCode);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 2);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        curl_setopt($curl, CURLOPT_COOKIE, $_SERVER['HTTP_COOKIE']);

        if(!empty($data))
        {
            if(is_object($data)) $data = (array) $data;
            if($method == 'POST') curl_setopt($curl, CURLOPT_POST, true);
            if(in_array($method, array('PATCH', 'PUT'))) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            $requestType = $method;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        /* Send request. */
        if($options) curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        $errno  = curl_errno($curl);
        $errors = empty($errno)  ? 0 : curl_error($curl);
        $info   = curl_getinfo($curl);

        /* Processing response data. */
        if($httpCode)
        {
            $httpCode     = $info['http_code'] ?? curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $headerSize   = $info['header_size'] ?? curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $headerString = substr($response, 0, $headerSize);
            $body         = substr($response, $headerSize);

            /* Parse header. */
            $header    = explode("\n", $headerString);
            $newHeader = array();
            foreach($header as $item)
            {
                $field = explode(':', $item);
                if(count($field) < 2) continue;
                $headerKey = array_shift($field);
                $newHeader[$headerKey] = implode('', $field);
            }
        }

        curl_close($curl);

        if($log or $app->config->debug)
        {
            $runMode = PHP_SAPI == 'cli' ? '_cli' : '';
            $logFile = $app->getLogRoot() . 'saas' . $runMode . '.' . date('Ymd') . '.log.php';
            if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . '>');

            $fh = fopen($logFile, 'a');
            if($fh)
            {
                fwrite($fh, date('Ymd H:i:s') . ': ' . $app->getURI() . "\n");
                fwrite($fh, "{$requestType} url:    " . $url . "\n");
                if(!empty($data)) fwrite($fh, 'data:   ' . print_r($data, true) . "\n");
                fwrite($fh, 'results:' . print_r($response, true) . "\n");

                if(!empty($errors))
                {
                    fwrite($fh, 'errno: ' . $errno . "\n");
                    fwrite($fh, 'errors: ' . $errors . "\n");
                    fwrite($fh, 'info: ' . print_r($info, true) . "\n");
                }

                fclose($fh);
            }
        }

        if($errors) commonModel::$requestErrors[] = $errors;

        return $httpCode ? array($response, $httpCode, 'body' => $body, 'header' => $newHeader, 'errno' => $errno, 'info' => $info, 'response' => $response) : $response;
    }
}
