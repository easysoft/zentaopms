<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';

/**
 * 镜像仓库浏览页 UI 测试。
 *
 */
class browseTester extends tester
{
    /**
     * 打开镜像代码库 browse 页（默认进入 mirror=1 的代码库）。
     *
     * @param  int    $repoID
     * @access public
     * @return object
     */
    public function openMirrorBrowse(int $repoID = 1)
    {
        $this->login();
        $this->openURL('repo', 'browse', array('repoID' => $repoID), 'appIframe-devops');
        $this->page->wait(1);
        $page = $this->loadPage('repo', 'browse');
        $page->dom->switchToIframe('appIframe-devops');
        return $page;
    }

    /**
     * 校验 sync-code-btn 存在且带 ajax-submit class。
     *
     * @access public
     * @return object
     */
    public function checkSyncCodeBtnExists()
    {
        $page = $this->openMirrorBrowse();
        $xpath = "//button[contains(@class,'sync-code-btn')]";
        try
        {
            $page->dom->waitElement($xpath, 3)->getElement($xpath);
        }
        catch(Exception $e)
        {
            return $this->failed('未找到 sync-code-btn');
        }
        $class = (string)$page->dom->attr('class');
        if(strpos($class, 'ajax-submit') === false) return $this->failed('sync-code-btn 缺少 ajax-submit class');
        return $this->success('sync-code-btn 已交由 ajax-submit 接管');
    }

    /**
     * 校验 sync-code-btn 的 href 指向 ajaxMirrorSync。
     *
     * @access public
     * @return object
     */
    public function checkSyncCodeBtnUrl()
    {
        $page = $this->openMirrorBrowse();
        $xpath = "//button[contains(@class,'sync-code-btn')]";
        try
        {
            $page->dom->waitElement($xpath, 3)->getElement($xpath);
        }
        catch(Exception $e)
        {
            return $this->failed('未找到 sync-code-btn');
        }
        $href = (string)$page->dom->attr('href');
        if($href === '') $href = (string)$page->dom->attr('data-url');
        if(stripos($href, 'ajaxmirrorsync') === false) return $this->failed('sync-code-btn href 未指向 ajaxMirrorSync');
        return $this->success('sync-code-btn href 指向 ajaxMirrorSync');
    }

    /**
     * 校验点击 sync-code-btn 后 ajax-submit 接管，无 JS 报错。
     *
     * @access public
     * @return object
     */
    public function checkSyncCodeBtnClick()
    {
        $page = $this->openMirrorBrowse();
        $xpath = "//button[contains(@class,'sync-code-btn')]";
        try
        {
            $page->dom->waitElement($xpath, 3)->getElement($xpath);
        }
        catch(Exception $e)
        {
            return $this->failed('未找到 sync-code-btn');
        }
        $page->dom->click();
        $page->wait(2);
        $errors = $page->dom->getErrorsInPage('appIframe-devops');
        if(!empty($errors)) return $this->failed('点击 sync-code-btn 后页面报错');
        return $this->success('sync-code-btn 点击触发 ajax-submit 范式');
    }

    /**
     * 校验 mirror 仓库工具栏按钮存在性（sync-code-btn 或 refresh-sync-btn 至少其一）。
     * 由 syncing 与非 syncing 两态二选一渲染。
     *
     * @access public
     * @return object
     */
    public function checkMirrorToolbarBtnPresent()
    {
        $page = $this->openMirrorBrowse();
        $page->dom->getElementList("//button[contains(@class,'sync-code-btn')]");
        $syncCount = is_array($page->dom->element) ? count($page->dom->element) : 0;
        $page->dom->getElementList("//button[contains(@class,'refresh-sync-btn')]");
        $refreshCount = is_array($page->dom->element) ? count($page->dom->element) : 0;
        if($syncCount + $refreshCount < 1) return $this->failed('mirror 工具栏既无同步代码也无刷新按钮');
        if($syncCount > 0 && $refreshCount > 0) return $this->failed('同步代码与刷新按钮不应同时存在');
        return $this->success('mirror 工具栏按钮存在性正确');
    }

    /**
     * 校验 syncFailed 时失败提示与详情链接结构一致（提示存在则详情链接必存在）。
     *
     * @access public
     * @return object
     */
    public function checkSyncFailureAlertStructure()
    {
        $page = $this->openMirrorBrowse();
        $page->dom->getElementList("//*[contains(@class,'sync-failure-alert')]");
        $alertCount = is_array($page->dom->element) ? count($page->dom->element) : 0;
        $page->dom->getElementList("//*[contains(@class,'sync-failure-detail')]");
        $detailCount = is_array($page->dom->element) ? count($page->dom->element) : 0;
        if($alertCount > 0 && $detailCount < 1) return $this->failed('失败提示存在但缺少详情链接');
        if($alertCount < 1 && $detailCount > 0) return $this->failed('详情链接存在但缺少失败提示');
        return $this->success('syncFailed 提示与详情链接结构一致');
    }
}
