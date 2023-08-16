<style>
.vision-switch {padding: 14px;}
.vision-switch-container {display: flex;}
.vision {width: 47%; border: none; background-color: rgba(230, 240 , 255, 0.4); cursor:pointer; border-radius: 2px; padding: 10px 10px 0 10px;}
.vision + .vision {margin-left: 10px;}
.vision:hover {box-shadow: 0 0 14px rgba(0, 0, 0, 0.12);}
.vision.active {box-shadow: 0 0 0 2px #2E7FFF;}
.vision-img {width: 100%; background-size: 100% !important;}
.vision-title {font-size: 14px; color: #0B0F18; padding: 10px 8px 0;}
.vision-text {font-size: 12px; color: #5E626D; padding: 8px;}
</style>
<?php js::set('vision', $this->config->vision);?>
<div class='vision-switch'>
  <p><?php echo $lang->block->visionTitle;?></p>
  <div class="vision-switch-container">
    <?php if($config->edition == 'ipd') $lang->block->visions = array('or' => $lang->block->visions['or']) + $lang->block->visions;?>
    <?php $userVisions = explode(',', $app->user->visions);?>
    <?php foreach($lang->block->visions as $vision):?>
    <?php if(!in_array($vision->key, $userVisions)) continue;?>
    <?php $active = $this->config->vision == $vision->key ? 'active' : '';?>
    <a href="<?php echo $this->createLink('my', 'ajaxSwitchVision', "vision=$vision->key");?>" data-type="ajax" class='vision <?php echo $active;?>' data-value="<?php echo $vision->key;?>">
      <div class='vision-img'>
        <?php if(common::checkNotCN() and $vision->key == 'rnd'):?>
        <?php echo html::image($config->webRoot . "theme/default/images/guide/vision_{$vision->key}_en.png");?>
        <?php elseif(!common::checkNotCN() and $vision->key == 'rnd'):?>
        <?php echo html::image($config->webRoot . "theme/default/images/guide/vision_{$vision->key}_cn.png");?>
        <?php else:?>
        <?php echo html::image($config->webRoot . "theme/default/images/guide/vision_{$vision->key}.png");?>
        <?php endif;?>
      </div>
      <div class='vision-title'><?php echo $vision->title;?></div>
      <div class='vision-text'><?php echo $vision->text;?></div>
    </a>
    <?php endforeach;?>
  </div>
</div>

<script>
$(function()
{
    var $block         = $('#block<?php echo $blockID;?>');
    var visionPosition = "<?php echo 'visionPosition-' . $blockID;?>";
    $block.find('.vision-switch .vision').click(function(e)
    {
        var selectedVision = $(this).data('value');
        if (vision == selectedVision)
        {
          e.preventDefault();
          return false;
        }

        var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop || $block.find('#guideBody').offset().top;
        localStorage.setItem(visionPosition, scrollTop);
    });
})
</script>
