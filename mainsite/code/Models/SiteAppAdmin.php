<?php

class SiteAppAdmin extends ModelAdmin
{
    private static $managed_models = array('SiteApp');
    private static $url_segment = 'site-apps';
    private static $menu_title = 'Internal Apps';
}
