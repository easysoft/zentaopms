<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: en.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */

include (dirname(__FILE__) . '/common.php');

global $config;

$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '-';
$lang->comma     = ',';
$lang->dot       = '.';
$lang->at        = ' am ';
$lang->downArrow = '↓';
$lang->null      = 'Null';
$lang->ellipsis  = '…';
$lang->percent   = '%';
$lang->dash      = '-';
$lang->and       = 'and';

$lang->zentaoPMS      = 'ZenTao';
$lang->pmsName        = 'ALM';
$lang->proName        = 'Pro';
$lang->bizName        = 'Biz';
$lang->maxName        = 'Max';
$lang->liteName       = 'Lite';
$lang->logoImg        = 'zt-logo-en.png';
$lang->welcome        = "%s PMS";
$lang->logout         = 'Abmelden';
$lang->login          = 'Anmelden';
$lang->help           = 'Hilfe';
$lang->aboutZenTao    = 'ZenTao';
$lang->ztWebsite      = 'ZenTao Address';
$lang->profile        = 'Profil';
$lang->changePassword = 'Passwort';
$lang->unfoldMenu     = 'Unfold Menu';
$lang->collapseMenu   = 'Collapse Menu';
$lang->preference     = 'Preference';
$lang->tutorialAB     = 'Tutorial';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time %s MS, Memory %s KB, Query %s.  </div></div>";
$lang->agreement      = "I have read and agreed to the terms and conditions of <a href='http://zpl.pub/page/zplv12.html' target='_blank'> Z PUBLIC LICENSE 1.2 </a>. <span class='text-danger'>Without authorization, I should not remove, hide or cover any logos/links of ZenTao.</span>";
$lang->designedByAIUX = "<a href='https://api.zentao.net/goto.php?item=aiux' class='link-aiux' target='_blank'><i class='icon icon-aiux'></i> AIUX</a>";

$lang->reset          = 'Zurücksetzen';
$lang->cancel         = 'Abbrechen';
$lang->refresh        = 'Aktualisieren';
$lang->refreshIcon    = "<i title='$lang->refresh' class='icon icon-refresh'></i>";
$lang->create         = 'Create';
$lang->edit           = 'Bearbeiten';
$lang->delete         = 'Löschen';
$lang->activate       = 'Activate';
$lang->close          = 'Schließen';
$lang->unlink         = 'Entfernen';
$lang->import         = 'Importieren';
$lang->export         = 'Exportieren';
$lang->setFileName    = 'Dateiame';
$lang->submitting     = 'Speichern...';
$lang->save           = 'Speichern';
$lang->confirm        = 'Bestätigen';
$lang->preview        = 'Ansehen';
$lang->goback         = 'Zurück';
$lang->goPC           = 'PC';
$lang->more           = 'Mehr';
$lang->moreLink       = 'MORE';
$lang->day            = 'Tag';
$lang->customConfig   = 'Custom Config';
$lang->public         = 'Öffentlich';
$lang->trunk          = 'Trunk';
$lang->sort           = 'Sortieren';
$lang->required       = 'Pflicht';
$lang->noData         = 'Kein Datensatz';
$lang->fullscreen     = 'Fullscreen';
$lang->retrack        = 'Retrack';
$lang->whitelist      = 'Weiße Liste';
$lang->globalSetting  = 'Global Setting';
$lang->waterfallModel = 'Waterfall';
$lang->scrumModel     = 'Scrum';
$lang->all            = 'All';
$lang->viewDetails    = 'View Details';

$lang->actions         = 'Aktionen';
$lang->restore         = 'Wiederherstellen';
$lang->comment         = 'Notiz';
$lang->history         = 'Historie';
$lang->attatch         = 'Anlagen';
$lang->reverse         = 'Umkehren';
$lang->switchDisplay   = 'Umschalten';
$lang->switchTo        = 'Switch To';
$lang->expand          = 'Alle aufklappen';
$lang->collapse        = 'Zuklappen';
$lang->saveSuccess     = 'Gespeichert';
$lang->importSuccess   = 'Gespeichert';
$lang->fail            = 'Fehlgeschlagen';
$lang->addFiles        = 'Hinzufügen ';
$lang->files           = 'Datei ';
$lang->pasteText       = 'Einfügen';
$lang->uploadImages    = 'Hochladen';
$lang->timeout         = 'Timeout. Bitte prüfen Sie Ihre Netzwerkverbindung oder versuchen Sie es erneut!';
$lang->repairTable     = 'Die Datenbank scheint koruppt zu sein. Bitte prüfen Sie die Datenbank.';
$lang->duplicate       = '%s existiert bereits.';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>Sorry, die IP Adresse wurde eingeschränkt. Bitte kontaktieren Sie den Administrator.</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = 'Als Startseite setzen';
$lang->noviceTutorial  = 'Anleitung';
$lang->changeLog       = 'Änderungsprotokoll';
$lang->manual          = 'Handbuch';
$lang->customMenu      = 'Benutzer Menü';
$lang->customField     = 'Individualfeld';
$lang->lineNumber      = 'Zeile Nr.';
$lang->tutorialConfirm = 'Sie benutzen die Anleitung. Möchten Sie diese jetzt verlassen？';
$lang->levelExceeded   = 'The level has exceeded the display range. For more information, please go to the web page or view it through search.';
$lang->noticeOkFile    = 'Aus Sicherheitsgrü nden muss Ihr Adminkonto bestätigt werden. \n Bitte melden Sie sich an und erstellen Sie die Datei %s File.\n Hinweis:\n 1. Die Datei ist leer.\n 2. Wenn die Datei bereits existiert, löschen Sie sie und erstellen Sie eine neue Datei.';

