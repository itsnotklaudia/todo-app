const editModalElement = document.getElementById('editListModal')
const editListInput = document.getElementById('listsEditInput')

editModalElement.addEventListener('shown.bs.modal', () => {
    editListInput.focus()
})

const editListModal = new bootstrap.Modal('#editListModal')

const editListBtns = document.querySelectorAll('.edit-list-btn');

editListBtns.forEach((button) => {
    button.addEventListener('click', event => {
        event.preventDefault();

        let id = button.dataset.id;
        let name = button.dataset.name;

        document.querySelector('#listsIdInput').value = id;
        document.querySelector('#listsEditInput').value = name;

        editListModal.show();
    });
});
