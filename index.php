<?php
    session_start();
    
    if (!$_SESSION['joined'] || $_SESSION['joined'] !== true) {
        header("Location: login.php");
    }
 ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BMS+D. Резервация переговорной комнаты, запусти своих индеейцев</title>
    <meta name="description" content="BMS+D. Резервация переговорной комнаты, календарь бронирования"/>
    <meta name="keywords" content="Зарезервировать перегорную комнату в BMS+D онлайн, впусти своих индейцев в нашу компанию"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="reserv.bmservice.com.ua/" />

    <!-- Подключение скриптов для календаря -->
    <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="bower_components/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="bower_components/toastr/toastr.min.js"></script>
    <script type="text/javascript" src="js/ru.js"></script>
    
    <!-- Подключение стилей для календаря -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" href="bower_components/toastr/toastr.min.css" />
    
    <!-- Подключение стилей для сервиса -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/custom.css" />  
</head>

<body>
<header> 
    <div class="HeaderContainer">  
        <div class="left-container">      
            <span class="LogoAut">BMS<span class="RedPlus">+</span>D. Резервация</span>
        </div>
        <div class="right-container">
            <span class="InSiDe"><?=$_SESSION['username'] ?></span> <img class="miniImg" src="img/reds.svg" alt="Индеец в системе">
        </div>
    </div>
</header>

<div class="main-container">
        <div class="right-container">
            <div class="fixed-calendar">
            <p class="calendar">
                <a href="#Main_calendar">Календарь<br>Общий</a>
                <img src='img/icons.png' class='calendar-icon'/>
            </p>
            <p class="calendar">
                <a href="#Cherokee_calendar">Календарь<br>Cherokee</a>
                <img src='img/icons.png' class='calendar-icon'/>
            </p>
            <p class="calendar">
                <a href="#Navajo_calendar">Календарь<br>Navajo</a>
                <img src='img/icons.png' class='calendar-icon'/>
            </p>
            <p class="calendar">
                <a href="#Aztec_calendar">Календарь<br>Aztec</a>
                <img src='img/icons.png' class='calendar-icon'/>
            </p>
            </div>
        </div>

    <div class="left-container">
        <div class="main-form-container">
            <div class='blocker'></div>
            <!-- Первая форма -->
            <form class='main-form'>
                <span class="big-letters">Подобрать переговорную:</span><br>
                <div class="DataTime">
                    <div class="DateSetup">
                        <div class="form-group" style="width:180px;">
                            <div class='input-group date' id='datetimepicker10'>
                                <input type='text' class="form-control" name='date' required autocomplete="off"/>
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="TimeSetup">
                        <span class="EnterTime">с </span>
                        <div class="form-group" style="width:160px;">
                            <div class="input-group date timepicker timepickerFrom">
                                <input type='text' class="form-control" name='timeFrom' required/>
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="TimeSetup">
                        <span class="EnterTime">до </span>
                        <div class="form-group" style="width:160px;">
                            <div class="input-group date timepicker timepickerTo">
                                <input type='text' class="form-control" name='timeTo' required/>
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <input type="image" class="Deya" src="img/reds.svg">                        
                </div>

                <div class="LiveMarkers">
                    <div class="People">
                        <span>Число индейцев</span>
                        <input class="EnterPeople" type="number" name="people" value="2" min="1" max="8" required></p>
                    </div>
                    <div class="Projector">
                        <input name="projector" type="checkbox" class="checkbox CheckProjector" id="checkbox" value='1' />
                        <label class="HideLabel w50" for="checkbox">Нужен проектор?</label>
                    </div>
                    <div class="MarkerBoard">
                        <input name="marker_board" type="checkbox" class="checkbox" id="checkbox2" value='1'/>
                        <label class="HideLabel w50" for="checkbox2">Маркерная доска?</label>
                    </div>
                    <br class='clear'/>                    
                </div>
            </form>   
        </div>                        
    </div>

    <div class='clear'></div>

    <!-- Контейнер с результатами поиска -->
    <div class="second results">
        <div class='all-reserved hidden'>
            <span class="big-letters">В это время все переговорные комнаты заняты!</span>
        </div>   

        <div class='best-options hidden'>
            <span class="big-letters">Подходящие варианты:</span>
        </div>     

        <div class="other-options hidden">
            <span class="AnotherRoom">В это же время:</span>
        </div>
    </div>
    <!-- eof результаты поиска -->
