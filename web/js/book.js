const DIRECTION_PAGE_BACK ='back' ;
const DIRECTION_PAGE_FORWARD ='forward' ;
const fileTypes = [
    "image/apng",
    "image/jpeg",
    "image/pjpeg",
    "image/png",
];

var photoInput = $('#photoInp');
var previewPhoto = $('.previewPhoto');
photoInput.on('change', updateImageDisplay);
function updateImageDisplay() {
    if(previewPhoto.length >0){
        previewPhoto.empty();
    }
    const curFiles = photoInput.prop('files')[0];
    if (curFiles.length === 0) {
        const para = document.createElement('p');
        para.textContent = 'No photo currently selected for upload';
        previewPhoto.append(para);
    } else {
        const list = document.createElement('ol');
        previewPhoto.append(list);
            const listItem = document.createElement('li');
            const para = document.createElement('p');
            if (validFileType(curFiles)) {
                para.textContent = `File name ${curFiles.name}, file size ${returnFileSize(curFiles.size)}.`;
                const image = document.createElement('img');
                image.src = URL.createObjectURL(curFiles);

                listItem.append(image);
                listItem.append(para);
            } else {
                para.textContent = `File name ${curFiles.name}: Not a valid file type. Update your selection.`;
                listItem.append(para);
            }

            list.append(listItem);
    }
}
let sort_by_title = function(){
    if(sessionStorage.getItem("sort_by_title")==='true'){
        sessionStorage.setItem("sort_by_title", "false");
        $('.sort_by_title').css("background-color","")
    }else{
        sessionStorage.setItem("sort_by_title", "true");
        $('.sort_by_title').css("background-color","rgba(209,255,255,0.86)");
    }
}

$(window).on('load',function(){
    if(sessionStorage.getItem("sort_by_title")==='true'){
        $('.sort_by_title').css("background-color","rgba(209,255,255,0.86)");
    }
})

let sort = function(){
    sort_by_title();
    getBook();
}
$('.sort_by_title').on('click',sort);
function validFileType(file=null) {
    let msg;
        msg= fileTypes.includes(file.type);
    return msg;
}
function returnFileSize(number) {
    if(number>2097152){
        return false;
    }else{
        if (number < 1024) {
            return `${number} bytes`;
        } else if (number >= 1024 && number < 1048576) {
            return `${(number / 1024).toFixed(1)} KB`;
        } else if (number >= 1048576) {
            return `${(number / 1048576).toFixed(1)} MB`;
        }
    }
}

//save book (start)///////////////////

var saveBook = function(){
    var bookMsg = $('.bookFormMassage');
    var formdata = document.getElementById("Book_form");
    var photo = photoInput.prop('files')[0];
    console.log(photo);
    var form = new FormData(formdata);
     let actionForm = null;
    if(photo==null){
        form.append('photo','');
        actionForm='true';
    }else{
        if(validFileType(photo) && photo.size<=2097152){
            form.append('photo',photo);
            actionForm='true';
        }else{
            actionForm='false';
            console.error('file not valid');
            let error = document.createElement('div');
            error.setAttribute("class","errorMassage");
            let p = document.createElement('p');
            p.append("Invalid file type or size exceeded");
            error.append(p) ;
            bookMsg.append(error);
            $('.errorMassage').css("background-color","rgba(227,224,65,0.39)");
            //скрытие подсказок об ошибках
            setTimeout(function(){
                $('.errorMassage').remove();
            },4000);
            $('.errorInput').on('click',function(e){
                $(this).removeClass('errorInput');
            });
        }
    }

    if(actionForm == 'true'){
        let url;
        if(form.get('action')==''){
            url = '/book/create';
        }else{
            url = '/book/update';
        }
        $.ajax({
            url: url,
            method: 'post',
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            data:form,
            success: function(data) {
                console.log(data);
                if (data['massage'] == 'successful_save') {
                    console.log(data);
                    let succesBody = document.createElement('div');
                    succesBody.setAttribute("class", "succesMsg");
                    let msg = document.createElement('p');
                    msg.innerText = "successful save";
                    succesBody.append(msg);
                    bookMsg.append(succesBody);
                    $('.succesMsg').css("background-color", "rgba(162,255,140,0.86)");
                    setTimeout(function () {
                        $('.succesMsg').remove();
                        resetFormBook();
                        $('.errorInput').removeClass('errorInput');
                        $('.windowModal').css("display", "none");
                    }, 4000);
                    // location.reload();
                    getBook();
                }
                // //подсвечиваем ошибки
                if (data['massage'] == 'error_save') {
                    console.error(data);
                    // //подсвечиваем ошибки
                    let error = document.createElement('div');
                    error.setAttribute("class", "errorMassage");
                    var dataError = data['data'];
                    for (let item in dataError) {
                        $('input[name=' + item + ']').addClass('errorInput');
                        let p = document.createElement('p');
                        p.append(dataError[item]);
                        error.append(p);
                    }
                    // let authorFormError = $('.authorFormMassage');
                    bookMsg.append(error);
                    $('.errorMassage').css("background-color", "rgba(227,224,65,0.39)");
                    //скрытие подсказок об ошибках
                    setTimeout(function () {
                        $('.errorMassage').remove();
                    }, 4000);
                    $('.errorInput').on('click', function (e) {
                        $(this).removeClass('errorInput');
                    });
                }

            },
            error: function(error){
                console.log(error.responseText);
            }
        });
    }


}
$('.saveBook').on('click',saveBook);

