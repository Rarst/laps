# Laps — light WordPress profiler
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Rarst/laps/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Rarst/laps/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Rarst/laps/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Rarst/laps/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/rarst/laps.svg)](https://packagist.org/packages/rarst/laps)
[![Latest Stable Version](https://img.shields.io/packagist/v/rarst/laps.svg?label=version)](https://packagist.org/packages/rarst/laps)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rarst/laps.svg)](https://packagist.org/packages/rarst/laps)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg)](https://github.com/php-pds/skeleton)

![Laps screenshot](http://i.imgur.com/zFokmkU.png)

Laps is light WordPress profiler (in a plugin), which aims to be:

 - always on and zero click (just hover on toolbar entry) away
 - quick indicator of what hogs page in general or right now

It is no match or replacement for *real* profiler, but is friendly and cute.

Out of the box Laps supports common stage of WordPress page life cycle:

 - plugins load
 - themes load
 - core init
 - main loop

And some of third party hooks conventions for themes using:

 - [Theme Hook Alliance](http://zamoose.github.io/themehookalliance/)
 - [Hybrid](http://themehybrid.com/)
 - [Genesis](http://my.studiopress.com/themes/genesis/)

There are also additional optional timelines, displaying:

 - SQL queries (with [`SAVEQUERIES` constant](http://codex.wordpress.org/Editing_wp-config.php#Save_queries_for_analysis) enabled)
 - HTTP requests performed

## Installation

Download plugin archive from [releases section](https://github.com/Rarst/laps/releases).

Or install in plugin directory via [Composer](https://getcomposer.org/):

    composer create-project rarst/laps --no-dev

## License Info

Laps own code is licensed under MIT and it makes use of code from:

 - Composer Installers (MIT)
 - Symfony Stopwatch (MIT)
 - Mustache.php (MIT)
 - Twitter Bootstrap (MIT)
