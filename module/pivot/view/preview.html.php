<?php include '../../common/view/header.html.php';?>
<script>
$('#subNavbar li').not('[data-id=<?php echo $group;?>]').removeClass('active');
</script>
<?php if($this->config->edition != 'open'):?>
<style>#mainContent > .side-col.col-lg{width: 210px}</style>
<style>.hide-sidebar #sidebar{width: 0 !important}</style>
<?php endif;?>
<?php js::set('dimension', $dimension);?>
<?php js::set('group', $group);?>
<div id='mainContent' class='main-row'>
  <div class='side-col col-lg' id='sidebar'>
    <div class="sidebar-toggle">
      <i class="icon icon-angle-left"></i>
    </div>
    <div class="cell">
      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $lang->report->list;?></div>
        </div>
        <div class='panel-body'>
          <div class='list-group'>
            <?php echo $sidebar;?>
          </div>
        </div>
      </div>
      <?php if($this->config->edition == 'open'):?>
      <div class='panel panel-body' style='padding: 10px 6px'>
        <div class='text proversion'>
          <strong class='text-danger small text-latin'>BIZ</strong> &nbsp;<span class='text-important'><?php echo (!empty($config->isINT)) ? $lang->report->proVersionEn : $lang->report->proVersion;?></span>
        </div>
      </div>
      <?php endif;?>
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
