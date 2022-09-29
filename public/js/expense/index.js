let search = '';
let isUpdate = false;

let data = {
    id:0,
    outletId: 0,
    cashId:0,
    cashName:'',
    itemId:0,
    itemName:'',
    value:0,
    description:'',
    noRef:'',
    date:'', 
    supplierId: 0,
    supplierName: ''
};

const defineComponentElement = () => {
    const expenseContainer = document.querySelector('#expense-container');
    const expenseListTable = document.querySelector('#expense-list-table');
    const countData = document.querySelector('#count-data');

    //input modal
    const noRef = document.querySelector('#no-ref');
    const itemExpense = document.querySelector('#item-expense');
    const expenseList = document.querySelector('#expense-list');
    const supplierList = document.querySelector('#supplier-list');
    const supplier = document.querySelector('#supplier');
    const date = document.querySelector('#date');
    const valueInput = document.querySelector('#value');
    const selectCash = document.querySelector('#select-cash');
    const supplierContainer = document.querySelector('#supplier-container');

    const submitCreate = document.querySelector('#submit-create');
    const submitEdit = document.querySelector('#submit-edit');

    //modal detail
    const dateDetail = document.querySelector('#date-detail');
    const noRefDetail = document.querySelector('#no-ref-detail');
    const cashNameDetail = document.querySelector('#cash-name-detail');
    const expenseNameDetail = document.querySelector('#expense-name-detail');
    const valueDetail = document.querySelector('#value-detail');
    const supplierNameDetail = document.querySelector('#supplier-name-detail');
    const cashDetail = document.querySelector('#cash-detail');
    const supplierDetailRow = document.querySelector('#supplier-detail-row');
    const descriptionDetail = document.querySelector('#description-detail');
 
     return {
        expenseContainer, expenseListTable, noRef, itemExpense, date, expenseList, supplierList,supplier, valueInput, submitCreate, submitEdit, supplierContainer, dateDetail, noRefDetail, cashNameDetail, expenseNameDetail, valueDetail, supplierNameDetail, cashDetail, supplierDetailRow, descriptionDetail, selectCash, countData
     }
 }

 const validationInputModal = () => {
    const { 
        submitCreate, submitEdit
    } = defineComponentElement();

    let isValidated = false;

    if (data.itemId > 0 && data.date && data.value > 0 && data.itemId > 0 && data.cashId > -1) {
        isValidated = true;
    }

    if (isValidated) {
        if (isUpdate) {
            submitEdit.classList.remove('d-none');
            submitEdit.removeAttribute('disabled');
        } else {
            submitCreate.classList.remove('d-none');
            submitCreate.removeAttribute('disabled');
        }
    } else {
        submitCreate.classList.add('d-none');
        submitCreate.setAttribute('disabled', 'disabled');

        submitEdit.classList.add('d-none');
        submitEdit.setAttribute('disabled', 'disabled');
    }
 }

 const changeDateInputFunc = (value) => {
    const { 
        date
    } = defineComponentElement();

    validationInputModal();
    
    if (value.getAttribute('id') == 'date') {
        data.date = dateFormatToSaveIntoDatabase(value.value);
        date.value = toDateFormat(value.value);
        
        return;
    }

}

const setDefault = () => {
    const { expenseContainer, itemExpense, valueInput,selectCash, supplierContainer } = defineComponentElement();
    search = '';
    isUpdate = false;
    
    data = {
        id:0,
        outletId: parseInt(expenseContainer.dataset.outletId),
        cashId:-1,
        cashName:'',
        itemId:0,
        itemName:'',
        value:0,
        description:'',
        noRef:'',
        date:'',
        supplierId: 0,
        supplierName: ''
    };

    validationInputModal();

}

const selectexpense = (value) => {
    const { 
        itemExpense
    } = defineComponentElement();

    data.itemId = parseInt(value.dataset.id);
    data.itemName = value.dataset.name;
    itemExpense.value = data.itemName;

    validationInputModal();

}

const setCurrencyFormat = (value) => {
    value.value = formatRupiah(value.value);

    if (!toPrice(value.value)) {
        value.value = 0;
    }

    data.value = parseInt(toPrice(value.value));

    validationInputModal();
}

const selectCashier = (value) => {
    const { 
        supplierContainer
    } = defineComponentElement();

    data.cashId = parseInt(value.value);
    if (data.cashId == 0) {
        supplierContainer.classList.remove('d-none');
    }
    else {
        supplierContainer.classList.add('d-none');
    }

    validationInputModal();
}

