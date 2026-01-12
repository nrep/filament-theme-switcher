# Filament Theme Switcher - Premium Roadmap

> **Project:** nrep/filament-theme-switcher  
> **Current Version:** v4.0.3  
> **Target:** Premium Filament Plugin  
> **Last Updated:** 2026-01-12

---

## Overview

Transform the basic theme switcher into a premium Filament plugin with advanced theming capabilities, dark mode support, visual builder, and multi-tenant features.

---

## Release Timeline

| Version | Codename | Target Date | Status |
|---------|----------|-------------|--------|
| v1.x | Foundation | Jan 2026 | ✅ Released |
| v2.0 | Dark Matter | Jan 2026 | ✅ Released |
| v3.0 | Architect | Jan 2026 | ✅ Released |
| v4.0 | Marketplace | Jan 2026 | 🚧 In Progress |

---

## v1.x - Foundation (Current)

### Completed Features
- [x] Basic theme switching
- [x] 7 pre-built themes (Default, Sunset, Ocean, Forest, Midnight, Rose, Amber)
- [x] Global and per-user theme modes
- [x] Custom color overrides
- [x] Theme settings page
- [x] Session/database persistence
- [x] Livewire theme switcher component
- [x] Middleware for applying themes
- [x] Filament v3 & v4 compatibility
- [x] Published to Packagist

### Bug Fixes
- [x] Fix custom colors not persisting after save
- [x] Fix composer.json self-reference error

---

## v2.0 - Dark Matter

### Milestone: Dark Mode System
> Priority: High | Effort: Medium

- [x] **DM-001**: Add dark mode toggle to theme settings
- [x] **DM-002**: Create dark mode variants for all built-in themes
- [x] **DM-003**: Implement system preference detection (prefers-color-scheme)
- [x] **DM-004**: Add scheduled dark mode (sunset/sunrise based on timezone)
- [x] **DM-005**: Per-user dark mode preference storage
- [x] **DM-006**: Add dark mode toggle to Livewire switcher component

### Milestone: Custom CSS Injection
> Priority: High | Effort: Medium

- [x] **CSS-001**: Add custom CSS field to theme settings
- [x] **CSS-002**: Create CSS editor with syntax highlighting (CodeMirror/Monaco)
- [x] **CSS-003**: Implement CSS scoping to prevent conflicts
- [x] **CSS-004**: Add CSS validation before saving
- [x] **CSS-005**: Create preset CSS snippets library

### Milestone: Theme Import/Export
> Priority: Medium | Effort: Low

- [x] **IE-001**: Create theme export to JSON functionality
- [x] **IE-002**: Create theme import from JSON functionality
- [x] **IE-003**: Add theme duplication feature
- [x] **IE-004**: Validate imported theme structure

### Milestone: Enhanced Color System
> Priority: Medium | Effort: Medium

- [x] **EC-001**: Add all Filament color slots (primary, secondary, danger, warning, success, info, gray)
- [x] **EC-002**: Add gradient support for primary colors
- [x] **EC-003**: Create color palette generator (complementary, analogous, triadic)
- [x] **EC-004**: Add color history/favorites

### v2.0 Release Checklist
- [x] All milestone tasks completed
- [ ] Unit tests written (80%+ coverage)
- [ ] Documentation updated
- [ ] Migration guide from v1.x
- [ ] Changelog updated

---

## v3.0 - Architect

### Milestone: Visual Theme Builder
> Priority: High | Effort: High

- [x] **VB-001**: Design theme builder UI mockups
- [x] **VB-002**: Create live preview panel component
- [x] **VB-003**: Implement drag-and-drop color picker
- [x] **VB-004**: Add component-level styling (sidebar, header, cards, buttons)
- [x] **VB-005**: Create undo/redo functionality
- [x] **VB-006**: Add responsive preview (desktop/tablet/mobile)

### Milestone: Font Customization
> Priority: Medium | Effort: Medium

- [x] **FC-001**: Integrate Google Fonts API
- [x] **FC-002**: Add font family selector (heading, body, mono)
- [x] **FC-003**: Implement font size customization
- [x] **FC-004**: Add font weight options
- [x] **FC-005**: Create font preview in settings

### Milestone: Brand Kits
> Priority: High | Effort: Medium

- [x] **BK-001**: Add logo upload per theme
- [x] **BK-002**: Add favicon customization
- [x] **BK-003**: Implement login page theming
- [x] **BK-004**: Add email template theming support
- [x] **BK-005**: Create brand kit presets

### Milestone: Multi-Panel Support
> Priority: Medium | Effort: Low

- [x] **MP-001**: Allow different themes per Filament panel
- [x] **MP-002**: Add panel-specific color overrides
- [x] **MP-003**: Create panel theme inheritance

### v3.0 Release Checklist
- [ ] All milestone tasks completed
- [ ] Visual builder tested across browsers
- [ ] Performance benchmarks (< 100ms theme switch)
- [ ] Documentation with video tutorials
- [ ] Changelog updated