</div> 

<footer>
    <a href="bmservice.com.ua/">Сайт BMS+D</a> 
    <div class="copyright">&copy; <?php echo date("Y"); ?>. <a href="reserv.bmservice.com.ua/">Все права защищены</div></a> 
</footer>

<!-- Шаблон рекомендованой комнаты -->
<div class='room best-room-template hidden'>
    <button class="button choose reserve-button">Бронировать!</button>                    
    <span class="title">Переговорная <span class='name'></span> </span>
    <p class="description">
        Индейцев: <span class='max'></span>
        <span class='projector hidden'>, проектор</span>
        <span class='no-projector hidden'>, без проектора</span>

        <span class='marker-board hidden'>, маркерная доска</span>
        <span class='no-marker-board hidden'>, без маркерной доски</span>
    </p> 
</div>

<!-- Шаблон свободной комнаты -->
<div class="room free-room-template FreeRoom hidden">
    <span class='name'></span>
    <span> -</span>
    <button class="AnotherButton reserve-button">Бронировать</button>
    <p class='description'>
        до <span class='max'></span> индейцев
        <span class='projector hidden'>, проектор</span>
        <span class='no-projector hidden'>, без проектора</span>

        <span class='marker-board hidden'>, маркерная доска</span>
        <span class='no-marker-board hidden'>, без маркерной доски</span>
    </p> 
</div>

<!-- Шаблон зарезервированной комнаты -->
<div class="room busy-room-template BusyRoom hidden">
    <span class='name'></span>
    <span class='time'></span>
    <span class='creator'></span>
    <p class='description'>
        Индейцев: <span class='people'></span>
        <span class='projector hidden'>, нужен проектор</span>
        <span class='marker-board hidden'>, нужна маркерная доска</span>
    </p> 
</div>
<!-- Вторая форма -->
<!-- reservation form -->
<div class='reservation-form-container hidden'>
    <form class='reservation-form'>
        <!-- reservation inputs -->
        <div class='reservation-form-group'>
            <div class='reservation-text'>Организатор</div>
            <div class='reservation-input'>
                <input class="inputReserv" placeholder="Ваше имя" value="Test" name="creator" required>
            </div>
        </div>
        <div class='reservation-form-group'>
            <div class='reservation-text'>Пригласить индейца</div>
            <div class='reservation-input'>
                <input class="inputReserv" placeholder="Добавьте сотрудника" value="Test" name="guests">
            </div>
        </div>
        <div class='reservation-form-group'>
            <div class='reservation-text'>Тема встречи</div>
            <div class='reservation-input'>
                <input class="inputReserv" placeholder="Тема встречи" value="Test" name="theme" required>
            </div>
        </div>
        <!-- end of reservation input -->
        <div>
            <div class="hideTheme">
                <input name="hide_theme" type="checkbox" class="checkbox" id="checkbox3" value='1'/>
                <label class="hideTheme" for="checkbox3">Скрыть тему?</label>
            </div>
            <div class="manager">
                <input name="manager" type="checkbox" class="checkbox" id="checkbox4" value='1'/>
                <label class="manager" for="checkbox4">Помощь офис-менеджера?</label>
            </div>
        </div>
        <input type="submit" class="button save" value="Сохранить!">
        <button class="button cancel">Отменить</button>     
    </form>
</div>
<!-- end of reservation form -->

<script src="js/main.js"></script>
</body>
</html>