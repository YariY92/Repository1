

var fname = $('.firstname');
var lname = $('.lastname'); 
var email_val = $('.email');

$("#but").click(function(e){
  e.preventDefault(); // прерываем перезагрузку страницы
  $.ajax({    // функция отпраки принятых значений
    type: 'POST',
   url: '../db.php',
    dataType: "json",
    data: {
        Firstname : fname.val(),
        Lastname : lname.val(),
        email : email_val.val()}, 
   
    success: function(result){
      alert("Thank you for your form: " + fname.val()); //в случае успеха выводим значения
    }
  });

});
