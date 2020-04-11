<?php 
if ($f == 'pages') {
    if ($s == 'create_page') {
        if (empty($_POST['page_name']) || empty($_POST['page_title']) || empty(Wo_Secure($_POST['page_title'])) || Wo_CheckSession($hash_id) === false) {
            $errors[] = $error_icon . $wo['lang']['please_check_details'];
        } else {
            $is_exist = Wo_IsNameExist($_POST['page_name'], 0);
            if (in_array(true, $is_exist)) {
                $errors[] = $error_icon . $wo['lang']['page_name_exists'];
            }
            if (in_array($_POST['page_name'], $wo['site_pages'])) {
                $errors[] = $error_icon . $wo['lang']['page_name_invalid_characters'];
            }
            if (strlen($_POST['page_name']) < 5 OR strlen($_POST['page_name']) > 32) {
                $errors[] = $error_icon . $wo['lang']['page_name_characters_length'];
            }
            if (!preg_match('/^[\w]+$/', $_POST['page_name'])) {
                $errors[] = $error_icon . $wo['lang']['page_name_invalid_characters'];
            }
            if (empty($_POST['page_category'])) {
                $_POST['page_category'] = 1;
            }
        }
        if (empty($errors)) {
            $re_page_data  = array(
                'page_name' => Wo_Secure($_POST['page_name']),
                'user_id' => Wo_Secure($wo['user']['user_id']),
                'page_title' => Wo_Secure($_POST['page_title']),
                'page_description' => Wo_Secure($_POST['page_description']),
                'page_category' => Wo_Secure($_POST['page_category']),
                'active' => '1'
            );
            $register_page = Wo_RegisterPage($re_page_data);
            if ($register_page) {
                $data = array(
                    'status' => 200,
                    'location' => Wo_SeoLink('index.php?link1=timeline&u=' . Wo_Secure($_POST['page_name']))
                );
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'update_information_setting') {
        if (!empty($_POST['page_id']) && Wo_CheckSession($hash_id) === true) {
            $PageData = Wo_PageData($_POST['page_id']);
            if (!empty($_POST['website'])) {
                if (!filter_var($_POST['website'], FILTER_VALIDATE_URL)) {
                    $errors[] = $error_icon . $wo['lang']['website_invalid_characters'];
                }
            }
            if (empty($errors)) {
                $Update_data = array(
                    'website' => $_POST['website'],
                    'page_description' => $_POST['page_description'],
                    'company' => $_POST['company'],
                    'address' => $_POST['address'],
                    'phone' => $_POST['phone']
                );
                if (Wo_UpdatePageData($_POST['page_id'], $Update_data)) {
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . $wo['lang']['setting_updated']
                    );
                }
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'update_sociallink_setting') {
        if (!empty($_POST['page_id']) && Wo_CheckSession($hash_id) === true) {
            $PageData = Wo_PageData($_POST['page_id']);
            if (empty($errors)) {
                $Update_data = array(
                    'facebook' => $_POST['facebook'],
                    'instgram' => $_POST['instgram'],
                    'twitter' => $_POST['twitter'],
                    'linkedin' => $_POST['linkedin'],
                    'vk' => $_POST['vk'],
                    'youtube' => $_POST['youtube']
                );
                if (Wo_UpdatePageData($_POST['page_id'], $Update_data)) {
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . $wo['lang']['setting_updated']
                    );
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_images_setting') {
        if (isset($_POST['page_id']) && Wo_CheckSession($hash_id) === true) {
            $Userdata = Wo_PageData($_POST['page_id']);
            if (!empty($Userdata['page_id'])) {
                if (isset($_FILES['avatar']['name'])) {
                    if (Wo_UploadImage($_FILES["avatar"]["tmp_name"], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['page_id'], 'page') === true) {
                        $page_data = Wo_PageData($_POST['page_id']);
                    }
                }
                if (isset($_FILES['cover']['name'])) {
                    if (Wo_UploadImage($_FILES["cover"]["tmp_name"], $_FILES['cover']['name'], 'cover', $_FILES['cover']['type'], $_POST['page_id'], 'page') === true) {
                        $page_data = Wo_PageData($_POST['page_id']);
                    }
                }
                if (empty($errors)) {
                    $Update_data = array(
                        'active' => '1'
                    );
                    if (Wo_UpdatePageData($_POST['page_id'], $Update_data)) {
                        $userdata2 = Wo_PageData($_POST['page_id']);
                        $data      = array(
                            'status' => 200,
                            'message' => $success_icon . $wo['lang']['setting_updated'],
                            'cover' => $userdata2['cover'],
                            'avatar' => $userdata2['avatar']
                        );
                    }
                }
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
    }
    if ($s == 'update_general_settings') {
        if (!empty($_POST['page_id']) && Wo_CheckSession($hash_id) === true) {
            $PageData = Wo_PageData($_POST['page_id']);
            if (empty($_POST['page_name']) OR empty($_POST['page_category']) OR empty($_POST['page_title']) OR empty(Wo_Secure($_POST['page_title']))) {
                $errors[] = $error_icon . $wo['lang']['please_check_details'];
            } else {
                if ($_POST['page_name'] != $PageData['page_name']) {
                    $is_exist = Wo_IsNameExist($_POST['page_name'], 0);
                    if (in_array(true, $is_exist)) {
                        $errors[] = $error_icon . $wo['lang']['page_name_exists'];
                    }
                }
                if (in_array($_POST['page_name'], $wo['site_pages'])) {
                    $errors[] = $error_icon . $wo['lang']['page_name_invalid_characters'];
                }
                if (strlen($_POST['page_name']) < 5 || strlen($_POST['page_name']) > 32) {
                    $errors[] = $error_icon . $wo['lang']['page_name_characters_length'];
                }
                if (!preg_match('/^[\w]+$/', $_POST['page_name'])) {
                    $errors[] = $error_icon . $wo['lang']['page_name_invalid_characters'];
                }
                if (empty($_POST['page_category'])) {
                    $_POST['page_category'] = 1;
                }
                $call_action_type = 0;
                if (!empty($_POST['call_action_type'])) {
                    if (array_key_exists($_POST['call_action_type'], $wo['call_action'])) {
                        $call_action_type = $_POST['call_action_type'];
                    }
                }
                if (!empty($_POST['call_action_type_url'])) {
                    if (!filter_var($_POST['call_action_type_url'], FILTER_VALIDATE_URL)) {
                        $errors[] = $error_icon . $wo['lang']['call_action_type_url_invalid'];
                    }
                }
                if (empty($errors)) {
                    $Update_data = array(
                        'page_name' => $_POST['page_name'],
                        'page_title' => $_POST['page_title'],
                        'page_category' => $_POST['page_category'],
                        'call_action_type' => $call_action_type,
                        'call_action_type_url' => $_POST['call_action_type_url']
                    );
                    $array       = array(
                        'verified' => 1,
                        'notVerified' => 0
                    );
                    if (!empty($_POST['verified'])) {
                        if (array_key_exists($_POST['verified'], $array)) {
                            $Update_data['verified'] = $array[$_POST['verified']];
                        }
                    }
                    if (Wo_UpdatePageData($_POST['page_id'], $Update_data)) {
                        $data = array(
                            'status' => 200,
                            'message' => $success_icon . $wo['lang']['setting_updated'],
                            'link' => $wo['site_url'].'/'.$_POST['page_name'],
                            'data_ajax' => '?link1=timeline&u='.$_POST['page_name']
                        );
                    }
                }
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'delete_page') {
        if (!empty($_POST['page_id']) && Wo_CheckSession($hash_id) === true) {
            if (!Wo_HashPassword($_POST['password'], $wo['user']['password']) && !Wo_CheckPageAdminPassword($_POST['password'], $_POST['page_id'])) {
                $errors[] = $error_icon . $wo['lang']['current_password_mismatch'];
            }
            if (empty($errors)) {
                if (Wo_DeletePage($_POST['page_id']) === true) {
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . $wo['lang']['page_deleted'],
                        'location' => Wo_SeoLink('index.php?link1=pages')
                    );
                }
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'add_admin') {
        $data = array(
            'status' => 304
        );
        if (isset($_GET['page_id']) && isset($_GET['user_id'])) {
            $page = Wo_Secure($_GET['page_id']);
            $user = Wo_Secure($_GET['user_id']);
            $code = Wo_AddPageAdmin($user, $page);
            if ($code === 1) {
                $data['status'] = 200;
                $data['code']   = 1;
            } else if ($code === 0) {
                $data['status'] = 200;
                $data['code']   = 0;
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'get_mbr' && isset($_GET['name']) && isset($_GET['page']) && is_numeric($_GET['page'])) {
        $data      = array(
            'status' => 304
        );
        $name      = Wo_Secure($_GET['name']);
        $page      = Wo_Secure($_GET['page']);
        $users     = Wo_GetUsersByName($name);
        $html      = '';
        $page_data = Wo_PageData($page);
        if (is_array($users) && count($users) > 0) {
            foreach ($users as $wo['member']) {
                $wo['member']['page_id']       = $page;
                $wo['member']['is_page_onwer'] = $page_data['is_page_onwer'];
                $html .= Wo_LoadPage('page-setting/admin-list');
            }
            $data['status'] = 200;
            $data['html']   = $html;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'get_more_likes') {
        $html = '';
        if (isset($_GET['user_id']) && isset($_GET['after_last_id'])) {
            foreach (Wo_GetLikes($_GET['user_id'], 'profile', 10, $_GET['after_last_id']) as $wo['PageList']) {
                $html .= Wo_LoadPage('timeline/likes-list');
            }
        }
        $data = array(
            'status' => 200,
            'html' => $html
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'get_next_page') {
        $html    = '';
        $page_id = (!empty($_GET['page_id'])) ? $_GET['page_id'] : 0;
        foreach (Wo_PageSug(1, $page_id) as $wo['PageList']) {
            $wo['PageList']['user_name'] = $wo['PageList']['name'];
            $html                        = Wo_LoadPage('sidebar/sidebar-home-page-list');
        }
        $data = array(
            'status' => 200,
            'html' => $html
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'get_likes') {
        $html = '';
        if (!empty($_GET['user_id'])) {
            foreach (Wo_GetLikes($_GET['user_id'], 'sidebar', 12) as $wo['PageList']) {
                $wo['PageList']['user_name'] = @mb_substr($wo['PageList']['name'], 0, 10, "utf-8");
                $html .= Wo_LoadPage('sidebar/sidebar-page-list');
            }
            $data = array(
                'status' => 200,
                'html' => $html
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'rate_page' && isset($_POST['page_id']) && isset($_POST['val'])) {
        $val  = Wo_Secure($_POST['val']);
        $id   = Wo_Secure($_POST['page_id']);
        $text = Wo_Secure($_POST['text']);
        $data = array(
            'status' => 304,
            'message' => $wo['lang']['page_rated']
        );
        if (Wo_RatePage($id, $val, $text)) {
            $data['status'] = 200;
            $data['val']    = $val;
            unset($data['message']);
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'load_reviews' && isset($_GET['page']) && isset($_GET['after_id'])) {
        $page_id = Wo_Secure($_GET['page']);
        $id      = Wo_Secure($_GET['after_id']);
        $data    = array(
            'status' => 404
        );
        $reviews = Wo_GetPageReviews($page_id, $id);
        $html    = '';
        if (count($reviews) > 0) {
            foreach ($reviews as $wo['review']) {
                $html .= Wo_LoadPage('page/review-list');
            }
            $data['status'] = 200;
            $data['html']   = $html;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
