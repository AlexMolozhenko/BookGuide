
const DIRECTION_PAGE_BACK ='back' ;
const DIRECTION_PAGE_FORWARD ='forward' ;


//sort by surname (start)////////////////
let sort_by_surname = function(){
    if(sessionStorage.getItem("sort_by_surname")==='true'){
        sessionStorage.setItem("sort_by_surname", "false");
        $('.sort_by_surname').css("background-color","")
    }else{
        sessionStorage.setItem("sort_by_surname", "true");
        $('.sort_by_surname').css("background-color","rgba(209,255,255,0.86)");
    }
}

$(window).on('load',function(){
   if(sessionStorage.getItem("sort_by_surname")==='true'){
       $('.sort_by_surname').css("background-color","rgba(209,255,255,0.86)");
   }
})

let sort = function(){
    sort_by_surname()
    getAuthor()
}
$('.sort_by_surname').on('click',sort);

//sort by surname (end)////////////////

//search by name/surname (start)///////////
let searchByNameSurname = function(query,page_number= 1){
    let sort_by;
    if(sessionStorage.getItem('sort_by_surname')==='true'){
        sort_by = 'surname';
    }else{
        sort_by = 'id';
    }

    var formdata = document.getElementById("getAuthorListForm");
    var form = new FormData(formdata);
    form.append("page",page_number);
    form.append("sort_by",sort_by);
    form.append("query",query);
    $.ajax({
        url: '/author/search-author',
        method: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        data:form,
        success: function(data){
            countPage(data['count'],data['limit_page']);
            currentPage(page_number);

            generateTable(data['dataSearch']['author'],data['offset']);
        },
        error:function(data){
            console.log(data.responseText);
        },
    });
}
$('.button_search_by_name_surname').on('click',function(){
    if($('#search_by_name_surname').val() != ''){
        searchByNameSurname($('#search_by_name_surname').val());
    }else{
        $('#search_by_name_surname').addClass('errorInput');
        setTimeout(function(){
            $('#search_by_name_surname').removeClass('errorInput');
        },1000);
        getAuthor();
    }
});
//search by name/surname (end)///////////

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

let generateTable = function(data_author,data_offset){
    if($('.authorDiv').length > 0){
        $('.authorDiv').remove();
    }
    var data_search = data_author;
    var tbl = $('.author_table');
    var offSet = data_offset;
    for(let tr in data_search){
        var newtr = document.createElement('tr');
        newtr.setAttribute("class","authorDiv");
        var data_tr = data_search[tr];
        offSet++;
        newtr.innerHTML +="<td>"+offSet+"</td>" ;
        let idAuthor;
        for(let val in data_tr){
            if(val!='id'){
                newtr.innerHTML +="<td>"+data_tr[val]+"</td>" ;

            }else{
                idAuthor=data_tr[val];
            }
        }
         // newtr.innerHTML +=`<td><button type="button" id="`+idAuthor+`" class="editAuthor btn btn-warning">Edit</button><button type="button" id="`+idAuthor+`" class="deleteAuthor btn btn-danger">Delete</button></td>`;
         newtr.innerHTML +=`<td><p id="`+idAuthor+`" data-action="edit"  class="editAuthor btn btn-warning">Edit</p><p data-action="delete" id="`+idAuthor+`" class="deleteAuthor btn btn-danger">Delete</p></td>`;
        tbl.append(newtr);
    }
}

//edit/delete author (start) ////////////

$('.author_table').on('click',function (e){
    let elem =  e.target;
    var formdata = document.getElementById("getAuthorListForm");
    var form = new FormData(formdata);
    form.append("id",elem.id);
    if(elem.dataset.action === 'edit'){
        $.ajax({
            url: '/author/get-author',
            method: 'post',
            dataType: 'json',
            contentType: false,
            processData: false,
            data:form,
            success: function(data){
                console.log(data);
                activeModal();
                $('input[name="id"]').val(data['id']);
                $('input[name="name"]').val(data['name']);
                $('input[name="patronymic"]').val(data['patronymic']);
                $('input[name="surname"]').val(data['surname']);
            },
            error:function(data){
                console.log(data.responseText);
            },
        });
    }
    if(elem.dataset.action === 'delete'){
        $.ajax({
            url: '/author/delete',
            method: 'post',
            dataType: 'json',
            contentType: false,
            processData: false,
            data:form,
            success: function(data){
                if(data == true){
                    getAuthor();
                }
            },
            error:function(data){
                console.log(data.responseText);
            },
        });
    }
});
//edit/delete  author (end) ////////////



let getAuthor  =  function(page_number= 1){
    let sort_by;
    if(sessionStorage.getItem('sort_by_surname')==='true'){
        sort_by = 'surname';
    }else{
        sort_by = 'id';
    }

    var formdata = document.getElementById("getAuthorListForm");
    var form = new FormData(formdata);
    form.append("page",page_number);
    form.append("sort_by",sort_by);
    $.ajax({
        url: '/author/get-author-list',
        method: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        data:form,
        success: function(data){
            countPage(data['count'],data['limit_page']);
            currentPage(page_number);

            generateTable(data['dataSearch']['author'],data['offset']);

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
    var currentVal = $('#input_current_page').val();
    var maxVal = $('#input_max_count_page').val();

    if(direction == 'back'){
        if(currentVal > 1){
            $('#input_current_page').val(--currentVal);
        }
    }
    if(direction == 'forward'){
        if(currentVal != maxVal ){
            $('#input_current_page').val(++currentVal);
        }
    }

    if($('#search_by_name_surname').val() != ''){
        searchByNameSurname($('#search_by_name_surname').val(),currentVal);
    }else{
        getAuthor(currentVal);
    }

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
    let url;
    if(form.get('id')==''){
        url = '/author/create';
    }else{
        url = '/author/update';
    }
    $.ajax({
        url: url,
        method: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        data:form,
        success: function(data){

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
                        resetFormAuthor();
                        $('.errorInput').removeClass('errorInput');
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
                for(let item in dataError){
                    $('input[name='+item+']').addClass('errorInput');
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

// author form (start)//////////////////////////
var activeModal = function (){
      if($('.windowModal').css("display")== 'none') {
          $('.windowModal').css("display","");
      }
}
 $('.addAuthor').on('click',activeModal);

let resetFormAuthor = function(){
    $('#author_form')[0].reset();
    $('input[name="id"]').val('');
}
var closeModal = function(){
    resetFormAuthor();
    $('.errorMassage').remove();
    $('.errorInput').removeClass('errorInput');
    $('.windowModal').css("display","none");
}
$('.closeAuthorForm').on('click',closeModal);
// author form (end)//////////////////////////


