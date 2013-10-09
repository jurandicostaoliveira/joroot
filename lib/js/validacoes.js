/**
 *Validacao para formulario de cadastro de dados
 */
function validarFormNoticias(){
    try{
        with(document.formNoticias){
            if(titulo.value == "") throw{
                "msg": "Preencha o titulo"
            };
            if(descricao.value.length < 10) throw{
                "msg": "Preencha o campo mensagem, minimo 10 caracteres"
            };
       }    
    }catch(erro){
        alert(erro.msg);
        return false;
    }
}

/**
 * Validacao para formulario de cadastro de imagem
 */
function validarFormImagemNoticias(){
    try{
        with(document.formImagemNoticias){
            if(imagem.value == "") throw{
                "msg": "Selecione uma imagem"
            };
       }    
    }catch(erro){
        alert(erro.msg);
        return false;
    }
}