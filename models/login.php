<?php

class Login extends Model
{
    public function getByMail($email){
        $email = $this->db->escape($email);
        $sql = "SELECT * FROM users WHERE email = '{$email}' LIMIT 1";
        $result = $this->db->query($sql);
        //print_r($result);
        if ( isset($result[0]) ) {
            return $result[0];
        }
        return false;
    }

    public function saveUserToDb($data, $id = null){

        //request from DB of email for checking of existing users

        $email_check='';
        $check = "SELECT email FROM users WHERE email = '{$data['email']}' LIMIT 1";

        foreach ( $this->db->query($check) as $item ) {
            foreach ($item as $email_check) {}
        }

        if ( !isset($data['name']) || !isset($data['email']) || !isset($data['password']) || $data['email'] == $email_check ) {
            return false;
        } else {

            $id = (int)$id;
            $name = $this->db->escape($data['name']);
            $email = $this->db->escape($data['email']);
            $password = $this->db->escape(md5(Config::get('salt') . $data['password']));

            if (!$id){//Add new record
                $sql = "
                INSERT INTO users
                SET user_name = '{$name}',
                    email = '{$email}',
                    password = '{$password}'
            ";

            } else {//Update existing record
                $sql = "
                UPDATE users
                SET user_name = '{$name}',
                    email = '{$email}',
                    password = '{$password}'
                WHERE user_id = {$id}
            ";//todo а нужен ли тут апдейт?
            }

        }

        return $this->db->query($sql);
    }

}