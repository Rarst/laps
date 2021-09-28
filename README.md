![Laps v3 toolbar screenshot](https://i.imgur.com/NtgAxhp.png)

# Laps — light WordPress profiler

_Make a site, make it fast._

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Rarst/laps/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Rarst/laps/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Rarst/laps/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/rarst/laps.svg)](https://packagist.org/packages/rarst/laps)
[![Latest Stable Version](https://img.shields.io/packagist/v/rarst/laps.svg?label=version)](https://packagist.org/packages/rarst/laps)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rarst/laps.svg)](https://packagist.org/packages/rarst/laps)
[![Download Laps](https://img.shields.io/badge/download-laps.zip-blue)](https://github.com/Rarst/laps/releases/latest/download/laps.zip)

Laps is a plugin that **shows performance information** about WordPress page load.

It provides a visual summary in toolbar that is quick and easy to inspect.

## Page profiling

Laps automatically tracks many events, such as:
- PHP, core, plugins, and themes load, main posts loop, sidebars
- database queries (with [`SAVEQUERIES` defined constant set to true](https://wordpress.org/support/article/editing-wp-config-php/#save-queries-for-analysis))
- network requests to other sites 

## API profiling

For Ajax and REST API — Laps outputs performance information by Server Timing API, for use with clients such as Chrome Dev Tools.

![Laps v3 dev tools screenshot](https://i.imgur.com/hkl1Qk9.png)

### API authentication

API requests need to be authenticated as admin for performance data to be sent. For Ajax requests cookies are sufficient. REST API requests also [need nonce passed](https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/#cookie-authentication).

`laps_can_see` check can be filtered to relax required permissions on the plugin’s side.

## Installation

| [Composer](https://getcomposer.org/) (recommended) | Release archive |  
| -------------------------------------------------- | -------- |  
| `composer require rarst/laps` | [![Download Laps](https://img.shields.io/badge/download-laps.zip-blue?style=for-the-badge)](https://github.com/Rarst/laps/releases/latest/download/laps.zip) |  

## Tests

Tests require [Brain Monkey](https://github.com/Brain-WP/BrainMonkey) (included in dependencies) and PHPUnit 9 (not included).

```bash
phpunit
```

## License

MIT
