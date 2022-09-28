let isUpdate = false;

let validated_input = true;

let search = '';

let data = {
    invoiceNumber: '',
    supplierName:'',
    supplierId: 0,
    value: 0,
    serverId: 0,
    serverName: '',
    cashierId: 0,
    cashierName: '',
    outletId: 0, 
    date: '', 
    id:0
}

const defineComponentElement = () => {
   const topUpBalanceContainer = document.querySelector('#top-up-balance-container');
   const countData = document.querySelector('#count-data');
   const topUpBalanceListTable = document.querySelector('#top-up-balance-list-table');
   const searchTopUpBalance = document.querySelector('#search-top-up-balance');
   

   //input component
   const supplierInput = document.querySelector('#supplier-input');
   const supplierList = document.querySelector('#supplier-list');
   const server = document.querySelector('#server');
   const balance = document.querySelector('#balance');
   const selectCash = document.querySelector('#select-cash');

   const submitCreate = document.querySelector('#submit-create');
   const submitEdit = document.querySelector('#submit-edit');

   const invoiceNumber = document.querySelector('#invoice-number');

   //detail component
   const invoiceNumberDetail = document.querySelector('#invoice-number-detail');
   const serverNameDetail = document.querySelector('#server-name-detail');
   const supplierNameDetail = document.querySelector('#supplier-name-detail');
   const dateDetail = document.querySelector('#date-detail');
   const valueDetail = document.querySelector('#value-detail');
   const cashDetail = document.querySelector('#cash-detail');


    return {
        topUpBalanceContainer, topUpBalanceListTable, invoiceNumber, supplierList, supplierInput, server, balance, selectCash, submitCreate,countData, searchTopUpBalance, invoiceNumberDetail, serverNameDetail, supplierNameDetail, dateDetail, valueDetail, cashDetail, submitEdit
    }
}

const setDefault = () => {
    const {
        topUpBalanceContainer, supplierInput, server, balance, selectCash
    } = defineComponentElement();

    search = '';

    validated_input = false;

    data = {
        invoiceNumber: '',
        supplierName:'',
        supplierId: 0,
        value: 0,
        serverId: 0,
        serverName: '',
        cashierId: -1,
        cashierName: '',
        outletId: parseInt(topUpBalanceContainer.dataset.outletId),
        date:'',
        id:0
    }
}

