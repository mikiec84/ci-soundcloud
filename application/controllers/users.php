<?php
class Users extends MY_Controller {

    function Users() {
        parent::MY_Controller();
    }

    function show($user_id) {
        try {
            $this->data['view']['me'] = json_decode(
                $this->soundcloud->get('users/' . $user_id),
                true
            );
            $this->data['view']['following'] = json_decode(
                $this->soundcloud->get('users/' . $user_id . '/followings'),
                true
            );
            $this->data['view']['followers'] = json_decode(
                $this->soundcloud->get('users/' . $user_id . '/followers'),
                true
            );
        } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
            show_error($e->getMessage());
        }

        $this->view();
    }

}
