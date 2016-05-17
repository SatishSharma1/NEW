<?php

class Example_memcached extends CI_Controller
{

    function __construct() {
        parent::__construct();
        // $this->load->model('campaignmodel','',TRUE);
     //   echo phpinfo();

        if(class_exists('Memcache')){
  echo "Satosh sharma";
}
        $this->load->model('usermodel','',TRUE);
        //$this->load->model('calender/Member_model');
        //$this->load->library('layout');
        //$this->load->library('Auth');
        //$this->config->load('email');
        $this->load->config('email', TRUE);
        $this->load->helper('form');
    }

    public function Example_memcached()
    {
      //  parent::Controller();
    }

    public function test()
    {
        // Load library
        $this->load->library('memcached_library');

        // Lets try to get the key
        $results = $this->memcached_library->get('test');

        // If the key does not exist it could mean the key was never set or expired
        if (!$results) {
            // Modify this Query to your liking!
            $query = $this->db->get('callLogs', 7000);

            // Lets store the results
            $this->memcached_library->add('test', $query->result());

            // Output a basic msg
            echo 'Alright! Stored some results from the Query... Refresh Your Browser';
        } else {
            // Output
            var_dump($results);

            // Now let us delete the key for demonstration sake!
            $this->memcached_library->delete('test');
        }
    }

    public function stats()
    {
        $this->load->library('memcached_library');

        echo $this->memcached_library->getversion();
        echo '<br/>';

        // We can use any of the following "reset, malloc, maps, cachedump, slabs, items, sizes"
        $p = $this->memcached_library->getstats('sizes');

        var_dump($p);
    }
}
