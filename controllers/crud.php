<?php 

include_once($_SERVER['DOCUMENT_ROOT'].'/config/connectDB.php');


$database = new connect();
$db = $database->getConnect();




class BestPlayersVoiting
{
    private $conn;

    public function __construct($db){
      $this->conn=$db;
    }


    public function createVoiting($array)
    {
        $query = "INSERT INTO `bestplayers` (`forward`, `central`, `def`, `gk`, `uid_id`, `uid_date`)
                VALUES (:forward, :central, :def, :gk, :uid_id, :uid_date)";

        $params=[
            ':forward'=> $array['forward'],
            ':central'=> $array['central'],
            ':def'=> $array['def'],
            ':gk'=> $array['gk'],
            ':uid_id'=> $array['uid_id'],
            ':uid_date'=> $array['uid_date']
        ];

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

    }

    public function isUniqueVoiting($uid){
        $query = "SELECT * FROM `bestplayers` 
                    WHERE uid_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$uid]);

        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($stmt);

        $res = ($count > 0) ? false : true;

        return $res;

    }



    public function readVoiting(){
        $query = "SELECT * FROM `bestplayers`";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    private function countVoiting(){
        $query = "SELECT * FROM `bestplayers`";
        $stmt = $this->conn->query($query);
        $stmt->execute();
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($stmt);

        return $count;
    }



    public function whoIsBest()
    {

        $best_players = [
            'fw'=>'nan',
            'forward_count'=>'',
            'forward_percent'=>'',
            'cm'=>'nan',
            'central_count'=>'',
            'central_percent'=>'',
            'df'=>'nan',
            'def_count'=>'',
            'def_percent'=>'',
            'gk'=>'nan',
            'gk_count'=>'',
            'gk_percent'=>'',
            
        ];

        $allvaiting = $this->countVoiting();

        foreach ($best_players as $key => $value) {
            if ($value != '') {
                $query_position = "SELECT * FROM `players`
                                WHERE position = ?";

                $stmt = $this->conn->prepare($query_position);
                $stmt->execute([$key]);
                $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                switch ($key) {
                    case 'fw':
                        $best_players['forward'] = $this->bestForward($players)['best_player'];
                        $best_players['forward_count'] = $this->bestForward($players)['best_count'];
                        $percent = $best_players['forward_count'] / $allvaiting * 100;
                        $best_players['forward_percent'] = (int)$percent;
                        $best_players['forward_country'] = $this->getCountryByName($best_players['forward'])['country'];
                        $best_players['forward_countryimg'] = $this->getCountryByName($best_players['forward'])['countryimg'];
                        break;
                    case 'cm':
                        $best_players['central'] = $this->bestCentral($players)['best_player'];
                        $best_players['central_count'] = $this->bestCentral($players)['best_count'];
                        $percent = $best_players['central_count'] / $allvaiting * 100;
                        $best_players['central_percent'] = (int)$percent;
                        $best_players['central_country'] = $this->getCountryByName($best_players['central'])['country'];
                        $best_players['central_countryimg'] = $this->getCountryByName($best_players['central'])['countryimg'];
                        break;
                    case 'df':
                        $best_players['def'] = $this->bestDef($players)['best_player'];
                        $best_players['def_count'] = $this->bestDef($players)['best_count'];
                        $percent = $best_players['def_count'] / $allvaiting * 100;
                        $best_players['def_percent'] = (int)$percent;
                        $best_players['def_country'] = $this->getCountryByName($best_players['def'])['country'];
                        $best_players['def_countryimg'] = $this->getCountryByName($best_players['def'])['countryimg'];
                        break;
                    case 'gk':
                        $best_players['gk'] = $this->bestGk($players)['best_player'];
                        $best_players['gk_count'] = $this->bestGk($players)['best_count'];
                        $percent = $best_players['gk_count'] / $allvaiting * 100;
                        $best_players['gk_percent'] = (int)$percent;
                        $best_players['gk_country'] = $this->getCountryByName($best_players['gk'])['country'];
                        $best_players['gk_countryimg'] = $this->getCountryByName($best_players['gk'])['countryimg'];
                        break;
                    default:
                        
                        break;
                }    


               // $best_players[$key] = $best_forward;

                //$key_count = $key . '_count';
                //$best_players[$key_count] = $best_count;
                
                //$percent = $best_count / $allvaiting *100;
                //$key_percent = $key . '_percent';
                //$best_players[$key_percent] = $percent;
            }
            
        }
        
        $best_players['all_vaiting'] = $allvaiting;
        return $best_players;
        

    }

    private function getCountryByName($name){
        $query = "SELECT * FROM `players`
                                WHERE name = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name]);
        $country = $stmt->fetch(PDO::FETCH_ASSOC);

        return $country;
    }

    private function bestForward($forwards)
    {    
        $best_count = 0;
        $best_forward ='Nan';   

        foreach ($forwards as $forward) {
            $query = "SELECT * FROM bestplayers WHERE forward = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(array($forward['name']));
            $count = count($stmt->fetchAll(PDO::FETCH_ASSOC));
            
            if ($count > $best_count) {
            $best_count = $count;
            $best_forward = $forward['name'];
            
            }
            
        }

        $player = [
            "best_player"=>$best_forward,
            "best_count"=>$best_count
        ];
        return $player;
    }

    private function bestCentral($players)
    {    
        $best_count = 0;
        $best_player ='Nan';   

        foreach ($players as $player) {
            $query = "SELECT * FROM bestplayers WHERE central = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(array($player['name']));
            $count = count($stmt->fetchAll(PDO::FETCH_ASSOC));
            
            if ($count > $best_count) {
            $best_count = $count;
            $best_player = $player['name'];
            }
            
        }
        $player = [
            "best_player"=>$best_player,
            "best_count"=>$best_count
        ];
        return $player;
    }

    private function bestDef($players)
    {    
        $best_count = 0;
        $best_player ='Nan';   

        foreach ($players as $player) {
            $query = "SELECT * FROM bestplayers WHERE def = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(array($player['name']));
            $count = count($stmt->fetchAll(PDO::FETCH_ASSOC));
            
            if ($count > $best_count) {
            $best_count = $count;
            $best_player = $player['name'];
            }
            
        }
        $player = [
            "best_player"=>$best_player,
            "best_count"=>$best_count
        ];
        return $player;
    }

    private function bestGk($players)
    {    
        $best_count = 0;
        $best_player ='Nan';   

        foreach ($players as $player) {
            $query = "SELECT * FROM bestplayers WHERE gk = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(array($player['name']));
            $count = count($stmt->fetchAll(PDO::FETCH_ASSOC));
            
            if ($count > $best_count) {
            $best_count = $count;
            $best_player = $player['name'];
            }
            
        }
        $player = [
            "best_player"=>$best_player,
            "best_count"=>$best_count
        ];
        return $player;
    }
    
}


