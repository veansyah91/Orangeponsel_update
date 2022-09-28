const id = document.getElementById('id');
const createJournalModal = document.getElementById('createJournalModal');

let editState = false;

// start input section
    let rowInput = 0;

    let timeNow;

    // input variable
    let journalData = {};
    let search = '';
    let date_from = '';
    let date_to = '';
    let thisWeek = '';
    let thisMonth = '';
    let thisYear = '';
    let filterValue = 0;

    // modal input
    const dateInput = document.getElementById('date-input');
    const description = document.getElementById('description');
    const detail = document.getElementById('detail');
    const referenceNo = document.getElementById('reference-no');

    let totalDebit = 0;
    let totalCredit = 0;
    let accounts = [
        [],[]
    ];

    const createJournalButton = document.getElementById('create-journal-button');

    const accountTableBody = document.getElementById('account-table-body');
    const accountTableRow = Array.from(document.getElementsByClassName('account-table-row'));
    const addAccountRow = document.getElementById('add-account-row');

    const debitInput = Array.from(document.getElementsByClassName('debit-input'));
    const creditInput = Array.from(document.getElementsByClassName('credit-input'));

    const accountInputDropdown = Array.from(document.getElementsByClassName('account-input-dropdown'));

    const accountList = Array.from(document.getElementsByClassName('account-list'));

    createJournalModal.addEventListener('click', async () => {
        const accountInputDropdown = Array.from(document.getElementsByClassName('account-input-dropdown'));
        
        accountInputDropdown.forEach((accountInput, index) => {
            journalData.accountList && journalData.accountList[index].account  
            ? accountInput.value = journalData.accountList[index].account : accountInput.value = '';
        });
    })

    const selectAccount = (index, accountId, accountName) => {
        const accountInputDropdown = Array.from(document.getElementsByClassName('account-input-dropdown'));

        journalData.accountList[index].account = accountName;
        journalData.accountList[index].account_id = accountId;
        accountInputDropdown[index].value = journalData.accountList[index].account;

        const debitInput = Array.from(document.getElementsByClassName('debit-input'));
        const creditInput = Array.from(document.getElementsByClassName('credit-input'));

        if (totalDebit > 0 && totalDebit > totalCredit) {
            let sisa = totalDebit - totalCredit;
            creditInput[index].value = formatRupiah(sisa.toString()); 
            journalData.accountList[index].credit = sisa;

            totalCredit = 0;
            creditInput.forEach(d => {
                totalCredit += d.value ? parseInt(toPrice(d.value)) : 0;
            });

            const totalCreditValue = document.getElementById('total-credit-value');

            totalCreditValue.innerHTML = formatRupiah(`Rp. ${totalCredit.toString()}`);
        }

        if (totalCredit > 0 && totalCredit > totalDebit) {
            let sisa = totalCredit - totalDebit;
            debitInput[index].value = formatRupiah(sisa.toString());
            journalData.accountList[index].debit = sisa;


            totalDebit = 0;
            debitInput.forEach(d => {
                totalDebit += d.value ? parseInt(toPrice(d.value)) : 0;
            });

            const totalDebitValue = document.getElementById('total-debit-value');

            totalDebitValue.innerHTML = formatRupiah(`Rp. ${totalDebit.toString()}`);

        }

        balanceDebitCredit(index);
    }

    createJournalButton.addEventListener('click', async () => {
        setInputDefault();
    })

    const accountDropdownKeyup = async (value) => {
        const accountList = Array.from(document.getElementsByClassName('account-list'));

        await axios.get(`/api/account/getData?outletId=${createJournalButton.dataset.outletId}&search=${value.value}&is_active=1`)
                .then(response => {
                    accounts[parseInt(value.getAttribute('order'))] = response.data.data;
                    accountList[parseInt(value.getAttribute('order'))].innerHTML = '';
                    let list = '';
                    accounts[parseInt(value.getAttribute('order'))].forEach(account => {
                        list += `<a class="dropdown-item" href="#" onclick="selectAccount(${parseInt(value.getAttribute('order'))}, ${account.id}, '${account.name}')"><div class="row">
                        <div class="col-12" style="font-size: 10px">
                            ${account.code}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            ${account.name}
                        </div>
                    </div></a>`;
                    });
                    accountList[parseInt(value.getAttribute('order'))].innerHTML = list;

                })
                .catch(error => {
                    console.log(error);
                });
    }

    const accountDropdownFocus = async (value) => {
        const accountList = Array.from(document.getElementsByClassName('account-list'));
        await axios.get(`/api/account/getData?outletId=${createJournalButton.dataset.outletId}&search=${value.value}&is_active=1`)
        .then(response => {
            accounts[parseInt(value.getAttribute('order'))] = response.data.data;
            accountList[parseInt(value.getAttribute('order'))].innerHTML = '';
            let list = '';
            accounts[parseInt(value.getAttribute('order'))].forEach(account => {
                list += `<a class="dropdown-item" href="#" onclick="selectAccount(${parseInt(value.getAttribute('order'))}, ${account.id}, '${account.name}', '${account.code}')">
                <div class="row">
                    <div class="col-12" style="font-size: 10px">
                        ${account.code}
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        ${account.name}
                    </div>
                </div>
                    
                </a>`;
            });
            accountList[parseInt(value.getAttribute('order'))].innerHTML = list;
        })
        .catch(error => {
            console.log(error);
        });
    }

    addAccountRow.addEventListener('click', async () => {
        rowInput += 1;

        // create a row node:
        const row = document.createElement('tr');
        row.className = 'account-table-row';
        row.innerHTML = `
        <td>
            <div class="dropdown">
                <input 
                    class="form-control account-input-dropdown" 
                    type="text" 
                    data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Akun"
                    autocomplete="off"
                    onkeyup="accountDropdownKeyup(this)"
                    onclick="accountDropdownFocus(this)"
                    order="${rowInput-1}"
                    onchange="balanceDebitCredit(${rowInput-1})"
                >

                <div class="dropdown-menu w-100 overflow-auto account-list" style="max-height:180px">
                    
                </div>
            </div>
            
        </td>
        <td class="text-right">
            <input type="text" class="form-control text-right debit-input" onkeyup="debitInputKeyup(this)"
            order="${rowInput-1}" inputmode="numeric" value="0">
        </td>
        <td class="text-right">
            <input type="text" class="form-control text-right credit-input" onkeyup="creditInputKeyup(this)"
            order="${rowInput-1}" inputmode="numeric" value="0">
        </td>
        <td class="text-center">
            <button class="btn btn-danger btn-sm remove-row-btn" order="${rowInput-1}" onclick="removeRowBtnFunc(this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                </svg>
            </button>
        </td>
        `
        accountTableBody.appendChild(row);
        journalData.accountList = [...journalData.accountList, {account: '', account_id: '', debit: 0, credit: 0}];
        accounts = [...accounts, []];
    })

    debitInput.forEach((d, index) => {
        d.addEventListener('keyup', () => {
            d.value = formatRupiah(d.value);
        });
    })

    creditInput.forEach((c, index) => {
        c.addEventListener('keyup', () => {
            c.value = formatRupiah(c.value);
        });
    })

    const removeRowBtnFunc = (value) => {
        // hapus element 
        const row = document.getElementsByClassName('account-table-row')[parseInt(value.getAttribute('order'))];
        row.remove();

        // hapus data list dropdowwn
        accounts.splice(parseInt(value.getAttribute('order')), 1);

        //hapus data pada journalData
        journalData.accountList.splice(parseInt(value.getAttribute('order')), 1);

        //set ulang attribute order setiap row
        const accountInputDropdown = document.getElementsByClassName('account-input-dropdown');
        const removeRowBtn = document.getElementsByClassName('remove-row-btn');
        for (let i = 0; i < accountInputDropdown.length; i++) {
            accountInputDropdown[i].setAttribute('order', i);
            removeRowBtn[i].setAttribute('order', i);
        }

        //hapus nilai rowInput 1
        rowInput -= 1;

        //set ulang debit dan credit
        const debitInput = Array.from(document.getElementsByClassName('debit-input'));
        const creditInput = Array.from(document.getElementsByClassName('credit-input'));

        totalDebit = 0;
        debitInput.forEach(d => {
            totalDebit += d.value ? parseInt(toPrice(d.value)) : 0;
        });

        const totalDebitValue = document.getElementById('total-debit-value');

        totalDebitValue.innerHTML = formatRupiah(`Rp. ${totalDebit.toString()}`);

        totalCredit = 0;
        creditInput.forEach(d => {
            totalCredit += d.value ? parseInt(toPrice(d.value)) : 0;
        });

        const totalCreditValue = document.getElementById('total-credit-value');

        totalCreditValue.innerHTML = formatRupiah(`Rp. ${totalCredit.toString()}`);

        balanceDebitCredit();

        
    }

    const setInputDefault = () => {
        const createJournalModalLabel = document.getElementById('createJournalModalLabel');
        createJournalModalLabel.innerHTML = 'Tambah Jurnal';

        const addJournalButton = document.getElementById('add-journal-button');

        const descriptionInput = document.getElementById('description-input');

        const detailInput = document.getElementById('detail-input');

        const referenceNoInput = document.getElementById('reference-no-input');

        addJournalButton.classList.add('d-none');

        const editJournalButton = document.getElementById('edit-journal-button');

        editJournalButton.classList.add('d-none');
        descriptionInput.value = '';
        detailInput.value = '';
        referenceNoInput.value = '';

        totalCredit = 0;
        totalDebit = 0;
        editState = false;

        journalData = {
            outletId:createJournalButton.dataset.outletId,
            dateInput:'',
            descriptionInput:'',
            detailInput:'',
            reference_no:'',
            accountList:[
                {
                    account:'',
                    account_id:null,
                    debit:0,
                    credit:0
                },
                {
                    account:'',
                    account_id:null,
                    debit:0,
                    credit:0
                }
            ],
        }
        timeNow = waktu().split(" ");
        rowInput = 2;
        dateInput.value = timeNow[0];
        journalData.dateInput = toDateFormat(timeNow[0]);

        let rowList = '';
        for (let i = 0; i < rowInput; i++) {
            rowList += `
            <tr class="account-table-row">
                <td>
                    <div class="dropdown">
                        <input 
                            class="form-control account-input-dropdown" 
                            type="text" 
                            data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Akun"
                            autocomplete="off"
                            onkeyup="accountDropdownKeyup(this)"
                            onclick="accountDropdownFocus(this)"
                            order="${i}"
                            onchange="balanceDebitCredit(${i})"
                        >

                        <div class="dropdown-menu w-100 overflow-auto account-list" style="max-height:180px">
                            
                        </div>
                    </div>
                    
                </td>
                <td class="text-right">
                    <input type="text" class="form-control text-right debit-input" onkeyup="debitInputKeyup(this)"
                    order="${i}" inputmode="numeric" value="0">
                </td>
                <td class="text-right">
                    <input type="text" class="form-control text-right credit-input" onkeyup="creditInputKeyup(this)"
                    order="${i}" inputmode="numeric" value="0">
                </td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm remove-row-btn" order="${i}" onclick="removeRowBtnFunc(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            `
        }
        accountTableBody.innerHTML = rowList;

        const totalDebitValue = document.getElementById('total-debit-value');
        totalDebitValue.innerHTML = "0";
        const totalCreditValue = document.getElementById('total-credit-value');
        totalCreditValue.innerHTML = "0";
    }

    const changeDetailFunc = (value) => {
        journalData.detail = value.value;
    }

    const changeDescriptionFunc = () => {
        const descriptionInput = document.getElementById('description-input');
        const referenceNoInput = document.getElementById('reference-no-input');
        let outletId = createJournalButton.dataset.outletId;

        descriptionInput.value = capital(descriptionInput.value);
        let referenceNo = `${abbreviation(descriptionInput.value)}-`;

        axios.get(`/api/journal/check-description?outlet_id=${outletId}&ref_no=${referenceNo}`)
        .then(res => {

            referenceNoInput.value = '';
            referenceNoInput.value = `${referenceNoInput.value}${res.data.data}`;
            journalData.descriptionInput = descriptionInput.value;
            journalData.reference_no = referenceNoInput.value;

            balanceDebitCredit();

        })
        .catch(err => {
            console.log(err);
        })
    }

    const debitInputKeyup = (value) => {
        if (value.value == '') {
            value.value = 0;
        }

        value.value = formatRupiah(value.value);
        const order = value.getAttribute('order');
        const debitInput = Array.from(document.getElementsByClassName('debit-input'));

        totalDebit = 0;
        debitInput.forEach(d => {
            totalDebit += d.value ? parseInt(toPrice(d.value)) : 0;
        });

        const totalDebitValue = document.getElementById('total-debit-value');

        totalDebitValue.innerHTML = formatRupiah(`Rp. ${totalDebit.toString()}`);

        journalData.accountList[order].debit = parseInt(toPrice(value.value));
        
        balanceDebitCredit(order);
    }

    const creditInputKeyup = (value) => {
        if (value.value == '') {
            value.value = 0;
        }
        value.value = formatRupiah(value.value);
        const order = value.getAttribute('order');
        const creditInput = Array.from(document.getElementsByClassName('credit-input'));
        
        totalCredit = 0;
        creditInput.forEach(d => {
            totalCredit += d.value ? parseInt(toPrice(d.value)) : 0;
        });

        const totalCreditValue = document.getElementById('total-credit-value');

        totalCreditValue.innerText = formatRupiah(`Rp. ${totalCredit.toString()}`);

        journalData.accountList[order].credit = parseInt(toPrice(value.value));


        balanceDebitCredit(order);
    }

    const balanceDebitCredit = (order = 0) => {
        const addJournalButton = document.getElementById('add-journal-button');
        const editJournalButton = document.getElementById('edit-journal-button');

        if(!journalData.descriptionInput) {
            editJournalButton.classList.add('d-none') ;
            addJournalButton.classList.add('d-none');
            return;
        }

        if(!journalData.reference_no) {
            editJournalButton.classList.add('d-none') ;
            addJournalButton.classList.add('d-none');
            return;
        }

        if(!journalData.accountList[order].account) {
            editJournalButton.classList.add('d-none') ;
            addJournalButton.classList.add('d-none');
            return;
        }

        if(totalCredit == 0 || totalDebit == 0) {
            editJournalButton.classList.add('d-none') ;
            addJournalButton.classList.add('d-none');
            return;
        }
        
        if (totalCredit != totalDebit) {
            editJournalButton.classList.add('d-none') ;
            addJournalButton.classList.add('d-none');
            return;
        }

        editState ? 
        editJournalButton.classList.remove('d-none'):
        addJournalButton.classList.remove('d-none');
        
    }

    const changeDateInputFunc = (value) => {
        if (value.getAttribute('id') == 'date-input') {
            journalData.dateInput = toDateFormat(value.value);
            return;
        }

        if (value.getAttribute('id') == 'filter-start-date') {
            date_from = toDateFormat(value.value);
            return;
        }

        if (value.getAttribute('id') == 'filter-end-date') {
            date_to = toDateFormat(value.value);
            return;
        }
        
    }

    async function submitJournalFunction(){
        await axios.post('/api/journal/create', journalData)
        .then(async res => {
            let result = res.data.data;
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `${result.description} Berhasil Diubah`,
                showConfirmButton: false,
                timer: 1000
              });
            await countJournalFunc();
            await showJournalDefault();
        })
        .catch(err => {
            console.log(err);
        })
        setInputDefault();
    }

