//state
let purchaseReturn = {
    id: 0,
    outletId: 0,
    noRef: '',
    supplierName: '',
    supplierId: '',
    dateDelivery:'',
    dateAcceptedOnSupplier:'',
    dateReceipt:'',
    products:[],
    value: 0,
    valueApprovement: 0,
    cashierId: 0,
    cashierName: 0,
    approvementDescription: 'menunggu',
}

let search = '';

let is_validated = false;

let setUpInput = false;

let isEdit = false;

let products = [];

let rowInput = 1;

const defineComponentElement = _ => {
    const countData = document.querySelector('#count-data');

    const searchPurchaseReturn = document.querySelector('#search-purchase-return');

    const purchaseReturnContainer = document.querySelector('#purchase-return-container');
    const purchaseReturnListTable = document.querySelector('#purchase-return-list-table');
    const purchaseReturnInputList = document.querySelector('#purchase-return-input-list');
    const invoiceNumber = document.querySelector('#invoice_number');
    const supplierList = document.querySelector('#supplier-list');
    const supplierInput = document.querySelector('#supplier-input');
    const submitCreate = document.querySelector('#submit-create');
    const submitEdit = document.querySelector('#submit-edit');
    const selectCash = document.querySelector('#select-cash');

    const createPurchaseReturnModalLabel = document.querySelector('#createPurchaseReturnModalLabel');

    const dateDelivery = document.querySelector('#date-delivery');
    const dateAcceptedOnSupplier = document.querySelector('#date-accepted-on-supplier');
    const dateReceipt = document.querySelector('#date-receipt');
    const statusApprovement = document.querySelector('#status-approvement');

    const purchaseReturnProducts = document.getElementsByClassName('purchase-return-product');
    const purchaseReturnQtys = document.getElementsByClassName('purchase-return-qty');
    const purchaseReturnPrices = document.getElementsByClassName('purchase-return-price');
    const purchaseReturnTotalPrices = document.getElementsByClassName('purchase-return-total-price');
    const purchaseReturnTotalPriceApprovements = document.getElementsByClassName('purchase-return-total-price-approvement');
    const purchaseReturnRows = document.getElementsByClassName('purchase-return-row');
    const removeRowBtn = document.getElementsByClassName('remove-row-btn');
    const productList = document.getElementsByClassName('product-list');

    const cashDetailContainer = document.querySelector('#cash-detail-container');
    const cashDetail = document.querySelector('#cash-detail');
    const purchaseReturnDetailList = document.querySelector('#purchase-return-detail-list');
    const noRefDetail = document.querySelector('#no-ref-detail');
    const supplierNameDetail = document.querySelector('#supplier-name-detail');
    const dateDeliveryDetail = document.querySelector('#date-delivery-detail');
    const dateAcceptedOnSupplierDetail = document.querySelector('#date-accepted-on-supplier-detail');
    const dateReceiptDetail = document.querySelector('#date-receipt-detail');

    const valueDetail = document.querySelector('#value-detail');
    const valueApprovementDetail = document.querySelector('#value-approvement-detail');
    const statusDetail = document.querySelector('#status-detail');

    const grandTotal = document.querySelector('#grand-total');
    const grandTotalApprovement = document.querySelector('#grand-total-approvement');

    return {
        searchPurchaseReturn,
        countData, purchaseReturnListTable, 
        purchaseReturnContainer,
        invoiceNumber, supplierList, supplierInput, 
        submitCreate,purchaseReturnInputList,
        purchaseReturnProducts, purchaseReturnQtys, purchaseReturnPrices, purchaseReturnTotalPrices, purchaseReturnTotalPriceApprovements, purchaseReturnRows, productList, grandTotal, removeRowBtn, selectCash,   noRefDetail, supplierNameDetail, dateDeliveryDetail, dateAcceptedOnSupplierDetail, dateReceiptDetail, valueApprovementDetail, valueDetail, statusDetail, submitEdit, dateDelivery, dateAcceptedOnSupplier, dateReceipt, createPurchaseReturnModalLabel, grandTotalApprovement, statusApprovement, purchaseReturnDetailList, cashDetailContainer, cashDetail
    }
}

