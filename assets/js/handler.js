$(function(){

//предварительный просмотр
$('#task-form .btn-primary').click(function(event) {

    if (checkField()) return false;

    var $that = $('#task-form'),
    formData = new FormData($that.get(0));

    $.ajax({
    url: '/addtask/view',
    type: $that.attr('method'),
    contentType: false, // важно - убираем форматирование данных по умолчанию
    processData: false, // важно - убираем преобразование строк по умолчанию
    data: formData,
    dataType: 'json',
    success: function(data){

        if(data.state == true){

          username = $('#loginform-username').val();
          email = $('#loginform-email').val();
          content = $('#loginform-content').val();

          title = username+','
                +'<a href="mailto:'+email+'">'+email+'</a>';

          html = '<p style="word-break: break-all;">'
                +'<span style="margin: 0 10px 10px 0; float: left;">'
                +'<img style="width: 150px;" src="../img/tmp/'+data.mes+'" />'
                +'</span>'+content+'</p>';

          $('#myModal .modal-body').html(html);
          $('#myModal .modal-title').html(title);
          
        } else {

          $('#myModal .modal-title').html('Ошибка');
          $('#myModal .modal-body').html(data['mes']);

        }

      }

    });


}); 


//добавление новой задачи
$('#task-form').submit(function(event) {

    var $that = $('#task-form'),
    formData = new FormData($that.get(0));

    if (checkField()) return false;

    $.ajax({
      url: '/addtask',
      type: $that.attr('method'),
      contentType: false, // важно - убираем форматирование данных по умолчанию
      processData: false, // важно - убираем преобразование строк по умолчанию
      data: formData,
      dataType: 'html',
      success: function(data){

          if(data == 'true'){
            
            alert('Задача была добавлена на сайт');

            //$that.replaceWith(json);
          } else {
            alert(data);
          }

        }

      });


    return false;

  });

});

function ValidEmail(email){
return /^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/.test(email);
}


function checkField() {

    stateError = false;

    if ($('#loginform-username').val().length <= 0) {
      $('#loginform-username').parent('div').addClass('has-error');
      stateError = true;
    } else {
      $('#loginform-username').parent('div').removeClass('has-error');
    }

    if (!ValidEmail($('#loginform-email').val())) {
      $('#loginform-email').parent('div').addClass('has-error');
      stateError = true;
    } else {
      $('#loginform-email').parent('div').removeClass('has-error');
    }

    if ($('#loginform-content').val().length <= 0) {
      $('#loginform-content').parent('div').addClass('has-error');
      stateError = true;
    } else {
      $('#loginform-content').parent('div').removeClass('has-error');
    }

    if ($('#loginform-img').val().length <= 0) {
      $('#loginform-img').parent('div').addClass('has-error');
      stateError = true;
    } else {
      $('#loginform-img').parent('div').removeClass('has-error');
    }

    return (stateError) ? true : false;

}