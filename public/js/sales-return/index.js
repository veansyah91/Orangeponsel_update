//state
let salesReturn = {
    outletId: 0,
    invoiceNumber: '',
    customerName: '',
    customerId: '',
    products:[],
    grandTotal: 0,
    cashierId: 0
}

let search = '';

let is_validated = false;

let setUpInput = false;

let isEdit = false;

let products = [];

let rowInput = 1;

const defineComponentElement = _ => {
    const countData = document.querySelector('#count-data');

    const searchSalesReturn = document.querySelector('#search-sales-return');

    const salesReturnContainer = document.querySelector('#sales-return-container');
    const salesReturnListTable = document.querySelector('#sales-return-list-table');
    const salesReturnInputList = document.querySelector('#sales-return-input-list');
    const invoiceNumber = document.querySelector('#invoice_number');
    const customerList = document.querySelector('#customer-list');
    const customerInput = document.querySelector('#customer-input');
    const submitCreate = document.querySelector('#submit-create');
    const submitEdit = document.querySelector('#submit-edit');
    const selectCash = document.querySelector('#select-cash');

    const createSalesReturnModalLabel = document.querySelector('#createSalesReturnModalLabel');

    const salesReturnProducts = document.getElementsByClassName('sales-return-product');
    const salesReturnQtys = document.getElementsByClassName('sales-return-qty');
    const salesReturnPrices = document.getElementsByClassName('sales-return-price');
    const salesReturnTotalPrices = document.getElementsByClassName('sales-return-total-price');
    const salesReturnRows = document.getElementsByClassName('sales-return-row');
    const removeRowBtn = document.getElementsByClassName('remove-row-btn');
    const productList = document.getElementsByClassName('product-list');

    const salesReturDetailList = document.querySelector('#sales-return-detail-list');
    const invoiceNumberDetail = document.querySelector('#invoice-number-detail');
    const customerNameDetail = document.querySelector('#customer-name-detail');
    const dateDetail = document.querySelector('#date-detail');
    const valueDetail = document.querySelector('#value-detail');

    const grandTotal = document.querySelector('#grand-total');


    return {
        searchSalesReturn,
        countData, salesReturnListTable, 
        salesReturnContainer,
        invoiceNumber, customerList, customerInput, 
        submitCreate,salesReturnInputList,
        salesReturnProducts, salesReturnQtys, salesReturnPrices, salesReturnTotalPrices, salesReturnRows, productList, grandTotal,removeRowBtn, selectCash, salesReturDetailList,  invoiceNumberDetail, customerNameDetail, dateDetail, valueDetail, submitEdit, createSalesReturnModalLabel
    }
}

const setDefault = _ => {
    const { salesReturnContainer, customerInput, submitCreate,selectCash, grandTotal,  } = defineComponentElement();

    isEdit = false;
    rowInput = 1;

    customerInput.value = '';
    submitCreate.classList.add('d-none');
    submitCreate.setAttribute('disabled', 'disabled');
    selectCash.value = 0;
    grandTotal.innerHTML = 'Rp.-';

    search = '';

    salesReturn = {
        outletId: parseInt(salesReturnContainer.dataset.outletId),
        invoiceNumber: '',
        customerName:'',
        customerId:'',
        products:[
                    {
                        name:'',
                        qty:0,
                        value:0
                    },
                ],
        grandTotal: 0,
        cashierId: 0
    }
}

