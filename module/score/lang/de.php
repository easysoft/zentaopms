<?php
$lang->score->common       = 'Meine Wertung';
$lang->score->record       = 'Score Record';
$lang->score->current      = 'Aktuelle Wertung';
$lang->score->level        = 'Wertungs Level';
$lang->score->reset        = 'Reset';
$lang->score->tips         = 'Gestern hinzugefügte Wertung: <strong>%d</strong><br/>Summe Wertung: <strong>%d</strong>';
$lang->score->resetTips    = 'Das wird eine Zeit lang dauern. <strong>Möchten Sie das Fenster schließen?</strong>';
$lang->score->resetStart   = 'Start';
$lang->score->resetLoading = 'Verarbeitung: ';
$lang->score->resetFinish  = 'Abgeschlossen';

$lang->score->id      = 'ID';
$lang->score->userID  = 'BenutzerID';
$lang->score->account = 'Benutzer';
$lang->score->module  = 'Module';
$lang->score->method  = 'Methode';
$lang->score->before  = 'Vor';
$lang->score->score   = 'Wertung';
$lang->score->after   = 'Nach';
$lang->score->time    = 'Zeit';
$lang->score->desc    = 'Beschreibung';
$lang->score->noLimit = 'Ohne Limit';
$lang->score->times   = 'Zeiten';
$lang->score->hour    = 'Stunden';

$lang->score->modules['task']        = 'Aufgabe';
$lang->score->modules['tutorial']    = 'Anleitung';
$lang->score->modules['user']        = 'Benutzer';
$lang->score->modules['ajax']        = 'Sonstiges';
$lang->score->modules['doc']         = 'Dok';
$lang->score->modules['todo']        = 'Todo';
$lang->score->modules['story']       = 'Story';
$lang->score->modules['bug']         = 'Bug';
$lang->score->modules['testcase']    = 'Testfall';
$lang->score->modules['testtask']    = 'Testaufgabe';
$lang->score->modules['build']       = 'Build';
$lang->score->modules['execution']   = $lang->executionCommon;
$lang->score->modules['productplan'] = 'Plan';
$lang->score->modules['release']     = 'Release';
$lang->score->modules['block']       = 'Block';
$lang->score->modules['search']      = 'Suche';

$lang->score->methods['task']['create']              = 'Aufgabe erstellen';
$lang->score->methods['task']['close']               = 'Aufgabe schließen';
$lang->score->methods['task']['finish']              = 'Aufageb abschließen';
$lang->score->methods['tutorial']['finish']          = 'Anleitung beenden';
$lang->score->methods['user']['login']               = 'Anmelden';
$lang->score->methods['user']['changePassword']      = 'Passwort ändern';
$lang->score->methods['user']['editProfile']         = 'Profil bearbeiten';
$lang->score->methods['ajax']['selectTheme']         = 'Theme ändern';
$lang->score->methods['ajax']['selectLang']          = 'Sprache wählen';
$lang->score->methods['ajax']['showSearchMenu']      = 'Erweiterte Suche';
$lang->score->methods['ajax']['customMenu']          = 'Eigenes Menü';
$lang->score->methods['ajax']['dragSelected']        = 'Auswahl ablegen';
$lang->score->methods['ajax']['lastNext']            = 'Seite gedreht';
$lang->score->methods['ajax']['switchToDataTable']   = 'Tabelle wechseln';
$lang->score->methods['ajax']['submitPage']          = 'Andere Seite';
$lang->score->methods['ajax']['quickJump']           = 'Springen';
$lang->score->methods['ajax']['batchCreate']         = 'Mehere erstellen';
$lang->score->methods['ajax']['batchEdit']           = 'Mehere aktualisiern';
$lang->score->methods['ajax']['batchOther']          = 'Mehere Sonstige';
$lang->score->methods['doc']['create']               = 'Dok erstellen';
$lang->score->methods['todo']['create']              = 'Todo erstellen';
$lang->score->methods['story']['create']             = 'Story erstellen';
$lang->score->methods['story']['close']              = 'Story schließen';
$lang->score->methods['bug']['create']               = 'Bug erstellen';
$lang->score->methods['bug']['confirm']              = 'Bug bestätigen';
$lang->score->methods['bug']['createFormCase']       = 'Bug aus Fall erzeugen';
$lang->score->methods['bug']['resolve']              = 'Bug lösen';
$lang->score->methods['bug']['saveTplModal']         = 'Bug Vorlage erstellen';
$lang->score->methods['testtask']['runCase']         = 'Testfall ausführen';
$lang->score->methods['testcase']['create']          = 'Testfall erstellen';
$lang->score->methods['build']['create']             = 'Build erstellen';
$lang->score->methods['execution']['create']         = "{$lang->executionCommon} erstellen";
$lang->score->methods['execution']['close']          = "{$lang->executionCommon} abschließe";
$lang->score->methods['productplan']['create']       = 'Plan erstellen';
$lang->score->methods['release']['create']           = 'Release erstellen';
$lang->score->methods['block']['set']                = 'Eigener Block';
$lang->score->methods['search']['saveQuery']         = 'Suche speichern';
$lang->score->methods['search']['saveQueryAdvanced'] = 'Erweitere Suche';

$lang->score->extended['user']['changePassword'] = 'Get ##strength,1## point, if password is medium. Get ##strength,2## points, if it is strong.';
$lang->score->extended['execution']['close']     = 'Nachdem das Projekt geschlossen wurde, bekommt der Projektmanager ##manager,close## Punkte und Teammitglieder ##member,close## Punkte. Wenn es vorzeitig erledigt ist, bekommt der Projektmanager ##manager,onTime## Punkte und Teammitglieder ##member,onTime## Punkte.';
$lang->score->extended['bug']['resolve']         = 'Nach dem Lösen eines Bugs, werden die Punkte nach der Dringlichkeit vergeben. S1, + ##severity,3##; S2 + ##severity,2##, S3 + ##severity,1##.';
$lang->score->extended['bug']['confirm']         = 'Wenn ein Bug bestätigt wurde, werden die Punkte nach der Dringlichkeit vergeben. S1, + ##severity,3##; S2 + ##severity,2##, S3 + ##severity,1##.';
$lang->score->extended['task']['finish']         = 'Wenn eine Aufgabe erledigt wurde, werden folgende Punkte vergeben: runde(Mannstunden / 10  Schätzung / Verbraucht) + Prirität (p1 ##pri,1##, p2 ##pri,2##).';
$lang->score->extended['story']['close']         = 'Wenn eine Story geschlossen wurde, bekommt der Ersteler ##createID## Punkte.';

$lang->score->featureBar['rule']['all'] = 'Score Rules';
