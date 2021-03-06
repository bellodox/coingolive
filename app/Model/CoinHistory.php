<?php

namespace Model;

class CoinHistory extends \Base\DAO {

    protected $_table = "coin_history";
    protected $codigo;
    protected $price;
    protected $vol24h;
    protected $available_supply;

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setVol24h($vol24h) {
        $this->vol24h = $vol24h;
    }

    function setAvailableSupply($available_supply) {
        $this->available_supply = $available_supply;
    }

    function findLast7Days($codigos) {
        $whereIn = "'".implode("','", $codigos)."'";
         $sql = "SELECT
               ch1.codigo,
               ch1.price,
               ch1.vol24h 
           FROM coin_history ch1
			INNER JOIN (
				SELECT 
				max(id) as id,
				date_format(created,'%Y-%m-%d %H') as dt 
				FROM coin_history
                WHERE codigo IN (".$whereIn.")
				AND created >=  DATE(NOW()) - INTERVAL 7 DAY
				GROUP BY codigo,dt
			) ch2 ON ch2.id=ch1.id
        WHERE ch1.created >= DATE(NOW()) - INTERVAL 7 DAY
        ORDER BY  ch1.codigo, ch1.id ASC";

        return $this->query($sql);
    }

    function delete8Days() {
        $db = $this->getDb();
        $sql = 'DELETE FROM ' . $this->_table . ' WHERE created <  DATE(NOW()) - INTERVAL 8 DAY ';
        $prepare = $db->prepare($sql);
        $this->exec($prepare);
        return $prepare->rowCount();
    }

}
