unit uNetstatTable;

interface

uses GnuGettext, SysUtils, Classes, Windows, Dialogs, uProcesses;

const
  MIB_TCP_STATE_LISTEN = 2;

type
  MIB_TCPROW_OWNER_PID = packed record
    dwState: DWORD;
    dwLocalAddr: DWORD;
    dwLocalPort: DWORD;
    dwRemoteAddr: DWORD;
    dwRemotePort: DWORD;
    dwOwningPid: DWORD;
    end;
//  PMIB_TCPROW_OWNER_PID = ^MIB_TCPROW_OWNER_PID;

  MIB_TCPTABLE_OWNER_PID = packed record
    dwNumEntries: DWord;
    table: array [0..99999] of MIB_TCPROW_OWNER_PID ;
  end;
  PMIB_TCPTABLE_OWNER_PID = ^MIB_TCPTABLE_OWNER_PID;


  tNetstatTable = class
  private
    hLibModule: THandle;
    DLLProcPointer: Pointer;
    procedure LoadExIpHelperProcedures;
    procedure UnLoadExIpHelperProcedures;
  public
    pTcpTable: PMIB_TCPTABLE_OWNER_PID;
    procedure UpdateTable;
    function GetPorts4PID(pid: integer):string;
    function GetPortCount4PID(pid: integer):integer;
    function isPortInUse(port: integer): string;
    constructor Create;
    destructor Destroy; override;
  end;


var
    NetStatTable: tNetstatTable;


implementation

uses uMain, uTools;

const
  TCP_TABLE_OWNER_PID_ALL = 5;
  AF_INET = 2;

type
  TCP_TABLE_CLASS = Integer;
  ULONG = Integer;
  TGetExtendedTcpTable = function(pTcpTable: Pointer; dwSize: PDWORD; bOrder: BOOL; lAf: ULONG; TableClass: TCP_TABLE_CLASS; Reserved: ULONG): DWord;stdcall;


{ tNetStatTable }

constructor tNetstatTable.Create;
begin
  DLLProcPointer:=nil;
  hLibModule:=0;
  pTCPTable:=nil;
  try
    LoadExIpHelperProcedures;
  except
  end;
end;

destructor tNetstatTable.Destroy;
begin
  try
    UnLoadExIpHelperProcedures;
  except
  end;
  inherited;
end;

function tNetstatTable.GetPortCount4PID(pid: integer): integer;
var i: integer;
    port: string;
begin
  result:=0;
  for i:=0 to NetStatTable.pTcpTable.dwNumEntries-1 do
    if NetStatTable.pTcpTable.table[i].dwOwningPid=Cardinal(pid) then
      result:=result+1;
end;

function tNetstatTable.GetPorts4PID(pid: integer): string;
var i: integer;
    port: string;
begin
  result:='';
  for i:=0 to NetStatTable.pTcpTable.dwNumEntries-1 do begin
    if NetStatTable.pTcpTable.table[i].dwOwningPid=Cardinal(pid) then begin
      port:=IntToStr(NetStatTable.pTcpTable.table[i].dwLocalPort);
      if result='' then result:=port
      else result:=result+', '+port;
    end;
  end;
end;

function tNetstatTable.isPortInUse(port: integer): string;
var i: integer;
  ProcInfo: TProcInfo;
  pid: Cardinal;
begin
  result:='';
  for i:=0 to NetStatTable.pTcpTable.dwNumEntries-1 do begin
    if NetStatTable.pTcpTable.table[i].dwLocalPort=Cardinal(port) then begin
      pid:=NetStatTable.pTcpTable.table[i].dwOwningPid;
      ProcInfo:=Processes.GetProcInfo(pid);
      if ProcInfo<>nil then result:=ProcInfo.ExePath
      else result:=_('unknown program');
      exit;
    end;
  end;
end;

procedure tNetstatTable.LoadExIpHelperProcedures;
begin
  hLibModule:=LoadLibrary('iphlpapi.dll');
  if hLibModule=0 then exit;
  DLLProcPointer:=GetProcAddress(hLibModule,'GetExtendedTcpTable');
  if not Assigned(DLLProcPointer) then begin
    ShowMessage(IntToStr(GetLastError));
  end;
end;

procedure tNetstatTable.UnLoadExIpHelperProcedures;
begin
  if hLibModule>HINSTANCE_ERROR then
    FreeLibrary(hLibModule);
end;

procedure tNetstatTable.UpdateTable;
var
  dwSize: DWord;
  Res: Dword;
  GetExtendedTcpTable: TGetExtendedTcpTable;
  i: integer;
begin
  if pTcpTable<>nil then begin
    FreeMem(pTcpTable);
    pTCPTable:=nil;
  end;

  if (DLLProcPointer=nil) or (hLibModule<HINSTANCE_ERROR) then begin
    exit;
  end;

  GetExtendedTcpTable:=DLLProcPointer;
  Try
    dwSize:=0;
    Res := GetExtendedTcpTable(pTcpTable, @dwSize, False, AF_INET, TCP_TABLE_OWNER_PID_ALL, 0);
    If (Res = ERROR_INSUFFICIENT_BUFFER) Then Begin
      GetMem(pTCPTable,dwSize); // das API hat die "gewünschte" Grösse gesetzt
      Res :=  GetExtendedTcpTable(pTcpTable, @dwSize, False, AF_INET, TCP_TABLE_OWNER_PID_ALL, 0);
    end;
    If (Res = NO_ERROR) then begin
      for i := 0 to pTcpTable.dwNumEntries-1 do begin
        pTcpTable.table[i].dwLocalPort:=
          ((pTcpTable.table[i].dwLocalPort and $FF00) shr 8) or
          ((pTcpTable.table[i].dwLocalPort and $00FF) shl 8);
        pTcpTable.table[i].dwRemotePort:=
          ((pTcpTable.table[i].dwRemotePort and $FF00) shr 8) or
          ((pTcpTable.table[i].dwRemotePort and $00FF) shl 8);
      end;

    end else begin
      raiseLastOSError(); // Error-Handling
    end;
  Finally
//    If (pTcpTable <> Nil) Then FreeMem(pTcpTable);
  end;
end;

initialization

NetStatTable:=tNetstatTable.Create;

finalization

NetStatTable.Free;

end.
