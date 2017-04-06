<?php
$extViewFile = $this->app->getModuleRoot() . 'ext/view/' . basename(__FILE__);
if(file_exists($extViewFile))
{
    include $extViewFile;
    return;
}
?>
            <?php if(isset($action->objectType) and isset($action->action)):?>
            <?php if(!empty($action->comment)):?>
            <tr>
              <td style="padding:0px 10px 10px 10px; border: none;">
                <fieldset style="border: 1px solid #e5e5e5">
                <legend style="color: #114f8e"><?php echo $this->lang->comment?></legend>
                <div style="padding:5px;"><?php echo $action->comment?></div>
                </fieldset>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td style='padding: 10px; background-color: #FFF0D5'>
                <?php if(isset($users[$action->actor])) $action->actor = $users[$action->actor];?>
                <?php if(isset($users[$action->extra])) $action->extra = $users[$action->extra];?>
                <span style='font-size: 16px; color: #F1A325'>●</span> &nbsp;<span><?php $this->action->printAction($action);?></span>
              </td>
            </tr>
            <?php if(!empty($action->history)):?>
            <tr>
              <td style='padding: 10px;'>
                <div><?php echo $this->action->printChanges($action->objectType, $action->history);?></div>
              </td>
            </tr>
            <?php endif;?>
            <?php endif;?>
          </table>
        </td>
      </tr>
  　</table>
  </body>
</html><?php // close tags in mail.header.html.php ?>
<?php if($onlybody) $_GET['onlybody'] = 'yes';?>
