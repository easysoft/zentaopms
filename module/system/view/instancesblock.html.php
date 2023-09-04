<?php $this->app->loadLang('instance');?>
<?php js::set('instanceIdList',  helper::arrayColumn($instances, 'id'));?>
<div class="cell main-table instance-container">
  <h3 class='text-center'><?php echo $lang->instance->runningService;?></h3>
  <?php if(empty($instances)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->instance->empty;?></span></p>
  </div>
  <?php else:?>
  <table class="table">
    <thead>
      <tr>
        <th><?php echo $lang->instance->name;?></th>
        <th><?php echo $lang->instance->version;?></th>
        <th class='w-180px'><?php echo $lang->instance->status?></th>
        <th><?php echo $lang->instance->cpu;?></th>
        <th><?php echo $lang->instance->mem;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($instances as $instance):?>
      <tr>
        <td><?php echo html::a($this->createLink('instance', 'view', "id=$instance->id"), $instance->name);?></td>
        <td><?php echo $instance->appVersion;?></td>
        <td class="instance-status" instance-id="<?php echo $instance->id;?>" data-status="<?php echo $instance->status;?>">
          <?php echo $this->instance->printStatus($instance, false);?>
          <?php if(commonModel::isDemoAccount()):?>
          <span class="count-down label label-outline label-danger" data-created-at="<?php echo strtotime($instance->createdAt);?>">
            <span><?php echo $lang->instance->leftTime;?></span>
            <span class='left-time'>00:00</span>
          </span>
          <?php endif;?>
        </td>
        <?php $metrics= zget($instancesMetrics, $instance->id);?>
        <td><?php $this->instance->printCpuUsage($instance, $metrics->cpu);?></td>
        <td><?php $this->instance->printMemUsage($instance, $metrics->memory);?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class="table-footer"><?php echo $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</div>
