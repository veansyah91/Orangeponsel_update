let isUpdate = false;
const outlet = document.querySelector('#outlet');
const pelanggan = document.getElementById('pelanggan');
const server = document.querySelector('#select-server');
const cashier = document.querySelector('#select-cashier');
const product = document.querySelector('#product');
const unitCost = document.querySelector('#unit-cost');
const sellingPrice = document.querySelector('#selling-price');
const addressNo = document.querySelector('#address-no');
const nominalPay = document.querySelector('#nominal-pay');
const isPaid = document.querySelector('#is-paid');

let invoice = {
    outlet_id: parseInt(outlet.dataset.id),
    invoice_number : '',
    customerId : 0,
    customer_name : '',
    product: '',
    address_no: '',
    selling_price: 0,
    unit_cost: 0,
    serverId: 0,
    cashierId: 0,
    isPaid: true
};

let selectDate = '';

const togglePaid = (value) => {
    invoice.isPaid = !invoice.isPaid;
    const labelIsPaid = document.querySelector('#label-is-paid');
    labelIsPaid.innerHTML = invoice.isPaid ? 'Lunas' : 'Hutang';

    const isCash = document.querySelector('#is-cash');
    invoice.isPaid ? isCash.classList.remove('d-none') : isCash.classList.add('d-none');

    setAddButtonActive();
}

const handleTopUpInvoiceForm = async (e) => {
    e.preventDefault();

    if (isUpdate)
    {
        let url = `/api/invoice/update-top-up-invoice`;

        await axios.put(url, invoice)
            .then(async res => {
                Swal.fire(
                    'Berhasil!',
                    'Invoice Berhasil Diubah',
                    'success'
                )
                cancelUpdateFunc();
            })
            .catch(err => {
                console.log(err);
            })
    } 
    else{
            let url = `/api/invoice/create-top-up-invoice`;

            await axios.post(url, invoice)
            .then(async _ => {
                Swal.fire(
                    'Berhasil!',
                    'Transaksi Behasil Ditambah',
                    'success'
                )
            })
            .catch(err => {
                console.log(err);
            })
        }

        await setDefaultInvoiceValue();
        await getServerBalance();
        await getTopUpInvoice();
}

const setCurrencyFormat = (value) => {
    value.value = formatRupiah(value.value);
}

const setTimeDefault = () => {
    let nowTime = waktu(true);
    let splitNowTime = nowTime.split(' ');
    selectDate = splitNowTime[0];
}

const setDefaultInvoiceValue = async () => {
    invoice = {
        outlet_id: parseInt(outlet.dataset.id),
        invoice_number : '',
        customerId : 0,
        customer_name:'',
        product: '',
        address_no: '',
        selling_price: 0,
        unit_cost: 0,
        serverId: 0,
        cashierId: 0,
        isPaid: true
    }

    setTimeDefault();

    const pelanggan = document.getElementById('pelanggan');
    const server = document.querySelector('#select-server');
    const cashier = document.querySelector('#select-cashier');
    const product = document.querySelector('#product');
    const unitCost = document.querySelector('#unit-cost');
    const sellingPrice = document.querySelector('#selling-price');
    const addressNo = document.querySelector('#address-no');

    pelanggan.value = '';
    server.value = 0;
    // cashier.value = invoice.cashierId;
    unitCost.value = 0;
    product.value = '';
    sellingPrice.value = 0;
    addressNo.value = '';
    invoice.isPaid ? isPaid.setAttribute('checked','checked') : isPaid.removeAttribute('checked');

    const labelIsPaid = document.querySelector('#label-is-paid');
    labelIsPaid.innerHTML = invoice.isPaid ? 'Lunas' : 'Hutang';

    const isCash = document.querySelector('#is-cash');

    invoice.isPaid 
    ? isCash.classList.remove('d-none')
    : isCash.classList.add('d-none');

    await getInvoiceNumber();
}