//save book (end)///////////////////
var generateAuthorList = function(data,update = null){
    let ul = document.createElement('ul');
    ul.setAttribute('class','listAuthor');
    for(let value of data){
        ul.innerHTML += "<li id="+value['id']+">"+value['name']+" "+value['patronymic']+" "+value['surname']+"</li>";
    }
    $('.listA').append('<div class="closeListAuthor  btn-close"></div>');
    $('.listA').append(ul);
    $('.listA').data('action','true');

    $('.closeListAuthor').on('click',closeList);

    $('.listAuthor').on('click',function(e){
        let elem = e.target;
        if($('#bookAuthid'+elem.id).id==elem.id){
        alert(true);
        }
        if(!document.getElementById('bookAuthid'+elem.id)){
            elem.style="background-color:lightcoral;";
            setTimeout(function (){
                elem.style="background-color:none;";
            },1000);
            let input = document.createElement('input');
            input.setAttribute("type","hidden");
            input.setAttribute("name","authors[]");
            input.setAttribute("value",elem.id);
            let span = document.createElement('span');
            let closeAuthor = document.createElement('span');


            for(let value of data){
                if(value['id'] == elem.id){
                    span.append(value['name']+" "+value['patronymic']+" "+value['surname']);
                    span.setAttribute("class","BookAuthor");
                    span.setAttribute("id",'bookAuthid'+value['id']);
                    closeAuthor.append('❌');
                    closeAuthor.setAttribute("class","closeAuthor btn-close");
                    closeAuthor.setAttribute("id",value['id']);
                }
            }
            $('.authors').append(span);
            $('.authors').append(closeAuthor);
            $('.authors').append(input);

            $('.closeAuthor').on('click',function(e){
                let elem = e.target;
                let bookAuthor = document.getElementById('bookAuthid'+elem.id);
                bookAuthor.style='background-color:red;';
                setTimeout(function (){
                    bookAuthor.remove();
                    elem.remove();
                },500);

            });
        }
    });
}

var getAuthorList = function($update=null){
    $.ajax({
        url: '/book/get-author-list',
        method: 'get',
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(data){
            generateAuthorList(data,$update)
        },
        error:function(data){
            console.log(data.responseText);
        },
    });
}


let closeList = function (){
    $('.listAuthor').remove();
    $('.closeListAuthor').remove();
    $('.listA').data("action","false");
}

$('.getListAuthor').on('click',function(){
     if(!$('.listAuthor').length>0) {
         getAuthorList();
     }

});