// end input section

let countData = 0;
let page = 0;
let journals = [];
let scroll = 0;
let rowOrder = 0;

const countJournal = document.getElementById('count-journal');

const countJournalFunc = async () => {
    await axios.get(`/api/journal/count?outlet_id=${createJournalButton.dataset.outletId}&search=${search}&date_from=${date_from}&date_to=${date_to}&this_week=${thisWeek}&this_month=${thisMonth}&this_year=${thisYear}`)
    .then(res => {
        countData = res.data.data;
        countJournal.innerHTML = countData;
    }).catch(err => {
        console.log(err);
    })
}

const searchJournalButton = (e) => {
    e.preventDefault();
    showJournalDefault();
    countJournalFunc();
}

const showJournal = async () => {
    const journalTableBody = document.getElementById('journal-table-body');

    let list = '';
    let url = `/api/journal/get-journals?outlet_id=${createJournalButton.dataset.outletId}&page=${page}&search=${search}&date_from=${date_from}&date_to=${date_to}&this_week=${thisWeek}&this_month=${thisMonth}&this_year=${thisYear}`;


    await axios.get(url)
    .then(res => {
        let temp = res.data.data;
        journals = [...journals, ...res.data.data];
        temp.forEach(t => {
            let newRowOrder = rowOrder++;
            list += `
            <tr class="journal-table-row" data-order="${newRowOrder}">
                <td>${t.date}</td>
                <td>${t.reference_no}</td>
                <td>${t.description}</td>
                <td>${t.detail ? t.detail : ''}</td>
                <td class="text-right">
                    ${formatRupiah(t.value.toString())}
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item edit-accounts" data-id="${t.id}" data-toggle="modal" data-target="#detailJournalModal" onclick="showJournalDetail(${t.id})">
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
                            <button class="dropdown-item edit-accounts" data-id="${t.id}" data-order="${newRowOrder}" data-toggle="modal" data-target="#createJournalModal" onclick="editJournal(this)">
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
                            <button class="dropdown-item edit-accounts" data-id="${t.id}" data-name="${t.name}" 
                            data-order="${newRowOrder}" data-toggle="modal" onclick="deleteJournal(this)">
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
            `
        })
    })
    .catch(err => {
        console.log(err);
    })

    if (journals.length < 1) {
        journalTableBody.innerHTML = `
            <td colspan="4" class="text-center font-italic">
                Tidak Ada Data
            </td>`;

        return;
    }

    journalTableBody.innerHTML += list;

}

