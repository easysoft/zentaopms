<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createPivotTester extends tester
{
    /**
     * 输入创建表单字段内容。
     * Input fields.
     *
     * @param  array  $pivot
     * @access public
     */
    public function inputFields($pivot)
    {
        $form = $this->loadPage();
        $form->wait(1);
        $form->dom->btn($this->lang->pivot->group)->picker($pivot['group']);
        $form->dom->btn($this->lang->pivot->name)->picker($pivot['name']);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
    }

    /**
     * 创建透视表。
     * Create pivot table.
     *
     * @param  array  $pivot
     * @access public
     * @return object
     */
    public function create($pivot)
    {
        $form = $this->initForm('pivot', 'browse', '', 'appIframe-bi');
        $form->dom->btn($this->lang->pivot->create)->click();
        $fhis->inputFields($pivot);
        $form->wait(1);

        $browsePage = $this->loadPage('pivot', 'browse');
        $name = $browsePage->dom->firstName->getText();
        if($name == $pivot['name']) return $this->success('创建透视表成功');
        return $this->failed('创建透视表失败');
    }
}
