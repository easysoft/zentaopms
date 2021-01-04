<?php
/**
 * The set story concept file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: setstoryconcept.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
tbody>tr:nth-child(odd) {background-color: #f5f5f5;}
.checkbox-primary {display: inline-block; line-height: 20px;}
.group-item {display:block; width:50%; float:left; margin-bottom:5px;}
.group-item>label {max-width:190px; display: block; height: 20px; padding-left: 30px; margin: 0; font-weight: 400; line-height: 20px; cursor: pointer;}
</style>
<div id='mainContent' class='main-content'>
  <div class="main-col">
    <div class='center-block'>
      <div class='main-header'>
        <h2>
          <span><?php echo $lang->my->setStoryConcept;?></span>
        </h2>
      </div>
      <form method='post' class='main-form' target='hiddenwin'>
        <table class='table table-form'>
          <tr>
            <td>
            <?php if (!empty($URSRList)):?>
            <?php foreach($URSRList as $key => $concept):?>
              <div class="group-item" title='<?php echo $concept;?>'>
                <label class="radio-inline text-ellipsis">
                  <input type="radio" name="URSR" value="<?php echo $key;?>" <?php if($key == $URSR) echo 'checked'?>><?php echo $concept;?>
                </label>
              </div>
            <?php endforeach;?>
            <?php else:?>
            <?php echo $lang->noData;?>
            <?php endif;?>
            </td>
          </tr>
          <tr>
            <td class='text-center'><?php echo html::submitButton();?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
