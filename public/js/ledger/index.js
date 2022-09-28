// set default value 
let selectAccount = '';
let accountId = '';

let countData = 0;
let page = 0;
let ledgers = [];
let scroll = 0;
let rowOrder = 0;

let date_from = '';
let date_to = '';
let thisWeek = '';
let thisMonth = 1;
let thisYear = '';
let filterValue = 3;

const body = document.querySelector('body');
const searchAccount = document.getElementById('search-account');

body.addEventListener('click', function(e) {
    if (selectAccount) {
        searchAccount.value = selectAccount;
        return;
    }
    searchAccount.value = ``;
})

const ledger = document.querySelector('#ledger');

const getAccount = async (search = '') => {
    const accountDropdownList = document.getElementById('account-dropdown-list');

    // let accounts = document.getElementById('accounts');

    accountDropdownList.innerHTML = `<a class="dropdown-item text-center" href="#" disabled><div class="spinner-border" role="status">
                                    <span class="sr-only"></span>
                                </div></a>`;

    let url = `/api/account/getData?search=${search}&outletId=${ledger.dataset.outletId}&is_active=1`;
    await axios.get(url)
        .then(response => {
            let accountList = response.data.data;

            let list = '';

            accountList.forEach((account, index) => {
                list += `
                <a class="dropdown-item" href="#" onclick="handleSelectAccount(${account.id}, '${account.name}')"><div class="row">
                    <div class="col-12" style="font-size: 10px">
                        ${account.code}
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        ${account.name}
                    </div>
                </div>
                </a>
                `;
            });

            accountDropdownList.innerHTML = list;
            // accounts.innerHTML = list2;

            
        })
        .catch(error => {
            console.log(error);
        })
}

const getBalance = async () => {
    await axios.get(`/api/ledgers/balance?outlet_id=${ledger.dataset.outletId}&account_id=${accountId}&date_from=${date_from}&date_to=${date_to}&end_week=${thisWeek}&end_month=${thisMonth}&end_year=${thisYear}&this_week=${thisWeek}&this_month=${thisMonth}&this_year=${thisYear}`)
        .then(res => {
            const endingBalance = document.querySelector('#ending-balance');
            const openingBalance = document.querySelector('#opening-balance');
            const totalDebit = document.querySelector('#total-debit');
            const totalCredit = document.querySelector('#total-credit');
            const mutation = document.querySelector('#mutation');

            
            let mutationValue = res.data.data.total_debit - res.data.data.total_credit;
            let endingBalanceValue = res.data.data.saldo_akhir;
            let startingBalanceValue = endingBalanceValue - mutationValue;

            openingBalance.innerHTML = `Rp. ${startingBalanceValue < 0 ? "-" : ""}${formatRupiah(startingBalanceValue.toString())}`;

            endingBalance.innerHTML = `Rp. ${endingBalanceValue < 0 ? "-" : ""}${formatRupiah(endingBalanceValue.toString())}`;

            totalDebit.innerHTML = `Rp. ${res.data.data.total_debit < 0 ? "-" : ""}${formatRupiah(res.data.data.total_debit.toString())}`;

            totalCredit.innerHTML = `Rp. ${res.data.data.total_credit < 0 ? "-" : ""}${formatRupiah(res.data.data.total_credit.toString())}`;

            mutation.innerHTML = `Rp. ${mutationValue < 0 ? "-" : ""}${formatRupiah(mutationValue.toString())}`;

        })
        .catch(err => {
            console.log(err);
        })
}

const handleSelectAccount = async (id, name) => {
    selectAccount = name;
    accountId = id;
    searchAccount.value = name;
    ledgers = [];
    const ledgerTableBody  = document.querySelector('#ledger-table-body');
    ledgerTableBody.innerHTML = '';
    page = 0;
    scroll = 0
    await showLedger();
    await getBalance();
    await countLedger();
}

searchAccount.addEventListener('keyup',async function(){
    await getAccount(this.value);
})

searchAccount.addEventListener('focus',async function(){
    await getAccount(this.value);
});

const journalTableScroll = async (value) => {
    if (value.scrollTop > (scroll+100)) {
        scroll += value.scrollHeight - value.scrollTop;
        page++;
        await showLedger();
    }
}

const countLedger = async () => {
    await axios.get(`/api/count-ledger?outlet_id=${ledger.dataset.outletId}&account_id=${accountId}&date_from=${date_from}&date_to=${date_to}&this_week=${thisWeek}&this_month=${thisMonth}&this_year=${thisYear}`)
            .then(res => {
                const countLedger = document.querySelector('#count-ledger');
                countLedger.innerHTML = res.data.data;
            })
            .catch(err => {
                console.log(err);
            })
}

const showLedger = async () => {
    const ledgerTableBody  = document.querySelector('#ledger-table-body');

    let url = `/api/ledgers?outlet_id=${ledger.dataset.outletId}&page=${page}&account_id=${accountId}&date_from=${date_from}&date_to=${date_to}&this_week=${thisWeek}&this_month=${thisMonth}&this_year=${thisYear}`;

    await axios.get(url)
    .then( res => {
        let temp = res.data.data;
        ledgers = [...ledgers, ...temp];

        if (ledgers.length < 1) {
            ledgerTableBody.innerHTML = `
                <td colspan="6" class="text-center font-italic">
                    Tidak Ada Data
                </td>`;

            return;
        }

        let list = '';
        temp.forEach( (ledger, index) => {
            list += `
            <tr>
                <td>${ledger.date}</td>
                <td>${ledger.no_ref}</td>
                <td>${ledger.description}</td>
                <td class="text-right">${formatRupiah(ledger.debit.toString())}</td>
                <td class="text-right">${formatRupiah(ledger.credit.toString())}</td>
            </tr>`
        })

        ledgerTableBody.innerHTML += list;

    })
    .catch( err => {
        console.log(err);
    } )
}

const changeDateFilter = (value) => {
    const filterSubmit = document.querySelector('#filter-submit');
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
        return;

    }   
    date_from = '';
    date_to = '';

}

const handleFilterSubmit =async (e) => {
    const filterDate = document.querySelector('#filter-date');

    filterValue = filterDate.value;
    ledgers = [];
    const ledgerTableBody  = document.querySelector('#ledger-table-body');
    ledgerTableBody.innerHTML = '';
    page = 0;
    scroll = 0;
    await showLedger();
    await getBalance();
    await countLedger();

}

const filterLedgerButton = () => {
    const filterDate = document.querySelector('#filter-date');
    const filterSubmit = document.querySelector('#filter-submit');
    const customDateFilter = document.querySelector('#custom-date-filter');

    if(filterValue < 1){
        filterSubmit.classList.add('d-none');
        customDateFilter.classList.add('d-none');
    }
    if(filterValue > 4){
        customDateFilter.classList.remove('d-none');
    }

    filterDate.value = filterValue;
}

const changeDateInputFunc = (value) => {

    if (value.getAttribute('id') == 'filter-start-date') {
        date_from = toDateFormat(value.value);
        return;
    }

    if (value.getAttribute('id') == 'filter-end-date') {
        date_to = toDateFormat(value.value);
        return;
    }
    
    
}

window.addEventListener('load',async () => {
    await getAccount();
})