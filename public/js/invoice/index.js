const outletId = document.getElementById('outlet-id');
let invoice = {
    outletId: outletId.value,
    nomor_nota: '',
    pelangganId: '',
    namaPelanggan: '',
    totalBayar: '',
    detail: []
};

let invoiceHistory = [];

const printInvoice = document.getElementById('print');
const app = document.getElementById('app');


const nomorNota = document.getElementById('nomor_nota');
const pelanggan = document.getElementById('pelanggan');
const kode = document.getElementById('kode');
const produk = document.getElementById('produk');
const idProduk = document.getElementById('id-produk');
const harga = document.getElementById('harga');
const jumlah = document.getElementById('jumlah');
const totalInvoice = Array.from(document.getElementsByClassName('total-invoice'));

const detailInvoice = document.getElementById('detail-invoice');

const buttonSubmitProduct = Array.from(document.getElementsByClassName('btn-submit'));
const submitProduct = document.getElementById('submit-product');
const addProductButton = document.getElementById('add-product-button');
const editProductButton = document.getElementById('edit-product-button');

const cancelEditProductButton = document.getElementById('cancel-edit-product-button');

const btnBayar = document.getElementById('btn-bayar');
const detailList = document.getElementById('detail-list');

let totalBelanja = 0;

btnBayar.addEventListener('click', async () => {
    detailList.innerHTML = '';

    totalBelanja = 0;
    invoice.detail.map((item, index) => {
        let total = item.harga * item.jumlah;
        totalBelanja += total;
        detailList.innerHTML += `<tr>
                                    <td>${index + 1}</td>
                                    <td>${item.kode}</td>
                                    <td class="text-left">${item.produk}</td>
                                    <td class="text-center">${item.jumlah}</td>
                                    <td class="text-right">${formatRupiah(item.harga.toString())}</td>
                                    <td class="text-right">${formatRupiah(total.toString())}</td>
                                </tr>`;
    });

    sisa.innerText = `Rp. -${formatRupiah(totalBelanja.toString())}`;
})

