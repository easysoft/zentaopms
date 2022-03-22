<?php
/**
 * The module entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class moduleStoriesEntry extends Entry
{
    public function get($moduleID)
    {
        $executionID = $this->param('executionID', 0);
        if(empty($executionID)) return $this->sendError(400, 'Need execution id.');

        $control = $this->loadController('execution', 'story');
        $control->story($executionID, 'order_desc', 'byModule', $moduleID);

        $data = $this->getData();

        $result = [];
        foreach($data->data->stories as $story)
        {
            $result[] = $this->filterFields($story, 'id,title,module,pri,status,stage,estimate');
        }
        return $this->send(200, array('stories' => $result));
    }
}
