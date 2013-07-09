<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010, 2011 Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Eugene Venter <eugene@catalyst.net.nz>
 * @package totara
 * @subpackage reportbuilder
 */

    require_once('../../config.php');
    header('Content-Type: text/javascript; charset=utf-8');
?>

$(document).ready(function() {
    rb_init_col_rows();
});

var rb_init_col_rows = function() {
    // disable the new column heading field on page load
    $('#id_newheading').attr('disabled', true);
    $('#id_newcustomheading').attr('disabled', true);

    // disable uncustomised headers on page load
    $('input.column_custom_heading_checkbox').not(':checked').each(function() {
        var textElement = $('input.column_heading_text', $(this).parents('tr:first'));
        textElement.attr('disabled', true);
        
        var heading = $(this).parents('tr:first').find('td').find('select option:selected').text();
        
        textElement.attr('value', heading);
    });
    

    // handle changes to the column pulldowns
    $('select.column_selector').unbind('change');
    $('select.column_selector').bind('change', function() {
        var changedSelector = $(this).val();
        var newContent = rb_column_headings[changedSelector];
        var textElement = $('input.column_heading_text', $(this).parents('tr:first'));

        textElement.val(newContent);  // insert new content
    });

    // handle changes to the customise checkbox
    // use click instead of change event for IE
    $('input.column_custom_heading_checkbox').unbind('click');
    $('input.column_custom_heading_checkbox').bind('click', function() {
        var textElement = $('input.column_heading_text', $(this).parents('tr:first'));
        if ($(this).is(':checked')) {
            // enable the textbox when checkbox isn't checked
            textElement.attr('disabled', false);
        } else {
            // disable the textbox when checkbox is checked
            // and reset text contents back to default
            var changedSelector = $('select.column_selector', $(this).parents('tr:first')).val();
            var newContent = rb_column_headings[changedSelector];
            textElement.val(newContent);
            textElement.attr('disabled', true);
        }
    });

    // special case for the 'Add another column...' selector
    $('select.new_column_selector').bind('change', function() {
        var newHeadingBox = $('input.column_heading_text', $(this).parents('tr:first'));
        var newCheckBox = $('input.column_custom_heading_checkbox', $(this).parents('tr:first'));
        var addbutton = rb_init_addbutton($(this));
        if($(this).val() == 0) {
            // empty and disable the new heading box if no column chosen
            newHeadingBox.val('');
            newHeadingBox.attr('disabled', true);
            addbutton.remove();
            newCheckBox.removeAttr('checked');
            newCheckBox.attr('disabled', true);
        } else {
            // reenable it (binding above will fill the value)
            newCheckBox.attr('disabled', false);
        }
    });

    // Set up delete button events
    rb_init_deletebuttons();

    // Set up hide button events
    rb_init_hidebuttons();

    // Set up show button events
    rb_init_showbuttons();

    // Set up 'move' button events
    rb_init_movedown_btns();
    rb_init_moveup_btns();
};

