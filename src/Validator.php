<?php
namespace App;
class Validator
{
  private $data;
  private $keys;

  function __construct($data, $keys)
  {
    $this->data = $data;
    $this->keys = $keys;
  }

  public function validate ()
  {
    $result = [];
    $data = $this->data;
    foreach ($keys as $value){
      if ($data[$value]) {
        $result[$value] = "can't be blank";
      }
    }
    return($result);
  }

}

?>
