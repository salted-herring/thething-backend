<?php

class CustomFieldAdmin extends ModelAdmin
{
    private static $managed_models = array('CustomForm');
    private static $url_segment = 'custom-forms';
    private static $menu_title = 'Custom Forms';
}
