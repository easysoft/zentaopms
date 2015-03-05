<?php include 'header.html.php';?>
<div class='side'>
  <div class="panel panel-sm with-list">
    <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->dev->dbList?></strong></div>
    <?php foreach($tables as $table):?>
    <?php
    $active    = ($table == $selectedTable) ? 'active' : '';
    $subTable  = substr($table, strpos($table, '_') + 1);
    $tableName = isset($lang->dev->tableList[$subTable]) ? $lang->dev->tableList[$subTable] : $table;
    ?>
    <?php echo html::a(inlink('db', "table=$table"), $tableName, '', "class='list-group-item $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div class='main'>
  <?php if($selectedTable):?>
  <table class="table table-condensed table-striped table-bordered tablesorter table-fixed active-disabled">
    <thead>
      <tr>
        <th class='text-left' colspan='6'><?php echo $selectedTable?></th>
      </tr>
      <tr>
        <th class='w-id'><?php echo $lang->dev->fields['id']?></th>
        <th><?php echo $lang->dev->fields['name']?></th>
        <th><?php echo $lang->dev->fields['desc']?></th>
        <th><?php echo $lang->dev->fields['type']?></th>
        <th><?php echo $lang->dev->fields['length']?></th>
        <th><?php echo $lang->dev->fields['null']?></th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1;?>
      <?php foreach($fields as $key => $field):?>
      <tr>
        <td><?php echo $i;?></td>
        <td><?php echo $key;?></td>
        <td><?php echo $field['name'];?></td>
        <td><?php echo $field['type'];?></td>
        <td><?php echo isset($field['options']['max']) ? $field['options']['max'] : '';?></td>
        <td><?php echo $field['null'];?></td>
      </tr>
      <?php $i++; endforeach?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
