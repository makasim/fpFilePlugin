<?php

class fpFolderTestCase extends sfBasePhpunitTestCase 
  implements sfPhpunitFixturePropelAggregator
{

  protected $_base;

  protected function _start()
  {
    $this->_base = sfConfig::get('sf_plugin_test_dir').'/temp/'.__CLASS__; 
    file_exists($this->_base) || mkdir($this->_base, 0777, true);

    file_exists($this->_base.'/folder/sub') && rmdir($this->_base.'/folder/sub');
    file_exists($this->_base.'/folder') && rmdir($this->_base.'/folder');

    file_exists($this->_base.'/folder2/sub') && rmdir($this->_base.'/folder2/sub');
    file_exists($this->_base.'/folder2') && rmdir($this->_base.'/folder2');
  }

  public function testCreate()
  {
    $this->assertFalse(file_exists($this->_base . '/folder'));

    $folder = new fpFolder($this->_base . '/folder');
    $this->assertTrue(file_exists($this->_base . '/folder'));
  }

  public function testRemove()
  {
    $this->assertFalse(file_exists($this->_base . '/folder'));

    $folder = new fpFolder($this->_base . '/folder');
    $this->assertTrue(file_exists($this->_base . '/folder'));

    $folder->remove();
    $this->assertFalse(file_exists($this->_base . '/folder'));
  }


  public function testHas()
  {

    $folder = new fpFolder($this->_base . '/folder');
    $sub = new fpFolder($this->_base . '/folder/sub');

    $this->assertTrue(file_exists($this->_base . '/folder/sub'));
    $this->assertTrue($folder->has('sub'));

  }

  public function testGetPath()
  {
    $folder = new fpFolder($this->_base . '/folder');
    $this->assertTrue(file_exists($folder->getPath()));
  }

  public function testCopy()
  {
    $this->assertFalse(file_exists($this->_base . '/folder'));
    $this->assertFalse(file_exists($this->_base . '/folder2'));

    $folder = new fpFolder($this->_base . '/folder');
    $sub = new fpFolder($this->_base . '/folder/sub');

    $folder2 = new fpFolder($this->_base . '/folder2');

    $folder->copy($folder2);

    $this->assertTrue($folder2->has('sub'));

  }


  public function testMove()
  {
    $this->assertFalse(file_exists($this->_base . '/folder'));
    $this->assertFalse(file_exists($this->_base . '/folder2'));

    $folder = new fpFolder($this->_base . '/folder');
    $folder->move($this->_base . '/folder2');
    
    $this->assertFalse(file_exists($this->_base . '/folder'));
    $this->assertTrue(file_exists($this->_base . '/folder2'));

  }

  public function testTruncate()
  {
    $this->assertFalse(file_exists($this->_base . '/folder'));

    $folder = new fpFolder($this->_base . '/folder');
    $sub = new fpFolder($this->_base . '/folder/sub');
    
    $this->assertTrue(file_exists($this->_base . '/folder/sub'));
    
    $folder->truncate();
    
    $this->assertFalse(file_exists($this->_base . '/folder/sub')); 
  } 
}