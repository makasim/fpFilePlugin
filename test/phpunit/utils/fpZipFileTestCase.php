<?php

class fpZipFileTestCase extends sfBasePhpunitTestCase
  implements sfPhpunitFixturePropelAggregator
{
  protected function _start()
  { 
    $test_dir = sfConfig::get('sf_cache_dir').'/test/phpunit-tmp'; 
    file_exists($test_dir) || mkdir($test_dir, 0777, true);
    file_exists($test_dir.'/linux_rulez.gif.zip') && unlink($test_dir.'/linux_rulez.gif.zip');
    file_exists($test_dir.'/linux_rulez.gif') && unlink($test_dir.'/linux_rulez.gif');
    
    copy($this->fixture()->getDirPackage().'/linux_rulez.gif.zip', $test_dir.'/linux_rulez.gif.zip');
    
    $this->_test_file = $test_dir.'/linux_rulez.gif.zip';
  }
  
  /**
   * @expectedException fpFileException
   */
  public function testFileHasInvalidExtension()
  {
    new fpZipFile(__FILE__);
  }
  
  public function testFileHasValidExtension()
  {
    return new fpZipFile($this->_test_file);
  }
  
  /**
   * @depends testFileHasValidExtension
   */
  public function testFileCanBeExtracted(fpZipFile $file)
  {
    $test_dir = dirname($this->_test_file);
    
    $file->unzip($test_dir);
    
    $this->assertTrue(file_exists($test_dir.'/linux_rulez.gif'));
  }
}