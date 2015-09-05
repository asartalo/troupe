Troupe
======

Unmaintained. Use [Composer](https://getcomposer.org/) instead.
---------------------------------------------------------------
Troupe was written at a time when there was no easy dependency management and vendoring available for PHP. The PHP community has since moved along and now we can happily discontinue this project.

**Troupe** is a tool for declaring and managing source code dependencies in PHP.

With Troupe you can:

- declare dependencies in your source. Mytroupe files documents your dependencies so that collaborators can easily import everything they need to get started.
- easily import source code from other SCMs. You don't have to worry when your project uses subversion and you need some code that is hosted on github. Simply declare that dependency, and only track the mytroupe file. Your project doesn't even have to use git submodules or svn externals!
- import source code archives. If a source code is served as a downloadable archive file like zip, targ.gz, Troupe can import them for you as well.

News
----

- Troupe can now download from, git, svn, and zip archives.

Hypothetical Example
--------------------

Dependency information is stored in a mytroupe file (here we use mytroupe.php)

```php
// mytroupe.php
return array(
  'symfony' => array(
    'url'     => 'git://github.com/symfony/symfony.git',
    'alias'   => 'symfony2', // This will be the directory name the source will be renamed to. Defaults to label/name
    'move_to' => 'lib/src', // Defaults to 'vendor_dir' in settings
  ),

  'doctrine' => 'git://github.com/doctrine/doctrine2.git',

  // Pear import is not yet implemented
  'phpunit' => array(
    'type' => 'pear',
    'channel' => 'pear.phpunit.de',
    'pear_name' => 'phpunit/PHPUnit'
  ),
  // Or 'phpunit' => 'pear://pear.phpunit.de/PHPUnit'

  'minify' => 'http://code.google.com/p/minify/downloads/detail?name=minify_2.1.3.zip',

  // This checks the platform or environment where the application runs
  '_platform' => array(
    'php_version' => '5.3.x'
  ),

  // This is for the project settings
  '_this' => array(
    'vendor_dir' => 'lib/src', // Default is 'vendor'
  ),
);
```

Then one can run this in terminal:

```bash
$ troupe assemble
```

And voila! Dependencies solved.

To update your sources, execute:

```bash
$ troupe update
```

To list all dependencies:

```bash
$ troupe list
```


Requirements
------------

- PHP 5.3.x or greater
- To uncompress gzip and zip files, PHP must be compiled with Zlib enabled
- To import git repositories, a git client on the terminal must be installed
- To import subversion repositories, an svn client on the terminal must be installed

Known Issues
------------

- This code has currently been tested on Linux (Ubuntu) and Mac OS X only.
- Troupe will not work for environments that do not support symbolic links. Windows Vista and Windows 7 seem to support this but Troupe hasn't been tested on those platforms yet.

Help Out
--------

Want to help out? Some things the project needs:

- Testing
  - Run the tests in different platforms.
  - Test in Windows
- Feedback
  - How does it work for you?
  - What do you think needs to be done?
- Improve it!
  - You can fork the code if you like. Please note that the documentation is lacking. I'll get to that, I promise.


TODO
-----

- Support PEAR sources
- Declare where in the source tree the interesting part of the code is located

MISC
----

- Troupe uses PclZip to expand Zip archives. PclZip is released under LGPL.
