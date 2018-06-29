<?php
  require_once '../db/pdo.php';

  // if we didnt receive any POST data - send 404
  if (empty($_POST)) {
    header("HTTP/1.0 404 Not Found");
    echo 'HTTP/1.0 404 Not Found';
    exit;
  }

  // assign POST to helper
  $data = $_POST;
  
  // if projector wasnt checked
  if ( !isset($_POST['projector']) ) {
    $data['projector'] = 0;
  }
  
  // if marker board wasnt checked
  if ( !isset($_POST['marker_board']) ) {
    $data['marker_board'] = 0;
  }

  // convert date to required format
  $data['date'] = date("Y-m-d", strtotime($data['date']));

  // change time for search query
  $data['timeFrom'] = date("H:i:s", strtotime($data['timeFrom']) + 60);
  $data['timeTo'] = date("H:i:s", strtotime($data['timeTo']));

  // print_r("SELECT *
  // FROM reservations
  // WHERE DATE(date_time_start) = '".$data['date']."'
  // AND
  // (
  //   TIME(date_time_start) <= '".$data['timeFrom']."' AND TIME(date_time_end) >= '".$data['timeTo']."'
  //   OR
  //   (
  //     TIME(date_time_start) BETWEEN '".$data['timeFrom']."' AND '".$data['timeTo']."'
  //     OR
  //     TIME(date_time_end) BETWEEN '".$data['timeFrom']."' AND '".$data['timeTo']."'
  //   )
  // )");
  // die;


  // get all reservations
  $reservations = $pdo->query("
    SELECT *
    FROM reservations
    WHERE DATE(date_time_start) = '".$data['date']."'
    AND
    (
      TIME(date_time_start) <= '".$data['timeFrom']."' AND TIME(date_time_end) >= '".$data['timeTo']."'
      OR
      (
        TIME(date_time_start) BETWEEN '".$data['timeFrom']."' AND '".$data['timeTo']."'
        OR
        TIME(date_time_end) BETWEEN '".$data['timeFrom']."' AND '".$data['timeTo']."'
      )
    )  
  ")->fetchAll(PDO::FETCH_ASSOC);


  $queryPart = '';

  // get all reserved IDS
  if (count($reservations) > 0) {
    $reservedIds = [];
    foreach($reservations as $k=>$v) {
      $reservedIds[] = $v['room_id'];
    }
    $reservedIds = array_unique($reservedIds);

    //если есть резервы на время, изменяем запрос 
    $queryPart = 'WHERE id NOT IN ('.implode(',',$reservedIds).')';
  }

  $rooms = $pdo->query("
    SELECT * 
    FROM meeting_rooms
    ".$queryPart
  )->fetchAll(PDO::FETCH_ASSOC);

  // сортировка по кол-ву пойнтов
  function sortByPoints($a, $b) {
    return $b['points'] - $a['points'];
  }

  // сортировка по макс кол-ву людей
  function sortByMaxPeople($a, $b) {
    return $a['max_people'] - $b['max_people'];
  }

  $max = 0;
  foreach($rooms as $key=>$value) {
    $rooms[$key]['points'] = 0;

    // если макс кол-во людей в комнате больше или равно нужному кол-ву
    if ($value['max_people'] >= $data['people']) {
      $rooms[$key]['points'] += 1;
    }

    // если нужна маркерная доска и в комнате есть маркерная доска
    if ($value['marker_board'] == 1 && $data['marker_board'] == 1) {
      $rooms[$key]['points'] += 1;
    }

    // если нужен проектор и в комнате есть проектор
    if ($value['projector'] == 1 && $data['projector'] == 1) {
      $rooms[$key]['points'] += 1;
    }

    // если кол-во набранных пойнтов рекордное - установить новый максимум
    if ($rooms[$key]['points'] > $max) {
      $max = $rooms[$key]['points'];
    }
  }

  $bestRooms = [];
  $ordinary = [];

  // найти все комнаты с максимумом пойнтов
  foreach($rooms as $key=>$value) {
    if ($value['points'] == $max) {
      $bestRooms[] = $value;
    } else {
      $ordinary[] = $value;
    }
  }
  
  // отсортировать лидеров по кол-ву макс людей (по возрастанию)
  usort($bestRooms, 'sortByMaxPeople');

  // если кол-во людеров больше чем 1
  if (count($bestRooms) > 1) {
    // перенести всех кроме первого в "обычные"
    $i = 0;
    foreach($bestRooms as $key=>$value) {
      if ($i > 0) {
        $bestRooms[$key]['green'] = true;
        array_unshift($ordinary, $bestRooms[$key]);
        unset($bestRooms[$key]);
      }
      $i++;
    }
  }

  // отсортировать обычные комнаты по кол-ву макс людей (по возрастанию)
  //usort($ordinary, 'sortByMaxPeople');


  header("Content-Type: application/json; charset=utf8", false, 200);
  echo  json_encode(array(
    'best' => $bestRooms,
    'ordinary' => $ordinary,
    'reserved' => $reservations
  ));

?>

<?php 
//   //Создаём масив statusRooms в котором присваем статус 1, 2, 3, соответсвующим комнатам 
//     $statusRooms = [
//        //Вывести строку с масива с подходящей комнатой 
//     "greenRoom" => "1", //Свободная и самая подходящая переговорная комната по всем параметрам
//        //Вывести строку с масива с менее подходящими комнатами
//     "orangeRoom" => "2", //Свободная комната, но не подходящая под необходимые параметры (Наличие проектора/Маркерной доски/Кол-во людей)
//        //Вывести строк с масива с занятыми комнатами
//     "redRoom" => "3", //Занятые комнаты
//   ];


//   //Описываем комнату с проектором
//   if ($data['projector'] == 1 > $greenRoom):
//     echo $greenRoom { //Если комната соответствует наличию проектора добавляем её в массив greenRoom
// //проверяем свободна ли комната по времени
// //проверяем базу 

//     //timeFrom = if true - комната свободна для бронирования 
//               //if false - комната занята на время бронирования
//     //timeTo =   if true - комната свободна для бронирования 
//               //if false - комната занята на время бронирования
//     elseif($timeFrom = true && $timeTo = true): //Если по врмени комната свободна добавляем её в массив greenRoom
//       echo $greenRoom; //Выводим масив свободных комнат
//        elseif($timeFrom = false && $timeTo = false): //Добавляем в массив занятых комнат и выводим
//          echo $redRoom; //Выводим массив занятых комнат
//       else:
//         //Если комната занята выводим уведомление о том, что комната соответвующая запросу не найдена
//         //а так же, выводим статус всех комнат и надпись о том что соответствующая комната не найдена
//       echo $statusRoom." Комната соответсвтующая запросу не найдена ".;
//     endif;

//   }

//         if ($data['projector'] == 1) {
//           echo $greenRoom;
//         } 
//         elseif ($timeFrom = true && $timeTo = true) {
//           echo $greenRoom;
//         } 
//         elseif ($timeFrom = false && $timeTo = false) {
//             echo $redRoom;
//         }

//         switch ($i) {
//             case 0:
//                 echo "i equals 0";
//                 break;
//             case 1:
//                 echo "i equals 1";
//                 break;
//             case 2:
//                 echo "i equals 2";
//                 break;
//         }
?>


