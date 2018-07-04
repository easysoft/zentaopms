<div class="main-col" data-min-width="400">
  <div class="panel block-files block-sm no-margin">
    <div class="panel-heading">
      <div class="panel-title font-normal">
        <?php $panelTitle = '';?>
        <?php if($type == 'custom')  $panelTitle = $lang->doc->custom;?>
        <?php if($type == 'product') $panelTitle = $lang->productCommon;?>
        <?php if($type == 'project') $panelTitle = $lang->projectCommon;?>
        <i class="icon icon-folder-open-o text-muted"></i> <?php echo $panelTitle;?>
      </div>
      <nav class="panel-actions btn-toolbar">
        <div class="btn-group">
          <?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-bars'></i>", '', "title='{$lang->doc->browseTypeList['list']}' class='btn btn-icon text-primary'");?>
          <?php echo html::a('javascript:setBrowseType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='{$lang->doc->browseTypeList['grid']}' class='btn btn-icon'");?>
        </div>
      </nav>
    </div>
    <div class="panel-body has-table">
      <table class="table table-borderless table-hover table-files">
        <thead>
          <tr>
            <?php $name = '';?>
            <?php if($type == 'product') $name = $lang->product->name;?>
            <?php if($type == 'project') $name = $lang->project->name;?>
            <?php if($type == 'custom')  $name = $lang->doc->libName;?>
            <th class="c-name"><?php echo $name;?></th>
            <th class="c-num"><?php echo $lang->doc->num;?></th>
            <?php if($type != 'custom'):?>
            <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
            <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
            <?php else:?>
            <th class="c-actions-4"><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($libs as $lib):?>
          <?php $link = $type != 'custom' ? $this->createLink('doc', 'objectLibs', "type=$type&objectID=$lib->id") : $this->createLink('doc', 'browse', "libID=$lib->id");?>
          <tr>
            <td class="c-name"><?php echo html::a($link, $lib->name);?></td>
            <td class="c-num">
              <?php if($type == 'custom'):?>
              <?php echo $itemCounts[$lib->id] . $lang->doc->item;?>
              <?php else:?>
              <?php echo count($subLibs[$lib->id]) . $lang->doc->item;?>
              <?php endif;?>
            </td>
            <?php if($type != 'custom'):?>
            <td class="c-user"><?php if($lib->createdBy) echo zget($users, $lib->createdBy);?></td>
            <td class="c-datetime"><?php if($lib->createdDate != '00-00-00 00:00:00') echo formatTime($lib->createdDate, 'm-d h:i');?></td>
            <?php else:?>
            <td class="c-actions">
              <?php $star = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
              <?php $collectTitle = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
              <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$lib->id&objectType=doclib");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
              <?php common::printLink('doc', 'editLib', "libID=$lib->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link iframe'")?>
              <?php common::printLink('doc', 'deleteLib', "libID=$lib->id", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
              <?php common::printLink('tree', 'browse', "rootID=$lib->id&type=doc", "<i class='icon icon-cog'></i>", '', "title='{$lang->tree->manage}' class='btn btn-link'")?>
            </td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  </div>
</div>
