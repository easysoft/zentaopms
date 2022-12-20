<style>
.vision-switch {padding: 14px;}
.vision-switch-container {display: flex;}
#vision-ALM {background: url('/theme/default/images/guide/vision_alm.png') no-repeat;}
#vision-light {background: url('/theme/default/images/guide/vision_light.png') no-repeat;}
.vision {width: 47%; border: none; background: #E6F0FF; cursor:pointer; border-radius: 2px;}
.vision + .vision {margin-left: 10px;}
.vision.active {box-shadow: 0 0 0 2px #2E7FFF;}
.vision-img {height: 118px; width: 100%; background-size: 100% !important;}
.vision-title {font-size: 14px; color: #0B0F18; padding: 0 8px;}
.vision-text {font-size: 12px; color: #5E626D; padding: 8px;}
</style>

<script>
$('.vision-switch .vision').click(function()
{
  console.log('vision-switch > .vision');
})
</script>
<div class='vision-switch'>
<p><?php echo $lang->block->visionTitle;?></p>
<div class="vision-switch-container">
  <?php foreach($lang->block->visions as $vision):?>
  <?php $active = $this->config->systemMode == $vision->key ? 'active' : '';?>
  <div class='vision <?php echo $active;?>' data-value="<?php echo $vision->key;?>">
      <div class='vision-img' id="<?php echo 'vision-' . $vision->key;?>"></div>
      <div class='vision-title'><?php echo $vision->title;?></div>
      <div class='vision-text'><?php echo $vision->text;?></div>
    </div>
  <?php endforeach;?>
  </div>
</div>