$lang->serviceAgreement = "Service Agreement";
$lang->privacyPolicy    = "Privacy Policy";
$lang->needAgreePrivacy = "Please read the service agreement and privacy policy first";
$lang->iAgreedPrivacy   = "I have read and agree";

$lang->preShortcutKey    = '[Shortcut:←]';
$lang->nextShortcutKey   = '[Shortcut:→]';
$lang->backShortcutKey   = '[Shortcut:Alt+↑]';
$lang->shortcutOperation = 'Quick Start';

$lang->select        = 'Auswählen';
$lang->selectAll     = 'Alle';
$lang->selectReverse = 'Auswahl umkehren';
$lang->loading       = 'Lade...';
$lang->notFound      = 'Nicht gefunden!';
$lang->notPage       = 'Sorry, the features you are visiting are in development!';
$lang->showAll       = '[[Alle anzeigen]]';
$lang->selectedItems = 'Seleted <strong>{0}</strong> items';

$lang->future      = 'Warte';
$lang->year        = 'Jahr';
$lang->workingHour = 'Stunde';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = 'Status';
$lang->openedByAB   = 'Ersteller';
$lang->assignedToAB = 'Bearbeiter';
$lang->typeAB       = 'Typ';
$lang->nameAB       = 'Name';
$lang->code         = 'Code';

$lang->pri     = 'Priority';
$lang->delayed = 'Delayed';

$lang->common->common     = 'Standard Module';
$lang->my->common         = 'My';
$lang->program->common    = 'Program';
$lang->product->common    = 'Product';
$lang->project->common    = 'Project';
$lang->execution->common  = $config->systemMode == 'new' ? 'Execution' : $lang->executionCommon;
$lang->kanban->common     = 'Kanban';
$lang->qa->common         = 'QA';
$lang->devops->common     = 'DevOps';
$lang->doc->common        = 'Doc';
$lang->repo->common       = 'Code';
$lang->report->common     = 'Statistic';
$lang->system->common     = 'System';
$lang->admin->common      = 'Admin';
$lang->story->common      = 'Story';
$lang->task->common       = 'Task';
$lang->bug->common        = 'Bug';
$lang->testcase->common   = 'Testcase';
$lang->testtask->common   = 'Request';
$lang->score->common      = 'Score';
$lang->build->common      = 'Build';
$lang->testreport->common = 'Report';
$lang->automation->common = 'Automation';
$lang->team->common       = 'Team';
$lang->user->common       = 'User';
$lang->custom->common     = 'Custom';
$lang->extension->common  = 'Extension';
$lang->company->common    = 'Company';
$lang->dept->common       = 'Dept';
$lang->upgrade->common    = 'Update';
$lang->program->list      = 'Program List';
$lang->program->kanban    = 'Program Kanban';
$lang->design->common     = 'Design';
$lang->design->HLDS       = 'HLDS';
$lang->design->DDS        = 'DDS';
$lang->design->DBDS       = 'DBDS';
$lang->design->ADS        = 'ADS';
$lang->stage->common      = 'Stage';
$lang->stage->list        = 'Stage List';
$lang->execution->list    = "{$lang->executionCommon} List";
$lang->kanban->common     = 'Kanban';
$lang->backup->common     = 'Backup';
$lang->action->trash      = 'Recycle';
$lang->app->common        = 'APP';
$lang->app->serverLink    = 'Server Link';

$lang->personnel->common     = 'Member';
$lang->personnel->invest     = 'Investment';
$lang->personnel->accessible = 'Accessible';

