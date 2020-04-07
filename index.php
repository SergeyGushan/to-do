<?php
session_start();
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>

    $(function () {

        $('button#selected').click(sort);
        $('button#create_task_show').click(showFormAdd);
        $('button#auth').click(showFormAuth);
        $('button#input_auth').click(auth);
        $('button#input_creat_task').click(add_new_task);


        let AUTH;
        let MESSAGE;
        var PAGE_START = 1;
        let DATA;

        $(document).ready(function ()
        {
            AUTH = '<?php echo $_SESSION['auth']?>';
            statusButton();
            get_content('id');

        });

        function returnSelect()
        {
            if(AUTH === true || AUTH == 1 || AUTH === 'true')
            {
                let select = $(this).val();
                let id_task = $(this).attr('id_task');
                $.post("select_change.php", {'id': id_task, 'select': select}, function (data) {
                    alert(data);
                }, "text")
            } else {
                let message_info = {'error': ' Авторизуйтесь'};
                message(message_info);
            }
        }

        function start_page()
        {
            PAGE_START = $(this).text();
            alertContent(DATA);
        }


        function get_content(select)
        {
            if(select !== '')
            {
                $.post( "get_contents.php", {'select': select}, function(data){ DATA = data; alertContent(DATA);}, "json")
            }else{
                let message_info = {'error': ' Произошел сбой, потоврите попытку.'};
                message(message_info);
            }
        }

        function alertContent(data) {
            if(data != '') {
                let content = $('div#content');
                let newContent = '';
                let disabled;
                let completed = '';
                let performed = '';
                let num_task = 3;
                let from = (PAGE_START - 1) * num_task;
                let before = data.length;


                if (AUTH === true || AUTH == 1 || AUTH === 'true') {
                    disabled = '';
                } else {
                    disabled = 'disabled';
                }
                ;

                if(from + num_task < before) {
                    before = from + num_task;
                }

                for (let i = from; i < before; i++) {
                    if(data[i]['status'] == 1) {
                        performed = 'selected';
                        completed = '';
                    } else {
                        completed = 'selected';
                        performed = '';
                    }

                    newContent += '<div class="row" style="background-color: aliceblue; margin-top: 20px; margin-right: 2px;margin-left: 2px;  padding: 3px ">\n' +
                        '            <div class="col" style="background-color: ; min-height: 30px ">\n' +
                        '                <b>' + data[i]['name'] + '</b>\n' +
                        '            </div>\n' +
                        '            <div class="col" style="background-color: ; min-height: 30px">\n' +
                        '               <b>' + data[i]['email'] + '</b>\n' +
                        '            </div>\n' +
                        '            <div class="col" style="background-color: ; min-height: 30px">\n' +
                        '\n' +
                        '            </div>\n' +
                        '            <div class="col" style="background-color: ; min-height: 30px; padding: 2px">\n' +
                        '                <select id_task = "'+ data[i]['id'] +'" id="select_change" id="status" class="custom-select mr-sm-2"' + disabled + '>\n' +
                        '                    <option ' + completed + ' value="completed">Выполнено</option>\n' +
                        '                    <option ' + performed + ' value="performed">Выполняется</option>\n' +
                        '                </select>\n' +
                        '            </div>\n' +
                        '            <div class="w-100"></div>\n' +
                        '            <div class="col" style="background-color:; margin-top: 0px; min-height: 200; border-top: 1px solid lightgrey; padding-top: 2px; padding-left: 20px;" >\n' +
                        '              <i>' + data[i]['task'] + '</i>\n' +
                        '            </div>\n' +
                        '       </div>';
                }

                newContent += '<nav aria-label="Page navigation example" style="margin-top: 10px" ">\n' +
                    '  <ul class="pagination justify-content-center">\n';

                if (data.length > num_task) {
                    for (let i = 1; i <= Math.ceil(data.length / num_task); i++) {
                        newContent += '<li class="page-item""><a class="page-link" href="#">' + i + '</a></li>';
                    }
                    newContent += '  </ul>\n' + '</nav></div>';
                }
                content.html([newContent]);
                $('a.page-link').click(start_page);
                $('select#select_change').change(returnSelect);
            }
        }

        function add_new_task()
        {
            let name = $('input#name').val();
            let email = $('input#email').val();
            let task = $('textarea#task').val();

            if(name != '' && email != '' && task != '') {
                let reg = /^([A-Za-z0-9_\-.])+\@([A-Za-z0-9_\-.])+\.([A-Za-z]{2,4})$/;
                if (reg.test(email) === false) {
                    let message_info = {'error': 'Не коректный email'};
                    message(message_info);
                } else {
                    let data = {'name': name, 'email': email, 'task': task};
                    $.post("add_task.php", data, function (data) {
                        AUTH = data['auth'];
                        MESSAGE = data['message'];
                        message(MESSAGE);
                    }, "json")

                }
            } else {
                let message_info = {'error': 'Заполните все поля'};
                message(message_info);
            }

        }

        function statusButton()
        {
           let button_auth = $('button.auth');
            if(AUTH === true || AUTH == 1 || AUTH === 'true')
            {
                $('select#status').removeAttr('disabled');
                if(button_auth.attr('id') === 'auth')
                {
                    button_auth.attr('id', 'log_in').text('Выйти');
                    $('button#log_in').unbind('click').click(function () {location.href = 'log_out.php';});
                }
            }
        }

        function message(message)
        {
            let message_status =  $('#message_status');
            let message_text = $('div#message');
            for(let key in message)
            {
                if(key === 'error')
                {
                    message_status.slideDown(200);
                    message_text.attr('style', 'padding: 5px; text-align: center; background-color: lightcoral; ').text([message[key]]);
                }

                if(key === 'ok')
                {
                    message_status.slideDown(200);
                    message_text.attr('style','padding:5px; text-align: center; background-color: lightgreen ;' ).text([message[key]]);
                    $('div#form_creat_task').slideUp(1000);
                    $('div#form_auth').slideUp(1000);
                    $('button#create_task_show').text('Добавить задачу');
                    $('input#name').val('');
                    $('input#email').val('');
                    $('textarea#task').val('');
                    get_content('id');

                }
                message_status.delay(5000).slideUp(1000);
            }
        }

        function  auth()
        {
            let login = $('input#login').val();
            let password = $('input#password').val();
            if(login !== '' && password !== '')
            {
                let data = {'login': login, 'password': password};
                $.post( "auth.php", data, function( data ) {
                     AUTH = data['auth'];
                     MESSAGE = data['message'];
                     message(MESSAGE);
                    statusButton();
                }, "json")

            } else {
                let message_info = {'error': 'Заполните все поля'};
                message(message_info);
            }

        }

        function sort()
        {
           let select = $('select#sort').val();
           console.log(select);
           get_content(select);
        }

        function showFormAdd()
        {
            $('div#form_auth').attr('style','display: none');
            $('div#form_creat_task').slideToggle(700);
        }

        function showFormAuth()
        {
            $('div#form_creat_task').attr('style','display: none');
            $('div#form_auth').slideToggle(700);
        }
    });

