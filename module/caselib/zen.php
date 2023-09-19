<?php
declare(strict_types=1);
class caselibZen extends caselib
{
    /**
     * 设置用例库菜单。
     * Set library menu.
     *
     * @param  array  $libraries
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function setLibMenu(array $libraries, int $libID): void
    {
        /* Set case lib menu. */
        $products = $this->loadModel('product')->getPairs();
        if(!empty($products) and $this->session->product) $this->loadModel('qa')->setMenu($this->session->product);
        if(empty($products)) $this->loadModel('qa')->setMenu();

        $this->lang->qa->menu->caselib['subModule'] .= ',testcase';

        if($libraries)
        {
            $libName = '';
            if(!isset($libraries[$libID]))
            {
                $lib     = $this->caselib->getByID($libID);
                $libName = $lib ? $lib->name : '';
            }
            $currentLibName = zget($libraries, $libID, $libName);
            setCookie("lastCaseLib", (string)$libID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

            $dropMenuLink = helper::createLink('caselib', 'ajaxGetDropMenu', "objectID={$libID}&module=caselib&method=browse");

            $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentLibName}'><span class='text'>{$currentLibName}</span> <span class='caret' style='margin-top: 3px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
            $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $output .= "</div></div>";

            $this->lang->switcherMenu = $output;
        }
    }

    /**
     * 设置用例库状态。
     * Save lib state.
     *
     * @param  int    $libID
     * @param  array  $libraries
     * @access public
     * @return int
     */
    public function saveLibState(int $libID = 0, array $libraries = array()): int
    {
        if($libID > 0) $this->session->set('caseLib', (int)$libID);
        if($libID == 0 and $this->cookie->lastCaseLib) $this->session->set('caseLib', $this->cookie->lastCaseLib);
        if($libID == 0 and $this->session->caseLib == '') $this->session->set('caseLib', key($libraries));
        if(!isset($libraries[$this->session->caseLib]))
        {
            $this->session->set('caseLib', key($libraries));
            $libID = $this->session->caseLib;
        }
        return $this->session->caseLib;
    }

    /**
     * 为浏览用例库的用例设置 session 和 cookie。
     * Set session and cookie for browse.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return void
     */
    public function setBrowseSessionAndCookie(int $libID = 0, string $browseType = 'all', int $param = 0): void
    {
        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');
        $this->session->set('caselibList', $this->app->getURI(true), 'qa');
        if($browseType != 'bymodule') $this->session->set('libBrowseType', $browseType);

        /* Save cookie. */
        helper::setcookie('preCaseLibID', (string)$libID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        if($this->cookie->preCaseLibID != $libID)
        {
            $_COOKIE['libCaseModule'] = 0;
            helper::setcookie('libCaseModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
        }
        if($browseType == 'bymodule') helper::setcookie('libCaseModule', $param, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
    }
}
