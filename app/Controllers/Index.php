<?php

namespace App\Controllers;

class Index extends BaseController {
    public function index() {
        // Add dynamic data to the views
        $data = array(
            'title' => 'FEU Tech ITSO Equipment Management',
            // This can be from the database
        );

        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('view_index', $data);
    }
}

?>