const showJournalDefault = async () => {
    const journalTableBody = document.getElementById('journal-table-body');

    const searchJournal = document.getElementById('search-journal');

    journalTableBody.innerHTML = '';
    page = 0;
    journals = [];
    scroll = 0;
    rowOrder = 0;
    search = searchJournal.value;

    // dapatkan total data
    await countJournalFunc();

    await showJournal();
}

const journalTableScroll = (value) => {

    if (value.scrollTop > (scroll+100)) {
        scroll += value.scrollHeight - value.scrollTop;
        page++;
        showJournal();
    }

}

const deleteJournal = (value) => {
    Swal.fire({
        title: `Anda Yakin Menghapus Data Jurnal?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/journal/delete/${value.dataset.id}`)
            .then(res => {
                
                // delete row
                const journalTableRows = document.getElementsByClassName('journal-table-row');

                journalTableRows[parseInt(value.dataset.order)].remove();
                countData--;

                const countJournal = document.getElementById('count-journal');
                countJournal.innerHTML = countData;

                // ubah urutan list data 
                for (let i = 0; i < journalTableRows.length; i++) {
                    journalTableRows[i].setAttribute('data-order', i);
                }

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

const editJournal = (value) => {
    const createJournalModalLabel = document.getElementById('createJournalModalLabel');
    createJournalModalLabel.innerHTML = 'Edit Jurnal';

    const editJournalButton = document.getElementById('edit-journal-button');

    editJournalButton.classList.remove('d-none');

    const addJournalButton = document.getElementById('add-journal-button');

    addJournalButton.classList.add('d-none');

    editState = true;
    axios.get(`/api/journal/edit/${value.dataset.id}`)
    .then(res => {
        journalData = {
            id: value.dataset.id,
            order: value.dataset.order,
            outletId:createJournalButton.dataset.outletId,
            dateInput:res.data.data.journal.date,
            descriptionInput:res.data.data.journal.description,
            reference_no:res.data.data.journal.reference_no,
            accountList:res.data.data.ledgers,
        }

        const descriptionInput = document.getElementById('description-input');
        descriptionInput.value = journalData.descriptionInput;

        const referenceNoInput = document.getElementById('reference-no-input');
        referenceNoInput.value = journalData.reference_no;

        const dateInput = document.getElementById('date-input');
        dateInput.value = toDateFormat2(journalData.dateInput);

        let rowList = '';
        let i = 0;
        totalDebit = 0;
        totalCredit = 0;
        journalData.accountList.forEach(t => {
            totalCredit += t.credit;
            totalDebit += t.debit;
            rowList += `
            <tr class="account-table-row">
                <td>
                    <div class="dropdown">
                        <input 
                            class="form-control account-input-dropdown" 
                            type="text" 
                            data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Akun"
                            autocomplete="off"
                            onkeyup="accountDropdownKeyup(this)"
                            onclick="accountDropdownFocus(this)"
                            order="${i}"
                            onchange="balanceDebitCredit(${i})"
                            value="${t.account}"
                        >

                        <div class="dropdown-menu w-100 overflow-auto account-list" style="max-height:180px">
                            
                        </div>
                    </div>
                    
                </td>
                <td class="text-right">
                    <input type="text" class="form-control text-right debit-input" onkeyup="debitInputKeyup(this)"
                    order="${i}" inputmode="numeric" value="${formatRupiah(t.debit.toString())}">
                </td>
                <td class="text-right">
                    <input type="text" class="form-control text-right credit-input" onkeyup="creditInputKeyup(this)"
                    order="${i}" inputmode="numeric" value="${formatRupiah(t.credit.toString())}">
                </td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm remove-row-btn" order="${i}" onclick="removeRowBtnFunc(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            `;

            i++;

        })
        accountTableBody.innerHTML = rowList;

        const totalDebitValue = document.getElementById('total-debit-value');
        totalDebitValue.innerHTML = formatRupiah(totalDebit.toString());
        const totalCreditValue = document.getElementById('total-credit-value');
        totalCreditValue.innerHTML = formatRupiah(totalCredit.toString());
        
    })
    .catch(err => {
        console.log(err);
    })
}

const submitEditJournalFunction = async () => {
    await axios.put(`/api/journal/edit/${journalData.id}`, journalData)
    .then(res => {

        let result = res.data.data;
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `${result.description} Berhasil Diubah`,
            showConfirmButton: false,
            timer: 1000
          });

        let t = res.data.data;
        const journalTableRow = document.getElementsByClassName('journal-table-row')[parseInt(journalData.order)];

        journalTableRow.innerHTML = `
            <td>${t.date}</td>
            <td>${t.reference_no}</td>
            <td>${t.description}</td>
            <td class="text-right">
                ${formatRupiah(t.value.toString())}
            </td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item edit-accounts" data-id="${t.id}" data-toggle="modal" data-target="#detailJournalModal" onclick="showJournalDetail(${t.id})">
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
                        <button class="dropdown-item edit-accounts" data-id="${t.id}" data-order="${journalData.order}" data-toggle="modal" data-target="#createJournalModal" onclick="editJournal(this)">
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
                        <button class="dropdown-item edit-accounts" data-id="${t.id}" data-name="${t.name}"
                        data-order="${journalData.order}" data-toggle="modal" onclick="deleteJournal(this)">
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
        `;

    })
    .catch(err => {
        console.log(err);
    })
}

const showJournalDetail = async (id) => {
    await axios.get(`/api/journal/get-journal/${id}`)
                .then(res => {
                    let result = res.data.data;
                    const journalDetailDate = document.querySelector('#journal-detail-date');
                    journalDetailDate.innerHTML = `: ${result.journal.date}`;

                    const journalDetailDescription = document.querySelector('#journal-detail-description');
                    journalDetailDescription.innerHTML = `: ${result.journal.description}`;

                    const journalDetailReferenceNo = document.querySelector('#journal-detail-reference-no');
                    journalDetailReferenceNo.innerHTML = `: ${result.journal.reference_no}`;

                    const journalDetailBody = document.querySelector('#journal-detail-body');

                    let list = '';

                    result.ledgers.forEach((ledger, i) => {
                        list += `
                        <tr>
                            <td>${ledger.account.code}</td>
                            <td>${ledger.account.name}</td>
                            <td class="text-right">Rp. ${formatRupiah(ledger.debit.toString())}</td>
                            <td class="text-right">Rp. ${formatRupiah(ledger.credit.toString())}</td>
                        </tr>
                        `
                    });

                    journalDetailBody.innerHTML = list;
                    
                    const journalDetailDebit = document.querySelector('#journal-detail-debit');
                    journalDetailDebit.innerHTML = `Rp. ${formatRupiah(result.journal.value.toString())}`;

                    const journalDetailCredit = document.querySelector('#journal-detail-credit');
                    journalDetailCredit.innerHTML = `Rp. ${formatRupiah(result.journal.value.toString())}`;
                    
                })
                .catch(err => {
                    console.log(err);
                })
}

const handleResetFilter = () => {
    date_from = '';
    date_to = '';
    thisWeek = '';
    thisMonth = '';
    thisYear = '';
    filterValue = 0;

    // const filterDate = document.querySelector('#filter-date');

    // filterDate.value = filterValue;

    showJournalDefault();
}

const changeDateFilter = (value) => {
    const filterSubmit = document.querySelector('#filter-submit');
    const resetFilter = document.querySelector('#reset-filter');
    const customDateFilter = document.querySelector('#custom-date-filter');
    const filterStartDate = document.querySelector('#filter-start-date');
    const filterEndDate = document.querySelector('#filter-end-date');

    filterEndDate.value = '';
    filterStartDate.value = '';

    if(value.value == 1) {
        let time = waktu(true);
        let date = time.split(' ')[0];
        date_from = date;
        date_to = date;
        thisWeek = '';
        thisMonth = '';
        thisYear = '';
        
        filterSubmit.classList.remove('d-none');
        resetFilter.classList.remove('d-none');
        customDateFilter.classList.add('d-none');
        return;
    }
    if (value.value == 2) {
        date_from = '';
        date_to = '';
        thisWeek = 1;
        thisMonth = '';
        thisYear = '';

        customDateFilter.classList.add('d-none');
        filterSubmit.classList.remove('d-none');
        resetFilter.classList.remove('d-none');
        return;

    }   
    if (value.value == 3) {
        date_from = '';
        date_to = '';
        thisWeek = '';
        thisMonth = 1;
        thisYear = '';

        customDateFilter.classList.add('d-none');
        filterSubmit.classList.remove('d-none');
        resetFilter.classList.remove('d-none');
        return;

    }   

    if (value.value == 4) {
        date_from = '';
        date_to = '';
        thisWeek = '';
        thisMonth = '';
        thisYear = 1;

        customDateFilter.classList.add('d-none');
        filterSubmit.classList.remove('d-none');
        resetFilter.classList.remove('d-none');
        return;

    }   

    if (value.value == 5) {
        
        customDateFilter.classList.remove('d-none');
        date_from = '';
        date_to = '';
        thisWeek = '';
        thisMonth = '';
        thisYear = '';

        filterSubmit.classList.remove('d-none');
        resetFilter.classList.remove('d-none');
        return;

    }   
    date_from = '';
    date_to = '';

}

const handleFilterSubmit = (e) => {
    const filterDate = document.querySelector('#filter-date');

    filterValue = filterDate.value;
    showJournalDefault();
}

const filterJournalButton = () => {
    const filterDate = document.querySelector('#filter-date');
    const filterSubmit = document.querySelector('#filter-submit');
    const resetFilter = document.querySelector('#reset-filter');
    const customDateFilter = document.querySelector('#custom-date-filter');

    if(filterValue < 1){
        filterSubmit.classList.add('d-none');
        resetFilter.classList.add('d-none');
        customDateFilter.classList.add('d-none');
    }
    if(filterValue > 4){
        customDateFilter.classList.remove('d-none');
    }

    filterDate.value = filterValue;
}

window.addEventListener('load',async function() {
    //dapatkan data 20 row per load
    await showJournalDefault();
})