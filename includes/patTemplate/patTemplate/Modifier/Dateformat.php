<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2008 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*
* @version		3.1.0
* @package		patTemplate
* @author		Stephan Schmidt <schst@php.net>
* @license		LGPL
* @link		http://www.php-tools.net
*/
// ������ ������� �������
defined('_VALID_MOS') or die();
class patTemplate_Modifier_Dateformat extends patTemplate_Modifier {
function modify($value,$params = array()) {
if(!isset($params['format'])) {
return $value;
}
if(!preg_match('/^[0-9]+$/',$value)) {
$value = strtotime($value);
}
return strftime($params['format'],$value);
}
}
?>