$lang->stakeholder->common = 'Stakeholder';
$lang->release->common     = 'Release';
$lang->message->common     = 'Message';
$lang->mail->common        = 'Mail';

$lang->my->shortCommon          = 'My';
$lang->testcase->shortCommon    = 'Case';
$lang->productplan->shortCommon = 'Plan';
$lang->score->shortCommon       = 'Score';
$lang->testreport->shortCommon  = 'Report';
$lang->qa->shortCommon          = 'QA';

$lang->dashboard       = 'Dashboard';
$lang->contribute      = 'Contribute';
$lang->dynamic         = 'Dynamic';
$lang->contact         = 'Contacts';
$lang->whitelist       = 'Weiße Liste';
$lang->roadmap         = 'Roadmap';
$lang->track           = 'Track';
$lang->settings        = 'Settings';
$lang->overview        = 'Overview';
$lang->module          = 'Module';
$lang->priv            = 'Privilege';
$lang->other           = 'Other';
$lang->estimation      = 'Estimation';
$lang->measure         = 'Report';
$lang->treeView        = 'Tree View';
$lang->groupView       = 'Group View';
$lang->executionKanban = 'Kanban';
$lang->burn            = 'Burndown';
$lang->view            = 'View';
$lang->intro           = 'Introduction';
$lang->indexPage       = 'Index';
$lang->model           = 'Model';
$lang->redev           = 'Develop';
$lang->browser         = 'Browser';
$lang->db              = 'Database';
$lang->editor          = 'Editor';
$lang->timezone        = 'Timezone';
$lang->security        = 'Security';
$lang->calendar        = 'Calendar';

$lang->my->work = 'Work';

$lang->project->list   = 'Project List';
$lang->project->kanban = 'Project Kanban';

$lang->execution->executionKanban = "{$lang->execution->common} Kanban";
$lang->execution->all             = "{$lang->execution->common} List";

$lang->doc->recent    = 'Recent';
$lang->doc->my        = 'My';
$lang->doc->favorite  = 'Favorite';
$lang->doc->product   = 'Product';
$lang->doc->project   = 'Project';
$lang->doc->api       = 'API';
$lang->doc->execution = $lang->execution->common;
$lang->doc->custom    = 'Custom';
$lang->doc->wiki      = 'WIKI';
$lang->doc->apiDoc    = 'API Docuemnt';
$lang->doc->apiStruct = 'Data Structure';

$lang->product->list   = $lang->productCommon . ' List';
$lang->product->kanban = $lang->productCommon . ' Kanban';

$lang->project->report = 'Report';

$lang->report->weekly = 'Weekly';
$lang->report->annual = 'Annual Summary';
$lang->report->notice = new stdclass();
$lang->report->notice->help = 'Hinweis: Berichte wurde auf Basis der Suche generiert. Bitte suchen Sie in der Liste bevor Sie einen Bericht generieren.';

$lang->testcase->case      = 'Test Case';
$lang->testcase->testsuite = 'Test Suite';
$lang->testcase->caselib   = 'Case Library';

$lang->devops->compile  = 'Compile';
$lang->devops->mr       = 'Merge Request';
$lang->devops->repo     = 'Repo';
$lang->devops->rules    = 'Rule';
$lang->devops->settings = 'Setting Merge Request';
$lang->devops->set      = 'Set';

$lang->admin->system      = 'System';
$lang->admin->entry       = 'Application';
$lang->admin->data        = 'Data';
$lang->admin->cron        = 'Cron';
$lang->admin->buildIndex  = 'Full Text Search';
$lang->admin->tableEngine = 'Table Engine';

$lang->convert->importJira = 'Import Jira';

$lang->storyConcept = 'Story Concpet';

$lang->searchTips = '';
$lang->searchAB   = 'Search';

/* Object list in search form. */
$lang->searchObjects['all']         = 'All';
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = 'Story';
$lang->searchObjects['task']        = 'Aufgaben';
$lang->searchObjects['testcase']    = 'Fall';
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['build']       = 'Build';
$lang->searchObjects['release']     = 'Release';
$lang->searchObjects['productplan'] = $lang->productCommon . 'Plan';
$lang->searchObjects['testtask']    = 'Test Aufgabe';
$lang->searchObjects['doc']         = 'Doc';
$lang->searchObjects['caselib']     = 'Case Library';
$lang->searchObjects['testreport']  = 'Test-Bericht';
$lang->searchObjects['program']     = 'Program';
$lang->searchObjects['project']     = 'Project';
$lang->searchObjects['execution']   = $lang->executionCommon;
$lang->searchObjects['user']        = 'User';
$lang->searchTips                   = '';

