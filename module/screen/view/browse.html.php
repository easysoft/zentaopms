<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='main-row'>
  <div id="mainContent">
    <?php if(empty($screens)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->screen->noscreens;?></span></p>
    </div>
    <?php else:?>
    <div class='main-col'>
      <div class='row'>
        <?php $i = 1;?>
        <?php foreach ($screens as $screenID => $screen):?>
        <div class='col-md-3' data-id='<?php echo $screenID;?>'>
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
              <?php if($i == 13) $i = 1;?>
              <?php endif?>
              <div class='bottom'>
                <div class='screen-title text-ellipsis' title='<?php echo $screen->name?>'><?php echo $screen->name;?></div>
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
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
