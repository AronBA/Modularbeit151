document.querySelector('.select-field').addEventListener('click',()=>{
    document.querySelector('.list').classList.toggle('show');
    document.querySelector('.down-arrow').classList.toggle('rotate180');

});
function outputUpdate(vol) {
    document.querySelector('#progresso').value = vol + "%";
}
function outputUpdate2(vol) {
    document.querySelector('#priorityo').value = vol + " out of 5" ;
}