const getServerBalance = async () => {
    let url = `/api/ledgers/top-up-balance?outlet_id=${parseInt(outlet.dataset.id)}`;
    await axios.get(url)
                .then(res => {
                    let accounts = res.data.data;
                    
                    const tableServerBalance = document.querySelector('#table-server-balance');

                    let list = '';

                    accounts.map(account => {
                        list += `<tr>
                            <th>${account.accountName.toUpperCase()}</th>
                            <th>: Rp. ${account.balance<0?'-':''}${formatRupiah(toPrice(account.balance.toString()))}</th>
                        </tr>`
                    });

                    tableServerBalance.innerHTML = list;
                })
                .catch(err => {
                    console.log(err);
                })
}

const showDetailInvoiceHistory = async (id) => {
    let url = `/api/top-up-invoice/detail/${id}`;

    await axios.get(url)
                .then(res => {
                    let invoiceDetail = res.data.data;
                    
                    const tableTopUpInvoiceDetail = document.querySelector('#table-top-up-invoice-detail');

                    tableTopUpInvoiceDetail.innerHTML = `
                        <tr>
                            <th>Nomor Invoice</th>
                            <td>: ${invoiceDetail.invoice.invoice_number}</td>
                        </tr>
                        <tr>
                            <th>Pelanggan</th>
                            <td>: ${invoiceDetail.customer.nama}</td>
                        </tr>
                        <tr>
                            <th>Produk</th>
                            <td>: ${invoiceDetail.invoice.product}</td>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <td>: ${invoiceDetail.invoice.address_no}</td>
                        </tr>
                        <tr>
                            <th>Modal</th>
                            <td>: Rp. ${formatRupiah(invoiceDetail.invoice.unit_cost.toString())}</td>
                        </tr>
                        <tr>
                            <th>Jual</th>
                            <td>: Rp. ${formatRupiah(invoiceDetail.invoice.selling_price.toString())}</td>
                        </tr>
                        <tr>
                            <th>Server</th>
                            <td>: ${invoiceDetail.invoice.server}</td>
                        </tr>
                    `

                })
                .catch(err => {
                    console.log(err);
                })
}

const cancelUpdateFunc = async () => {
    const btnCreate = document.querySelector('#btn-create');
    const btnUpdate = document.querySelector('#btn-update');
    const btnCancelUpdate = document.querySelector('#btn-cancel-update');

    const selectCash = document.querySelector('#select-cash');

    selectCash.removeAttribute('disabled', 'disabled');
    selectCash.classList.remove('disabled');

    btnCreate.classList.remove('d-none');
    btnCancelUpdate.classList.add('d-none');
    btnUpdate.classList.add('d-none');

    await setDefaultInvoiceValue();

}

const editTopUpBalance  = async (id) => {
    const btnCreate = document.querySelector('#btn-create');
    const btnUpdate = document.querySelector('#btn-update');
    const btnCancelUpdate = document.querySelector('#btn-cancel-update');

    const nomorNota = document.querySelector('#nomor-nota');
    const pelanggan = document.querySelector('#pelanggan');
    const server = document.querySelector('#select-server');
    const addressNo = document.querySelector('#address-no');
    const product = document.querySelector('#product');
    const unitCost = document.querySelector('#unit-cost');
    const sellingPrice = document.querySelector('#selling-price');
    const selectCash = document.querySelector('#select-cash');

    btnCreate.classList.add('d-none');
    btnCancelUpdate.classList.remove('d-none');
    btnUpdate.classList.remove('d-none');

    isUpdate = true;
    
    let url = `/api/top-up-invoice/detail/${id}`;

    await axios(url)
            .then(res=> {
                let oldResult = res.data.data;
                
                invoice = {
                    outlet_id: parseInt(outlet.dataset.id),
                    invoice_number : oldResult.invoice.invoice_number,
                    customerId : oldResult.invoice.customer_id,
                    customer_name : oldResult.customer.nama,
                    product: oldResult.invoice.product,
                    address_no: oldResult.invoice.address_no,
                    selling_price: oldResult.invoice.selling_price,
                    unit_cost: oldResult.invoice.unit_cost,
                    serverId: oldResult.invoice.account_id,
                    cashierId: oldResult.invoice.cashier_id,
                    isPaid: oldResult.isPaid
                };

                nomorNota.value = invoice.invoice_number;
                pelanggan.value = invoice.customer_name;
                server.value = invoice.serverId;
                product.value = invoice.product;
                addressNo.value = invoice.address_no;
                unitCost.value = formatRupiah(invoice.unit_cost.toString());
                sellingPrice.value = formatRupiah(invoice.selling_price.toString());
                selectCash.value = invoice.cashierId;

                invoice.isPaid 
                ? isPaid.setAttribute('checked', 'checked') 
                : isPaid.removeAttribute('checked');

                const isCash = document.querySelector('#is-cash');

                invoice.isPaid 
                ? isCash.classList.remove('d-none')
                : isCash.classList.add('d-none');

                const labelIsPaid = document.querySelector('#label-is-paid');
                labelIsPaid.innerHTML = invoice.isPaid ? 'Lunas' : 'Hutang';


            })
            .catch(err => {
                console.log(err);
            })
}

