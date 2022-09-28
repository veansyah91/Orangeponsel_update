//state
let search = '';
let is_paid = true;

const defineComponentElement = _ =>{
    const accountReveivableContainer = document.querySelector('#account-receivable-container');
    const accountReceivableListTable = document.querySelector('#account-receivable-list-table');
    const searchAccountReceivable = document.querySelector('#search-account-receivable');
    const accountReceivableDetailListTable = document.querySelector('#account-receivable-detail-list-table'); 
    const paidStatus = document.querySelector('#paid-status');
    const countData = document.querySelector('#count-data');

    return {
        accountReveivableContainer,
        accountReceivableListTable,
        searchAccountReceivable,
        accountReceivableDetailListTable,
        paidStatus, countData
    }
}

const setDefault = _ => {
    const { paidStatus } = defineComponentElement();
    search = '';
    is_paid = true;

    paidStatus.setAttribute('checked', 'checked');
}

const submitSearchAccountReceivable = async event => {
    event.preventDefault();

    const { searchAccountReceivable, accountReceivableListTable } = defineComponentElement();

    search = searchAccountReceivable.value;

    accountReceivableListTable.innerHTML = `<tr class="journal-table-row">
                            <td colspan="4" class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </td>
                        </tr>`;

    await showData();
}

const showAccountReceivableDetail = async (id) => {
    let url = `/api/account-receivable/${id}/detail?is_paid=${is_paid}`;

    await axios.get(url)
                .then(res => {
                    const { accountReceivableDetailListTable } = defineComponentElement();

                    let list = '';
                    let details = res.data.data;

                    details.map(detail => {
                        list += `<tr>
                            <td>${detail.ref}</td>
                            <td class="text-right">Rp.${formatRupiah((detail.debit - detail.credit).toString())}</td>
                            <td class="text-center">${dateReadable(detail.date)}</td>
                        </tr>`
                    })

                    accountReceivableDetailListTable.innerHTML = list;
                })
                .catch(err => {
                    console.log(err);
                })
}

const showData = async _=> {
    const { accountReveivableContainer, accountReceivableListTable, countData} = defineComponentElement();

    let url = `/api/account-receivable?outlet_id=${accountReveivableContainer.dataset.outletId}&search=${search}&${is_paid ? 'is_paid=true' : ''}`;

    accountReceivableListTable.innerHTML = `<tr class="journal-table-row">
                        <td colspan="4" class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </td>
                    </tr>`

    await axios.get(url)
            .then((result) => {
                
                let accountReceivableLists = result.data.data;
                accountReceivableListTable.innerHTML = '';

                countData.innerHTML = accountReceivableLists.length

                if (accountReceivableLists.length > 0) {
                    let list = '';
                    accountReceivableLists.map(accountReceivableList => {
                        list += `<tr>
                        <td>${accountReceivableList.customer_name}</td>
                        <td class="text-right">Rp. ${formatRupiah(accountReceivableList.balance.toString())}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    </svg>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item account-receivable-detail" data-id="${accountReceivableList.id}" data-toggle="modal" data-target="#detailAccountReceivableModal" onclick="showAccountReceivableDetail(${accountReceivableList.id})">
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
                                </div>
                            </div>
                        </td>
                    </tr>`
                    })

                    accountReceivableListTable.innerHTML = list;
                }
                else {
                    accountReceivableListTable.innerHTML = `
                        <tr class="journal-table-row">
                                <td colspan="4" class="text-center">
                                    Tidak Ada Data
                                </td>
                        </tr>`
                }

                
            }).catch((err) => {
                console.log(err);
            });
}

const handleShowPaidStatus = async () => {
    is_paid = !is_paid;
    await showData();
}

window.addEventListener('load', async function(){
    await showData();
    setDefault();
})