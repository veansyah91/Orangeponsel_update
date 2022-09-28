const createAccountButton = document.getElementById('create-account-button');

let setEditStatus = false;

let accounts = [];
let inputAccount = {
    code: '',
    name: '',
    classification: '',
    is_active: true,
    cash: false,
    outlet_id: createAccountButton.dataset.outletId
}

//variabel data akun pada modal
// inputForm
const categoryAccount = document.getElementById('categoryAccount');
const codeAccount = document.getElementById('codeAccount');
const idAccount = document.getElementById('id-account');
const nameAccount = document.getElementById('nameAccount');
const isCashAccount = document.getElementById('cashAccount');
const isActiveAccount = document.getElementById('isActiveAccount');
const addAccountButton = document.getElementById('add-account-button');
const editAccountButton = document.getElementById('edit-account-button');

// Modal
const createAccountModalLabel = document.getElementById('createAccountModalLabel');

const setDefaultAddAccountButton = () => {
    if (categoryAccount.value == '' || nameAccount.value == '' || categoryAccount.value == '') {
        !setEditStatus ? addAccountButton.classList.add('d-none') : editAccountButton.classList.add('d-none');
    } else {
        !setEditStatus ? addAccountButton.classList.remove('d-none') : editAccountButton.classList.remove('d-none');
    }
}

addAccountButton.addEventListener('click', () => {
    inputAccount.name = nameAccount.value;
    inputAccount.is_active = isActiveAccount.checked;
    inputAccount.cash = isCashAccount.checked;

    axios.post(`/api/account/add-account`, inputAccount)
    .then(response => {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `Akun ${inputAccount.name} berhasil ditambahkan`,
            showConfirmButton: false,
            timer: 1000
          });
          getData();
    })
    .catch(error => {
        console.log(error);
    })
})

editAccountButton.addEventListener('click', () => {
    inputAccount.name = nameAccount.value;
    inputAccount.is_active = isActiveAccount.checked;
    inputAccount.cash =  cashAccount.checked;
    inputAccount.code = codeAccount.value;
    inputAccount.classification = categoryAccount.value;

    axios.put(`/api/account/edit-account/${idAccount.value}`, inputAccount)
    .then(response => {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `Akun ${inputAccount.name} berhasil diubah`,
            showConfirmButton: false,
            timer: 1500
        });
        getData();
    })
    .catch(error => {
        console.log(error);
    })
})

nameAccount.addEventListener('keyup', () => {
    setDefaultAddAccountButton();
})

const accountTableBody = document.getElementById('account-table-body');

const defaultValue = () => {
    categoryAccount.value = '';
    categoryAccountId.value = '';
    codeAccount.value = '';
    nameAccount.value = '';
    isCashAccount.checked = false;
    isActiveAccount.checked = true;
    createCategoryAccountButton.classList.remove('d-none');
    editCategoryAccountButton.classList.add('d-none');
    createAccountModalLabel.innerHTML = 'Tambah Akun';
    setEditStatus = false;
    setDefaultAddAccountButton();
}

categoryAccount.addEventListener('keyup', function(){
    getAccountCategory(this.value);
})

const showData = () => {
    
    let list = '';

    accounts.map((account, index) => {
        list += `<tr>
            <td>${account.code}</td>
            <td>${account.name}</td>
            <td>${account.classification}</td>
            <td class="${account.is_active > 0 ? 'text-success' : 'text-danger'}">${ account.is_active > 0 ? 'Aktif' : 'Tidak Aktif' }</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item edit-accounts" data-id="${account.id}" data-code=${account.code} data-name="${account.name}" data-classification="${account.classification}" data-is-active="${account.is_active}" data-cash="${account.cash}" data-toggle="modal" data-target="#createAccountModal">
                            <div class="row">
                                <div class="col-3 my-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square text-success" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </div>
                                <div class="col-9 my-auto">
                                    Ubah
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </td>
        </tr>`
    });

    accountTableBody.innerHTML = list;

    const editAccounts = Array.from(document.getElementsByClassName('edit-accounts'));

    editAccounts.map((editAccount, index) => {
        editAccount.addEventListener('click', function(){
            const idAccount = document.getElementById('id-account');
            setEditStatus = true;
            editAccountButton.classList.remove('d-none');
            addAccountButton.classList.add('d-none') 
            createAccountModalLabel.innerHTML = 'Ubah Akun';
            idAccount.value = this.dataset.id;
            codeAccount.value = this.dataset.code;
            nameAccount.value = this.dataset.name;
            categoryAccount.value = this.dataset.classification;
            isActiveAccount.checked = this.dataset.isActive > 0 ? true : false;
            cashAccount.checked = this.dataset.cash > 0 ? true : false;

            inputAccount.name = nameAccount.value;
            inputAccount.is_active = isActiveAccount.checked;
            inputAccount.cash =  cashAccount.checked;
            inputAccount.code = codeAccount.value;
            inputAccount.classification = categoryAccount.value;
        })
    })
}

const getData = async () =>  {
    accountTableBody.innerHTML = `<tr><td colspan="5" class="text-center"><div class="spinner-border" role="status">
                                    <span class="sr-only"></span>
                                </div></td></tr>`;
    await axios.get(`/api/account/getData?outletId=${createAccountButton.dataset.outletId}`)
        .then(response => {
            accounts = response.data.data;
            showData();
        })
        .catch(error => {
            console.log(error);
        });
}

