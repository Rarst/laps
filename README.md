# Laps — light WordPress profiler
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Rarst/laps/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Rarst/laps/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Rarst/laps/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/rarst/laps.svg)](https://packagist.org/packages/rarst/laps)
[![Latest Stable Version](https://img.shields.io/packagist/v/rarst/laps.svg?label=version)](https://packagist.org/packages/rarst/laps)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rarst/laps.svg)](https://packagist.org/packages/rarst/laps)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg)](https://github.com/php-pds/skeleton)

_Make a site, make it fast._

![Laps v2 screenshot](https://i.imgur.com/6n36KPE.png)

Laps is a plugin that **shows performance information** about WordPress page load.

It provides a visual summary in toolbar that is quick and easy to inspect.

Laps tracks many events, such as:
- PHP, core, plugins, and themes load
- database queries (with [`SAVEQUERIES` constant](http://codex.wordpress.org/Editing_wp-config.php#Save_queries_for_analysis) enabled)
- network requests to other sites
- main posts loop
- events for supported theme frameworks: 
  - [Theme Hook Alliance](http://zamoose.github.io/themehookalliance/)
  - [Hybrid](http://themehybrid.com/)
  - [Genesis](http://my.studiopress.com/themes/genesis/)
- events for supported plugins:
  - [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/) 

## Installation

Require with [Composer](https://getcomposer.org/):

```bash
composer require rarst/laps
```

Or download plugin archive from [releases section](https://github.com/Rarst/laps/releases).

## License

MIT