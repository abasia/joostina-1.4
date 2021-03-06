<?php
/**
 * @package Joostina BOSS
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina BOSS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Joostina BOSS основан на разработках Jdirectory от Thomas Papin
 */
defined('_VALID_MOS') or die();

    class BossSelectPlugin {
        
        //имя типа поля в выпадающем списке в настройках поля
        var $name = 'Drop Down (Single Select)';
        
        //тип плагина для записи в таблицы
        var $type = 'BossSelectPlugin';
	
        //отображение поля в категории
        function getListDisplay($directory, $content, $field, $field_values, $itemid, $conf) {
            return $this->getDetailsDisplay($directory, $content, $field, $field_values, $itemid, $conf);
        }

        //отображение поля в контенте
        function getDetailsDisplay($directory, $content, $field, $field_values, $itemid, $conf) {
            $fieldname = $field->name;
            $value = (isset ($content->$fieldname)) ? $content->$fieldname : '';
            $return = '';
            if (isset($field_values)) {

                if(!empty($field->text_before))
                    $return .= '<span>'.$field->text_before.'</span>';
                if(!empty($field->tags_open))
                    $return .= html_entity_decode($field->tags_open);

                for ($i = 0, $nb = count($field_values); $i < $nb; $i++) {
                    $fieldvalue = @$field_values[$i]->fieldvalue;
                    $fieldtitle = @$field_values[$i]->fieldtitle;

                    if (strpos($value, $fieldvalue) !== false) {
                        $return .= $fieldtitle;
                        if(!empty($field->tags_separator) && $i < ($nb-1))
                            $return .= html_entity_decode($field->tags_separator);
                    }
                }

                if(!empty($field->tags_close))
                    $return .= html_entity_decode($field->tags_close);
                if(!empty($field->text_after))
                    $return .= '<span>'.$field->text_after.'</span>';
            }
            return $return;
        }

        //функция вставки фрагмента ява-скрипта в скрипт
        //сохранения формы при редактировании контента с фронта.
        function addInWriteScript($field){

        }

        //отображение поля в админке в редактировании контента
        function getFormDisplay($directory, $content, $field, $field_values, $nameform = 'adminForm', $mode = "write") {
            $fieldname = $field->name;
            $value = (isset ($content->$fieldname)) ? $content->$fieldname : '';
            $strtitle = htmlentities(jdGetLangDefinition($field->title), ENT_QUOTES, 'utf-8');
            if (($mode == "write") && ($field->editable == 0))
                    $disabled = "disabled=true";
                else
                    $disabled = "";

                if (($mode == "write") && ($field->required == 1))
                    $return = "<select id='" . $field->name . "' name='" . $field->name . "' mosReq='1' mosLabel='" . $strtitle . "' class='boss_required' $disabled>\n";
                else
                    $return = "<select id='" . $field->name . "' name='" . $field->name . "' mosLabel='" . $strtitle . "' class='boss' $disabled>\n";
				$return .= "<option value='' >".BOSS_SELECT."</option>\n";
                if (isset($field_values[$field->fieldid])) {
                    foreach ($field_values[$field->fieldid] as $v) {
                        $ftitle = jdGetLangDefinition($v->fieldtitle);
						
						$selected = "";
						$sel_val = mosGetParam($_REQUEST, $field->name, '');
						
                        if ($mode == "write" && ($value == $v->fieldvalue || $value == $ftitle)){
							$selected = "selected='selected'";								
						}
						if ($mode == "search" && ($sel_val == $v->fieldvalue || $sel_val == $ftitle)) {
							$selected = "selected='selected'";
						}
						$return .= "<option value='" . $v->fieldvalue . "' ".$selected." >$ftitle</option>\n";
                    }
                }
                else{
                     $return .= "<option value=''>&nbsp;</option>\n";
                }

                $return .= "</select>\n";

            return $return;
        }

        function onFormSave($directory, $contentid, $field, $isUpdateMode, $itemid) {
            $return = mosGetParam($_POST, $field->name, "");
            return $return;
        }

        function onDelete($directory, $content) {
            return;
        }

        //отображение поля в админке в настройках поля
        function getEditFieldOptions($row, $directory,$fieldimages,$fieldvalues){
            $return = '
        <script type="text/javascript">
            function insertRow() {
                var oTable = getObject("fieldValuesBody");
                var oRow, oCell, oInput;
                var oCell2, oInput2;
                var i;
                i = document.fieldForm.valueCount.value;
                i++;
                // Create and insert rows and cells into the first body.
                oRow = document.createElement("TR");
                oTable.appendChild(oRow);

                oCell = document.createElement("TD");
                oInput = document.createElement("INPUT");
                oInput.name = "vNames[" + i + "]";
                oInput.setAttribute("mosLabel", "Name");
                oInput.setAttribute("mosReq", 0);
                oCell.appendChild(oInput);
                oCell2 = document.createElement("TD");
                oInput2 = document.createElement("INPUT");
                oInput2.name = "vValues[" + i + "]";
                oInput2.setAttribute("mosLabel", "Name");
                oInput2.setAttribute("mosReq", 0);
                oCell2.appendChild(oInput2);

                oRow.appendChild(oCell);
                oRow.appendChild(oCell2);
                oInput.focus();

                document.fieldForm.valueCount.value = i;
            }
        </script>
        <div id=divValues style="text-align:left;">
        '.BOSS_FIELD_VALUES_EXPLANATION.'
            <input type=button onclick="insertRow();" value="Add a Value"/>
            <table align=left id="divFieldValues" cellpadding="4" cellspacing="1" border="0" width="100%"
                   class="adminform">
                <tr>
                    <th width="20%">'.BOSS_FIELD_VALUE_NAME.'</th>
                    <th width="20%">'.BOSS_FIELD_VALUE_VALUE.'</th>
                </tr>
                <tbody id="fieldValuesBody">
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>';

                $i=0;
                if(count($fieldvalues) > 0){
                    foreach ($fieldvalues as $fieldvalue) {
                    $return .= '
                    <tr>
                        <td width="20%">
                            <input type=text mosReq=0  mosLabel="Name" value="'. stripslashes($fieldvalue->fieldtitle) . '" id="vNames['.$i.']" name="vNames['.$i.']" />
                        </td>
                        <td width="20%">
                            <input type=text mosReq=0 mosLabel="Value" value="' . stripslashes($fieldvalue->fieldvalue) . '" id="vValues['.$i.']" name="vValues['.$i.']" />
                        </td>
                    </tr>';
                    $i++;
                    }
                }
                if ($i > 0)
                    $i--;
                if (count($fieldvalues) < 1) {
                    $return .= '
                    <tr>
                        <td width="20%">
                            <input type=text mosReq=0  mosLabel="Name" value="" id="vNames[0]" name="vNames[0]" />
                        </td>
                        <td width="20%">
                            <input type=text mosReq=0  mosLabel="Value" value="" name="vValues[0]" id="vValues[0]" />
                        </td>
                    </tr>';
                    $i = 0;
                }
                $return .= '
                </tbody>
            </table>
        </div>
        <input type="hidden" name="valueCount" value="'.$i.'"/>
            ';
            return $return;
        }

        //действия при сохранении настроек поля
        function saveFieldOptions($directory, $field) {
            $fieldNames  = $_POST['vNames'];
	        $fieldValues = $_POST['vValues'];
            $database = database::getInstance();
            $j=0;
			$i=0;
            $values = array();
            
			while(isset($fieldNames[$i])) {
				$fieldName  = $fieldNames[$i];
				$fieldValue = $fieldValues[$i];
				$i++;
				if(trim($fieldName)!=null && trim($fieldName)!='') {
					$values[] = "('$field->fieldid','".htmlspecialchars($fieldName)."','".htmlspecialchars($fieldValue)."',$j)";
					$j++;
				}
			}

            $database->setQuery( "INSERT INTO #__boss_".$directory."_field_values "
                . "(fieldid,fieldtitle,fieldvalue,ordering)"
				. " VALUES"
                . implode(', ', $values)
            )->query();
            //если плагин не создает собственных таблиц а пользется таблицами босса то возвращаем false
            //иначе true
            return false;
        }

        //расположение иконки плагина начиная со слеша от корня сайта
        function getFieldIcon($directory) {
            return "/images/boss/$directory/plugins/fields/".__CLASS__."/images/dropdown.png";
        }

        //действия при установке плагина
        function install($directory) {
            return;
        }

        //действия при удалении плагина
        function uninstall($directory) {
            return;
        }

        //действия при поиске
        function search($directory,$fieldName) {
            $search = '';
            $value = mosGetParam( $_REQUEST, $fieldName, "" );
					if ($value != "") {
						$search .= " AND a.$fieldName = '$value'";
					}
            return $search;
        }
    }
?>