<?php
class Model{
    private $credentials = null;
    private $connection = null;

    private $sql        = array(
                            "table"=>"",
                            "joins"=>"",
                            "where"=>""
                        );
    private function connect(){
        $this->credentials = new Credentials();
        try {
            $this->connection = new PDO('mysql:host='.$this->credentials->host.';dbname='.$this->credentials->database, $this->credentials->username, $this->credentials->password);
        } catch (PDOException $e) {
            http_response_code(503);
            echo json_encode(array("error"=>"Error!: " . $e->getMessage()));
            exit(0);
        }

    }

    private $pkey = "id";

    private function exec( $sql ){
        if ($this->debug) echo $sql;
        $rows = array();
        try{
            foreach( $this->connection->query($sql) as $row ){
                $rows[] = $row;
            };
            return $rows;
        } catch (PDOException $e) {
            http_response_code(503);
            echo json_encode(array("error"=>"Error!: " . $e->getMessage()));
            exit(0);
        }

    }
    /**************
    * Public
    ***************/
    public $debug = false;
    /********************************************************************************************************
    *   $table: The table of the database to select.
    *   $pkey:  The field of the table that acts as primary private key.
    *   $joins: an array of fields and they matching table/field.
    *           The array should look like this:
    *
    *           $joins = array( "permission_id" => array( "permission" => "id" ) );
    *               -or-
    *           $joins = ["field_name"] | ["table_name"] = "field_name"
    *
    ********************************************************************************************************/
    public function __construct($table, $pkey='id', $joins=[] ){
        $this->connect();

        $joinStr = "";
        foreach( $joins as $field=>$join ) {
            foreach ($join as $target=>$target_field ) {
                $joinStr.="LEFT JOIN `".$target."` ON `".$table."`.`".$field."` = `".$target."`.`".$target_field."` ";
            }
        }

        $this->sql['table'] = $table;
        $this->sql['joins'] = $joinStr;
        $this->sql['where'] = "WHERE `".$table."`.`".$pkey."` = ";
        $this->pkey = $pkey;
    }


    /********************************************************************************************************
    *
    ********************************************************************************************************/
    public function get( $id ){
        $value = str_replace("'","''",$id);

        $sql = "SELECT * FROM `".$this->sql['table']."` ".$this->sql['joins'].$this->sql['where']."'".$value."'";
        return $this->exec($sql);
    }
    /********************************************************************************************************
    *
    ********************************************************************************************************/
    public function select( $key=null, $value=null, $sortBy='1', $sortOrd='DESC', $limit='100', $startat='0' ){
        if (!is_numeric($sortBy)){
            $sortBy = "`".$this->sql['table']."`.`".$sortBy."`";
        }
        $endSql = "ORDER BY ".$sortBy." ".$sortOrd." LIMIT ".$startat.",".$limit;
        if (!is_null($value)){
            $value = str_replace("'","''",$value);
            if (is_null($key)){
                $key = $pkey;
            }
            if (!is_null($key) && !empty($key)){
                $sql = "SELECT * FROM `".$this->sql['table']."` ".$this->sql['joins']." WHERE `".$this->sql['table']."`.`".$key."` = '".$value."' ".$endSql;//where;

            } else {
                http_response_code(503);
                return array("error"=>"No key was specified for select.");
            }
        } else {
            $sql = "SELECT * FROM `".$this->sql['table']."` ".$this->sql['joins']." ".$endSql;
        }

        return $this->exec($sql);
    }

