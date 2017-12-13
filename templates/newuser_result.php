<?php
/**
 * This file is part of simpleSAMLphp.
 * 
 * The authTiqr module is a module adding authentication via the tiqr 
 * project to simpleSAMLphp. It was initiated by SURFnet and 
 * developed by Egeniq.
 *
 * See the README file for instructions and requirements.
 *
 * @author Ivo Jansch <ivo@egeniq.com>
 * 
 * @package simpleSAMLphp
 * @subpackage authTiqr
 *
 * @license New BSD License - See LICENSE file in the tiqr library for details
 * @copyright (C) 2010-2011 SURFnet BV
 *
 */

$this->data['header'] = $this->t('{authTiqr:tiqr:header_enrollment}');

if (sspmod_authTiqr_Helper_VersionHelper::useOldVersion()) {
    $this->data['jquery'] = array('version' => '1.6', 'core' => true);
} else {
    $this->data['jquery'] = array('version' => '1.8', 'core' => true);
}

$this->includeAtTemplateBase('includes/header.php');

$verify_login_error = json_encode($this->t('{authTiqr:tiqr:verify_login_error}'));

$this->data['htmlinject']['htmlContentPost'][] = <<<JAVASCRIPT
<script type="text/javascript">
function verifyEnrollment() {
  jQuery.get('{$this->data['verifyEnrollUrl']}', function(data) {
    if (data == 'NO') {
      window.setTimeout(verifyEnrollment, 1500);
    } else if (data.substring(0, 4) == 'URL:') {
      document.location.href = data.substring(4);
    } else {
      alert({$verify_login_error});
    }
  });
}
jQuery(document).ready(verifyEnrollment);
</script>
JAVASCRIPT;

if (isset($this->data['errorcode'])) {
    $this->includeAtTemplateBase("authTiqr:includes/inline_error.php");
}
?>

<h2 class="main"><?php echo $this->t('{authTiqr:tiqr:header_enrollment}'); ?></h2>

<form action="?" method="post" name="f">

    <p><?php echo $this->t('{authTiqr:tiqr:instruction_qr_enroll}')?></p>
    
    <img src="<?php echo $this->data['qrUrl']; ?>" /> <br/> 
    
        <?php
        if (isset($this->data['loginUrl'])) {
            $linkStart = '<a href="'.$this->data['loginUrl'].'">'; 
            echo $this->t('{authTiqr:tiqr:instruction_enroll_proceed_manually}', array("[link]"=>$linkStart, "[/link]"=>"</a>")); 
        }
        ?>
 
<?php
if (isset($this->data['stateparams'])) {
    foreach ($this->data['stateparams'] as $name => $value) {
        echo('<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" />');
    }
}
?>

</form>

<?php

$this->includeAtTemplateBase('includes/footer.php');
