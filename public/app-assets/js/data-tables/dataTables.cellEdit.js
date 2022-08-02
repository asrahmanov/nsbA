/*! CellEdit 1.0.19
* ©2016 Elliott Beaty - datatables.net/license
*/

/**
* @summary     CellEdit
* @description Make a cell editable when clicked upon
* @version     1.0.19
* @file        dataTables.editCell.js
* @author      Elliott Beaty
* @contact     elliott@elliottbeaty.com
* @copyright   Copyright 2016 Elliott Beaty
*
* This source file is free software, available under the following license:
*   MIT license - http://datatables.net/license/mit
*
* This source file is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
*
* For details please refer to: http://www.datatables.net
*/


function getImageName1 (str)
{
    str = str.replace("&#39;", '"').replace("&#39;", '"');
    return str.substr(str.lastIndexOf('/') + 1, str.lastIndexOf('"') - str.lastIndexOf('/') - 1);
}


jQuery.fn.dataTable.Api.register('MakeCellsEditable()', function (settings) {
    var table = this.table();

    jQuery.fn.extend({
        // UPDATE
        updateEditableCell: function (callingElement) {
            // Need to redeclare table here for situations where we have more than one datatable on the page. See issue6 on github
            var table = $(callingElement).closest("table").DataTable().table();
            var row = table.row($(callingElement).parents('tr'));
            var cell = table.cell($(callingElement).parent());
            var columnIndex = cell.index().column;
            var inputField = getInputField(callingElement);

            if (inputField[0].type == 'file') {
                let files = event.target.files;
                let data = new FormData();
                $.each(files, function(key, value) {
                    data.append(key, value);
                });
                $.ajax({
                    url: 'scripts/upload.php?files',
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                }).done(function(res) {
                    console.log(res);
                });
            }

            // Update
            var newValue = inputField.val().replace('C:\\fakepath\\', '');
            if (!newValue && ((settings.allowNulls) && settings.allowNulls != true)) {
                // If columns specified
                if (settings.allowNulls.columns) {
                    // If current column allows nulls
                    if (settings.allowNulls.columns.indexOf(columnIndex) > -1) {
                        _update(newValue);
                    } else {
                        _addValidationCss();
                    }
                    // No columns allow null
                } else if (!newValue) {
                    _addValidationCss();
                }
                //All columns allow null
            } else {
                _update(newValue);
            }
            function _addValidationCss() {
                // Show validation error
                if (settings.allowNulls.errorClass) {
                    $(inputField).addClass(settings.allowNulls.errorClass)
                } else {
                    $(inputField).css({ "border": "red solid 1px" });
                }
            }
            function _update(newValue) {
                var oldValue = cell.data();
                cell.data(newValue);
                //Return cell & row.
                settings.onUpdate(cell, row, oldValue, table);
            }
            // Get current page
            var currentPageIndex = table.page.info().page;

            //Redraw table
            table.page(currentPageIndex).draw(false);
        },
        // CANCEL
        cancelEditableCell: function (callingElement) {
            var table = $(callingElement.closest("table")).DataTable().table();
            var cell = table.cell($(callingElement).parent());
            // Set cell to it's original value
            cell.data(cell.data());

            // Redraw table
            table.draw();
        }
    });

    // Destroy
    if (settings === "destroy") {
        $(table.body()).off("click", "td");
        table = null;
    }

    if (table != null) {
        // On cell click
        $(table.body()).on('click', 'td', function () {

            var currentColumnIndex = table.cell(this).index().column;

            // DETERMINE WHAT COLUMNS CAN BE EDITED
            if ((settings.columns && settings.columns.indexOf(currentColumnIndex) > -1) || (!settings.columns)) {
                var row = table.row($(this).parents('tr'));
                editableCellsRow = row;

                var cell = table.cell(this).node();
                var oldValue = table.cell(this).data();
                // Sanitize value
                oldValue = sanitizeCellValue(oldValue);

                // Show input
                if (!$(cell).find('input').length && !$(cell).find('select').length && !$(cell).find('textarea').length) {
                    // Input CSS
                    var input = getInputHtml(currentColumnIndex, settings, oldValue);
                    $(cell).html(input.html);
                    if (input.focus) {
                        if (input.isTextarea) {
                            tinymce.init({
                                selector: "textarea",
                                plugins: "advcode, linkchecker, media mediaembed, powerpaste, spellchecker, textcolor",
                                toolbar: "cut copy paste | undo redo | bold italic | underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontsizeselect fontselect | forecolor backcolor | spellchecker",
                                width: "640",
                                height: "500",
                                language_url : 'assets/js/langs/ru.js',
                                // Spellchecker
                                spellchecker_languages: "Russian=ru,Ukrainian=uk,English=en",
                                spellchecker_language: "ru",  // default language
                                spellchecker_rpc_url: "//speller.yandex.net/services/tinyspell"
                            });
                        }
                        $('#ejbeatycelledit').focus();
                    }
                }
            }
        });
    }

});

