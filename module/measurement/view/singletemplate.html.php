<div class='main-col'>
  <div class='main-table' >
    <table class='table'>
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->meastemplate->id?></th>
          <th width='160'><?php echo $lang->meastemplate->name?></th>
          <th><?php echo $lang->meastemplate->desc?></th>
          <th class='w-130px'><?php echo $lang->actions?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
        <?php foreach($templates as $report):?>
        <tr>
          <td><?php echo $report->id;?></td>
          <td class='text-left'>
            <?php
            $name = json_decode($report->name, true);
            if(empty($name)) $name[$this->app->getClientLang()] = $report->name;
            echo zget($name, $this->app->getClientLang(), '');
            ?>
          </td>
          <?php
          $desc = json_decode($report->desc, true);
          $desc = zget($desc, $this->app->getClientLang(), '');
          ?>
          <td class='text-left' title='<?php echo $desc?>'><?php echo $desc;?></td>
          <td>
            <?php
            if(common::hasPriv('report', 'useReport')) echo html::a($this->createLink('report', 'useReport', "reportID=$report->id&from=cmmi"), $lang->report->useReport, '', "data-type='iframe' data-toggle='modal'");
            if(common::hasPriv('report', 'editReport')) echo html::a($this->createLink('report', 'editReport', "reportID=$report->id&from=cmmi"), $lang->report->editReport, '', "data-type='iframe' data-toggle='modal'");
            if(common::hasPriv('report', 'deleteReport')) echo html::a($this->createLink('report', 'deleteReport', "reportID=$report->id&confirm=no&from=cmmi"), $lang->delete, 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </div>
</div>
