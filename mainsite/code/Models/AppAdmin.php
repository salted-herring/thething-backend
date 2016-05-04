<?php

class AppAdmin extends ModelAdmin
{
    private static $managed_models = array('App');
    private static $url_segment = 'apps';
    private static $menu_title = 'Apps';
}
