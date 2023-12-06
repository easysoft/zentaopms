<?php
/**
 * The mail file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     release
 * @version     $Id: sendmail.html.php 867 2021-08-12 13:37:58Z $
 * @link        https://www.zentao.net
 */
?>
<?php $mailTitle = 'RELEASE #' . $release->id . ' ' . $release->name;?>
<?php $module    = $this->app->tab == 'product' ? 'release' : 'projectrelease';?>
<?php
$webRoot  = $this->app->getWebRoot();
$onlybody = isonlybody() ? true : false;
if($onlybody) $_GET['onlybody'] = 'no';
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
　<head>
　　<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
　　<title><?php echo $mailTitle ?></title>
　　<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
    <style>
      .content tr {line-height: 30px;}
      .content tr:last-child td{border-bottom: 1px solid #D7DBDE;}
      .content th, .content td {border-top: 1px solid #D7DBDE; color: #3C4353;}
      .content .id {text-align: center; width: 100px;}
      .content .title {border-left: 1px solid #D7DBDE; text-align: left; padding-left: 30px;}
      .content-icon {width: 16px; height: 16px; vertical-align: middle;}
    </style>
　</head>
  <body style='background-color: #FFF;'>
  　<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-size: 13px; color: #333; line-height: 20px; font-family: "Helvetica Neue",Helvetica,"Microsoft Yahei","Hiragino Sans GB","WenQuanYi Micro Hei",Tahoma,Arial,sans-serif;'>
      <tr>
        <td>
          <table align='center' border='0' cellpadding='0' cellspacing='0' width='1080' style='border: none; border-collapse: collapse;'>
            <tr>
              <td style='padding: 10px 0; border: none; vertical-align: middle;'><strong style='font-size: 18px; color: #1B1F28;'><?php echo $this->app->company->name ?></strong></td>
            </tr>
          </table>
          <table align='center' border='0' cellpadding='0' cellspacing='0' width='1080' style='background-color: #fff; border: 1px solid; border-color: rgba(0, 127, 255, 0.2); margin-bottom: 20px; font-size:13px;'>
            <tr>
              <td>
                <table cellpadding='0' cellspacing='0' width='1080' style='border: none; border-collapse: collapse;'>
                  <tr>
                    <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 15px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
                      <?php echo html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink($module, 'view', "releaseID=$release->id", 'html'), $mailTitle, '', "style='text-decoration: none; color: #007FFF; font-family: PingFang-SC;''");?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td class='content-head' style='padding: 15px 10px 15px 40px; border: none;'>
                <img src='<?php echo common::getSysURL() . $webRoot . 'theme/default/images/release/story.png';?>' class='content-icon' style='width: 16px; height: 16px; vertical-align: middle;'/>
                <span style='vertical-align: middle; font-weight: bold; color: #1B1F28;'><?php echo $this->lang->release->stories . '（' . count($stories) . '）';?></span>
              </td>
            </tr>
            <tr>
              <td>
                <table align='center' cellpadding='0' cellspacing='0' width='1000' class='content' style='background-color: #fff;margin-bottom: 20px; font-size:13px;'>
                  <thead>
                    <tr style='background-color: #F4F5F7;'>
                      <th style='text-align: center; width: 100px;border-top: 1px solid #D7DBDE; color: #3C4353; border-left: 1px solid #D7DBDE;'><?php echo $this->lang->idAB; ?></th>
                      <th class='title' style='border-left: 1px solid #D7DBDE; text-align: left; padding-left: 30px;border-top: 1px solid #D7DBDE; color: #3C4353; border-right: 1px solid #D7DBDE;'><?php echo $this->lang->release->storyTitle; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($stories as $story):?>
                    <tr>
                    <td class='id' style='text-align: center; width: 100px;border-top: 1px solid #D7DBDE; color: #3C4353; border-left: 1px solid #D7DBDE;'><?php echo $story->id;?></td>
                    <td class='title' style='border-left: 1px solid #D7DBDE; text-align: left; padding-left: 30px;border-top: 1px solid #D7DBDE; color: #3C4353; border-right: 1px solid #D7DBDE;'><?php echo $story->title;?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td class='content-head' style='padding: 15px 10px 15px 40px; border: none;'>
                <img src='<?php echo common::getSysURL() . $webRoot . 'theme/default/images/release/bug.png';?>' class='content-icon' style='width: 16px; height: 16px; vertical-align: middle;'/>
                <span style='vertical-align: middle; font-weight: bold; color: #1B1F28;'><?php echo $this->lang->release->bugs . '（' . count($bugs) . '）';?></span>
              </td>
            </tr>
            <tr>
              <td>
                <table align='center' cellpadding='0' cellspacing='0' width='1000' class='content' style='background-color: #fff;margin-bottom: 20px; font-size:13px;'>
                  <thead>
                    <tr style='background-color: #F4F5F7;'>
                      <th class='id' style='text-align: center; width: 100px;border-top: 1px solid #D7DBDE; color: #3C4353; border-left: 1px solid #D7DBDE;'><?php echo $this->lang->idAB; ?></th>
                      <th class='title' style='border-left: 1px solid #D7DBDE; text-align: left; padding-left: 30px;border-top: 1px solid #D7DBDE; color: #3C4353; border-right: 1px solid #D7DBDE;'><?php echo $this->lang->release->bugTitle; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($bugs as $bug):?>
                    <tr>
                    <td class='id' style='text-align: center; width: 100px;border-top: 1px solid #D7DBDE; color: #3C4353; border-left: 1px solid #D7DBDE;'><?php echo $bug->id;?></td>
                    <td class='title' style='border-left: 1px solid #D7DBDE; text-align: left; padding-left: 30px;border-top: 1px solid #D7DBDE; color: #3C4353; border-right: 1px solid #D7DBDE;'><?php echo $bug->title;?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td class='content-head' style='padding: 15px 10px 15px 40px; border: none;'>
                <img src='<?php echo common::getSysURL() . $webRoot . 'theme/default/images/release/leftbug.png';?>' class='content-icon' style='width: 16px; height: 16px; vertical-align: middle;'/>
                <span style='vertical-align: middle; font-weight: bold; color: #1B1F28;'><?php echo $this->lang->release->leftBugs . '（' . count($leftBugs) . '）';?></span>
              </td>
            </tr>
            <tr>
              <td>
                <table align='center' cellpadding='0' cellspacing='0' width='1000' class='content' style='background-color: #fff;margin-bottom: 20px; font-size:13px;'>
                  <thead>
                    <tr style='background-color: #F4F5F7;'>
                      <th class='id' style='text-align: center; width: 100px;border-top: 1px solid #D7DBDE; color: #3C4353; border-left: 1px solid #D7DBDE;'><?php echo $this->lang->idAB; ?></th>
                      <th class='title' style='border-left: 1px solid #D7DBDE; text-align: left; padding-left: 30px;border-top: 1px solid #D7DBDE; color: #3C4353; border-right: 1px solid #D7DBDE;'><?php echo $this->lang->release->bugTitle; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($leftBugs as $bug):?>
                    <tr>
                    <td class='id' style='text-align: center; width: 100px;border-top: 1px solid #D7DBDE; color: #3C4353; border-left: 1px solid #D7DBDE;'><?php echo $bug->id;?></td>
                    <td class='title' style='border-left: 1px solid #D7DBDE; text-align: left; padding-left: 30px;border-top: 1px solid #D7DBDE; color: #3C4353; border-right: 1px solid #D7DBDE;'><?php echo $bug->title;?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </td>
            </tr>
<?php include $this->app->getModuleRoot() . 'common/view/mail.footer.html.php';?>
