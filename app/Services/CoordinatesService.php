<?php


namespace App\Services;

use App\Models\Rules;

class CoordinatesService {


  public function checkInPolygon(Rules $rule, $coordenadasCliente) : bool
  {
    $coords = $this->getPolygonCoordinates($rule);

    $poligonoString = [];
    $cont = 1;
    $inicioFim = '';
    $i = 0;
    foreach ($coords as $key => $c) {
      $coordenadasItens = $coords->polygonCoordinateItem();
      $i = 0;
      foreach ($coordenadasItens as $coordenadaItem) {
        if ($cont == 1) {
          $inicioFim = $coordenadaItem['latitude'] . ' ' . $coordenadaItem['longitude'];
        }
        $cont++;
        $poligonoString[$key][] = trim($coordenadaItem['latitude']) . ' ' . trim($coordenadaItem['longitude']);
        $i++;
      }
    }

    if(isset($poligonoString[$i])) {
      $poligonoString[$i][] =  $inicioFim;
      foreach ($poligonoString as $polygon) {
        $checkInPolygon = $this->pointInPolygon($coordenadasCliente, $polygon);
        if ($checkInPolygon)
          break;
      }
    } else {
      $checkInPolygon = false;
    }
    return $checkInPolygon;
  }

  private function getPolygonCoordinates(Rules $rule)
  {
    return $rule->polygonCoordinate();
  }

  private function pointInPolygon($point, $polygon, $pointOnVertex = true)
  {
    // Transform string coordinates into arrays with x and y values
    $point = $this->pointStringToCoordinates($point);
    $vertices = [];
    foreach ($polygon as $key => $vertex) {
      $vertices[] = $this->pointStringToCoordinates($vertex);
    }

    // Check if the point sits exactly on a vertex
    if ($pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
      return true;
    }

    // Check if the point is inside the polygon or on the boundary
    $intersections = 0;
    $vertices_count = count($vertices);

    for ($i = 1; $i < $vertices_count; $i++) {
      $vertex1 = $vertices[$i - 1];
      $vertex2 = $vertices[$i];
      if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
        return true;
      }
      if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
        $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
        if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
          return true;
        }
        if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
          $intersections++;
        }
      }
    }
    // If the number of edges we passed through is odd, then it's in the polygon.
    if ($intersections % 2 != 0) {
      return true;
    } else {
      return false;
    }
  }

  private function pointOnVertex($point, $vertices)
  {
    foreach ($vertices as $vertex) {
      if ($point == $vertex) {
        return true;
      }
    }
  }

  private function pointStringToCoordinates($pointString)
  {
    if ($pointString != "") {
      if (is_array($pointString))
        $pointString = $pointString[0];
      $coordinates = explode(" ", $pointString);
      return [
        "x" => $coordinates[0],
        "y" => $coordinates[1]
      ];
    } else {
      return [];
    }
  }


}