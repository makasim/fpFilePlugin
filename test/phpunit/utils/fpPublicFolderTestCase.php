<?php

class fpPublicFolderTestCase extends sfBasePhpunitTestCase
{
  protected $_absolutePath;
  
  protected $_relativePath;
  
  protected function _start()
  {
    $tempDir = sfConfig::get('sf_plugin_test_dir').'/temp/'.__CLASS__; 
    file_exists($tempDir) || mkdir($tempDir, 0777, true);
    
    sfConfig::set('sf_web_dir', $tempDir);
    sfConfig::set('app_web_dir', $tempDir);
    
    $this->_relativePath = 'relativeDir';
    $this->_absolutePath = $tempDir.'/'.$this->_relativePath;
    !file_exists($this->_absolutePath) && mkdir($this->_absolutePath);
  }
  
  protected function _end()
  {
    if (file_exists($this->_absolutePath)) {
      $f = new fpFolder($this->_absolutePath);
      $f->remove();
    }
  }

  public function testConstructNotExistPath()
  {
    $expectedFolderName = preg_replace('/[^\w]/', '_', __METHOD__);
    $expectedFolder = sfConfig::get('app_web_dir').'/'.$expectedFolderName;
    file_exists($expectedFolder) && rmdir($expectedFolder);
    
    $folder = new fpPublicFolder($expectedFolderName);
    
    $this->assertEquals($expectedFolder, $folder->getPath());
    $this->assertTrue(file_exists($expectedFolder));
    
    file_exists($expectedFolder) && rmdir($expectedFolder);
  }
  
  public function testConstructValidAbsolutePath()
  {     
    $folder = new fpPublicFolder($this->_absolutePath);
    
    $this->assertEquals($this->_absolutePath, $folder->getPath());
  }
  
  public function testConstructValidAppRelativePublicPath()
  {
    $folder = new fpPublicFolder($this->_relativePath);
    
    $this->assertEquals($this->_absolutePath, $folder->getPath());
  }
  
  public function testConstructForEmptyPathReturnsRootPublicFolder()
  {     
    $folder = new fpPublicFolder();
    
    $this->assertEquals(sfConfig::get('app_web_dir'), $folder->getPath());
  }
  
  /**
   * @depends testConstructForEmptyPathReturnsRootPublicFolder
   */
  public function testGetUrl()
  {
    $folder = new fpPublicFolder($this->_absolutePath);
    
    $this->assertEquals("/$this->_relativePath", $folder->getUrl());
  }
}