    /********************************************************************************************************
    *      $select = array( "field_name" => "value" );
    *               -or-
    *      $select = ["field_name"] = "value"
    ********************************************************************************************************/
    public function selectmany( $select=[], $sortBy='1', $sortOrd='DESC', $limit='100', $startat='0' ){
        if (!is_numeric($sortBy)){
            $sortBy = "`".$this->sql['table']."`.`".$sortBy."`";
        }
        $endSql = "ORDER BY ".$sortBy." ".$sortOrd." LIMIT ".$startat.",".$limit;
        if (!is_null($select) && !empty($select)){
            $where = "";
            foreach( $select as $key => $value){
                  if (is_array($value) > 0) {
                      $table = $key;
                      foreach( $value as $col => $field ){
                          $field = str_replace("'","''",$field);
                          $where.= "`".$table."`.`".$col."` = '".$field."' AND ";
                      }
                  } else {
                      $value = str_replace("'","''",$value);
                      $where.= "`".$this->sql['table']."`.`".$key."` = '".$value."' AND ";
                  }
            }
            $where = substr($where,0,-4);

            $sql = "SELECT * FROM `".$this->sql['table']."` ".$this->sql['joins']." WHERE ".$where.$endSql;//where;
        } else {
            $sql = "SELECT * FROM `".$this->sql['table']."` ".$this->sql['joins']." ".$endSql;
        }

        return $this->exec($sql);
    }

    /********************************************************************************************************
    *      $where = write it yourself
    *      USE AT YOUR OWN RISK
    ********************************************************************************************************/
    public function selectwhere( $sortBy='1', $sortOrd='DESC', $limit='100', $startat='0', $where = "" ){
        if (!is_numeric($sortBy)){
            $sortBy = "`".$this->sql['table']."`.`".$sortBy."`";
        }
        $endSql = "ORDER BY ".$sortBy." ".$sortOrd." LIMIT ".$startat.",".$limit;
        if (!empty($where)){
            $sql = "SELECT * FROM `".$this->sql['table']."` ".$this->sql['joins']." WHERE ".$where." ".$endSql;//where;
        } else {
            $sql = "SELECT * FROM `".$this->sql['table']."` ".$this->sql['joins']." ".$endSql;
        }

        return $this->exec($sql);
    }

    /********************************************************************************************************
    *
    ********************************************************************************************************/
    public function delete( $id ){
        $id = str_replace("'","''",$id);
        $sql = "DELETE FROM `".$this->sql['table']."` WHERE `".$this->pkey."`='".$id."'";
        $this->exec($sql);
        return array("success"=>"I said what what.");
    }

    /********************************************************************************************************
    *
    ********************************************************************************************************/
    public function update( $id, $values ) {
        $id = str_replace("'","''",$id);
        $valueStr = "";
        foreach ( $values as $key=>$value ) {
            $valueStr.="`".$key."` = '".str_replace("'","''",$value)."', ";
        }
        $valueStr=substr($valueStr,0,-2);
        $sql = "UPDATE `".$this->sql['table']."` SET ".$valueStr." WHERE `".$this->pkey."`='".$id."'";
        $this->exec($sql);
        return array("success"=>"It was a triumph, I'm making a note here, huge success.");
    }
    /********************************************************************************************************
    *
    ********************************************************************************************************/
    public function insert( $values ){
        $valueStr = "";
        foreach ( $values as $key=>$value ) {
            $valueStr.="`".$key."` = '".str_replace("'","''",$value)."', ";
        }
        $valueStr=substr($valueStr,0,-2);
        $sql = "INSERT INTO `".$this->sql['table']."` SET ".$valueStr;
        $this->exec($sql);
        return $this->connection->lastInsertId();
    }
    /********************************************************************************************************
    * Execute arbitrary sql
    ********************************************************************************************************/
    public static function dangerousExec( $sql, $debug = false ){
        $sql = str_replace("'","''",$sql);

        if ( $debug ) echo $sql;
        $rows = array();
        
        try{
            $credentials = new Credentials();
            $connection = new PDO('mysql:host='.$credentials->host.';dbname='.$credentials->database, $credentials->username, $credentials->password);
            foreach( $connection->query($sql) as $row ){
                $rows[] = $row;
            };
            return $rows;
        } catch (PDOException $e) {
            http_response_code(503);
            echo json_encode(array("error"=>"Error!: " . $e->getMessage()));
            exit(0);
        }

    }
}
