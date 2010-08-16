<?php
/**
 * The convert module English file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
$lang->convert->common  = 'Import';
$lang->convert->next    = 'Next';
$lang->convert->pre     = 'Back';
$lang->convert->reload  = 'Reload';
$lang->convert->error   = 'Error ';

$lang->convert->start   = 'Begin import';

$lang->convert->desc    = <<<EOT
<p>Welcome to use this convert wizard which will help you to import other system data to ZenTaoPMS.</p>
<strong>Importing is dangerous. Be sure to backup your database and other data files and sure nobody is using pms when importing.</strong>
EOT;

$lang->convert->selectSource     = 'Select source system and version';
$lang->convert->source           = 'Source system';
$lang->convert->version          = 'Version';
$lang->convert->mustSelectSource = "Must select a source system";

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');

$lang->convert->setting     = 'Setting';
$lang->convert->checkConfig = 'Check setting';

$lang->convert->ok         = 'Check passed(√)';
$lang->convert->fail       = 'Check failed(×)';

$lang->convert->settingDB  = 'Set database';
$lang->convert->dbHost     = 'Database server';
$lang->convert->dbPort     = 'Server port';
$lang->convert->dbUser     = 'Database user';
$lang->convert->dbPassword = 'Database password';
$lang->convert->dbName     = '%s database';
$lang->convert->dbPrefix   = '%s table prefix';
$lang->convert->installPath= '%s installed path';

$lang->convert->checkDB    = 'Database';
$lang->convert->checkTable = 'Table';
$lang->convert->checkPath  = 'Installed path';

$lang->convert->execute    = 'Execute import';
$lang->convert->item       = 'Imported items';
$lang->convert->count      = 'Count';
$lang->convert->info       = 'Info';

$lang->convert->bugfree->users    = 'User';
$lang->convert->bugfree->projects = 'Project';
$lang->convert->bugfree->modules  = 'Module';
$lang->convert->bugfree->bugs     = 'Bug';
$lang->convert->bugfree->cases    = 'Case';
$lang->convert->bugfree->results  = 'Result';
$lang->convert->bugfree->actions  = 'History';
$lang->convert->bugfree->files    = 'File';

$lang->convert->errorConnectDB     = 'Connect to database server failed.';
$lang->convert->errorFileNotExits  = 'File %s not exits.';
$lang->convert->errorUserExists    = 'User %s exits already.';
$lang->convert->errorCopyFailed    = 'file %s copy failed.';
