# Description

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

 - Theme Hook Alliance
 - Hybrid
 - Genesis
 - Thematic

There are also additional optional timelines, displaying:

 - SQL queries (with `SAVEQUERIES` constant enabled)
 - HTTP requests performed

# Screenshot

![Laps screenshot](http://i.imgur.com/zFokmkU.png)

# Installation

Laps is a Composer package and can be installed in plugin directory via:

    composer create-project rarst/laps --no-dev

# Frequently Asked Questions

None... Yet.

# Changelog

## 1.2

 - merged RP with additional core events in admin
 - implemented (ballpark) toolbar event
 - fixed issue with event offsets in timelines

## 1.1

 - added SQL timeline
 - added HTTP timeline
 - switched tooltips implementation to Bootstrap script

## 1.0

 - Initial public release

# License Info

Laps own code is licensed under MIT and it makes use of code from:

 - Composer (MIT)
 - Symfony Stopwatch (MIT)
 - Mustache.php (MIT)
 - Twitter Bootstrap (Apache License 2.0)