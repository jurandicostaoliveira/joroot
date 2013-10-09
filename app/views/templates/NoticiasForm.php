<p style="float: left; width: 100%; padding: 10px 0px;">
    <a href="noticias/listar">&laquo; Voltar</a>
</p>   
<div style="float: left;">
    <form name="formNoticias" method="post" onsubmit="return validarFormNoticias();" action="noticias/<?php echo $dados['acao']; ?>">
        <table border="1" width="100%">
            <tr>
                <td>Titulo : </td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $dados['id']; ?>" />
                    <input type="text" name="titulo" value="<?php echo $dados['titulo']; ?>" />
                </td>
            </tr>
            <tr>
                <td>Descri&ccedil;&atilde;o : </td>
                <td>
                    <textarea name="descricao" cols="50" rows="5"><?php echo $dados['descricao']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="botao" value="<?php echo $dados['botao']; ?>" /></td>
            </tr>
        </table>
    </form>
</div>