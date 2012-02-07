unit uConfig;

interface

uses
  GnuGettext, Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, Buttons, uTools;

type
  TfConfig = class(TForm)
    bSelectEditor: TBitBtn;
    eEditor: TEdit;
    OpenDialog1: TOpenDialog;
    Label1: TLabel;
    bSave: TBitBtn;
    bAbort: TBitBtn;
    Label2: TLabel;
    bSelectBrowser: TBitBtn;
    eBrowser: TEdit;
    cbDebug: TCheckBox;
    cbDebugDetails: TComboBox;
    GroupBox1: TGroupBox;
    cbASApache: TCheckBox;
    cbASMySQL: TCheckBox;
    cbASFileZilla: TCheckBox;
    cbASMercury: TCheckBox;
    Label3: TLabel;
    cbCheckDefaultPorts: TCheckBox;
    BitBtn1: TBitBtn;
    bConfigUserdefined: TBitBtn;
    cbTomcatVisible: TCheckBox;
    procedure bAbortClick(Sender: TObject);
    procedure bSaveClick(Sender: TObject);
    procedure FormShow(Sender: TObject);
    procedure bSelectEditorClick(Sender: TObject);
    procedure bSelectBrowserClick(Sender: TObject);
    procedure cbDebugClick(Sender: TObject);
    procedure BitBtn1Click(Sender: TObject);
    procedure FormCreate(Sender: TObject);
    procedure FormKeyPress(Sender: TObject; var Key: Char);
    procedure bConfigUserdefinedClick(Sender: TObject);
  private
    { Private-Deklarationen }
  public
  end;

var
  fConfig: TfConfig;

implementation

uses uMain, uLanguage, uConfigUserDefined;

{$R *.dfm}

procedure TfConfig.bSelectBrowserClick(Sender: TObject);
var dp: string;
begin
  dp:=ExtractFilePath(eBrowser.Text);
  if dp<>'' then begin
    OpenDialog1.InitialDir:=dp;
    OpenDialog1.FileName:=ExtractFileName(eBrowser.Text);
  end;
  if OpenDialog1.Execute then eBrowser.Text:=OpenDialog1.FileName;
end;

procedure TfConfig.bSelectEditorClick(Sender: TObject);
var dp: string;
begin
  dp:=ExtractFilePath(eEditor.Text);
  if dp<>'' then begin
    OpenDialog1.InitialDir:=dp;
    OpenDialog1.FileName:=ExtractFileName(eEditor.Text);
  end;
  if OpenDialog1.Execute then eEditor.Text:=OpenDialog1.FileName;
end;

procedure TfConfig.cbDebugClick(Sender: TObject);
begin
  cbDebugDetails.Visible:=cbDebug.Checked;
end;

procedure TfConfig.BitBtn1Click(Sender: TObject);
begin
  fLanguage.ShowModal;
end;

procedure TfConfig.bConfigUserdefinedClick(Sender: TObject);
begin
  fConfigUserDefined.ShowModal;
end;

procedure TfConfig.bSaveClick(Sender: TObject);
begin
  Config.EditorApp:=Trim(eEditor.Text);
  Config.BrowserApp:=Trim(eBrowser.Text);
  Config.ShowDebug:=cbDebug.Checked;
  Config.DebugLevel:=cbDebugDetails.ItemIndex;
  Config.CheckDefaultPorts:=cbCheckDefaultPorts.Checked;
  Config.TomcatVisible:=cbTomcatVisible.Checked;

  Config.ASApache:=cbASApache.Checked;
  Config.ASMySQL:=cbASMySQL.Checked;
  Config.ASFileZilla:=cbASFileZilla.Checked;
  Config.ASMercury:=cbASMercury.Checked;

  SaveSettings;
  Close;
end;

procedure TfConfig.bAbortClick(Sender: TObject);
begin
  Close;
end;

procedure TfConfig.FormCreate(Sender: TObject);
begin
  TranslateComponent(Self);
end;

procedure TfConfig.FormKeyPress(Sender: TObject; var Key: Char);
begin
  if key=#27 then begin key:=#0; bAbort.Click; end;
end;

procedure TfConfig.FormShow(Sender: TObject);
begin
  eEditor.Text:=Config.EditorApp;
  eBrowser.Text:=Config.BrowserApp;
  cbDebug.Checked:=Config.ShowDebug;
  cbDebugDetails.Visible:=cbDebug.Checked;
  cbDebugDetails.ItemIndex:=Config.DebugLevel;
  cbCheckDefaultPorts.Checked:=Config.CheckDefaultPorts;
  cbTomcatVisible.Checked:=Config.TomcatVisible;

  cbASApache.Checked:=Config.ASApache;
  cbASMySQL.Checked:=Config.ASMySQL;
  cbASFileZilla.Checked:=Config.ASFileZilla;
  cbASMercury.Checked:=Config.ASMercury;
end;

end.
