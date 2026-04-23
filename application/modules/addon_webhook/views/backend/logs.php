<?php
/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Zigapage_wp
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://zigapage.softdiscover.com
 */
if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
ob_start();
?>
 
<div class="sfdclauncher zgfm-block1-container sfdc-clearfix" >
<table class="sfdc-table sfdc-table-striped sfdc-table-bordered sfdc-table-condensed" style="margin:-30px;">
  <thead>
    <tr>
      <th scope="col"><?php echo __('File name', 'FRocket_admin'); ?></th>
      <th scope="col"><?php echo __('Content', 'FRocket_admin'); ?></th>
      
    </tr>
  </thead>
  <tbody>
  <?php 
  if(!empty($files))
  foreach ( $files as $key => $value) { ?>
    <tr>    
      <td style="width:50px;"><?php echo $value['file'];?></td>
      <td>
        <?php foreach ($value['content'] as $key2 => $value2) { ?>
          <code><?php echo $value2;?></code>
          <hr>
        
        <?php }?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>    
  
<?php
$cntACmp = ob_get_contents();

$cntACmp = preg_replace('/\s+/', ' ', $cntACmp);
ob_end_clean();
echo $cntACmp;
?>
