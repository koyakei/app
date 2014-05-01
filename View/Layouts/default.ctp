<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript">
	function toggleShow(obj) {
 var ch = obj.parentNode.children;
 for (var i = 0, len = ch.length; i < len; i++) {
    if (ch[i].getAttribute("id") == "HSfield") {
      var element = ch[i];
         if (element.style.display == 'none') {
           element.style.display='block';
         } else {
           element.style.display='none';
         }
       }
    }
 }
</script>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo "SRNS" ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js"></script>
</head>
<body>
	<div id="container">
		<div id="header">
			
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer" style="text-align:left">
			<?php echo $this->Html->link("Contact", "https://twitter.com/koyakei"); ?>

		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>

</body>

</html>
