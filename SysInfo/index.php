<?php
require_once 'inc/serviceManager.class.php';
ServiceManager::init();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="author" content="Yonas, Jan Krüger">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="keywords" content="<?php echo ServiceManager::getConfig()->get('metaKeywords'); ?>">
        <meta name="description" content="<?php echo ServiceManager::getConfig()->get('metaDescription'); ?>">
        <title>Network Status &nbsp;&middot;&nbsp; <?php echo ServiceManager::getConfig()->get('metaName'); ?></title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic">
        <link rel="stylesheet" href="inc/css/style.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <script>var jQl={q:[],dq:[],gs:[],ready:function(a){'function'==typeof a&&jQl.q.push(a);return jQl},getScript:function(a,c){jQl.gs.push([a,c])},unq:function(){for(var a=0;a<jQl.q.length;a++)jQl.q[a]();jQl.q=[]},ungs:function(){for(var a=0;a<jQl.gs.length;a++)jQuery.getScript(jQl.gs[a][0],jQl.gs[a][1]);jQl.gs=[]},bId:null,boot:function(a){'undefined'==typeof window.jQuery.fn?jQl.bId||(jQl.bId=setInterval(function(){jQl.boot(a)},25)):(jQl.bId&&clearInterval(jQl.bId),jQl.bId=0,jQl.unqjQdep(),jQl.ungs(),jQuery(jQl.unq()), 'function'==typeof a&&a())},booted:function(){return 0===jQl.bId},loadjQ:function(a,c){setTimeout(function(){var b=document.createElement('script');b.src=a;document.getElementsByTagName('head')[0].appendChild(b)},1);jQl.boot(c)},loadjQdep:function(a){jQl.loadxhr(a,jQl.qdep)},qdep:function(a){a&&('undefined'!==typeof window.jQuery.fn&&!jQl.dq.length?jQl.rs(a):jQl.dq.push(a))},unqjQdep:function(){if('undefined'==typeof window.jQuery.fn)setTimeout(jQl.unqjQdep,50);else{for(var a=0;a<jQl.dq.length;a++)jQl.rs(jQl.dq[a]); jQl.dq=[]}},rs:function(a){var c=document.createElement('script');document.getElementsByTagName('head')[0].appendChild(c);c.text=a},loadxhr:function(a,c){var b;b=jQl.getxo();b.onreadystatechange=function(){4!=b.readyState||200!=b.status||c(b.responseText,a)};try{b.open('GET',a,!0),b.send('')}catch(d){}},getxo:function(){var a=!1;try{a=new XMLHttpRequest}catch(c){for(var b=['MSXML2.XMLHTTP.5.0','MSXML2.XMLHTTP.4.0','MSXML2.XMLHTTP.3.0','MSXML2.XMLHTTP','Microsoft.XMLHTTP'],d=0;d<b.length;++d){try{a= new ActiveXObject(b[d])}catch(e){continue}break}}finally{return a}}};if('undefined'==typeof window.jQuery){var $=jQl.ready,jQuery=$;$.getScript=jQl.getScript};</script>			
        <!--[if lt IE 9]>
                <script>jQl.loadjQ('//cdn.jsdelivr.net/g/modernizr,selectivizr,prefixfree,jquery@1.11.0,jquery.equalize,jquery.downboy');</script>
        <![endif]-->
        <!--[if IE 9]><!-->
        <script>jQl.loadjQ('//cdn.jsdelivr.net/g/modernizr,prefixfree,jquery,jquery.equalize,jquery.downboy');</script>
        <!--<![endif]-->
        <script>
            $(function() {
                equalize();
                downBoy();
                window.onresize = function() {
                    equalize();
                    downBoy();
                }
            })
        </script>
    </head>
    <body>
        <div id="headcontainer">
            <header>
                <h1>Network Status</h1>
                <h5>of <a href="<?php echo ServiceManager::getConfig()->get('metaLink'); ?>"><?php echo ServiceManager::getConfig()->get('metaName'); ?></a></h5>
            </header>
        </div>
        <div id="maincontentcontainer">
            <div id="maincontent">
                <div class="section group" id="statusContent">
                    <h1>Loading...</h1>
                </div>
            </div>
        </div>
        <div id="footercontainer">
        </div>

        <script type="text/javascript">

            $(document).ready(function() {
                
                $.get('inc/loadContent.php', function(data) {
                
                    var content = $('#statusContent');
                    content.css('display', 'none');
                    content.html(data);
                    content.fadeIn(800);
                    
                });

            });

            $(document).ready(function() {
                setInterval(function() {

                    $.get('inc/loadContent.php', function(data) {

                        $('#statusContent').html(data);

                    });

                }, 1000);

            });

        </script>
    </body>
</html>
