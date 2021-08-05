<?php 
session_start();
session_id();

if(isset($_POST)){

    $data = $_POST;
    $data['uid_id'] = session_id();
    $type = $_POST['type'];

    switch ($type) {
        case 'stepform':
            postSendStepForm($data);
            break;
        case 'newplayer':
            createPlayer($data);
            break;
        case 'viewresult':
            viewResult();
            break;
        case 'isvoiting':
            isUniqueVoiting($data);
            break;
        default:
            serverTest();
            break;
    }

}

function createPlayer($data){
    include_once($_SERVER['DOCUMENT_ROOT'].'/controllers/crud.php');
    $Player = new Players($db);

    if($data['name'] =='' && $data['position']==''){
        $res = [
            "status"=>'error',
            "message"=>"Укажите Имя и Амплуа",
            "error"=>''
        ];

        echo json_encode($res);
        exit;
    }
    
    $array = [
        "name"=>$data['name'],
        "position"=>$data['position'],
        "img"=>$data['img']
    ];

    $Player->createPlayer($array);
    $res = [
        "status"=>'ok',
        "message"=>"Игорк {$array['name']} добавлен",
        "error"=>''
    ];

    echo json_encode($res);
}

function isUniqueVoiting($data){
    include_once($_SERVER['DOCUMENT_ROOT'].'/controllers/crud.php');
    $Voiting = new BestPlayersVoiting($db);
    $id = session_id();
    if ($Voiting->isUniqueVoiting($id)) {
        

        $res=[
            "status"=>'ok',
            "message"=>"",
            "error"=>""
        ];
    }else{
        $res=[
            "status"=>'notunique',
            "message"=>"Вы уже голосовали",
            "error"=>""
        ];
    }

    echo json_encode($res);
}

function postSendStepForm($data){
    include_once($_SERVER['DOCUMENT_ROOT'].'/controllers/crud.php');
    $Voiting = new BestPlayersVoiting($db);
    $date = date("j-n-Y");

    $array['forward'] = $data['forward'];
    $array['central'] = $data['central'];
    $array['def'] = $data['def'];
    $array['gk'] = $data['gk'];
    $array['uid_id'] = session_id();
    $array['uid_date'] = $date;

/*
    if ($Voiting->isUniqueVoiting($array['uid_id'])) {
        
    }else{
        $res=[
            "status"=>'ok',
            "message"=>"Вы уже голосовали",
            "error"=>''
        ];
    }
    */
    $Voiting->createVoiting($array);

        $res=[
            "status"=>'ok',
            "message"=>"Спасибо! Ваш голос сохранен",
            "error"=>''
        ];

    echo json_encode($res);
}

function viewResult(){
    include_once($_SERVER['DOCUMENT_ROOT'].'/controllers/crud.php');
    $Voiting = new BestPlayersVoiting($db);

    $best_players = $Voiting->whoIsBest();

    $Player = new Players($db);
    $forward = $Player->getForwardByName($best_players['forward']);
    $central = $Player->getCentralByName($best_players['central']);
    $def = $Player->getDefByName($best_players['def']);
    $gk = $Player->getGkByName($best_players['gk']);


    $res=[
        'forward'=>$forward['name'],
        'forward_img'=>$forward['img'],
        'forward_country'=>$forward['country'],
        'forward_countryimg'=>$forward['countryimg'],
        'forward_percent'=>$best_players['forward_percent'],

        'central'=>$central['name'],
        'central_img'=>$central['img'],
        'central_country'=>$central['country'],
        'central_countryimg'=>$central['countryimg'],
        'central_percent'=>$best_players['central_percent'],

        'def'=>$def['name'],
        'def_img'=>$def['img'],
        'def_country'=>$def['country'],
        'def_countryimg'=>$def['countryimg'],
        'def_percent'=>$best_players['def_percent'],

        'gk'=>$gk['name'],
        'gk_img'=>$gk['img'],
        'gk_country'=>$gk['country'],
        'gk_countryimg'=>$gk['countryimg'],
        'gk_percent'=>$best_players['gk_percent'],
        'all_vaiting'=>$best_players['all_vaiting'],
        'status'=>'ok',
        'message'=>'',
        'error'=>''
    ];

    echo json_encode($res);

}



function serverTest()
{
    $res=[
        "status"=>'ok',
        "message"=>'srever test ok',
        "error"=>''
    ];

    echo json_encode($res);
}