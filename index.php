<?php 
session_start();
session_id();

$uid_id = session_id();

?>




<!DOCTYPE html>



<html lang="en" dir="ltr">
  <head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="/src/css/style.css">
    
    <link rel="shortcut icon" href="src/img/favicon.png" type="image/png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width" />
    <title>Лучшие игроки EURO 2020</title>
  </head>
    <?php include 'auth.php' ?>
  <body>



<?php 
    include_once($_SERVER['DOCUMENT_ROOT'].'/controllers/crud.php');
    $Players = new Players($db);
    $forwards = $Players->getForwards();
    $centrals = $Players->getCentrals();
    $defs = $Players->getDefs();
    $gks = $Players->getGks();

?>
<!--
<div>
    <form id="newplayer" name="newplayer">
        <input type="text" name="name" placeholder="Имя игрока">
        <select name="position" id="position">
            <option value="forward">Нападающий</option>
            <option value="central">Полузащитник</option>
            <option value="def">Защитник</option>
            <option value="gk">Голкипер</option>
        </select>
        <input type="text" name="img" placeholder="img">
        <button type="submit">Добавить</button>
    </form>
</div>

-->
<div class="voiting__title">
    <img src="src/img/logo.webp" alt="">
    <h1>Голосование: Лучшие игроки EURO 2020</h1>
</div>


<div class="control">
    <div class="control__button" id="div_btn_viewvoiting">
        <button class="btn" id="viewvoiting">Проголосовать</button>
    </div>
    <div class="control__button" id="div_btn_viewresult">
        <button class="btn" id="viewresult">Показать результаты</button>
    </div>
</div>

<div class="voiting hidden" id="voiting">


    <form id="stepform" action="">

        <div class="tab hidden">
            <div><h2>Выберите лучшего нападающего</h2></div>
            <div class="tab__radio-list">
                <?php  foreach($forwards as $forward) :?>
                     
                    <div class="tab__radio-item">
                        
                        <div class="item__img">
                            <img src="/src/img/forward-<?=$forward['img'];?>.jpg" alt="">
                        </div>
                        <div class="item__info">
                            <h3><?=$forward['name']; ?></h3>
                            <div class="item__country">
                                <img src="/src/img/<?=$forward['countryimg'];?>.svg" alt="">
                                <p><?=$forward['country']; ?></p>
                            </div>
                        </div>
                        <div class="item__stats">
                            <div>Матчей на евро: <?=$forward['games']; ?>
                            </div>
                            <div>
                               <span>Рост: </span>
                               <span><?= $forward['height'];?>см</span>
                            </div>
                            <div>
                               <span>Вес: </span>
                               <span><?= $forward['weight']; ?>кг</span>   
                            </div>
                        </div>
                        <div class="item__check">
                        <input type="radio" name="forward" id="forward-<?=$forward['id'];?>" value="<?=$forward['name'];?>">
                        <label for="forward-<?=$forward['id'];?>" class="check">
                            Выбрать
                            
                        </label>   
                        </div>
                        
                    </div>
                
                <?php endforeach; ?>
                
            </div>
        </div>
        <div class="tab hidden">
        <div><h2>Выберите лучшего полузащитника</h2></div>
            <div class="tab__radio-list">
                <?php  foreach($centrals as $central) :?>
                    <label for="central-<?=$central['id'];?>" class="check">
                        <div class="tab__radio-item">
                            <input type="radio" name="central" id="central-<?=$central['id'];?>" value="<?=$central['name'];?>">
                            <img src="/src/img/central-<?=$central['img'];?>.jpg" alt="">
                            <h3><?=$central['name']; ?></h3>
                        </div>
                    </label>  
                <?php endforeach; ?>
                
            </div>
        </div>
        <div class="tab hidden">
        <div><h2>Выберите лучшего защитника</h2></div>
            <div class="tab__radio-list">
                <?php  foreach($defs as $def) :?>
                <label for="def-<?=$def['id'];?>" class="check">        
                    <div class="tab__radio-item">
                        <input type="radio" name="def" id="def-<?=$def['id'];?>" value="<?=$def['name'];?>">
                        <img src="/src/img/def-<?=$def['img'];?>.jpg" alt="">
                        <h3><?=$def['name']; ?></h3>
                    </div>
                </label>
                <?php endforeach; ?>
                
            </div>
        </div>
        <div class="tab hidden">
        <div><h2>Выберите лучшего вратаря</h2></div>
            <div class="tab__radio-list">
                <?php  foreach($gks as $gk) :?>
                <label for="gk-<?=$gk['id'];?>" class="check">
                    <div class="tab__radio-item">
                        <input type="radio" name="gk" id="gk-<?=$gk['id'];?>" value="<?=$gk['name'];?>">
                        <img src="/src/img/gk-<?=$gk['img'];?>.jpg" alt="">
                        <h3><?=$gk['name']; ?></h3>                   
                    </div>
                </label>
                <?php endforeach; ?>
                
            </div>
        </div>
        <div class="form__control">
            <button class="btn-prev" type="button" id="prevBtn" onclick="nextPrev(-1)">Назад</button>
            <button class="btn-next" type="button" id="nextBtn" onclick="nextPrev(1)">Далее</button>
        </div>
    </form>
</div>

<div class="message" id="message"></div>
<div class="voiting-result hidden" id="voiting_result">
    <div class="voiting-result-wrapper">
   <div class="forward player" id="forward">
        <div class="player_percent">
            <h4 id="forward_percent"></h4>
       </div>
       <div class="player__img" >
            <img id="forward_img" src="" alt="">
       </div>
       <div class="player__name">
            <h3 id="forward_name"></h3>
       </div>
       
   </div>
   <div class="central player" id="central">
        <div class="player_percent">
            <h4 id="central_percent"></h4>
       </div>
       <div class="player__img">
            <img id="central_img" src="" alt="">
       </div>
       <div class="player__name">
            <h3 id="central_name"></h3>
       </div>
      
   </div>
   <div class="def player" id="def">
        <div class="player_percent">
            <h4 id="def_percent"></h4>
       </div>
       <div class="player__img">
            <img id="def_img" src="" alt="">
       </div>
       <div class="player__name">
            <h3 id="def_name"></h3>
       </div>
       
   </div>
   <div class="gk player" id="gk">
        <div class="player_percent">
            <h4 id="gk_percent"></h4>
       </div>
       <div class="player__img">
            <img id="gk_img" src="" alt="">
       </div>
       <div class="player__name">
            <h3 id="gk_name"></h3>
       </div>
       
   </div>
   </div>
</div>

<script src="/src/js/step-form.js"></script>


</body>
</html>
