<?php
class bugPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'          => "//*[@id='featureBar']/menu/li[1]/a",
            'unresolvedTab'   => "//*[@id='featureBar']/menu/li[2]/a",
            'bugNum'          => "//*[@id='table-project-bug']/div[3]/div[2]/strong",
            /* 1.5级产品导航 */
            'dropMenu'        => "//*[@id='pick-project-menu']",
            'firstProduct'    => "//*[@id='pick-pop-project-menu']/div[2]/div/div[1]/menu/li[2]/div",
            'secondProduct'   => "//*[@id='pick-pop-project-menu']/div[2]/div/div[1]/menu/li[3]/div",
            /* 列表 */
            'firstAssign'     => "//*[@id='table-project-bug']/div[2]/div[2]/div/div[7]/div/a/span",
            'secondCheckbox'  => "//*[@id='table-project-bug']/div[2]/div[1]/div/div[3]/div/div/label",
            'batchAssignTo'   => "//*[@id='table-project-bug']/div[3]/nav[1]/button/span[2]",
            'assignToAdmin'   => "//*[@class='popover show fade dropdown in']/menu/menu/li[1]/a/div/div",
            'confirmBtn'      => "//*[@id='table-project-bug']/div[2]/div[3]/div/div[1]/div/nav/a[1]",
            'firstConfirm'    => "//*[@id='table-project-bug']/div[2]/div[2]/div/div[6]/div",
            'resolveBtn'      => "//*[@id='table-project-bug']/div[2]/div[3]/div/div[2]/div/nav/a[1]/i",
            'secondStatus'    => "//*[@id='table-project-bug']/div[2]/div[2]/div/div[11]/div/span",
            /* 指派页面 */
            'assignTo'        => "//*[@id='assignedTo']/div/input",
            'submitBtn'       => "//*[@class='form load-indicator form-ajax no-morph form-horz']/div[4]/div/button/span",
            /* 确认页面 */
            'confirmAssignTo' => "//*[@name='assignedTo']",
            'confirm'         => "//*[@class='form load-indicator form-ajax no-morph form-horz']/div[8]/div/button/span",
            /* 解决页面 */
            'resolution'      => "//*[@name='resolution']",
            'build'           => "//*[@name='resolvedBuild']",
            'resolve'         => "//*[@class='toolbar form-actions form-group no-label']/button/span",
            'resolveTitle'           => "//*[@class='modal modal-async load-indicator modal-trans show in']/div/div/div[1]/div/div[2]/span[1]",
            /* 搜索列表 */
            'status'          => "//*[@id='table-project-bug']/div[2]/div[2]/div/div[3]/div/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
