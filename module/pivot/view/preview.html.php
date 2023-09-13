<?php include '../../common/view/header.html.php';?>
<script>
$('#subNavbar li').not('[data-id=<?php echo $groupID;?>]').removeClass('active');
</script>
<?php if($this->config->edition != 'open'):?>
<style>.hide-sidebar #sidebar{width: 0 !important}</style>
<?php endif;?>
<?php js::set('dimension', $dimension);?>
<?php js::set('groupID', $groupID);?>

<div id='mainMenu' class='clearfix main-position'>
  <div class="btn-toolbar pull-left parent-position">
    <?php foreach($lang->pivot->featureBar['preview'] as $key => $label):?>
    <?php $active = ($key == $groupID) ? ' btn-active-text' : '';?>
    <?php echo html::a(inlink('preview', "dimension=$dimension&group=$key"), "<span class='text'>$label</span>", '', "class='btn btn-link {$active}'");?>
    <?php endforeach;?>
  </div>
  <?php if($this->config->edition != 'open'):?>
  <div class='btn-toolbar pull-right child-position'>
    <?php if(common::hasPriv('pivot', 'export')):?>
    <a href="#" class="btn btn-link btn-export hidden" data-toggle="modal" data-target="#export"><?php echo '<i class="icon-export muted"> </i>' . $lang->export;?></a>
    <?php endif;?>
    <?php common::printLink('pivot', 'browse', '', $lang->pivot->toDesign, '', "class='btn btn-primary'");?>
  </div>
  <?php endif;?>
</div>
<?php if($this->config->edition != 'open'):?>
<?php $pivotPath = $this->app->getModuleExtPath('pivot', 'view');?>
<?php include $pivotPath['common'] . 'exportdata.html.php';?>
<?php endif;?>

<div id='mainContent' class='main-row'>
  <div class='side-col col-lg' id='sidebar'>
    <div class="sidebar-toggle">
      <i class="icon icon-angle-left"></i>
    </div>
    <div class="cell">
      <div class='panel'>
        <div class='panel-heading text-ellipsis'>
          <div class='panel-title'><?php echo isset($group->name) ? $group->name : '';?></div>
        </div>
        <div class='panel-body'>
          <div class='list-group'>
            <?php echo $sidebar;?>
          </div>
          <?php if($this->config->edition == 'open'):?>
          <div class='text biz-version'>
            <span class='text-important'><?php echo (!empty($config->isINT)) ? $lang->bizVersionINT : $lang->bizVersion;?></span>
          </div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  <div class='main-col'>
    <?php if(empty($module) || empty($method)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
    </div>
    <?php else:?>
    <?php echo $this->fetch($module, $method, $params);?>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