class Players{
    private $conn;

    public function __construct($db){
      $this->conn=$db;
    }

    public function createPlayer($array)
    {
        $table_name = $array['position'];
        $name = $array['name'];
        $img = $array['img'];
        
        try {
            $query = "INSERT INTO " . $table_name . " (`name`, `img`) VALUES (:name, :img)";
            $params = [
                ':name'=>$name,
                ':img'=>$img
            ];
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $array['position'];
        } catch (\PDOException $e) {
            return $e->getMessage();
        }
       
    }

    public function getForwards()
    {
        $query = "SELECT * FROM `players` WHERE `position`='fw'";
        $stmt = $this->conn->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getForwardByName($name){
        $query = "SELECT * FROM `players` 
                    WHERE `position`='fw'
                    AND name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name]);
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt;
    }

    public function getCentrals()
    {
        $query = "SELECT * FROM `players` WHERE `position`='cm'";
        $stmt = $this->conn->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getCentralByName($name){
        $query = "SELECT * FROM `players` 
                    WHERE `position`='cm'
                    AND name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name]);
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt;
    }

    public function getDefs()
    {
        $query = "SELECT * FROM `players` WHERE `position`='df'";
        $stmt = $this->conn->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getDefByName($name){
        $query = "SELECT * FROM `players` 
                WHERE `position`='df'
                AND name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name]);
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt;
    }

    public function getGks()
    {
        $query = "SELECT * FROM `players` WHERE `position`='gk'";
        $stmt = $this->conn->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getGkByName($name){
        $query = "SELECT * FROM `players` 
                WHERE `position`='gk'
                AND name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name]);
        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt;
    }
}