</script>

<div class="container" style="border: solid lightgrey;  min-height: 100%; width: 800px">
    <div class="row" style="background-color: aliceblue; min-height: 60px" >
        <div class="col-sm-5" style="padding: 10px; padding-left: 20px">
            <button id="create_task_show" class="btn btn-outline-primary">Добавить задачу</button>
        </div>
        <div class="col-sm-">
        </div>
        <div class="col-sm text-right" style="padding: 10px; padding-right: 20px">
            <button id="auth" class="btn btn-outline-primary auth" >Авторизация для админастратора</button>
        </div>
    </div>
    <hr>

    <div value="0" id="form_creat_task" style="display: none;">
        <div  class="row-12" style="background-color: aliceblue; padding: 15px;">
            <div class="form-group">
                <label for="name">Введите Ваше имя:</label><input  class="form-control" id="name" type="text" placeholder="Введите Ваше имя">
                <label for="email">Введите Ваш email:</label><input  class="form-control"  id="email" type="email" placeholder="Введите Ваш email"><br>
                <textarea class="form-control" id="task" placeholder="Поставьте задачу..."></textarea>
            </div>
            <button  class="btn btn-outline-primary" id="input_creat_task" type="submit" name="create_task" vision="false">Добавить задачу</button>
            <hr>
        </div>
    </div>

    <div value="0" id="form_auth"  class="row-12" style="display: none; ">
        <div style="background-color: aliceblue; padding: 15px;">
            <div class="form-group">
                <label for="name">Логин:</label><input  class="form-control" id="login" type="text" placeholder="Введите Ваше имя">
                <label for="email">Пароль:</label><input  class="form-control"  id="password" type="password" placeholder="Введите Ваш email"><br>
            </div>
            <button  class="btn btn-outline-primary" id="input_auth" type="submit" name="create_task" vision="false">Авторизоваться</button>
            <hr>
        </div>
    </div>
    <div id="message_status" style="display: none; ">
        <div  class="row" style="min-height: 40px; margin-top: 20px; margin-right: 2px;margin-left: 2px " >
            <div id="message" class="col-sm " >
            </div>
        </div>
    </div>

    <div class="row" style="background-color: aliceblue; min-height: 60px; margin-top: 20px; margin-right: 2px;margin-left: 2px " >
        <div class="col-sm-5" style="padding: 10px 10px 10px 20px;">
            <select id="sort" class="custom-select mr-sm-2" >
                <option value="id" selected>Сортировать по:</option>
                <option value="name">Имени</option>
                <option value="status">Статус</option>
                <option value="email">Email</option>
            </select>
        </div>
        <div class="col-sm" style="padding: 10px 10px 10px 20px;">
            <button  id="selected" class="btn btn-outline-primary">Сортировать</button>
        </div>
    </div>



    <div id="content">

    </div>
</div>