const deleteInvoice = (id) => {
    Swal.fire({
        title: `Anda Yakin Menghapus Invoice?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/top-up-invoice/delete/${id}`)
            .then(async res => {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )

                await setDefaultInvoiceValue();
                await getServerBalance();
                await getTopUpInvoice();
                
            })
            .catch(err => {
                console.log(err);
            })  
            
        }
      })
}

const getTopUpInvoice = async () => {
    let url = `/api/top-up-invoice?outlet_id=${parseInt(outlet.dataset.id)}&date=${selectDate}`;

    await axios.get(url)
                .then(res => {
                    let list = '';
                    let invoiceDetails = res.data.data;

                    const tableTopUpInvoice = document.querySelector('#table-top-up-invoice');

                    let total = 0;

                    invoiceDetails.map((detail, index )=> {
                        total += parseInt(detail.selling_price);
                        list += `<tr>
                        <td>${detail.product}</td>
                        <td>Rp. ${formatRupiah(detail.selling_price.toString())}</td>
                        <td>${detail.server}</td>
                        <td><div class="dropdown">
                            <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                </svg>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item"
                                    onclick="showDetailInvoiceHistory(${detail.id})"
                                    data-toggle="modal" data-target="#invoiceDetailModal"
                                    >
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
                                <button class="dropdown-item"
                                    onclick="editTopUpBalance(${detail.id})"
                                    data-toggle="modal"
                                    >
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
                                <button class="dropdown-item"
                                    onclick="deleteInvoice(${detail.id})"
                                    data-toggle="modal"
                                    >
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
                        </div></td>
                    </tr>`
                    })

                    const totalTransaction = document.getElementById('total-transaction');

                    totalTransaction.innerHTML = `Rp.${formatRupiah(total.toString())}`;
                    tableTopUpInvoice.innerHTML = list;
                })
                .catch(err => {
                    console.log(err);
                })
}

const setAddButtonActive = () => {
    const buttonSubmit = Array.from(document.getElementsByClassName('button-submit'));

    buttonSubmit.map(button => {
        if (invoice.product && invoice.customerId && invoice.selling_price > 0 && invoice.unit_cost > 0 && invoice.serverId > 0) {
            if (invoice.isPaid && invoice.cashierId > 0 ) {
                button.classList.remove('disabled');
                button.removeAttribute('disabled');
                return;
            }
    
            if (!invoice.isPaid) {
                button.classList.remove('disabled');
                button.removeAttribute('disabled');
                return;
            }
    
        } 
    
        button.classList.add('disabled');
        button.setAttribute('disabled', 'disabled');
    })
}

