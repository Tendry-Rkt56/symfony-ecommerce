const connected = document.querySelector('.connected')
const errorMessage = document.getElementById('error-message');
const closeError = document.getElementById('close-error');
const form = document.querySelectorAll('.form')

console.log(connected)

// Fonction pour afficher le message d'erreur
function showError() {
    errorMessage.classList.remove('hidden');
}

// Fonction pour fermer le message d'erreur
closeError.addEventListener('click', () => {
    errorMessage.classList.add('hidden');
});

form.forEach(element => {
     element.addEventListener('submit', (e) => {
          if (!connected) {
               e.preventDefault()
               showError()
          }
     })
})