var rb_init_addbutton = function(colselector) {
    var newHeadingBox = $('input.column_heading_text', colselector.parents('tr:first'));
    var optionsbox = $('td:last', newHeadingBox.parents('tr:first'));
    var newcolinput = colselector.closest('tr').clone();  // clone of current 'Add new col...' tr
    var addbutton = optionsbox.find('.addcolbtn');

    if (addbutton.length == 0) {
        addbutton = rb_get_btn_add(rb_reportid);
    } else {
        // Button already initialised
        return addbutton;
    }

    // Add save button to options
    optionsbox.prepend(addbutton);

    addbutton.unbind('click');
    addbutton.bind('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/ajax/column.php'; ?>",
            type: "POST",
            data: ({action: 'add', sesskey: '<?php echo $USER->sesskey; ?>', id: rb_reportid, col: colselector.val(), heading: newHeadingBox.val()}),
            beforeSend: function() {
                addbutton.html('<img src="<?php echo "{$CFG->wwwroot}/pix/i/ajaxloader.gif"; ?>" alt="Saving..." class="iconsmall" />');
            },
            success: function(o) {
                if (o.length > 0) {
                    // Add action buttons to row
                    var colid = parseInt(o);
                    var hidebutton = rb_get_btn_hide(rb_reportid, colid);
                    var deletebutton = rb_get_btn_delete(rb_reportid, colid);

                    var upbutton = '';
                    var uppersibling = colselector.closest('tr').prev('tr');
                    if (uppersibling.find('select.column_selector').length > 0) {
                        // Create an up button for the newly added col, to be added below
                        var upbutton = rb_get_btn_up(rb_reportid, colid);
                    }

                    addbutton.remove();
                    optionsbox.prepend(hidebutton, deletebutton, upbutton);

                    // Set row atts
                    var columnbox = $('td:first', optionsbox.parents('tr:first'));
                    var columnSelector = $('select.column_selector', columnbox);
                    var newCustomHeading = $('input.column_custom_heading_checkbox', optionsbox.parents('tr:first'));
                    columnSelector.removeClass('new_column_selector');
                    columnSelector.attr('name', 'column'+colid);
                    columnSelector.attr('id', 'id_column'+colid);
                    columnbox.find('select optgroup[label=New]').remove();

                    newCustomHeading.attr('name', 'customheading'+colid);
                    newCustomHeading.removeAttr('id');

                    newHeadingBox.attr('name', 'heading'+colid);
                    newHeadingBox.attr('id', 'id_heading'+colid);
                    newHeadingBox.closest('tr').attr('colid', colid);

                    // Append a new col select box
                    newcolinput.find('input[name=newheading]').val('');
                    columnbox.closest('table').append(newcolinput);

                    rb_reload_option_btns(uppersibling);

                    var coltype = colselector.val().split('-')[0];
                    var colval = colselector.val().split('-')[1];

                    // Remove added col from the new col selector
                    $('.new_column_selector optgroup option[value='+coltype+'-'+colval+']').remove();

                    // Add added col to 'default sort column' selector
                    $('select[name=defaultsortcolumn]').append('<option value="'+coltype+'_'+colval+'">'+rb_column_headings[coltype+'-'+colval]+'</option>');



                    rb_init_col_rows();

                } else {
                    alert('Error');
                    // Reload the broken page
                    location.reload();
                }

            },
            error: function(h, t, e) {
                alert('Error');
                // Reload the broken page
                location.reload();
            }
        }); // ajax
    }); // click event

    return addbutton;
};


