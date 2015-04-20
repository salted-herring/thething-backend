<?php
	/**
	 * @file PageExtensions.php
	 * ------------------------
	 * Generic extensions to page controller.
	 * */
	class PageControllerDecorator extends Extension {
		public function getCSS() {
			$css = strtolower($this->owner->data()->class);
			
			if(!file_exists(BASE_PATH . $this->owner->ThemeDir() . '/css/' . $css . '.css')) {
				$css = 'styles';
			}
			
			return sprintf('<link rel="stylesheet" href="%s" />', $this->owner->ThemeDir() . '/css/' . $css . '.css');
		}
		
		public function getRequireJS() {
			
			if(Director::isDev()) {
				$script = "<script src=\"" . $this->owner->ThemeDir() . "/js/lib/require.js\"></script>\n";
				$script .= "<script>\n";
				$script .= "require([\"" . $this->owner->ThemeDir() . "/js/dev.config\"], function (common) {";
				$script.= "require([\"pagetypes/" . strtolower($this->owner->ClassName) . "\"]);";
				$script.= "});";
				$script .= "</script>";
	
				return $script;
				
			} else {
				$script = '<script src="' . $this->owner->ThemeDir() . '/js/lib/require.js" data-main="' 
							. $this->owner->ThemeDir() . '/%s"></script>';

				return sprintf($script, 'build/pagetypes/' . strtolower($this->owner->ClassName));
			}
		}
		
		public function getGACode() {
			$GA = SiteConfig::current_site_config()->GoogleAnalyticsCode;
			
			if($GA) {
				return $GA;
			}
			return false;
		}
		
		public function getSiteVersion() {
			$version = SiteConfig::current_site_config()->SiteVersion;
			
			if($version) {
				return $version;
			}
			return false;
		}
		
		public function getCustomGACode() {
			$custom = SiteConfig::current_site_config()->GoogleCustomCode;
			
			if($custom) {
				return $custom;
			}
			return false;
		}
		
		public function isMobile() {
			$mobi = new Mobile_Detect();
			return $mobi->isMobile();
		}
	}