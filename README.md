# Laps — light WordPress profiler

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
 - [Thematic](http://thematictheme.com/)

There are also additional optional timelines, displaying:

 - SQL queries (with [`SAVEQUERIES` constant](http://codex.wordpress.org/Editing_wp-config.php#Save_queries_for_analysis) enabled)
 - HTTP requests performed

## Installation

Laps is a Composer package and can be installed in plugin directory via:

    composer create-project rarst/laps --no-dev

## License Info

Laps own code is licensed under MIT and it makes use of code from:

 - Composer (MIT)
 - Symfony Stopwatch (MIT)
 - Mustache.php (MIT)
 - Twitter Bootstrap (MIT)