Troupe
======

**Troupe** is a tool for declaring and managing application dependencies in PHP.

News
----

- January 3, 2011 GMT+8 : Importing tar archives is currently buggy
- Troupe can now download from, git, svn, and zip archives.

Hypothetical Example
--------------------

Dependency information is stored in mytroupe.php

    // mytroupe.php
    return array(
      'symfony' => array(
        'url'     => 'git://github.com/symfony/symfony.git',
        'alias'   => 'symfony2', // This will be the directory name the source will be renamed to. Defaults to label/name
        'move_to' => 'lib/src', // Defaults to 'vendor_dir' in settings
      ),
      'doctrine' => 'git://github.com/doctrine/doctrine2.git',
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
      '_settings' => array(
        'vendor_dir' => 'lib/src', // Default is 'vendor'
      ),
    );

Then one can run this in terminal:

   troupe assemble

And voila! Dependencies solved.

Requirements
------------

- PHP 5.3.x or greater
- To uncompress gzip and zip files, PHP must be compiled with Zlib enabled
- To import git repositories, a git client on the terminal must be installed
- To import subversion repositories, an svn client on the terminal must be installed

Known Issues
------------

- Importing tar and tar.gz archives are currently buggy.
- This code has currently been tested on Linux (Ubuntu) only.

Help Out
--------

Want to help out? Some things the project needs:

- Testing
  - Run the tests. The project currently lacks an integration test.
  - Test in Windows
- Feedback
  - How does it work for you?
  - What do you think needs to be done?
- Improve it!
  - You can fork the code if you like. Please note that the documentation is lacking. I'll get to that, I promise.
  

TODO
-----

- Declare dependencies through php code
- Support different sources
  - subversion - done (must have an svn client installed that can be run in terminal)
  - git - done (must have a git client installed)
  - pear
  - archive source files
    - tar - buggy
    - zip - done (uses the PclZip library so we're not dependent on enabling ZipLib)
    - tar.gz - buggy
- Declare where a source will be placed (default is vendor dir)
- Declare where in the source tree the interesting part of the code is located

