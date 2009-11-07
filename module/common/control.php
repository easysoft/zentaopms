<?php
/**
 * The control file of common module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class common extends control
{
    /**
     * 构造函数：启动会话，加载公司模块，并设置公司信息。
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        ini_set('session.cookie_lifetime', 3600);
        ini_set('session.gc_maxlifetime', 3600); 
        session_start();
        $this->sendHeader();
        $this->loadModel('company');
        $this->setCompany();
        $this->setUser();
    }

    /**
     * 检查用户对当前的请求有没有权限。如果没有权限，则跳转到登陆界面。
     * 
     * @access public
     * @return void
     */
    public function checkPriv()
    {
        $module = $this->app->getModuleName();
        $method = $this->app->getMethodName();
        if($module == 'user')
        {
            if($method == 'login' or $method == 'logout' or $method == 'deny') return true;
        }

        if(isset($this->app->user))
        {
            if(!common::hasPriv($module, $method))
            {
                $referer  = helper::safe64Encode($_SERVER['HTTP_REFERER']);
                $denyLink = $this->createLink('user', 'deny', "module=$module&method=$method&referer=$referer");

                /* Fix the bug of IE: use js locate, can't get the referer. */
                if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
                {
                echo <<<EOT
<a href='$denyLink' id='denylink' style='display:none'>deny</a>
<script language='javascript'>document.getElementById('denylink').click();</script>
EOT;
                }
                else
                {
                    echo js::locate($denyLink);
                }
                exit;
            }
        }
        else
        {
            $this->locate($this->createLink('user', 'login'));
        }
    }

    /* 检查当前用户对某一个模块的某一个访问是否有权限访问。*/
    public static function hasPriv($module, $method)
    {
        global $app;

        /* 检查是否是管理员。*/
        $account = ',' . $app->user->account . ',';
        if(strpos($app->company->admins, $account) !== false) return true; 

        /* 非管理员，则检查权限列表中是否存在。*/
        $rights  = $app->user->rights;
        if(isset($rights[$module][$method])) return true;
        return false;
    }

    /**
     * 设置当前访问的公司信息。
     * 
     * 首先尝试按照当前访问的域名查找对应的公司信息，如果无法查到，再按照默认的域名进行查找。
     * 获取公司信息之后，将其写入到$_SESSION中。
     *
     * @access public
     * @return void
     */
    private function setCompany()
    {
        if(isset($_SESSION['company']) and $_SESSION['company']->pms == $_SERVER['HTTP_HOST'])
        {
            $this->app->setSessionCompany($_SESSION['company']);
        }
        $company = $this->company->getByDomain();
        if(!$company) $company = $this->company->getByDomain($this->config->default->domain);
        if(!$company) $this->app->error(sprintf($this->lang->error->companyNotFound, $_SERVER['HTTP_HOST']), __FILE__, __LINE__, $exit = true);
        $_SESSION['company'] = $company;
        $this->app->setSessionCompany($company);
    }

    /**
     * 设置当前访问的用户信息。
     * 
     * @access public
     * @return void
     */
    private function setUser()
    {
        if(isset($_SESSION['user']))
        {
            $this->app->setSessionUser($_SESSION['user']);
        }
        elseif($this->app->company->guest)
        {
            $user = new stdClass();
            $user->account  = 'guest';
            $user->realname = 'guest';
            $this->loadModel('user');
            $user->rights = $this->user->authorize('guest');
            $_SESSION['user'] = $user;
            $this->app->setSessionUser($_SESSION['user']);
        }
    }

    /* 保存最后浏览的产品id到session会话中。*/
    public static function saveProductState($productID, $defaultProductID)
    {
        global $app;
        if($productID > 0) $app->session->set('product', (int)$productID);
        if($productID == 0 and $app->session->product == '') $app->session->set('product', $defaultProductID);
        return $app->session->product;
    }

    /* 保存最后浏览的项目id到session会话中。*/
    public static function saveProjectState($projectID, $projects)
    {
        global $app;
        if($projectID > 0) $app->session->set('project', (int)$projectID);
        if($projectID == 0 and $app->session->project == '') $app->session->set('project', $projects[0]);
        if(!in_array($app->session->project, $projects)) $app->session->set('project', $projects[0]);
        return $app->session->project;
    }

    /**
     * 发送header信息到浏览器。
     * 
     * @access public
     * @return void
     */
    public function sendHeader()
    {
        header("Content-Type: text/html; Language={$this->config->encoding}");
        header("Cache-control: private");
    }

    /* 比较两个数组元素的不同，产生修改记录。*/
    public static function createChanges($old, $new)
    {
        $changes = array();
        foreach($new as $key => $value)
        {
            if(strtolower($key) == 'lastediteddate') continue;
            if($new->$key !== $old->$key)
            { 
                $diff = '';
                if(substr_count($value, "\n") > 1 or substr_count($old->$key, "\n") > 1) $diff = self::diff($old->$key, $value);
                $changes[] = array('field' => $key, 'old' => $old->$key, 'new' => $value, 'diff' => $diff);
            }
        }
        return $changes;
    }

    /* 比较两个字符串的不同。摘自PHPQAT自动化测试框架。*/
    public static function diff($text1, $text2)
    {
        $w  = explode("\n", $text1);
        $o  = explode("\n", $text2);
        $w1 = array_diff_assoc($w,$o);
        $o1 = array_diff_assoc($o,$w);
        $w2 = array();
        $o2 = array();
        foreach($w1 as $idx => $val) $w2[sprintf("%03d<",$idx)] = sprintf("%03d- ", $idx+1) . "<del>" . trim($val) . "</del>";
        foreach($o1 as $idx => $val) $o2[sprintf("%03d>",$idx)] = sprintf("%03d+ ", $idx+1) . "<ins>" . trim($val) . "</ins>";
        $diff = array_merge($w2, $o2);
        ksort($diff);
        return implode("\n", $diff);
    }
}
