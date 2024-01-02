<?php
global $config;

$lang->repo->common          = 'Référentiel';
$lang->repo->codeRepo        = 'Référentiel';
$lang->repo->browse          = 'Aff';
$lang->repo->viewRevision    = 'Aff Modifs';
$lang->repo->product         = $lang->productCommon;
$lang->repo->projects        = $lang->projectCommon;
$lang->repo->execution       = $lang->execution->common;
$lang->repo->create          = 'Créer';
$lang->repo->maintain        = 'Liste Ref';
$lang->repo->edit            = 'Editer';
$lang->repo->delete          = 'Suppr. Ref';
$lang->repo->showSyncCommit  = 'Afficher Synchronisation';
$lang->repo->ajaxSyncCommit  = 'Interface: Ajax Sync Note';
$lang->repo->setRules        = 'Fixer règles';
$lang->repo->download        = 'Download File';
$lang->repo->downloadDiff    = 'Download Diff';
$lang->repo->addBug          = 'Ajout Révision';
$lang->repo->editBug         = 'Editer Bug';
$lang->repo->deleteBug       = 'Suppr. Bug';
$lang->repo->addComment      = 'Ajout Comment.';
$lang->repo->editComment     = 'Edit. Comment';
$lang->repo->deleteComment   = 'Suppr. Comment';
$lang->repo->encrypt         = 'Encrypt';
$lang->repo->repo            = 'Repository';
$lang->repo->parent          = 'Parent File';
$lang->repo->branch          = 'Branch';
$lang->repo->tag             = 'Tag';
$lang->repo->addWebHook      = 'Add Webhook';
$lang->repo->apiGetRepoByUrl = 'API: Get repo by URL';
$lang->repo->blameTmpl       = 'Code for line <strong>%line</strong>: %name commited at %time, %version %comment';
$lang->repo->notRelated      = 'There is currently no related ZenTao object';
$lang->repo->source          = 'Criterion';
$lang->repo->target          = 'Contrast';
$lang->repo->descPlaceholder = 'One sentence description';
$lang->repo->namespace       = 'Namespace';
$lang->repo->branchName      = 'Branch Name';
$lang->repo->branchFrom      = 'Create from';

$lang->repo->createBranchAction = 'Create Branch';
$lang->repo->browseAction       = 'Browse Repo';
$lang->repo->createAction       = 'Créer Ref';
$lang->repo->editAction         = 'Editer Ref';
$lang->repo->diffAction         = 'Compare Code';
$lang->repo->downloadAction     = 'Download File';
$lang->repo->revisionAction     = 'Détail Révision';
$lang->repo->blameAction        = 'Blâme du Référentiel';
$lang->repo->reviewAction       = 'Review List';
$lang->repo->downloadCode       = 'Download Code';
$lang->repo->downloadZip        = 'Download compressed package';
$lang->repo->sshClone           = 'Clone with SSH';
$lang->repo->httpClone          = 'Clone with HTTP';
$lang->repo->cloneUrl           = 'Clone URL';
$lang->repo->linkTask           = 'Link Task';
$lang->repo->unlinkedTasks      = 'Unlinked Tasks';
$lang->repo->importAction       = 'Import Repo';
$lang->repo->import             = 'Import';
$lang->repo->importName         = 'Name after import';
$lang->repo->importServer       = 'Please select a server';
$lang->repo->gitlabList         = 'Gitlab Repo';
$lang->repo->batchCreate        = 'Batch create repo';

$lang->repo->createRepoAction = 'Create repository';

$lang->repo->submit     = 'Soumettre';
$lang->repo->cancel     = 'Annuler';
$lang->repo->addComment = 'Ajout Comment.';
$lang->repo->addIssue   = 'Add Issue';
$lang->repo->compare    = 'Compare';