//search by name/surname (start)///////////
let searchByTitleAuthor = function(query,page_number= 1){
    let sort_by;
    if(sessionStorage.getItem('sort_by_title')==='true'){
        sort_by = 'title';
    }else{
        sort_by = 'id';
    }

    var formdata = document.getElementById("getBookListForm");
    var form = new FormData(formdata);
    form.append("page",page_number);
    form.append("sort_by",sort_by);
    form.append("query",query);
    $.ajax({
        url: '/book/search-book',
        method: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        data:form,
        success: function(data){
            countPage(data['count'],data['limit_page']);
            currentPage(page_number);
            generateTable(data['dataSearch'],data['offset']);
        },
        error:function(data){
            console.log(data.responseText);
        },
    });
}
$('.button_search_by_title_author').on('click',function(){
    if($('#search_by_title_author').val() != ''){
        searchByTitleAuthor($('#search_by_title_author').val());
    }else{
        $('#search_by_title_author').addClass('errorInput');
        setTimeout(function(){
            $('#search_by_title_author').removeClass('errorInput');
        },1000);
        getBook();
    }

});
//search by title/author (end)///////////


var countPage = function(countP,limit){
    let pageCount = Math.ceil(countP/limit);
    $('#input_max_count_page').val(pageCount);
    viewNumPage()
}

var currentPage = function(numberPage){
    $('#input_current_page').val(numberPage);
    viewNumPage()
}
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

    if($('#search_by_title_author').val() != ''){
        searchByTitleAuthor($('#search_by_title_author').val(),currentVal);
    }else{
    getBook(currentVal);
    }
    viewNumPage()
}
$('.back_page').on('click',function(){
    turnPage(DIRECTION_PAGE_BACK)
});

$('.forward_page').on('click',function(){
    turnPage(DIRECTION_PAGE_FORWARD)
});
let getBook  =  function(page_number= 1){
    let sort_by;
    if(sessionStorage.getItem('sort_by_title')==='true'){
        sort_by = 'title';
    }else{
        sort_by = 'id';
    }

    var formdata = document.getElementById("getBookListForm");
    var form = new FormData(formdata);
    form.append("page",page_number);
    form.append("sort_by",sort_by);
    $.ajax({
        url: '/book/get-book-list',
        method: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        data:form,
        success: function(data){
            console.log(data);
             countPage(data['count'],data['limit_page']);
             currentPage(page_number);
            generateTable(data['bookdata'],data['offset']);

        },
        error:function(data){
            console.log(data.responseText);
        },
    });
}
$(window).on('load',getBook());

let generateTable = function(data_search,data_offset){
    if($('.elemBookList').length > 0){
        $('.elemBookList').remove();
    }
    var offSet =  data_offset;
    console.log(data_search)
        for(let item of  data_search){

               let book = item['book'];
               let author = item['author'];
                let bookForm = document.createElement('div');
                bookForm.setAttribute("class","elemBookList");
                let photo_elem =  new Image(100, 200);
                photo_elem.src = '/uploads/'+book['name_img'];
                bookForm.append(photo_elem);
                let bookID = document.createElement('input');
                bookID.type='hidden';
                bookID.name = 'bookId';
                bookID.value = book['id'];
                bookForm.append(bookID);
                let bookTitle = document.createElement('div');
                    let title= document.createElement('p');
                        title.setAttribute('class','titleP');
                        title.innerHTML = "<b>Title : </b>"+book['title'];
                bookTitle.append(title);
                bookForm.append(bookTitle);
                let bookDescription = document.createElement('div');
                    let description= document.createElement('p');
                        description.setAttribute('class','descriptionP');
                        description.innerHTML = '<b>Description : </b>'+book['description'];
                 bookDescription.append(description) ;
                bookForm.append(bookDescription);
                let bookPublicate_date = document.createElement('div');
                    let publication= document.createElement('p');
                        publication.setAttribute('class','publicationP');
                        publication.innerHTML = '<b>Publication Date : </b>'+book['publication_date'];
                    bookPublicate_date.append(publication);
                bookForm.append(bookPublicate_date);
                let authARR= document.createElement('div');
                    authARR.setAttribute('class','authorP');
                    let nameAuth= document.createElement('p');
                        nameAuth.innerHTML = '</p><b>Authors : </b>';
            authARR.append(nameAuth)  ;
            for(let el of author){
                if(el.length>0){
                    let autName  = document.createElement('div');
                    autName.innerText =el[0]['name']+' '+el[0]['patronymic']+' '+el[0]['surname'] ;
                    authARR.append(autName);
                }
            }
            bookForm.append(authARR);
            let buttonDiv = document.createElement('div');
            buttonDiv.setAttribute("class","buttonDiv");
            let btnEdit = document.createElement('p');
            btnEdit.setAttribute('class',"btnEdit btn btn-warning");
            btnEdit.id = book['id'];
            btnEdit.innerText = 'Edit';
            btnEdit.dataset.action = 'edit';
            let btnDelete = document.createElement('p');
            btnDelete.setAttribute('class',"btnDelete btn btn btn-danger");
            btnDelete.id = book['id'];
            btnDelete.innerText = 'Delete';
            btnDelete.dataset.action = 'delete';
            buttonDiv.append(btnEdit);
            buttonDiv.append(btnDelete);
            bookForm.append(buttonDiv);
                $('.listBook').append(bookForm);

    }
}


