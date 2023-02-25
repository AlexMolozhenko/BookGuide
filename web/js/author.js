
//Save author (start)//////////////////////////////
var renderCreateForm = function(){

    var formdata = document.getElementById("author_form");
    var form = new FormData(formdata);
    console.log(form.values());
    $.ajax({
        url: '/author/create',
        method: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        data:form,
        success: function(data){
            console.log(data);

            if(data['massage']=='successful_save'){
                let succesBody = document.createElement('div');
                succesBody.setAttribute("class","succesMsg");
                let msg = document.createElement('p');
                msg.innerText="successful save";
                succesBody.append(msg);
                $('.authorFormMassage').append(succesBody);
                $('.succesMsg').css("background-color","rgba(162,255,140,0.86)");

                setTimeout(function(){
                    $('.succesMsg').remove();
                    $('#author_form')[0].reset();
                    $('.windowModal').css("display","none");
                },4000);

            }




            //подсвечиваем ошибки
            if(data['massage']=='error_save'){
                let error = document.createElement('div');
                error.setAttribute("class","errorMassage");
                var dataError = data['data'];
                console.log(dataError);
                var inputErrorName=[];
                for(let item in dataError){
                    $('input[name='+item+']').addClass('errorInput');
                    console.log(item);
                    inputErrorName+= [item];
                    let p = document.createElement('p');
                    p.append(dataError[item]);
                    error.append(p) ;
                }
                let authorFormError = $('.authorFormMassage');
                    authorFormError.append(error);
                $('.errorMassage').css("background-color","rgba(227,224,65,0.39)");


                //скрытие подсказок об ошибках
                setTimeout(function(){
                    $('.errorMassage').remove();
                },4000);
                $('.errorInput').on('click',function(e){
                    $(this).removeClass('errorInput');
                });
            }
        },
        error: function(error){
            console.log(error.responseText);
        }
    });

}
$('.saveAuth').on('click',renderCreateForm);
////Save author (end) //////////////////////////////

//add author form (start)//////////////////////////
var activeModal = function (){
      if($('.windowModal').css("display")== 'none') {
          $('.windowModal').css("display","");
      }
}
 $('.addAuthor').on('click',activeModal);
//add author form (end)//////////////////////////

//close erroPanel (start)////////////////////



//close erroPanel (end)////////////////////


