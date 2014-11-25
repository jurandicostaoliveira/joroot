/**
 *Validacao para formulario de cadastro de dados
 *
 * @returns {Boolean}
 */
function requiredFormNews() {
    try {
        with (document.formNews) {
            if (title.value == '') {
                throw{
                    message: 'Preencha o titulo'
                };
            }
            if (description.value.length < 10) {
                throw{
                    message: 'Preencha o campo mensagem, minimo 10 caracteres'
                };
            }
        }
    } catch (e) {
        alert(e.message);
        return false;
    }
}

/**
 * Validacao para formulario de cadastro de imagem
 * 
 * @returns {Boolean}
 */
function requiredFormNewsImage() {
    try {
        with (document.formNewsImage) {
            if (image.value == '') {
                throw{
                    message: 'Selecione uma imagem'
                };
            }
        }
    } catch (e) {
        alert(e.message);
        return false;
    }
}

/**
 * Confirmacao
 * 
 * @param {int} id
 * @returns {undefined}
 */
function confirmRemoveNews(id) {
    if (confirm('Tem certeza que deseja excluir essa noticias?')) {
        window.location.href = 'news/remove/' + id;
    }
}