<?php

class fpNotExistFile extends fpFile
{
  public function __construct() {}
  
  public function exists()
  {
    return false;
  }
  
  public function getPath()
  {
    $this->_getPath(); 
  }
  
  public function remove()
  {
    return $this;
  }
  
  public function chmod($permission)
  {
    return $this;
  }
  
  protected function _getPath()
  {
    throw new fpFileException('This instance `'.__CLASS__.'` used in case of file does not exists. So any methods accept exists will throw this error.'); 
  }
}