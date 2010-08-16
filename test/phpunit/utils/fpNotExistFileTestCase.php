<?php

class fpNotExistFileTestCase extends sfBasePhpunitTestCase
  implements sfPhpunitFixturePropelAggregator
{
  public function testConstructNotRequierAnyParameters()
  {
    new fpNotExistFile();
  }
  
  public function testExistAlwaysReturnsFalse()
  {
    $file = new fpNotExistFile();
    $this->assertFalse($file->exists());
    
    $file = new fpNotExistFile($this->fixture()->getFilePackage('linux_rulez.gif.zip'));
    $this->assertFalse($file->exists());
  }
  
  /**
   * @dataProvider providerFileMethods
   * 
   * @expectedException fpFileException
   */
  public function testOtherMethodsWillThrowAnException($method, $args)
  {
    $file = new fpNotExistFile();
    
    call_user_func_array(array($file, $method), $args);
  }
  
  public static function providerFileMethods()
  {
    return array(
      array('copy',       array('target_file')),
      array('getFolder',  array()),
      array('getPath',    array()),
      array('move',       array('target_file')));
  }
  
  public function testRemoveDoNothing()
  {
    $file = new fpNotExistFile();
    $result = $file->remove();
    
    $this->assertSame($file, $result);
  }

  public function testChmodDoNothing()
  {
    $file = new fpNotExistFile();
    $result = $file->chmod(0777);
    
    $this->assertSame($file, $result);
  }
}