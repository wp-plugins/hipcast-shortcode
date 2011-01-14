<?php
/*
Plugin Name: Hipcast Shortcode
Plugin URI: http://hipcast.com
Description: A simple plugin to allow Hipcast.com users to post directly to WordPress blogs using shortcodes.
Version: 1.0
Author: VocalSpace LLC
Author URI: http://www.vocalspace.com
License: GPL2
*/

add_shortcode('hipcast', 'hipcast_shortcode');
function hipcast_shortcode($attributes){
    $HIPCAST_SWF_URL = "http://www.hipcast.com/client/player/en_us/xplayer01.swf";



    $object = new SimpleXMLElement('<object/>');
    $embed = $object->addChild('embed');


    // get the movie request parameters
    $swfParameters = "";
    if (isset($attributes['swfparameters']) == true){
        $swfParameters = "?" . $attributes['swfparameters'];
        $swfParameters = str_replace('&amp;', '&', $swfParameters);
        unset($attributes['swfparameters']);
    }


    // set up properties for the object itself
    if (isset($attributes['style'])){        
        $objectAttributes['style'] = $attributes['style'];
        unset($attributes['style']);
    }
    // set the id on the movie
    if (isset($attributes['id'])){
        $objectAttributes['id'] = $attributes['id'];
        $objectAttributes['name'] = $attributes['name'];
        unset($attributes['id']);
    }

    // set the class
    if (isset($attributes['class'])){
        $objectAttributes['class'] = $attributes['class'];
        unset($attributes['class']);
    }

    // set the movie paramater for the object tag
    $movie = $object->addChild('param');
    $movie->addAttribute('name', 'movie');
    $movie->addAttribute('value', $HIPCAST_SWF_URL . $swfParameters);

    // set the movie parameter for the embed tag
    $embed->addAttribute('src', $HIPCAST_SWF_URL . $swfParameters);

    // set attributes for the object and embed tag
    foreach ($objectAttributes as $key=>$value){
        $object->addAttribute($key, $value);
        $embed->addAttribute($key, $value);
    }

    // take the rest of the attributes and build the object and embed parameters
    foreach($attributes as $key=>$value){
        // convert to &'s
        $value = str_replace("&amp;", "&", $value);

        // generate the parameter object
        $param = $object->addChild('param');

        // set the name and value of the parameter
        $param->addAttribute('name', $key);
        $param->addAttribute('value', $value);

        // add the attribute to the embed tag
        $embed->addAttribute($key, $value);

    }

    return $object->asXML();
}
?>