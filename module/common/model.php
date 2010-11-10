<?php
/**
 * The model file of common module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class commonModel extends model
{
    /**
     * Start the session.
     * 
     * @access public
     * @return void
     */
    public function startSession()
    {
        session_name($this->config->sessionVar);
        if(isset($_GET[$this->config->sessionVar])) session_id($_GET[$this->config->sessionVar]);
        session_start();
    }

    /**
     * Set the header info.
     * 
     * @access public
     * @return void
     */
    public function sendHeader()
    {
        header("Content-Type: text/html; Language={$this->config->encoding}");
        header("Cache-control: private");
    }

    /**
     * Set the commpany.
     *
     * First, search company by the http host. If not found, search by the default domain. Last, use the first as the default. 
     * After get the company, save it to session.
     * @access public
     * @return void
     */
    public function setCompany()
    {        
        $httpHost = $this->server->http_host;
        if(strpos($httpHost, ":"))
        {
            $httpHost = explode(":", $httpHost);
            $httpHost = $httpHost[0];
        }

        if($this->session->company and $this->session->company->pms == $httpHost)
        {
            $this->app->company = $this->session->company;
        }
        else
        {
            $company = $this->loadModel('company')->getByDomain();
            if(!$company and isset($this->config->default->domain)) $company = $this->company->getByDomain($this->config->default->domain);
            if(!$company) $company = $this->company->getFirst();
            if(!$company) $this->app->error(sprintf($this->lang->error->companyNotFound, $httpHost), __FILE__, __LINE__, $exit = true);
            $this->session->set('company', $company);
            $this->app->company  = $company;
        }
    }

    /**
     * Set the user info.
     * 
     * @access public
     * @return void
     */
    public function setUser()
    {
        if($this->session->user)
        {
            $this->app->user = $this->session->user;
        }
        elseif($this->app->company->guest)
        {
            $user             = new stdClass();
            $user->id         = 0;
            $user->account    = 'guest';
            $user->realname   = 'guest';
            $user->rights     = $this->loadModel('user')->authorize('guest');
            $this->session->set('user', $user);
            $this->app->user = $this->session->user;
        }
    }

    /**
     * Get the run info.
     * 
     * @param mixed $startTime  the start time of this execution
     * @access public
     * @return array    the run info array.
     */
    public function getRunInfo($startTime)
    {
        $info['timeUsed'] = round(getTime() - $startTime, 4) * 1000;
        $info['memory']   = round(memory_get_peak_usage() / 1024, 1);
        $info['querys']   = count(dao::$querys);
        return $info;
    }

    /**
     * Diff two string. (see phpt)
     * 
     * @param string $text1 
     * @param string $text2 
     * @static
     * @access public
     * @return string
     */
    public static function diff($text1, $text2)
    {
        $w  = explode("\n", trim($text1));
        $o  = explode("\n", trim($text2));
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
