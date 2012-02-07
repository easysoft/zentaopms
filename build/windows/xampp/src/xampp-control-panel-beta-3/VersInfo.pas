(******************************************************************************)
(* Delphi Free Stuff Include File.  This file is used for all my components   *)
(* to create some standard defines.  This will help reduce headaches when new *)
(* versions of Delphi and C++Builder are released, among other things.        *)
(******************************************************************************)
(* Brad Stowers: bstowers@pobox.com                                           *)
(* Delphi Free Stuff: http://www.delphifreestuff.com/                         *)
(* June 27, 2001                                                              *)
(******************************************************************************)
(* Usage:   Add the following line to the top of your unit file:              *)
(*   {$I DFS.INC}                                                             *)
(******************************************************************************)
(*                                                                            *)
(* Complete Boolean Evaluation compiler directive is turned off by including  *)
(*   this file.                                                               *)
(* The $ObjExportAll directive is turned on if compiling with C++Builder 3 or *)
(*   higher.  This is required for Delphi components built in Builder with    *)
(*   run-time packages.                                                       *)
(*                                                                            *)
(* Here is a brief explanation of what each of the defines mean:              *)
(* DELPHI_FREE_STUFF    : Always defined when DFS.INC is included             *)
(* DFS_WIN16            : Compilation target is 16-bit Windows                *)
(* DFS_WIN32            : Compilation target is 32-bit Windows                *)
(* DFS_USEDEFSHLOBJ     : The SHLOBJ.PAS version contains no none errors.     *)
(*                        Delphi 2.0x and C++Builder 1.0x shipped with a      *)
(*                        version of SHLOBJ.PAS that had many nasty errors.   *)
(*                        See my web site in the Hints section for how to fix *)
(* DFS_NO_COM_CLEANUP   : The version of the compiler being used does not     *)
(*                        require COM objects to be released; it is done      *)
(*                        automatically when they go "out of scope".          *)
(* DFS_NO_DSGNINTF      : Delphi 6 pretty much kills off the DsgnIntf unit    *)
(*                        for good. Split into a couple of new units.         *)
(* DFS_DESIGNERSELECTIONS: IDesignerSelections replaced TDesignerSelectionList*)
(* DFS_IPROPERTY        : IProperty introduced for design-time stuff.         *)
(* DFS_COMPILER_1       : Delphi 1.0 is the compiler.  Note that C++B 1.0     *)
(*                        does NOT cause this to be defined.  It is really    *)
(*                        the 2.0 compiler.                                   *)
(* DFS_COMPILER_1_UP    : Delphi 1.0x or higher, or C++B 1.0x or higher is    *)
(*                        the compiler.                                       *)
(* DFS_COMPILER_2       : Delphi 2.0x or C++B 1.0x is the compiler.           *)
(* DFS_COMPILER_2_UP    : Delphi 2.0x or higher, or C++B 1.0x or higher is    *)
(*                        the compiler.                                       *)
(* DFS_COMPILER_3       : Delphi 3.0x or C++B 3.0x is the compiler.           *)
(* DFS_COMPILER_3_UP    : Delphi 3.0x or higher, or C++B 3.0x or higher is    *)
(*                        the compiler.                                       *)
(* DFS_COMPILER_4       : Delphi 4.0x or C++B 4.0x is the compiler.           *)
(* DFS_COMPILER_4_UP    : Delphi 4.0x or higher, or C++B 4.0x or higher is    *)
(*                        the compiler.                                       *)
(* DFS_COMPILER_5       : Delphi 5.0x or C++B 5.0x is the compiler.           *)
(* DFS_COMPILER_5_UP    : Delphi 5.0x or higher, or C++B 5.0x or higher is    *)
(*                        the compiler.                                       *)
(* DFS_COMPILER_6       : Delphi 6.0x or C++B 6.0x is the compiler.           *)
(* DFS_COMPILER_6_UP    : Delphi 6.0x or higher, or C++B 6.0x or higher is    *)
(*                        the compiler.                                       *)
(* DFS_UNKNOWN_COMPILER : No sense could be made of the compiliation          *)
(*                        environment.                                        *)
(* DFS_CPPB             : Any version of C++B is being used.                  *)
(* DFS_CPPB_1           : C++B v1.0x is being used.                           *)
(* DFS_CPPB_3           : C++B v3.0x is being used.                           *)
(* DFS_CPPB_3_UP        : C++B v3.0x or higher is being used.                 *)
(* DFS_CPPB_4           : C++B v4.0x is being used.                           *)
(* DFS_CPPB_4_UP        : C++B v4.0x or higher is being used.                 *)
(* DFS_CPPB_5           : C++B v5.0x is being used.                           *)
(* DFS_CPPB_5_UP        : C++B v5.0x or higher is being used.                 *)
(* DFS_DELPHI           : Any version of Delphi is being used.                *)
(* DFS_DELPHI_1         : Delphi v1.0x is being used.                         *)
(* DFS_DELPHI_2         : Delphi v2.0x is being used.                         *)
(* DFS_DELPHI_2_UP      : Delphi v2.0x or higher is being used.               *)
(* DFS_DELPHI_3         : Delphi v3.0x is being used.                         *)
(* DFS_DELPHI_3_UP      : Delphi v3.0x or higher is being used.               *)
(* DFS_DELPHI_4         : Delphi v4.0x is being used.                         *)
(* DFS_DELPHI_4_UP      : Delphi v4.0x or higher is being used.               *)
(* DFS_DELPHI_5         : Delphi v5.0x is being used.                         *)
(* DFS_DELPHI_5_UP      : Delphi v5.0x or higher is being used.               *)
(* DFS_DELPHI_6         : Delphi v6.0x is being used.                         *)
(* DFS_DELPHI_6_UP      : Delphi v6.0x or higher is being used.               *)
(******************************************************************************)


{ All DFS components rely on complete boolean eval compiler directive set off. }
{$B-}

{$DEFINE DELPHI_FREE_STUFF}

{$IFDEF WIN32}
  {$DEFINE DFS_WIN32}
{$ELSE}
  {$DEFINE DFS_WIN16}
{$ENDIF}

{$IFDEF VER140}
  {$DEFINE DFS_COMPILER_6}
  {$DEFINE DFS_DELPHI}
  {$DEFINE DFS_DELPHI_6}
{$ENDIF}

{$IFDEF VER130}
  {$DEFINE DFS_COMPILER_5}
  {$IFDEF BCB}
    {$DEFINE DFS_CPPB}
    {$DEFINE DFS_CPPB_5}
  {$ELSE}
    {$DEFINE DFS_DELPHI}
    {$DEFINE DFS_DELPHI_5}
  {$ENDIF}
{$ENDIF}

{$IFDEF VER125}
  {$DEFINE DFS_COMPILER_4}
  {$DEFINE DFS_CPPB}
  {$DEFINE DFS_CPPB_4}
{$ENDIF}

{$IFDEF VER120}
  {$DEFINE DFS_COMPILER_4}
  {$DEFINE DFS_DELPHI}
  {$DEFINE DFS_DELPHI_4}
{$ENDIF}

{$IFDEF VER110}
  {$DEFINE DFS_COMPILER_3}
  {$DEFINE DFS_CPPB}
  {$DEFINE DFS_CPPB_3}
{$ENDIF}

{$IFDEF VER100}
  {$DEFINE DFS_COMPILER_3}
  {$DEFINE DFS_DELPHI}
  {$DEFINE DFS_DELPHI_3}
{$ENDIF}

{$IFDEF VER93}
  {$DEFINE DFS_COMPILER_2}  { C++B v1 compiler is really v2 }
  {$DEFINE DFS_CPPB}
  {$DEFINE DFS_CPPB_1}
  {.$DEFINE DFS_USEDEFSHLOBJ} { C++B 1 has the correct SHLOBJ.H, but
                                SHLOBJ.PAS has errors so this isn't defined }
{$ENDIF}

{$IFDEF VER90}
  {$DEFINE DFS_COMPILER_2}
  {$DEFINE DFS_DELPHI}
  {$DEFINE DFS_DELPHI_2}
{$ENDIF}

{$IFDEF VER80}
  {$DEFINE DFS_COMPILER_1}
  {$DEFINE DFS_DELPHI}
  {$DEFINE DFS_DELPHI_1}
{$ENDIF}

{$IFNDEF DFS_CPPB}
  {$IFNDEF DFS_DELPHI}
    { Don't know what the hell it is.  Could be new version, or could be old BP. }
    {$DEFINE DFS_UNKNOWN_COMPILER}
  {$ENDIF}
{$ENDIF}

{$IFDEF DFS_COMPILER_1}
  {$DEFINE DFS_COMPILER_1_UP}
{$ENDIF}

{$IFDEF DFS_COMPILER_2}
  {$DEFINE DFS_COMPILER_1_UP}
  {$DEFINE DFS_COMPILER_2_UP}
{$ENDIF}

{$IFDEF DFS_COMPILER_3}
  {$DEFINE DFS_COMPILER_1_UP}
  {$DEFINE DFS_COMPILER_2_UP}
  {$DEFINE DFS_COMPILER_3_UP}
{$ENDIF}

{$IFDEF DFS_COMPILER_4}
  {$DEFINE DFS_COMPILER_1_UP}
  {$DEFINE DFS_COMPILER_2_UP}
  {$DEFINE DFS_COMPILER_3_UP}
  {$DEFINE DFS_COMPILER_4_UP}
{$ENDIF}

{$IFDEF DFS_COMPILER_5}
  {$DEFINE DFS_COMPILER_1_UP}
  {$DEFINE DFS_COMPILER_2_UP}
  {$DEFINE DFS_COMPILER_3_UP}
  {$DEFINE DFS_COMPILER_4_UP}
  {$DEFINE DFS_COMPILER_5_UP}
{$ENDIF}

{$IFDEF DFS_COMPILER_6}
  {$DEFINE DFS_COMPILER_1_UP}
  {$DEFINE DFS_COMPILER_2_UP}
  {$DEFINE DFS_COMPILER_3_UP}
  {$DEFINE DFS_COMPILER_4_UP}
  {$DEFINE DFS_COMPILER_5_UP}
  {$DEFINE DFS_COMPILER_6_UP}
{$ENDIF}

{$IFDEF DFS_DELPHI_2}
  {$DEFINE DFS_DELPHI_2_UP}
{$ENDIF}

{$IFDEF DFS_DELPHI_3}
  {$DEFINE DFS_DELPHI_2_UP}
  {$DEFINE DFS_DELPHI_3_UP}
{$ENDIF}

{$IFDEF DFS_DELPHI_4}
  {$DEFINE DFS_DELPHI_2_UP}
  {$DEFINE DFS_DELPHI_3_UP}
  {$DEFINE DFS_DELPHI_4_UP}
{$ENDIF}

{$IFDEF DFS_DELPHI_5}
  {$DEFINE DFS_DELPHI_2_UP}
  {$DEFINE DFS_DELPHI_3_UP}
  {$DEFINE DFS_DELPHI_4_UP}
  {$DEFINE DFS_DELPHI_5_UP}
{$ENDIF}

{$IFDEF DFS_DELPHI_6}
  {$DEFINE DFS_DELPHI_2_UP}
  {$DEFINE DFS_DELPHI_3_UP}
  {$DEFINE DFS_DELPHI_4_UP}
  {$DEFINE DFS_DELPHI_5_UP}
  {$DEFINE DFS_DELPHI_6_UP}
{$ENDIF}

{$IFDEF DFS_CPPB_3}
  {$DEFINE DFS_CPPB_3_UP}
{$ENDIF}

{$IFDEF DFS_CPPB_4}
  {$DEFINE DFS_CPPB_3_UP}
  {$DEFINE DFS_CPPB_4_UP}
{$ENDIF}

{$IFDEF DFS_CPPB_5}
  {$DEFINE DFS_CPPB_3_UP}
  {$DEFINE DFS_CPPB_4_UP}
  {$DEFINE DFS_CPPB_5_UP}
{$ENDIF}

{$IFDEF DFS_COMPILER_3_UP}
  {$DEFINE DFS_NO_COM_CLEANUP}
  {$DEFINE DFS_USEDEFSHLOBJ} { Only D3+ and C++B 3+ have no errors in SHLOBJ }
{$ENDIF}

{$IFDEF DFS_CPPB_3_UP}
  // C++Builder requires this if you use Delphi components in run-time packages.
  {$ObjExportAll On}
{$ENDIF}

{$IFDEF DFS_COMPILER_6_UP}
  // Delphi 6 pretty much kills off the DsgnIntf unit for good.
  {$DEFINE DFS_NO_DSGNINTF}
  {$DEFINE DFS_DESIGNERSELECTIONS}
  {$DEFINE DFS_IPROPERTY}
{$ENDIF}

{------------------------------------------------------------------------------}
{ TdfsVersionInfoResource v2.36                                                }
{------------------------------------------------------------------------------}
{ A component to read version info resources.  It is intended for Delphi 3,    }
{ but should work with any file that contains a properly formatted resource.   }
{                                                                              }
{ Copyright 2000-2001, Brad Stowers.  All Rights Reserved.                     }
{                                                                              }
{ Copyright:                                                                   }
{ All Delphi Free Stuff (hereafter "DFS") source code is copyrighted by        }
{ Bradley D. Stowers (hereafter "author"), and shall remain the exclusive      }
{ property of the author.                                                      }
{                                                                              }
{ Distribution Rights:                                                         }
{ You are granted a non-exlusive, royalty-free right to produce and distribute }
{ compiled binary files (executables, DLLs, etc.) that are built with any of   }
{ the DFS source code unless specifically stated otherwise.                    }
{ You are further granted permission to redistribute any of the DFS source     }
{ code in source code form, provided that the original archive as found on the }
{ DFS web site (http://www.delphifreestuff.com) is distributed unmodified. For }
{ example, if you create a descendant of TDFSColorButton, you must include in  }
{ the distribution package the colorbtn.zip file in the exact form that you    }
{ downloaded it from http://www.delphifreestuff.com/mine/files/colorbtn.zip.   }
{                                                                              }
{ Restrictions:                                                                }
{ Without the express written consent of the author, you may not:              }
{   * Distribute modified versions of any DFS source code by itself. You must  }
{     include the original archive as you found it at the DFS site.            }
{   * Sell or lease any portion of DFS source code. You are, of course, free   }
{     to sell any of your own original code that works with, enhances, etc.    }
{     DFS source code.                                                         }
{   * Distribute DFS source code for profit.                                   }
{                                                                              }
{ Warranty:                                                                    }
{ There is absolutely no warranty of any kind whatsoever with any of the DFS   }
{ source code (hereafter "software"). The software is provided to you "AS-IS", }
{ and all risks and losses associated with it's use are assumed by you. In no  }
{ event shall the author of the softare, Bradley D. Stowers, be held           }
{ accountable for any damages or losses that may occur from use or misuse of   }
{ the software.                                                                }
{                                                                              }
{ Support:                                                                     }
{ Support is provided via the DFS Support Forum, which is a web-based message  }
{ system.  You can find it at http://www.delphifreestuff.com/discus/           }
{ All DFS source code is provided free of charge. As such, I can not guarantee }
{ any support whatsoever. While I do try to answer all questions that I        }
{ receive, and address all problems that are reported to me, you must          }
{ understand that I simply can not guarantee that this will always be so.      }
{                                                                              }
{ Clarifications:                                                              }
{ If you need any further information, please feel free to contact me directly.}
{ This agreement can be found online at my site in the "Miscellaneous" section.}
{------------------------------------------------------------------------------}
{ The lateset version of my components are always available on the web at:     }
{   http://www.delphifreestuff.com/                                            }
{ See VersInfo.txt for notes, known issues, and revision history.              }
{------------------------------------------------------------------------------}
{ Date last modified:  June 28, 2001                                           }
{------------------------------------------------------------------------------}

{$DEFINE DFS_VERSION_INFO_AS_CLASS}
{$M+}

unit VersInfo;

interface

uses

{$IFDEF DFS_VERSION_INFO_AS_CLASS}
  {$IFDEF DFS_WIN32}
  Windows,
  {$ELSE}
  WinTypes, WinProcs, Ver,
  {$ENDIF}
  Classes, SysUtils;  { I really hate Forms }
{$ELSE}
  {$IFDEF DFS_WIN32}
  Windows, ComCtrls,
  {$ELSE}
  WinTypes, WinProcs, Ver, Grids,
  {$ENDIF}
  Messages, SysUtils, Classes, Graphics, Forms, StdCtrls;
{$ENDIF}


const
  { This shuts up C++Builder 3 about the redefiniton being different. There
    seems to be no equivalent in C1.  Sorry. }
  {$IFDEF DFS_CPPB_3_UP}
  {$EXTERNALSYM DFS_COMPONENT_VERSION}
  {$ENDIF}
  DFS_COMPONENT_VERSION = 'TdfsVersionInfoResource v2.36';
  DEFAULT_LANG_ID       = $0409;
  DEFAULT_CHAR_SET_ID   = $04E4;
  DEFAULT_LANG_CHAR_SET = '040904E4';


{$IFDEF DFS_COMPILER_3_UP}
resourcestring
{$ELSE}
const
{$ENDIF}
  SFlagDebug = 'Debug';
  SFlagInfoInferred = 'Info Inferred';
  SFlagPatched = 'Patched';
  SFlagPreRelease = 'Pre-Release';
  SFlagPrivate = 'Private';
  SFlagSpecial = 'Special';
  SHeaderResource = 'Resource';
  SHeaderValue ='Value';

  { Predefined resource item captions. }  
  SResCapCompanyName = 'Company Name';
  SResCapFileDescription = 'File Description';
  SResCapFileVersion = 'File Version';
  SResCapInternalName = 'Internal Name';
  SResCapLegalCopyright = 'Legal Copyright';
  SResCapLegalTrademarks = 'Legal Trademarks';
  SResCapOriginalFilename = 'Original Filename';
  SResCapProductName = 'Product Name';
  SResCapProductVersion = 'Product Version';
  SResCapComments = 'Comments';
  SResCapBuildFlags = 'Build Flags';


{ set values to choose which resources are seen in the grid/listview }
type
  TPreDef = (pdCompanyName, pdFileDescription, pdFileVersion,
    pdInternalName, pdLegalCopyright, pdLegalTrademarks,
    pdOriginalFilename, pdProductName, pdProductVersion,
    pdComments, pdBuildFlags);
  TPreDefs = set of TPreDef;

{ but to index properties we need integers }
const
  IDX_COMPANYNAME           = ord (pdCompanyName);
  IDX_FILEDESCRIPTION       = ord (pdFileDescription);
  IDX_FILEVERSION           = ord (pdFileVersion);
  IDX_INTERNALNAME          = ord (pdInternalName);
  IDX_LEGALCOPYRIGHT        = ord (pdLegalCopyright);
  IDX_LEGALTRADEMARKS       = ord (pdLegalTrademarks);
  IDX_ORIGINALFILENAME      = ord (pdOriginalFilename);
  IDX_PRODUCTNAME           = ord (pdProductName);
  IDX_PRODUCTVERSION        = ord (pdProductVersion);
  IDX_COMMENTS              = ord (pdComments);
  IDX_BUILDFLAGS            = ord (pdBuildFlags);

const
  IDX_VER_MAJOR   = 0;
  IDX_VER_MINOR   = 1;
  IDX_VER_RELEASE = 2;
  IDX_VER_BUILD   = 3;

type
  {$IFNDEF DFS_WIN32}
  PVSFixedFileInfo = PVS_FixedFileInfo;
  DWORD = longint;
  UINT = word;
  {$ENDIF}


  TFixedFileInfoFlag = (ffDebug, ffInfoInferred, ffPatched, ffPreRelease,
      ffPrivateBuild, ffSpecialBuild);
  TFixedFileInfoFlags = set of TFixedFileInfoFlag;

  TVersionOperatingSystemFlag = (vosUnknown, vosDOS, vosOS2_16, vosOS2_32,
      vosNT, vosWindows16, vosPresentationManager16, vosPresentationManager32, vosWindows32);
  { This is supposed to be one of the first line, and one of the second line. }
  TVersionOperatingSystemFlags = set of TVersionOperatingSystemFlag;

  TVersionFileType = (vftUnknown, vftApplication, vftDLL, vftDriver, vftFont,
      vftVXD, vftStaticLib);

  TdfsVersionInfoResource = class; { forward declaration }

  TFixedFileVersionInfo = class
  private
    FParent: TdfsVersionInfoResource;
    FData: PVSFixedFileInfo;

    function GetSignature: DWORD;
    function GetStructureVersion: DWORD;
    function GetFileVersionMS: DWORD;
    function GetFileVersionLS: DWORD;
    function GetProductVersionMS: DWORD;
    function GetProductVersionLS: DWORD;
    function GetValidFlags: TFixedFileInfoFlags;
    function GetFlags: TFixedFileInfoFlags;
    function GetFileOperatingSystem: TVersionOperatingSystemFlags;
    function GetFileType: TVersionFileType;
    function GetFileSubType: DWORD;
    function GetCreationDate: TDateTime;
  public
    constructor Create(AParent: TdfsVersionInfoResource);

    property Parent: TdfsVersionInfoResource
      read FParent write FParent;
    property Data: PVSFixedFileInfo
      read FData write FData;

    property Signature: DWORD
      read GetSignature;
    property StructureVersion: DWORD
      read GetStructureVersion;
    property FileVersionMS: DWORD
      read GetFileVersionMS;
    property FileVersionLS: DWORD
      read GetFileVersionLS;
    property ProductVersionMS: DWORD
      read GetProductVersionMS;
    property ProductVersionLS: DWORD
      read GetProductVersionLS;
    property ValidFlags: TFixedFileInfoFlags
      read GetValidFlags;
    property Flags: TFixedFileInfoFlags
      read GetFlags;
    property FileOperatingSystem: TVersionOperatingSystemFlags
      read GetFileOperatingSystem;
    property FileType: TVersionFileType
      read GetFileType;
    property FileSubType: DWORD
      read GetFileSubType;
    property CreationDate: TDateTime
      read GetCreationDate;
  end;

  TVersionNumberInformation = class
  private
    FValid : boolean;
    FMostSignificant: DWORD;
    FLeastSignificant: DWORD;
    FVersionNumberString: string;

    function GetVersionNumber(Index: integer): word;
    function GetVersionNumberString: string;
  public
    constructor Create(MSVer, LSVer: DWORD);
    property Valid : boolean read FValid write FValid;
    property Major: word
      index IDX_VER_MAJOR
      read GetVersionNumber;
    property Minor: word
      index IDX_VER_MINOR
      read GetVersionNumber;
    property Release: word
      index IDX_VER_RELEASE
      read GetVersionNumber;
    property Build: word
      index IDX_VER_BUILD
      read GetVersionNumber;

    property AsString: string
      read GetVersionNumberString;
  end;

{$IFDEF DFS_DELPHI_1}
  TVersionFilename = string;
{$ELSE}
  TVersionFilename = type string;
{$ENDIF}

  TdfsVersionInfoResource = class{$IFNDEF DFS_VERSION_INFO_AS_CLASS}(TComponent){$ENDIF}
  private
    FVersionInfo: PChar;
    FVersionInfoSize: DWORD;
    FFilename: TVersionFilename;
    FTranslationIDs: TStringList;
    FTranslationIDIndex: integer;
    FFixedInfo: TFixedFileVersionInfo;
    FForceEXE: boolean;
    FFileVersion: TVersionNumberInformation;
    FProductVersion: TVersionNumberInformation;
{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
    FFileVersionLabel: TLabel;
    FCopyrightLabel: TLabel;
    {$IFDEF DFS_WIN32}
    FVersionListView: TListView;
    {$ELSE}
    FVersionGrid: TStringGrid;
    {$ENDIF}
    FDescriptionLabel: TLabel;
    FProductLabel: TLabel;
    FShowResource: TPreDefs;
{$ENDIF}
  protected
    procedure SetFilename(const Val: TVersionFilename);
    procedure SetTranslationIDIndex(Val: integer);
    function GetTranslationIDs: TStrings;
    procedure SetForceEXE(Val: boolean);
{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
    {$IFDEF DFS_WIN32}
    procedure SetVersionListView (Value: TListView);
    {$ELSE}
    procedure SetVersionGrid(Value: TStringGrid);
    {$ENDIF}
    procedure SetShowResource(Value: TPreDefs);
    procedure SetFileVersionLabel(Value: TLabel);
    procedure SetCopyrightLabel(Value: TLabel);
    procedure SetProductLabel(Value: TLabel);
    procedure SetDescriptionLabel(Value: TLabel);
    function GetVersion: string;
    procedure SetVersion(const Val: string);
{$ENDIF}

    function GetResourceFilename: string; virtual;
{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
    procedure PopulateControls; virtual;
    {$IFDEF DFS_WIN32}
    procedure BuildListView; virtual;
    {$ELSE}
    procedure BuildGrid; virtual;
    {$ENDIF}
    procedure Notification(AComponent: TComponent;
                     Operation: TOperation); override;
    procedure Loaded; override;
{$ENDIF}
    function BuildFlags : string; virtual;
    procedure ReadVersionInfoData; virtual;

    function GetVersionInfoString(Index: integer): string;
    function GetResourceStr(Index: string): string;
  public
{$IFDEF DFS_VERSION_INFO_AS_CLASS}
    constructor Create; virtual;
{$ELSE}
    constructor Create(AOwner: TComponent); override;
{$ENDIF}    
    destructor Destroy; override;

    property TranslationIDIndex: integer
      read FTranslationIDIndex
      write SetTranslationIDIndex;
    property TranslationIDs: TStrings
      read GetTranslationIDs;
    property FixedInfo: TFixedFileVersionInfo
      read FFixedInfo;
    property UserResource[Index: string]: string
      read GetResourceStr;

    property FileVersion: TVersionNumberInformation
      read FFileVersion;
    property ProductVersion: TVersionNumberInformation
      read FProductVersion;
  published
{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
    property Version: string
       read GetVersion
       write SetVersion
       stored FALSE;
{$ENDIF}
    property Filename: TVersionFilename
      read FFilename
      write SetFilename;
    property ForceEXE: boolean
      read FForceEXE
      write SetForceEXE
      default FALSE;

    property CompanyName: string
      index IDX_COMPANYNAME
      read GetVersionInfoString;
    property FileDescription: string
      index IDX_FILEDESCRIPTION
      read GetVersionInfoString;
    property InternalName: string
      index IDX_INTERNALNAME
      read GetVersionInfoString;
    property LegalCopyright: string
      index IDX_LEGALCOPYRIGHT
      read GetVersionInfoString;
    property LegalTrademarks: string
      index IDX_LEGALTRADEMARKS
      read GetVersionInfoString;
    property OriginalFilename: string
      index IDX_ORIGINALFILENAME
      read GetVersionInfoString;
    property ProductName: string
      index IDX_PRODUCTNAME
      read GetVersionInfoString;
    property Comments: string
      index IDX_COMMENTS
      read GetVersionInfoString;

{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
    property VersionLabel: TLabel
      read FFileVersionLabel
      write SetFileVersionLabel;
    {$IFDEF DFS_WIN32}
    property VersionListView: TListView
      read FVersionListView
      write SetVersionListView;
    {$ELSE}
    property VersionGrid: TStringGrid
      read FVersionGrid
      write SetVersionGrid;
    {$ENDIF}
    property DescriptionLabel: TLabel
      read FDescriptionLabel
      write SetDescriptionLabel;
    property CopyrightLabel: TLabel
      read FCopyrightLabel
      write SetCopyrightLabel;
    property ProductLabel: TLabel
      read FProductLabel
      write SetProductLabel;
    property ShowResource: TPreDefs
      read FShowResource
      write SetShowResource;
{$ENDIF}
  end;

implementation

const
  PREDEF_RESOURCES: array[IDX_COMPANYNAME..IDX_BUILDFLAGS] of string = (
     'CompanyName', 'FileDescription', 'FileVersion', 'InternalName',
     'LegalCopyright', 'LegalTrademarks', 'OriginalFilename', 'ProductName',
     'ProductVersion', 'Comments', 'BuildFlags'
    );

  PREDEF_CAPTIONS: array[IDX_COMPANYNAME..IDX_BUILDFLAGS] of string = (
     SResCapCompanyName, SResCapFileDescription, SResCapFileVersion,
     SResCapInternalName, SResCapLegalCopyright, SResCapLegalTrademarks,
     SResCapOriginalFilename, SResCapProductName, SResCapProductVersion,
     SResCapComments, SResCapBuildFlags
    );

{$IFDEF DFS_DELPHI_2}
  {$DEFINE ST2DT_UNDEF}
{$ENDIF}
{$IFDEF DFS_CPPB_1}
  {$DEFINE ST2DT_UNDEF}
{$ENDIF}

{$IFDEF ST2DT_UNDEF}
function SystemTimeToDateTime(const SystemTime: TSystemTime): TDateTime;
begin
  with SystemTime do
    Result := EncodeDate(wYear, wMonth, wDay) +
      EncodeTime(wHour, wMinute, wSecond, wMilliSeconds);
end;
{$ENDIF}

{$IFNDEF DFS_WIN32}
function IsLibrary: boolean;
begin
  Result := PrefixSeg = 0;
end;
{$ENDIF}


constructor TFixedFileVersionInfo.Create(AParent: TdfsVersionInfoResource);
begin
  inherited Create;
  FParent := AParent;
end;

function TFixedFileVersionInfo.GetSignature: DWORD;
begin
  if FData = nil then
    Result := 0
  else
    Result := FData^.dwSignature;
end;

function TFixedFileVersionInfo.GetStructureVersion: DWORD;
begin
  if FData = nil then
    Result := 0
  else
    Result := FData^.dwStrucVersion;
end;

function TFixedFileVersionInfo.GetFileVersionMS: DWORD;
begin
  if FData = nil then
    Result := 0
  else
    Result := FData^.dwFileVersionMS;
end;

function TFixedFileVersionInfo.GetFileVersionLS: DWORD;
begin
  if FData = nil then
    Result := 0
  else
    Result := FData^.dwFileVersionLS;
end;

function TFixedFileVersionInfo.GetProductVersionMS: DWORD;
begin
  if FData = nil then
    Result := 0
  else
    Result := FData^.dwProductVersionMS;
end;

function TFixedFileVersionInfo.GetProductVersionLS: DWORD;
begin
  if FData = nil then
    Result := 0
  else
    Result := FData^.dwProductVersionLS;
end;

function TFixedFileVersionInfo.GetValidFlags: TFixedFileInfoFlags;
begin
  Result := [];
  if FData <> nil then
  begin
    if (FData^.dwFileFlagsMask and VS_FF_DEBUG) <> 0 then
      Include(Result, ffDebug);
    if (FData^.dwFileFlagsMask and VS_FF_PRERELEASE) <> 0 then
      Include(Result, ffPreRelease);
    if (FData^.dwFileFlagsMask and VS_FF_PATCHED) <> 0 then
      Include(Result, ffPatched);
    if (FData^.dwFileFlagsMask and VS_FF_PRIVATEBUILD) <> 0 then
      Include(Result, ffPrivateBuild);
    if (FData^.dwFileFlagsMask and VS_FF_INFOINFERRED ) <> 0 then
      Include(Result, ffInfoInferred );
    if (FData^.dwFileFlagsMask and VS_FF_SPECIALBUILD ) <> 0 then
      Include(Result, ffSpecialBuild );
  end;
end;

function TFixedFileVersionInfo.GetFlags: TFixedFileInfoFlags;
begin
  Result := [];
  if FData <> nil then
  begin
    if (FData^.dwFileFlags and VS_FF_DEBUG) <> 0 then
      Include(Result, ffDebug);
    if (FData^.dwFileFlags and VS_FF_PRERELEASE) <> 0 then
      Include(Result, ffPreRelease);
    if (FData^.dwFileFlags and VS_FF_PATCHED) <> 0 then
      Include(Result, ffPatched);
    if (FData^.dwFileFlags and VS_FF_PRIVATEBUILD) <> 0 then
      Include(Result, ffPrivateBuild);
    if (FData^.dwFileFlags and VS_FF_INFOINFERRED ) <> 0 then
      Include(Result, ffInfoInferred );
    if (FData^.dwFileFlags and VS_FF_SPECIALBUILD ) <> 0 then
      Include(Result, ffSpecialBuild );
  end;
end;

function TFixedFileVersionInfo.GetFileOperatingSystem: TVersionOperatingSystemFlags;
{$IFNDEF DFS_WIN32}
var
  FileOS: word;
{$ENDIF}
begin
  Result := [];
  if FData <> nil then
  begin
    case HiWord(FData^.dwFileOS) of
      VOS_DOS shr 16:   Include(Result, vosDOS);
      VOS_OS216 shr 16: Include(Result, vosOS2_16);
      VOS_OS232 shr 16: Include(Result, vosOS2_32);
      VOS_NT shr 16:    Include(Result, vosNT);
    else
      Include(Result, vosUnknown);
    end;

{$IFDEF DFS_WIN32}
    case LoWord(FData^.dwFileOS) of
      LoWord(VOS__WINDOWS16): Include(Result, vosWindows16);
      LoWord(VOS__PM16):      Include(Result, vosPresentationManager16);
      LoWord(VOS__PM32):      Include(Result, vosPresentationManager32);
      LoWord(VOS__WINDOWS32): Include(Result, vosWindows32);
    else
      Include(Result, vosUnknown);
    end;
{$ELSE}
    FileOS := LoWord(FData^.dwFileOS);
    if      FileOS = LoWord(VOS__WINDOWS16) then Include(Result, vosWindows16)
    else if FileOS = LoWord(VOS__PM16) then      Include(Result, vosPresentationManager16)
    else if FileOS = LoWord(VOS__PM32) then      Include(Result, vosPresentationManager32)
    else if FileOS = LoWord(VOS__WINDOWS32) then Include(Result, vosWindows32)
    else                                         Include(Result, vosUnknown);
{$ENDIF}
  end;
end;

function TFixedFileVersionInfo.GetFileType: TVersionFileType;
begin
  Result := vftUnknown;
  if FData <> nil then
  begin
    case FData^.dwFileType of
      VFT_APP:        Result := vftApplication;
      VFT_DLL:        Result := vftDLL;
      VFT_DRV:        Result := vftDriver;
      VFT_FONT:       Result := vftFont;
      VFT_VXD:        Result := vftVXD;
      VFT_STATIC_LIB: Result := vftStaticLib;
    end;
  end;
end;

function TFixedFileVersionInfo.GetFileSubType: DWORD;
begin
  if FData = nil then
    Result := 0
  else begin
    Result := FData^.dwFileSubtype;
  end;
end;

function TFixedFileVersionInfo.GetCreationDate: TDateTime;
{$IFDEF DFS_WIN32}
var
  SysTime: TSystemTime;
  FileTime: TFileTime;
begin
  if FData = nil then
    Result := 0
  else begin
    FileTime.dwLowDateTime := FData^.dwFileDateLS;
    FileTime.dwHighDateTime := FData^.dwFileDateMS;
    if FileTimeToSystemTime(FileTime, SysTime) then
    begin
      Result := SystemTimeToDateTime(SysTime);
    end else
      Result := 0;
  end;
{$ELSE}
var
  SR: TSearchRec;
begin
  { Fake it until I can figure out how to convert dwFileDateMS and LS }
  Result := 0;
  if assigned(FParent) then
  begin
    if FindFirst(FParent.GetResourceFilename, faAnyFile, SR) = 0 then
    begin
      Result := FileDateToDateTime(SR.Time);
      FindClose(SR);
    end;
  end;
(*
var
  BigNum: comp;
begin
  if FData = nil then
    Result := 0
  else begin

    BigNum := (FData^.dwFileDateMS * MaxLongInt) + FData^.dwFileDateLS;
    BigNum := BigNum / 10000000;
  { LS and MS is the number of 100 nanosecond intervals since 1/1/1601 }
  { 10,000,000s of a second }
    Result := EncodeDate(1601, 1, 1);
    Result := BigNum.....
  end;
*)

{$ENDIF}
end;



constructor TVersionNumberInformation.Create(MSVer, LSVer: DWORD);
begin
  inherited Create;

  FValid := false;
  FMostSignificant := MSVer;
  FLeastSignificant := LSVer;
end;

function TVersionNumberInformation.GetVersionNumber(Index: integer): word;
begin
  Result := 0;
  if FValid then
    case Index of
      IDX_VER_MAJOR:   Result := HiWord(FMostSignificant);
      IDX_VER_MINOR:   Result := LoWord(FMostSignificant);
      IDX_VER_RELEASE: Result := HiWord(FLeastSignificant);
      IDX_VER_BUILD:   Result := LoWord(FLeastSignificant)
    end
end;

function TVersionNumberInformation.GetVersionNumberString: string;
begin
  if FValid then
  begin
    if FVersionNumberString = '' then
      Result := Format('%d.%d.%d.%d', [Major, Minor, Release, Build])
    else
      Result := FVersionNumberString;
  end
  else
    Result := ''
end;



{$IFDEF DFS_VERSION_INFO_AS_CLASS}
constructor TdfsVersionInfoResource.Create;
begin
  inherited Create;

  FVersionInfo := nil;
  FVersionInfoSize := 0;
  FFilename := '';
  FTranslationIDIndex := 0;
  FForceEXE := FALSE;
  FTranslationIDs := TStringList.Create;
  FFileVersion := TVersionNumberInformation.Create(0, 0);
  FProductVersion := TVersionNumberInformation.Create(0, 0);
  FFixedInfo := TFixedFileVersionInfo.Create(Self);
end;
{$ELSE}
constructor TdfsVersionInfoResource.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);

  FVersionInfo := nil;
  FVersionInfoSize := 0;
  FFilename := '';
  FTranslationIDIndex := 0;
  FForceEXE := FALSE;
  FTranslationIDs := TStringList.Create;
  FFileVersion := TVersionNumberInformation.Create(0, 0);
  FProductVersion := TVersionNumberInformation.Create(0, 0);
  FFixedInfo := TFixedFileVersionInfo.Create(Self);
  FShowResource := [Low(TPreDef)..High(TPreDef)]
end;
{$ENDIF}

destructor TdfsVersionInfoResource.Destroy;
begin
  FFileVersion.Free;
  FProductVersion.Free;
  FFixedInfo.Free;
  FTranslationIDs.Free;
  if FVersionInfo <> nil then
    FreeMem(FVersionInfo, FVersionInfoSize);

  inherited Destroy;
end;

{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
procedure TdfsVersionInfoResource.Loaded;
begin
  inherited Loaded;

  ReadVersionInfoData;
(*
{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
  PopulateControls;
{$ENDIF}
*)
end;
{$ENDIF}

procedure TdfsVersionInfoResource.SetFilename(const Val: TVersionFilename);
begin
  FFilename := Val;
  ReadVersionInfoData;
end;

procedure TdfsVersionInfoResource.ReadVersionInfoData;
const
  TRANSLATION_INFO = '\VarFileInfo\Translation';
type
  TTranslationPair = packed record
    Lang,
    CharSet: word;
  end;
  PTranslationIDList = ^TTranslationIDList;
  TTranslationIDList = array[0..MAXINT div SizeOf(TTranslationPair)-1] of TTranslationPair;
var
  QueryLen: UINT;
  IDsLen: UINT;
  Dummy: DWORD;
  IDs: PTranslationIDList;
  IDCount: integer;
  FixedInfoData: PVSFixedFileInfo;
  TempFilename: array[0..255] of char;
begin
  FTranslationIDs.Clear;
  FFixedInfo.Data := nil;
  if FVersionInfo <> nil then
    FreeMem(FVersionInfo, FVersionInfoSize);
  StrPCopy(TempFileName, GetResourceFilename);

  { Denis Kopprasch: added a try-Except because GetFileVersionInfoSize can fail
    with an invalid pointer or something like that! }
  try
    FVersionInfoSize := GetFileVersionInfoSize(TempFileName, Dummy);
  except
    FVersionInfoSize := 0;
  end;

  if FVersionInfoSize = 0 then
  begin
    FVersionInfo := nil;
    FFileVersion.Valid := false;
    FProductVersion.Valid := false;
  end else begin
    GetMem(FVersionInfo, FVersionInfoSize);
    GetFileVersionInfo(TempFileName, Dummy, FVersionInfoSize, FVersionInfo);

    VerQueryValue(FVersionInfo, '\', pointer(FixedInfoData), QueryLen);
    FFixedInfo.Data := FixedInfoData;
    if VerQueryValue(FVersionInfo, TRANSLATION_INFO, Pointer(IDs), IDsLen) then
    begin
      { Denis Kopprasch: if IDCount = 0, the for .. to ...-Statement is executed
        several times (maybe infinite until error) if range checking off }
      IDCount := IDsLen div SizeOf(TTranslationPair);
      if (IDCount > 0) then
      begin
        for Dummy := 0 to IDCount-1 do
        begin
  {!!! Potential problem.  Some of MS's stuff does this, some does not.  Need to
       figure a way to make it work with both.}
(*          if IDs^[Dummy].Lang = 0 then
            IDs^[Dummy].Lang := DEFAULT_LANG_ID; { Some of Microsoft's stuff does this }
          if IDs^[Dummy].CharSet = 0 then
            IDs^[Dummy].CharSet := DEFAULT_CHAR_SET_ID;*)
          FTranslationIDs.Add(Format('%.4x%.4x', [IDs^[Dummy].Lang, IDs^[Dummy].CharSet]));
        end;
      end
      else
      if (IDCount = 0) and (IDsLen > 0) then
      begin
        { There was translation info, but there was not a full set.  What's
          there is usually a char set, so we have to swap things around. }
        FTranslationIDs.Add(Format('%.4x%.4x', [DEFAULT_LANG_ID, IDs^[Dummy].Lang]));
      end;
    end;

    if FTranslationIDIndex >= FTranslationIDs.Count then
      FTranslationIDIndex := 0;

    FFileVersion.Valid := true;
    FFileVersion.FMostSignificant := FFixedInfo.GetFileVersionMS;
    FFileVersion.FLeastSignificant := FFixedInfo.GetFileVersionLS;
    FFileVersion.FVersionNumberString := GetVersionInfoString(IDX_FILEVERSION);
    FProductVersion.Valid := true;
    FProductVersion.FMostSignificant := FFixedInfo.GetProductVersionMS;
    FProductVersion.FLeastSignificant := FFixedInfo.GetProductVersionLS;
    FProductVersion.FVersionNumberString := GetVersionInfoString(
      IDX_PRODUCTVERSION);
  end;
{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
  PopulateControls;
{$ENDIF}
end;

{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
procedure TdfsVersionInfoResource.PopulateControls;
begin
  if [csDesigning, csLoading] * ComponentState <> [] then exit;
  
  if assigned(FFileVersionLabel) then
    FFileVersionLabel.Caption := FileVersion.AsString;
  if assigned(FCopyrightLabel) then
    FCopyrightLabel.Caption := LegalCopyright;
  if assigned(FProductLabel) then
    FProductLabel.Caption := ProductName;
  if assigned(FDescriptionLabel) then
    FDescriptionLabel.Caption := FileDescription;
{$IFDEF DFS_WIN32}
  if Assigned (FVersionListView) then
    BuildListView;
{$ELSE}
  if assigned(FVersionGrid) then
    BuildGrid;
{$ENDIF}
end;
{$ENDIF}

function TdfsVersionInfoResource.GetResourceFilename: string;
{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
var
  TempFilename: array[0..255] of char;
{$ENDIF}
begin
  {$IFNDEF DFS_VERSION_INFO_AS_CLASS}
  if FFilename = '' then
  begin
    if IsLibrary and (not FForceEXE) then
    begin
      GetModuleFileName(HInstance, TempFileName, SizeOf(TempFileName)-1);
      Result := StrPas(TempFileName);
    end else
      Result := Application.EXEName;
  end else
  {$ENDIF}
    Result := FFilename;
end;


function TdfsVersionInfoResource.GetVersionInfoString(Index: integer): string;
begin
  if (Index >= Low(PREDEF_RESOURCES)) and (Index <= High(PREDEF_RESOURCES)) then
    Result := GetResourceStr(PREDEF_RESOURCES[Index])
  else
    Result := ''
end;


function TdfsVersionInfoResource.GetResourceStr(Index: string): string;
var
  ResStr: PChar;
  StrLen: UINT;
  SubBlock: array[0..255] of char;
  LangCharSet: string;
begin
  if FTranslationIDIndex < FTranslationIDs.Count then
    LangCharSet := FTranslationIDs[FTranslationIDIndex]
  else
    LangCharSet := DEFAULT_LANG_CHAR_SET;
  StrPCopy(SubBlock, '\StringFileInfo\' + LangCharSet + '\' + Index);
  if (FVersionInfo <> nil) and
     VerQueryValue(FVersionInfo, SubBlock, Pointer(ResStr), StrLen)
  then
    Result := StrPas(ResStr)
  else
    Result := '';
end;


procedure TdfsVersionInfoResource.SetTranslationIDIndex(Val: integer);
begin
  if (Val > 0) and (Val < FTranslationIDs.Count) then
    FTranslationIDIndex := Val;
end;


function TdfsVersionInfoResource.GetTranslationIDs: TStrings;
begin
  Result := FTranslationIDs;
end;


procedure TdfsVersionInfoResource.SetForceEXE(Val: boolean);
begin
  if FForceEXE <> Val then
  begin
    FForceEXE := Val;
    ReadVersionInfoData;
  end;
end;


{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
procedure TdfsVersionInfoResource.SetFileVersionLabel(Value: TLabel);
begin
  FFileVersionLabel := Value;
  if assigned(FFileVersionLabel) then
  begin
{$IFDEF DFS_WIN32}
    FFileVersionLabel.FreeNotification(Self);
{$ENDIF}
    FShowResource := FShowResource - [pdFileVersion];
    PopulateControls;
  end;
end;

procedure TdfsVersionInfoResource.SetCopyrightLabel(Value: TLabel);
begin
  FCopyrightLabel := Value;
  if assigned(FCopyrightLabel) then
  begin
{$IFDEF DFS_WIN32}
    FCopyrightLabel.FreeNotification(Self);
{$ENDIF}
    FShowResource := FShowResource - [pdLegalCopyright];
    PopulateControls;
  end;
end;

procedure TdfsVersionInfoResource.SetProductLabel(Value: TLabel);
begin
  FProductLabel := Value;
  if assigned(FProductLabel) then
  begin
{$IFDEF DFS_WIN32}
    FProductLabel.FreeNotification(Self);
{$ENDIF}
    FShowResource := FShowResource - [pdProductName];
    PopulateControls;
  end;
end;

procedure TdfsVersionInfoResource.SetDescriptionLabel(Value: TLabel);
begin
  FDescriptionLabel := Value;
  if assigned(FDescriptionLabel) then
  begin
{$IFDEF DFS_WIN32}
    FDescriptionLabel.FreeNotification(Self);
{$ENDIF}
    FShowResource := FShowResource - [pdFileDescription];
    PopulateControls;
  end;
end;

procedure TdfsVersionInfoResource.SetShowResource(Value: TPreDefs);
begin
  if Value <> FShowResource then
  begin
    FShowResource:= Value;
    PopulateControls;
  end
end;

{$IFDEF DFS_WIN32}
procedure TdfsVersionInfoResource.SetVersionListView (Value: TListView);
begin
  FVersionListView := Value;
  if Assigned(FVersionListView) then
  begin
    FVersionListView.FreeNotification(Self);
    PopulateControls;
  end;
end;

{$ELSE}

procedure TdfsVersionInfoResource.SetVersionGrid(Value: TStringGrid);
begin
  FVersionGrid := Value;
  if Assigned(FVersionGrid) then
  begin
{$IFDEF DFS_WIN32}
    FVersionGrid.FreeNotification(Self);
{$ENDIF}
    PopulateControls;
  end;
end;
{$ENDIF}


procedure TdfsVersionInfoResource.Notification(AComponent: TComponent;
   Operation: TOperation);
begin
  inherited Notification(AComponent, Operation);
  if Operation = opRemove then
  begin
    if (AComponent = FFileVersionLabel) then
      FFileVersionLabel := nil
    else if (AComponent = FCopyrightLabel) then
      FCopyRightLabel := nil
    else if (AComponent = FProductLabel) then
      FProductLabel := nil
    else if (AComponent = FDescriptionLabel) then
      FDescriptionLabel := nil
{$IFDEF DFS_WIN32}
    else if (AComponent = FVersionListView) then
      FVersionListView := nil;
{$ELSE}
    else if (AComponent = FVersionGrid) then
      FVersionGrid := nil;
{$ENDIF}
  end;
end;
{$ENDIF}

function TdfsVersionInfoResource.BuildFlags : string;
const
  FLAG_STRING: array[TFixedFileInfoFlag] of string = (
      SFlagDebug, SFlagInfoInferred, SFlagPatched, SFlagPreRelease,
      SFlagPrivate, SFlagSpecial
    );
var
  AFlag: TFixedFileInfoFlag;
begin
  Result := '';
  for AFlag := Low(TFixedFileInfoFlag) to High(TFixedFileInfoFlag) do
    if AFlag in FixedInfo.Flags then
      Result := Result + FLAG_STRING[AFlag] + ', ';

  if Length(Result) > 0 then
    Result := Copy(Result, 1, Length(Result)-2);
end;

{$IFNDEF DFS_VERSION_INFO_AS_CLASS}
{$IFDEF DFS_WIN32}
procedure TdfsVersionInfoResource.BuildListView;

  procedure Add_Item (StrId: integer; const Str: string);
  var
    NewItem : TListItem;
  begin
    if (Str <> '') and (TPreDef(StrId) in FShowResource) then
    begin
      NewItem := VersionListView.Items.Add;
      NewItem.Caption := PREDEF_CAPTIONS[StrId];
      NewItem.SubItems.Add (Str)
    end
  end;

  procedure Add_Column (const Str: string);
  var
    NewColumn : TListColumn;
  begin
    NewColumn := VersionListView.Columns.Add;
    NewColumn.Caption := Str;
    NewColumn.Width := -2; { nifty! }
  end;

begin
  if Assigned (VersionListView) then
  with VersionListView do
  begin
    Columns.Clear;
    Items.Clear;

{ only the minimum parameters in the listview are forced: }
    ViewStyle := vsReport;
    ReadOnly := true;
    ColumnClick := false;
    Add_Column (SHeaderResource);
    Add_Column (SHeaderValue);

    Add_Item (IDX_PRODUCTNAME, ProductName);
    Add_Item (IDX_PRODUCTVERSION, ProductVersion.AsString);
    Add_Item (IDX_COMPANYNAME, CompanyName);
    Add_Item (IDX_LEGALCOPYRIGHT, LegalCopyright);
    Add_Item (IDX_LEGALTRADEMARKS, LegalTrademarks);
    Add_Item (IDX_FILEDESCRIPTION, FileDescription);
    Add_Item (IDX_FILEVERSION, FileVersion.AsString);
    Add_Item (IDX_INTERNALNAME, InternalName);
    Add_Item (IDX_ORIGINALFILENAME, OriginalFilename);
    Add_Item (IDX_BUILDFLAGS, BuildFlags);
    Add_Item (IDX_COMMENTS, Comments);
  end
end;

{$ELSE}

procedure TdfsVersionInfoResource.BuildGrid;
const
  FLAG_STRING: array[TFixedFileInfoFlag] of string = (
      SFlagDebug, SFlagInfoInferred, SFlagPatched, SFlagPreRelease,
      SFlagPrivate, SFlagSpecial
    );

  procedure AddGridRow(var RowNum: integer; StrID: integer; Str: string);
  var
    i: integer;
  begin
    if (Str <> '') and (TPreDef(StrId) in FShowResource) then
    begin
      with VersionGrid do
      begin
        Cells[0,RowNum] := PREDEF_CAPTIONS[StrID];
        Cells[1,RowNum] := Str;
        i := Canvas.TextWidth(Str);
        if i > ColWidths[1] then
          ColWidths[1] := i + 4;
        inc(RowNum);
      end;
    end;
  end;

var
  i, FRow: Integer;

begin
  With VersionGrid do
  begin
    { Set Defaults }
    FixedCols := 0;
    FixedRows := 0;
    ColCount := 2;
    RowCount := 10;
    Canvas.Font.Assign(Font);
    DefaultRowHeight := Canvas.TextHeight(PREDEF_CAPTIONS[IDX_ORIGINALFILENAME]) + 2;
    ColWidths[0] := Canvas.TextWidth(PREDEF_CAPTIONS[IDX_LEGALTRADEMARKS]) + 4;
    ColWidths[1] := ClientWidth - COlWidths[0] - 1;
    { Clear }
    for i:= 0 to (ColCount-1) do
      Cols[i].Clear;

    FRow := 0;
    AddGridRow(FRow, IDX_PRODUCTNAME, ProductName);
    AddGridRow(FRow, IDX_PRODUCTVERSION, ProductVersion.AsString);
    AddGridRow(FRow, IDX_COMPANYNAME, CompanyName);
    AddGridRow(FRow, IDX_LEGALCOPYRIGHT, LegalCopyright);
    AddGridRow(FRow, IDX_LEGALTRADEMARKS, LegalTrademarks);
    AddGridRow(FRow, IDX_FILEDESCRIPTION, FileDescription);
    AddGridRow(FRow, IDX_FILEVERSION, FileVersion.AsString);
    AddGridRow(FRow, IDX_INTERNALNAME, InternalName);
    AddGridRow(FRow, IDX_ORIGINALFILENAME, OriginalFilename);
    AddGridRow(FRow, IDX_BUILDFLAGS, BuildFlags);
    AddGridRow(FRow, IDX_COMMENTS, Comments);
    RowCount := FRow;
  end;
end;
{$ENDIF}

function TdfsVersionInfoResource.GetVersion: string;
begin
  Result := DFS_COMPONENT_VERSION;
end;

procedure TdfsVersionInfoResource.SetVersion(const Val: string);
begin
  { empty write method, just needed to get it to show up in Object Inspector }
end;
{$ENDIF}


end.