const validationInput = () => {
    const {
        submitCreate, submitEdit
    } = defineComponentElement();

    validated_input = true;

    if (!data.invoiceNumber || !data.supplierName || data.supplierId < 1 || data.value < 1 || data.serverId < 1 ||data.cashierId < 0) {
        validated_input = false;
    }

    if (validated_input) {
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

const selectsupplier = (value) => {
    const { 
        supplierInput
    } = defineComponentElement();

    data.supplierName = value.dataset.name;
    data.supplierId = value.dataset.id;
    supplierInput.value = data.supplierName;

    validationInput();
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

const supplierDropdownChange = (value) => {
    value.value = data.supplierName ? data.supplierName : '';
}

const supplierDropdownKeyup = async (value) => {
    await getsuppliers(value.value);
}

const supplierDropdownFocus = async (value) => {
    await getsuppliers(data.supplierName);
}

const showData = (result) => {
    const {
        topUpBalanceListTable
    } = defineComponentElement();

    if (result.length > 0) {
        let list = '';

        result.map(r=> {
            list +=`
            <tr>
                <td>${dateReadable(r.date)}</td>
                <td>${r.invoice_number}</td>
                <td>${r.supplier_name}</td>
                <td>${r.server_name}</td>
                <td class="text-right">Rp.${formatRupiah(r.value.toString())}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item edit-accounts" data-id="${r.id}" data-toggle="modal" data-target="#detailTopUpBalanceModal" onclick="handleDetailSalesReturn(${r.id})">
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
                            <button class="dropdown-item edit-accounts" data-id="${r.id}" data-toggle="modal" data-target="#createTopUpBalanceModal" onclick="editData(${r.id})">
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
                            <button class="dropdown-item " data-id="${r.id}"data-toggle="modal" onclick="deleteTopUpBalance(this)">
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
            </tr>
            `;
            topUpBalanceListTable.innerHTML = list;
        })
    } else {
        topUpBalanceListTable.innerHTML = `
        <tr>
            <td colspan="6" class="text-center font-italic">Tidak Ada Data</td>
        </tr>
        `;
    }
}

const getData = async () => {
    const {
        topUpBalanceListTable, countData
    } = defineComponentElement();

    topUpBalanceListTable.innerHTML = `
        <tr class="journal-table-row">
            <td colspan="6" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </td>
        </tr>
    `;

    let url =  `/api/top-up-balance?outlet_id=${data.outletId}&search=${search}`;

    await axios.get(url)
                .then(res => {
                    countData.innerHTML = res.data.data.length;
                    showData(res.data.data);
                })
                .catch(err => {
                    console.log(err);
                })
}

const getNewInvoice = async () => {

    let url = `/api/top-up-balance/new-invoice?outlet_id=${data.outletId}`;

    await axios.get(url)
                .then((result) => {
                    data.invoiceNumber = result.data.data;
                }).catch((err) => {
                    console.log(err);
                });
}

const handleModalInput = () => {
    const {
        supplierInput, server, balance, selectCash, invoiceNumber
    } = defineComponentElement();

    //input value

    invoiceNumber.value = data.invoiceNumber;
    supplierInput.value = data.supplierName;
    server.value = data.serverId;
    balance.value = formatRupiah(data.value.toString());
    selectCash.value = data.cashierId;
}

const addData = async () => {
    setDefault();
    await getNewInvoice();
    handleModalInput();
}

const selectServer = (value) => {
    data.serverId = parseInt(value.value);
    validationInput();
}

const selectCashier = (value) => {
    data.cashierId = parseInt(value.value);
    validationInput();
}

const handleBalanceChangeInput = (value) => {
    data.value = toPrice(value.value);
    validationInput();
}

const setCurrencyFormat = (value) => {
    if (!value.value) {
        value.value = 0;
    }

    let temp = toPrice(value.value);

    value.value = formatRupiah(temp.toString());
}

const handleSubmitCreate = async () => {
    let url =  `/api/top-up-balance`;

    await axios.post(url, data)
                .then(async (result) => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Pembelian Saldo Berhasil Ditambah`,
                        showConfirmButton: false,
                        timer: 1000
                      });
                    setDefault();
                    await getData();
                }).catch((err) => {
                    console.log(err);
                });
}

const handleSubmitEdit = async () => {
    let url = `/api/top-up-balance/${data.id}`;

    await axios.put(url, data)
                .then(async (result) => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Pembelian Saldo Berhasil Diubah`,
                        showConfirmButton: false,
                        timer: 1000
                      });
                    setDefault();
                    await getData();
                }).catch((err) => {
                    console.log(err);
                });
}

const deleteTopUpBalance = async (value) => {

    Swal.fire({
        title: `Anda Yakin Menghapus Data Pembelian Saldo?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
        let url = `/api/top-up-balance/${value.dataset.id}`;

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

const submitSearchTopUpBalance = async (e) => {
    e.preventDefault();
    
    const {
        searchTopUpBalance
    } = defineComponentElement();

    search = searchTopUpBalance.value;

    await getData();
}

const getSingleData = async (id) => {
    let url = `/api/top-up-balance/${id}`;
    
    await axios.get(url)
                .then((result) => {
                    
                    data = {...data, 
                        id: result.data.data.id,
                        date: result.data.data.date,
                        invoiceNumber: result.data.data.invoice_number,
                        supplierName:result.data.data.supplier_name,
                        supplierId: result.data.data.supplier_id,
                        value: result.data.data.value,
                        serverId: result.data.data.server_id,
                        serverName: result.data.data.server_name,
                        cashierId: result.data.data.cashier_id ?? 0,
                        cashierName: result.data.data.cashier_name ? result.data.data.cashier_name :'Tidak Tunai',
                    }
                    
                }).catch((err) => {
                    console.log(err);
                });
}

const handleDetailSalesReturn = async (id) => {
    const {
        invoiceNumberDetail, serverNameDetail, supplierNameDetail, dateDetail, valueDetail, cashDetail
    } = defineComponentElement();

    await getSingleData(id);
    invoiceNumberDetail.innerHTML = data.invoiceNumber;
    serverNameDetail.innerHTML = data.serverName;
    supplierNameDetail.innerHTML = data.supplierName;
    dateDetail.innerHTML = data.date;
    valueDetail.innerHTML = `Rp.${formatRupiah(data.value.toString())}`;
    cashDetail.innerHTML = data.cashierName;
    data.cashierId < 1 ? cashDetail.classList.add('text-danger') : cashDetail.classList.remove('text-danger');
    
}

const editData = async (id) => {
    isUpdate = true;
    await getSingleData(id);
    handleModalInput();
    validationInput();

}

window.addEventListener('load', async function(){
    setDefault();
    await getData();
})