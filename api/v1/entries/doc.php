<?php
/**
 * The doc entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class docEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $docID
     * @access public
     * @return string
     */
    public function get($docID)
    {
        $this->resetOpenApp($this->param('tab', 'doc'));

        $control = $this->loadController('doc', 'view');

        $doc = $this->loadModel('doc')->getByID($docID, 0, true);
        if(!$doc) return $this->send400('error');

        unset($doc->draft);
        if(!empty($doc->files)) $doc->files = array_values((array)$doc->files);

        $lib = null;
        if($doc->lib) $lib = $this->doc->getLibByID((int)$doc->lib);
        $doc->libName = $lib ? $lib->name : '';

        $doc->preAndNext = array();
        $doc->preAndNext['pre']  = '';
        $doc->preAndNext['next'] = '';

        return $this->send(200, $this->format($doc, 'addedBy:user,addedDate:time,assignedTo:user,assignedDate:date,editedBy:user,editedDate:time,mailto:userList'));
    }

    /**
     * PUT method.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function put($storyID)
    {
        $control = $this->loadController('story', 'edit');
        $oldStory = $this->loadModel('story')->getByID($storyID);

        /* Set $_POST variables. */
        $fields = 'type';
        $this->batchSetPost($fields, $oldStory);
        $this->setPost('parent', 0);

        $control->edit($storyID);

        $this->getData();
        $story = $this->story->getByID($storyID);
        return $this->sendSuccess(200, $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function delete($storyID)
    {
        $control = $this->loadController('story', 'delete');
        $control->delete($storyID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
