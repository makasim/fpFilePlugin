<?php

class fpZipFile extends fpFile
{
  public function copy($path)
  {
    $file = parent::copy($path);
    
    return new self($file->getPath());
  }
  
  /**
   *
   */
  public function __construct($path, $checkExtension = true)
  {
    if (strpos($path, '.zip') === false && $checkExtension) {
      throw new fpFileException('Invalid file given. The extension of the file should be `.zip`');
    }
    
    parent::__construct($path);
  }
  
  /**
   * @param string|fpFolder path to extract to
   * 
   * @return string|fpZipFile return itself
   */
  public function unzip($folder)
  {
    if (is_string($folder)) {
      $folder = new fpFolder($folder);
    }
    if (!$folder instanceof fpFolder) {
      throw new fpFileException('The first parameter should be either string path or instance of `Folder`');
    }
   
    $zip = new ZipArchive;
    $zip->open($this->_getPath());
    $zip->extractTo($folder->getPath());
    $zip->close();
    
    $folder->chmod(0666);    

    return $this;
  }
}