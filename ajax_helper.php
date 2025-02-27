<?php
	session_start();
	require 'autoloader.php';
	$Website = new Website();
	$Account = new Account();

	const INVALID_ACTION = 'INVALID_ACTION';
	const INVALID_SUB_ACTION = 'INVALID_SUB_ACTION';
	const EXPIRED_CSRF = 'EXPIRED_CSRF';

	$data = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST' && $Website->VerifyAjax()) {
		$action = isset($_POST['action']) ? $_POST['action'] : '';
		$sub_action = isset($_POST['sub_action']) ? $_POST['sub_action'] : '';
		switch($action){
            case 'users':
				switch($sub_action) {
					case 'create_user':
						$post_fields = [
							'first_name' => isset($_POST['first_name']) ? trim($_POST['first_name']) : '',
							'last_name' => isset($_POST['last_name']) ? trim($_POST['last_name']) : '',
							'phone_number' => isset($_POST['phone']) ? trim($_POST['phone']) : ''
						];
						$data = $Account->CreateUser($post_fields);
						break;
					case 'edit_user':
						$post_fields = [
							'user_id' => isset($_POST['user_id']) ? (int)trim($_POST['user_id']) : '',
							'first_name' => isset($_POST['first_name']) ? trim($_POST['first_name']) : '',
							'last_name' => isset($_POST['last_name']) ? trim($_POST['last_name']) : '',
							'phone_number' => isset($_POST['phone']) ? trim($_POST['phone']) : ''
						];
						$data = $Account->EditUser($post_fields);
						break;
					case 'delete_user':
						$post_fields = [
							'user_id' => isset($_POST['user_id']) ? (int)trim($_POST['user_id']) : ''
						];
						$data = $Account->DeleteUser($post_fields);
						break;
					case 'sync_user':
						$post_fields = [
							'user_id' => isset($_POST['user_id']) ? (int)trim($_POST['user_id']) : ''
						];
						$data = $Account->SyncUser($post_fields);
						break;
					default:
						$data['error'] = INVALID_SUB_ACTION;
				}
                break;
            default:
			$data['error'] = INVALID_ACTION;
		}
	}
	else{
		$data['error'] = EXPIRED_CSRF;
		$data['type'] = 1;
	}
	echo json_encode($data);
