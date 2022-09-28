//state
let search = '';
let balance = 0;

let invoice = {
    outletId : 0,
    invoiceNumber : '',
    accountPayableId: 0,
    supplierName: '',
    supplierId:0,
    value: 0,
    cashierId:0
};

let suppliers = [];

const defineComponentElement = _ =>{
    const app = document.getElementById('app');
    const accountPayablePaymentContainer = document.querySelector('#account-payable-payment-container');
    const accountPayablePaymentListTable = document.querySelector('#account-payable-payment-list-table');
    const invoiceNumber = document.querySelector('#invoice_number');
    const supplierInput = document.querySelector('#supplier-input');
    const supplierList = document.querySelector('#supplier-list');
    const paymentValue = document.querySelector('#payment-value');
    const savePaymentButton = document.querySelector('#save-payment-button');
    const accountPayableDetailListTable = document.querySelector('#account-payable-detail-list-table');
    const printInvoice = document.getElementById('print');
    const countData = document.querySelector('#count-data');
    const searchAccountPayable = document.querySelector('#search-account-payable');

    return {
        accountPayablePaymentContainer,
        accountPayablePaymentListTable,
        invoiceNumber,
        supplierInput,
        supplierList,
        paymentValue,
        savePaymentButton,
        accountPayableDetailListTable,
        printInvoice, app, countData, searchAccountPayable
    }
}

const setDefault = _ => {
    const { 
        accountPayablePaymentContainer,
        accountPayableDetailListTable,
        app, printInvoice
    } = defineComponentElement();

    app.classList.remove('d-none');
    printInvoice.classList.add('d-none');

    accountPayableDetailListTable.innerHTML = '';

    search = '';
    balance = 0;

    invoice = {
        outletId : parseInt(accountPayablePaymentContainer.dataset.outletId),
        invoiceNumber : '',
        accountPayableId: 0,
        supplierName: '',
        supplierId: '',
        value: 0,
        cashierId: 0

    }

    suppliers = [];
}

const showAccountPayableDetail = async () => {
    let url = `/api/account-payable/${id}/detail`;

    await axios.get(url)
                .then(res => {
                    const { accountPayableDetailListTable } = defineComponentElement();

                    let list = '';
                    let details = res.data.data;

                    details.map(detail => {
                        list += `<tr>
                            <td>${detail.ref}</td>
                            <td class="text-right">Rp.${formatRupiah(detail.debit.toString())}</td>
                            <td class="text-center">${dateReadable(detail.date)}</td>
                        </tr>`
                    })

                    accountPayableDetailListTable.innerHTML = list;
                })
                .catch(err => {
                    console.log(err);
                })
}

const newInvoiceNumber = async () => {
    const { 
        invoiceNumber,
    } = defineComponentElement();

    let url = `/api/account-payable-payments/new-invoice-number?outlet_id=${invoice.outletId}`;

    await axios.get(url)
                .then(res=> {
                    invoice.invoiceNumber = res.data.data;
                    invoiceNumber.value = invoice.invoiceNumber;
                })
                .catch(err => {
                    console.log(err);
                })
}

const createPayment = async () => {
    const { 
        supplierInput, paymentValue
    } = defineComponentElement();

    supplierInput.value = invoice.supplierName;
    paymentValue.value = invoice.value;

    await newInvoiceNumber();
}

const validationInputModal = () => {
    const { 
        paymentValue, savePaymentButton
    } = defineComponentElement();

    invoice.supplierName ? paymentValue.removeAttribute('disabled') : paymentValue.setAttribute('disabled', 'disabled');
    invoice.supplierName ? paymentValue.classList.remove('disabled') : paymentValue.classList.add('disabled');

    if (invoice.value > 0 && invoice.cashierId > 0) {
        savePaymentButton.removeAttribute('disabled');
        savePaymentButton.classList.remove('disabled');
    }
    else{
        savePaymentButton.setAttribute('disabled', 'disabled');
        savePaymentButton.classList.add('disabled');
    }
}

const selectSupplier = async (value) => {
    const { 
        supplierInput, paymentValue, accountPayableDetailListTable
    } = defineComponentElement();

    invoice.accountPayableId = parseInt(value.dataset.id);
    invoice.supplierName = value.dataset.name;
    invoice.supplierId = parseInt(value.dataset.supplierId);
    invoice.value = parseInt(value.dataset.balance);
    balance = parseInt(value.dataset.balance);
    supplierInput.value = value.dataset.name;
    paymentValue.value = formatRupiah(value.dataset.balance.toString());

    validationInputModal();

    let url = `/api/account-payable/${value.dataset.id}/detail?is_paid=false`;

    accountPayableDetailListTable.innerHTML = `
                    <tr class="account-payable-payment-table-row">
                        <td colspan="4" class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </td>
                    </tr>`;

    await axios.get(url)
                .then(res=>{
                    let details = res.data.data;
                    let list = '';

                    details.map(detail=> {
                        list += `<tr>
                            <td>${detail.ref}</td>
                            <td class="text-right">Rp.${formatRupiah((detail.debit - detail.credit).toString())}</td>
                            <td class="text-center">${dateReadable(detail.date)}</td>
                        </tr>`
                    });

                    accountPayableDetailListTable.innerHTML = list;
                })
                .catch(err=>{
                    console.log(err);
                })
}

