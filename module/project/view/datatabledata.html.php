<?php
/**
 * The datatable data view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/datatable.html.php';
$setting = $this->datatable->getSetting('project');
$widths  = $this->datatable->setFixedFieldWidth($setting);
$columns = 0;
extract($widths);
?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed datatable' id='taskList' data-checkable='true' data-fixed-left-width='<?php echo $leftWidth?>' data-fixed-right-width='<?php echo $rightWidth?>' data-custom-menu='true' data-checkbox-name='taskIDList[]'>
      <thead>
        <tr><?php
        foreach ($setting as $key => $value)
        {
            if($value->show)
            {
                $this->datatable->printHead($value, $orderBy, $vars);
                $columns++;
            }
        }
        ?></tr>
      </thead>
      <tbody>
        <?php foreach($tasks as $task):?>
        <tr class='text-center' data-id='<?php echo $task->id?>'>
          <?php foreach ($setting as $key => $value) $this->task->printCell($value, $task, $users, $browseType, $branchGroups, $modulePairs);?>
        </tr>
        <?php endforeach;?>
      </tbody>