$lang->repo->copy     = 'Click to copy';
$lang->repo->copied   = 'Copy successful';
$lang->repo->module   = 'Module';
$lang->repo->type     = 'Type';
$lang->repo->assign   = 'Assigner à';
$lang->repo->title    = 'Titre';
$lang->repo->detile   = 'Détail';
$lang->repo->lines    = 'Lignes';
$lang->repo->line     = 'Ligne';
$lang->repo->expand   = 'Déplier';
$lang->repo->collapse = 'Replier';

$lang->repo->id                 = 'ID';
$lang->repo->SCM                = 'Type';
$lang->repo->name               = 'Nom';
$lang->repo->path               = 'Chemin';
$lang->repo->prefix             = 'Préfixe';
$lang->repo->config             = 'Config';
$lang->repo->desc               = 'Description';
$lang->repo->account            = 'Nom Utilisateur';
$lang->repo->password           = 'Password';
$lang->repo->encoding           = 'Encodage';
$lang->repo->client             = 'Chemin Client';
$lang->repo->size               = 'Taille';
$lang->repo->revision           = 'Révision';
$lang->repo->revisionA          = 'Révision';
$lang->repo->revisions          = 'Révision';
$lang->repo->time               = 'Date';
$lang->repo->committer          = 'Committeur';
$lang->repo->commits            = 'Commits';
$lang->repo->synced             = 'Initialise Sync';
$lang->repo->lastSync           = 'Dern Sync';
$lang->repo->deleted            = 'Supprimé';
$lang->repo->commit             = 'Commit';
$lang->repo->comment            = 'Comment';
$lang->repo->view               = 'Voir Fichier';
$lang->repo->viewA              = 'Voir';
$lang->repo->log                = 'Log Révision';
$lang->repo->blame              = 'Blâme';
$lang->repo->date               = 'Date';
$lang->repo->diff               = 'Diff';
$lang->repo->diffAB             = 'Diff';
$lang->repo->diffAll            = 'Toutes Diff';
$lang->repo->viewDiff           = 'Voir diff';
$lang->repo->allLog             = 'All Commits';
$lang->repo->location           = 'Localisation';
$lang->repo->file               = 'Fichier';
$lang->repo->action             = 'Action';
$lang->repo->code               = 'Code';
$lang->repo->review             = 'Révision Ref';
$lang->repo->acl                = 'ACL';
$lang->repo->group              = 'Groupe';
$lang->repo->user               = 'User';
$lang->repo->info               = 'Info Version';
$lang->repo->job                = 'Job';
$lang->repo->fileServerUrl      = 'File Server Url';
$lang->repo->fileServerAccount  = 'File Server Account';
$lang->repo->fileServerPassword = 'File Server Password';
$lang->repo->linkStory          = 'Link ' . $lang->SRCommon;
$lang->repo->linkBug            = 'Link Bug';
$lang->repo->linkTask           = 'Link Task';
$lang->repo->unlink             = 'Unlink';
$lang->repo->viewBugs           = 'View Bugs';
$lang->repo->lastSubmitTime     = 'Final submission time';

$lang->repo->title      = 'Titre';
$lang->repo->status     = 'Statut';
$lang->repo->openedBy   = 'Créé par';
$lang->repo->assignedTo = 'Assigné à';
$lang->repo->openedDate = 'Date Création';

$lang->repo->latestRevision = 'Dernière Révision';
$lang->repo->actionInfo     = "Ajouté par %s dans %s";
$lang->repo->changes        = "Change Log";
$lang->repo->reviewLocation = "Fichier: %s@%s, ligne:%s - %s";
$lang->repo->commentEdit    = '<i class="icon-pencil"></i>';
$lang->repo->commentDelete  = '<i class="icon-remove"></i>';
$lang->repo->allChanges     = "Autres Changements";
$lang->repo->commitTitle    = "Le %sème Commit";
$lang->repo->mark           = "Mark Tag";
$lang->repo->split          = "Split Mark";

$lang->repo->objectRule   = 'Object Rule';
$lang->repo->objectIdRule = 'Object ID Rule';
$lang->repo->actionRule   = 'Action Rule';
$lang->repo->manHourRule  = 'Man-hour Rule';
$lang->repo->ruleUnit     = "Unit";
$lang->repo->ruleSplit    = "Plusieurs mots-clés sont séparés par ';'. Par exemple : plusieurs mots clés tâche : Tâche;tâche";

