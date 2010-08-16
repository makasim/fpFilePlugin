<?php

class fpFile
{
  /**
   * 
   * @var string
   */  
  protected $_path = '';

  /**
   * 
   * @param string $path
   * 
   * @return void
   */
  public function __construct($path)
  {
    $this->_path = $path;
  }

  /**
   * @return string path to file
   * 
   * @return string
   */
  public function getPath()
  {
    return $this->_path;
  }
  
  /**
   * @return fpFolder
   */
  public function getFolder()
  {
    return new fpFolder(dirname($this->getPath()));
  }

  /**
   * 
   * @param string $path to copy to
   * 
   * @throws fpFileException if file is not exist any more
   * @throws fpFileException if the file under the target pass already exist
   * 
   * @return fpFile for the copy of the file
   */
  public function copy($path)
  {
    if(file_exists($path)) {
      throw new fpFileException($path . '; File already exists');
    }
    
    if (!is_dir(dirname($path))) {
      new fpFolder(dirname($path));
    }  
    
    copy($this->_getPath(), $path);
    
    return new self($path);
  }

  /**
   * The function change the 
   * 
   * @param string $path
   * 
   * @throws fpFileException if file is not exist any more
   * @throws fpFileException if the file under the target pass already exist
   * 
   * @return File itself poited to the new location of the file
   */
  public function move($path)
  {
    if(file_exists($path)) {
      throw new fpFileException($path . '; Already exists');
    }

    rename($this->_getPath(), $path);
    $this->_path = $path;
    
    return $this;
  }

  /**
   * 
   * @return fpFile
   */
  public function remove()
  {
    if ($this->exists()) {
      unlink($this->getPath());
    }
    
    return $this;
  }

  /**
   * @return bool whether the file exist or not. 
   */
  public function exists()
  {
    return file_exists($this->_path);
  }
  
  /**
   * 
   * @param mixed $permission
   * 
   * @return File
   */
  public function chmod($permission)
  {
    @chmod($this->getPath(), $permission);
    
    return $this;
  }
  
  protected function _getPath()
  {
    if (!$this->exists()) {
      throw new fpFileException('The file `'.$this->_path.'` is not exist any more. Maybe you removed it');
    }
    
    return $this->_path;
  }
  
  public function __toString()
  {
    return $this->getPath();
  }
}