<?php

/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://wordpress-form-builder.uiform.com/
 */
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
?>

<form action="#" class="zgfm-license-val-form ">
<div class="sfdclauncher">
  <h3 class="text-center"><?php echo __('License Validation', 'FRocket_admin'); ?></h3>
  <div class="alert alert-info">
  
  <strong><i class="fa fa-caret-right" aria-hidden="true"></i></strong> 
<?php 
  $lic='https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code';
   ?>
  <?php echo __('To ensure you have a valid license, please enter your purchase code in the field below. You can find your purchase code by following the instructions provided', 'FRocket_admin'); ?>
  <?php echo sprintf('<a href="%s" target="_blank" >%s</a>', $lic, __('here.', 'FRocket_admin')); ?>
</div>
  <div class="form-group">
    <input type="text" class="form-control" name="zgfm-purchase-code" id="zgfm-purchase-code" />
    <label for="zgfm-purchase-code" class="animated-label"><?php echo __('Purchase Code', 'FRocket_admin'); ?></label>
  </div>
  <div class="submit">
    <button id="zgfm-license-val-btn" class="sfdc-btn sfdc-btn-primary sfdc-btn-block" disabled>
      <?php echo __('Send', 'FRocket_admin'); ?>
      <span class="loader" style="display:none; margin-left: 10px;">
        <i class="fa fa-spinner fa-spin"></i>
      </span>
    </button>
  </div>
  <div id="error-message" class="error-message" style="display: none;"></div>
  </div>
</form>
 

<script>
  jQuery(document).ready(function($) {
    // Add or remove 'not-empty' class based on input value
    $('.form-control').on('input', function() {
      var $this = $(this);
      if ($this.val().length > 0) {
        $this.closest('.form-group').addClass('not-empty');
      } else {
        $this.closest('.form-group').removeClass('not-empty');
      }
    });

    // Enable or disable submit button based on input values
    $('.form-control').on('input', function() {
      var isUserNameFilled = $('#zgfm-purchase-code').val().length > 0;

      if (isUserNameFilled) {
        $('.submit button').prop('disabled', false);
      } else {
        $('.submit button').prop('disabled', true);
      }
    });

    $('#zgfm-license-val-btn').on('click', function(e) {
      e.preventDefault();
      var pcode = $('#zgfm-purchase-code').val();
      var $button = $(this);
      var $loader = $button.find('.loader');

      $button.prop('disabled', true);
      $loader.show();

      // First attempt to validate the code via server
      validatePurchaseCode(pcode, $button, $loader);
    });

    function validatePurchaseCode(pcode, $button, $loader) {
      $.ajax({
        type: 'POST',
        url: rockfm_vars.uifm_siteurl + 'formbuilder/license/validatepurchasecode',
        data: {
          action: 'rocket_fbuilder_validate_purchase_code',
          page: 'zgfm_form_builder',
          zgfm_security: uiform_vars.ajax_nonce,
          pcode: pcode,
          item: 'zgfmphpcost',
          csrf_field_name: uiform_vars.csrf_field_name,
        },
        success: function(response) {
          if (response.success === true) {
            window.location.reload(); // Refresh the page if the option update is successful
          } else {
            zgfmLicShowError(response.message);
            retryValidationInBrowser(pcode);  // Retry validation from browser on failure
          }
        },
        complete: function() {
          $button.prop('disabled', false);
          $loader.hide();
        }
      });
    }

    // Retry validation by redirecting to the validation URL
    function retryValidationInBrowser(pcode) {
      $.ajax({
        type: 'POST',
        crossDomain: true,
        dataType: "json",
        url: 'https://license-hub-zgfm.softdiscover.com/verify/code/' + pcode + '/item/zgfmphpcost',
        xhrFields: { withCredentials: true },
        success: function(response) {
          if (response.success === true) {
            updatePurchaseOption(pcode);  // Update option on success
          } else {
            zgfmLicShowError("Validation failed again. Please check your purchase code.");
          }
        },
        error: function() {
          zgfmLicShowError("Browser validation failed. Unable to connect to server.");
        }
      });
    }

    // Update the purchase option after successful validation
    function updatePurchaseOption(pcode) {
      $.ajax({
        type: 'POST',
        url: rockfm_vars.uifm_siteurl + 'formbuilder/license/update_option',
        data: {
          action: 'rocket_fbuilder_update_option',
          pcode: pcode,
          csrf_field_name: uiform_vars.csrf_field_name
        },
        success: function(response) {
          if (response.success === true) {
            window.location.reload(); // Refresh the page if the option update is successful
          } else {
            zgfmLicShowError("Failed to save validation status.");
          }
        },
        error: function() {
          zgfmLicShowError("Server error while saving validation status.");
        }
      });
    }

    // Display error messages
    function zgfmLicShowError(message) {
      var $errorMessage = $('#error-message');
      $errorMessage.text(message).addClass('show').fadeIn();
      setTimeout(function() {
        $errorMessage.removeClass('show').fadeOut();
      }, 5000); // Error message will disappear after 5 seconds
    }
  });
</script>