/* Code formats for import. */
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* File type list for export. */
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = 'Alle';
$lang->exportTypeList['selected'] = 'Ausgewählte';

$lang->visionList = array();
$lang->visionList['rnd']  = 'R&D';
$lang->visionList['lite'] = 'Lite';

$lang->createObjects['todo']        = 'Todo';
$lang->createObjects['effort']      = 'Effort';
$lang->createObjects['bug']         = 'Bug';
$lang->createObjects['story']       = 'Story';
$lang->createObjects['task']        = 'Task';
$lang->createObjects['testcase']    = 'Case';
$lang->createObjects['execution']   = $lang->execution->common;
$lang->createObjects['project']     = 'Project';
$lang->createObjects['product']     = 'Product';
$lang->createObjects['program']     = 'Program';
$lang->createObjects['doc']         = 'Doc';
$lang->createObjects['kanbanspace'] = 'Space';
$lang->createObjects['kanban']      = 'Kanban';

/* Language. */
$lang->lang    = 'Sprache';
$lang->setLang = 'Language Setting';

/* Theme style. */
$lang->theme                = 'Theme';
$lang->themes['default']    = 'Standard';
$lang->themes['blue']       = 'Young Blue';
$lang->themes['green']      = 'Grün';
$lang->themes['red']        = 'Rot';
$lang->themes['purple']     = 'Lila';
$lang->themes['pink']       = 'Pink';
$lang->themes['blackberry'] = 'Dunkelblau';
$lang->themes['classic']    = 'Klassisch';

/* Error info. */
$lang->error = new stdclass();
$lang->error->companyNotFound = "The domain %s cannot be found!";
$lang->error->length          = array("『%s』Length Error. It should be『%s』", "『%s』length should be <=『%s』and >『%s』.");
$lang->error->reg             = "『%s』Format Error. It should be『%s』.";
$lang->error->unique          = "『%s』『%s』existed. Please go to Admin->System->Data->Recycle to restore it, if you are sure it is deleted.";
$lang->error->repeat          = "『%s』『%s』exists.";
$lang->error->gt              = "『%s』should be >『%s』.";
$lang->error->ge              = "『%s』should be >=『%s』.";
$lang->error->lt              = "『%s』should be <『%s』。";
$lang->error->le              = "『%s』should be <=『%s』。";
$lang->error->notempty        = "『%s』should not be blank.";
$lang->error->empty           = "『%s』should be null.";
$lang->error->equal           = "『%s』has to be『%s』.";
$lang->error->int             = array("『%s』should be numbers", "『%s』should be 『%s-%s』.");
$lang->error->float           = "『%s』should be numbers, decimals included.";
$lang->error->email           = "『%s』should be valid Email.";
$lang->error->phone           = "『%s』should be valid phone number.";
$lang->error->mobile          = "『%s』should be valid mobile number.";
$lang->error->URL             = "『%s』should be url.";
$lang->error->date            = "『%s』should be valid date.";
$lang->error->datetime        = "『%s』should be valid date.";
$lang->error->code            = "『%s』should be letters or numbers.";
$lang->error->account         = "『%s』should be valid account.";
$lang->error->passwordsame    = "The two passwords should be the same.";
$lang->error->passwordrule    = "Password should follow rules. It must be at least 6 characters.";
$lang->error->accessDenied    = 'Access is denied.';
$lang->error->pasteImg        = 'Image is not allowed to be pasted in your browser!';
$lang->error->noData          = 'No Data';
$lang->error->editedByOther   = 'This record might have been changed. Please refresh and try to edit again!';
$lang->error->tutorialData    = 'No data can be imported in tutorial mode. Please exit tutorial first!';
$lang->error->noCurlExt       = 'No Curl module installed';
$lang->error->loginTimeout    = 'Login has timed out, please login again!';

/* Page info. */
$lang->pager = new stdclass();
$lang->pager->noRecord     = "No Records";
$lang->pager->digest       = " <strong>%s</strong> in total. %s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = " <strong>%s</strong> per page";
$lang->pager->first        = "<i class='icon-step-backward' title='Home'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='Previous Page'></i>";
$lang->pager->next         = "<i class='icon-play' title='Next Page'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='Last Page'></i>";
$lang->pager->locate       = "Go!";
$lang->pager->previousPage = "Prev";
$lang->pager->nextPage     = "Next";
$lang->pager->summery      = "<strong>%s-%s</strong> of <strong>%s</strong>.";
$lang->pager->pageOfText   = "Page {0}";
$lang->pager->firstPage    = "First";
$lang->pager->lastPage     = "Last";
$lang->pager->goto         = "Goto";
$lang->pager->pageOf       = "Page <strong>{page}</strong>";
$lang->pager->totalPage    = "<strong>{totalPage}</strong> pages";
$lang->pager->totalCount   = "Total: <strong>{recTotal}</strong> items";
$lang->pager->pageSize     = "<strong>{recPerPage}</strong> per page";
$lang->pager->itemsRange   = "From <strong>{start}</strong> to <strong>{end}</strong>";
$lang->pager->pageOfTotal  = "Page <strong>{page}</strong> of <strong>{totalPage}</strong>";