const handleChangeDescription = (event) => {
    data.description = event.target.value;
}

const getExpenses = async (value) => {
    const { 
        expenseList
    } = defineComponentElement();

    let url = `/api/account/get-expense?outlet_id=${data.outletId}&search=${value}`;

    await axios.get(url)    
                .then(res=>{
                    expenses = res.data.data;

                    let list = '';
                    expenses.map(expense => {
                        list += `<a class="dropdown-item" href="#" onclick="selectexpense(this)" data-id="${expense.id}" data-name="${expense.name}">
                            <div class="row">
                                <div class="col-12">
                                    ${expense.name}
                                </div>
                            </div>
                                
                            </a>`;
                    })
                    expenseList.innerHTML = list

                })
                .catch(err=>{
                    console.log(err);
                })
}

const expenseDropdownChange = (value) => {
    value.value = data.itemName ? data.itemName : '';
    validationInputModal();

}

const expenseDropdownKeyup = async (value) => {
    
    await getExpenses(value.value);
}

const expenseDropdownFocus = async (value) => {
    await getExpenses(data.itemName);
}

const showData = (result) => {
    const { expenseListTable, countData } = defineComponentElement();
    
    countData.innerHTML = result.length;

    if (result.length > 0) {
         let list = '';

         result.map(res => {
            list += `
            <tr>
                <td>${res.date}</td>
                <td>${res.no_ref}</td>
                <td>${res.item_name}</td>
                <td>${res.cash_name ? res.cash_name : '-'}</td>
                <td class="text-right">Rp.${formatRupiah(res.value.toString())}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item edit-accounts" data-id="${res.id}" data-toggle="modal" data-target="#detailExpenseModal" onclick="handleDetailExpense(${res.id})">
                                <div class="row">
                                    <div class="col-3 my-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle text-info" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                    </svg>
                                    </div>
                                    <div class="col-9 my-auto text-info">
                                        Detail
                                    </div>
                                </div>
                            </button>
                            <button class="dropdown-item edit-accounts" data-id="${res.id}" data-toggle="modal" data-target="#createExpenseModal" onclick="editData(${res.id})">
                                <div class="row">
                                    <div class="col-3 my-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square text-success" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </div>
                                    <div class="col-9 my-auto text-success">
                                        Ubah
                                    </div>
                                </div>
                            </button>
                            <button class="dropdown-item " data-id="${res.id}"data-toggle="modal" onclick="deleteExpense(${res.id})">
                                <div class="row">
                                    <div class="col-3 my-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                        </svg>
                                    </div>
                                    <div class="col-9 my-auto text-danger">
                                        Hapus
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>`
         })

         expenseListTable.innerHTML = list;

    } else {
        expenseListTable.innerHTML = `
        <tr class="journal-table-row">
            <td colspan="6" class="text-center font-italic">
                Tidak Ada Data
            </td>
        </tr>
    `;
    }
}

const getData = async () => {
    const { expenseListTable } = defineComponentElement();
    let url = `/api/expense?outlet_id=${data.outletId}&search=${search}`;

    expenseListTable.innerHTML = `
        <tr class="journal-table-row">
            <td colspan="6" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </td>
        </tr>
    `;

    await axios.get(url)
                .then((result) => {
                    showData(result.data.data);
                }).catch((err) => {
                    console.log(err);
                });

}

const handleModalInput = () => {
    const {
        noRef, itemExpense, valueInput, date, selectCash, supplierContainer
    } = defineComponentElement();

    //input value
    noRef.value = data.noRef;
    itemExpense.value = data.itemName;
    valueInput.value = formatRupiah(data.value.toString());
    date.value = data.date ? toDateFormat2(data.date) : '';
    selectCash.value = data.cashId ?? -1;

    if (data.cashId) {
        supplierContainer.classList.add('d-none');
    } else {
        supplierContainer.classList.remove('d-none');
    }
    
}

const getNewInvoice = async () => {
    let url = `/api/expense/new-invoice?outlet_id=${data.outletId}`;

    await axios.get(url)
                .then((result) => {
                    data.noRef = result.data.data;
                }).catch((err) => {
                    console.log(err);
                });
}

const addData = async () => {

    setDefault();
    await getNewInvoice();
    handleModalInput();
}

