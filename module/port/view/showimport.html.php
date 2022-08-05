<?php include $app->getModuleRoot() . 'port/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header clearfix">
    <h2><?php echo $lang->port->import;?></h2>
  </div>
  <form class='main-form' target='hiddenwin' method='post'>
    <table class='table table-form' id='showData'>
      <thead>
        <tr>
          <th class='w-70px'> <?php echo $lang->port->id?></th>
          <?php foreach($fields as $key => $value):?>
          <?php if($value['control'] != 'hidden'):?>
          <th class='c-<?php echo $key?>'  id='<?php echo $key;?>'>  <?php echo $value['title'];?></th>
          <?php endif;?>
          <?php endforeach;?>
          <?php
          if(!empty($appendFields))
          {
              foreach($appendFields as $field)
              {
                  if(!$field->show) continue;

                  $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
                  $required = strpos(",$field->rules,", ",$notEmptyRule->id,") !== false ? 'required' : '';
                  echo "<th class='$required' style='width: $width'>$field->name</th>";
              }
          }
          ?>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <?php include $app->getModuleRoot() . 'port/view/tfoot.html.php';?>
    </table>
    <?php if(!$insert and $dataInsert === '') include $app->getModuleRoot() . 'common/view/noticeimport.html.php';?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'port/view/footer.html.php';?>
