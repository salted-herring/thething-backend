<?php
/*
 * @file FieldProcessor.php
 *
 * Abstract class for processors that are going to be used to process data values
 */
abstract class FieldProcessor {
	
	abstract function process(&$item);
}