$lang->repo->viewDiffList['inline'] = 'Interligne';
$lang->repo->viewDiffList['appose'] = 'Parallèle';

$lang->repo->encryptList['plain']  = "Pas d'encodage";
$lang->repo->encryptList['base64'] = 'BASE64';

$lang->repo->logStyles['A'] = 'Ajout';
$lang->repo->logStyles['M'] = 'Modification';
$lang->repo->logStyles['D'] = 'Suppression';

$lang->repo->encodingList['utf_8'] = 'UTF-8';
$lang->repo->encodingList['gbk']   = 'GBK';

$lang->repo->scmList['Gitlab']     = 'GitLab';
$lang->repo->scmList['Gogs']       = 'Gogs';
if(!$config->inQuickon) $lang->repo->scmList['Gitea']      = 'Gitea';
$lang->repo->scmList['Git']        = 'Git';
$lang->repo->scmList['Subversion'] = 'Subversion';

$lang->repo->aclList['private'] = 'Private(The product and related project personnel can access it)';
$lang->repo->aclList['open']    = 'Open(Users with privileges to DevOps can access it)';
$lang->repo->aclList['custom']  = 'Custom';

$lang->repo->gitlabHost    = 'GitLab Host';
$lang->repo->gitlabToken   = 'GitLab Token';
$lang->repo->gitlabProject = 'Project';

$lang->repo->serviceHost    = 'Host';
$lang->repo->serviceProject = 'Project';

$lang->repo->placeholder = new stdclass;
$lang->repo->placeholder->gitlabHost = 'Input url of gitlab';

$lang->repo->notice                 = new stdclass();
$lang->repo->notice->syncing        = 'Synchronisation en cours. Veuillez patienter ...';
$lang->repo->notice->syncComplete   = 'Synchronisé. Vous allez être redirigé ...';
$lang->repo->notice->syncFailed     = 'Synchronized failed.';
$lang->repo->notice->syncedCount    = "Le nombre d'enregistrements synchronisés est ";
$lang->repo->notice->delete         = 'Etes vous certain de vouloir supprimer ce référentiel ?';
$lang->repo->notice->successDelete  = 'Le référentiel est supprimé.';
$lang->repo->notice->commentContent = 'Commentaire';
$lang->repo->notice->deleteReview   = 'Do you want to delete this review?';
$lang->repo->notice->deleteBug      = 'Êtes-vous sûr de vouloir supprimer ce bug ?';
$lang->repo->notice->deleteComment  = 'Êtes-vous certain de vouloir supprimer ce commentaire ?';
$lang->repo->notice->lastSyncTime   = 'Dern Sync:';

$lang->repo->rules = new stdclass();
$lang->repo->rules->exampleLabel = "Comment Exemple";
$lang->repo->rules->example['task']['start']  = "%start% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['task']['finish'] = "%finish% %task% %id%1%split%2 %cost%%consumedmark%10%cunit%";
$lang->repo->rules->example['task']['effort'] = "%effort% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['bug']['resolve'] = "%resolve% %bug% %id%1%split%2";

