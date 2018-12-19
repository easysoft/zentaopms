<?php include 'header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class="panel panel-sm with-list">
        <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->dev->dbList?></strong></div>
        <?php foreach($lang->dev->groupList as $group => $groupName):?>
        <?php if(isset($tables[$group])):?>
        <div class='modulegroup'><?php echo $groupName?></div>
        <?php foreach($tables[$group] as $subTable => $table):?>
        <?php
        $active    = ($table == $selectedTable) ? 'active' : '';
        $tableName = zget($lang->dev->tableList, $subTable, $table);
        ?>
        <?php echo html::a(inlink('db', "table=$table"), $tableName, '', "class='$active'");?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
        <?php foreach($lang->dev->endGroupList as $group => $groupName):?>
        <?php if(isset($tables[$group])):?>
        <div class='modulegroup'><?php echo $groupName?></div>
        <?php foreach($tables[$group] as $subTable => $table):?>
        <?php
        $active    = ($table == $selectedTable) ? 'active' : '';
        $tableName = zget($lang->dev->tableList, $subTable, $table);
        ?>
        <?php echo html::a(inlink('db', "table=$table"), $tableName, '', "class='$active'");?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
    <?php if($selectedTable):?>
    <div class='detail'>
      <div class='detail-title'><?php echo $selectedTable?></div>
      <div class='detail-content'>
        <table class="table table-bordered">
          <thead>
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
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
