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

    // if hide theme wasnt checked
    if ( !isset($_POST['hide_theme']) ) {
        $data['hide_theme'] = 0;
    }

    // if manager wasnt checked
    if ( !isset($_POST['manager']) ) {
        $data['manager'] = 0;
    }

    /**
     * Отправить нужные уведомления
     */
     // если нужен проектор
    if ($data['projector'] == 1) {
        $to      = 'example@gmail.com';
        $subject = 'Необходим проектор в переговорную Aztec';

        $message = '';
        $message .= 'Необходим проектор в переговорную Aztec<br />
                     Комната забронирована на: ' . $data['timeFrom'] . '<br />';
        $message .= 'Бронь установлена до: ' . $data['timeTo'] . '<br /><br />';
        $message .= 'Организатор встречи: ' . $data['creator'];

        $headers = 'From: example2@gmail.com' . "\r\n" .
                    'Reply-To: example3@gmail.com' . "\r\n" .
                    'Content-type: text/html; charset=utf-8' .
                    'X-Mailer: PHP/' . phpversion();
        //mail($to, $subject, $message, $headers);
    }

    // если нужен менеджер
    if ($data['manager'] == 1) {
        
        // по-умолчанию отправляем
        $to      = 'example4@gmail.com';

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // если комната Navajo
        if ($data['roomName'] == 'Navajo') {
            $to = 'anotheremail@asds';
        }
        
        $subject = 'Необходим менеджер в переговорную ' . $data['roomName'];

        $message = '';
        $message .= 'Необходим офис-менеджер в переговорную: ' . $data['roomName'] . '<br /><br />';
        $message .= 'Комната забронирована на: ' . $data['timeFrom'] . '<br />';
        $message .= 'Бронь установлена до: ' . $data['timeTo'] . '<br />';
        $message .= 'Организатор встречи: ' . $data['creator'] . '<br />';
        $message .= 'Количество людей: ' . $data['people'];

        $headers = 'From: example5a@gmail.com' . "\r\n" .
                    'Reply-To: example6@gmail.com' . "\r\n" .
                    'Content-type: text/html; charset=utf-8' .
                    'X-Mailer: PHP/' . phpversion();
        //mail($to, $subject, $message, $headers);
    }

    // print_r($data);
    // die;

    $timeFrom = date("Y-m-d H:i:s", strtotime($data['date'] . ' ' . $data['timeFrom']));
    $timeTo   = date("Y-m-d H:i:s", strtotime($data['date'] . ' ' . $data['timeTo']));
        
    $addQuery = $pdo->query("INSERT INTO reservations 
        (room_id,
        room_name,
        date_time_start,
        date_time_end,
        creator,
        guests,
        people,
        theme,
        hide_theme,
        need_projector,
        need_board,
        manager) 
        VALUES 
        (
        '$data[roomId]',
        '$data[roomName]',
        '$timeFrom',
        '$timeTo',
        '$data[creator]',
        '$data[guests]',
        '$data[people]',
        '$data[theme]',
        '$data[hide_theme]',
        '$data[projector]',
        '$data[marker_board]',
        '$data[manager]'
        )");

    header("Content-Type: application/json; charset=utf8", false, 200);

    if ($addQuery) {
        echo  json_encode(array('message' => 'success'), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    } else {
        echo  json_encode(array('message' => 'error'), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);        
    }
?>