const getInvoiceNumber = async () => {
    const outlet = document.querySelector('#outlet');
    let url = `/api/invoice/get-top-up-invoice-number?outlet_id=${outlet.dataset.id}`;

    await axios.get(url)
        .then(response => {
            const nomorNota = document.querySelector('#nomor-nota');
            
            nomorNota.value = response.data.data;
            invoice.invoice_number = response.data.data;
        })
        .catch(error => {
            console.log(error);
        })
}

const defineAttributeModal = (
        title = 'Cari',
        searchInputId = '',
        searchListId = '',
        placeholder='',
        submitButtonLabel = 'Pilih',
        showSubmitButton = false,
    ) => {
    const modalTitle = document.getElementById('modal-title');
    modalTitle.innerHTML = title;

    const submitButton = document.getElementById('submit-button');
    submitButton.innerHTML = submitButtonLabel;
    submitButton.style.display = showSubmitButton ? 'block' : 'none';

    const modalBody = document.getElementById('modal-body');
    modalBody.innerHTML = `<input class="form-control" type="text" placeholder="${placeholder}" id="${searchInputId}">
                            <div class="list-group mt-2" id="${searchListId}">
                                
                            </div>`;
}

const cariPelanggan = document.getElementById('cari-pelanggan');

cariPelanggan.addEventListener('click', async () => {
    let title = 'Cari Pelanggan';
    let searchInputId = 'input-cari-pelanggan';
    let searchListId = 'list-pelanggan';
    let placeholder = 'Cari Pelanggan';
    defineAttributeModal(title, searchInputId, searchListId, placeholder);

    const listPelanggan = document.getElementById('list-pelanggan');

    listPelanggan.innerHTML = '';

    const getPelanggan = async (input='') => {
        let dataPelanggan = [];
        listPelanggan.innerHTML = '';
        listPelanggan.innerHTML = '<div class="row"><div class="col-12 text-center"><div class="spinner-border" role="status"></div></div></div> ';    
        await axios.get(`/api/pelanggan/get-pelanggan?pelanggan=${input}`)
        .then(response => {
            listPelanggan.innerHTML = '';
            
            dataPelanggan = response.data.data;
            dataPelanggan.map(item => {
                listPelanggan.innerHTML += `<a href="#" class="list-group-item list-group-item-action list-grop-pelanggan" id="list-group-${item.id}" data-id="${item.id}" data-nama="${item.nama}"  data-dismiss="modal">${item.nama}</a>`;
            });

            const listGroupPelanggan = Array.from(document.getElementsByClassName('list-grop-pelanggan'));

            listGroupPelanggan.map(p => {
                p.addEventListener('click', () => {
                    invoice.customerId = parseInt(p.dataset.id);
                    invoice.customer_name = p.dataset.nama;
                    pelanggan.value = p.dataset.nama;
                    setAddButtonActive();
                })
            })
        })
        .catch(error => {
            console.log(error);
        }
        )
    }

    await getPelanggan();

    const inputCariPelanggan = document.getElementById('input-cari-pelanggan');

    inputCariPelanggan.addEventListener('keyup', async function(){
        await getPelanggan(inputCariPelanggan.value);
    });
    
})

const selectServer = (value) => {
    invoice.serverId = parseInt(value.value);

    setAddButtonActive();
}

const selectCashier = (value) => {
    invoice.cashierId = parseInt(value.value);

    setAddButtonActive();
}

product.addEventListener('change', function(){
    product.value = product.value.toUpperCase();
    invoice.product = product.value.toUpperCase();

    setAddButtonActive();
})

unitCost.addEventListener('change', function(){
    invoice.unit_cost = parseInt(toPrice(unitCost.value));

    setAddButtonActive();
})

sellingPrice.addEventListener('change', function(){
    invoice.selling_price = parseInt(toPrice(sellingPrice.value));

    setAddButtonActive();
})

addressNo.addEventListener('change', function(){
    invoice.address_no = addressNo.value;

    setAddButtonActive();
})

window.addEventListener('load',async function(){
    setTimeDefault();
    await setDefaultInvoiceValue();
    await getServerBalance();
    await getTopUpInvoice();
})