const showData  = async _ => {
    const { salesReturnListTable, countData } = defineComponentElement();

    salesReturnListTable.innerHTML = `<tr class="journal-table-row">
                    <td colspan="5" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </td>
                </tr>`;

    let url = `/api/sales-return?outlet_id=${salesReturn.outletId}&search=${search}`;

    await axios.get(url)
                .then((result) => {  
                    let salesReturns = result.data.data;

                    countData.innerHTML = salesReturns.length

                    if (salesReturns.length > 0) {
                        let list = '';
                        salesReturns.map(salesReturn => {
                            list += `
                            <tr>
                                <td>${dateReadable(salesReturn.date)}</td>
                                <td>${salesReturn.invoice_number}</td>
                                <td>${salesReturn.customer_name}</td>
                                <td class="text-right">Rp.${formatRupiah(salesReturn.value.toString())}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <button class="dropdown-item edit-accounts" data-id="${salesReturn.id}" data-toggle="modal" data-target="#detailSalesReturnModal" onclick="handleDetailSalesReturn(${salesReturn.id})">
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
                                            <button class="dropdown-item edit-accounts" data-id="${salesReturn.id}" data-toggle="modal" data-target="#createSalesReturnModal" onclick="editData(${salesReturn.id})">
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
                                            <button class="dropdown-item " data-id="${salesReturn.id}"data-toggle="modal" onclick="deleteSalesReturn(this)">
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

                        salesReturnListTable.innerHTML = list;
                    } else {
                        salesReturnListTable.innerHTML = `<tr class="journal-table-row">
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
        salesReturnProducts,
        salesReturnQtys,
        salesReturnPrices,
        salesReturnTotalPrices,
        customerInput,
        submitCreate, submitEdit, selectCash,
    
    } = defineComponentElement();

    is_validated = true;

    for (let index = 0; index < rowInput; index++) {
        if (!salesReturnProducts[index].value || salesReturnQtys[index].value < 1 || salesReturnPrices[index].value < 1 || salesReturnTotalPrices[index].value < 1 || !customerInput.value || selectCash.value < 1) {
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
    salesReturn = {...salesReturn, cashierId: parseInt(value.value)}

}

const getNewInvoice = async () => {
    const { salesReturnContainer, invoiceNumber } = defineComponentElement();

    let url = `/api/sales-return/new-invoice-number?outlet_id=${salesReturnContainer.dataset.outletId}`;

    await axios.get(url)
                .then(res => {
                    let newInvoice = res.data.data;
                    salesReturn.invoiceNumber = newInvoice;
                    invoiceNumber.value = newInvoice;

                    salesReturn = {...salesReturn, invoiceNumber:  newInvoice};
                })
                .catch(err => {
                    console.log(err);
                })
}

const inputListPerColumn = (index) => {
    return `<td style="width: 40%">
        <div class="dropdown w-100">
            <input 
                class="form-control sales-return-product" 
                type="text" 
                data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Masukkan Tipe / IMEI"
                autocomplete="off"
                onkeyup="productDropdownKeyup(this)"
                onclick="productDropdownFocus(this)"
                onchange="productDropdownChange(this)"
                data-order="${index}"
                value="${salesReturn.products[index].name}"
            >

            <div class="dropdown-menu w-100 overflow-auto product-list" style="max-height:180px">
                
            </div>
        </div>
    </td>
    <td>
        <input type="text" class="form-control sales-return-qty text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" onchange="handleSalesReturnQty(this)" autocomplete="off" value="${salesReturn.products[index].qty}">
    </td>
    <td>
        <input type="text" class="form-control sales-return-price text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off" value="${formatRupiah((salesReturn.products[index].value / salesReturn.products[index].qty).toString())}" onchange="handleSalesReturnPrice(this)">
    </td>
    <td>
        <input type="text" class="form-control sales-return-total-price text-right" data-order="${index}" inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off" value="${formatRupiah((salesReturn.products[index].value).toString())}" readonly>
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
    const { salesReturnInputList, customerInput, submitCreate } = defineComponentElement();

    if (!setUpInput) {
        setDefault();
        submitCreate.classList.add('d-none');
        submitCreate.setAttribute('disabled', 'disabled');

        let list = ``;
        for (let index = 0; index < rowInput; index++) {
            list += `<tr class="sales-return-row">
                ${inputListPerColumn(index)}
            </tr>`;
        }
        salesReturnInputList.innerHTML = list;
    }
    
}

const handleAddRowInputList = () =>{
    const { salesReturnInputList } = defineComponentElement();

    salesReturn.products = 
        [
            ...salesReturn.products, 
            {
                name:'',
                qty:0,
                value:0
            }
        ];

    // create a row node:
    const row = document.createElement('tr');
    row.className = 'sales-return-row';
    row.innerHTML = inputListPerColumn(rowInput)

    salesReturnInputList.appendChild(row);

    rowInput++;
    validationInput();
}

const addData = async _ => {
    const { createSalesReturnModalLabel } = defineComponentElement();
    createSalesReturnModalLabel.innerHTML = 'Tambah Data Retur Penjualan'
        
    setDefaultInputRow();
    await getNewInvoice();
}

const editData = async (id) => {
    const { invoiceNumber, customerInput, grandTotal, selectCash, salesReturnContainer,salesReturnInputList, submitCreate, submitEdit, createSalesReturnModalLabel} = defineComponentElement();

    createSalesReturnModalLabel.innerHTML = 'Ubah Data Retur Penjualan';

    submitCreate.classList.add('d-none');
    submitEdit.classList.remove('d-none');

    submitCreate.setAttribute('disabled', 'disabled');
    submitEdit.removeAttribute('disabled', 'disabled');
    isEdit = true;

    let url = `/api/sales-return-detail?sales_return_id=${id}`;

    await axios.get(url)
                .then((result) => {
                    salesReturn = {
                        outletId: parseInt(salesReturnContainer.dataset.outletId),
                        invoiceNumber: '',
                        customerName:'',
                        customerId:'',
                        products:[],
                        grandTotal: 0,
                        cashierId: 0
                    }
                    salesReturn = {
                                    ...salesReturn, 
                                    invoiceNumber: result.data.data.salesReturn.invoice_number,
                                    customerName: result.data.data.salesReturn.customer_name,
                                    customerId: result.data.data.salesReturn.customer_id,
                                    cashierId: result.data.data.salesReturn.cashier_id,
                                    grandTotal: result.data.data.salesReturn.value,
                                    cashierId: result.data.data.salesReturn.cashier_id,
                                  }

                    result.data.data.salesReturnDetail.map(detail => {
                        salesReturn.products = [
                            ...salesReturn.products,
                            {
                                name:detail.product_name,
                                qty:detail.qty,
                                value:detail.value
                            }
                        ]
                    });

                    

                    
                    let list = '';
                    salesReturn.products.map((_, index) => {
                        list += `<tr class="sales-return-row">
                            ${inputListPerColumn(index)}
                        </tr>`
                    })
                    
                    invoiceNumber.value = salesReturn.invoiceNumber;
                    customerInput.value = salesReturn.customerName;
                    selectCash.value = salesReturn.cashierId;
                    grandTotal.innerHTML = `Rp.${formatRupiah(salesReturn.grandTotal.toString())}`;

                    salesReturnInputList.innerHTML = list;

                    rowInput = salesReturn.products.length;

                }).catch((err) => {
                    console.log(err);
                });
}

const selectCustomer = (value) => {
    const { 
        customerInput
    } = defineComponentElement();


    salesReturn = {...salesReturn, customerName: value.dataset.name, customerId: parseInt(value.dataset.id)};

    customerInput.value = salesReturn.customerName;

    validationInput();
}

const getCustomers = async (value) => {
    const { 
        customerList
    } = defineComponentElement();

    let url = `/api/pelanggan/get-pelanggan?pelanggan=${value}`;
    

    await axios.get(url)    
                .then(res=>{
                    customers = res.data.data;

                    let list = '';
                    customers.map(customer => {
                        list += `<a class="dropdown-item" href="#" onclick="selectCustomer(this)" data-id="${customer.id}" data-name="${customer.nama}" data-customer-id="${customer.customer_id}"">
                            <div class="row">
                                <div class="col-12">
                                    ${customer.nama}
                                </div>
                            </div>
                                
                            </a>`;
                    })
                    customerList.innerHTML = list

                })
                .catch(err=>{
                    console.log(err);
                })
}

const customerDropdownChange = (value) => {
    value.value = salesReturn.customerName ? salesReturn.customerName : '';
}

const customerDropdownKeyup = async (value) => {
    
    await getCustomers(value.value);
}

const customerDropdownFocus = async (value) => {
    await getCustomers(salesReturn.customerName);
}

const removeRowBtnFunc = (value) => {

    const { 
        salesReturnProducts,
        salesReturnQtys,
        salesReturnPrices,
        salesReturnTotalPrices,
        removeRowBtn,
        salesReturnRows, grandTotal
    
    } = defineComponentElement();

    salesReturn.grandTotal -= salesReturn.products[parseInt(value.dataset.order)].qty * salesReturn.products[parseInt(value.dataset.order)].value;

    salesReturnRows[parseInt(value.dataset.order)].remove();
    salesReturn.products.splice(parseInt(value.dataset.order),1);
   
    rowInput--;

    for (let index = 0; index < rowInput; index++) {
        salesReturnProducts[index].setAttribute('data-order', index);
        salesReturnQtys[index].setAttribute('data-order', index);
        salesReturnPrices[index].setAttribute('data-order', index);
        salesReturnTotalPrices[index].setAttribute('data-order', index);
        removeRowBtn[index].setAttribute('data-order', index);
    } 

    grandTotal.innerHTML = `Rp.${formatRupiah(salesReturn.grandTotal.toString())}`;

    validationInput();
}

const productDropdownChange = (value) => {
    salesReturn.products[value.dataset.order].name = value.value;
    defaultQty(value.dataset.order);
}

const defaultQty = (order) => {
    const { salesReturnQtys } = defineComponentElement();

    salesReturn.products[order].qty = 1;
    salesReturnQtys[order].value = 1;
}


const handleSelectProduct = (value) => {
    const { salesReturnProducts } = defineComponentElement();

    salesReturnProducts[value.dataset.order].value = value.dataset.tipe;

    salesReturn.products[value.dataset.order].name = value.dataset.tipe;
    
    defaultQty(value.dataset.order);
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
                            list += `<a class="dropdown-item" href="#" data-id="${product.id}" data-tipe="${product.tipe}" data-order="${value.dataset.order}" onclick="handleSelectProduct(this)">
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

const handleSalesReturnQty = (value) => {
    const { salesReturnTotalPrices, salesReturnPrices, grandTotal } = defineComponentElement();

    let total = toPrice(value.value) * toPrice(salesReturnPrices[value.dataset.order].value);

    salesReturnTotalPrices[value.dataset.order].value = `${formatRupiah(total.toString())}`;

    salesReturn.products[value.dataset.order].qty = toPrice(value.value);
    salesReturn.products[value.dataset.order].value = total;

    let tempTotal = 0;
    for (let index = 0; index < rowInput; index++) {
        tempTotal += parseInt(toPrice(salesReturnTotalPrices[index].value));
    }

    salesReturn.grandTotal = tempTotal;

    grandTotal.innerHTML = `Rp.${formatRupiah(salesReturn.grandTotal.toString())}`;

    validationInput();
}

const handleSalesReturnPrice = (value) => {
    const { salesReturnTotalPrices, salesReturnQtys, grandTotal } = defineComponentElement();

    let total = toPrice(value.value) * toPrice(salesReturnQtys[value.dataset.order].value);

    salesReturnTotalPrices[value.dataset.order].value = `${formatRupiah(total.toString())}`;

    salesReturn.products[value.dataset.order].value = total;

    let tempTotal = 0;
    for (let index = 0; index < rowInput; index++) {
        tempTotal += parseInt(toPrice(salesReturnTotalPrices[index].value))
    }

    salesReturn.grandTotal = tempTotal;

    grandTotal.innerHTML = `Rp.${formatRupiah(salesReturn.grandTotal.toString())}`;

    validationInput();
}

const handlesSubmitCreate = async () => {
    const url = `/api/sales-return/`;

    await axios.post(url, salesReturn)
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

const handlesSubmitEdit = async () => {
    let url = `/api/sales-return`;


    await axios.put(url, salesReturn)
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

const handleDetailSalesReturn = async (id) => {
    const { salesReturDetailList, invoiceNumberDetail, customerNameDetail, dateDetail, valueDetail } = defineComponentElement();
    
    let url = `/api/sales-return-detail?sales_return_id=${id}`;

    await axios.get(url)
                .then((res) => {
                    let details = res.data.data;
                    let list = '';

                    invoiceNumberDetail.innerHTML = details.salesReturn.invoice_number;
                    customerNameDetail.innerHTML = details.salesReturn.customer_name;
                    dateDetail.innerHTML = details.salesReturn.date;
                    valueDetail.innerHTML = `Rp.${formatRupiah(details.salesReturn.value.toString())}`;


                    details.salesReturnDetail.map(detail=>{
                        list += `
                        <tr>
                            <td>${detail.product_name}</td>
                            <td class="text-right">${detail.qty}</td>
                            <td class="text-right">${formatRupiah((parseInt(detail.value) / parseInt(detail.qty)).toString())}</td>
                            <td class="text-right">${formatRupiah(detail.value.toString())}</td>
                        </tr>`;

                       
                    })
                    salesReturDetailList.innerHTML = list;

                }).catch((err) => {
                    console.log(err);
                });
}

const deleteSalesReturn = (value) => {
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
            axios.delete(`/api/sales-return/${value.dataset.id}`)
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

const submitSearchSalesReturn = async (event) => {
    event.preventDefault(); 
    const { searchSalesReturn } = defineComponentElement();

    search = searchSalesReturn.value;
    await showData();
}


window.addEventListener('load',async function(){
    setDefault();
    await showData();
})