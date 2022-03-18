<?php
/**
 * The story component entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 **/
class storyChildEntry extends Entry
{
    /** 
     * POST method.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     **/
    public function post($storyID)
    {   
        $fields = 'title,spec,verify,reviewer,type,plan,module,moduleOptionMenu,source,sourceNote,category,pri,estimate,keywords,parent';
        $this->batchSetPost($fields);

        $fields = explode(',', $fields);
        foreach($fields as $field) $this->setArrayPost($field);
        $story = $this->loadModel('story')->getById($storyID);

        $control = $this->loadController('story', 'batchCreate');
        $control->batchCreate($story->product, 0, 0, $storyID);

        $data = $this->getData();

        $story = $this->story->getById($data->idList[0]);
        $this->send(200, $this->format($story, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList'));
    }   

    public function setArrayPost($field)
    {   
        $_POST[$field] = array('0' => $_POST[$field]);
    }   
}

