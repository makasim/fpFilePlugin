<?php

class fpPublicFileTestCase extends sfBasePhpunitTestCase
{
  protected function _start()
  {
    $tempDir = sfConfig::get('sf_plugin_test_dir').'/temp/'.__CLASS__; 
    file_exists($tempDir) || mkdir($tempDir, 0777, true);
    
    sfConfig::set('sf_web_dir', $tempDir);
    sfConfig::set('app_web_dir', $tempDir);
    
    file_exists($tempDir.'/test.txt') || file_put_contents($tempDir.'/test.txt', 'aa');
  }
  
  /**
   * @expectedException fpFileException
   */
  public function testGetUrlInvalidPublicPath()
  {
    $file = new fpPublicFile(sfConfig::get('sf_root_dir'));
    $file->getUrl();
  }
  
  public function testConstructValidAbsolutePublicPath()
  {
    $file = new fpPublicFile(sfConfig::get('sf_web_dir').'/test.txt');
    
    $this->assertEquals(sfConfig::get('sf_web_dir').'/test.txt', $file->getPath());
    $this->assertEquals('/test.txt', $file->getUrl());
  }
  
  public function testConstructValidRelativePublicPath()
  {
    $file = new fpPublicFile('test.txt');
    
    $this->assertEquals(sfConfig::get('sf_web_dir').'/test.txt', $file->getPath());
    $this->assertEquals('/test.txt', $file->getUrl());
  }
}