var rb_init_deletebuttons = function() {
    $('.reportbuilderform table .deletecolbtn').unbind('click');
    $('.reportbuilderform table .deletecolbtn').bind('click', function(e) {
        e.preventDefault();
        var clickedbtn = $(this);

        confirmed = confirm('<?php echo addslashes_js(get_string('confirmcoldelete', 'block_lp_reportbuilder')); ?>');

        if (!confirmed) {
            return;
        }

        var colrow = $(this).closest('tr');
        $.ajax({
            url: "<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/ajax/column.php'; ?>",
            type: "POST",
            data: ({action: 'delete', sesskey: '<?php echo $USER->sesskey; ?>', id: rb_reportid, cid: colrow.attr('colid')}),
            beforeSend: function() {
                clickedbtn.replaceWith('<img src="<?php echo "{$CFG->wwwroot}/pix/i/ajaxloader.gif"; ?>" alt="Saving..." class="iconsmall" />');
            },
            success: function(o) {
                if (o.length > 0) {
                    //o = eval('('+o+')');  // this may become necessary for older browsers :(
                    o = JSON.parse(o);

                    var uppersibling = colrow.prev('tr');
                    var lowersibling = colrow.next('tr');

                    // Remove column row
                    colrow.remove();

                    // Fix sibling buttons
                    if (uppersibling.find('select.column_selector').length > 0) {
                        rb_reload_option_btns(uppersibling);
                    }
                    if (lowersibling.find('select.column_selector:not(.new_column_selector)').length > 0) {
                        rb_reload_option_btns(lowersibling);
                    }

                    // Add deleted col to new col selector
                    var nlabel = o.type.replace(/[-_]/g, ' ');  // Determine the optgroup label
                    nlabel = rb_ucwords(nlabel);
                    var optgroup = $('.new_column_selector optgroup[label='+nlabel+']');
                    if (optgroup.length == 0) {
                        // Create optgroup and append to select
                        optgroup = $('<optgroup label="'+nlabel+'"></optgroup>');
                        $('.new_column_selector').append(optgroup);
                    }

                    if (optgroup.find('option[value='+o.type+'-'+o.value+']').length == 0) {
                        optgroup.append('<option value="'+o.type+'-'+o.value+'">'+rb_column_headings[o.type+'-'+o.value]+'</option>');
                    }

                    // Remove deleted col from 'default sort column' selector
                    $('select[name=defaultsortcolumn] option[value='+o.type+'_'+o.value+']').remove();

                    rb_init_col_rows();

                } else {
                    alert('Error');
                    // Reload the broken page
                    location.reload();
                }

            },
            error: function(h, t, e) {
                alert('Error');
                // Reload the broken page
                location.reload();
            }
        }); // ajax

    });

    function rb_ucwords (str) {
        return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

}

var rb_init_hidebuttons = function() {
    $('.reportbuilderform table .hidecolbtn').unbind('click');
    $('.reportbuilderform table .hidecolbtn').bind('click', function(e) {
        e.preventDefault();
        var clickedbtn = $(this);

        var colrow = $(this).closest('tr');
        $.ajax({
            url: "<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/ajax/column.php'; ?>",
            type: "POST",
            data: ({action: 'hide', sesskey: '<?php echo $USER->sesskey; ?>', id: rb_reportid, cid: colrow.attr('colid')}),
            beforeSend: function() {
                clickedbtn.find('img').replaceWith('<img src="<?php echo "{$CFG->wwwroot}/pix/i/ajaxloader.gif"; ?>" alt="Saving..." class="iconsmall" />');
            },
            success: function(o) {
                if (o.length > 0) {
                    var colid = parseInt(o);

                    var showbtn = rb_get_btn_show(rb_reportid, colid);
                    clickedbtn.replaceWith(showbtn);

                    rb_init_col_rows();

                } else {
                    alert('Error');
                    // Reload the broken page
                    location.reload();
                }

            },
            error: function(h, t, e) {
                alert('Error');
                // Reload the broken page
                location.reload();
            }
        }); // ajax

    });
};

var rb_init_showbuttons = function() {
    $('.reportbuilderform table .showcolbtn').unbind('click');
    $('.reportbuilderform table .showcolbtn').bind('click', function(e) {
        e.preventDefault();
        var clickedbtn = $(this);

        var colrow = $(this).closest('tr');
        $.ajax({
            url: "<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/ajax/column.php'; ?>",
            type: "POST",
            data: ({action: 'show', sesskey: '<?php echo $USER->sesskey; ?>', id: rb_reportid, cid: colrow.attr('colid')}),
            beforeSend: function() {
                clickedbtn.find('img').replaceWith('<img src="<?php echo "{$CFG->wwwroot}/pix/i/ajaxloader.gif"; ?>" alt="Saving..." class="iconsmall" />');
            },
            success: function(o) {
                if (o.length > 0) {
                    var colid = parseInt(o);

                    var showbtn = rb_get_btn_hide(rb_reportid, colid);
                    clickedbtn.replaceWith(showbtn);

                    rb_init_col_rows();

                } else {
                    alert('Error');
                    // Reload the broken page
                    location.reload();
                }

            },
            error: function(h, t, e) {
                alert('Error');
                // Reload the broken page
                location.reload();
            }
        }); // ajax

    });

}

var rb_init_movedown_btns = function() {
    $('.reportbuilderform table .movecoldownbtn').unbind('click');
    $('.reportbuilderform table .movecoldownbtn').bind('click', function(e) {
        e.preventDefault();
        var clickedbtn = $(this);

        var colrow = $(this).closest('tr');

        var colrowclone = colrow.clone();
        // Set the selected option, cause for some reason this don't clone so well...
        colrowclone.find('select.column_selector option[value='+colrow.find('select.column_selector').val()+']').attr('selected', 'selected');

        var lowersibling = colrow.next('tr');

        var lowersiblingclone = lowersibling.clone();
        // Set the selected option, cause for some reason this don't clone so well...
        lowersiblingclone.find('select.column_selector option[value='+lowersibling.find('select.column_selector').val()+']').attr('selected', 'selected');

        var loadingimg = '<img src="<?php echo "{$CFG->wwwroot}/pix/i/ajaxloader.gif"; ?>" alt="Saving..." class="iconsmall" />';

        $.ajax({
            url: "<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/ajax/column.php'; ?>",
            type: "POST",
            data: ({action: 'movedown', sesskey: '<?php echo $USER->sesskey; ?>', id: rb_reportid, cid: colrow.attr('colid')}),
            beforeSend: function() {
                lowersibling.html(loadingimg);
                colrow.html(loadingimg);
                colrowclone.find('td *').hide();
                lowersiblingclone.find('td *').hide();
            },
            success: function(o) {
                if (o.length > 0) {
                    // Switch!
                    colrow.replaceWith(lowersiblingclone);
                    lowersibling.replaceWith(colrowclone);

                    colrowclone.find('td *').fadeIn();
                    lowersiblingclone.find('td *').fadeIn();

                    // Fix option buttons
                    rb_reload_option_btns(colrowclone);
                    rb_reload_option_btns(lowersiblingclone);

                    rb_init_col_rows();

                } else {
                    alert('Error');
                    // Reload the broken page
                    location.reload();
                }

            },
            error: function(h, t, e) {
                alert('Error');
                // Reload the broken page
                location.reload();
            }
        }); // ajax

    });
}


var rb_init_moveup_btns = function() {
    $('.reportbuilderform table .movecolupbtn').unbind('click');
    $('.reportbuilderform table .movecolupbtn').bind('click', function(e) {
        e.preventDefault();
        var clickedbtn = $(this);

        var colrow = $(this).closest('tr');

        var colrowclone = colrow.clone();
        // Set the selected option, cause for some reason this don't clone so well...
        colrowclone.find('select.column_selector option[value='+colrow.find('select.column_selector').val()+']').attr('selected', 'selected');

        var uppersibling = colrow.prev('tr');

        var uppersiblingclone = uppersibling.clone();
        // Set the selected option, cause for some reason this don't clone so well...
        uppersiblingclone.find('select.column_selector option[value='+uppersibling.find('select.column_selector').val()+']').attr('selected', 'selected');

        var loadingimg = '<img src="<?php echo "{$CFG->wwwroot}/pix/i/ajaxloader.gif"; ?>" alt="Saving..." class="iconsmall" />';

        $.ajax({
            url: "<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/ajax/column.php'; ?>",
            type: "POST",
            data: ({action: 'moveup', sesskey: '<?php echo $USER->sesskey; ?>', id: rb_reportid, cid: colrow.attr('colid')}),
            beforeSend: function() {
                uppersibling.html(loadingimg);
                colrow.html(loadingimg);

                colrowclone.find('td *').hide();
                uppersiblingclone.find('td *').hide();
            },
            success: function(o) {
                if (o.length > 0) {
                    // Switch!
                    colrow.replaceWith(uppersiblingclone);
                    uppersibling.replaceWith(colrowclone);

                    colrowclone.find('td *').fadeIn();
                    uppersiblingclone.find('td *').fadeIn();

                    // Fix option buttons
                    rb_reload_option_btns(colrowclone);
                    rb_reload_option_btns(uppersiblingclone);

                    rb_init_col_rows();

                } else {
                    alert('Error');
                    // Reload the broken page
                    location.reload();
                }

            },
            error: function(h, t, e) {
                alert('Error');
                // Reload the broken page
                location.reload();
            }
        }); // ajax

    });
}

var rb_reload_option_btns = function(colrow) {
    var optionbox = colrow.children('td').filter(':last');
    var hideshowbtn = optionbox.find('.hidecolbtn');
    if (hideshowbtn.length == 0) {
        hideshowbtn = optionbox.find('.showcolbtn');
    }
    hideshowbtn = hideshowbtn.closest('a');

    // Remove all option buttons
    //optionbox.find('a:not(.hidecolbtn):not(.showcolbtn)').remove();
    optionbox.find('a').remove();
    optionbox.find('img').remove();

    // Replace with btns with updated ones
    var colid = colrow.attr('colid');
    var deletebtn = rb_get_btn_delete(rb_reportid, colid);
    var upbtn = '<img src="<?php echo $CFG->wwwroot.'/pix/spacer.gif'; ?>" class="iconsmall" alt="" />';
    if (colrow.prev('tr').find('select.column_selector').length > 0) {
        upbtn = rb_get_btn_up(rb_reportid, colid);
    }
    var downbtn = '<img src="<?php echo $CFG->wwwroot.'/pix/spacer.gif'; ?>" class="iconsmall" alt="" />';
    if (colrow.next('tr').next('tr').find('select.column_selector').length > 0) {
        downbtn = rb_get_btn_down(rb_reportid, colid);
    }

    optionbox.append(hideshowbtn, deletebtn, upbtn, downbtn);
}


var rb_get_btn_hide = function(reportid, colid) {
    return $('<a href="<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/columns.php?id='; ?>' + reportid + '&cid='+colid+'&h=1" class="hidecolbtn"><img src="<?php echo "{$CFG->wwwroot}/pix/t/hide.gif"; ?>" alt="Hide" class="iconsmall" /></a>');
};

var rb_get_btn_show = function(reportid, colid) {
    return $('<a href="<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/columns.php?id='; ?>' + reportid + '&cid='+colid+'&h=0" class="showcolbtn"><img src="<?php echo "{$CFG->wwwroot}/pix/t/show.gif"; ?>" alt="Show" class="iconsmall" /></a>');
};

var rb_get_btn_delete = function(reportid, colid) {
    return $('<a href="<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/columns.php?id='; ?>' + reportid + '&cid='+colid+'&d=1" class="deletecolbtn"><img src="<?php echo "{$CFG->wwwroot}/pix/t/delete.gif"; ?>" alt="Delete" class="iconsmall" /></a>');
}

var rb_get_btn_up = function (reportid, colid) {
    return $('<a href="<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/columns.php?id='; ?>' + reportid + '&cid='+colid+'&m=up" class="movecolupbtn"><img src="<?php echo "{$CFG->wwwroot}/pix/t/up.gif"; ?>" alt="Move up" class="iconsmall" /></a>');
}

var rb_get_btn_down = function (reportid, colid) {
    return $('<a href="<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/columns.php?id='; ?>' + reportid + '&cid='+colid+'&m=down" class="movecoldownbtn"><img src="<?php echo "{$CFG->wwwroot}/pix/t/down.gif"; ?>" alt="Move down" class="iconsmall" /></a>');
}

var rb_get_btn_add = function (reportid) {
    return $('<a href="<?php echo $CFG->wwwroot.'/blocks/lp_reportbuilder/columns.php?id='; ?>' + reportid + '" class="addcolbtn"><input type="button" value="<?php echo addslashes_js(get_string('add')); ?>" /></a>');
}
