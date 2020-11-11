<?php
/**
 * The browse view file of holiday module of ZenTao.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     holiday
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <?php
    echo html::a($this->createLink('holiday', 'browse'), '<span class="text">' . $lang->holiday->all . '</span>', '', "class='btn btn-link btn-active-text'");
    ?>
  </div>
  <div class='pull-right'>
    <?php common::printLink('holiday', 'create', "", "<i class='icon icon-plus'> </i>" . $lang->holiday->create, '', "class='btn btn-primary iframe'", '', true)?>
  </div>
</div>
<div id='mainContent' class='main-row fade in'>
  <div class='side-col'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-ride='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php common::printLink('holiday', 'browse', "year=$year", $year);?>
          </li>
          <?php endforeach;?>
        </ul>
      </div>
    </div>
  </div>
  <div class='main-col main-table'>
  <?php if(!empty($holidays)):?>
    <table class='table has-sort-head'>
      <thead>
        <tr>
          <th class='w-150px'><?php echo $lang->holiday->name;?></th>
          <th class='w-200px'><?php echo $lang->holiday->holiday;?></th>
          <th class='w-80px'><?php echo $lang->holiday->type;?></th>
          <th><?php echo $lang->holiday->desc;?></th>
          <th class='w-100px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <?php foreach($holidays as $holiday):?>
      <tr>
        <td><?php echo $holiday->name;?></td>
        <td><?php echo formatTime($holiday->begin, DT_DATE1) . ' ~ ' . formatTime($holiday->end, DT_DATE1);?></td>
        <td><?php echo zget($lang->holiday->typeList, $holiday->type);?></td>
        <td><?php echo $holiday->desc;?></td>
        <td class=" c-actions">
          <?php common::printIcon('holiday', 'edit', "id=$holiday->id", $holiday, 'list', '', '', 'iframe', 'yes');?>
          <?php common::printIcon('holiday', 'delete', "id=$holiday->id", $holiday, 'list', '', 'hiddenwin');?>
        </td>
      </tr>
      <?php endforeach;?>
    </table>
  <?php else:?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->holiday->emptyTip;?></span>
      </p>
    </div>
  <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
