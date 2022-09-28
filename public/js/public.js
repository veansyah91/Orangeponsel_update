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

function abbreviation(str) {
    let strArr = str.split(' ');
    let result = '';
    for (let i = 0; i < strArr.length; i++) {
        result += strArr[i].slice(0,1);
    }
    return result; 
}

const waktu = (dateInputType=false) => {
    date = new Date();
    millisecond = date.getMilliseconds();
    detik = date.getSeconds();
    menit = date.getMinutes();
    jam = date.getHours();
    hari = date.getDay();
    tanggal = date.getDate();
    bulan = date.getMonth();
    tahun = date.getFullYear();
    return dateInputType ? `${tahun}-${bulan+1}-${tanggal} ${jam}:${menit}:${detik}` : `${tanggal}/${bulan+1}/${tahun} ${jam}:${menit}:${detik}`;
}

const toDateFormat = (date) => {
    let dateArr = date.split('/');
    return `${dateArr[2]}-${dateArr[1]}-${dateArr[0]}`;
}

const toDateFormat2 = (date) => {
    let dateArr = date.split('-');
    return `${dateArr[2]}/${dateArr[1]}/${dateArr[0]}`;
}

const dateFormatToSaveIntoDatabase = (date) => {
    let dateArr = date.split('/');
    return `${dateArr[2]}-${dateArr[1]}-${dateArr[0]}`;
}

const monthReadable = (m) => {
    switch (parseInt(m)) {
        case 1:
            return 'Januari'
            break;
        case 2:
            return 'Februari'
            break;
        case 3:
            return 'Maret'
            break;
        case 4:
            return 'April'
            break;
        case 5:
            return 'Mei'
            break;
        case 6:
            return 'Juni'
            break;
        case 7:
            return 'Juli'
            break;
        case 8:
            return 'Agustus'
            break;
        case 9:
            return 'September'
            break;
        case 10:
            return 'Oktober'
            break;
        case 11:
            return 'November'
            break;
        default:
            return 'Desember'
            break;
    }
}

const dateReadable = (date) => {
    let dateArr = date.split('-');

    let year = dateArr[0];

    let month = monthReadable(dateArr[1]);

    let day = dateArr[2];

    return `${day} ${month} ${year}`
}

