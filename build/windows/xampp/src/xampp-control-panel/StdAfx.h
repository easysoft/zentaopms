// stdafx.h : include file for standard system include files,
//  or project specific include files that are used frequently, but
//      are changed infrequently
//

#if !defined(AFX_STDAFX_H__202939CE_5B83_453D_A1C9_63457E43CBB0__INCLUDED_)
#define AFX_STDAFX_H__202939CE_5B83_453D_A1C9_63457E43CBB0__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

//#define STRICT
#define WIN32_LEAN_AND_MEAN
#include <windows.h>

#ifdef UNICODE
#define stristr stristrW
#else
#define stristr stristrA
#endif

char*  __fastcall stristrA(const char* psz1, const char* psz2);
WCHAR* __fastcall stristrW(const WCHAR* pszMain, const WCHAR* pszSub);

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ will insert additional declarations immediately before the previous line.

#endif // !defined(AFX_STDAFX_H__202939CE_5B83_453D_A1C9_63457E43CBB0__INCLUDED_)
