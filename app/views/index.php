<!DOCTYPE html>
<html>
    <head>
        <title>Joroot Framework</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!--META TAGS-->
        <meta name="keywords" content="Joroot framework, joroot, framework joroot" />
        <meta name="description" content="Joroot framework, pronto para auxiliar no desenvolvimento de sistemas web" />
        <!--BASE-->
        <base href="<?php echo ROOT; ?>" target="_parent" />
        <!--ICONE-->
        <link rel="shortcut icon" href="lib/imagens/icon.ico" />
        <!--CSS-->
        <link href="lib/css/style.css" rel="stylesheet" type="text/css" media="screen" />
        <!--SCRIPTS-->
        <script src="lib/js/jquery-1.8.2.js" type="text/javascript"></script>
        <script src="lib/js/isIE.js" type="text/javascript"></script>
        <script src="lib/js/validacoes.js" type="text/javascript"></script>
    </head>
    <body>
        <!--dvContainer-->
        <div id="container">
            <div id="top">
                <h1><a href="home">Joroot Framework</a></h1>
            </div>
            <div id="center">
                <div id="menu">
                    <ul>
                        <li>
                            <a href="home">Home</a>
                        </li>
                        <li>
                            <a href="noticias/listar">Noticias</a>
                        </li>
                    </ul>
                </div>
                <?php include('templates/' . $conteudo); ?>
            </div>
            <div id="bottom">
                Copyright &copy; Joroot Framework. Todos os direitos reservados.
            </div>
        </div>
        <!--Fim dvContainer-->
    </body>
</html>