<div class="main-col" data-min-width="400">
  <div class="panel block-files block-sm no-margin">
    <div class="panel-heading">
      <div class="panel-title font-normal"><i class="icon icon-folder-open-o text-muted"></i> <?php echo $title;?></div>
      <nav class="panel-actions btn-toolbar">
        <div class="btn-group">
          <?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-bars'></i>", '', "title='{$lang->doc->browseTypeList['list']}' class='btn btn-icon'");?>
          <?php echo html::a('javascript:setBrowseType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='{$lang->doc->browseTypeList['grid']}' class='btn btn-icon text-primary'");?>
        </div>
      </nav>
    </div>
    <?php if(empty($docs) and empty($modules) and empty($libs)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->doc->noDoc;?></span> <?php common::printLink('doc', 'create', "libID={$libID}", "<i class='icon icon-plus'></i> " . $lang->doc->create, '', "class='btn btn-info'");?></p>
    </div>
    <?php else:?>
    <div class="panel-body">
      <div class="row row-grid files-grid" data-size="300">
        <?php foreach($libs as $lib):?>
        <?php $star = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
        <div class="col">
          <a class="file" href="<?php echo inlink('browse', "libID=$lib->id&browseType=all&param=0&orderBy=$orderBy&from=$from");?>">
            <i class="file-icon icon icon-folder text-yellow"></i>
            <div class="file-name"><?php echo (strpos($lib->collector, $this->app->user->account) !== false ? "<i class='icon icon-star text-yellow'></i> " : '') . $lib->name;?></div>
            <div class="text-primary file-info"><?php echo zget($itemCounts, $lib->id, 0) . $lang->doc->item;?></div>
          </a>
          <div class="actions">
            <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$lib->id&objectType=doclib");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
            <?php common::printLink('doc', 'editLib', "libID=$lib->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link iframe'")?>
            <?php common::printLink('tree', 'browse', "rootID=$lib->id&type=doc", "<i class='icon icon-cog'></i>", '', "title='{$lang->tree->manage}' class='btn btn-link'")?>
          </div>
        </div>
        <?php endforeach;?>
        <?php foreach($modules as $module):?>
        <?php $star = strpos($module->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
        <div class="col">
          <a class="file" href="<?php echo inlink('browse', "libID=$libID&browseType=bymodule&param=$module->id&orderBy=$orderBy&from=$from");?>">
            <i class="file-icon icon icon-folder text-yellow"></i>
            <div class="file-name"><?php echo (strpos($module->collector, $this->app->user->account) !== false ? "<i class='icon icon-star text-yellow'></i> " : '') . $module->name;?></div>
            <div class="text-primary file-info"><?php echo $module->docCount . $lang->doc->item;?></div>
          </a>
          <div class="actions">
            <?php common::printLink('doc', 'collect', "objectID={$module->id}&objectType=module", "<i class='icon {$star}'></i>", 'hiddenwin', "title='{$lang->doc->collect}' class='btn btn-link'");?>
            <?php common::printLink('tree', 'browse', "rootID=$libID&type=doc", "<i class='icon icon-cog'></i>", '', "title='{$lang->tree->manage}' class='btn btn-link'")?>
          </div>
        </div>
        <?php endforeach;?>
        <?php foreach($docs as $doc):?>
        <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
        <?php $collectTitle = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
        <div class="col">
          <a class="file" href="<?php echo inlink('view', "docID=$doc->id");?>">
            <i class="file-icon icon icon-file-text text-muted"></i>
            <div class="file-name"><?php echo (strpos($doc->collector, $this->app->user->account) !== false ? "<i class='icon icon-star text-yellow'></i> " : '') . $doc->title;?></div>
            <div class="text-primary file-info"><?php echo zget($users, $doc->addedBy);?></div>
          </a>
          <div class="actions">
            <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID={$doc->id}&objectType=doc");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
            <?php common::printLink('doc', 'edit', "docID={$doc->id}", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link'")?>
            <?php common::printLink('doc', 'delete', "docID={$doc->id}", "<i class='icon icon-trash'></i>", '', "title='{$lang->delete}' class='btn btn-link'")?>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <?php if(empty($docs) and empty($modules)):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
    <?php endif;?>
  </div>
</div>