$lang->colorPicker = new stdclass();
$lang->colorPicker->errorTip = 'Not a valid color value';

$lang->downNotify     = "Download Desktop Notification";
$lang->clientName     = "Desktop";
$lang->downloadClient = "Download ZenTao Desktop";
$lang->downloadMobile = "Download Mobile Terminal";
$lang->clientHelp     = "Client Help";
$lang->clientHelpLink = "https://www.zentao.pm/book/zentaomanual/scrum-tool-im-integration-206.html";
$lang->website        = "http://www.zentao.net";

$lang->suhosinInfo     = "Warning! Data is reaching the limit. Please change <font color=red>sohusin.post.max_vars</font> and <font color=red>sohusin.request.max_vars</font> (set larger %s value) in php.ini, then save and restart Apache or php-fpm, or some data will not be saved.";
$lang->maxVarsInfo     = "Warning! Data is reaching the limit. Please change <font color=red>max_input_vars</font> (set larger %s value) in php.ini, then save and restart Apache or php-fpm, or some data will not be saved.";
$lang->pasteTextInfo   = "Paste text here. Each line will be the title of each record. ";
$lang->noticeImport    = "Imported data contains data that has already existed in system. Please confirm you actions on the date.";
$lang->importConfirm   = "Import Confirm";
$lang->importAndCover  = "Override";
$lang->importAndInsert = "New Insertion";

$lang->noResultsMatch     = "Keine weiteren Treffer!";
$lang->searchMore         = "Weitere Treffer：";
$lang->chooseUsersToMail  = "Choose users that will be notified.";
$lang->noticePasteImg     = "Paste images here";
$lang->pasteImgFail       = "Failed to paste images. Try again later.";
$lang->pasteImgUploading  = "Uploading...";

/* Time formats settings. */
if(!defined('DT_DATETIME1'))      define('DT_DATETIME1',  'Y-m-d H:i:s');
if(!defined('DT_DATETIME2'))      define('DT_DATETIME2',  'y-m-d H:i');
if(!defined('DT_MONTHTIME1'))     define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2'))     define('DT_MONTHTIME2', 'n/d H:i');
if(!defined('DT_DATE1'))          define('DT_DATE1',     'Y-m-d');
if(!defined('DT_DATE2'))          define('DT_DATE2',     'Ymd');
if(!defined('DT_DATE3'))          define('DT_DATE3',     'Y/m/d');
if(!defined('DT_DATE4'))          define('DT_DATE4',     'M d');
if(!defined('DT_DATE5'))          define('DT_DATE5',     'j/n');
if(!defined('DT_TIME1'))          define('DT_TIME1',     'H:i:s');
if(!defined('DT_TIME2'))          define('DT_TIME2',     'H:i');
if(!defined('LONG_TIME'))         define('LONG_TIME',    '2059-12-31');
if(!defined('BRANCH_MAIN'))       define('BRANCH_MAIN', '0');
if(!defined('DEFAULT_CARDCOUNT')) define('DEFAULT_CARDCOUNT', '2');
if(!defined('MAX_CARDCOUNT'))     define('MAX_CARDCOUNT', '32767');

/* Datepicker. */
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = 'oder ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = 'Letzes Jahr';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = 'Letzen Monat';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = 'Letzte Woche';
$lang->datepicker->dpText->TEXT_YESTERDAY   = 'Gestern';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = 'Dieser Monat';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = 'Diese Woche';
$lang->datepicker->dpText->TEXT_TODAY       = 'Heute';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = 'Nächstes Jahr';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = 'Nächsten Monat';
$lang->datepicker->dpText->TEXT_CLOSE       = 'Schließen';
$lang->datepicker->dpText->TEXT_DATE        = 'Zeit wählen';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = 'Datum wählen';

$lang->datepicker->dayNames     = array('Sonntag', 'Montag', 'Diensteg', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
$lang->datepicker->abbrDayNames = array('Son', 'Mon', 'Die', 'Mit', 'Don', 'Fri', 'Sam');
$lang->datepicker->monthNames   = array('Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez');

include (dirname(__FILE__) . '/menu.php');
