<?php

class fpFolder extends fpFile implements IteratorAggregate
{
  public function __construct($path)
  {
    $this->_path = $path;
    $this->create();
  }
  
  /**
   * @return bool whether the file exist or not. 
   */
  public function exists()
  {
    return file_exists($this->_path) && is_dir($this->_path);
  }

  /**
   * @return Folder
   */
  public function create()
  {
    if(!is_dir($this->_path))
    {
      if(!mkdir($this->_path, 0777, true))
      {
        throw new fpFileException($this->_path . '; Does not create folder' );
      }
    }

    return $this;
  }

  /**
   * @param string|Folder
   * 
   * @throws Excpetion if First parameter is invalid
   * 
   * @return Folder
   */
  public function copy($target)
  {
    if (is_string($target)) {
      $target = new fpFolder($target); 
    }
    if (!$target instanceof fpFolder) {
      throw new fpFileException('Invalid first parameter should be either string path or instance of `Folder`'); 
    }

    if ($dh = opendir($this->_getPath()))
    {
      while (($file = readdir($dh)) !== false)
      {
        if (in_array($file, array('.', '..', '.svn'))) {
          continue;
        }
        
        $filePath = $this->_getPath() . '/' . $file;
        if(is_dir($filePath)) {
          $folder = new fpFolder($filePath);
          $folder->copy($target . '/' . $file);
        } else {
          $aFile = new fpFile( $filePath );
          $aFile->copy($target . '/' . $file);
        }
      }
      closedir($dh);
    }

    return $this;
  }

  /**
   * @return Folder
   */
  public function remove()
  {
    $this->truncate();
    rmdir($this->_getPath());

    return $this;
  }

  /**
   * @return Folder
   */
  public function truncate()
  {
    if(!$this->exists()) {
      throw new fpFileException($this->_getPath() . '; Folder not exists');
    }

    $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($iterator as $file)
    {
      //aviod deleteing .svn folder and its subfolders
      if (strpos($file->getPathname(), '.svn') !== false) {
        continue;
      }
      if ($file->isDir() && in_array($file->getFilename(), array('.', '..'))) {
        continue;
      }

      $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    }

    return $this;
  }

  /**
   *
   * @param mixed $permission
   *
   * @return Folder
   */
  public function chmod($permission, $recursive = true)
  {
    $R = $recursive ? ' -R ' : '';
    if (stripos(PHP_OS, 'win') === false) {
      $result = '';
      system("chmod {$R} a+rwx {$this->_getPath()} 2> /dev/null", $result);
    }
    
    return $this;
  }

  public function has($name)
  {
    return file_exists($this->_getPath() . '/' . $name);
  }
  
  /**
   * @return RecursiveDirectoryIterator
   */
  public function getIterator()
  {
    return new RecursiveDirectoryIterator($this->_getPath());
  }
}