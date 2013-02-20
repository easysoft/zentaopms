<?php $url = $this->report->getSysURL();?>
<style>
del  {background:#fcc}
ins  {background:#cfc; text-decoration:none}
table, tr, th, td {border:1px solid gray; font-size:12px; border-collapse:collapse}
tr, th, td {padding:5px}
.w-id      {width:45px}
.header    {background:#efefef}
</style>
<?php if(isset($mail->bugs)):?>
<table width='66%' align='center'>
  <tr class='header'>
    <th class='w-id'><?php echo $lang->report->idAB;?></th>
    <th><?php echo $lang->report->bugTitle;?></th>
  </tr>
  <?php foreach($mail->bugs as $bug):?>
  <tr>
    <td><?php echo $bug->id;?></td>
    <td>
    <?php
    $link = $this->createLink('bug', 'view', "bugID=$bug->id");
    if($config->requestType == 'GET' and strpos($link, 'ztcli') !== false) $link = str_replace($this->server->php_self, $config->webRoot, $link);
    echo html::a($url . $link, $bug->title);
    ?>
    </td>
  </tr>
  <?php endforeach;?>
</table>
<?php endif;?>

<?php if(isset($mail->tasks)):?>
<table width='66%' align='center'>
  <tr class='header'>
    <th class='w-id'><?php echo $lang->report->idAB;?></th>
    <th><?php echo $lang->report->taskName;?></th>
  </tr>
  <?php foreach($mail->tasks as $task):?>
  <tr>
    <td><?php echo $task->id;?></td>
    <td>
    <?php
    $link = $this->createLink('task', 'view', "taskID=$task->id");
    if($config->requestType == 'GET' and strpos($link, 'ztcli') !== false) $link = str_replace($this->server->php_self, $config->webRoot, $link);
    echo html::a($url . $link, $task->name);
    ?>
    </td>
  </tr>
  <?php endforeach;?>
</table>
<?php endif;?>

<?php if(isset($mail->todos)):?>
<table width='66%' align='center'>
  <tr class='header'>
    <th class='w-id'><?php echo $lang->report->idAB;?></th>
    <th><?php echo $lang->report->todoName;?></th>
  </tr>
  <?php foreach($mail->todos as $todo):?>
  <tr>
    <td><?php echo $todo->id;?></td>
    <td>
    <?php
    $link = $this->createLink('todo', 'view', "todoID=$todo->id");
    if($config->requestType == 'GET' and strpos($link, 'ztcli') !== false) $link = str_replace($this->server->php_self, $config->webRoot, $link);
    echo html::a($url . $link, $todo->name);
    ?>
    </td>
  </tr>
  <?php endforeach;?>
</table>
<?php endif;?>
