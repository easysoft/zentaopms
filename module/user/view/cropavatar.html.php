<?php
/**
 * The crop avatar view file of user module of ZentaoPms.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: cropavatar.html.php 8669 2021-01-18 16:58:48Z sunguangming$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<table class='table table-form'>
  <tr>
    <td>
      <div class="img-cutter fixed-ratio" id="imgCutter" style="max-width: 100%">
        <div class="canvas">
        <?php
        if(empty($user->avatar))
        {
            echo html::image($image->webPath);
        }
        else
        {
            echo html::image($user->avatar);
        }
        ?>
        </div>
        <div class="form-actions">
          <h5 id='avatarCropTip'><?php echo $lang->user->cropAvatarTip;?></h5>
          <div class="img-cutter-preview"></div>
          <button type="button" class="btn btn-primary img-cutter-submit"><?php echo $lang->save;?></button> <?php echo html::a(inlink('profile'), $lang->goback, "class='btn loadInModal'");?>
        </div>
      </div>
    </td>
  </tr>
</table>
<script>
var $imgCutter = $("#imgCutter");
$imgCutter.imgCutter(
{
    fixedRatio: true,
    minWidth: 48,
    minHeight: 48,
    post: '<?php echo inlink('cropavatar', "image={$image->id}")?>',
    ready: function() {$.zui.ajustModalPosition(); $imgCutter.css('width', $imgCutter.closest('.modal-dialog').width() - 50);},
    done: function(response)
    {
        $('#start .avatar, #startMenu .avatar').html('<img src="<?php echo $user->avatar?>?v=' + $.zui.uuid() + '" />');
        if($('#start .avatar, #startMenu .avatar').hasClass('with-text')) $('#start .avatar, #startMenu .avatar').toggleClass('with-text').css('background', 'none');
        $('#ajaxModal').load(createLink('user', 'profile'), function(){$.zui.ajustModalPosition()});
    },
    onSizeError: function(size)
    {
        $('#avatarCropTip').text('<?php echo $lang->user->cropImageTip ?>'.replace('%s', size.width + 'x' + size.height)).addClass('text-danger');
    }
});
</script>
<?php include '../../common/view/footer.html.php';?>
