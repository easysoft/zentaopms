unit uMercury;

interface

uses GnuGettext, uBaseModule, SysUtils, Classes, Windows, ExtCtrls, StdCtrls, Buttons,
  uNetstatTable, uTools, uProcesses;

type
  tMercury = class(tBaseModule)
    OldPIDs, OldPorts: string;
    procedure ServiceInstall; override;
    procedure ServiceUnInstall; override;
    procedure Start; override;
    procedure Stop; override;
    procedure Admin; override;
    procedure UpdateStatus; override;
    procedure AddLog(Log: string; LogType: tLogType=ltDefault); reintroduce;
    constructor Create(pbbService: TBitBtn; pStatusPanel: tPanel; pPIDLabel, pPortLabel: tLabel; pStartStopButton, pAdminButton: tBitBtn);
    destructor Destroy; override;
  end;

implementation

uses uMain;

const
//  cServiceName = 'Mercury';
  cModuleName = 'Mercury';


var hWindow: HWND;

{ tMercury }

procedure tMercury.AddLog(Log: string; LogType: tLogType);
begin
  inherited AddLog('mercury', Log, LogType);
end;

function EnumProcess(hHwnd: HWND; lParam : integer): boolean; stdcall;
var
  pPid : DWORD;
  title, ClassName : string;
begin
  if (hHwnd=0) then begin
    result := false;
  end else begin
    GetWindowThreadProcessId(hHwnd,pPid);
    SetLength(ClassName, 255);
    SetLength(ClassName, GetClassName(hHwnd, PChar(className), Length(className)));
    SetLength(title, 255);
    SetLength(title, GetWindowText(hHwnd, PChar(title), Length(title)));
//    fMain.Addlog(
//       'Class Name = ' + className +
//       '; Title = ' + title +
//       '; HWND = ' + IntToStr(hHwnd) +
//       '; Pid = ' + IntToStr(pPid)
//       );
    if title='Mercury/32' then begin
      hWindow:=hHwnd;
    end;

    Result := true;
  end;
end;

procedure tMercury.Admin;
begin
  hWindow:=0;
  EnumWindows(@EnumProcess,0);
  if hWindow<>0 then
    ShowWindow(hWindow,SW_SHOW);
end;

constructor tMercury.Create;
const Ports:array[0..6] of integer=(25,79,105,106,110,143,2224);
var
  PortBlocker: string;
  ServerApp: string;
  p: Integer;
//  DidShowRunningWarn: Boolean;
  BlockedPorts: string;
begin
  inherited;
  ModuleName:=cModuleName;
  isService:=false;
//  DidShowRunningWarn:=false;
  AddLog(_('Initializing module...'),ltDebug);
  ServerApp:=basedir+'MercuryMail\mercury.exe';
  if not FileExists(ServerApp) then
    AddLog(Format(_('Possible problem detected: file "%s" not found - run this program from your XAMPP root directory!'),[ServerApp]),ltError);

  if Config.CheckDefaultPorts then begin
    AddLog(_('Checking default ports...'),ltDebug);
    for p:=Low(Ports) to High(Ports) do begin

      PortBlocker:=NetStatTable.isPortInUse(Ports[p]);
      if (PortBlocker<>'') then begin
        if (LowerCase(PortBlocker)=LowerCase(ServerApp)) then begin
//          if NOT DidShowRunningWarn then
//            AddLog(Format(_('"%s" seems to be running on port %d?'),[ServerApp,Ports[p]]),ltError);
//          DidShowRunningWarn:=true;
          if BlockedPorts='' then BlockedPorts:=InttoStr(Ports[p])
          else BlockedPorts:=BlockedPorts+', '+InttoStr(Ports[p]);
        end else begin
          AddLog('Possible problem detected: Port '+IntToStr(Ports[p])+' in use by "'+PortBlocker+'"!',ltError);
        AddLog(Format(_('Possible problem detected: Port %d in use by "%s"!'),[Ports[p],PortBlocker]),ltError);
        end;
      end;
    end;
    if BlockedPorts<>'' then
      AddLog(Format(_('"%s" seems to be running on port(s) %s?'),[ServerApp,BlockedPorts]),ltError);
  end;
end;

destructor tMercury.Destroy;
begin

  inherited;
end;

procedure tMercury.ServiceInstall;
begin
  inherited;

end;

procedure tMercury.ServiceUnInstall;
begin
  inherited;

end;

procedure tMercury.Start;
var
  App: string;
begin
  App:=BaseDir+'MercuryMail\mercury.exe';
  AddLog(Format(_('Starting %s app...'),[cModuleName]));
  Addlog(Format(_('Executing "%s"'),[App]),ltDebug);
  RunProcess(App,SW_HIDE,false);
  AddLog('Starting Mercury...');
end;

procedure tMercury.Stop;
var
  App: string;
begin
  AddLog('Stopping Mercury');
  Admin;
  AddLog(_('Stopping')+' '+cModuleName);
  App:=BaseDir+'apache\bin\pv.exe -f -c mercury.exe -q -e';
  Addlog(Format(_('Executing "%s"'),[App]),ltDebug);
  RunProcess(App,SW_HIDE,false);
end;

procedure tMercury.UpdateStatus;
 var
  p: Integer;
  ProcInfo: TProcInfo;
  s: string;
  ports: string;
begin
  if IsService then begin
  end else begin
    isRunning:=false;
    PIDList.Clear;
    for p:=0 to Processes.ProcessList.Count-1 do begin
      ProcInfo:=Processes.ProcessList[p];
      if (pos(BaseDir,ProcInfo.ExePath)=1) and (pos('mercury.exe',ProcInfo.Module)=1) then begin
        isRunning:=true;
        PIDList.Add(Pointer(ProcInfo.PID));
      end;
    end;
  end;
  s:='';
  // Checking processes
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
