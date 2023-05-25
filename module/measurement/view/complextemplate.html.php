<div class='main-table'>
  <table class='table'>
    <thead>
      <tr>
        <th class='w-60px'><?php echo $lang->idAB;?></th>
        <th><?php echo $lang->meastemplate->name;?></th>
        <th class='w-120px'><?php echo $lang->measurement->model;?></th>
        <th class='w-120px'><?php echo $lang->meastemplate->createdBy;?></th>
        <th class='w-120px'><?php echo $lang->meastemplate->createdDate;?></th>
        <th class='c-actions'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($templates as $template):?>
      <tr>
        <td><?php echo $template->id;?></td>
        <td><?php echo $template->name;?></td>
        <td><?php echo zget($lang->measurement->modelList, $template->model, '');?></td>
        <td><?php echo $template->createdBy;?></td>
        <td><?php echo $template->createdDate;?></td>
        <td class='c-actions'>
          <?php echo common::printIcon('measurement', 'editTemplate', "id=$template->id", $template, '', 'edit', '', '', '', '', $lang->measurement->editTemplate);?>
          <?php echo common::printIcon('measurement', 'viewTemplate', "id=$template->id", $template, '', 'eye', '', '', '', $lang->measurement->viewTemplate);?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
</div>
