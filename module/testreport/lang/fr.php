<?php
$lang->testreport->common       = 'Rapport de Test';
$lang->testreport->id           = 'ID';
$lang->testreport->browse       = 'Rapports de Test';
$lang->testreport->create       = 'Créer un Rapport';
$lang->testreport->edit         = 'Modifier Rapport';
$lang->testreport->delete       = 'Supprimer Rapport';
$lang->testreport->export       = 'Exporter';
$lang->testreport->exportAction = 'Exporter Rapport';
$lang->testreport->view         = 'Détail Rapport';
$lang->testreport->recreate     = 'Recréer';

$lang->testreport->title       = 'Titre';
$lang->testreport->product     = $lang->productCommon;
$lang->testreport->bugTitle    = 'Bug';
$lang->testreport->storyTitle  = 'Story';
$lang->testreport->project     = $lang->projectCommon;
$lang->testreport->execution   = 'Execution';
$lang->testreport->testtask    = 'Test Task';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = 'Début&Fin';
$lang->testreport->owner       = 'Propriétaire';
$lang->testreport->members     = 'Utilisateurs';
$lang->testreport->begin       = 'Début';
$lang->testreport->end         = 'Fin';
$lang->testreport->stories     = 'Story Testée';
$lang->testreport->bugs        = 'Bug Testé';
$lang->testreport->builds      = 'Info Build';
$lang->testreport->goal        = 'But ' . $lang->projectCommon;
$lang->testreport->cases       = 'CasTest';
$lang->testreport->bugInfo     = 'Distribution Bugs';
$lang->testreport->report      = 'Résumé';
$lang->testreport->legacyBugs  = 'Bugs Restants';
$lang->testreport->createdBy   = 'Créé par';
$lang->testreport->createdDate = 'Créé le';
$lang->testreport->objectID    = 'Objet';
$lang->testreport->objectType  = 'Object Type';
$lang->testreport->profile     = 'Profil';
$lang->testreport->value       = 'Valeur';
$lang->testreport->none        = 'Aucun';
$lang->testreport->all         = 'Tous Rapports';
$lang->testreport->deleted     = 'Supprimé';
$lang->testreport->selectTask  = 'Créer rapport par Campagne';

$lang->testreport->legendBasic       = 'Infos de Base';
$lang->testreport->legendStoryAndBug = 'Périmètre';
$lang->testreport->legendBuild       = 'Test Rounds';
$lang->testreport->legendCase        = 'CasTests associés';
$lang->testreport->legendLegacyBugs  = 'Bugs Restants';
$lang->testreport->legendReport      = 'Rapport';
$lang->testreport->legendComment     = 'Résumé';
$lang->testreport->legendMore        = 'Plus';
$lang->testreport->date              = 'Date';

$lang->testreport->bugSeverityGroups   = 'Distribution Sévérité de Bug';
$lang->testreport->bugTypeGroups       = 'Distribution Type de Bug';
$lang->testreport->bugStatusGroups     = 'Distribution Statut de Bug';
$lang->testreport->bugOpenedByGroups   = 'Distribution Bug Signalé par';
$lang->testreport->bugResolvedByGroups = 'Distribution Bug Résolu par';
$lang->testreport->bugResolutionGroups = 'Distribution Bug Résolution';
$lang->testreport->bugModuleGroups     = 'Distribution Bug Module';
$lang->testreport->bugStageGroups      = 'Bug Priority Distribution';
$lang->testreport->bugHandleGroups     = 'Distribution of daily bug processing';
$lang->testreport->legacyBugs          = 'Bugs Restants';
$lang->testreport->bugConfirmedRate    = 'Taux de Bugs confirmés (Résolution est corrigée ou reportée / statut est résolu ou fermé)';
$lang->testreport->bugCreateByCaseRate = 'Taux Bug Signalés par CasTest (Bugs signalés dans les CasTests / Nouveaux bugs)';

$lang->testreport->bugStageList = array();
$lang->testreport->bugStageList['generated'] = 'Generated Bugs';
$lang->testreport->bugStageList['legacy']    = 'Legacy Bugs';
$lang->testreport->bugStageList['resolved']  = 'Resolved Bugs';

$lang->testreport->featureBar['browse']['all'] = 'all';

$lang->testreport->caseSummary     = 'Total <strong>%s</strong> casTests. <strong>%s</strong> casTests joués. <strong>%s</strong> résultats générés. <strong>%s</strong> casTests ont échoué.';
$lang->testreport->buildSummary    = 'Testé <strong>%s</strong> builds.';
$lang->testreport->confirmDelete   = 'Voulez-vous supprimer ce rapport ?';
$lang->testreport->moreNotice      = "Plus de fonctionnalités peuvent être étendues en référence au manuel de l'extension ZenTao, ou vous pouvez nous contacter à support@zentaoalm.com pour la personnalisation.";
$lang->testreport->exportNotice    = "Exporté par <a href='https://www.zentao.pm' target='_blank' style='color:grey'>ZenTao</a>";
$lang->testreport->noReport        = "Aucun rapport n'a été généré. Vérifiez plus tard.";
$lang->testreport->foundBugTip     = "Bugs trouvés dans cette période de build et les build affectés sont dans la période de test.";
$lang->testreport->legacyBugTip    = "Bigs Actifs, ou bugs qui n'ont pas été résolus dans la période de test.";
$lang->testreport->activatedBugTip = "Reactived bugs during the testtask.";
$lang->testreport->fromCaseBugTip  = "Bugs trouvés en jouant des CasTests pendant la période de test.";
$lang->testreport->errorTrunk      = "Vous ne pouvez pas créer un rapport de test pour le tronc. Modifiez le build lié !";
$lang->testreport->noTestTask      = "Pas de campagne de test pour ce {$lang->productCommon}, aucun rapport ne peut être généré. Choisissez un {$lang->productCommon} avec des campagnes de recette et vous pourrez produire des rapports.";
$lang->testreport->noObjectID      = "Pas de campagne de test ou un {$lang->executionCommon} est sélectionné, aucun rapport ne peut être généré.";
$lang->testreport->moreProduct     = "Les rapports de test ne peuvent être produits que pour le même {$lang->productCommon}.";
$lang->testreport->hiddenCase      = "Hide %s use cases";
$lang->testreport->goalTip         = "Descriptive information about the {$lang->execution->common} of this build";
$lang->testreport->runDateTips     = "Des cas ont été exécutés en dehors de la plage de temps (dernier temps : %s), non inclus dans le rapport";
$lang->testreport->ignore          = "Ignorer";

$lang->testreport->bugSummary = <<<EOD
Total <strong>%s</strong> Bugs reported <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs remained unresolved <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs reactivated <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->activatedBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs found from the running of cases<a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-help'></i></a>.
Bug Effective Rate <a data-toggle='tooltip' class='text-warning' title='Resolution is resolved or delayed / status is resolved or closed'><i class='icon-help'></i></a>: <strong>%s</strong>，Bugs-reported-from-cases rate<a data-toggle='tooltip' class='text-warning' title='Bugs created from cases / bugs'><i class='icon-help'></i></a>: <strong>%s</strong>
EOD;
