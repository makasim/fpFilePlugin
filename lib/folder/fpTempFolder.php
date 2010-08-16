<?php

class fpTempFolder extends fpFolder
{
  public function __construct()
  {    
    $prj_tmp = new fpFolder(sfConfig::get('app_temp_dir')); 
    $prj_tmp->chmod(0777);
    
    parent::__construct($prj_tmp.'/'.md5(time(). rand() . time()));
    $this->chmod(0777);
  }
  
  public function __destruct()
  {
    if ($this->exists()) $this->remove();
  }
}