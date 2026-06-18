<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        /* 镜像仓库同步相关页面元素。
         * sync-code-btn   触发 ajax-submit 走 ajaxMirrorSync。
         * refresh-sync-btn syncing 状态下出现，用于轮询进度。
         * sync-failure-alert/sync-failure-detail syncFailed 状态下显示失败提示与详情链接。
         */
        $xpath = array(
            'syncCodeBtn'       => "//button[contains(@class,'sync-code-btn')]",
            'refreshSyncBtn'    => "//button[contains(@class,'refresh-sync-btn')]",
            'syncFailureAlert'  => "//*[contains(@class,'sync-failure-alert')]",
            'syncFailureDetail' => "//*[contains(@class,'sync-failure-detail')]"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