const getSuppliers = async (value) => {
    const { 
        supplierList
    } = defineComponentElement();

    let url = `/api/account-payable?outlet_id=${invoice.outletId}&search=${value}&is_paid=false`;

    await axios.get(url)    
                .then(res=>{
                    suppliers = res.data.data;
                    let list = '';
                    suppliers.map(supplier => {
                        list += `<a class="dropdown-item" href="#" onclick="selectSupplier(this)" data-id="${supplier.id}" data-name="${supplier.supplier_name}" data-supplier-id="${supplier.supplier_id}" data-balance="${supplier.balance}">
                            <div class="row">
                                <div class="col-12" style="font-size: 10px">
                                    Sisa: Rp.${formatRupiah(supplier.balance.toString())}
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    ${supplier.supplier_name}
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

const accountDropdownChange = (value) => {
    value.value = invoice.supplierName ? invoice.supplierName : '';
}

const accountDropdownKeyup = async (value) => {

    await getSuppliers(value.value);
}

const accountDropdownFocus = async (value) => {
    await getSuppliers(invoice.supplierName);
}

const showData = async _ => {
    const { 
        accountPayablePaymentContainer,accountPayablePaymentListTable, countData
    } = defineComponentElement();
    let list = '';
    
    let url = `/api/account-payable-payments?outlet_id=${accountPayablePaymentContainer.dataset.outletId}&search=${search}`;

    accountPayablePaymentListTable.innerHTML = `<tr class="journal-table-row">
                        <td colspan="4" class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </td>
                    </tr>`

    await axios.get(url)
                .then( res =>{
                    let detailPayments = res.data.data;

                    countData.innerHTML = detailPayments.length;
                    

                    if (detailPayments.length>0) {
                        detailPayments.map(detail => {
                            list += `<tr>
                            <td>${dateReadable(detail.date)}</td>
                            <td>${detail.invoice_number}</td>
                            <td>${detail.supplier_name}</td>
                            <td class="text-right">Rp.${formatRupiah(detail.value.toString())}</td>
                            <td></td>
                        </tr>`
                        })
                    } else {
                        accountPayablePaymentListTable.innerHTML = `
                        <tr class="journal-table-row">
                                <td colspan="4" class="text-center">
                                    Tidak Ada Data
                                </td>
                        </tr>`
                    }
                    accountPayablePaymentListTable.innerHTML = list;
                })
                .catch( err =>{
                    console.log(err);
                })
}

const setCurrencyFormat = (value) => {
    value.value = formatRupiah(value.value);

    if (!toPrice(value.value)) {
        value.value = 0;
    }

    invoice.value = toPrice(value.value);

    validationInputModal();
}

const handleSavePayment = async () => {
    await printInvoiceFunc();
}

const printInvoiceFunc = () => {
    const { 
        accountPayablePaymentContainer, printInvoice, app
    } = defineComponentElement();

    app.classList.add('d-none');
    printInvoice.classList.remove('d-none');
    
    let list = '';

    list += `<div class="text-center fw-bold">
                        ${accountPayablePaymentContainer.dataset.outletName}
                    </div>
                    <div class="text-center">
                    ${accountPayablePaymentContainer.dataset.outletAddress}
                    </div>`;

    list += `<table style="font-size: 13px">
                <tbody>
                    <tr>
                        <td>Nomor Nota</td>
                        <td>: ${invoice.invoiceNumber}</td>
                    </tr>
                    <tr>
                        <td>Kepada</td>
                        <td>: ${invoice.supplierName}</td>
                    </tr>
                    <tr>
                        <td>Waktu</td>
                        <td>: ${waktu()}</td>
                    </tr>
                    <tr style="margin-top: 15px;border-top:solid black" class="font-weight-bold">
                        <td>Jumlah Bayar</td>
                        <td>
                            <div class="row">
                                <div class="col-6">
                                :
                                </div>
                                <div class="col-6 text-right">
                                Rp.${formatRupiah(invoice.value.toString())}
                                </div>
                            </div>
                             
                        </td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td>Sisa</td>
                        <td>
                            <div class="row">
                                <div class="col-6">
                                :
                                </div>
                                <div class="col-6 text-right">
                                Rp.${formatRupiah((balance - invoice.value).toString())}
                                </div>
                            </div>
                             
                        </td>
                    </tr>
                </tbody>
            </table>`;

            list += `
            <div class="text-center border-bottom font-italic font-weight-bold">
                Terima Kasih Atas Kunjungan Anda
            </div>`;


    list += `<div class='row justify-content-center'>
        <div class='col-12'>
            <button class="btn btn-sm btn-primary d-print-none w-100" id="print-btn" onclick="printInvoice()">
                cetak
            </button>
        </div>
        <div class='col-12'>
            <button class="btn btn-sm btn-success d-print-none w-100" onclick="saveInvoice()">
                pembayaran baru
            </button>
        </div>
        <div class='col-12'>
            <button id="batal-print" class="btn btn-sm btn-secondary d-print-none w-100" onclick="cancelPrint()" data-toggle="modal" data-target="#createAccountPayablePaymentModal">
                kembali
            </button>
        </div>
    </div>`;

    printInvoice.innerHTML = list;
}

const selectCashier = (value) => {
    invoice.cashierId = parseInt(value.value);
    validationInputModal();
}

const saveInvoice = async () => {
    let url = '/api/account-payable-payments';

    await axios.post(url, invoice)
                .then(async res=>{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Pembayaran Piutang ${res.name} Berhasil Ditambah`,
                        showConfirmButton: false,
                        timer: 1000
                      });
            
                    setDefault();
                    await showData();
                })
                .catch(err=>{
                    console.log(err);
                })
}

const printInvoice = async () => {
    window.print();
    await saveInvoice();
}

const cancelPrint = () => {
    const { 
        printInvoice, app
    } = defineComponentElement();
    
    app.classList.remove('d-none');
    printInvoice.classList.add('d-none');
}

const submitSearchAccountPayablePayment = async (event) => {
    event.preventDefault();
    const { 
        searchAccountPayable
    } = defineComponentElement();
    search = searchAccountPayable.value;
    await showData();
}

window.addEventListener('load',async function(){
    setDefault();
    await showData();
})