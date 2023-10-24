<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('showGuide', $showGuide ? 1 : 0);?>
<div id="mainContent">
  <?php if(empty($screens)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->screen->noscreens;?></span></p>
  </div>
  <?php else:?>
  <div class='row'>
    <?php foreach ($screens as $screenID => $screen):?>
    <?php if($screenID == 3 && !common::hasPriv('screen', 'annualData')) continue;?>
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
          <div class='top img image_<?php echo $screen->status;?>'>
            <img src='<?php echo "static/images/screen_{$screen->status}.png";?>' controls='controls' width='100%'/>
          </div>
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

<div class="modal fade" id="firstGuide">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="background-img" <?php echo "style='background: url($imageURL) no-repeat; background-size: 100%'";?>>
          <?php if($version == 'pms'):?>
            <a href="<?php echo $lang->admin->bizInfoURL;?>" class="clickable-area" target="_blank"></a>
          <?php endif;?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-wide btn-primary" data-dismiss="modal"><?php echo $lang->close;?></button>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