const setDefault = _ => {
    const { purchaseReturnContainer, supplierInput, submitCreate,selectCash, grandTotal, grandTotalApprovement, statusApprovement,dateDelivery, dateAcceptedOnSupplier, dateReceipt, } = defineComponentElement();

    isEdit = false;
    rowInput = 1;

    supplierInput.value = '';
    submitCreate.classList.add('d-none');
    submitCreate.setAttribute('disabled', 'disabled');
    selectCash.value = 0;
    grandTotal.innerHTML = 'Rp.-';
    grandTotalApprovement.value = 0;

    [
        dateDelivery, dateAcceptedOnSupplier, dateReceipt
    ].map(value => {
        value.value = '';
    })

    search = '';

    purchaseReturn = {
        id: 0,
        outletId:parseInt(purchaseReturnContainer.dataset.outletId),
        noRef: '',
        supplierName: '',
        supplierId: '',
        dateDelivery:'',
        dateAcceptedOnSupplier:'',
        dateReceipt:'',
        products:[
            {
                id:0,
                name:'',
                qty:1,
                value:0,
                valueApprovement:0,
            },
        ],
        value: 0,
        valueApprovement: 0,
        cashierId: 0,
        cashierName: '',
        approvementDescription: 'menunggu',
    }

    statusApprovement.value = purchaseReturn.approvementDescription
}