//get author list (end)////////////////////////

$('.listBook').on('click',function (e){
    let elem =  e.target;
    var formdata = document.getElementById("Book_form");
    var form = new FormData(formdata);
    form.append("id",elem.id);
    if(elem.dataset.action === 'edit'){

        $.ajax({
            url: '/book/get-book',
            method: 'post',
            dataType: 'json',
            contentType: false,
            processData: false,
            data:form,
            success: function(data){
                console.log(data);
                activeModal();
                let book =data['book'];

                $('input[name="action"]').val(book['id']);
                $('input[name="title"]').val(book['title']);
                $('#description').val(book['description']);
                $('input[name="publication_date"]').val(book['publication_date']);
                let author = data['author'];
                for(let elem of author){
                    let input = document.createElement('input');
                    input.setAttribute("type","hidden");
                    input.setAttribute("name","authors[]");
                    input.setAttribute("value",elem[0]['id']);
                    let span = document.createElement('span');
                    let closeAuthor = document.createElement('span');
                            span.append(elem[0]['name']+" "+elem[0]['patronymic']+" "+elem[0]['surname']);
                            span.setAttribute("class","BookAuthor");
                            span.setAttribute("id",'bookAuthid'+elem[0]['id']);
                            closeAuthor.append('❌');
                            closeAuthor.setAttribute("class","closeAuthor btn-close");
                            closeAuthor.setAttribute("id",elem[0]['id']);
                    $('.authors').append(span);
                    $('.authors').append(closeAuthor);
                    $('.authors').append(input);
                    $('.closeAuthor').on('click',function(e){
                        let elem = e.target;
                        let bookAuthor = document.getElementById('bookAuthid'+elem.id);
                        bookAuthor.style='background-color:red;';
                        setTimeout(function (){
                            bookAuthor.remove();
                            elem.remove();
                        },500);
                    });
                }
                let photo_elem =  new Image();
                photo_elem.src = '/uploads/'+book['name_img'];
                var list = document.createElement('ol');
                const listItem = document.createElement('li');
                const para = document.createElement('p');
                    listItem.append(photo_elem);
                    listItem.append(para);
                list.append(listItem);
                previewPhoto.append(list);
            },
            error:function(data){
                console.log(data.responseText);
            },
        });
    }
    if(elem.dataset.action === 'delete'){
        $.ajax({
            url: '/book/delete',
            method: 'post',
            dataType: 'json',
            contentType: false,
            processData: false,
            data:form,
            success: function(data){
                console.log(data);
                if(data == true){
                    getBook();
                }
            },
            error:function(data){
                console.log(data.responseText);
            },
        });
    }
});

//edit/delete  author (end) ////////////

// active modal (start)////////////////////////////
var activeModal = function (){
    if($('.windowModal').css("display")== 'none') {
        $('.windowModal').css("display","");
    }
}
$('.addBook').on('click',activeModal);

let resetFormBook = function(){
    $('#Book_form')[0].reset();
    $('.previewPhoto').empty();
    $('.authors').empty();
    $('input[name="action"]').val('');
}

var closeModal = function(){
    resetFormBook();
    $('.errorMassage').remove();
    $('.errorInput').removeClass('errorInput');
    $('.windowModal').css("display","none");
}
$('.closeBookForm').on('click',closeModal);
// active modal (end)////////////////////////////
