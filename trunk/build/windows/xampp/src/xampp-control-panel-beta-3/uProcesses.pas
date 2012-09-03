unit uProcesses;

interface

uses GnuGettext, TlHelp32, uTools, Classes, SysUtils, Windows;

type
  TProcInfo = class
    PID: integer;
    Module, ExePath: String;
    CanDelete: boolean;
  end;

  tProcesses = class
  public
    ProcessList: tList;
    function GetProcInfo(PID: integer):TProcInfo;
    procedure Update;
    constructor Create;
    destructor Destroy; override;
  end;


var
    Processes: tProcesses;

implementation

uses uMain;

const cModuleName = 'procs';

{ tProcessList }

constructor tProcesses.Create;
begin
  ProcessList:=TList.Create;
end;

destructor tProcesses.Destroy;
var
  ProcInfo: TProcInfo;
  p: Integer;
begin
  for p:=0 to ProcessList.Count-1 do begin
    ProcInfo:=ProcessList[p];
    FreeAndNil(ProcInfo);
  end;
  FreeAndNil(ProcessList);
  inherited;
end;

function tProcesses.GetProcInfo(PID: integer): TProcInfo;
var
  ProcInfo: TProcInfo;
  p: Integer;
begin
  for p:=0 to ProcessList.Count-1 do begin
    ProcInfo:=ProcessList[p];
    if ProcInfo.PID=pid then begin
      result:=ProcInfo;
      exit;
    end;
  end;
  Result:=nil;
end;

procedure tProcesses.Update;
var hProcessSnap: tHandle;
    TProcessEntry: TProcessEntry32;
    ProcInfo: TProcInfo;
    hModuleSnap: tHandle;
    ModuleEntry: MODULEENTRY32;
  i: Integer;
  PID: Cardinal;
begin
//  fMain.AddLog('processes', 'Checking processes...', ltDebugDetails);

  for i:=0 to ProcessList.Count-1 do begin
    ProcInfo:=ProcessList[i];
    ProcInfo.CanDelete:=true;
//    FreeAndNil(ProcInfo);
  end;
//  ProcessList.Clear;

  hProcessSnap := CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0);
  if (hProcessSnap = INVALID_HANDLE_VALUE) then exit;
  TProcessEntry.dwSize := SizeOf(TProcessEntry);
  if (Process32First(hProcessSnap, TProcessEntry)) then begin
    repeat
      PID:=TProcessEntry.th32ProcessID;
      ProcInfo:=GetProcInfo(PID);
      if ProcInfo<>nil then begin
        ProcInfo.CanDelete:=false
      end else begin
//        hModuleSnap := INVALID_HANDLE_VALUE;
        hModuleSnap := CreateToolhelp32Snapshot(TH32CS_SNAPMODULE, TProcessEntry.th32ProcessID );
        if (hModuleSnap <> INVALID_HANDLE_VALUE) then begin
          ModuleEntry.dwSize := sizeof(MODULEENTRY32);
          if (Module32First(hModuleSnap, &ModuleEntry)) then begin
            ProcInfo:=TProcInfo.Create;
            ProcInfo.Module:=LowerCase(ModuleEntry.szModule);
            ProcInfo.ExePath:=LowerCase(ModuleEntry.szExePath);
            ProcInfo.PID:=TProcessEntry.th32ProcessID;
            ProcInfo.CanDelete:=false;
            ProcessList.Add(ProcInfo);
          end else begin
            ProcInfo:=nil;
          end;
        end else begin
          ProcInfo:=TProcInfo.Create;
          ProcInfo.Module:=LowerCase(TProcessEntry.szExeFile);
          ProcInfo.ExePath:=LowerCase(TProcessEntry.szExeFile);
          ProcInfo.PID:=TProcessEntry.th32ProcessID;
          ProcInfo.CanDelete:=false;
          ProcessList.Add(ProcInfo);
        end;
        if ProcInfo<>nil then
          fMain.AddLog(cModuleName,Format(_('Creating PID-entry %d: %s'),[ProcInfo.PID,ProcInfo.ExePath]),ltDebugDetails);
        CloseHandle(hModuleSnap);
      end;
    until not (Process32Next(hProcessSnap, TProcessEntry));
  end;
  CloseHandle(hProcessSnap);

  i:=0;
  while i<ProcessList.Count do begin
    ProcInfo:=ProcessList[i];
    if ProcInfo.CanDelete then begin
      fMain.AddLog(cModuleName,Format(_('Deleting PID-entry %d: %s'),[ProcInfo.PID,ProcInfo.ExePath]),ltDebugDetails);
      FreeAndNil(ProcInfo);
      ProcessList.Delete(i);
    end else begin
      inc(i);
    end;
  end;
end;

initialization

Processes:=tProcesses.Create;

finalization

Processes.Free;

end.