const showData  = async _ => {
    const { purchaseReturnListTable, countData } = defineComponentElement();

    purchaseReturnListTable.innerHTML = `<tr class="journal-table-row">
                    <td colspan="5" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </td>
                </tr>`;

    let url = `/api/purchase-return?outlet_id=${purchaseReturn.outletId}&search=${search}`;

    await axios.get(url)
                .then((result) => {  
                    let purchaseReturns = result.data.data;

                    countData.innerHTML = purchaseReturns.length

                    if (purchaseReturns.length > 0) {
                        let list = '';
                        purchaseReturns.map(purchaseReturn => {
                            list += `
                            <tr>
                                <td
                                    class="font-weight-bold ${purchaseReturn.approvement_description == 'menunggu' ? 'text-secondary' : 'text-success'}"
                                >
                                    ${capital(purchaseReturn.approvement_description)}
                                    ${purchaseReturn.approvement_description == 'menunggu' 
                                    ? `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
                                  </svg>` 
                                    : `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                    <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                                    <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                                  </svg>`}
                                </td>
                                <td>${dateReadable(purchaseReturn.date_delivery)}</td>
                                <td>${purchaseReturn.no_ref}</td>
                                <td>${purchaseReturn.supplier_name}</td>
                                <td class="text-right">Rp.${formatRupiah(purchaseReturn.value.toString())}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <button class="dropdown-item edit-accounts" data-id="${purchaseReturn.id}" data-toggle="modal" data-target="#detailPurchaseReturnModal" onclick="handleDetailPurchaseReturn(${purchaseReturn.id})">
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
                                            <button class="dropdown-item edit-accounts" data-id="${purchaseReturn.id}" data-toggle="modal" data-target="#createPurchaseReturnModal" onclick="editData(${purchaseReturn.id})">
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
                                            <button class="dropdown-item " data-id="${purchaseReturn.id}"data-toggle="modal" onclick="deletePurchaseReturn(this)">
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
                        });

                        purchaseReturnListTable.innerHTML = list;
                    } else {
                        purchaseReturnListTable.innerHTML = `<tr class="journal-table-row">
                                    <td colspan="5" class="text-center font-italic">
                                        Tidak Ada Data
                                    </td>
                                </tr>`
                    }

                }).catch((err) => {
                    console.log(err);
                });
}

const validationInput = _ => {
    const { 
        submitCreate, submitEdit,
    
    } = defineComponentElement();

    is_validated = true;

    for (let index = 0; index < rowInput; index++) {
        if ( !purchaseReturn.noRef || !purchaseReturn.supplierName || !purchaseReturn.supplierId || !purchaseReturn.dateDelivery || !purchaseReturn.value || !purchaseReturn.products[index].name || !purchaseReturn.products[index].qty > 0 || !purchaseReturn.products[index].value > 0) {
            is_validated = false;
        }
    }

    if (purchaseReturn.approvementDescription == 'selesai') {
     if ( !purchaseReturn.dateReceipt|| !purchaseReturn.cashierId > 0 ) {
        is_validated = false;
     }
    }

    if (is_validated) {
        if (isEdit) {
            submitEdit.classList.remove('d-none');
            submitEdit.removeAttribute('disabled');
        } else {

            submitCreate.classList.remove('d-none');
            submitCreate.removeAttribute('disabled');
        }        
    }
    else {
        submitCreate.classList.add('d-none');
        submitCreate.setAttribute('disabled', 'disabled');

        submitEdit.classList.add('d-none');
        submitEdit.setAttribute('disabled', 'disabled');
    }
}

const changeDateInputFunc = (value) => {
    const { 
        dateDelivery, dateAcceptedOnSupplier, dateReceipt, 
    } = defineComponentElement();

    validationInput();
    
    if (value.getAttribute('id') == 'date-delivery') {
        purchaseReturn.dateDelivery = dateFormatToSaveIntoDatabase(value.value);
        dateDelivery.value = toDateFormat(value.value);
        
        return;
    }

    if (value.getAttribute('id') == 'date-accepted-on-supplier') {
        purchaseReturn.dateAcceptedOnSupplier = dateFormatToSaveIntoDatabase(value.value);
        dateAcceptedOnSupplier.value = toDateFormat(value.value);
        return;
    }

    if (value.getAttribute('id') == 'date-receipt') {
        purchaseReturn.dateReceipt = dateFormatToSaveIntoDatabase(value.value);
        dateReceipt.value = toDateFormat(value.value);
        return;
    }
}

const selectSetStatusApprovement = (value) => {
    const { 
        purchaseReturnTotalPriceApprovements
    } = defineComponentElement();
    purchaseReturn.approvementDescription = value.value;

    for (let index = 0; index < rowInput; index++) {
        value.value == 'menunggu' ? purchaseReturnTotalPriceApprovements[index].setAttribute('disabled', 'disabled') : purchaseReturnTotalPriceApprovements[index].removeAttribute('disabled');

        value.value == 'menunggu' ? purchaseReturnTotalPriceApprovements[index].classList.add('disabled') : purchaseReturnTotalPriceApprovements[index].classList.remove('disabled');
        
        
        
    }

    validationInput();
}

const selectCashier = (value) => {
    purchaseReturn = {...purchaseReturn, cashierId: parseInt(value.value)};
    validationInput();
}

const getNewInvoice = async () => {
    const { purchaseReturnContainer, invoiceNumber } = defineComponentElement();

    let url = `/api/purchase-return/new-invoice-number?outlet_id=${purchaseReturnContainer.dataset.outletId}`;

    await axios.get(url)
                .then(res => {
                    let newInvoice = res.data.data;
                    purchaseReturn.noRef = newInvoice;
                    invoiceNumber.value = newInvoice;

                    purchaseReturn = {...purchaseReturn, noRef:  newInvoice};
                })
                .catch(err => {
                    console.log(err);
                })
}

const inputListPerColumn = (index) => {
    return `<td style="width: 25%">
        <div class="dropdown w-100">
            <input 
                class="form-control purchase-return-product" 
                type="text" 
                data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Masukkan Produk / Tipe / IMEI"
                autocomplete="off"
                onkeyup="productDropdownKeyup(this)"
                onclick="productDropdownFocus(this)"
                onchange="productDropdownChange(this)"
                data-order="${index}"
                value="${purchaseReturn.products[index].name}"
            >

            <div class="dropdown-menu w-100 overflow-auto product-list" style="max-height:180px">
                
            </div>
        </div>
    </td>
    <td style="width: 8%">
        <input type="text" class="form-control purchase-return-qty text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" onchange="handlePurchaseReturnQty(this)" autocomplete="off" value="${purchaseReturn.products[index].qty}" onclick="this.select()">
    </td>
    <td>
        <input type="text" class="form-control purchase-return-price text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off" value="${formatRupiah((purchaseReturn.products[index].value / purchaseReturn.products[index].qty).toString())}" onchange="handlePurchaseReturnPrice(this)" onclick="this.select()">
    </td>
    <td>
        <input type="text" class="form-control purchase-return-total-price text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off" value="${formatRupiah((purchaseReturn.products[index].value).toString())}" readonly>
    </td>
    <td>
        <input type="text" class="form-control purchase-return-total-price-approvement text-right ${purchaseReturn.approvementDescription == 'menunggu' ? 'disabled' : ''}" ${purchaseReturn.approvementDescription  == 'menunggu'  ? 'disabled="disabled"' : ''} data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off" value="${formatRupiah((purchaseReturn.products[index].valueApprovement).toString())}" onchange="handlePurchaseReturnPriceApprovement(this)" onclick="this.select()">
    </td>
    <td>
        <button class="btn btn-danger btn-sm remove-row-btn my-auto" data-order="${index}" onclick="removeRowBtnFunc(this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
            </svg>
        </button>
    </td>`
}

const setDefaultInputRow = () => {
    const { purchaseReturnInputList, supplierInput, submitCreate } = defineComponentElement();

    if (!setUpInput) {
        setDefault();
        submitCreate.classList.add('d-none');
        submitCreate.setAttribute('disabled', 'disabled');
        supplierInput.value = '';

        let list = ``;
        for (let index = 0; index < rowInput; index++) {
            list += `<tr class="purchase-return-row">
                ${inputListPerColumn(index)}
            </tr>`;
            
        }
        purchaseReturnInputList.innerHTML = list;
    }
    
}

const handleAddRowInputList = () =>{
    const { purchaseReturnInputList } = defineComponentElement();

    purchaseReturn.products = 
        [
            ...purchaseReturn.products, 
            {
                name:'',
                qty:0,
                value:0,
                id:0,
                valueApprovement:0
            }
        ];

    // create a row node:
    const row = document.createElement('tr');
    row.className = 'purchase-return-row';
    row.innerHTML = inputListPerColumn(rowInput)

    purchaseReturnInputList.appendChild(row);

    rowInput++;
    validationInput();
}

const addData = async _ => {
    const { createPurchaseReturnModalLabel } = defineComponentElement();
    createPurchaseReturnModalLabel.innerHTML = 'Tambah Data Retur Pembelian'
        
    setDefaultInputRow();
    await getNewInvoice();
}

const editData = async (id) => {
    const { invoiceNumber, supplierInput, grandTotal, selectCash, purchaseReturnContainer,purchaseReturnInputList, submitCreate, submitEdit, createPurchaseReturnModalLabel, dateDelivery, dateAcceptedOnSupplier, dateReceipt, statusApprovement, grandTotalApprovement } = defineComponentElement();

    createPurchaseReturnModalLabel.innerHTML = 'Ubah Data Retur Pembelian';

    submitCreate.classList.add('d-none');
    submitEdit.classList.remove('d-none');

    submitCreate.setAttribute('disabled', 'disabled');
    submitEdit.removeAttribute('disabled', 'disabled');
    isEdit = true;

    let url = `/api/purchase-return/${id}`;

    await axios.get(url)
                .then((result) => {
                    purchaseReturn = {
                        id: id,
                        outletId: parseInt(purchaseReturnContainer.dataset.outletId),
                        noRef: '',
                        supplierName: '',
                        supplierId: '',
                        dateDelivery:'',
                        dateAcceptedOnSupplier:'',
                        dateReceipt:'',
                        products:[],
                        value: 0,
                        valueApprovement: 0,
                        cashierId: 0,
                        approvementDescription: 'menunggu',
                    }
                    purchaseReturn = {
                                    ...purchaseReturn, 
                                    noRef: result.data.data.no_ref,
                                    supplierName: result.data.data.supplier_name,
                                    supplierId: result.data.data.supplier_id,
                                    dateDelivery: result.data.data.date_delivery,
                                    dateAcceptedOnSupplier: result.data.data.date_accepted_on_supplier,
                                    dateReceipt:result.data.data.date_receipt,
                                    products:[],
                                    value: result.data.data.value,
                                    valueApprovement: result.data.data.value_approvement,
                                    cashierId: result.data.data.account_id,
                                    approvementDescription: result.data.data.approvement_description,
                                  }
                                  
                    result.data.data.purchase_return_details.map(detail => {
                        purchaseReturn.products = [
                            ...purchaseReturn.products,
                            {
                                name:detail.product_name, 
                                qty:detail.qty,
                                value:detail.value,
                                id:detail.id,
                                valueApprovement:detail.value_approvement,
                            }
                        ]
                    });

                    let list = '';
                    purchaseReturn.products.map((_, index) => {
                        list += `<tr class="purchase-return-row">
                            ${inputListPerColumn(index)}
                        </tr>`
                    })
                    
                    invoiceNumber.value = purchaseReturn.noRef;
                    supplierInput.value = purchaseReturn.supplierName;
                    selectCash.value = purchaseReturn.cashierId;
                    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseReturn.value.toString())}`;
                    grandTotalApprovement.value = formatRupiah(purchaseReturn.valueApprovement.toString());

                    dateDelivery.value = purchaseReturn.dateDelivery;
                    dateAcceptedOnSupplier.value = purchaseReturn.dateAcceptedOnSupplier;
                    dateReceipt.value = purchaseReturn.dateReceipt;
                    statusApprovement.value = purchaseReturn.approvementDescription;

                    purchaseReturnInputList.innerHTML = list;

                    rowInput = purchaseReturn.products.length;

                }).catch((err) => {
                    console.log(err);
                });
}

const selectsupplier = (value) => {
    const { 
        supplierInput
    } = defineComponentElement();


    purchaseReturn = {...purchaseReturn, supplierName: value.dataset.name, supplierId: parseInt(value.dataset.id)};

    supplierInput.value = purchaseReturn.supplierName;

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
    value.value = purchaseReturn.supplierName ? purchaseReturn.supplierName : '';
}

const supplierDropdownKeyup = async (value) => {
    await getsuppliers(value.value);
}

const supplierDropdownFocus = async (value) => {
    await getsuppliers(purchaseReturn.supplierName);
}

const removeRowBtnFunc = (value) => {

    const { 
        purchaseReturnProducts,
        purchaseReturnQtys,
        purchaseReturnPrices,
        purchaseReturnTotalPrices,
        purchaseReturnTotalPriceApprovements,
        removeRowBtn,
        purchaseReturnRows,
        grandTotal, grandTotalApprovement
    } = defineComponentElement();

    purchaseReturn.value -= purchaseReturn.products[parseInt(value.dataset.order)].qty * purchaseReturn.products[parseInt(value.dataset.order)].value;

    purchaseReturnRows[parseInt(value.dataset.order)].remove();
    purchaseReturn.products.splice(parseInt(value.dataset.order),1);
   
    rowInput--;

    for (let index = 0; index < rowInput; index++) {
        purchaseReturnProducts[index].setAttribute('data-order', index);
        purchaseReturnQtys[index].setAttribute('data-order', index);
        purchaseReturnPrices[index].setAttribute('data-order', index);
        purchaseReturnTotalPrices[index].setAttribute('data-order', index);
        purchaseReturnTotalPriceApprovements[index].setAttribute('data-order', index);
        removeRowBtn[index].setAttribute('data-order', index);
    } 

    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseReturn.value.toString())}`;

    validationInput();
}

const productDropdownChange = (value) => {
    purchaseReturn.products[value.dataset.order].name = value.value;
    defaultQty(value.dataset.order);
    validationInput();
}

const defaultQty = (order) => {
    const { purchaseReturnQtys } = defineComponentElement();

    purchaseReturn.products[order].qty = 1;
    purchaseReturnQtys[order].value = 1;
}


const handleSelectProduct = (value) => {
    const { purchaseReturnProducts, purchaseReturnPrices, purchaseReturnTotalPriceApprovements, purchaseReturnTotalPrices, grandTotal, grandTotalApprovement } = defineComponentElement();

    purchaseReturn.products[value.dataset.order].id = parseInt(value.dataset.id);
    purchaseReturn.products[value.dataset.order].name = value.dataset.tipe;
    purchaseReturn.products[value.dataset.order].value = parseInt(value.dataset.unitPrice);
    purchaseReturn.products[value.dataset.order].valueApprovement = purchaseReturn.approvementDescription == "selesai" ? parseInt(value.dataset.unitPrice) : 0;

    purchaseReturnProducts[value.dataset.order].value = value.dataset.tipe;
    purchaseReturnPrices[value.dataset.order].value = formatRupiah(value.dataset.unitPrice.toString());
    purchaseReturnTotalPrices[value.dataset.order].value = formatRupiah(value.dataset.unitPrice.toString());
    purchaseReturnTotalPriceApprovements[value.dataset.order].value = purchaseReturn.approvementDescription == "selesai" ? formatRupiah(value.dataset.unitPrice.toString()) : 0;
    
    defaultQty(value.dataset.order);
    purchaseReturn.value += parseInt(value.dataset.unitPrice);
    purchaseReturn.valueApprovement += parseInt(value.dataset.unitPrice);
    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseReturn.value.toString())}`;
    grandTotalApprovement.value = purchaseReturn.approvementDescription == "selesai" ? `${formatRupiah(purchaseReturn.value.toString())}` : 0;

    validationInput();
}

const handleValidating = (value) => {
    validationInput();
}

const getProduct = async (value) => {
    const { productList } = defineComponentElement();

    let search = value.value;

    let url = `/api/get-products?search=${search}`;

    await axios.get(url)
                .then((result) => {
                    let products = result.data.data;
                    let list = '';
                    if (products.length > 0) {
                        products.map(product=>{
                            list += `<a class="dropdown-item" href="#" data-id="${product.id}" data-tipe="${product.tipe}" data-order="${value.dataset.order}" data-unit-price="${product.modal}" onclick="handleSelectProduct(this)">
                                <div class="row">
                                    <div class="col-12" style="font-size: 10px">
                                        ${product.kode}
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        ${product.tipe}
                                    </div>
                                </div>
                            </a>`
                        })
                        productList[parseInt(value.dataset.order)].innerHTML = list;
                    } else {
                        productList[parseInt(value.dataset.order)].innerHTML = `<a class="dropdown-item disabled" href="#" disabled>
                            
                            Produk Tidak Ditemukan
                                
                            </a>`
                    }
                }).catch((err) => {
                    console.log(err);
                });
}

const productDropdownKeyup = (value) => {
    getProduct(value);
}   

const productDropdownFocus = (value) => {
    getProduct(value);
}  

const setCurrencyFormat = (value) => {
    if (!value.value) {
        value.value = 0;
    }

    let temp = toPrice(value.value);

    value.value = formatRupiah(temp.toString());
}

const handlePurchaseReturnPriceApprovement = (value) => {
    const { grandTotalApprovement } = defineComponentElement();

    purchaseReturn.products[value.dataset.order].valueApprovement = toPrice(value.value);

    let tempTotal = 0;
    for (let index = 0; index < rowInput; index++) {
        tempTotal += purchaseReturn.products[value.dataset.order].valueApprovement;
    }

    purchaseReturn.valueApprovement = tempTotal;
    grandTotalApprovement.value = formatRupiah(purchaseReturn.valueApprovement);
}

const handlePurchaseReturnQty = (value) => {
    const { purchaseReturnTotalPrices, purchaseReturnPrices, purchaseReturnTotalPriceApprovements, grandTotal, grandTotalApprovement} = defineComponentElement();

    let total = toPrice(value.value) * toPrice(purchaseReturnPrices[value.dataset.order].value);

    purchaseReturnTotalPrices[value.dataset.order].value = `${formatRupiah(total.toString())}`;
    purchaseReturnTotalPriceApprovements[value.dataset.order].value = purchaseReturn.approvementDescription == "selesai" ? `${formatRupiah(total.toString())}` : 0;

    purchaseReturn.products[value.dataset.order].qty = toPrice(value.value);
    purchaseReturn.products[value.dataset.order].value = total;
    purchaseReturn.products[value.dataset.order].valueApprovement = total;

    let tempTotal = 0;
    for (let index = 0; index < rowInput; index++) {
        tempTotal += parseInt(toPrice(purchaseReturnTotalPrices[index].value));
    }

    purchaseReturn.value = tempTotal;
    purchaseReturn.valueApprovement = tempTotal;

    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseReturn.value.toString())}`;
    grandTotalApprovement.value = `${formatRupiah(purchaseReturn.value.toString())}`;

    validationInput();
}

const handlePurchaseReturnPrice = (value) => {
    const { purchaseReturnTotalPrices, purchaseReturnQtys, grandTotal, grandTotalApprovement, purchaseReturnTotalPriceApprovements } = defineComponentElement();

    let total = toPrice(value.value) * toPrice(purchaseReturnQtys[value.dataset.order].value);

    purchaseReturnTotalPrices[value.dataset.order].value = `${formatRupiah(total.toString())}`;
    purchaseReturnTotalPriceApprovements[value.dataset.order].value = `${formatRupiah(total.toString())}`;

    purchaseReturn.products[value.dataset.order].value = total;
    purchaseReturn.products[value.dataset.order].valueApprovement = total;

    let tempTotal = 0;
    for (let index = 0; index < rowInput; index++) {
        tempTotal += parseInt(toPrice(purchaseReturnTotalPrices[index].value))
    }

    purchaseReturn.value = tempTotal;
    purchaseReturn.valueApprovement = tempTotal;

    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseReturn.value.toString())}`;
    grandTotalApprovement.value = formatRupiah(purchaseReturn.value.toString());

    validationInput();
}

