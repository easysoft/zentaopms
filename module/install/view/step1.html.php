<?php
/**
 * The html template file of step1 method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: step1.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'><strong><?php echo $lang->install->checking;?></strong></div>
    <div class='modal-body'>
      <table class='table table-bordered'>
        <thead>
          <tr class='text-center'>
            <th class='w-p20'><?php echo $lang->install->checkItem;?></th>
            <th class='w-p25'><?php echo $lang->install->current?></th>
            <th class='w-p15'><?php echo $lang->install->result?></th>
            <th><?php echo $lang->install->action?></th>
          </tr>
        </thead>
        <tr>
          <th><?php echo $lang->install->phpVersion;?></th>
          <td><?php echo $phpVersion;?></td>
          <td class='<?php echo $phpResult;?>'><?php echo $lang->install->$phpResult;?></td>
          <td class='text-left f-12px'><?php if($phpResult == 'fail') echo $lang->install->phpFail;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->install->pdo;?></th>
          <td><?php $pdoResult == 'ok' ? printf($lang->install->loaded) : printf($lang->install->unloaded);?></td>
          <td class='<?php echo $pdoResult;?>'><?php echo $lang->install->$pdoResult;?></td>
          <td class='text-left f-12px'><?php if($pdoResult == 'fail') echo $lang->install->pdoFail;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->install->pdoMySQL;?></th>
          <td><?php $pdoMySQLResult == 'ok' ? printf($lang->install->loaded) : printf($lang->install->unloaded);?></td>
          <td class='<?php echo $pdoMySQLResult;?>'><?php echo $lang->install->$pdoMySQLResult;?></td>
          <td class='text-left f-12px'><?php if($pdoMySQLResult == 'fail') echo $lang->install->pdoMySQLFail;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->install->json;?></th>
          <td><?php $jsonResult == 'ok' ? printf($lang->install->loaded) : printf($lang->install->unloaded);?></td>
          <td class='<?php echo $jsonResult;?>'><?php echo $lang->install->$jsonResult;?></td>
          <td class='text-left f-12px'><?php if($jsonResult == 'fail') echo $lang->install->jsonFail;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->install->tmpRoot;?></th>
          <td>
            <?php
            $tmpRootInfo['exists']   ? print($lang->install->exists)   : print($lang->install->notExists);
            $tmpRootInfo['writable'] ? print($lang->install->writable) : print($lang->install->notWritable);
            $mkdir = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? $lang->install->mkdirWin : $lang->install->mkdirLinux;
            $chmod = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? $lang->install->chmodWin : $lang->install->chmodLinux;
            ?>
          </td>
          <td class='<?php echo $tmpRootResult;?>'><?php echo $lang->install->$tmpRootResult;?></td>
          <td class='text-left f-12px'>
            <?php 
            if(!$tmpRootInfo['exists'])   printf($mkdir, $tmpRootInfo['path'], $tmpRootInfo['path']);
            if(!$tmpRootInfo['writable']) printf($chmod, $tmpRootInfo['path'], $tmpRootInfo['path']);
            ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->install->dataRoot;?></th>
          <td>
            <?php
            $dataRootInfo['exists']   ? print($lang->install->exists)   : print($lang->install->notExists);
            $dataRootInfo['writable'] ? print($lang->install->writable) : print($lang->install->notWritable);
            ?>
          </td>
          <td class='<?php echo $dataRootResult;?>'><?php echo $lang->install->$dataRootResult;?></td>
          <td class='text-left f-12px'>
            <?php 
            if(!$dataRootInfo['exists'])   printf($mkdir, $dataRootInfo['path'], $dataRootInfo['path']);
            if(!$dataRootInfo['writable']) printf($chmod, $dataRootInfo['path'], $dataRootInfo['path']);
            ?>
          </td>
        </tr>
        <?php if($checkSession):?>
        <tr>
          <th><?php echo $lang->install->session;?></th>
          <td>
            <?php
            $sessionInfo['exists']   ? print($lang->install->exists)   : print($lang->install->notExists);
            $sessionInfo['writable'] ? print($lang->install->writable) : print($lang->install->notWritable);
            ?>
          </td>
          <td class='<?php echo $sessionResult;?>'><?php echo $lang->install->$sessionResult;?></td>
          <td class='text-left f-12px'>
            <?php 
            if($sessionInfo['path'])
            {
                if(!$sessionInfo['exists'])   printf($mkdir, $sessionInfo['path'], $sessionInfo['path']);
                if(!$sessionInfo['writable']) printf($chmod, $sessionInfo['path'], $sessionInfo['path']);
            }
            else
            {
                echo $lang->install->sessionFail; 
            }
            ?>
          </td>
        </tr>
        <?php endif;?>
      </table>
    </div>
    <div class='modal-footer'>
      <?php
      if($phpResult == 'ok' and $pdoResult == 'ok' and $pdoMySQLResult == 'ok' and $tmpRootResult == 'ok' and $dataRootResult == 'ok' and $sessionResult == 'ok')
      {
          echo html::a($this->createLink('install', 'step2'), $lang->install->next, '', "class='btn btn-primary'");
      }
      else
      {
          echo html::a($this->createLink('install', 'step1'), $lang->install->reload, '', "class='btn btn-primary mgb-20'");
          if($pdoResult == 'fail' or $pdoMySQLResult == 'fail')
          {
            echo '<div class="panel panel-sm text-left"><div class="panel-heading strong">' . $lang->install->phpINI . '</div><div class="panel-body">' . nl2br($this->install->getIniInfo()) . '</div></div>';
          }
      }
      ?>      
    </div>
  </div>
</div>

<?php include '../../common/view/footer.lite.html.php';?>
