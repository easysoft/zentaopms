unit uLanguage;

interface

uses
  GnuGettext, Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, Buttons, jpeg, ExtCtrls, Registry;

type
  TfLanguage = class(TForm)
    GroupBox1: TGroupBox;
    ImgEn: TImage;
    ImgDe: TImage;
    rbEn: TRadioButton;
    rbDe: TRadioButton;
    bOkay: TBitBtn;
    bAbort: TBitBtn;
    procedure bOkClick(Sender: TObject);
    procedure FormKeyPress(Sender: TObject; var Key: Char);
    procedure RadioGroup1Click(Sender: TObject);
    procedure FormCreate(Sender: TObject);
    procedure bAbortClick(Sender: TObject);
    procedure FormShow(Sender: TObject);
  private
    OldLang: string;
  public
  end;

var
  fLanguage: TfLanguage;

implementation

uses uTools, uMain;

{$R *.dfm}


procedure TfLanguage.bAbortClick(Sender: TObject);
begin
  ModalResult:=mrAbort;
end;

procedure TfLanguage.bOkClick(Sender: TObject);
begin
  if rbEn.Checked then Config.Language:='en'
  else if rbde.Checked then Config.Language:='de'
  else Config.Language:='en';
  ModalResult:=mrOk;

  if (OldLang<>'') and (OldLang<>Config.Language) then
    MessageDlg('Restart application to apply changes!',mtInformation,[mbOk],0);
end;


procedure TfLanguage.FormCreate(Sender: TObject);
begin
  TranslateComponent(self);
end;

procedure TfLanguage.FormKeyPress(Sender: TObject; var Key: Char);
begin
  if key=#27 then begin
    key:=#0;
    ModalResult:=mrAbort;
    Close;
  end;
end;

procedure TfLanguage.FormShow(Sender: TObject);
begin
  OldLang:=Config.Language;
  if Config.Language='en' then rbEn.Checked:=true
  else if Config.Language='de' then rbDe.Checked:=true
  else rbEn.Checked:=true;
end;


procedure TfLanguage.RadioGroup1Click(Sender: TObject);
begin
  if (Sender=rbEn) or (Sender=ImgEn) then rbEn.Checked:=true;
  if (Sender=rbDe) or (Sender=ImgDe) then rbDe.Checked:=true;
end;


end.
