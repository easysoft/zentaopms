# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Interactive
Always reply in Chinese.

## Project Overview

ZenTao is a comprehensive, open-source project management software written in PHP that covers the main PM process from product and project management to quality management, documentation management, organization management, and office management. It follows a modular MVC architecture pattern.

## Architecture

### Core Structure
- **framework/**: Core framework classes (control, model, router, helper)
- **module/**: Modular architecture with each module containing:
  - `control.php` - Controller logic
  - `model.php` - Data layer and business logic
  - `zen.php` - New architecture layer (when present)
  - `tao.php` - Extended business logic layer (when present)
  - `config/` - Module-specific configuration
  - `lang/` - Internationalization files
  - `view/` - Traditional view templates
  - `ui/` - Modern UI components
  - `css/` and `js/` - Frontend assets
- **lib/**: Third-party libraries and utility classes
- **config/**: Global configuration files
- **www/**: Web entry point and public assets
- **db/**: Database schemas and migration scripts
- **extension/**: Extension system for customization

### Database
- Uses MySQL/MariaDB with custom DAO layer in `lib/dao/`
- Database schemas in `db/` directory with versioned SQL files
- Supports multiple database engines including experimental DuckDB support

### Frontend
- Mix of traditional PHP templates and modern UI components
- Uses ZUI framework (custom UI library)
- jQuery-based JavaScript
- CSS organized per module

## Development Commands

### Build System
```bash
# Full build process
make all

# Clean build artifacts
make clean

# Common build (core functionality)
make common

# Package for distribution
make package

# Create distribution packages
make pms          # Standard package
make ci           # CI build with all packages
```

### Testing
The project uses a custom testing framework located in `test/`:
```bash
# Run tests (navigate to test directory first)
cd test
php spider.php

# UI testing configuration available in test/config/config.php
```

### Code Quality
- PHP compatibility checks via `misc/compatibility/`
- Downgrade scripts for PHP version compatibility in `misc/rector/`
- Code minification: `php misc/minifyfront.php`

## Key Modules

### Core Business Modules
- **product/**: Product management
- **project/**: Project management (includes execution/)
- **story/**: User story management
- **task/**: Task management
- **bug/**: Bug tracking
- **testcase/**: Test case management
- **build/**: Build management

### Administrative Modules
- **user/**: User management
- **group/**: Permission groups
- **company/**: Organization management
- **dept/**: Department structure

### Integration Modules
- **git/, gitlab/, gitea/, gogs/**: Source control integration
- **jenkins/**: CI/CD integration
- **api/**: API management
- **webhook/**: Webhook support

### Reporting & Analytics
- **report/**: Standard reports
- **chart/**: Charting functionality
- **metric/**: Metrics calculation
- **bi/**: Business intelligence

## Configuration

### Database Configuration
- Main config: `config/config.php`
- Database settings typically in `config/my.php` (not tracked)

### Extension System
- Custom extensions in `extension/custom/`
- Configuration extensions in `config/ext/`

### Internationalization
- Language files in each module's `lang/` directory
- Supported languages: zh-cn, zh-tw, en, de, fr

## File Patterns

### Naming Conventions
- Controllers: `control.php`
- Models: `model.php`
- Views: `view/*.html.php`
- UI Components: `ui/*.html.php`
- Configurations: `config/*.php`
- Language files: `lang/{locale}.php`

### Architecture Layers
- **zen.php**: New architecture implementation
- **tao.php**: Business logic extension layer
- Traditional MVC for legacy code

## Development Notes

### PHP Requirements
- Minimum PHP 5.6, supports up to PHP 8.1+
- Uses strict types declaration in newer files
- Extensive use of custom framework classes

### Security
- Custom authentication and authorization system
- Input filtering via `lib/filter/`
- SQL injection protection through DAO layer

### Performance
- Built-in caching system in `lib/cache/`
- Database query optimization
- File-based session management

## Common Tasks

### Adding New Features
1. Create module directory structure in `module/`
2. Implement controller, model, and views
3. Add language files for internationalization
4. Configure routing if needed
5. Add database tables via SQL files in `db/`

### Database Changes
1. Add migration SQL to `db/update*.sql`
2. Update schema in `db/zentao.sql`
3. Test with different database engines if needed

### Testing Changes
1. Add test data to `test/data/`
2. Create test cases using the custom framework
3. Run compatibility checks for PHP versions

This codebase represents a mature, enterprise-level project management system with extensive customization capabilities and multi-language support.
