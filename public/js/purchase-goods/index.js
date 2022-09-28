//state
let purchaseGoodsId = 0;
let purchaseGoods = {
    outletId: 0,
    invoiceNumber: '',
    supplierName: '',
    supplierId: '',
    products:[],
    grandTotal: 0,
    cashierId: -1
}

let search = '';

let is_validated = false;

let setUpInput = false;

let isEdit = false;

let products = [];

let rowInput = 1;

const defineComponentElement = _ => {
    const countData = document.querySelector('#count-data');

    const searchPurchaseGoods = document.querySelector('#search-purchase-goods');

    const purchaseGoodsContainer = document.querySelector('#purchase-goods-container');
    const purchaseGoodsListTable = document.querySelector('#purchase-goods-list-table');
    const purchaseGoodsInputList = document.querySelector('#purchase-goods-input-list');
    const invoiceNumber = document.querySelector('#invoice_number');
    const supplierList = document.querySelector('#supplier-list');
    const supplierInput = document.querySelector('#supplier-input');
    const submitCreate = document.querySelector('#submit-create');
    const submitEdit = document.querySelector('#submit-edit');
    const selectCash = document.querySelector('#select-cash');

    const createPurchaseGoodsModalLabel = document.querySelector('#createPurchaseGoodsModalLabel');

    const purchaseGoodsProducts = document.getElementsByClassName('purchase-goods-product');
    const purchaseGoodsQtys = document.getElementsByClassName('purchase-goods-qty');
    const purchaseGoodsPrices = document.getElementsByClassName('purchase-goods-price');
    const purchaseGoodsTotalPrices = document.getElementsByClassName('purchase-goods-total-price');
    const purchaseGoodsRows = document.getElementsByClassName('purchase-goods-row');
    const removeRowBtn = document.getElementsByClassName('remove-row-btn');
    const productList = document.getElementsByClassName('product-list');

    const purchaseGoodsDetailList = document.querySelector('#purchase-goods-detail-list');
    const invoiceNumberDetail = document.querySelector('#invoice-number-detail');
    const supplierNameDetail = document.querySelector('#supplier-name-detail');
    const dateDetail = document.querySelector('#date-detail');
    const valueDetail = document.querySelector('#value-detail');

    const grandTotal = document.querySelector('#grand-total');


    return {
        searchPurchaseGoods,
        countData, purchaseGoodsListTable, 
        purchaseGoodsContainer,
        invoiceNumber, supplierList, supplierInput, 
        submitCreate,purchaseGoodsInputList,
        purchaseGoodsProducts, purchaseGoodsQtys, purchaseGoodsPrices, purchaseGoodsTotalPrices, purchaseGoodsRows, productList, grandTotal,removeRowBtn, selectCash, purchaseGoodsDetailList,  invoiceNumberDetail, supplierNameDetail, dateDetail, valueDetail, submitEdit, createPurchaseGoodsModalLabel
    }
}

const setDefault = _ => {
    const { purchaseGoodsContainer, supplierInput, submitCreate,selectCash, grandTotal,  } = defineComponentElement();

    isEdit = false;
    rowInput = 1;

    supplierInput.value = '';
    submitCreate.classList.add('d-none');
    submitCreate.setAttribute('disabled', 'disabled');
    selectCash.value = -1;
    grandTotal.innerHTML = 'Rp.-';

    search = '';

    purchaseGoods = {
        outletId: parseInt(purchaseGoodsContainer.dataset.outletId),
        invoiceNumber: '',
        supplierName:'',
        supplierId:'',
        products:[
                    {
                        id:0,
                        name:'',
                        qty:0,
                        value:0
                    },
                ],
        grandTotal: 0,
        cashierId: -1
    }
}

