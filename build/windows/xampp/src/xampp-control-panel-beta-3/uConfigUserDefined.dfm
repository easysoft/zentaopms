object fConfigUserDefined: TfConfigUserDefined
  Left = 487
  Top = 248
  Caption = 'User-defined log/config-files'
  ClientHeight = 689
  ClientWidth = 557
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'Tahoma'
  Font.Style = []
  OldCreateOrder = False
  Position = poScreenCenter
  OnCreate = FormCreate
  OnKeyPress = FormKeyPress
  OnShow = FormShow
  DesignSize = (
    557
    689)
  PixelsPerInch = 96
  TextHeight = 13
  object lHeader1: TLabel
    Left = 8
    Top = 8
    Width = 310
    Height = 13
    Caption = 
      'Enter user defined files. Files must be relative to xampp-basedi' +
      'r!'
  end
  object lHeader2: TLabel
    Left = 8
    Top = 27
    Width = 222
    Height = 13
    Caption = 'Example: "apache\conf\extra\httpd-info.conf"'
  end
  object lConfig: TLabel
    Left = 17
    Top = 60
    Width = 35
    Height = 13
    Caption = 'Config'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'Tahoma'
    Font.Style = [fsBold]
    ParentFont = False
  end
  object lLogs: TLabel
    Left = 280
    Top = 60
    Width = 26
    Height = 13
    Caption = 'Logs'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'Tahoma'
    Font.Style = [fsBold]
    ParentFont = False
  end
  object bSave: TBitBtn
    Left = 474
    Top = 656
    Width = 75
    Height = 25
    Anchors = [akRight, akBottom]
    Caption = 'Save'
    DoubleBuffered = True
    Glyph.Data = {
      DE010000424DDE01000000000000760000002800000024000000120000000100
      0400000000006801000000000000000000001000000000000000000000000000
      80000080000000808000800000008000800080800000C0C0C000808080000000
      FF0000FF000000FFFF00FF000000FF00FF00FFFF0000FFFFFF00333333333333
      3333333333333333333333330000333333333333333333333333F33333333333
      00003333344333333333333333388F3333333333000033334224333333333333
      338338F3333333330000333422224333333333333833338F3333333300003342
      222224333333333383333338F3333333000034222A22224333333338F338F333
      8F33333300003222A3A2224333333338F3838F338F33333300003A2A333A2224
      33333338F83338F338F33333000033A33333A222433333338333338F338F3333
      0000333333333A222433333333333338F338F33300003333333333A222433333
      333333338F338F33000033333333333A222433333333333338F338F300003333
      33333333A222433333333333338F338F00003333333333333A22433333333333
      3338F38F000033333333333333A223333333333333338F830000333333333333
      333A333333333333333338330000333333333333333333333333333333333333
      0000}
    ModalResult = 1
    NumGlyphs = 2
    ParentDoubleBuffered = False
    TabOrder = 0
    OnClick = bSaveClick
    ExplicitTop = 544
  end
  object bAbort: TBitBtn
    Left = 393
    Top = 656
    Width = 75
    Height = 25
    Anchors = [akRight, akBottom]
    Caption = 'Abort'
    DoubleBuffered = True
    Kind = bkAbort
    ParentDoubleBuffered = False
    TabOrder = 1
    OnClick = bAbortClick
    ExplicitTop = 544
  end
  object gbApache: TGroupBox
    Left = 8
    Top = 79
    Width = 541
    Height = 110
    Caption = 'Apache'
    TabOrder = 2
    object mConfigApache: TMemo
      Left = 9
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 0
    end
    object mLogsApache: TMemo
      Left = 272
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 1
    end
  end
  object gbMySQL: TGroupBox
    Left = 8
    Top = 195
    Width = 541
    Height = 110
    Caption = 'MySQL'
    TabOrder = 3
    object mConfigMySQL: TMemo
      Left = 9
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 0
    end
    object mLogsMySQL: TMemo
      Left = 272
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 1
    end
  end
  object gbFileZilla: TGroupBox
    Left = 8
    Top = 311
    Width = 541
    Height = 110
    Caption = 'FileZilla'
    TabOrder = 4
    object mConfigFilezilla: TMemo
      Left = 9
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 0
    end
    object mLogsFileZilla: TMemo
      Left = 272
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 1
    end
  end
  object gbMercury: TGroupBox
    Left = 8
    Top = 427
    Width = 541
    Height = 110
    Caption = 'Mercury'
    TabOrder = 5
    object mConfigMercury: TMemo
      Left = 9
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 0
    end
    object mLogsMercury: TMemo
      Left = 272
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 1
    end
  end
  object gbTomcat: TGroupBox
    Left = 8
    Top = 543
    Width = 541
    Height = 110
    Caption = 'Mercury'
    TabOrder = 6
    object mConfigTomcat: TMemo
      Left = 9
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 0
    end
    object mLogsTomcat: TMemo
      Left = 272
      Top = 17
      Width = 261
      Height = 85
      ScrollBars = ssBoth
      TabOrder = 1
    end
  end
end
