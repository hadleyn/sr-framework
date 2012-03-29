<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo $title; ?></title>
    <?php foreach ($css as $c): ?>
        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo $c; ?> "/>
    <?php endforeach; ?>
    <?php foreach ($script as $s): ?>
            <script type="text/javascript" src="<?php echo $s; ?>"></script>
    <?php endforeach; ?>
    <?php foreach ($meta as $name => $m): ?>
                <meta name="<?php echo $name; ?>" content="<?php echo $m; ?>"/>
    <?php endforeach; ?>
</head>
