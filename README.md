Troupe
======

**Troupe** is a tool for declaring and managing application dependencies in PHP.

Status
------

Troupe can now download from, git, svn, and zip archives.

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
        'vendor_dir' => 'lib/src', // Default is 'vendor',
        
      ),
    );

Then one can run this in the commandline:

   troupe assemble

And voila! Dependencies solved.

TODO
-----

- Declare dependencies through php code
- Support different sources
  - subversion - done (must have an svn client installed that can be run in terminal)
  - git - done (must have a git client installed)
  - pear
  - archive source files
    - tar 
    - zip - done (uses the PclZip library so we're not dependent on enabling ZipLib)
    - tar.gz
- Declare where a source will be placed (default is vendor dir)
- Declare where in the source tree the interesting part of the code is located

