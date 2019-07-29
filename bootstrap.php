<?php
  
$this->bind("/api/grp/:group", function($params) {  
    
    header("Content-Type: application/json");
    $group = $params["group"];
    $allS = [];
    $allC = [];
    $allC = $this->module("collections")->collections();
    $allS = $this->module("singletons")->singletons();
    $allF = $this->module("forms")->forms();
    $user = $this->module('cockpit')->getUser();

    $options = [];

    if ($lang = $this->param("lang", false)) $options["lang"] = $lang;
    $options["populate"] = true;
    if ($ignoreDefaultFallback = $this->param("ignoreDefaultFallback", false)) $options["ignoreDefaultFallback"] = $ignoreDefaultFallback;
    if ($user) $options["user"] = $user;

    foreach($allS as $key => $value) { 
        $singleton = $this->module("singletons")->getData($key, $options);
        if($value["group"] == $group){ 
            $singletons[$key] = $singleton; 
        }
    }
    
    foreach($allC as $key => $value) { 
        $collection = $this->module("collections")->find($key, $options);
        if($value["group"] == $group){
            $collections[$key] = $collection;
            $collections['schema'] = $value['fields'];
        }
    }

    foreach($allF as $key => $value) {
        $form = $this->module("forms")->find($key, $options);
        if($key == $group){
            $forms[$key] = $form;
            $forms['schema'] = $value;
        }
    }

    $returnArray = [];
    $returnArray["singletons"] = $singletons;
    $returnArray["collections"] = $collections;
    $returnArray["forms"] = $forms;
    echo json_encode($returnArray);
    
    
    exit();


});
