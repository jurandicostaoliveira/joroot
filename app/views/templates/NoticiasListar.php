<p style="float: left; width: 100%; padding: 10px 0px;">
    <a href="noticias/adicionar">+ Nova not&iacute;cia</a>
</p>    
<table border="1" width="100%">
    <tr>
        <td><strong>Titulo</strong></td>
        <td><strong>Not&iacute;cia</strong></td>
        <td><strong>Imagem</strong></td>
        <td><strong>Editar</strong></td>
        <td><strong>Excluir</strong></td>
    </tr>
    <?php foreach ($dados as $d): ?>
    <tr>
        <td><?php echo $d['titulo']; ?></td>
        <td><?php echo $d['descricao']; ?></td>
        <td>
            <img src="lib/images/<?php echo $d['imagem']; ?>" alt="imagem" /><br />
            <a href="noticias/editar-imagem/<?php echo $d['id']; ?>">Alterar</a>
        </td>
        <td><a href="noticias/editar/<?php echo $d['id']; ?>">Editar</a></td>
        <td><a href="noticias/excluir/<?php echo $d['id']; ?>">Excluir</a></td>
    </tr>
    <?php endforeach; ?>
</table>