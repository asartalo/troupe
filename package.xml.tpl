<?php echo '<'. '?xml version="1.0" encoding="UTF-8"?>' ?>
<package packagerversion="1.8.0" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
    http://pear.php.net/dtd/tasks-1.0.xsd
    http://pear.php.net/dtd/package-2.0
    http://pear.php.net/dtd/package-2.0.xsd">
<name>Troupe</name>
<channel>pear.brainchildprojects.org</channel>
<summary>Troupe is a tool for declaring and managing source code dependencies in PHP.</summary>
<description>
 Troupe is a tool for declaring and managing source code dependencies in PHP.

 With Troupe you can:
  * declare dependencies in your source. Mytroupe files documents your dependencies so that collaborators can easily import everything they need to get started.
  * easily import source code from other SCMs. You don't have to worry when your project uses subversion and you need some code that is hosted on github. Simply declare that dependency, and only track the mytroupe file. Your project doesn't even have to use git submodules or svn externals!
  * import source code archives. If a source code is served as a downloadable archive file like zip, targ.gz, Troupe can import them for you as well.
</description>
<lead>
  <name>Wayne Duran</name>
  <user>asartalo</user>
  <email>asartalo@projectweb.ph</email>
  <active>yes</active>
</lead>
<date>2011-04-22</date>
<time>16:00:00</time>
<version>
  <release>0.1.0</release>
  <api>0.1.0</api>
</version>
<stability>
<release>beta</release>
<api>beta</api>
</stability>
<license uri="http://www.opensource.org/licenses/mit-license.php">MIT</license>
<notes>https://github.com/asartalo/troupe/blob/master/README.md</notes>
<contents>
  <dir name="/">
    <file name="LICENSE" role="doc" />
    <file name="README.md" role="doc" />
    <file role="script" baseinstalldir="/" name="bin/troupe">
      <tasks:replace from="/usr/bin/env php" to="php_bin" type="pear-config" />
    </file>
    <?php echo $sources ?>
  </dir>
</contents>
<dependencies>
<required>
  <php>
    <min>5.3.0</min>
  </php>
  <pearinstaller>
    <min>1.9.0</min>
  </pearinstaller>
</required>
</dependencies>
<phprelease>
  <filelist>
    <install as="troupe" name="bin/troupe" />
  </filelist>
</phprelease>

</package>
