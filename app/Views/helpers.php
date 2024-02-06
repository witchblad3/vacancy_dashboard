<?php

if (!function_exists('isFavoriteVacancie')) {
    function isFavoriteVacancie($id, $fav)
    {
        foreach ($fav as $f){
            if($f['id'] == $id){
                return true;
            }
        }
        return false;
    }
}

if(!function_exists('beautyDate')){
    function beautyDate($timestamp){
        return date('d.m.Y H:i', strtotime($timestamp));
    }
}