const showData  = async _ => {
    const { purchaseGoodsListTable, countData } = defineComponentElement();

    purchaseGoodsListTable.innerHTML = `<tr class="journal-table-row">
                    <td colspan="5" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </td>
                </tr>`;

    let url = `/api/purchase-goods?outlet_id=${purchaseGoods.outletId}&search=${search}`;

    await axios.get(url)
                .then((result) => {  
                    let purchaseGoodsResult = result.data.data;

                    countData.innerHTML = purchaseGoodsResult.length

                    if (purchaseGoodsResult.length > 0) {
                        let list = '';
                        purchaseGoodsResult.map(purchaseGoods => {
                            list += `
                            <tr>
                                <td>${dateReadable(purchaseGoods.date)}</td>
                                <td>${purchaseGoods.invoice_number}</td>
                                <td>${purchaseGoods.supplier_name}</td>
                                <td class="text-right">Rp.${formatRupiah(purchaseGoods.value.toString())}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <button class="dropdown-item edit-accounts" data-id="${purchaseGoods.id}" data-toggle="modal" data-target="#detailPurchaseGoodsModal" onclick="handleDetailPurchaseGoods(${purchaseGoods.id})">
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
                                            <button class="dropdown-item edit-accounts" data-id="${purchaseGoods.id}" data-toggle="modal" data-target="#createPurchaseGoodsModal" onclick="editData(${purchaseGoods.id})">
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
                                            <button class="dropdown-item " data-id="${purchaseGoods.id}"data-toggle="modal" onclick="deletePurchaseGoods(this)">
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

                        purchaseGoodsListTable.innerHTML = list;
                    } else {
                        purchaseGoodsListTable.innerHTML = `<tr class="journal-table-row">
                                    <td colspan="5" class="text-center font-italic">
                                        Tidak Ada Data Pembelian Barang Dagang
                                    </td>
                                </tr>`
                    }


                }).catch((err) => {
                    console.log(err);
                });
}

const validationInput = _ => {
    const { 
        purchaseGoodsProducts,
        purchaseGoodsQtys,
        purchaseGoodsPrices,
        purchaseGoodsTotalPrices,
        supplierInput,
        submitCreate, submitEdit, selectCash,invoiceNumber
    
    } = defineComponentElement();

    is_validated = true;

    for (let index = 0; index < rowInput; index++) {
        if ( !invoiceNumber.value || !purchaseGoodsProducts[index].value || purchaseGoodsQtys[index].value < 1 || purchaseGoodsPrices[index].value < 1 || purchaseGoodsTotalPrices[index].value < 1 || !supplierInput.value || selectCash.value < 0) {
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

const selectCashier = (value) => {
    validationInput();
    purchaseGoods = {...purchaseGoods, cashierId: parseInt(value.value)}

}

const getNewInvoice = async () => {
    const { purchaseGoodsContainer, invoiceNumber } = defineComponentElement();

    let url = `/api/purchase-goods/new-invoice-number?outlet_id=${purchaseGoodsContainer.dataset.outletId}`;

    await axios.get(url)
                .then(res => {
                    let newInvoice = res.data.data;
                    
                    invoiceNumber.value = newInvoice;

                    purchaseGoods = {...purchaseGoods, invoiceNumber:  newInvoice};
                })
                .catch(err => {
                    console.log(err);
                })
}

const inputListPerColumn = (index) => {
    return `<td style="width: 40%">
        <div class="dropdown w-100">
            <input 
                class="form-control purchase-goods-product" 
                type="text" 
                data-toggle="dropdown" aria-expanded="false"  data-reference="parent"  placeholder="Masukkan Tipe / IMEI"
                autocomplete="off"
                onkeyup="productDropdownKeyup(this)"
                onclick="productDropdownFocus(this)"
                onchange="productDropdownChange(this)"
                data-order="${index}"
                value="${purchaseGoods.products[index].name}"
            >

            <div class="dropdown-menu w-100 overflow-auto product-list" style="max-height:180px">
                
            </div>
        </div>
    </td>
    <td>
        <input type="text" class="form-control purchase-goods-qty text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" onchange="handlePurchaseGoodsQty(this)" autocomplete="off" value="${purchaseGoods.products[index].qty}" onclick="this.select()">
    </td>
    <td>
        <input type="text" class="form-control purchase-goods-price text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off" value="${formatRupiah((purchaseGoods.products[index].value / purchaseGoods.products[index].qty).toString())}" onchange="handlePurchaseGoodsPrice(this)" onclick="this.select()">
    </td>
    <td>
        <input type="text" class="form-control purchase-goods-total-price text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off" value="${formatRupiah((purchaseGoods.products[index].value).toString())}" readonly>
    </td>
    <td>
        <button class="btn btn-danger btn-sm remove-row-btn my-auto" data-order="${index}" onclick="removeRowBtnFunc(this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
            </svg>
        </button>
    </td>`
}

const handleInvoiceNumberChange = (value) => {
    purchaseGoods = {...purchaseGoods, invoiceNumber:  value.value};
    validationInput();
}

const setDefaultInputRow = () => {
    const { purchaseGoodsInputList, supplierInput, submitCreate } = defineComponentElement();

    

    if (!setUpInput) {
        setDefault();
        submitCreate.classList.add('d-none');
        submitCreate.setAttribute('disabled', 'disabled');

        let list = ``;
        for (let index = 0; index < rowInput; index++) {
            list += `<tr class="purchase-goods-row">
                ${inputListPerColumn(index)}
            </tr>`;
        }
        purchaseGoodsInputList.innerHTML = list;
    }
    
}

const handleAddRowInputList = () =>{
    const { purchaseGoodsInputList } = defineComponentElement();

    purchaseGoods.products = 
        [
            ...purchaseGoods.products, 
            {
                name:'',
                qty:0,
                value:0,
                id: 0
            }
        ];

    // create a row node:
    const row = document.createElement('tr');
    row.className = 'purchase-goods-row';
    row.innerHTML = inputListPerColumn(rowInput)

    purchaseGoodsInputList.appendChild(row);

    rowInput++;
    validationInput();
}

const addData = async _ => {
    const { createPurchaseGoodsModalLabel } = defineComponentElement();
    createPurchaseGoodsModalLabel.innerHTML = 'Tambah Data Pembelian Barang Dagang';

    setDefaultInputRow();
    await getNewInvoice();
}

const editData = async (id) => {
    const { invoiceNumber, supplierInput, grandTotal, selectCash, purchaseGoodsContainer, purchaseGoodsInputList, submitCreate, submitEdit, createPurchaseGoodsModalLabel} = defineComponentElement();

    createPurchaseGoodsModalLabel.innerHTML = 'Ubah Data Pembelian Barang Dagang';

    purchaseGoodsId = id;

    submitCreate.classList.add('d-none');
    submitEdit.classList.remove('d-none');

    submitCreate.setAttribute('disabled', 'disabled');
    submitEdit.removeAttribute('disabled', 'disabled');
    isEdit = true;

    let url = `/api/purchase-goods-detail/${id}`;

    await axios.get(url)
                .then((result) => {
                    purchaseGoods = {
                        outletId: parseInt(purchaseGoodsContainer.dataset.outletId),
                        invoiceNumber: '',
                        supplierName:'',
                        supplierId:'',
                        products:[],
                        grandTotal: 0,
                        cashierId: 0
                    }
                    purchaseGoods = {
                                    ...purchaseGoods, 
                                    invoiceNumber: result.data.data.purchaseGoods.invoice_number,
                                    supplierName: result.data.data.purchaseGoods.supplier_name,
                                    supplierId: result.data.data.purchaseGoods.supplier_id,
                                    cashierId: result.data.data.purchaseGoods.cashier_id,
                                    grandTotal: result.data.data.purchaseGoods.value,
                                    cashierId: result.data.data.purchaseGoods.cashier_id,
                                  }

                    result.data.data.purchaseGoodsDetail.map(detail => {
                        purchaseGoods.products = [
                            ...purchaseGoods.products,
                            {
                                id:detail.product_id,
                                name:detail.product_name,
                                qty:detail.qty,
                                value:detail.value
                            }
                        ]
                    });

                    
                    let list = '';
                    purchaseGoods.products.map((_, index) => {
                        list += `<tr class="purchase-goods-row">
                            ${inputListPerColumn(index)}
                        </tr>`
                    })
                    
                    invoiceNumber.value = purchaseGoods.invoiceNumber;
                    supplierInput.value = purchaseGoods.supplierName;
                    selectCash.value = purchaseGoods.cashierId ? purchaseGoods.cashierId : 0;
                    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseGoods.grandTotal.toString())}`;

                    purchaseGoodsInputList.innerHTML = list;

                    rowInput = purchaseGoods.products.length;

                }).catch((err) => {
                    console.log(err);
                });
}

const selectsupplier = (value) => {
    const { 
        supplierInput
    } = defineComponentElement();


    purchaseGoods = {...purchaseGoods, supplierName: value.dataset.name, supplierId: parseInt(value.dataset.id)};

    supplierInput.value = purchaseGoods.supplierName;

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
    value.value = purchaseGoods.supplierName ? purchaseGoods.supplierName : '';
}

const supplierDropdownKeyup = async (value) => {
    
    await getsuppliers(value.value);
}

const supplierDropdownFocus = async (value) => {
    await getsuppliers(purchaseGoods.supplierName);
}

const removeRowBtnFunc = (value) => {

    const { 
        purchaseGoodsProducts,
        purchaseGoodsQtys,
        purchaseGoodsPrices,
        purchaseGoodsTotalPrices,
        removeRowBtn,
        purchaseGoodsRows, grandTotal
    
    } = defineComponentElement();

    purchaseGoods.grandTotal -= purchaseGoods.products[parseInt(value.dataset.order)].qty * purchaseGoods.products[parseInt(value.dataset.order)].value;

    purchaseGoodsRows[parseInt(value.dataset.order)].remove();
    purchaseGoods.products.splice(parseInt(value.dataset.order),1);
    
    rowInput--;

    for (let index = 0; index < rowInput; index++) {
        purchaseGoodsProducts[index].setAttribute('data-order', index);
        purchaseGoodsQtys[index].setAttribute('data-order', index);
        purchaseGoodsPrices[index].setAttribute('data-order', index);
        purchaseGoodsTotalPrices[index].setAttribute('data-order', index);
        removeRowBtn[index].setAttribute('data-order', index);
    } 

    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseGoods.grandTotal.toString())}`;

    validationInput();
}

const productDropdownChange = (value) => {
    value.value = purchaseGoods.products[value.dataset.order].name;
    defaultQty(value.dataset.order);
}

const defaultQty = (order) => {
    const { purchaseGoodsQtys } = defineComponentElement();

    purchaseGoods.products[order].qty = 1;
    purchaseGoodsQtys[order].value = 1;
}


const handleSelectProduct = (value) => {
    const { purchaseGoodsProducts, purchaseGoodsPrices,purchaseGoodsTotalPrices, grandTotal } = defineComponentElement();

    purchaseGoodsProducts[value.dataset.order].value = value.dataset.tipe;
    purchaseGoodsPrices[value.dataset.order].value = `${formatRupiah(value.dataset.unitPrice)}`;
    purchaseGoodsTotalPrices[value.dataset.order].value = `${formatRupiah(value.dataset.unitPrice)}`;
    
    purchaseGoods.products[value.dataset.order].id = parseInt(value.dataset.id);
    purchaseGoods.products[value.dataset.order].name = value.dataset.tipe;
    purchaseGoods.products[value.dataset.order].value = parseInt(value.dataset.unitPrice);

    defaultQty(value.dataset.order);

    let tempTotal = 0;
    for (let index = 0; index < rowInput; index++) {
        tempTotal += purchaseGoods.products[index].value ;
    }

    purchaseGoods.grandTotal = parseInt(tempTotal);
    grandTotal.innerHTML = `Rp.${formatRupiah(tempTotal.toString())}`;
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

const handlePurchaseGoodsQty = (value) => {
    const { purchaseGoodsTotalPrices, purchaseGoodsPrices, grandTotal } = defineComponentElement();

    let total = toPrice(value.value) * toPrice(purchaseGoodsPrices[value.dataset.order].value);

    purchaseGoodsTotalPrices[value.dataset.order].value = `${formatRupiah(total.toString())}`;

    purchaseGoods.products[value.dataset.order].value = total;
    purchaseGoods.products[value.dataset.order].qty = parseInt(value.value);

    let tempTotal = 0;
    for (let index = 0; index < rowInput; index++) {
        tempTotal += parseInt(toPrice(purchaseGoodsTotalPrices[index].value));
    }

    purchaseGoods.grandTotal = tempTotal;

    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseGoods.grandTotal.toString())}`;

    validationInput();
}

const handlePurchaseGoodsPrice = (value) => {
    const { purchaseGoodsTotalPrices, purchaseGoodsQtys, grandTotal } = defineComponentElement();

    let total = toPrice(value.value) * toPrice(purchaseGoodsQtys[value.dataset.order].value);

    purchaseGoodsTotalPrices[value.dataset.order].value = `${formatRupiah(total.toString())}`;

    purchaseGoods.products[value.dataset.order].value = total;

    let tempTotal = 0;
    for (let index = 0; index < rowInput; index++) {
        tempTotal += parseInt(toPrice(purchaseGoodsTotalPrices[index].value))
    }

    purchaseGoods.grandTotal = tempTotal;

    grandTotal.innerHTML = `Rp.${formatRupiah(purchaseGoods.grandTotal.toString())}`;

    validationInput();
}

const handleSubmitEdit = async () => {
    let url = `/api/purchase-goods/${purchaseGoodsId}`;
    await axios.put(url, purchaseGoods)
                .then(async res => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Pembalian Barang Dagang Berhasil Diubah`,
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

const handleSubmitCreate = async () => {
    const url = `/api/purchase-goods`;
    await axios.post(url, purchaseGoods)
                .then(async res => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Pembalian Barang Dagang Berhasil Ditambahkan`,
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


const handleDetailPurchaseGoods = async (id) => {
    const { purchaseGoodsDetailList, invoiceNumberDetail, supplierNameDetail, dateDetail, valueDetail } = defineComponentElement();
    
    let url = `/api/purchase-goods-detail/${id}`;

    await axios.get(url)
                .then((res) => {
                    let details = res.data.data;
                    let list = '';

                    invoiceNumberDetail.innerHTML = details.purchaseGoods.invoice_number;
                    supplierNameDetail.innerHTML = details.purchaseGoods.supplier_name;
                    dateDetail.innerHTML = dateReadable(details.purchaseGoods.date);
                    valueDetail.innerHTML = `Rp.${formatRupiah(details.purchaseGoods.value.toString())}`;


                    details.purchaseGoodsDetail.map(detail=>{
                        list += `
                        <tr>
                            <td>${detail.product.kode}</td>
                            <td>${detail.product_name}</td>
                            <td class="text-right">${detail.qty}</td>
                            <td class="text-right">${formatRupiah((parseInt(detail.value) / parseInt(detail.qty)).toString())}</td>
                            <td class="text-right">${formatRupiah(detail.value.toString())}</td>
                        </tr>`;

                       
                    })
                    purchaseGoodsDetailList.innerHTML = list;

                }).catch((err) => {
                    console.log(err);
                });
}

const deletePurchaseGoods = (value) => {
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
            axios.delete(`/api/purchase-goods/${value.dataset.id}`)
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

const submitSearchPurchaseGoods = async (event) => {
    event.preventDefault(); 
    const { searchPurchaseGoods } = defineComponentElement();

    search = searchPurchaseGoods.value;
    await showData();
}


window.addEventListener('load',async function(){
    setDefault();
    await showData();
})