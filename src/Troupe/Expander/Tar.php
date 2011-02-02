<?php
/*
 * untar method copied from: http://www.php.net/manual/en/function.fopen.php
 * written by: kazuki dot przyborowski at gmail dot com
 * 
 * code uses the Revised BSD License (probably this one: http://www.opensource.org/licenses/bsd-license.php)
 * As of February 2, 2011, I have yet to contact the original author.
 * I promise to do so immediately.
 */
namespace Troupe\Expander;


class Tar implements Expander {
  
  function expand($archive, $to) {
    if (is_dir($to)) {  
      $first_scandir = scandir($to);
    } else {
      $first_scandir = array();
    }
    if (file_exists($archive) && $this->untar($archive, $to)) {
      $second_scandir = scandir($to);
      $diff = array_diff($second_scandir, $first_scandir);
      $result = array();
      foreach ($diff as $item) {
        $result[] = $to . '/' . $item;
      }
      return $result;
    }
    return array();
  }
  
  function untar($tarfile,$outdir="./",$chmod=null) {
    $TarSize = filesize($tarfile);
    $TarSizeEnd = $TarSize - 1024;
    if( $outdir!="" && !file_exists($outdir)) {
      mkdir($outdir,0777);
    }
    
    // safely reformat output directory
    $outdir = rtrim($outdir, '/') . '/';
    
    $thandle = fopen($tarfile, "r");
    while (ftell($thandle)<$TarSizeEnd) {
      $OrigFileName = trim(fread($thandle,100));
      $FileName = $outdir . $OrigFileName;
      $FileMode = trim(fread($thandle,8));
      if($chmod===null) {
        $FileCHMOD = octdec("0".substr($FileMode,-3));
      } else {
        $FileCHMOD = $chmod;
      }
      $OwnerID = trim(fread($thandle,8));
      $GroupID = trim(fread($thandle,8));
      $FileSize = octdec(trim(fread($thandle,12)));
      $LastEdit = trim(fread($thandle,12));
      $Checksum = trim(fread($thandle,8));
      $FileType = trim(fread($thandle,1));
      $LinkedFile = trim(fread($thandle,100));
      
      fseek($thandle,255,SEEK_CUR);
      if($FileType=="0") {
        $FileContent = fread($thandle,$FileSize);
      }
      if($FileType=="1") {
        $FileContent = null;
      }
      if($FileType=="2") {
        $FileContent = null;
      }
      if($FileType=="5") {
        $FileContent = null;
      }
      // If it's a file and it's not the output directory...
      if($FileType=="0" && $FileName !== $outdir) {
        // Check if containing directory exists
        // Because some tar files do not explicitly include directories
        if (!file_exists(dirname($FileName))) {
          $this->autoCreateDirectories($outdir, dirname($OrigFileName));
        }
        $subhandle = fopen($FileName, "a+");
        fwrite($subhandle,$FileContent,$FileSize);
        fclose($subhandle);
        chmod($FileName,$FileCHMOD);
      }
      if($FileType=="1") {
        link($FileName,$LinkedFile);
      }
      if($FileType=="2") {
        symlink($LinkedFile,$FileName);
      }
      if($FileType=="5") {
        mkdir($FileName,$FileCHMOD);
      }
      if($FileType=="0") {
        $CheckSize = 512;
        while ($CheckSize<$FileSize) {
          if($CheckSize<$FileSize) {
            $CheckSize = $CheckSize + 512;
          } 
        }
        $SeekSize = $CheckSize - $FileSize;
        fseek($thandle,$SeekSize,SEEK_CUR);
      }
    }
    fclose($thandle);
    return true;
  }
  
  // TODO: Make this section more robust
  function autoCreateDirectories($parent, $dir) {
    $this->_autoCreateDirectories(
      rtrim($parent, '/\\') . DIRECTORY_SEPARATOR . ltrim($dir, '/\\'),
      $parent
    );
  }
  
  private function _autoCreateDirectories($dir, $limit) {
    // $limit is to ensures we don't write anywhere else
    if (file_exists($dir) || (realpath($dir) == realpath($limit))) {
      return;
    }
    // Check if parent directory exists
    $parent_dir = pathinfo($dir, PATHINFO_DIRNAME);
    if (!file_exists($parent_dir)) {
      $this->_autoCreateDirectories($parent_dir, $limit);
    }
    mkdir($dir);
  }
  
}
