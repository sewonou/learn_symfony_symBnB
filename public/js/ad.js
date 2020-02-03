$('#add-image').click(function () {
    //Récupération du numéro des futurs champs à créer
    //const index = $('#ad_images div.form-group').length;
    const index = +$('#widgets-counter').val();

    // Récupération des prototypes des entrées
    tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    // Injection du code dans la div
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    //Gérer les boutons de suppression d'une image
    handleDeleteButtons();
});
function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    })
}

function updateCounter(){
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);

}
updateCounter();
handleDeleteButtons();