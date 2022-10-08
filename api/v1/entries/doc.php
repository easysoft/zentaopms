<?php
/**
 * The doc entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class docEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function get($docID)
    {
        $this->resetOpenApp($this->param('tab', 'doc'));

        $control = $this->loadController('doc', 'view');
        $control->view($docID);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $doc  = $data->data->doc;

        unset($doc->draft);
        if(!empty($doc->files)) $doc->files = array_values((array)$doc->files);

        /* Set lib name */
        $doc->libName = $data->data->lib->name;

        $preAndNext = $data->data->preAndNext;
        $doc->preAndNext = array();
        $doc->preAndNext['pre']  = $preAndNext->pre  ? $preAndNext->pre->id : '';
        $doc->preAndNext['next'] = $preAndNext->next ? $preAndNext->next->id : '';

        $this->send(200, $this->format($doc, 'addedBy:user,addedDate:time,assignedTo:user,assignedDate:date,editedBy:user,editedDate:time,mailto:userList'));
    }

    /**
     * PUT method.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function put($storyID)
    {
        $oldStory = $this->loadModel('story')->getByID($storyID);

        /* Set $_POST variables. */
        $fields = 'type';
        $this->batchSetPost($fields, $oldStory);
        $this->setPost('parent', 0);

        $control = $this->loadController('story', 'edit');
        $control->edit($storyID);

        $this->getData();
        $story = $this->story->getByID($storyID);
        $this->sendSuccess(200, $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function delete($storyID)
    {
        $control = $this->loadController('story', 'delete');
        $control->delete($storyID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
