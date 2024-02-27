<?php

class GrapesJS_NewBlankPage extends Action{
    // function __construct() {
    //     global $interface;
    //     echo('lalasldljfwelww');
    //     $this->display('new-blank-page.tpl', 'New Blank Page', 'Search/home-sidebar.tpl', false);
    // }
    
    function launch() {
        global $interface;
        $this->display('new-blank-page.tpl', 'New Blank Page', 'Search/home-sidebar.tpl', false);
    }
    function getBreadcrumbs():array {
        $breadcrumbs = [];
        return $breadcrumbs;
    }
}