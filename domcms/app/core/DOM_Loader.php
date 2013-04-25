<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class DOM_Loader extends CI_Loader{

    function MY_Loader(){
        parent::CI_Loader();
    }

    function dom_view($view,$view_path,$vars=array(),$return=false){
        $file_ext = pathinfo($view,PATHINFO_EXTENSION);
        $view = ($file_ext == '') ? $view.EXT : $view;

        $data=array(
            '_ci_path' => $view_path.'/'.$view,
            '_ci_vars' => $this->_ci_object_to_array($vars),
            '_ci_return' => $return
        );

        return $this->_ci_load($data);
    }
}