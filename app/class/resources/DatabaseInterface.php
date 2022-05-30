<?php

class DatabaseInterface
{


    public function store(array $fields, $table_name)
    {
        try {
            $database = new Database();
            $columns_string = "";
            $prepare_string = "";
            if (count($fields) > 0) {
                foreach ($fields as $key => $value) {
                    $columns_string .= $key . ",";
                    $prepare_string .= "?,";
                }
            }
            $columns_string = substr($columns_string, 0, -1);
            $prepare_string = substr($prepare_string, 0, -1);
            $query_string = "INSERT INTO %s (%s) VALUES (%s)";
            $query_string = sprintf($query_string, $table_name, $columns_string, $prepare_string);
            $database->query($query_string);
            if (count($fields) > 0) {
                $i = 0;
                foreach ($fields as $key => $value) {
                    $i++;
                    $database->bind($i, $this->prepare($value[0], $value[1]));
                }
            }
            $database->execute();
            $last_id = $database->lastInsertId();
            if ($last_id > 0) return $last_id;
        } catch (Exception $exception) {
            logger($exception);
        }
        return 0;
    }


    public function update(array $fields, $table_name, $where = "")
    {
        $database = new Database();
        try {
            $id_update = 0;
            if (!not_empty($where)) return false;
            $columns_string = "";
            if (count($fields) > 0) {
                foreach ($fields as $key => $value) {
                    if (strpos($where, $key) === false) {
                        $columns_string .= $key . " = ?,";
                        $id_update = $value[0];
                    } else {
                        $where = str_replace(":" . $key, $value[0], $where);
                    }
                }
            }
            $columns_string = substr($columns_string, 0, -1);
            $query_string = "UPDATE `%s` SET %s WHERE %s";
            $query_string = sprintf($query_string, $table_name, $columns_string, $where);
            $database->query($query_string);
            if (count($fields) > 0) {
                $i = 0;
                foreach ($fields as $key => $value) {
                    if (strpos($where, $key) === false) {
                        $i++;
                        $val = not_empty($value[0]) ? $value[0] : null;
                        ///logger("$i? > $val");
                        $database->bind($i, $this->prepare($val, $value[1]));
                    }else{
                       
                    }
                }
            }

            //logger($query_string);

            $database->execute();
            return $id_update > 0 ? $id_update : true;
        } catch (Exception $exception) {
            logger($exception);
        } finally {
            $database->close();
        }
        return false;
    }


    private function prepare($value, $type = "string")
    {
        $number = new Numeric();
        $encryption = new Encryption();
        $text = new Text();
        $date = new Date();
        try {
            if (not_empty($value) || $type === "date") {
                switch ($type) {
                    case "integer":
                        $value = $number->set($value)->clean()->output();
                        if (!not_empty($value)) $value = null;
                        break;
                    case "password":
                        $value = $encryption->EncryptPassword($value);
                        break;
                    case "money":
                        $value = $number->set($value)->database()->output();
                        break;
                    case "card_number":
                        $value = $number->set($value)->clean()->output();
                        $value = substr($value, -4);
                        break;
                    case "date":
                        $value = $date->set($value)->database();
                        break;
                    case "string":
                        $value = $text->set($value)->encode()->output();
                        break;
                    default:
                        break;
                }
                return $value;
            }
        } catch (Exception $exception) {
            logger($exception);
        }
        return null;
    }


}