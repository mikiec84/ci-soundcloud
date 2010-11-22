<?php
class MY_Controller extends Controller {

    var $data = array(
        'all' => array(),
        'footer' => array(),
        'header' => array(),
        'view' => array()
    );

    function MY_Controller() {
        parent::Controller();

        $this->load->library('predis');

        try {
            $this->predis->connect($this->config->item('redis'));
        } catch (Predis_CommunicationException $e) {
            show_error('Redis connection refused.');
        }

        $this->load->library(
            'soundcloud',
            array(
                'client_id' => $this->config->item('client_id'),
                'client_secret' => $this->config->item('client_secret'),
                'redirect_uri' => $this->config->item('redirect_uri')
            )
        );

        $user = get_cookie('user');

        if ($user && $this->user->find_by_hash($user)) {
            $this->user = $this->user->find_by_hash($user);
            $this->data['all']['user'] = array();

            // set the access token in order to authicate.
            $this->soundcloud->setAccessToken($this->user->access_token);

            // refresh expired token.
            if ($this->user->expires < time()
                && !preg_match('/sessions/', current_url())
            ) {
                redirect($this->_redirect_uri('sessions/refresh'));
            }

            foreach (User::$attributes as $attribute) {
                if (property_exists($this->user, $attribute)) {
                    $this->data['all']['user'][$attribute] = $this->user->{$attribute};
                }
            }
        } elseif (!preg_match('/sessions/', current_url())) {
            $this->data['user'] = false;

            redirect(
                $this->_redirect_uri(
                    'sessions/connect',
                    array(
                        'redirect_uri' => $this->_redirect_uri(current_url())
                    )
                )
            );
        } else {
            $this->data['all']['user'] = false;
        }
    }

    function view($template = null) {
        $output = '';
        $template = implode(
            '/',
            ($template)
                ? array_values($template)
                : array(
                    'controller' => $this->router->class,
                    'method' => $this->router->method
                )
        );
        $template .= '.php';

        // header template.
        $output .= $this->load->view(
            'layouts/header.php',
            array_merge($this->data['all'], $this->data['header']),
            true
        );
        $output .= "\n";
        // current template.
        $output .= $this->load->view(
            $template,
            array_merge($this->data['all'], $this->data['view']),
            true
        );
        $output .= "\n";
        // footer template.
        $output .= $this->load->view(
            'layouts/footer.php',
            array_merge($this->data['all'], $this->data['footer']),
            true
        );
        // trim all tabs and empty lines in order reduce the size of the response.
        $output = preg_replace(
            array('/[\t\s]+(\<)/', '/\n\s*\n/'),
            array("\n\\1", "\n"),
            $output
        );

        echo $output;
    }

    /**
     * Generate absolute redirect uri since the redirect function doesn't
     * handle relative uri that well.
     *
     * @access protected
     *
     * @param string $uri Relative uri path
     * @param array Optional query string parameters
     *
     * @return string $url
     */
    protected function _redirect_uri($uri = '', $params = null) {
        $url = (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on')
            ? 'https'
            : 'http';
        $url .= '://';
        $url .= $_SERVER['HTTP_HOST'];
        $url .= (array_key_exists('SERVER_PORT', $_SERVER) && (int)$_SERVER['SERVER_PORT'] != 80)
            ? ':' . $_SERVER['SERVER_PORT']
            : '';
        $url .= str_replace(
            basename($_SERVER['SCRIPT_NAME']),
            '',
            $_SERVER['SCRIPT_NAME']
        );
        $url .= trim($uri, '/');
        $url .= ($params) ? '?' . http_build_query($params) : '';

        return $url;
    }

}