---

## v4.0 - Marketplace

### Milestone: Multi-Tenant Theming
> Priority: High | Effort: High

- [x] **MT-001**: Add tenant-aware theme resolution
- [x] **MT-002**: Implement tenant-specific branding
- [x] **MT-003**: Create white-label support (remove plugin branding)
- [x] **MT-004**: Add subdomain-based theme detection
- [x] **MT-005**: Create tenant theme management API

### Milestone: Theme Marketplace
> Priority: Medium | Effort: High

- [x] **TM-001**: Design marketplace UI
- [x] **TM-002**: Create theme submission workflow
- [x] **TM-003**: Implement theme installation from marketplace
- [x] **TM-004**: Add theme ratings and reviews
- [x] **TM-005**: Create theme versioning system
- [x] **TM-006**: Add theme update notifications

### Milestone: Analytics Dashboard
> Priority: Low | Effort: Medium

- [x] **AD-001**: Track theme usage statistics
- [x] **AD-002**: Create analytics dashboard widget
- [x] **AD-003**: Add popular themes report
- [x] **AD-004**: Implement A/B testing for themes

### v4.0 Release Checklist
- [ ] All milestone tasks completed
- [ ] Multi-tenant tested with Filament packages (Tenancy, Spatie)
- [ ] Marketplace security audit
- [ ] API documentation
- [ ] Changelog updated

---

## Licensing & Monetization

### Setup Tasks
- [ ] **LIC-001**: Choose licensing platform (Anystack.sh recommended)
- [ ] **LIC-002**: Create license verification middleware
- [ ] **LIC-003**: Implement license activation flow
- [ ] **LIC-004**: Add graceful degradation for expired licenses
- [ ] **LIC-005**: Create license management page in settings

### Pricing Tiers

| Tier | Price | Features |
|------|-------|----------|
| **Starter** | $49 | Single project, 1 year updates |
| **Pro** | $99 | Unlimited projects, 1 year updates, priority support |
| **Agency** | $249 | Unlimited projects + clients, lifetime updates, white-label |

### Marketing Tasks
- [ ] **MKT-001**: Create landing page
- [ ] **MKT-002**: Write documentation site
- [ ] **MKT-003**: Create demo video
- [ ] **MKT-004**: Submit to Filament plugins directory
- [ ] **MKT-005**: Write launch blog post

---

## Technical Debt & Improvements

### Code Quality
- [ ] **TQ-001**: Add PHPStan static analysis (level 8)
- [ ] **TQ-002**: Set up Pest test suite
- [ ] **TQ-003**: Add GitHub Actions CI/CD
- [ ] **TQ-004**: Create code style enforcement (Pint)

### Performance
- [ ] **PF-001**: Implement theme caching
- [ ] **PF-002**: Lazy load theme assets
- [ ] **PF-003**: Optimize database queries for user mode
- [ ] **PF-004**: Add Redis support for session storage

### Developer Experience
- [ ] **DX-001**: Create Artisan commands (make:theme, theme:list)
- [ ] **DX-002**: Add IDE helper file generation
- [ ] **DX-003**: Create Facade for ThemeManager
- [ ] **DX-004**: Add event hooks (ThemeChanged, ThemeSaved)

---

## Notes

### Dependencies to Evaluate
- `livewire/livewire` - Already required via Filament
- `spatie/laravel-medialibrary` - For logo/image uploads
- `google/apiclient` - For Google Fonts API
- `anystack-sh/laravel-licensing` - For license verification

### Competitors Analysis
- **Hasnayeen/themes** - Basic theme switching (free)
- **Filament Curator** - Media management approach
- **Custom implementations** - Most users roll their own

### Success Metrics
- 500+ installs within 3 months of v2.0 launch
- 4.5+ star rating on Packagist
- < 10 open issues at any time
- 50+ paying customers by v3.0

---

## Changelog

### 2026-01-12
- Created initial roadmap
- Released v1.1.0 with bug fixes
- **v2.0 Development Started:**
  - Added dark mode toggle (light/dark/system)
  - Added custom CSS injection feature
  - Added theme export functionality
  - Added SupportsDarkMode contract
  - Updated config with dark_mode, custom_css, import_export options
  - Updated migrations for dark_mode and custom_css columns
  - Updated translations for new features
  - Added dark mode color variants for all 7 themes
  - Added dark mode toggle to Livewire switcher dropdown
  - Added ColorPaletteGenerator support class
  - Added color history and favorites functionality
  - Added CssSnippets library with validation and scoping
  - Added gradient support to ColorPaletteGenerator
  - Added theme duplication feature
  - Added scheduled dark mode with time-based activation
  - **All v2.0 milestone tasks completed!**

### 2026-01-11
- Released v1.0.0 (initial release)
- Published to GitHub and Packagist
