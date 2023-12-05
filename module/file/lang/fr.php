<?php
/**
 * The file module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common        = 'Fichier';
$lang->file->id            = 'ID';
$lang->file->objectType    = 'Object Type';
$lang->file->objectID      = 'Object ID';
$lang->file->deleted       = 'Deleted';
$lang->file->uploadImages  = 'Charger Images en Lot';
$lang->file->download      = 'Télécharger Fichiers';
$lang->file->uploadDate    = 'Chargé le';
$lang->file->edit          = 'Renommer';
$lang->file->inputFileName = 'Entrez Nom Fichier';
$lang->file->delete        = 'Supprimer Fichier';
$lang->file->label         = 'Label :';
$lang->file->maxUploadSize = "<span class='text-red'>%s</span>";
$lang->file->applyTemplate = "Appliquer Modèle";
$lang->file->tplTitle      = "Nom Modèle";
$lang->file->tplTitleAB    = "Modèles";
$lang->file->setPublic     = "Rendre Public le Modèle";
$lang->file->exportFields  = "Champs";
$lang->file->exportRange   = "Données";
$lang->file->defaultTPL    = "Défaut";
$lang->file->setExportTPL  = "Paramétrages";
$lang->file->preview       = "Apperçu";
$lang->file->previewFile   = "aperçu de la pièce jointe";
$lang->file->addFile       = 'Ajouter';
$lang->file->beginUpload   = 'Clic pour Charger';
$lang->file->uploadSuccess = 'Chargé !';
$lang->file->batchExport   = 'Export in batches';
$lang->file->downloadFile  = 'Download';
$lang->file->playFailed    = 'Video preview failed, please contact admin';
$lang->file->exportData    = "Exporter Données";

$lang->file->pathname  = 'Nom Chemin';
$lang->file->title     = 'Titre';
$lang->file->fileName  = 'Nom Fichier';
$lang->file->untitled  = 'Sans Titre';
$lang->file->extension = 'Format';
$lang->file->size      = 'Taille';
$lang->file->encoding  = 'Encodage';
$lang->file->addedBy   = 'Ajouté par';
$lang->file->addedDate = 'Ajouté le';
$lang->file->downloads = 'Téléchargements';
$lang->file->extra     = 'Commentaires';

$lang->file->dragFile            = 'Faites glisser images ici.';
$lang->file->childTaskTips       = 'Il s\'agit d\'une sous-tâche s\'il y a un \'>\' devant le nom.';
$lang->file->uploadImagesExplain = "Note : Chargez images au format .jpg, .jpeg, .gif, ou .png. Le nom de l'image sera le nom de la Story et l'image en sera sa description.";
$lang->file->uploadingImages     = 'There are <strong>%s</strong> files being uploaded.';
$lang->file->saveAndNext         = 'Enregistrer et poursuivre';
$lang->file->importPager         = 'Total: <strong>%s</strong>. Page <strong>%s</strong> de <strong>%s</strong>';
$lang->file->importSummary       = "Import <strong id='totalAmount'>%s</strong> items  Vous pouvez avoir <strong>%s</strong> items/page, vous devez donc importer <strong id='times'>%s</strong> fois.";
$lang->file->accessDenied        = 'Access denied to this file!';

$lang->file->errorNotExists   = "<span class='text-red'>'%s' non trouvé.</span>";
$lang->file->errorCanNotWrite = "<span class='text-red'>'%s' non inscriptible. Changez les permissions. Entrez la commande <span class='code'>sudo chmod -R 777 '%s'</span></span> sous Linux.";
$lang->file->confirmDelete    = " Voulez-vous le supprimer ?";
$lang->file->errorFileSize    = " Taille du fichier excédant la limite. Il ne peut être chargé !";
$lang->file->errorFileUpload  = " Echec du chargement. La taille du fichier peut excéder la limite.";
$lang->file->errorFileFormate = " Echec du chargement. Le format du fichier est non autorisé.";
$lang->file->errorFileMove    = " Echec du chargement. Une erreur est survenue lors du chargement.";
$lang->file->dangerFile       = " Le fichier n'a pas pu être chargé pour des raisons de sécurité.";
$lang->file->errorSuffix      = 'Format incorrect. Fichiers .zip SEULEMENT !';
$lang->file->errorExtract     = "Echec de l'extraction des fichiers. Les fichiers peuvent être endommagé ou il y a un fichier invalide dans le zip.";
$lang->file->errorUploadEmpty = 'No upload file.';
$lang->file->fileNotFound     = 'Fichier non trouvé. Le fichier physique a peut-être été supprimé par innadvertance !';
$lang->file->fileContentEmpty = 'The file is empty. Check the file and upload it again.';
$lang->file->bizGuide         = 'To utilize Excel import/export, upgrade to ZenTao %s edition';

$lang->file->uploadError[1] = 'The uploaded filesize exceeds the limit. Please change the upload_max_filesize and post_max_size options in php.ini';
$lang->file->uploadError[2] = 'The size of the uploaded file exceeds the value specified by the MAX_FILE_SIZE option in the HTML form';
$lang->file->uploadError[3] = 'Only part of the file has been uploaded, please re-upload';
$lang->file->uploadError[4] = 'No files have been uploaded';
$lang->file->uploadError[5] = 'The size of the file is 0. Please upload the file again';
