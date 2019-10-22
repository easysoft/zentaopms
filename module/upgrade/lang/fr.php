<?php
/**
 * The upgrade module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: en.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->upgrade->common  = 'Mise à jour';
$lang->upgrade->result  = 'Résultat';
$lang->upgrade->fail    = 'Echec';
$lang->upgrade->success = 'Mise à jour effectuée';
$lang->upgrade->tohome  = 'Visitez ZenTao';
$lang->upgrade->license = 'ZenTao est sous Z PUBLIC LICENSE(ZPL) 1.2.';
$lang->upgrade->warnning= 'Attention!';
$lang->upgrade->checkExtension  = 'Vérifiez Extensions';
$lang->upgrade->consistency     = 'Vérifiez Consistence';
$lang->upgrade->warnningContent = <<<EOT
<p>Sauvegardez votre base de données avant la mise à jour de ZenTao !</p>
<pre>
1. Utilisez phpMyAdmin pour faire la sauvegarde.
2. Utilisez une commande mysql pour faire la sauvegarde.
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span> 
   Changez le texte en rouge par les code user et le nom de la base qui correspondent.
   e.g. mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;
$lang->upgrade->createFileWinCMD   = 'Ouvrez la fenêtre Ligne de commandes de windows et exécutez <strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = 'Executez la ligne de commande suivante: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>Accomplissez les actions suivantes</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>Ou supprimez "<strong style="color:#ed980f">%s</strong>" et créez <strong style="color:#ed980f">ok.txt</strong> et laissez ce fichier vide.</li>
                                      </ul>
                                      <p><strong style="color:red">Vous avez lu et accompli toutes les actions précédentes. <a href="upgrade.php">Continuez la mise à jour.</a></strong></p>';
$lang->upgrade->selectVersion = 'Version';
$lang->upgrade->continue      = 'Continuer';
$lang->upgrade->noteVersion   = "Sélectionnez une version compatible où vous pourriez perdre des données.";
$lang->upgrade->fromVersion   = 'De';
$lang->upgrade->toVersion     = 'à';
$lang->upgrade->confirm       = 'Confirmez SQL';
$lang->upgrade->sureExecute   = 'Executez';
$lang->upgrade->forbiddenExt  = 'Cette extension est incompatible avec la version. Elle a été désactivée :';
$lang->upgrade->updateFile    = "Le fichier information a besoin d'une mise à jour.";
$lang->upgrade->noticeSQL     = 'Votre base de donnée est inconsistente avec le standard et il y a eu un échec pour la corriger. Exécutez la commande SQL suivante et rafraichissez.';
$lang->upgrade->afterDeleted  = "Le fichier n'est pas supprimé. Recommencez après l'avoir supprimé.";

include dirname(__FILE__) . '/version.php';
