<?PHP
class TestAdmin extends ModelAdmin {
   private static $managed_models = array('TestModel');
   static $url_segment = 'test';
   static $menu_title = 'Test Model Management';
}