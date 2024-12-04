const select = document.getElementById('select')

select.addEventListener('change', (e) => {
     if (e.target.value) window.location.href = e.target.value
})