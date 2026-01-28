<?php
class todoPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'addTodo'         => '/html/body/div/div/div[1]/div[2]/div/a',
            'addTodoBtn'      => '/html/body/div[2]/div/div/div[2]/div/div[2]/form/div[15]/div/button',
            'fstTodoTitle'    => '/html/body/div/div/div[2]/div/div/div[2]/div[1]/div/div[2]/div/a',
            'secTodoTitle'    => '/html/body/div/div/div[2]/div/div/div[2]/div[1]/div/div[4]/div/a',
            'trdTodoTitle'    => '/html/body/div/div/div[2]/div/div/div[2]/div[1]/div/div[6]/div/a',
            'editTodoBtn'     => '/html/body/div[3]/div/div/div[2]/div/div/form/div[9]/div/button',
            'fstTodoStatus'   => '/html/body/div[1]/div/div[2]/div/div/div[2]/div[2]/div/div[5]/div/span',
            'fstTodoEdit'     => '/html/body/div[1]/div/div[2]/div/div/div[2]/div[3]/div/div[1]/div/nav/a[3]/i',
            'fstTodoStart'    => '/html/body/div/div/div[2]/div/div/div[2]/div[3]/div/div[1]/div/nav/a[1]/i',
            'fstTodoFinish'   => '/html/body/div/div/div[2]/div/div/div[2]/div[3]/div/div[1]/div/nav/a[1]/i',
            'fstTodoActivate' => '/html/body/div[1]/div/div[2]/div/div/div[2]/div[3]/div/div[1]/div/nav/a[1]/i',
            'fstTodoClose'    => '/html/body/div[1]/div/div[2]/div/div/div[2]/div[3]/div/div[1]/div/nav/a[2]/i',
            'moreBtn'         => '/html/body/div[1]/div/div[1]/div[2]/div/button',
            'batchAddBtn'     => '//*[@class="menu-wrapper popup search-menu is-contextmenu"]/menu/li[2]/a/div/div',
            'fstTitle'        => '//*[@id="name_0"]',
            'secTitle'        => '//*[@id="name_1"]',
            'trdTitle'        => '//*[@id="name_2"]',
            'saveBtn'         => '//*[@id="batchCreateTodoForm"]/div[2]/form/div[2]/button[1]',
            /* 待办列表 */
            'assignBtn'       => '//*[@id="table-my-todo"]/div[2]/div[3]/div/div/div/nav/a[2]/i',
            'date'            => '//*[@id="table-my-todo"]/div[2]/div[2]/div/div[2]/div',
            'more'            => '//*[@id="featureBar"]/menu/li[7]/a/span[1]',
            'assignedToOther' => '//*[@id="more"]/menu/menu/li[2]/a/div/div',
            'assignedToA'     => '//*[@id="table-my-todo"]/div[2]/div[2]/div/div[7]/div',
            'title'           => '//*[@id="table-my-todo"]/div[2]/div[1]/div/div[2]/div/a',
            'status'          => '//*[@id="table-my-todo"]/div[2]/div[2]/div/div[5]/div/span',
            'type'            => '//*[@id="table-my-todo"]/div[2]/div[2]/div/div[6]/div',
            /* 指派给页面 */
            'assignedTo'   => '//*[@name="assignedTo"]',
            'assignDate'   => '//*[@name="date"]',
            'submitBtn'    => '//*[@class="form load-indicator form-ajax no-morph form-horz"]/div[4]/div/button',
            /* 待办详情页*/
            'name'    => '//*[@class="modal modal-async load-indicator modal-trans show in"]/div/div/div[2]/div[4]/div/div/span[2]',
            'statusA' => '//*[@id="legendBasic"]/table/tbody/tr[2]/td',
            'typeA'   => '//*[@id="legendBasic"]/table/tbody/tr[3]/td',
            'dateA'   => '//*[@id="legendBasic"]/table/tbody/tr[5]/td',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
