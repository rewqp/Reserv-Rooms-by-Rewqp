// инициализировать дату и время в инпутах
var initDateTime = function() {
    $('#datetimepicker10').datetimepicker({
        viewMode: 'days',
        format: 'DD-MM-YYYY', //YYYY-MM-DD изменён формат даты
        locale: 'ru',
        defaultDate: new Date(),
        minDate: new Date()
    });

    $('.timepickerFrom').datetimepicker({
        format: 'HH:mm',
        defaultDate: moment().add('1','hour').startOf('hour'),
        stepping: 15
    });

    $('.timepickerTo').datetimepicker({
        format: 'HH:mm',      
        minDate: moment().add('1', 'hour').startOf('hour').add('15', 'minutes'),
        stepping: 15
    });

    $('.timepickerFrom').on('dp.change', function(e) {
        $('.timepickerTo').data("DateTimePicker").minDate(moment(e.date).add('15', 'minutes'));
    })
};

// закинуть в инпуты значения даты и времени по-умолчанию
var resetDateTime = function() {
    $('#datetimepicker10 input').val(moment().format('DD-MM-YYYY'));
    $('.timepickerFrom input').val(moment().add('1','hour').startOf('hour').format('HH:mm'));
    $('.timepickerTo input').val(moment().add('1','hour').startOf('hour').add('15', 'minutes').format('HH:mm'));
};

/**
 * Обработать лучшие и обычные комнаты
 * @param {*} data массив объектов
 * @param {*} container куда ложить результаты
 * @param {*} template шаблон описания
 */
var fetchBestAndNormalRooms = function(data, container, template) {
    if (data.length || data[0] !== undefined) {
        var newContainer;

        $(container).removeClass('hidden');

        for (var i in data) {
            newContainer = $(template).first().clone();

            // убрать невидимость
            newContainer.removeClass('hidden');

            // добавить класс "сгенерировано"
            newContainer.addClass('generated');

            // закинуть макс кол-во людей
            newContainer.find('.max').html(data[i].max_people);

            // закинуть название
            newContainer.find('.name').html(data[i].name);

            // добавить к кнопке данные о комнате
            newContainer.find('.reserve-button').data('room-id', data[i].id).data('room-name', data[i].name);
            
            // если комната подходящая как альтернатива
            if (data[i]['green'] == true) {
                newContainer.addClass('green');
            } else {
                newContainer.removeClass('green');
            }

            // если есть доска - показать
            if (data[i].marker_board == 1) {
                newContainer.find('.marker-board').removeClass('hidden');
                newContainer.find('.no-marker-board').addClass('hidden');
            } 
            // иначе - показать, что нет
            else if (data[i].marker_board == 0) {
                newContainer.find('.marker-board').addClass('hidden');
                newContainer.find('.no-marker-board').removeClass('hidden');
            }

            // если есть проектор - показать
            if (data[i].projector == 1) {
                newContainer.find('.projector').removeClass('hidden');
                newContainer.find('.no-projector').addClass('hidden');
            } 
            // иначе - показать, что нет
            else if (data[i].projector == 0) {
                newContainer.find('.projector').addClass('hidden');
                newContainer.find('.no-projector').removeClass('hidden');
            }

            // добавить в хтмл сгенерированный контейнер
            $(container).append(newContainer);
        }
    }
};

/**
 * Забронированные
 */
var printReservedRooms = function(data) {
    if (data.length || data[0] !== undefined) {
        var newContainer;

        $('.other-options').removeClass('hidden');

        for (var i in data) {
            newContainer = $('.BusyRoom').first().clone();

            // убрать невидимость
            newContainer.removeClass('hidden');

            // добавить класс "сгенерировано"
            newContainer.addClass('generated');

            // закинуть название
            newContainer.find('.name').html(data[i].room_name);

            // создатель
            newContainer.find('.creator').html(data[i].creator);

            // на сколько человек
            newContainer.find('.people').html(data[i].people);

            // время
            var timeFrom = moment(data[i].date_time_start).format('HH:mm'),
                timeTo   = moment(data[i].date_time_end).format('HH:mm');

            newContainer.find('.time').html(timeFrom + '-' + timeTo);           
            
            // нужна ли доска
            if (data[i].need_board) {
                newContainer.find('.marker-board').removeClass('hidden');
            }

            // если есть проектор - показать
            if (data[i].need_projector) {
                newContainer.find('.projector').removeClass('hidden');
            }

            // добавить в хтмл сгенерированный контейнер
            $('.other-options').append(newContainer);
        }
    }
};

