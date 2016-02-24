            <?php if(!empty($action)): ?>
            <tr>
              <td style='padding: 10px; background-color: #FFF0D5'>
                <?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
                <?php if(isset($users[$action->actor])) $action->actor = $users[$action->actor];?>
                <?php if(isset($users[$action->extra])) $action->extra = $users[$action->extra];?>
                <span style='font-size: 16px; color: #F1A325'>●</span> &nbsp;<span><?php $this->action->printAction($action);?></span>
              </td>
            </tr>
            <?php if(!empty($action->comment) or !empty($action->history)):?>
            <tr>
              <td style='padding: 10px;'>
                <div><?php echo $this->action->printChanges($action->objectType, $action->history);?></div>
                <?php if(!empty($action->comment)): ?>
                  <p style='padding: 0; padding-left: 10px; margin: 0; margin-top: 8px; border-left: 4px solid #ddd; color: #666'><?php echo $action->comment;?></p>
                <?php endif;?>
                </div>
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