const showDetailProduct = () => {
    totalBelanja = 0;
    invoice.detail.map((item, index) => {
        let total = item.harga * item.jumlah;
        totalBelanja += total;
        detailInvoice.innerHTML += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${item.kode}</td>
                                        <td class="text-left">${item.produk}</td>
                                        <td class="text-center">${item.jumlah}</td>
                                        <td class="text-right">${formatRupiah(item.harga.toString())}</td>
                                        <td class="text-right">${formatRupiah(total.toString())}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                      </svg>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <button class="dropdown-item"
                                                        onclick="editItem(${index})"
                                                    >
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

                                                    <button class="dropdown-item" onclick="deleteItem(${index})">
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
                                    </tr>`;
    });

    totalInvoice.map(total => {
        total.innerText = `Rp. ${formatRupiah(totalBelanja.toString())}`;
    });
}

const setDefault = () => {
    kode.value = '';
    produk.value = '';
    harga.value = '';
    jumlah.value = 1; 

    cancelEditProductButton.classList.add('d-none');
    addProductButton.classList.remove('d-none');
    editProductButton.classList.add('d-none');

}

addProductButton.addEventListener('click', async () => {
    
    invoice.nomor_nota = parseInt(nomorNota.value);
    
    invoice.detail = [...invoice.detail, {
        kode: kode.value,    
        produk: produk.value,
        idProduk: idProduk.value,
        harga: parseInt(toPrice(harga.value)),
        jumlah: parseInt(jumlah.value),
    }];

    detailInvoice.innerHTML = '';
    showDetailProduct();

    setDefault();
    addProductButton.classList.add('disabled');
    addProductButton.setAttribute('disabled', 'disabled');
})

let indexUbah = 0;

const editItem = (index) => {
    indexUbah = index;
    let detailTemp = invoice.detail[index];
    kode.value = detailTemp.kode;
    produk.value = detailTemp.produk;
    harga.value = formatRupiah(detailTemp.harga.toString());
    jumlah.value = detailTemp.jumlah;

    cancelEditProductButton.classList.remove('d-none');
    addProductButton.classList.add('d-none');
    editProductButton.classList.remove('d-none');
}

const deleteItem = (index) => {
    invoice.detail.splice(index, 1);
    detailInvoice.innerHTML = '';
    showDetailProduct();
    
    if (invoice.detail.length == 0) {
        btnBayar.setAttribute('disabled', 'disabled');
    }
}

editProductButton.addEventListener('click', () => {
    let detailItem = {};
    detailItem.kode = kode.value;
    detailItem.produk = produk.value;
    detailItem.idProduk = idProduk.value;
    detailItem.harga = parseInt(harga.value.replace(/[^0-9]/g, ''));
    detailItem.jumlah = parseInt(jumlah.value);
    invoice.detail[indexUbah] = detailItem;
    
    detailInvoice.innerHTML = '';
    showDetailProduct();
    setDefault();
});

cancelEditProductButton.addEventListener('click', () => {
    setDefault();
})

const getInvoiceNumber = async () => {
    await axios.get('/api/invoice/get-invoice-number?outlet_id=' + outletId.value)
        .then(response => {
            nomorNota.value = response.data ? parseInt(response.data.data.no_nota) + 1 : 1;
        })
        .catch(error => {
            console.log(error);
        })
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
                    invoice.pelangganId = p.dataset.id;
                    invoice.namaPelanggan = p.dataset.nama;
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

const cariProduk = document.getElementById('cari-produk');

cariProduk.addEventListener('click', function(){
    let title = 'Cari Produk';
    let searchInputId = 'input-cari-produk';
    let searchListId = 'list-produk';
    let placeholder = 'Masukkan Kode Produk';
    defineAttributeModal(title, searchInputId, searchListId, placeholder);

    const listProduk = document.getElementById('list-produk');

    listProduk.innerHTML = '';

    const getProduk = async (inputKode='') => {
        let dataProduk = [];
        listProduk.innerHTML = '';
        listProduk.innerHTML = '<div class="row"><div class="col-12 text-center"><div class="spinner-border" role="status"></div></div></div> ';  
        axios.get(`/api/produk/get-produk?outlet=${outletId.value}&kode=${inputKode}`)
            .then(response => {
                listProduk.innerHTML = '';
                dataProduk = response.data.data;
                dataProduk.map(item => {
                    listProduk.innerHTML += `<a href="#" class="list-group-item list-group-item-action list-grop-produk" id="list-group-${item.id}" data-id="${item.id}" data-kode="${item.kode}" data-jual="${item.jual}" data-produk = "${item.tipe}" data-product-id="${item.product_id}" data-dismiss="modal">${item.kode} - ${item.tipe}</a>`;
                });
    
                const listGroupProduk = Array.from(document.getElementsByClassName('list-grop-produk'));
    
                listGroupProduk.map(p => {
                    p.addEventListener('click', () => {
                        kode.value = p.dataset.kode;
                        produk.value = p.dataset.produk;
                        produk.value = p.dataset.produk;
                        idProduk.value = p.dataset.productId;
                        harga.value = harga.value = formatRupiah(p.dataset.jual);

                        setAddButtonActive();
                    })
                })
            })
            .catch(error => {
                console.log(error);
            })
    }

    getProduk();

    const inputCariProduk = document.getElementById('input-cari-produk');;

    inputCariProduk.addEventListener('keyup', async function(){
        await getProduk(inputCariProduk.value);
    });
})

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

harga.addEventListener('keyup', function(){
    harga.value = formatRupiah(this.value);
    setAddButtonActive();
})

jumlah.addEventListener('keyup', function(){
    if (jumlah.value == '') {
        jumlah.value = '0';
    }
})

const setAddButtonActive = () => {
    if (pelanggan.value && produk.value && harga.value) {
        buttonSubmitProduct.map(b => {
            b.classList.remove('disabled');
            b.removeAttribute('disabled');
        })
        return;
    } 
    buttonSubmitProduct.map(b => {
        b.classList.add('disabled');
        b.setAttribute('disabled', 'disabled');
    })
}

const inputPayment = document.getElementById('input-payment');
const sisa = document.getElementById('sisa');

inputPayment.addEventListener('keyup', function(){
    if (inputPayment.value == '') {
        inputPayment.value = '0';
    }
    inputPayment.value = formatRupiah(this.value);

    let sisaBelanja = parseInt(toPrice(inputPayment.value)) - totalBelanja;

    sisa.innerText = `Rp.${sisaBelanja < 0 ? '-' : ''} ${formatRupiah(sisaBelanja.toString())}`;
})

const payConfirmationButton = document.getElementById('pay-confirmation-button');

payConfirmationButton.addEventListener('click', function(){
    inputPayment.value = formatRupiah('0');
    invoice.totalBayar = toPrice(inputPayment.value);
    app.classList.add('d-none');
    printInvoice.classList.remove('d-none');

    let list = '';

    list += `<div class="text-center fw-bold">
                        ${payConfirmationButton.dataset.outletName}
                    </div>
                    <div class="text-center">
                    ${payConfirmationButton.dataset.outletAddress}
                    </div>`;

    list += `<table style="font-size: 13px">
                <tbody>
                    <tr>
                        <td>Nomor Nota</td>
                        <td>: ${invoice.nomor_nota}</td>
                    </tr>
                    <tr>
                        <td>Kepada</td>
                        <td>: ${invoice.namaPelanggan}</td>
                    </tr>
                    <tr>
                        <td>Kasir</td>
                        <td>: ${capital(payConfirmationButton.dataset.user)}</td>
                    </tr>
                    <tr>
                        <td>Waktu</td>
                        <td>: ${waktu()}</td>
                    </tr>
                </tbody>
            </table>`;

    list += `<table style="width: 100%;font-size: 14px; font-family: 'Arial', Times, serif;margin-bottom:10px;">`

    let grandTotal = 0;
    let i = 0;
    invoice.detail.map(item => {
        i += item.jumlah;
        let total = item.jumlah * item.harga;
        grandTotal += total;
        list += `<tbody style="border-top:1px solid;border-bottom:1px solid">
                <tr style="vertical-align: text-top;">
                    <td>${item.kode}</td>
                    <td style="padding-left: 2px" colspan="2" class="align-baseline">${item.produk}</td>
                </tr>
                <tr>
                    <td class="text-center">${item.jumlah}</td>
                    <td class="text-right">${formatRupiah(item.harga.toString())}</td>
                    <td class="text-right">${formatRupiah(total.toString())}</td>
                </tr>
                </tbody>`
    });

    let sisaBayar = parseInt(toPrice(inputPayment.value)) - grandTotal;

    list += `<tfoot style="border-bottom: 2px solid">
            <tr class="font-weight-bold">
                <td class="text-left">Jumlah: ${i}</td>
                <td class="text-right">Total:</td>
                <td class="text-right">${formatRupiah(grandTotal.toString())}</td>
            </tr>
            <tr class="font-weight-bold">
                <td class="text-right" colspan="2">Bayar:</td>
                <td class="text-right">${formatRupiah(inputPayment.value)}</td>
            </tr>
            <tr class="font-weight-bold">
                <td class="text-right" colspan="2">Sisa:</td>
                <td class="text-right">${formatRupiah(sisaBayar.toString())}</td>
            </tr>
            </tfoot>`;

    list += `</table>
            <div class="text-center border-bottom font-italic font-weight-bold">
                Terima Kasih Atas Kunjungan Anda
            </div>`;

    list += `<div class='row justify-content-center'>
                <div class='col-12'>
                    <button class="btn btn-sm btn-primary d-print-none w-100" id="print-btn" >
                        cetak
                    </button>
                </div>
                <div class='col-12'>
                    <button id="new-invoice" class="btn btn-sm btn-success d-print-none w-100">
                        nota baru
                    </button>
                    <button id="batal-print" class="btn btn-sm btn-secondary d-print-none w-100">
                        batal cetak
                    </button>
                </div>
            </div>`;

    printInvoice.innerHTML = list;

    const  batalPrint = document.getElementById('batal-print');
    const  newInvoice = document.getElementById('new-invoice');
    const printButton = document.getElementById('print-btn');

    batalPrint.addEventListener('click', function(){
        printInvoice.classList.add('d-none');
        app.classList.remove('d-none');
    })

    printButton.addEventListener('click',async function(){
        await saveInvoice(true);
    })

    newInvoice.addEventListener('click',async function(){
        await saveInvoice(false);
    })
});

const saveInvoice = async (print = false) => {
    await axios.post('/api/invoice/create',
        invoice
    )
    .then(res => {
        print ? window.print() : '';
        printInvoice.classList.add('d-none');
        app.classList.remove('d-none');

        setTimeout(() => {
            pelanggan.value = '';
            produk.value = '';
            harga.value = '';
            jumlah.value = '0';
            nomorNota.value = invoice.nomor_nota;
            
            btnBayar.setAttribute('disabled', 'disabled');
            totalInvoice.map(total => {
                total.innerText = `Rp.`;
            });
            detailInvoice.innerHTML = '';
            setDefault();
            getInvoiceHistory();
        }, 100);
        invoice = {
            nomor_nota: res.data.data.no_nota + 1,
            namaPelanggan: '',
            totalBayar: 0,
            detail: []
        }
    })
    .catch(err => {
        console.log(err);
    })

}

/* Fungsi formatRupiah */
function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);
  
    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
      separator = sisa ? "." : "";
      rupiah += separator + ribuan.join(".");
    }
  
    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
  }

function toPrice(angka) {
    return angka.replace(/[^0-9]/g, '');
}

function capital(str)
{
    return str.replace(/\w\S*/g, function(kata){ 
        const kataBaru = kata.slice(0,1).toUpperCase() + kata.substr(1);
        return kataBaru
    });
}

const waktu = () => {
    date = new Date();
    millisecond = date.getMilliseconds();
    detik = date.getSeconds();
    menit = date.getMinutes();
    jam = date.getHours();
    hari = date.getDay();
    tanggal = date.getDate();
    bulan = date.getMonth();
    tahun = date.getFullYear();
    return `${tanggal}/${bulan+1}/${tahun} ${jam}:${menit}:${detik}`
}

const getInvoiceHistory = async () => {
    await axios.get(`/api/invoices?outletId=${outletId.value}&date=${now()}`)
    .then(res => {
        let list = '';
        invoiceHistory = res.data.data;
        let invoiceHistoryDetail = document.getElementById('invoice-history-detail');
        let totalHistory = 0;
        
        invoiceHistory.map((history, index) => {
            //hitung total invoice
            let total = 0;
            history.invoice_detail.map(detail => {
                total += detail.jual * detail.jumlah;
                });
            totalHistory += total;

            list += `<tr>
                        <td class="text-center">${history.no_nota}</td>
                        <td class="text-center">${history.customer.nama}</td>
                        <td class="text-right">${formatRupiah(total.toString())}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    </svg>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item"
                                        onclick="showDetailInvoiceHistory(${index})"
                                        data-toggle="modal" data-target="#invoiceDetailModal"
                                        >
                                        <div class="row">
                                            <div class="col-3 my-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                                </svg>
                                            </div>
                                            <div class="col-9 my-auto">
                                                Detail
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>`
        });

        invoiceHistoryDetail.innerHTML = list;
        const totalHistoryInvoice = document.getElementById('total-history-invoice');
        totalHistoryInvoice.innerHTML = `${formatRupiah(totalHistory.toString())}`;

    })
    .catch(err => {
        console.log(err);
    })
}

const printInvoiceHistoryButton = document.getElementById('print-invoice-history-button');

const showDetailInvoiceHistory = (id) => {
    const invoiceDetailNumber = document.getElementById('invoice-detail-number');
    invoiceDetailNumber.innerHTML = `: ${invoiceHistory[id].no_nota}`;

    const invoiceDetailCustomer = document.getElementById('invoice-detail-customer');
    invoiceDetailCustomer.innerHTML = `: ${invoiceHistory[id].customer.nama}`;

    const invoiceDetailList = document.getElementById('invoice-detail-list');

    let list = '';
    let grandTotalInvoice = 0;
    invoiceHistory[id].invoice_detail.map((detail, index) => {
        let total = detail.jual * detail.jumlah; 
        grandTotalInvoice += total;
        list += `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">${detail.product.kode}</td>
                    <td class="text-center">${detail.product.tipe}</td>
                    <td class="text-center">${detail.jual}</td>
                    <td class="text-center">${detail.jumlah}</td>
                    <td class="text-right">${formatRupiah(total.toString())}</td>
                </tr>`
    });

    invoiceDetailList.innerHTML = list;

    let grandTotalInvoiceHistory = document.getElementById('grand-total-invoice-history');

    grandTotalInvoiceHistory.innerHTML = `${formatRupiah(grandTotalInvoice.toString())}`;

    printInvoiceHistoryButton.addEventListener('click', () => {
        printInvoiceHistory(invoiceHistory[id]);
    })
}

const printInvoiceHistory = (invoice) => {
    app.classList.add('d-none');
    printInvoice.classList.remove('d-none');

    let list = '';

    list += `<div class="text-center fw-bold">
                        ${payConfirmationButton.dataset.outletName}
                    </div>
                    <div class="text-center">
                    ${payConfirmationButton.dataset.outletAddress}
                    </div>`;

    list += `<table style="font-size: 13px">
                <tbody>
                    <tr>
                        <td>Nomor Nota</td>
                        <td>: ${invoice.no_nota}</td>
                    </tr>
                    <tr>
                        <td>Kepada</td>
                        <td>: ${invoice.customer.nama}</td>
                    </tr>
                    <tr>
                        <td>Kasir</td>
                        <td>: ${capital(payConfirmationButton.dataset.user)}</td>
                    </tr>
                    <tr>
                        <td>Waktu</td>
                        <td>: ${waktu()}</td>
                    </tr>
                </tbody>
            </table>`;

    list += `<table style="width: 100%;font-size: 14px; font-family: 'Arial', Times, serif;margin-bottom:10px;">`

    let grandTotal = 0;
    let i = 0;
    invoice.invoice_detail.map(item => {
        i += item.jumlah;
        let total = item.jumlah * item.jual;
        grandTotal += total;
        list += `<tbody style="border-top:1px solid;border-bottom:1px solid">
                <tr style="vertical-align: text-top;">
                    <td>${item.product.kode}</td>
                    <td style="padding-left: 2px" colspan="2" class="align-baseline">${item.product.tipe}</td>
                </tr>
                <tr>
                    <td class="text-center">${item.jumlah}</td>
                    <td class="text-right">${formatRupiah(item.jual.toString())}</td>
                    <td class="text-right">${formatRupiah(total.toString())}</td>
                </tr>
                </tbody>`
    });

    list += `<tfoot style="border-bottom: 2px solid">
            <tr class="font-weight-bold">
                <td class="text-left">Jumlah: ${i}</td>
                <td class="text-right">Total:</td>
                <td class="text-right">${formatRupiah(grandTotal.toString())}</td>
            </tr>
            </tfoot>`;

    list += `</table>`;

    list += `<div class='row justify-content-center'>
                <div class='col-12'>
                    <button class="btn btn-sm btn-primary d-print-none w-100" id="print-btn" >
                        cetak
                    </button>
                </div>
                <div class='col-12'>
                    <button id="batal-print" class="btn btn-sm btn-secondary d-print-none w-100">
                        kembali
                    </button>
                </div>
            </div>`;

    printInvoice.innerHTML = list;

    const  batalPrint = document.getElementById('batal-print');
    const printButton = document.getElementById('print-btn');

    batalPrint.addEventListener('click', function(){
        printInvoice.classList.add('d-none');
        app.classList.remove('d-none');
    })

    printButton.addEventListener('click',async function(){
        window.print();
    })
}

const now = () => {
    date = new Date();
    year = date.getFullYear();
    month = date.getMonth();
    day = date.getDate();

    return `${year}-${month+1}-${day}`
}

window.addEventListener('load', function () {
    getInvoiceNumber();
    getInvoiceHistory();
})