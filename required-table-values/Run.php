<?php

class Run {
    public function __construct($data) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        //Pull in SqlBuilder class
        require_once('SqlBuilder.php');

        //Make sure data has been passed
        if(empty($data)) {
            echo 'Please pass your database connection settings in a key value array';
            exit;
        }

        //Make sure data is array
        if(!is_array($data)) {
            echo 'You must pass through your database connection settings as a array';
            exit;
        }

        //Check to see if port is set, if not set it to 3306
        if(!isset($data['port'])) {
            $data['port'] = '3306';
        }

        //Make sure all relevant values are set in data
        if(!isset($data['database_host']) || !isset($data['username']) || !isset($data['password']) || !isset($data['database'])) {
            echo 'Please make sure you pass through your data with the correct array keys';
            exit;
        }

        //Create DB connection
        $con = self::connect($data);
        if(mysqli_connect_errno()) {
            echo 'Error: ' . mysqli_connect_error();
            exit;
        }

        //Make sure required-table-values directory exists
        if (!file_exists(dirname(__DIR__) . '/required-table-values')) {
            echo 'Please make sure that you have a "required-table-values" folder in root';
            exit;
        }

        //Get directories
        $dirs = $this->get_dirs();

        //Loop through all json files and insert relevant data
        foreach($dirs as $dir) {
            //Get the table from filename
            $table = substr($dir, 0, strlen($dir) - 5);
            $contents = file_get_contents(__DIR__ . '/' . $dir);
            $json = json_decode($contents, true);
            //Get the settings
            if(!empty($json['settings'])) {
                $settings = $json['settings'];
            } else {
                $settings = array();
            }
            //Run through settings
            $this->settings($settings, $json, $table, $con);
            //Insert each row
            foreach($json['rows'] as $row) {
                //Remove any array rows
                $row = $this->remove_array_rows($row);
                $sql = SqlBuilder::build_insert_sql($row, $table);
                //Insert built row
                //Prepare the sql
                if($query = $con->prepare($sql)) {
                    $query->execute();
                } else {
                    echo 'There was a error when inserting your row';
                    exit;
                }
            }
        }
        $con->close();
        echo 'Finished';
        exit;
    }

    public static function connect($data) {
        $socket = ini_get('mysqli.default_socket');
        if($socket === false) {
            $socket = null;
        }
        return new mysqli($data['database_host'], $data['username'], $data['password'], $data['database'], $data['port'], $socket);
    }

    /**
     * @return array
     */
    public function get_dirs() {
        //Get contents of directory
        $dirs = scandir(__DIR__);
        //Filter out any none json files
        foreach ($dirs as $index => $dir) {
            if (strtolower(substr($dir, -5)) != '.json') {
                unset($dirs[$index]);
            }
        }
        return $dirs;
    }

    /**
     * @param $settings
     * @param $json
     * @param string $table
     * @param mysqli $con
     *
     * @return null|boolean
     */
    public function settings($settings, $json, $table, $con) {
        foreach ($settings as $setting => $value) {
            if (strtolower($setting) == 'overwrite' && $value === true) {
                foreach ($json['rows'] as $row) {
                    //Remove any array rows
                    if (isset($row['replace']) && is_array($row['replace'])) {
                        $replace = $row['replace'];
                    } else {
                        //Remove any array rows
                        $replace = $this->remove_array_rows($row);
                    }
                    $sql = SqlBuilder::build_delete_sql($table, $replace);
                    //Delete row
                    //Prepare statement
                    if ($query = $con->prepare($sql)) {
                        $query->execute();
                    } else {
                        echo 'There was a problem when deleting the row';
                        exit;
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param $row
     */
    public function remove_array_rows($row) {
        foreach ($row as $index => $check) {
            if (is_array($check)) {
                unset($row[$index]);
            }
        }
        return $row;
    }
}

?>
