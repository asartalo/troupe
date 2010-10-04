Troupe
======

**Troupe** is a tool for declaring and managing application dependencies in PHP. Troupe is not yet ready. Watch out for it!

Ideas
-----

- Declare dependencies through php code
- Support different sources
  - subversion
  - git
  - pear
  - archive source files (tar, zip)
- Declare where a source will be placed (default is vendor dir)
- Declare where in the source tree the interesting part of the code is located

Hypothetical Example
--------------------

Dependency information is stored in troupe.php

    // troupe.php
    return array(
      'symfony2' => array(
        'url' => 'git://github.com/symfony/symfony.git',
        'as'  => 'symfony', // Defaults to label
        'move_to' => 'lib/src', // Defaults to 'vendor'
      ),
      'doctrine' => 'git://github.com/doctrine/doctrine2.git',
      'phpunit' => array(
        'type' => 'pear',
        'channel' => 'pear.phpunit.de',
        'pear_name' => 'phpunit/PHPUnit'
      ),
      // Or 'phpunit' => 'pear://pear.phpunit.de/PHPUnit'
      'minify' => 'http://code.google.com/p/minify/downloads/detail?name=minify_2.1.3.zip'
    );

Then one can run this in the commandline:

   troupe assemble

And voila! Dependencies solved.