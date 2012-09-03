unit uTomcat;

interface

uses GnuGettext, uBaseModule, SysUtils, Classes, Windows, ExtCtrls, StdCtrls, Buttons,
  uNetstatTable, uTools, uProcesses;

type
  tTomcat = class(tBaseModule)
    OldPIDs, OldPorts: string;
    procedure ServiceInstall; override;
    procedure ServiceUnInstall; override;
    procedure Start; override;
    procedure Stop; override;
    procedure Admin; override;
    procedure UpdateStatus; override;
    procedure CheckIsService; reintroduce;
    procedure AddLog(Log: string; LogType: tLogType=ltDefault); reintroduce;
    constructor Create(pbbService: TBitBtn; pStatusPanel: tPanel; pPIDLabel, pPortLabel: tLabel; pStartStopButton, pAdminButton: tBitBtn);
    destructor Destroy; override;
  end;


implementation

const cModuleName = 'tomcat';

{ tTomcat }

procedure tTomcat.AddLog(Log: string; LogType: tLogType);
begin
  inherited AddLog(cModuleName, Log, LogType);
end;

procedure tTomcat.Admin;
var
  App, Param: string;
begin
  Param:='http://localhost:8080/';
  if Config.BrowserApp<>'' then begin
    App:=Config.BrowserApp;
    ExecuteFile(App,Param,'',SW_SHOW);
    Addlog(Format(_('Executing "%s" "%s"'),[App,Param]),ltDebug);
  end else begin
    ExecuteFile(Param,'','',SW_SHOW);
    Addlog(Format(_('Executing "%s"'),[Param]),ltDebug);
  end;
end;

procedure tTomcat.CheckIsService;
begin
  isService:=false;
end;

constructor tTomcat.Create(pbbService: TBitBtn; pStatusPanel: tPanel; pPIDLabel,
  pPortLabel: tLabel; pStartStopButton, pAdminButton: tBitBtn);
const Ports:array[0..2] of integer=(1,2,3);
var
  PortBlocker: string;
  ServerApp1, ServerApp2: string;
  p: integer;
begin
  inherited;
  ModuleName:=cModuleName;
  AddLog(_('Initializing module...'),ltDebug);
  ServerApp1:=basedir+'tomcat\bin\tomcat6.exe';
  ServerApp2:=basedir+'tomcat\bin\tomcat7.exe';
  if ((not FileExists(ServerApp1)) and (not FileExists(ServerApp2))) then
    AddLog(Format(_('Possible problem detected: file "%s" not found - run this program from your XAMPP root directory!'),[ServerApp1+'/'+ServerApp2]),ltError);



  CheckIsService;

  if Config.CheckDefaultPorts then begin
    AddLog(_('Checking default ports...'),ltDebug);

    for p:=Low(Ports) to High(Ports) do begin
      PortBlocker:=NetStatTable.isPortInUse(Ports[p]);
      if (PortBlocker<>'') then begin
        if (LowerCase(PortBlocker)=LowerCase(ServerApp1)) then begin
          AddLog(Format(_('"%s" seems to be running on port %d?'),[ServerApp1,Ports[p]]),ltError);
        end else if (LowerCase(PortBlocker)=LowerCase(ServerApp2)) then begin
          AddLog(Format(_('"%s" seems to be running on port %d?'),[ServerApp2,Ports[p]]),ltError);
        end else begin
          AddLog(Format(_('Possible problem detected: Port %d in use by "%s"!'),[Ports[p],PortBlocker]),ltError);
        end;
      end;
    end;

  end;

end;

destructor tTomcat.Destroy;
begin

  inherited;
end;

procedure tTomcat.ServiceInstall;
begin
  inherited;

end;

procedure tTomcat.ServiceUnInstall;
begin
  inherited;

end;

procedure tTomcat.Start;
var
  App, Param: string;
begin
  AddLog(Format(_('Starting %s app...'),[cModuleName]));
  Param:='/c '+BaseDir+'catalina_start.bat';
  if FileExists('c:\Windows\sysnative\cmd.exe') then app:='c:\Windows\sysnative\cmd.exe'  // 64 bit? dann DIESE shell starten!
  else app:='cmd';
  Addlog(Format(_('Executing "%s" "%s"'),[App,Param]),ltDebug);
  if Config.TomcatVisible then
    ExecuteFile(App,Param,BaseDir,SW_MINIMIZE)
  else
    ExecuteFile(App,Param,BaseDir,SW_HIDE)
end;

procedure tTomcat.Stop;
var
  App, Param: string;
begin
  AddLog(_('Stopping'));
  Param:='/c '+BaseDir+'catalina_stop.bat';
  if FileExists('c:\Windows\sysnative\cmd.exe') then app:='c:\Windows\sysnative\cmd.exe'  // 64 bit? dann DIESE shell starten!
  else app:='cmd';
  Addlog(Format(_('Executing "%s" "%s"'),[App,Param]),ltDebug);
  ExecuteFile(App,Param,BaseDir,SW_HIDE);
end;

procedure tTomcat.UpdateStatus;
var
  p: Integer;
  ProcInfo: TProcInfo;
  s: string;
  ports: string;
begin
  isRunning:=false;
  PIDList.Clear;
  for p:=0 to Processes.ProcessList.Count-1 do begin
    ProcInfo:=Processes.ProcessList[p];
    if { (pos(BaseDir,ProcInfo.ExePath)=1) and } (pos('java.exe',ProcInfo.Module)=1) then begin

      // Sind genau 3 Ports offen?
      if NetStatTable.GetPortCount4PID(ProcInfo.PID)=3 then begin
        isRunning:=true;
        PIDList.Add(Pointer(ProcInfo.PID));
      end;
    end;
  end;

  // Checking processes
  s:='';
  for p:=0 to PIDList.Count-1 do begin
    if p=0 then s:=IntToStr(Integer(PIDList[p]))
    else s:=s+#13+IntToStr(Integer(PIDList[p]));
  end;
  if s<>OldPIDs then begin
    lPID.Caption:=s;
    OldPIDs:=s;
  end;

  // Checking netstats
  s:='';
  for p:=0 to PIDList.Count-1 do begin
    ports:=NetStatTable.GetPorts4PID(Integer(PIDList[p]));
    if ports<>'' then begin
      if s='' then s:=ports
      else s:=s+', '+ports;
    end;
  end;
  if s<>OldPorts then begin
    lPort.Caption:=s;
    OldPorts:=s;
  end;

  if byte(isRunning)<>oldIsRunningByte then begin

    if oldIsRunningByte<>2 then begin
      if isRunning then s:=_('running')
      else s:=_('stopped');
      AddLog(_('Status change detected:')+' '+s);
    end;

    oldIsRunningByte:=byte(isRunning);
    if isRunning then begin
      pStatus.Color:=cRunningColor;
      bStartStop.Caption:=_('Stop');
      bAdmin.Enabled:=true;
    end else begin
      pStatus.Color:=cStoppedColor;
      bStartStop.Caption:=_('Start');
      bAdmin.Enabled:=false;
    end;
  end;

  if AutoStart then begin
    AutoStart:=false;
    if isRunning then begin
      AddLog(_('Autostart active: modul is already running - aborted'),ltError);
    end else begin
      AddLog(_('Autostart active: starting...'));
      Start;
    end;
  end;
end;

end.
