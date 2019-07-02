# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased

## 3.3.1 - 2019-07-02

### Fixed
- crash from broken plugin loaded hook input from global

## 3.3 - 2019-03-27

### Added
- plotting of early SQL queries (WP 5.1+)
- tracking load of individual plugin load (WP 5.1+)

### Changed
- unit tests for PHPUnit 8

### Fixed
- error when formatting empty hook.

## 3.2 - 2019-01-30

### Added
- Beaver Builder events
- error message on unsupported PHP version

### Fixed
- handling of reoccurring hook events
- tracking of load events when network–activated

### Deprecated
- dedicated classes for extension events

## 3.1 - 2019-01-22

### Added
- WooCommerce events
- PHP version and OPcache status information
- error message on incomplete install

### Fixed
- headers error from new line in Server Timing

## 3.0.1 - 2019-01-09

### Fixed
- fatal error when HTTP request is pre–filtered

## 3.0 - 2018-12-28

### Added
- call backtraces to SQL records and HTTP calls
- new format of hook events
- sidebar tracking
- list of callbacks to hook events
- Server Timing output for Ajax and REST APIs
- lazy records collector to pass around instead of whole container

### Changed
- required PHP version to 7.1
- organization and names for Record classes

### Deprecated
- old format of hook events

### Fixed
- error when no SQL queries logged by core

## 2.0 - 2018-02-26

### Added
- unified processing of events from arbitrary sources
- unit tests
- tracking PHP and WP core load times

### Changed
- minimum PHP version requirement to >=5.6
- class files names to PSR–4
- main class into concrete instance and Pimple container
- timeline to unified processing with as necessary nesting
- directory structure to PDS skeleton
- baseline timings to float in seconds (from milliseconds)
- color scheme

### Removed
- Thematic hook events

### Fixed
- handling or not properly started/stopped hook events

## 1.4.4 - 2017-07-05

### Added
- Robo task to update header version and tag in Git

### Fixed
- version in header

## 1.4.3 - 2017-06-28

### Fixed
- sanity check for invalid priority returned by core

## 1.4.2 - 2016-12-02

### Fixed
- back to fully functional on WP 4.7 beta

## 1.4.1 - 2016-12-01

### Fixed
- errors and crash on WP 4.7 beta (not fully functional yet)

## 1.4 - 2016-10-01

### Added
- added screen reader text to events
- colorized writing SQL queries to warning color

### Changed
- updated change log format to Keep a Changelog
- updated dependencies (Stopwatch 2.8.11, Mustache 2.11.1, Bootstrap 3.3.7)
- updated assets

### Removed
- removed release Robo task

## 1.3.2 - 2015-08-27

### Fixed
 - fixed dynamic method call crash on PHP 7
 - fixed issue with slashes in generated release archives

## 1.3.1 - 2015-08-12

### Fixed
 - fixed crash on event stop without matching start

## 1.3 - 2015-06-12

### Added
 - implemented minified assets
 - implemented pre-cached template
 - implemented release build command
 - added WP SEO event
 
### Changed
 - split plugins_loaded hook into separate event
 
### Fixed
 - fixed crash with upcoming Hybrid Core v3

## 1.2.1 - 2014-07-12

### Fixed
 - disabled tooltip animation to fix JS errors and UI disappearing from under cursor

## 1.2 - 2014-05-18

### Added
 - implemented (ballpark) toolbar event

### Changed
 - merged RP with additional core events in admin
 
### Fixed
 - fixed issue with event offsets in timelines

## 1.1 - 2013-12-11

### Added
 - added SQL timeline
 - added HTTP timeline
 
### Changed
 - switched tooltips implementation to Bootstrap script

## 1.0 - 2013-04-22

### Added
 - Initial public release