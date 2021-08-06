
var currentTab = 0;

document.addEventListener('DOMContentLoaded', function(){
    currentTab = 0;
    console.log(currentTab);
    

    if (document.getElementById('voiting').classList.contains('hidden')) {
        document.getElementById('nextBtn').classList.add('hidden');
        document.getElementById('prevBtn').classList.add('hidden');
    }else{
        showTab(currentTab);
    }
    
});

$("#newplayer").on('submit',function(e){
    e.preventDefault();
    newPlayer();
   
    
});

$("#viewresult").click(function(e){
    e.preventDefault();
    viewResult();
   
    
});





$("#viewvoiting").click(function(){
    console.log('click view');
    document.getElementById('title_h1').classList.add('reduction');
    document.getElementById('title_h1').style.fontSize='24px';
    document.getElementById('title_h1').style.width='70%';
    document.getElementById('title_h1').style.margin='0 auto';
    isUniqueVoiting();
   
    
});


function showTab(n) {
    var x = document.getElementsByClassName('tab');
    if(currentTab == 0) document.getElementById('prevBtn').classList.add('hidden')
    if(currentTab != 0 ) document.getElementById('prevBtn').classList.remove('hidden')
    if(currentTab != x.length -1) document.getElementById('nextBtn').classList.remove('hidden')
    if(currentTab == x.length -1){
        document.getElementById('nextBtn').innerHTML = 'Отправить'
        
    } 
    x[n].classList.toggle('hidden');


    
}


function nextPrev(n) {
    var x = document.getElementsByClassName('tab');
    if (currentTab == x.length -1) {
        x[currentTab].classList.toggle('hidden');
        document.getElementById('nextBtn').classList.add('hidden');
        document.getElementById('prevBtn').classList.add('hidden');
        submitForm();
        
    }

    if(currentTab != x.length -1){
        showTab(currentTab)
        currentTab = currentTab + n;
        showTab(currentTab)
    }
    
}

function isUniqueVoiting(){
    var form = $('#stepform')[0];
    var formData = new FormData(form);
    formData.append('type', 'isvoiting');
    console.log('isUniqueVoiting');
    $.ajax({
        url:"/controllers/post.php",
        type:"POST",
        data:formData,
        contentType: false,
        processData: false,
        success: function (response) {
            
            res = $.parseJSON(response);
            if(res.error !='') {
                $('#message').html('Ошибка ' + res.error)
            }else if(res.status == 'notunique'){
                document.getElementById('div_btn_viewvoiting').classList.toggle('hidden');
                document.getElementById('viewresult').style.backgroundColor = '#B6CB67';
                document.getElementById('viewresult').style.color = '#002B31';
                document.getElementById('div_btn_viewresult').style.width = '100%'
                $('#message').html(res.message)
            }else{
                document.getElementById('voiting').classList.remove('hidden');
                document.getElementById('div_btn_viewvoiting').classList.toggle('hidden');
                document.getElementById('div_btn_viewresult').classList.add('hidden');
                document.getElementById('control').style.margin = '0';
                document.getElementById('form_control').classList.add('slide-up');
                //setTimeout(() => document.getElementById('form_control').classList.add('slide-up'), 1000);
                showTab(currentTab);
                console.log('is unique');
                //submitForm(formData);
            }
            
        },
        error:function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
            $('#message').html('Непредвиденная ошибка')
        }
    });
}


