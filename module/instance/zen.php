<?php
declare(strict_types=1);
class instanceZen extends instance
{
    /**
     * 获取最新一条app动态。
     * Get the last article of an app.
     *
     * @param  int       $appID
     * @access protected
     * @return string
     */
    protected function getLastArticle(int $appID): string
    {
        $appInfo = $this->store->getAppInfo($appID, true);
        if(empty($appInfo)) return '';

        $dynamicResult = $this->store->appDynamic($appInfo, 1, 1);
        if(empty($dynamicResult) || empty($dynamicResult->articles)) return '';

        return $dynamicResult->articles[0]->title;
    }
}
