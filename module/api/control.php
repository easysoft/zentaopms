<?php
/**
 * The control file of api of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class api extends control
{
    /**
     * Return session to the client.
     * 
     * @access public
     * @return void
     */
    public function getSessionID()
    {
        $this->session->set('rand', mt_rand(0, 10000));
        $this->view->sessionName = session_name();
        $this->view->sessionID   = session_id();
        $this->view->rand        = $this->session->rand;
        $this->display();
    }

    /**
     * Execute a module's model's method, return the result.
     * 
     * @param  string    $moduleName 
     * @param  string    $methodName 
     * @param  string    $params        param1=value1,param2=value2, don't use & to join them.
     * @access public
     * @return string
     */
    public function getModel($moduleName, $methodName, $params = '')
    {
        parse_str(str_replace(',', '&', $params), $params);
        $module = $this->loadModel($moduleName);
        $result = call_user_func_array(array(&$module, $methodName), $params);
        if(dao::isError()) die(json_encode(dao::getError()));
        $output['status'] = $result ? 'success' : 'fail';
        $output['data']   = json_encode($result);
        $output['md5']    = md5($output['data']);
        $this->output     = json_encode($output);
        die($this->output);
    }

    /**
     * The interface of api.
     * 
     * @param  int    $filePath 
     * @param  int    $action 
     * @access public
     * @return void
     */
    public function debug($filePath, $action)
    {
        if($filePath)
        {
            $host     = common::getSysURL() . $this->config->webRoot;
            $filePath = helper::safe64Decode($filePath);
            if($action == 'extendModel')
            {
                $method = $this->api->getMethod($filePath, 'Model');
            }
            elseif($action == 'extendControl')
            {
                $method = $this->api->getMethod($filePath);
            }

            if(!empty($_POST))
            {
                $param = '';
                if($action == 'extendModel')
                {
                    if(!isset($_POST['noparam']))
                    {
                        foreach($_POST as $key => $value) $param .= ',' . $key . '=' . $value;
                        $param = ltrim($param, ',');
                    }
                    $url   = rtrim($host, '/') . inlink('getModel',  "moduleName={$method->className}&methodName={$method->methodName}&params=$param", 'json');
                    $url  .= $this->config->requestType == "PATH_INFO" ? '?' : '&';
                    $url  .= $this->config->sessionVar . '=' . session_id();
                }
                else
                {
                    if(!isset($_POST['noparam']))
                    {
                        foreach($_POST as $key => $value) $param .= '&' . $key . '=' . $value;
                        $param = ltrim($param, '&');
                    }
                    $url   = rtrim($host, '/') . $this->createLink($method->className, $method->methodName, $param, 'json');
                    $url  .= $this->config->requestType == "PATH_INFO" ? '?' : '&';
                    $url  .= $this->config->sessionVar . '=' . session_id();
                }

                /* Unlock session. After new request, restart session. */
                session_write_close();
                $content = file_get_contents($url);
                session_start();

                $result  = json_decode($content);
                $status  = $result->status;
                $data    = json_decode($result->data);
                $data    = '<xmp>' . print_r($data, true) . '</xmp>';

                $response['result']  = 'success';
                $response['status']  = $status;
                $response['url']     = $url;
                $response['data']    = $data;
                $this->send($response);
            }

            $this->view->method   = $method;
            $this->view->filePath = $filePath;
            $this->display();
        }
    }
}