function submitForm(formData) {
    var forms = $('#stepform')[0];
    console.log(forms['forward']);
    var formData = new FormData(forms);
    formData.append('type', 'stepform');

    $.ajax({
        url:"/controllers/post.php",
        type:"POST",
        data:formData,
        contentType: false,
        processData: false,
        success: function (response) {
            document.getElementById('voiting').style.display = 'none';
            res = $.parseJSON(response);
            if(res.error !='') {
                $('#message').html('Ошибка ' + res.error)
            }else{
                $('#message').html(res.message)
                document.getElementById('control').style.marginTop = '50px';
                document.getElementById('control').style.marginBottom = '20px';
                document.getElementById('div_btn_viewresult').classList.remove('hidden');
                document.getElementById('div_btn_viewresult').style.width = '100%';
                document.getElementById('viewresult').style.color = '#002B31';
                document.getElementById('viewresult').style.backgroundColor = '#B6CB67';
                
            }
            
        },
        error:function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
            $('#message').html('Непредвиденная ошибка')
        }
    });
}

function newPlayer() {
    var form = $('#newplayer')[0];
    var formData = new FormData(form);
    formData.append('type', 'newplayer');

    $.ajax({
        url:"/controllers/post.php",
        type:"POST",
        data:formData,
        contentType: false,
        processData: false,
        success: function (response) {
           // document.getElementById('stepform').style.display = 'none';
            res = $.parseJSON(response);
            if(res.error !='') {
                $('#message').html('Ошибка ' + res.error)
            }else{
                $('#message').html(res.message)
            }
            
        },
        error:function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
            $('#message').html('Непредвиденная ошибка')
        }
    });
}

function viewResult() {
    $.ajax({
        url:"/controllers/post.php",
        type:"POST",
        data:{'type':'viewresult'},
        
        success: function (response) {
            res = $.parseJSON(response);
            if(res.error !='') {
                $('#message').html('Ошибка ' + res.error)
            }else{
                $('#message').html(res.message)
                renderResult(res);
            }
            
        },
        error:function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
            $('#message').html('Непредвиденная ошибка')
        }
    });
}


function renderResult(res){
    if(document.getElementById('voiting_result').classList.contains('hidden')){
        document.getElementById('voiting_result').classList.toggle('hidden');
        document.getElementById('viewresult').innerHTML='Скрыть результат';

        let forward_countryimg = '/src/img/' + res.forward_countryimg + '.svg';
        let forward_img = '/src/img/forward-' + res.forward_img + '.jpg';
        document.getElementById('forward_name').innerHTML = res.forward;
        document.getElementById('forward_country').innerHTML = res.forward_country;
        document.getElementById('forward_countryimg').src = forward_countryimg;
        document.getElementById('forward_img').src = forward_img;
        document.getElementById('forward_percent').innerHTML = res.forward_percent;

        let central_countryimg = '/src/img/' + res.central_countryimg + '.svg';
        let central_img = '/src/img/central-' + res.central_img + '.jpg';
        document.getElementById('central_name').innerHTML = res.central;
        document.getElementById('central_country').innerHTML = res.central_country;
        document.getElementById('central_countryimg').src = central_countryimg;
        document.getElementById('central_img').src = central_img;
        document.getElementById('central_percent').innerHTML = res.central_percent;
        
        let def_countryimg = '/src/img/' + res.def_countryimg + '.svg';
        let def_img = '/src/img/def-' + res.def_img + '.jpg';
        document.getElementById('def_name').innerHTML = res.def;
        document.getElementById('def_country').innerHTML = res.def_country;
        document.getElementById('def_countryimg').src = def_countryimg;
        document.getElementById('def_img').src = def_img;
        document.getElementById('def_percent').innerHTML = res.def_percent;

        let gk_countryimg = '/src/img/' + res.gk_countryimg + '.svg';
        let gk_img = '/src/img/gk-' + res.gk_img + '.jpg';
        document.getElementById('gk_name').innerHTML = res.gk;
        document.getElementById('gk_country').innerHTML = res.gk_country;
        document.getElementById('gk_countryimg').src = gk_countryimg;
        document.getElementById('gk_img').src = gk_img;
        document.getElementById('gk_percent').innerHTML = res.gk_percent;

    }else{
        document.getElementById('voiting_result').classList.toggle('hidden');
        document.getElementById('viewresult').innerHTML='Показать результат';

       
        
    }

    

}