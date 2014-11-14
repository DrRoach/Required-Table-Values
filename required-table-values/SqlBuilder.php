<?php

class SqlBuilder {
   /**
    * @param $row
    * @param string $table
    * 
    * @return string
    */
    public static function build_insert_sql($row, $table) {
        $count = 0;
        $sql = 'INSERT INTO '.$table.' (';
        $end_sql = 'VALUES (';
        foreach($row as $col => $val) {
            $sql .= $col;
            $end_sql .= '\''.$val.'\'';
            ($count == count($row) - 1 ? $sql .= ') ' : $sql .= ', ');
            ($count == count($row) - 1 ? $end_sql .= ');' : $end_sql .= ', ');
            $count++;
        }
        return $sql . $end_sql;
    }

    /**
     * @param string $table
     * @param $replace
     * @param $count
     *
     * @return string
     */
    public static function build_delete_sql($table, $replace) {
        $count = 0;
        $sql = 'DELETE FROM '.$table.' WHERE ';
        foreach($replace as $key => $val) {
            $sql .= '`' . $key . '` = \'' . $val . '\'';
            ($count == count($replace) - 1 ? $sql .= ';' : $sql .= ' AND ');
            $count++;
        }
        return $sql;
    }
}

?>
