<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent">
  <?php if(empty($screens)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->screen->noscreens;?></span></p>
  </div>
  <?php else:?>
  <div class='row'>
    <?php $i = 1;?>
    <?php foreach ($screens as $screenID => $screen):?>
    <div class='col-md-3' data-id='<?php echo $screen->id;?>'>
      <?php if(common::hasPriv('screen', 'view')):?>
      <a href='<?php echo $this->createLink('screen', 'view', "id=$screen->id");?>' target="_blank" />
      <?php else:?>
      <div>
      <?php endif;?>
        <div class='screen'>
          <?php if(!empty($screen->cover)):?>
          <div class='top img'>
            <img src='<?php echo $screen->cover;?>' controls='controls' width='100%'/>
          </div>
          <?php else:?>
          <div class='top img bg<?php echo $i?>'>
          </div>
          <?php $i++;?>
          <?php if($i == 7) $i = 1;?>
          <?php endif?>
          <div class='bottom' data-builtin='<?php echo $screen->builtin;?>' data-status='<?php echo $screen->status;?>'>
            <div class='screen-title' title='<?php echo $screen->name?>'><?php echo $screen->name;?></div>
            <div class='screen-desc' title="<?php echo $screen->desc;?>">
            <?php echo empty($screen->desc) ? $lang->screen->noDesc : $screen->desc;?>
            </div>
          </div>
        </div>
      <?php if(common::hasPriv('screen', 'view')):?>
      </a>
      <?php else:?>
      </div>
      <?php endif;?>
    </div>
    <?php endforeach;?>
  </div>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
