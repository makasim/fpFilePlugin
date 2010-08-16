<?php

class fpTempFolderTestCase extends sfBasePhpunitTestCase
  implements sfPhpunitFixturePropelAggregator
{
  public function testTempFolder()
  {
    $tmp_folder = new fpTempFolder();
    $this->assertTrue(file_exists($tmp_folder->getPath()));
    $this->assertTrue(is_readable($tmp_folder->getPath()));
    $this->assertTrue(is_writeable($tmp_folder->getPath()));
    
    return $tmp_folder;
  }
  
  /**
   * @depends testTempFolder
   */
  public function testTwoTempDirAreDifferent(fpTempFolder $tmp_folder)
  {
    $tmp_folder2 = new fpTempFolder();
    $this->assertNotEquals($tmp_folder->getPath(), $tmp_folder2->getPath());
  }
  
  /**
   * @depends testTempFolder
   */
  public function testTmpDirIsAutoRemoved()
  {
    $tmp_folder = new fpTempFolder();
    $path = $tmp_folder->getPath();

    unset($tmp_folder);
    $this->assertFalse(file_exists($path));
  }
  
  public function testTmpDirWouldNotDeleteAutomaticalyIfDeletedManualy()
  {
    $tmp_folder = new fpTempFolder();
    $path = $tmp_folder->getPath();
    $tmp_folder->remove();
    $this->assertFalse(file_exists($path));
    
    unset($tmp_folder);
  }
}