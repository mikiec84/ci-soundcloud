<?php
class Sessions extends MY_Controller {

    function Sessions() {
        parent::MY_Controller();
    }

    function callback() {
        // equivalent to $_GET['code']
        $code = (strlen($this->input->get('code')))
            ? $this->input->get('code')
            : null;

        if ($code) {
            try {
                $access_token = $this->soundcloud->accessToken($code);
            } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                show_error($e->getMessage());
            }

            try {
                $me = json_decode($this->soundcloud->get('me'), true);
            } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                show_error($e->getMessage());
            }

            $user_data = array(
                'access_token' => $access_token['access_token'],
                'refresh_token' => $access_token['refresh_token'],
                'expires' => time() + $access_token['expires_in'],
                'id' => $me['id'],
                'username' => $me['username'],
                'name' => $me['full_name'],
                'avatar' => $me['avatar_url']
            );

            if ($user = $this->user->add($user_data)) {
                set_cookie(
                    array(
                        'name' => 'user',
                        'value' => $user->hash(),
                        'expire' => 86400
                    )
                );

                redirect($this->_redirect_uri());
            }
        }
    }

    function connect() {
        $this->data['header']['title'] = 'Connect';
        $this->data['view']['authorize_url'] = $this->soundcloud->getAuthorizeUrl();

        $this->view();
    }

    function disconnect() {
        $this->user->delete();

        delete_cookie('user');

        redirect($this->_redirect_uri('sessions/connect'));
    }

    function refresh() {
        try {
            $access_token = $this->soundcloud->accessTokenRefresh(
                $this->user->refresh_token
            );
        } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
            redirect($this->_redirect_uri('sessions/connect'));
        }

        $access_token['expires'] = $access_token['expires_in'] + time();

        $this->user->update($access_token);

        $this->data['header']['title'] = 'Refresh';
        $this->data['view']['minutes'] = round($access_token['expires_in'] / 60);
        $this->data['view']['redirect_uri'] = $this->input->get('redirect_uri');

        $this->view();
    }

}
