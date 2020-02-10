<div class="main-col" data-min-width="400">
<div class="cell" id="queryBox" data-module='doc'></div>
  <div class="panel block-files block-sm no-margin">
    <div class="panel-heading">
      <div class="panel-title font-normal">
        <?php if($this->from == 'doc'):?>
        <i class="icon icon-folder-open-o text-muted"></i> <?php echo $object->name;?>
        <?php endif;?>
        <div class="btn-group pull-right">
          <?php echo html::a('javascript:setBrowseType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='{$lang->doc->browseTypeList['grid']}' class='btn btn-icon'");?>
          <?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-bars'></i>", '', "title='{$lang->doc->browseTypeList['list']}' class='btn btn-icon text-primary'");?>
        </div>
      </div>
    </div>
    <div class='panel-body'>
      <table class="table table-borderless table-hover table-files table-fixed no-margin">
        <thead>
          <tr>
            <th class="c-name"><?php echo $lang->doc->libName;?></th>
            <th class="c-num"><?php echo $lang->doc->num;?></th>
            <th class="w-90px text-center"><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($libs as $libID => $lib):?>
          <?php if($libID == 'project' and $from != 'doc') continue;?>
          <?php if(strpos($config->doc->custom->objectLibs, 'files') === false && $libID == 'files') continue;?>
          <?php if(strpos($config->doc->custom->objectLibs, 'customFiles') === false && isset($lib->main) && !$lib->main) continue;?>

          <?php $libLink = inlink('browse', "libID=$libID&browseType=all&param=0&orderBy=id_desc&from=$from");?>
          <?php if($libID == 'project') $libLink = inlink('allLibs', "type=project&product=$object->id");?>
          <?php if($libID == 'files')   $libLink = inlink('showFiles', "type=$type&objectID=$object->id");?>
          <tr>
            <td class="c-name"><?php echo html::a($libLink, $lib->name);?></td>
            <td class="c-num"><?php echo  $lib->allCount . $lang->doc->item;?></td>
            <td class="c-actions">
              <?php if($libID != 'project' and $libID != 'files'):?>
              <?php $star = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
              <?php $collectTitle = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
              <?php if(common::hasPriv('doc', 'collect')):?>
              <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$libID&objectType=doclib");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
              <?php endif;?>
              <?php common::printLink('doc', 'editLib', "libID=$libID", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link iframe'")?>
              <?php if(empty($lib->main)) common::printLink('doc', 'deleteLib', "libID=$libID", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
              <?php common::printLink('tree', 'browse', "rootID=$libID&type=doc", "<i class='icon icon-cog'></i>", '', "title='{$lang->doc->manageType}' class='btn btn-link'")?>
              <?php endif;?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
