<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists('wdk_get_languages'))
{

    function wdk_get_languages($lang_code_id = NULL)
    {
        
        
        $langauges = array();
        $langauges[1] = array('title'=>get_bloginfo("language"), 'lang_code'=>'en', 'id'=>1);
        //$langauges[2] = array('title'=>__('Croatian', 'wdk_win'), 'lang_code'=>'hr', 'id'=>2);
        
        // [qTranslate X]
        if(function_exists('qtranxf_getSortedLanguages'))
        {
            global $q_config;
            
            // for wp all import, must be tested
            if(wdk_count($q_config) == 0)
            {
                //error_reporting(0);
                //qtranxf_init_language();
            }

            $all_langs = qtranxf_getSortedLanguages();

            if(wdk_count($all_langs) > 0)
            {
                $langauges = array();
                
                foreach(qtranxf_getSortedLanguages() as $key=>$lang_code)
                {
                    $langauges[$key+1] = array('title'=>$q_config['language_name'][$lang_code], 'lang_code'=>$lang_code, 'id'=>$key+1);
                }
            }

        }
        else{
            
        }
        // [/qTranslate X]

        // [WPML]
        if(function_exists('icl_wdk_get_languages'))
        {
            $langauges = array();
            $wpml_langs = icl_wdk_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');

            $k=0;
            foreach($wpml_langs as $key=>$lang_data)
            {
                $k++;
                $langauges[$k] = array('title'=>$lang_data['translated_name'], 'lang_code'=>$lang_data['code'], 'id'=>$k);
            }
        }

        // WPML 4
                // Also used for polylang, required activated "WPML compatibility mode of Polylang"
        elseif(has_filter('wpml_active_languages'))
        {
            
            $wpml_langs = array();
            if(function_exists('PLL') && null !== PLL()){
                if(isset( PLL()->links ))
                    $wpml_langs = apply_filters( 'wpml_active_languages', NULL );
            } else {
                $wpml_langs = apply_filters( 'wpml_active_languages', NULL );
            }
            
            if(empty($wpml_langs))
                $wpml_langs = get_query_var('lang', 'all');
                

            $k=0;
            if(count($wpml_langs) && !is_string($wpml_langs)){
                $langauges = array(); // for polylang, WPML don't need that because of strange 
                                  // default language mechanism in polylang
                                  
                foreach($wpml_langs as $key=>$lang_data)
                {
                    $k++;
    
                    // for polylang
                    if(!isset($lang_data['code']) && isset($lang_data['language_code'])) 
                        $lang_data['code'] = $lang_data['language_code'];
    
                    if(empty($lang_data['translated_name']) && !empty($lang_data['native_name']))
                        $lang_data['translated_name'] = $lang_data['native_name'];
                    // for polylang, end
    
                    $langauges[$lang_data['id']] = array('title'=>$lang_data['translated_name'], 'lang_code'=>$lang_data['code'], 'id'=>$lang_data['id']);
                }
            }

        }

        // [/WPML]
        
        if(!empty($lang_code_id))
        {
            if(is_numeric($lang_code_id))
            {
                foreach($langauges as $lang)
                {
                    if($lang['id'] == $lang_code_id)
                    {
                        return $lang['lang_code'];
                    }
                }
            }
            else
            {
                foreach($langauges as $lang)
                {
                    if($lang['lang_code'] == $lang_code_id)
                    {
                        return $lang['id'];
                    }
                }
            }
            
            return FALSE;
        }
        
        return $langauges;
    }

}

if ( ! function_exists('wdk_current_language'))
{

    function wdk_current_language()
    {
        // [qTranslate X]
        if(function_exists('qtranxf_getLanguage'))
        {
            return qtranxf_getLanguage();
        }
        
        // [WPML]
        if(function_exists('icl_wdk_get_languages'))
        {
            $langauges = array();
            $wpml_langs = icl_wdk_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
            foreach($wpml_langs as $key=>$lang_data)
            {
                if($lang_data['active'] == 1)
                    return $lang_data['code'];
            }
        }

        // WPML 4

        elseif(has_filter('wpml_current_language'))
        {
            $langauges = array();
            $wpml_langs = apply_filters( 'wpml_current_language', NULL ); // returning only lang code
            
            if($wpml_langs !== FALSE)
                return $wpml_langs;
        }

        // [/WPML]
        
        return 'en';
    }

}

if ( ! function_exists('wdk_current_language_id'))
{

    function wdk_current_language_id()
    {
        $lang_id = wdk_get_languages(wdk_current_language());
        
        if(is_numeric($lang_id))return $lang_id;
        
		return wdk_default_language_id();
    }

}

if ( ! function_exists('wdk_default_language_id'))
{
    function wdk_default_language_id()
    {
        $lang_id = wdk_get_languages(wdk_default_language());
        
        if(is_numeric($lang_id))return $lang_id;
        
        return '1';
    }
}

/*
if ( ! function_exists('wdk_get_language_name'))
{
    function wdk_get_language_name($id_or_code)
    {
        $langs = wdk_get_languages();
        
        foreach($langs as $lang)
        {
            if($lang['lang_code'] == $id_or_code || $lang['id'] == $id_or_code)
            {
                return $lang['title'];
            }
        }

        return '';
    }
}
*/



/*
if ( ! function_exists('wdk_get_language_url'))
{

    function wdk_get_language_url($lang_code)
    {
        // [qTranslate X]
        if(function_exists('qtranxf_convertURL'))
        {
            return qtranxf_convertURL('', $lang_code, false, true);
        }
        // [/qTranslate X]
        
        // [WPML]
        if(function_exists('icl_wdk_get_languages'))
        {
            $langauges = array();
            $wpml_langs = icl_wdk_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
            
            foreach($wpml_langs as $key=>$lang_data)
            {
                if($lang_data['code'] == $lang_code)
                    return $lang_data['url'];
            }
        }

        // WPML 4

        elseif(has_filter('wpml_active_languages'))
        {
            $langauges = array();
            $wpml_langs = apply_filters( 'wpml_active_languages', NULL );

            foreach($wpml_langs as $key=>$lang_data)
            {
                // for polylang
                if(!isset($lang_data['code']) && isset($lang_data['language_code'])) 
                $lang_data['code'] = $lang_data['language_code'];
                if(empty($lang_data['translated_name']) && !empty($lang_data['native_name']))
                    $lang_data['translated_name'] = $lang_data['native_name'];
                // for polylang, end

                $url = wdk_wpml_ls_language_url( $lang_data['url'], $lang_data );
                $lang_data['url'] = $url;

                if($lang_data['code'] == $lang_code)
                    return $url;
            }

        }

        // [/WPML]
        
    }
    
}*/


?>