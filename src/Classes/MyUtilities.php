<?php

namespace Src\Classes;

class MyUtilities
{

  public function __construct(){
    $this->alphaCode = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	}

  //Generator of code
  public function generateCode(){
				var_dump("generateCode");
    $code = '';
    for($i = 0 ; $i < 4 ; $i++){
							var_dump("boucle For");
      $a = random_int(1, 26);
      $code = .$this->alphaCode[$a];
    }
    return $code;
  }
}
