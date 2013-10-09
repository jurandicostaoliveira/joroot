<p style="float: left; width: 100%; padding: 10px 0px;">
    <a href="noticias/listar">&laquo; Voltar</a>
</p>   
<div style="float: left;">
    <form name="formImagemNoticias" method="post" onsubmit="return validarFormImagemNoticias();" action="noticias/upload-imagem" enctype="multipart/form-data">
        <table border="1" width="100%">
            <tr>
                <td>Selecione uma imagem : </td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="file" name="imagem" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="botao" value="Enviar" /></td>
            </tr>
        </table>
    </form>
</div>