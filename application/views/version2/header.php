<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<meta name="description" content="" />
		<meta name="author" content="SONG QIYUE" />

		<meta name="viewport" content="width=device-width; initial-scale=1.0" />

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="<?=ASSEST_URL?>desktop/favicon.ico" />
		<link rel="apple-touch-icon" href="<?=ASSEST_URL?>desktop/apple-touch-icon.png" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Petit+Formal+Script' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="<?=ASSEST_URL?>desktop/favicon.ico" />
		<link rel="apple-touch-icon" href="<?=ASSEST_URL?>desktop/apple-touch-icon.png" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/redmond/jquery-ui.css" type="text/css" />
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/metro-bootstrap.css">

        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
                text-transform: none;
            }
            .officelist.active{
            	color:white;
            	background-color:#08C;
            }
			
			.privateRoom{
				background: url(<?=ASSEST_URL?>desktop/img/roomIcon.png) center center no-repeat;
			}
			
			.commonRoom{
				background: url(<?=ASSEST_URL?>desktop/img/roomIconCommon.png) center center no-repeat;
			}
			
			#roomArea{
				background: url(<?=ASSEST_URL?>desktop/img/roomBodyMain.png) top left repeat-y;
			}
			
			#roomLabel{
				background: url(<?=ASSEST_URL?>desktop/img/roomBodyHeader.png) top left no-repeat;
			}
			
			.btn-help{
				background: url(<?=ASSEST_URL?>desktop/img/glyphicons_194_circle_question_mark.png) center center no-repeat;
			}
			
			.iconFloorMain{
				background: url(<?=ASSEST_URL?>desktop/img/roomIconContainerMain.png) top left repeat-y;
			}
			
			.iconFloorHeader{
				background: url(<?=ASSEST_URL?>desktop/img/roomIconContainerHeader.png) top left no-repeat;
			}
			
			.iconFloorFooter{
				background: url(<?=ASSEST_URL?>desktop/img/roomIconContainerFooter.png) top left no-repeat;
			}
        </style>
        <link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/main.css">
       	<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/ui.daterangepicker.css" type="text/css" />

        <script src="<?=ASSEST_URL?>desktop/js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
        <script>window.jQuery || document.write('<script src="<?=ASSEST_URL?>desktop/js/vendor/jquery-1.8.1.min.js"><\/script>')</script>

        <script src="<?=ASSEST_URL?>desktop/js/vendor/bootstrap.min.js"></script>

        <script src="<?=ASSEST_URL?>desktop/js/plugins.js"></script>
        <script src="<?=ASSEST_URL?>desktop/js/main.js"></script>