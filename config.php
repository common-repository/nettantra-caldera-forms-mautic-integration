<?php
/* *
* Author: NetTantra
* @package         caldera-forms-mautic-integration
*/

defined( 'ABSPATH' ) or die();
?>
<div class="caldera-config-group">
    <label><?php echo __('Mautic Base URL'); ?> </label>
    <div class="caldera-config-field">
        <input type="text" class="block-input field-config required" name="{{_name}}[cfmi_mautic_base_url]" value="{{cfmi_mautic_base_url}}">
    </div>
</div>
<div class="caldera-config-group">
    <label><?php echo __('Mautic Username'); ?> </label>
    <div class="caldera-config-field">
        <input type="text" class="block-input field-config required" name="{{_name}}[cfmi_mautic_username]" value="{{cfmi_mautic_username}}">
    </div>
</div>
<div class="caldera-config-group">
    <label><?php echo __('Mautic Password'); ?> </label>
    <div class="caldera-config-field">
        <input type="text" class="block-input field-config required" name="{{_name}}[cfmi_mautic_password]" value="{{cfmi_mautic_password}}">
    </div>
</div>
<hr>
<h4><?php echo __('Caldera Mautic Field Mappings Setup'); ?></h4>
<table class="wp-list-table widefat fixed striped posts cfmi-mautic-mappings">
    <thead>
        <tr>
            <th style="width:45%;">
                <?php echo __('Mautic Field Aliases'); ?>
            </th>
            <th style="width: 45%">
                <?php echo __('Values to Send'); ?>
            </th>
            <th style="width: 10%">
            </th>
        </tr>
    </thead>
    <tbody>
        {{#each cfmi_mautic_field_mappings_value as |cfmi_mautic_field_mapping_value key|}}
        <tr>
            <td>
                <input type="text" class="block-input field-config" name="{{../_name}}[cfmi_mautic_field_mappings_alias][]" value="{{lookup ../cfmi_mautic_field_mappings_alias key}}">
            </td>
            <td>
                <input type="text" class="block-input field-config magic-tag-enabled caldera-field-bind required" name="{{../_name}}[cfmi_mautic_field_mappings_value][]" value="{{lookup ../cfmi_mautic_field_mappings_value key}}">
            </td>
            <td style="text-align: center;">
                <button class="button caldera-mautic-remove-mappings" type="button">&times;</button>
            </td>
        </tr>
        {{else}}
        <tr>
            <td>
                <input type="text" class="block-input field-config" name="{{../_name}}[cfmi_mautic_field_mappings_alias][]" value="email">
            </td>
            <td>
                <input type="text" class="block-input field-config magic-tag-enabled caldera-field-bind required" name="{{../_name}}[cfmi_mautic_field_mappings_value][]" value="">
            </td>
            <td style="text-align: center;">
                <button class="button caldera-mautic-remove-mappings" type="button">&times;</button>
            </td>
        </tr>
        {{/each}}
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:center;">
                <button class="button caldera-mautic-add-mappings" type="button">Add Another Mapping</button>
            </td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
<!--
jQuery(function(){
    var block_first_row = function () {
      jQuery(".cfmi-mautic-mappings input.field-config:first").prop('readonly', 'readonly');
      jQuery(".cfmi-mautic-mappings input.field-config:first").parent().parent().addClass('cfmi-required');
    };
    block_first_row();
    jQuery(window).on('load', block_first_row);
    jQuery("body").on('click', '.caldera-mautic-add-mappings', function() {
        jQuery("tbody tr:last").clone().appendTo("tbody");
        jQuery("tbody tr:last input").val("");
        jQuery("tbody tr:last input").removeProp("readonly");
    });
    jQuery("body").on('click', '.caldera-mautic-remove-mappings', function() {
        var $parentTr = jQuery(this).parent().parent();
        if($parentTr.hasClass('cfmi-required')) {
            alert("Email is a required mapping for Mautic Integration!");
            return;
        }
        if(jQuery('.caldera-mautic-remove-mappings').length > 1) {
            $parentTr.remove();
        }
    });
});
//-->
</script>
