<th class='w-70px'> <?php echo $lang->transfer->id?></th>
<?php foreach($fields as $key => $value):?>
<?php if($value['control'] != 'hidden'):?>
<th class='c-<?php echo $key?>'  id='<?php echo $key;?>'>  <?php echo $value['title'];?></th>
<?php endif;?>
<?php endforeach;?>
<?php if(in_array($app->getModuleName(), $config->transfer->actionModule)):?>
<th class="w-70px"><?php echo $lang->actions;?></th>
<?php endif;?>
