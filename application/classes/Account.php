<?php
	defined('INSITE') or die('No direct script access allowed');
	class Account extends Database {
        protected $phone_regex = "/^05\d{8}$/";
        protected $api_url = 'https://api.aimpact.ai/api/';
        protected $api_request = 'CompanyCreateTrainee';
        protected $post_fields = [];
        protected $method = 'GET';

        public function GetUsers(){
			$sth = $this->db->query("SELECT * FROM ".SQL_WEB_DB.".`users` ORDER BY `id` ASC");
            if ($sth->rowCount()) {
                $row = $sth->fetchAll();
                return $row;
            }
		}
        public function UserInfoByPhone($phone_number) {
            $sth = $this->db->prepare("SELECT * FROM ".SQL_WEB_DB.".`users` WHERE `phone_number` = :user_phone LIMIT 1");
            $sth->execute([':user_phone' => $phone_number]);
            if ($sth->rowCount()) {
                $row = $sth->fetch();
                return $row;
            }
            return false;
        }
        public function UserInfoById($user_id) {
            $sth = $this->db->prepare("SELECT * FROM ".SQL_WEB_DB.".`users` WHERE `id` = :user_id LIMIT 1");
            $sth->execute([':user_id' => $user_id]);
            if ($sth->rowCount()) {
                $row = $sth->fetch();
                return $row;
            }
            return false;
        }
		public function SaveUsers($data) {
			$path = BASE_DIR.'application/config/users.json';
			return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
		}

        private function ApiRequest() {
            $headers = [
                'Accept: text/plain',
                'X-API-KEY: oJgCCk8q1Ir9CSrrp332YA==',
                'Content-Type: application/json'
            ];            
            $api_endpoint = $this->api_url.$this->api_request;

            if($this->method === 'GET') {
                // fields are sent as http query, but expected it as a POST for some reason?

                $api_endpoint .= '?'.(http_build_query($this->post_fields));
                try {
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $api_endpoint,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_HTTPHEADER => $headers,
                        CURLOPT_TIMEOUT => 10
                    ]);
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    if($err) {
                        writelog($err, 'API_DEBUG');
                        return ['error' => 'אממ...נסו שנית במועד מאוחר יותר!'];
                    }
                    return json_decode($response, true);
                } catch (Exception $e) {
                    #writelog $e->getMessage();
                    writelog($e->getMessage(), 'API_DEBUG');
                    return ['error' => 'שגיאת מערכת בתקשורת!'];
                }
            } elseif($this->method === 'PATCH') { 
                // fields are sent as http query, but expected it as a POST for some reason?

                $api_endpoint .= '?'.(http_build_query($this->post_fields));
                try {
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $api_endpoint,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => "PATCH",
                        CURLOPT_HTTPHEADER => $headers,
                        CURLOPT_TIMEOUT => 10
                    ]);
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    if($err) {
                        writelog($err, 'API_DEBUG');
                        return ['error' => 'אממ...נסו שנית במועד מאוחר יותר!'];
                    }
                    return json_decode($response, true);
                } catch (Exception $e) {
                    #writelog $e->getMessage();
                    writelog($e->getMessage(), 'API_DEBUG');
                    return ['error' => 'שגיאת מערכת בתקשורת!'];
                }
            }
        }

        public function EditUser($data = []) {
            if(!count($data) || !is_array($data)) {
                return ['error' => 'שגיאת מערכת!'];
            }
            $required_fields = ['user_id', 'first_name', 'last_name', 'phone_number'];
            if(!CheckFields($required_fields, $data)) {
                return ['error' => 'אנא מלא את כל השדות!'];
            }
            $phone_number = $data['phone_number'];
            if(strlen($phone_number) != 10) {
                return ['error' => 'מספר הטלפון לא באורך תקין!'];
            }
            if($this->phone_regex) {
                if(!preg_match($this->phone_regex, $phone_number)) {
                    return ['error' => 'מספר טלפון לא בפורמט תקין!'];
                }
                $phone_number = '972'.substr($phone_number, 1);
            }

            $user_info = $this->UserInfoById($data['user_id']);
            if(!is_array($user_info)) {
                return ['error' => 'משתמש לא קיים במערכת!'];
            }
            $is_new_phone_number = ($user_info['phone_number'] !== $phone_number);
            if($is_new_phone_number) {
                $is_new_phone_available = $this->UserInfoByPhone($phone_number);
                if(is_array($is_new_phone_available)) {
                    return ['error' => 'מספר הטלפון שברצונך להשתמש בו נמצא כבר בשימוש!'];
                }
            }
            try {
                $sth = $this->db->prepare("UPDATE ".SQL_WEB_DB.".`users` SET `first_name` = :first_name, `last_name` = :last_name, `phone_number` = :phone_number WHERE `id` = :id");
                $sth->bindParam(':first_name', $data['first_name'], PDO::PARAM_STR);
                $sth->bindParam(':last_name', $data['last_name'], PDO::PARAM_STR);
                $sth->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
                $sth->bindParam(':id', $user_info['id'], PDO::PARAM_INT);
                $sth->execute();
                // api call to update at aimpact too?
                return ['success' => 'משתמש עודכן בהצלחה!'];
            } catch (Exception $e) {
                writelog($e->getMessage(), 'SQL_DEBUG');
                return ['error' => 'לא ניתן לעדכן משתמש! צור קשר עם התמיכה!'];
            }
        }

        public function SyncUser($data = []) {
            if(!count($data) || !is_array($data)) {
                return ['error' => 'שגיאת מערכת!'];
            }
            $required_fields = ['user_id'];
            if(!CheckFields($required_fields, $data)) {
                return ['error' => 'אנא מלא את כל השדות!'];
            }
            $user_info = $this->UserInfoById($data['user_id']);
            if(!is_array($user_info)) {
                return ['error' => $data['user_id'].'משתמש זה לא נמצא במערכת!'];
            }
            $this->method = 'PATCH';
            $this->api_request = 'ProgramAddTrainee';
            $this->post_fields = [
                'ProgramId' => 561,
                'PhoneNumber' => $user_info['phone_number']
            ];


            $api_call = $this->ApiRequest();
            if(isset($api_call['Message']) && isset($api_call['StatusCode']) && $api_call['StatusCode'] === 400) {
                return ['error' => $api_call['Message']];
            }
            //if(isset($api_call['success'])) {
                return ['success' => 'משתמש שויך בהצלחה!'];
            //}
            //return ['error' => 'לא ניתן לשייך משתמש! נסה שנית במועד מאוחר יותר!'];
        }

        public function DeleteUser($data = []) {
            if(!count($data) || !is_array($data)) {
                return ['error' => 'שגיאת מערכת!'];
            }
            $required_fields = ['user_id'];
            if(!CheckFields($required_fields, $data)) {
                return ['error' => 'אנא מלא את כל השדות!'];
            }
            $user_info = $this->UserInfoById($data['user_id']);
            if(!is_array($user_info)) {
                return ['error' => 'משתמש זה לא נמצא במערכת!'];
            }
            try {
                $sth = $this->db->prepare("DELETE FROM ".SQL_WEB_DB.".`users` WHERE `id` = :user_id");
                $sth->execute([':user_id' => $user_info['id']]);
                // api call to remove from aimpact too?
                return ['success' => 'משתמש נמחק בהצלחה!'];
            } catch (Exception $e) {
                writelog($e->getMessage(), 'SQL_DEBUG');
                return ['error' => 'לא ניתן למחוק משתמש! צור קשר עם התמיכה!'];
            }
        }

        public function CreateUser($data = []) {
            if(!count($data) || !is_array($data)) {
                return ['error' => 'שגיאת מערכת!'];
            }
            $required_fields = ['first_name', 'last_name', 'phone_number'];
            if(!CheckFields($required_fields, $data)) {
                return ['error' => 'אנא מלא את כל השדות!'];
            }
            $phone_number = $data['phone_number'];
            if(strlen($phone_number) != 10) {
                return ['error' => 'מספר הטלפון לא באורך תקין!'];
            }
            if($this->phone_regex) {
                if(!preg_match($this->phone_regex, $phone_number)) {
                    return ['error' => 'מספר טלפון לא בפורמט תקין!'];
                }
            }
            
            $phone_number = '972'.substr($phone_number, 1);     // api expects 9725xxx

            $user_info = $this->UserInfoByPhone($phone_number);
            if(is_array($user_info)) {
                return ['error' => 'משתמש עם מספר טלפון זה קיים כבר במערכת!'];
            }

            $this->method = 'GET';
            $this->post_fields = [
                'ProgramId' => 561,
                'PhoneNumber' => $phone_number,
                'ChangeIfExists' => 'true',
                'FirstName' => $data['first_name'],
                'LastName' => $data['last_name'],
                'NoNotification' => 'true',
                'LanguageCode' => 'EN'
            ];

            $api_call = $this->ApiRequest();        // create user at aimpact systems
            
            if(isset($api_call['error'])) {
                return ['error' => $api_call['error']];
            }
            if(isset($api_call['id'])) {
                // only add user locally in our DB if API success.
                /*
                    user id => success, not failed to add.
                */
                try {
                    $user_ip = getUserIP();
                    $sth = $this->db->prepare("INSERT INTO ".SQL_WEB_DB.".`users` (`aimpact_api_user_id`, `first_name`, `last_name`, `phone_number`, `ip_address`, `createdAt`) VALUES (:aimpact_api_user_id, :first_name, :last_name, :phone_number, :ip_address, '".(int)time()."')");
                    $sth->bindParam(':aimpact_api_user_id', $api_call['id'], PDO::PARAM_INT);
                    $sth->bindParam(':first_name', $data['first_name'], PDO::PARAM_STR);
                    $sth->bindParam(':last_name', $data['last_name'], PDO::PARAM_STR);
                    $sth->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
                    $sth->bindParam(':ip_address', $user_ip, PDO::PARAM_STR);
                    $sth->execute();
                    return ['success' => 'משתמש נוצר בהצלחה!'];
                } catch (Exception $e) {
                    writelog($e->getMessage(), 'SQL_DEBUG');
                    return ['error' => 'לא ניתן לרשום משתמש! אנא צור קשר עם התמיכה!'];
                }
            }
            return ['error' => 'שגיאת מערכת! צור קשר!'];
        }
    }
