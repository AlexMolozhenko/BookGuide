
const DIRECTION_PAGE_BACK ='back' ;
const DIRECTION_PAGE_FORWARD ='forward' ;
//close authorTable (start)////////////////////

var countPage = function(countP,limit){
    let pageCount = Math.ceil(countP/limit);
        $('#input_max_count_page').val(pageCount);
    viewNumPage()
}

var currentPage = function(numberPage){
    $('#input_current_page').val(numberPage);
    viewNumPage()
}

let getAuthor  =  function(page_number= 1){

    var formdata = document.getElementById("getAuthorListForm");
    var form = new FormData(formdata);
    form.append("page",page_number);
    $.ajax({
        url: '/author/get-author-list',
        method: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        data:form,
        success: function(data){
            console.log(data);
            countPage(data['count'],data['limit_page']);
            currentPage(page_number);


            if($('.authorDiv').length > 0){
                $('.authorDiv').remove();
            }
            var data_search = data['dataSearch']['author'];
            var tbl = $('.author_table');
            var offSet = data['offset'];
            for(let tr in data_search){

                var newtr = document.createElement('tr');
                    newtr.setAttribute("class","authorDiv");

                    var data_tr = data_search[tr];

                    offSet++;
                    newtr.innerHTML +="<td>"+offSet+"</td>" ;
                    for(let val in data_tr){
                        if(val!='id'){
                            newtr.innerHTML +="<td>"+data_tr[val]+"</td>" ;
                        }
                    }
                newtr.innerHTML +="<td>(del/ed)</td>" ;
                tbl.append(newtr);
            }
        },
        error:function(data){
            console.log(data.responseText);
        },
    });
}
$(window).on('load',getAuthor());


//close authorTable (end)////////////////////

//turn page (start)//////////////////////////////

var viewNumPage = function(){
    $('.max_count_page').text('');
    $('.max_count_page').text($('#input_max_count_page').val());
    $('.current_page').text('');
    $('.current_page').text($('#input_current_page').val());
}

var turnPage = function(direction){
    var curVal = $('#input_current_page').val();
    var maxVal = $('#input_max_count_page').val();
    console.log('min-max');
    console.log(curVal+"="+maxVal);
    if(direction == 'back'){
        if(curVal > 1){
            $('#input_current_page').val(--curVal);
        }
    }
    if(direction == 'forward'){
        if(curVal != maxVal ){
            $('#input_current_page').val(++curVal);
        }
    }


    getAuthor(curVal);
    viewNumPage()
}

// $('.back_page').on('click',turnPage(DIRECTION_PAGE_BACK));
$('.back_page').on('click',function(){
    turnPage(DIRECTION_PAGE_BACK)
});
// $('.forward_page').on('click',turnPage(DIRECTION_PAGE_FORWARD));
$('.forward_page').on('click',function(){
    turnPage(DIRECTION_PAGE_FORWARD)
});

//turn page (end)//////////////////////////////


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
                    // location.reload();
                    getAuthor();
            }
            //подсвечиваем ошибки
            if(data['massage']=='error_save'){
                let error = document.createElement('div');
                error.setAttribute("class","errorMassage");
                var dataError = data['data'];
                console.log(dataError);
                for(let item in dataError){
                    $('input[name='+item+']').addClass('errorInput');
                    console.log(item);
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