const editData = async (id) => {
    let url = `/api/expense/${id}`;

    isUpdate = true;

    await axios.get(url)
                .then(res=>{
                    data = {
                        ...data,
                        id:res.data.data.id,
                        cashId:res.data.data.cash_id,
                        cashName:res.data.data.cash_name,
                        itemId:res.data.data.item_id,
                        itemName:res.data.data.item_name,
                        value:res.data.data.value,
                        description:res.data.data.description,
                        noRef:res.data.data.no_ref,
                        date:res.data.data.date, 
                        supplierId: res.data.data.supplier_id,
                        supplierName: res.data.data.supplier_name
                    }

                    handleModalInput();
                    validationInputModal();
                })
                .catch(err => {
                    console.log(err);
                })
}

const selectsupplier = (value) => {
    const { 
        supplier
    } = defineComponentElement();


    data = {...data, supplierName: value.dataset.name, supplierId: parseInt(value.dataset.id)};

    supplier.value = data.supplierName;

    validationInputModal();
}

const getsuppliers = async (value) => {
    const { 
        supplierList
    } = defineComponentElement();

    let url = `/api/supplier/get-supplier?supplier=${value}`;
    

    await axios.get(url)    
                .then(res=>{
                    suppliers = res.data.data;

                    let list = '';
                    suppliers.map(supplier => {
                        list += `<a class="dropdown-item" href="#" onclick="selectsupplier(this)" data-id="${supplier.id}" data-name="${supplier.nama}" data-supplier-id="${supplier.supplier_id}"">
                            <div class="row">
                                <div class="col-12">
                                    ${supplier.nama}
                                </div>
                            </div>
                                
                            </a>`;
                    })
                    supplierList.innerHTML = list

                })
                .catch(err=>{
                    console.log(err);
                })
}

const selectitem = (value) => {
    data.itemId = parseInt(value.value);
    validationInputModal();
}


const supplierDropdownChange = (value) => {
    value.value = data.supplierName ? data.supplierName : '';
}

const supplierDropdownKeyup = async (value) => {
    await getsuppliers(value.value);
}

const supplierDropdownFocus = async () => {
    await getsuppliers(data.supplierName);
}

const handleSubmitCreate = async () => {
    let url = `/api/expense`;

    await axios.post(url, data)
                .then(async res => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Uang Keluar Berhasil Ditambah`,
                        showConfirmButton: false,
                        timer: 1000
                      });
                    setDefault();
                    await getData();
                })
                .catch(err => {
                    console.log(err);
                })
}

const handleDetailExpense = async (id) => {
    const { 
        dateDetail, noRefDetail, cashNameDetail, expenseNameDetail, valueDetail, supplierNameDetail, cashDetail, supplierDetailRow, descriptionDetail
    } = defineComponentElement();

    let url = `/api/expense/${id}`;

    await axios.get(url)
                .then(res => {
                    let detail = res.data.data;
                    dateDetail.innerHTML = dateReadable(detail.date);
                    noRefDetail.innerHTML = detail.no_ref;
                    cashNameDetail.innerHTML = detail.cash_name ? detail.cash_name : 'Tidak Tunai';
                    expenseNameDetail.innerHTML = detail.item_name;
                    valueDetail.innerHTML = `Rp.${formatRupiah(detail.value.toString())}`;
                    descriptionDetail.innerHTML = detail.description;

                    if (detail.cash_name) {
                        supplierDetailRow.classList.add('d-none');
                        supplierNameDetail.classList.remove('text-danger');
                        cashNameDetail.classList.remove('text-danger');
                    }
                    else {
                        supplierDetailRow.classList.remove('d-none');
                        supplierNameDetail.classList.add('text-danger');
                        cashNameDetail.classList.add('text-danger');

                        supplierNameDetail.innerHTML = detail.supplier_name;
                    }
                })
                .catch(err => {
                    console.log(err);
                })

}

const deleteExpense = (id) => {
    Swal.fire({
        title: `Anda Yakin Menghapus Data Pengeluaran?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            let url = `/api/expense/${id}`;

            axios.delete(url)
            .then(async res => {

                setDefault();
                await getData();
                
                Swal.fire(
                    'Terhapus!',
                    'Data Anda Berhasil Dihapus.',
                    'success'
                )
            })
            .catch(err => {
                console.log(err);
            })  
        }
      })
}

const handleSubmitEdit = async () => {
    let url = `/api/expense/${data.id}`;
    
    await axios.put(url, data)
                .then(async res=>{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Uang Keluar Berhasil Ditambah`,
                        showConfirmButton: false,
                        timer: 1000
                      });
                    setDefault();
                    await getData();
                })
                .catch(err => {
                    console.log(err);
                })
}

window.addEventListener('load', async function(){
    setDefault();
    await getData();
})