const handleSubmitCreate = async () => {
    
    const url = `/api/purchase-return`;
    await axios.post(url, purchaseReturn)
                .then(async res => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Pembelian Barang Dagang Berhasil Ditambahkan`,
                        showConfirmButton: false,
                        timer: 1000
                      });
            
                    setDefault();
                    await showData();
                })
                .catch(err => {
                    console.log(err);
                })
}

const handleSubmitEdit = async () => {
    let url = `/api/purchase-return/${purchaseReturn.id}`;

    await axios.put(url, purchaseReturn)
                .then(async (result) => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Pembelian Barang Dagang Berhasil Diubah`,
                        showConfirmButton: false,
                        timer: 1000
                      });
            
                    setDefault();
                    await showData();
                }).catch((err) => {
                    console.log(err);
                });
}

const deletepurchaseReturn = (value) => {
    Swal.fire({
        title: `Anda Yakin Menghapus Data Pembelian Barang Dagang?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/purchase-return/${value.dataset.id}`)
            .then(async _ => {
                setDefault();
                await showData();

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

const submitSearchPurchaseReturn = async (event) => {
    event.preventDefault(); 
    const { searchPurchaseReturn } = defineComponentElement();

    search = searchPurchaseReturn.value;
    await showData();
}

const deletePurchaseReturn = (value) => {
    let url = `/api/purchase-return/${value.dataset.id}`;

    Swal.fire({
        title: `Anda Yakin Menghapus Data Return Pembelian Barang Dagang?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(url)
            .then(async res => {
                setDefault();
                await showData();
                
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

const handleDetailPurchaseReturn  = async (value) => {
    const { noRefDetail, supplierNameDetail, dateDeliveryDetail, dateAcceptedOnSupplierDetail, dateReceiptDetail, valueApprovementDetail, valueDetail, statusDetail, purchaseReturnDetailList, cashDetailContainer, cashDetail } = defineComponentElement();

    let url = `/api/purchase-return/${value}`;

    await axios.get(url)
                .then(res => {
                    let result = res.data.data;
                    
                    if (result.account_id > 0) {
                        cashDetailContainer.classList.remove('d-none');
                        cashDetail.value = result.account_name
                    } else {
                        cashDetailContainer.classList.add('d-none');
                    }
                    
                    noRefDetail.value = result.no_ref;
                    supplierNameDetail.value = result.supplier_name;
                    dateDeliveryDetail.value = dateReadable(result.date_delivery);
                    dateAcceptedOnSupplierDetail.value = result.date_accepted_on_supplier ? dateReadable(result.date_accepted_on_supplier) : '-';
                    dateReceiptDetail.value = result.approvement ? dateReadable(result.date_receipt) : '-';
                    valueDetail.value = `Rp. ${formatRupiah(result.value.toString())}`;
                    valueApprovementDetail.value = result.approvement ? `Rp. ${formatRupiah(result.value_approvement.toString())}` : 'Rp.-';
                    statusDetail.value = capital(result.approvement_description);
                    result.approvement ? statusDetail.classList.add('text-success') : statusDetail.classList.add('text-secondary')
                    if (result.approvement) {
                        statusDetail.classList.add('text-success');
                        statusDetail.classList.remove('text-secondary');
                    } else {
                        statusDetail.classList.remove('text-success');
                        statusDetail.classList.add('text-secondary');
                    }

                    let list = '';
                    result.purchase_return_details.map(detail=>{
                        list += ` 
                        <tr>
                            <td>${detail.product_name}</td>
                            <td class="text-right">${detail.qty}</td>
                            <td class="text-right">${formatRupiah((detail.value / detail.qty).toString())}</td>
                            <td class="text-right">${formatRupiah(detail.value.toString())}</td>
                            <td class="text-right">${result.approvement ? formatRupiah(detail.value_approvement.toString()) : 0}</td>
                        </tr>`;
                    })
                    purchaseReturnDetailList.innerHTML = list;
                })
                .catch(err => {
                    console.log(err);
                })
}


window.addEventListener('load',async function(){
    setDefault();
    await showData();
})