$lang->repo->error = new stdclass();
$lang->repo->error->useless           = 'Votre serveur a désactivé exec et shell_exec, il ne peut donc pas être appliqué.';
$lang->repo->error->connect           = "La connexion au référentiel a échoué. Veuillez saisir correctement le nom d'utilisateur, le mot de passe et l'adresse du référentiel !";
$lang->repo->error->version           = 'Version 1.8+ de https et du protocole svn est requis. Veuillez mettre à jour vers la dernière version ! Allez à http://subversion.apache.org/';
$lang->repo->error->path              = "L'adresse du référentiel est le chemin du fichier, ex: /home/test";
$lang->repo->error->cmd               = 'Erreur du Client !';
$lang->repo->error->diff              = 'Deux version doivent être sélectionnées.';
$lang->repo->error->safe              = "For security reasons, the client version needs to be detected. Please write the version to the file %s. \n Execute command: %s";
$lang->repo->error->product           = "Veuillez sélectionner un {$lang->productCommon}!";
$lang->repo->error->commentText       = 'Veuillez entrer du contenu pour une révision !';
$lang->repo->error->comment           = 'Veuillez entrer le contenu du commentaire !';
$lang->repo->error->title             = 'Veuillez saisir un titre !';
$lang->repo->error->accessDenied      = "Vous n'avez pas les privilèges d'accéder au référentiel.";
$lang->repo->error->noFound           = 'Le référentiel est non trouvé.';
$lang->repo->error->noFile            = "%s n'existe pas.";
$lang->repo->error->noPriv            = "Le programme n'a pas les privilèges pour basculer vers %s";
$lang->repo->error->output            = "La commande est: %s\nL'erreur est (%s): %s\n";
$lang->repo->error->clientVersion     = "La version du client est trop ancienne, veuillez mettre à niveau ou changer le client SVN";
$lang->repo->error->encoding          = "L'encodage est peut-être erroné. Veuillez modifier l'encodage et réessayer.";
$lang->repo->error->deleted           = "Deletion of the repository failed. The current repository has a commit record associated with the design.";
$lang->repo->error->linkedJob         = "Deletion of the repository failed. The current repository has associated with the Compile.";
$lang->repo->error->clientPath        = "The client installation directory cannot have spaces!";
$lang->repo->error->notFound          = "The repository %s’s URL %s does not exist. Please confirm if this repository has been deleted from the local server.";
$lang->repo->error->noWritable        = '%s is not writable! Please check the privilege, or download will not be done.';
$lang->repo->error->noCloneAddr       = 'The repository clone address was not found';
$lang->repo->error->differentVersions = 'The criterion and contrast cannot be the same';
$lang->repo->error->needTwoVersion    = 'Two branches or tags must be selected.';
$lang->repo->error->emptyVersion      = 'Version cannot be empty';
$lang->repo->error->versionError      = 'Wrong version format!';
$lang->repo->error->projectUnique     = $lang->repo->serviceProject . " exists. Go to Admin->System->Data->Recycle Bin to restore it, if you are sure it is deleted.";
$lang->repo->error->repoNameInvalid   = 'The name should contain only alphanumeric numbers, dashes, underscores, and dots.';
$lang->repo->error->createdFail       = 'Create failed';
$lang->repo->error->noProduct         = 'Please associate the product corresponding to the project before starting to associate the code repository.';

$lang->repo->syncTips          = '<strong>Vous pouvez trouver la référence sur la façon de définir la synchronisation Git à partir de la page se trouvant <a target="_blank" href="https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-git-105.html">ici</a>.</strong>';
$lang->repo->encodingsTips     = "Les encodages des commentaires de validation peuvent être des valeurs séparées par des virgules，ex: utf-8";
$lang->repo->pathTipsForGitlab = "GitLab Project URL";

$lang->repo->example              = new stdclass();
$lang->repo->example->client      = new stdclass();
$lang->repo->example->path        = new stdclass();
$lang->repo->example->client->git = "ex: /usr/bin/git";
$lang->repo->example->client->svn = "ex: /usr/bin/svn";
$lang->repo->example->path->git   = "ex: /home/user/myproject";
$lang->repo->example->path->svn   = "ex: http://example.googlecode.com/svn/trunk/myproject";
$lang->repo->example->config      = "Le répertoire de configuration est requis en https. Utilisez '--config-dir' pour générer le répertoire de configuration.";
$lang->repo->example->encoding    = "encodage d'entrée des fichiers";

$lang->repo->typeList['standard']    = 'Standard';
$lang->repo->typeList['performance'] = 'Performance';
$lang->repo->typeList['security']    = 'Securité';
$lang->repo->typeList['redundancy']  = 'Redondance';
$lang->repo->typeList['logicError']  = 'Erreur Logique';

$lang->repo->featureBar['maintain']['all'] = 'All';
