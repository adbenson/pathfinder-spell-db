<html>
	<head>
		<title><?php echo $this->config->item('title');?></title>
		
		<?php foreach ($this->config->item('js_includes') as $js):?>
			<script type="text/javascript" src="<?php echo $js;?>"></script>
		<?php endforeach;?>
		
		<?php foreach ($this->config->item('css_includes') as $css):?>
			<link rel="stylesheet" type="text/css" href="<?php echo $css;?>" />
		<?php endforeach;?>
	</head>
	
	<body>