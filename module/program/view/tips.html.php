<?php
/**
 * The tips view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     program
 * @version     $Id: tips.html.php 4769 2020-11-24 09:24:21Z tianshujie $
 * @link        https://www.zentao.net
 */
?>
<div style='margin: 0 auto; max-width: 400px'>
  <p><strong><?php echo $lang->program->afterInfo;?></strong></p>
  <div>
    <?php echo html::a($this->createLink('program', 'PRJManageMembers', "projectID=$projectID", '', '', $projectID), $lang->program->setTeam, '', "class='btn'");?>
    <?php 
    if($model == 'waterfall')
    {
        echo html::a($this->createLink('programplan', 'create', "projectID=$projectID", '', '', $projectID), $lang->programplan->create, '', "class='btn'");  
    }
    else
    {
        echo html::a($this->createLink('project', 'create', '', '', '', $projectID), $lang->project->create, '', "class='btn'");  
    }
    ?>
    <?php if($project->type != 'ops') echo html::a($this->createLink('program', 'PRJManageProducts', "projectID=$projectID&programID=$programID", '', '', $projectID), $lang->program->PRJManageProducts, '', "class='btn'");?>
    <?php 
    if($from = 'PMG')
    {
        echo html::a($this->createLink('program', 'PRJBrowse', '', '', '', $projectID), $lang->program->goback, '', "class='btn'");
    }
    else
    {
        echo html::a($this->createLink('program', 'PRJBrowse', "programID=$programID&browseType=all", '', '', $projectID), $lang->program->goback, '', "class='btn'");
    }
    ?>
  </div>
</div>
