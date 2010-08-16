<?php

class fpFileTestCase extends sfBasePhpunitTestCase
  implements sfPhpunitFixturePropelAggregator
{
  protected $_test_file;
  
  protected function _start()
  { 
    $test_dir = sfConfig::get('sf_plugin_test_dir').'/temp/'.__CLASS__; 
    file_exists($test_dir) || mkdir($test_dir, 0777, true);
    file_exists($test_dir.'/linux_rulez.gif.zip') && unlink($test_dir.'/linux_rulez.gif.zip');
    file_exists($test_dir.'/copied.zip') && unlink($test_dir.'/copied.zip');
    file_exists($test_dir.'/movied.zip') && unlink($test_dir.'/movied.zip');
        
    copy(
      $this->fixture()->getDirPackage().'/linux_rulez.gif.zip', 
      $test_dir.'/linux_rulez.gif.zip');
    
    $this->_test_file = $test_dir.'/linux_rulez.gif.zip';
  }
  
  public function testObjectCanBeCreatedForExistFile()
  {
    return new fpFile($this->_test_file);
  } 
  
  /**
   * @depends testObjectCanBeCreatedForExistFile
   */
  public function testFileCanBeCopied(fpFile $file)
  {    
    $test_dir = dirname($this->_test_file);
    
    $copyied = $file->copy($test_dir.'/copied.zip');
    
    $this->assertTrue(file_exists($test_dir.'/copied.zip'));
    
    $this->assertType('fpFile', $copyied);
    $this->assertTrue($copyied->exists());
    $this->assertEquals($test_dir.'/copied.zip', $copyied->getPath());
  }
  
  /**
   * @depends testObjectCanBeCreatedForExistFile
   */
  public function testFileCanBeMovied(fpFile $file)
  {
    $test_dir = dirname($this->_test_file);
    
    $movied = $file->move($test_dir.'/movied.zip'); 
    
    $this->assertEquals($file, $movied);
    $this->assertTrue($movied->exists());
    $this->assertEquals($test_dir.'/movied.zip', $movied->getPath());
  }
}