// спрятать все ненужное и удалить сгенерированное
var reset = function() {
    $('.best-options').addClass('hidden');
    $('.other-options').addClass('hidden');
    $('.all-reserved').addClass('hidden');
    $('.generated').remove();
    $('.generatedForm').remove();
};

var initFormListeners = function(parent, roomId, roomName) {
    // Форма сохранения брони
    parent.find('.reservation-form').on('submit', function(event) {
        event.preventDefault(); //Отменяет действие по дефолту

        // form to string
        var body = $(this).serialize();

        var result = searchFormData + '&' + body + '&roomId=' + roomId + '&roomName=' + roomName;

        // send request to backend
        $.post("server/add.php", result, function(data) {
            if (data.message == 'success') {
                toastr.success('Комната успешно забронирована!', 'Успех')
            }

            // удалить все сгенерированные формы и результаты
            reset();

            // сбросить значения главной формы
            $('.main-form').trigger("reset");

            // закинуть значения по-умолчанию в инпуты
            resetDateTime();

            // спрятать блокер
            $('.blocker').fadeOut();
        });
    });

    // при нажатии на кнопку "отмена"
    parent.find('.cancel').on('click', function(e) {
        e.preventDefault();
        
        // спрятать блокер
        $('.blocker').fadeOut();

        // убрать форму
        $('.generatedForm').fadeOut(function() {
            $('.generatedForm').remove();
        })
    });
};

var searchFormData;

var initButtonsListeners = function() {
    $('.reserve-button').on('click', function(e) {

        // родительский элемент кнопки
        var parent = $(this).parent();

        // если рядом с кнопкой уже есть форма - выйти
        if (parent.find('.reservation-form-container').length) {
            return;
        }

        // удалить все прежние формы
        $('.generatedForm').remove();

        // клонировать нужную форму
        var newForm = $('.reservation-form-container').first().clone();

        // сделать видимой
        newForm.removeClass('hidden').hide();
        
        // добавить класс "сгенерировано кодом"
        newForm.addClass('generatedForm');

        // закинуть форму в родительский элемент кнопки
        parent.append(newForm);

        newForm.slideDown();

        // навешать слушателей на форму
        initFormListeners(parent, $(this).data('room-id'), $(this).data('room-name'));

        // показать блокер
        $('.blocker').fadeIn();
    });
};

$(function() {
    initDateTime();

    // Форма поиска доступных комнат
    $('.main-form').on('submit', function(event) {
        
        event.preventDefault(); //Отменяет действие по дефолту

        // спрятать все ненужное и удалить сгенерированное
        reset();

        // form to string
        searchFormData = $('.main-form').serialize();

        // send request to backend
        $.post("server/search.php", searchFormData, function(data) {
            //console.warn(data);

            // если пришли пустые массивы лучших и обычных комнат
            if (data.best[0] === undefined && data.ordinary[0] === undefined) {
                // показать контейнер "все заняты"
                $('.all-reserved').removeClass('hidden');
            }            

            // обработать лучшие
            fetchBestAndNormalRooms(data.best, '.best-options', '.best-room-template');
            
            // обработать обычные
            fetchBestAndNormalRooms(data.ordinary, '.other-options', '.free-room-template');

            // обработать забронированные
            printReservedRooms(data.reserved);

            // навешать на кнопки слушателей события
            initButtonsListeners();
        });
    });
});