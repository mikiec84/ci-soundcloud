<?php
class Tracks extends MY_Controller {

    /**
     * Constructor.
     *
     * @access public
     */
    function Tracks() {
        parent::MY_Controller();

        $this->load->library('form_validation');
        $this->load->helper('form');
    }

    /**
     * Upload handler.
     *
     * @access public
     */
    function add() {
        $this->form_validation->set_rules('title', 'title', 'required');

        // make sure a title is supplied.
        if (!$this->form_validation->run()) {
            show_error(validation_errors());
        }

        $extension = $this->_get_track_extension();
        $upload_config = array(
            'upload_path' => '/tmp/',
            'allowed_types' => $extension
        );

        // make sure the uploaded audio format is supported.
        try {
            $mime_type = $this->soundcloud->getAudioMimeType($extension);
        } catch (Services_Soundcloud_Unsupported_Audio_Format_Exception $e) {
            show_error($e->getMessage());
        }

        $this->load->library('upload', $upload_config);

        if ($this->upload->do_upload('track')) {
            $track = $this->upload->data();

            $track_data = array(
                'track[sharing]' => 'private',
                'track[title]' => $this->input->post('title'),
                'track[tags]' => (strlen($this->input->post('tags')))
                    ? $this->input->post('tags')
                    : null,
                'track[asset_data]' => '@' . $track['full_path']
            );

            // perform the actual upload to soundcloud.
            try {
                $response = json_decode(
                    $this->soundcloud->post('tracks', $track_data),
                    true
                );
            } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                show_error($e->getMessage());
            }

            $this->data['view']['track'] = $response;

            $this->view(
                array(
                    'controller' => 'tracks',
                    'action' => 'new'
                )
            );
        } else {
            show_error($this->upload->display_errors());
        }
    }

    /**
     * Upload form.
     *
     * @access public
     */
    function index() {
        $this->data['view']['form_action'] = $this->_redirect_uri('tracks/add');

        $this->view();
    }

    /**
     * Retreive the extension of the uploaded file.
     *
     * @access private
     *
     * @return mixed
     */
    function _get_track_extension() {
        if (array_key_exists('track', $_FILES)
            && array_key_exists('name', $_FILES['track'])
        ) {
            return pathinfo($_FILES['track']['name'], PATHINFO_EXTENSION);
        } else {
            return false;
        }
    }

}