function getInputHtml(currentColumnIndex, settings, oldValue) {
    var inputSetting, inputType, input, inputCss, confirmCss, cancelCss;

    input = {"focus":true,"html":null}

    if(settings.inputTypes){
        $.each(settings.inputTypes, function (index, setting) {
            if (setting.column == currentColumnIndex) {
                inputSetting = setting;
                inputType = inputSetting.type.toLowerCase();
            }
        });
    }

    if (settings.inputCss) { inputCss = settings.inputCss; }
    if (settings.confirmationButton) {
        confirmCss = settings.confirmationButton.confirmCss;
        cancelCss = settings.confirmationButton.cancelCss;
        inputType = inputType + "-confirm";
    }
    switch (inputType) {
        case "list":
        input.html = "<select class='" + inputCss + "' onchange='$(this).updateEditableCell(this);'>";
        $.each(inputSetting.options, function (index, option) {
            if (oldValue == option.value) {
                input.html = input.html + "<option value='" + option.value + "' selected>" + option.display + "</option>"
            } else {
                input.html = input.html + "<option value='" + option.value + "' >" + option.display + "</option>"
            }
        });
        input.html = input.html + "</select>";
        input.focus = false;
        break;
        case "list-confirm": // List w/ confirm
        input.html = "<select class='" + inputCss + "'>";
        $.each(inputSetting.options, function (index, option) {
            if (oldValue == option.value) {
                input.html = input.html + "<option value='" + option.value + "' selected>" + option.display + "</option>"
            } else {
                input.html = input.html + "<option value='" + option.value + "' >" + option.display + "</option>"
            }
        });
        input.html = input.html + "</select>&nbsp;<a href='javascript:void(0);' class='" + confirmCss + "' onclick='$(this).updateEditableCell(this);'>Сохранить</a> <a href='javascript:void(0);' class='" + cancelCss + "' onclick='$(this).cancelEditableCell(this)'>Отмена</a> ";
        input.focus = false;
        break;
        case "datepicker": //Both datepicker options work best when confirming the values
        case "datepicker-confirm":
        // Makesure jQuery UI is loaded on the page
        if (typeof jQuery.ui == 'undefined') {
            alert("jQuery UI is required for the DatePicker control but it is not loaded on the page!");
            break;
        }
        jQuery(".datepick").datepicker("destroy");
        input.html = "<input id='ejbeatycelledit' type='text' name='date' class='datepick " + inputCss + "'   value='" + oldValue + "'></input> &nbsp;<a href='javascript:void(0);' class='" + confirmCss + "' onclick='$(this).updateEditableCell(this)'>Сохранить</a> <a href='javascript:void(0);' class='" + cancelCss + "' onclick='$(this).cancelEditableCell(this)'>Отмена</a>";
        setTimeout(function () { //Set timeout to allow the script to write the input.html before triggering the datepicker
                var icon = "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif";
                // Allow the user to provide icon
                if (typeof inputSetting.options !== 'undefined' && typeof inputSetting.options.icon !== 'undefined') {
                    icon = inputSetting.options.icon;
                }
                $.datepicker.setDefaults($.datepicker.regional['ru']);
                var self = jQuery('.datepick').datepicker({
                        showOn: "button",
                        buttonImage: icon,
                        buttonImageOnly: true,
                        buttonText: "Выберите дату"
                    });
            },100);
            break;
            case "text-confirm": // text input w/ confirm
            input.html = "<input id='ejbeatycelledit' class='" + inputCss + "' value='"+oldValue+"'></input>&nbsp;<a href='javascript:void(0);' class='" + confirmCss + "' onclick='$(this).updateEditableCell(this)'>Сохранить</a> <a href='javascript:void(0);' class='" + cancelCss + "' onclick='$(this).cancelEditableCell(this)'>Отмена</a> ";
            break;
            case "undefined-confirm": // text input w/ confirm
            input.html = "<input id='ejbeatycelledit' class='" + inputCss + "' value='" + oldValue + "'></input>&nbsp;<a href='javascript:void(0);' class='" + confirmCss + "' onclick='$(this).updateEditableCell(this)'>Сохранить</a> <a href='javascript:void(0);' class='" + cancelCss + "' onclick='$(this).cancelEditableCell(this)'>Отмена</a> ";
            break;
            case "textarea":
            input.html = "<textarea rows='6' cols='50' id='ejbeatycelledit' colclass='" + inputCss + "'>"+oldValue+"</textarea><a href='javascript:void(0);' class='" + confirmCss + "' onclick='tinyMCE.triggerSave(); tinyMCE.remove(); $(this).updateEditableCell(this)'>Сохранить</a> <a href='javascript:void(0);' class='" + cancelCss + "' onclick='tinyMCE.remove(); $(this).cancelEditableCell(this)'>Отмена</a>";
            input.isTextarea = true;
            break;
            case "file":
            let ImgValue = getImageName1(oldValue);
            input.html = "<input id='ejbeatycelledit' type='file' class='" + inputCss + "'onchange='$(this).updateEditableCell(this)' value='" + ImgValue + "'>";
            break;
            default: // text input
            input.html = "<input id='ejbeatycelledit' class='" + inputCss + "' onfocusout='$(this).updateEditableCell(this)' value='" + oldValue + "'></input>";
            break;
        }
        return input;
    }



    function getInputField(callingElement) {
        // Update datatables cell value
        var inputField;
        switch ($(callingElement).prop('nodeName').toLowerCase()) {
            case 'a': // This means they're using confirmation buttons
            if ($(callingElement).siblings('input').length > 0) {
                inputField = $(callingElement).siblings('input');
            }
            if ($(callingElement).siblings('select').length > 0) {
                inputField = $(callingElement).siblings('select');
            }
            if ($(callingElement).siblings('textarea').length > 0) {
                inputField = $(callingElement).siblings('textarea');
            }
            break;
            default:
            inputField = $(callingElement);
        }
        return inputField;
    }

    function sanitizeCellValue(cellValue) {
        if (typeof (cellValue) === 'undefined' || cellValue === null || cellValue.length < 1) {
            return "";
        }

        // If not a number
        if (isNaN(cellValue)) {
            // escape single quote
            cellValue = cellValue.replace(/'/g, "&#39;");
        }
        return cellValue;
    }