const createAccountCategoryModal = document.getElementById('create-account-category-modal');

createAccountCategoryModal.addEventListener('click', () => {
    getAccountCategory();
})

const categoryAccountName = document.getElementById('categoryAccountName');
const categoryAccountId = document.getElementById('categoryAccountId');

const createCategoryAccountButton = document.getElementById('create-category-account-button');
const editCategoryAccountButton = document.getElementById('edit-category-account-button');

let categoryAccountNameValue = '';

categoryAccountName.addEventListener('keyup', () => {
    categoryAccountName.value ? createCategoryAccountButton.removeAttribute('disabled') : createCategoryAccountButton.setAttribute('disabled', 'disabled');
});

const categoryAccountCloses = Array.from(document.getElementsByClassName('category-account-close'));

categoryAccountCloses.map(categoryAccountClose => {
    categoryAccountClose.addEventListener('click', () => {
        categoryAccount.value = '';
    })
})

createAccountButton.addEventListener('click',async () => {
    defaultValue();
})

createCategoryAccountButton.addEventListener('click', async () => {
    let name = categoryAccountName.value;
    await axios.post('/api/account/get-account-category', {name})
    .then( _ => {
        categoryAccountName.value = '';
        getAccountCategory();
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `Kategori ${name} berhasil ditambahkan`,
            showConfirmButton: false,
            timer: 1500
          })
    })
    .catch(error => {
        console.log(error);
    });
})

const categoryAccountFunc = async (id, name) => {
    createCategoryAccountButton.classList.add('d-none');
    editCategoryAccountButton.classList.remove('d-none');
    categoryAccountName.value = name;
    categoryAccountId.value = id;
}

editCategoryAccountButton.addEventListener('click', async () => {
    let id = categoryAccountId.value;
    let name = categoryAccountName.value;
    await axios.put(`/api/account/update-account-category/${id}`, {name})
    .then(response => {
        categoryAccountName.value = '';
        createCategoryAccountButton.classList.remove('d-none');
        editCategoryAccountButton.classList.add('d-none');
        getAccountCategory();
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `Kategori ${name} berhasil diubah`,
            showConfirmButton: false,
            timer: 1500
          })
    })
    .catch(error => {
        console.log(error);
    });
})

const deleteAccountCategoryFunc = async (id, name) => {
    axios.delete(`/api/account/delete-account-category/${id}`)
    .then(response => {
        if (response.data.status == 'success') {
            getAccountCategory();
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `Kategori ${name} berhasil dihapus`,
                showConfirmButton: false,
                timer: 1500
            })
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: `${response.data.message}`,
                showConfirmButton: false,
                timer: 1500
            })
        }
        
    })
    .catch(error => {
        console.log(error);
    })
}

const createAccountCategoryModalContent = document.getElementById('createAccountCategoryModalContent');

createAccountCategoryModalContent.addEventListener('click', (e) => {
    if(inputAccount.classification) {
        categoryAccount.value = inputAccount.classification;
    } else {
        categoryAccount.value = null;
    }
})

const selectAccountCategory = (name) => {
    inputAccount.classification = name;
    categoryAccount.value = name;

    //cek nomor account selanjutnya
    axios.get(`/api/account/get-next-account-number?outletId=${createAccountButton.dataset.outletId}&classification=${name}`)
    .then(response => {
        inputAccount.code = response.data.data ? parseInt(response.data.data.code) + 1 : 1;
        codeAccount.value = inputAccount.code;
        setDefaultAddAccountButton();
    })
    .catch(error => {
        console.log(error);
    });
}

codeAccount.addEventListener('change', () => {
    inputAccount.code = codeAccount.value;
})

const getAccountCategory = async (name = '') => {
    const accountCategoryDropdownList = document.getElementById('account-category-dropdown-list');

    let accountCategotyDetail = document.getElementById('accountCategotyDetail');

    accountCategoryDropdownList.innerHTML = `<a class="dropdown-item text-center" href="#" disabled><div class="spinner-border" role="status">
                                    <span class="sr-only"></span>
                                </div></a>`
    await axios.get(`/api/account/get-account-category?name=${name}`)
        .then(response => {
            let accountCategoriesList = response.data.data;

            let list = '';
            let list2 = '';
        
            accountCategoriesList.forEach((accountCategory, index) => {
                list += `<a class="dropdown-item" href="#" onclick="selectAccountCategory('${accountCategory.name}')">${accountCategory.name}</a>`;
                list2 += `<tr>
                <td>${accountCategory.name}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item"
                                onclick="categoryAccountFunc(${accountCategory.id}, '${accountCategory.name}')">
                                <div class="row">
                                    <div class="col-3 my-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square text-success" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </div>
                                    <div class="col-9 my-auto">
                                        Ubah
                                    </div>
                                </div>
                            </button>
    
                            <button class="dropdown-item" onclick="deleteAccountCategoryFunc(${accountCategory.id}, '${accountCategory.name}')">
                                <div class="row">
                                    <div class="col-3 my-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                        </svg>
                                    </div>
                                    <div class="col-9 my-auto">
                                        Hapus
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>`
            });

            accountCategoryDropdownList.innerHTML = list;
            accountCategotyDetail.innerHTML = list2;

            
        })
        .catch(error => {
            console.log(error);
        })
}

window.addEventListener('load', function() {
    getData();
    getAccountCategory();
});