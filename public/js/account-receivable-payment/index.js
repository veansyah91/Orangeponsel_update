//state
let search = '';
let balance = 0;

let invoice = {
    outletId : 0,
    invoiceNumber : '',
    accountReceivableId: 0,
    customerName: '',
    customerId:0,
    value: 0,
    cashierId:0
};

let customers = [];

const defineComponentElement = _ =>{
    const app = document.getElementById('app');
    const accountReveivablePaymentContainer = document.querySelector('#account-receivable-payment-container');
    const accountReveivablePaymentListTable = document.querySelector('#account-receivable-payment-list-table');
    const invoiceNumber = document.querySelector('#invoice_number');
    const customerInput = document.querySelector('#customer-input');
    const customerList = document.querySelector('#customer-list');
    const paymentValue = document.querySelector('#payment-value');
    const savePaymentButton = document.querySelector('#save-payment-button');
    const accountReceivableDetailListTable = document.querySelector('#account-receivable-detail-list-table');
    const printInvoice = document.getElementById('print');
    const countData = document.querySelector('#count-data');
    const searchAccountReceivable = document.querySelector('#search-account-receivable');

    return {
        accountReveivablePaymentContainer,
        accountReveivablePaymentListTable,
        invoiceNumber,
        customerInput,
        customerList,
        paymentValue,
        savePaymentButton,
        accountReceivableDetailListTable,
        printInvoice, app, countData, searchAccountReceivable
    }
}

const setDefault = _ => {
    const { 
        accountReveivablePaymentContainer,
        accountReceivableDetailListTable,
        app, printInvoice
    } = defineComponentElement();

    app.classList.remove('d-none');
    printInvoice.classList.add('d-none');

    accountReceivableDetailListTable.innerHTML = '';

    search = '';
    balance = 0;

    invoice = {
        outletId : parseInt(accountReveivablePaymentContainer.dataset.outletId),
        invoiceNumber : '',
        accountReceivableId: 0,
        customerName: '',
        customerId: '',
        value: 0,
        cashierId: 0

    }

    customers = [];
}

const showAccountReceivableDetail = async () => {
    let url = `/api/account-receivable/${id}/detail`;

    await axios.get(url)
                .then(res => {
                    const { accountReceivableDetailListTable } = defineComponentElement();

                    let list = '';
                    let details = res.data.data;

                    details.map(detail => {
                        list += `<tr>
                            <td>${detail.ref}</td>
                            <td class="text-right">Rp.${formatRupiah(detail.debit.toString())}</td>
                            <td class="text-center">${dateReadable(detail.date)}</td>
                        </tr>`
                    })

                    accountReceivableDetailListTable.innerHTML = list;
                })
                .catch(err => {
                    console.log(err);
                })
}

const newInvoiceNumber = async () => {
    const { 
        invoiceNumber,
    } = defineComponentElement();

    let url = `/api/account-receivable-payments/new-invoice-number?outlet_id=${invoice.outletId}`;

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
        customerInput, paymentValue
    } = defineComponentElement();

    customerInput.value = invoice.customerName;
    paymentValue.value = invoice.value;

    await newInvoiceNumber();
}

const validationInputModal = () => {
    const { 
        paymentValue, savePaymentButton
    } = defineComponentElement();

    invoice.customerName ? paymentValue.removeAttribute('disabled') : paymentValue.setAttribute('disabled', 'disabled');
    invoice.customerName ? paymentValue.classList.remove('disabled') : paymentValue.classList.add('disabled');

    if (invoice.value > 0 && invoice.cashierId > 0) {
        savePaymentButton.removeAttribute('disabled');
        savePaymentButton.classList.remove('disabled');
    }
    else{
        savePaymentButton.setAttribute('disabled', 'disabled');
        savePaymentButton.classList.add('disabled');
    }
}

