<?php
namespace App;
/**
 *
 */
class Repository
{
  private $path;

  function __construct($path)
  {
    $this->path = $path;
  }

  private function get_data()
  {
    $data = file($this->path);
    $decodedData = array_map(function ($user) {
      return json_decode($user);
    }, $data);
    return $decodedData;
  }

  public function save($data)
  {
    $data = json_encode($data);
    return(file_put_contents($this->path, $data, FILE_APPEND));
  }

  public function all()
  {
    return $this->data();
  }

  public function find($key, $value)
  {
    $data = $this->data();
    $result = array_filter($data, function($element) use ($key, $value) {
      return $element[$key] === $value;
    });
  }
}

?>
