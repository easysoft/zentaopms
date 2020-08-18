<?php
/**
 * The model file of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id: model.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class issueModel extends model
{
    public function create()
    {
        $now = helper::now();
        $data = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->addIF($this->post->assignedTo, 'assignedBy', $this->app->user->account)
            ->addIF($this->post->assignedTo, 'assignedDate', $now)
            ->stripTags($this->config->issue->editor->create['id'], $this->config->allowedTags)
            ->get();

        if(strpos($this->config->issue->create->requiredFields, 'type') !== false and !$this->post->type)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->type);
            return false;
        }

        if(strpos($this->config->issue->create->requiredFields, 'title') !== false and !$this->post->title)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->title);
            return false;
        }

        if(strpos($this->config->issue->create->requiredFields, 'severity') !== false and !$this->post->severity)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->severity);
            return false;
        }

        $this->dao->insert(TABLE_ISSUE)->data($data)->exec();
        return true;
    }
}
