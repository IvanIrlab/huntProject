<?php

namespace Src\Classes;

class Utilities
{
  public function __construct(){
    $this->alphaCode = ['A','B','C','D','E','F','G','H','I','J','K','L','M',
    'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	}

  //Generator of code
  public function generateCode(){
    for($i = 0 ; $i < 4 ; $i++){
      $a = rand(0, 25);
      $code .= $this->alphaCode[$a];
    }
    return $code;
  }
}
