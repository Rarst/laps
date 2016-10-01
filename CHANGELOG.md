# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased

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