const selectCustomer = async (value) => {
    const { 
        customerInput, paymentValue, accountReceivableDetailListTable
    } = defineComponentElement();

    invoice.accountReceivableId = parseInt(value.dataset.id);
    invoice.customerName = value.dataset.name;
    invoice.customerId = parseInt(value.dataset.customerId);
    invoice.value = parseInt(value.dataset.balance);
    balance = parseInt(value.dataset.balance);
    customerInput.value = value.dataset.name;
    paymentValue.value = formatRupiah(value.dataset.balance.toString());

    validationInputModal();

    let url = `/api/account-receivable/${value.dataset.id}/detail?is_paid=false`;

    accountReceivableDetailListTable.innerHTML = `
                    <tr class="account-receivable-payment-table-row">
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

                    accountReceivableDetailListTable.innerHTML = list;
                })
                .catch(err=>{
                    console.log(err);
                })
}

const getCustomers = async (value) => {
    const { 
        customerList
    } = defineComponentElement();

    let url = `/api/account-receivable?outlet_id=${invoice.outletId}&search=${value}&is_paid=false`;

    await axios.get(url)    
                .then(res=>{
                    customers = res.data.data;
                    let list = '';
                    customers.map(customer => {
                        list += `<a class="dropdown-item" href="#" onclick="selectCustomer(this)" data-id="${customer.id}" data-name="${customer.customer_name}" data-customer-id="${customer.customer_id}" data-balance="${customer.balance}">
                            <div class="row">
                                <div class="col-12" style="font-size: 10px">
                                    Sisa: Rp.${formatRupiah(customer.balance.toString())}
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    ${customer.customer_name}
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

const accountDropdownChange = (value) => {
    value.value = invoice.customerName ? invoice.customerName : '';
}

const accountDropdownKeyup = async (value) => {

    await getCustomers(value.value);
}

const accountDropdownFocus = async (value) => {
    await getCustomers(invoice.customerName);
}

const showData = async _ => {
    const { 
        accountReveivablePaymentContainer,accountReveivablePaymentListTable, countData
    } = defineComponentElement();
    let list = '';
    
    let url = `/api/account-receivable-payments?outlet_id=${accountReveivablePaymentContainer.dataset.outletId}&search=${search}`;

    accountReveivablePaymentListTable.innerHTML = `<tr class="journal-table-row">
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
                            <td>${detail.customer_name}</td>
                            <td class="text-right">Rp.${formatRupiah(detail.value.toString())}</td>
                            <td></td>
                        </tr>`
                        })
                    } else {
                        accountReveivablePaymentListTable.innerHTML = `
                        <tr class="journal-table-row">
                                <td colspan="4" class="text-center">
                                    Tidak Ada Data
                                </td>
                        </tr>`
                    }
                    accountReveivablePaymentListTable.innerHTML = list;
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
        accountReveivablePaymentContainer, printInvoice, app
    } = defineComponentElement();

    app.classList.add('d-none');
    printInvoice.classList.remove('d-none');
    
    let list = '';

    list += `<div class="text-center fw-bold">
                        ${accountReveivablePaymentContainer.dataset.outletName}
                    </div>
                    <div class="text-center">
                    ${accountReveivablePaymentContainer.dataset.outletAddress}
                    </div>`;

    list += `<table style="font-size: 13px">
                <tbody>
                    <tr>
                        <td>Nomor Nota</td>
                        <td>: ${invoice.invoiceNumber}</td>
                    </tr>
                    <tr>
                        <td>Kepada</td>
                        <td>: ${invoice.customerName}</td>
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
            <button id="batal-print" class="btn btn-sm btn-secondary d-print-none w-100" onclick="cancelPrint()" data-toggle="modal" data-target="#createAccountReceivablePaymentModal">
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
    let url = '/api/account-receivable-payments';

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

const submitSearchAccountReceivablePayment = async (event) => {
    event.preventDefault();
    const { 
        searchAccountReceivable
    } = defineComponentElement();
    search = searchAccountReceivable.value;
    await showData();
}

window.addEventListener('load',async function(){
    setDefault();
    await showData();
})