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
